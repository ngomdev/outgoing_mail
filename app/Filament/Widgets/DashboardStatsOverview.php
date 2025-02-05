<?php

namespace App\Filament\Widgets;

use App\Enums\RoleEnum;
use App\Models\Setting;
use App\Enums\DocStatus;
use App\Models\Document;
use App\Enums\DocUrgency;
use App\Enums\SettingKeys;
use App\Models\CourierUser;
use App\Enums\CourierStatus;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DashboardStatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    private $startDate;

    private $endDate;

    protected static ?int $sort = -3;

    protected static bool $isLazy = false;

    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {

        $this->startDate = Carbon::createFromFormat('Y-m-d', $this->filters['start_date'])->startOfDay();

        $this->endDate = Carbon::createFromFormat('Y-m-d', $this->filters['end_date'])->endOfDay();

        $user = auth()->user();

        if ($user->can(['view_any_document::module::document', 'view_document::module::document']) && $user->can(['view_any_courier::module::courier', 'view_courier::module::courier'])) {
            return array_merge($this->getDocumentsStats(), $this->getCouriersStats());
        }

        if ($user->can(['view_any_document::module::document', 'view_document::module::document'])) {
            return $this->getDocumentsStats();
        }

        if ($user->can(['view_any_courier::module::courier', 'view_courier::module::courier'])) {
            return $this->getCouriersStats();
        }

        return [];
    }


    private function getDocumentsStats()
    {
        // documents
        $totalDocuments = 0;
        $countsDocsToValidate = 0;
        $countsDocsToSign = 0;
        $countsLateDocs = 0;

        $countsUrgentDocs = 0;
        $countsCriticalDocs = 0;
        $countsNormalDocs = 0;

        $countsCriticalDocsToValidate = 0;
        $countsUrgentDocsToValidate = 0;
        $countsNormalDocsToValidate = 0;

        $countsCriticalDocsToSign = 0;
        $countsUrgentDocsToSign = 0;
        $countsNormalDocsToSign = 0;

        $auth = auth()->user();

        $countsPerStatusAndUrgency = collect();

        $query = Document::query()
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', '!=', DocStatus::CANCELLED);

        // get late documents
        $lateDocs = Document::query()
            ->whereIn('status', [DocStatus::INITIATED, DocStatus::VALIDATING])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();


        if (!$auth->hasAnyRole([RoleEnum::RES_SUIVI->getLabel(), RoleEnum::AG->getLabel(), RoleEnum::ADMIN->getLabel(), RoleEnum::SUPER_ADMIN->getLabel()])) {
            $query = $query->whereHas('documentUsers', function ($q) use (&$auth) {
                $q->where('user_id', $auth->id);
            });
        }



        // late documents count
        $lateDocs->each(function ($doc) use (&$countsLateDocs) {
            $requestDate = Carbon::parse($doc->currentValidator->action_request_date);
            $delayExpirationDate = $requestDate->addHours($doc->doc_urgency->getValue());
            if ($delayExpirationDate <= now()) {
                $countsLateDocs++;
            }
        });

        $countsPerDocUrgency = $query->groupBy('status', 'doc_urgency')
            ->selectRaw('status, doc_urgency, count(*) as count')
            ->get();


        if ($countsPerDocUrgency->isNotEmpty()) {
            $countsPerStatus = $countsPerDocUrgency->groupBy('status');

            $countsPerStatus->each(function ($documents, $status) use (&$countsPerStatusAndUrgency) {

                $countsByUrgency = [
                    'normal_count' => $documents->firstWhere('doc_urgency', DocUrgency::NORMAL)?->count ?? 0,
                    'urgent_count' => $documents->firstWhere('doc_urgency', DocUrgency::URGENT)?->count ?? 0,
                    'critical_count' => $documents->firstWhere('doc_urgency', DocUrgency::CRITICAL)?->count ?? 0,
                    'total_count' => $documents->sum('count')
                ];

                $countsPerStatusAndUrgency->put(DocStatus::from($status)->getLabel(), $countsByUrgency);
            });

            $totalDocuments = $countsPerStatusAndUrgency->sum(fn ($item) => $item['total_count']);

            $countsCriticalDocs = $countsPerStatusAndUrgency->sum(fn ($item) => $item['critical_count']);
            $countsUrgentDocs = $countsPerStatusAndUrgency->sum(fn ($item) => $item['urgent_count']);
            $countsNormalDocs = $countsPerStatusAndUrgency->sum(fn ($item) => $item['normal_count']);

            $docsToValidate = $countsPerStatusAndUrgency->filter(function ($item, $status) {
                return $status === DocStatus::INITIATED->getLabel() || $status === DocStatus::VALIDATING->getLabel();
            });

            $countsDocsToValidate = $docsToValidate->sum('total_count');
            $countsCriticalDocsToValidate = $docsToValidate->sum('critical_count');
            $countsUrgentDocsToValidate = $docsToValidate->sum('urgent_count');
            $countsNormalDocsToValidate = $docsToValidate->sum('normal_count');

            $docsToSign = $countsPerStatusAndUrgency->filter(function ($item, $status) {
                return $status === DocStatus::VALIDATED->getLabel();
            });

            $countsDocsToSign = $docsToSign->sum('total_count');
            $countsCriticalDocsToSign = $docsToSign->sum('critical_count');
            $countsUrgentDocsToSign = $docsToSign->sum('urgent_count');
            $countsNormalDocsToSign = $docsToSign->sum('normal_count');
        }

        return [
            Stat::make(
                label: 'Total Documents',
                value: $totalDocuments,
            )
                ->description(new HtmlString("<span class='text-danger-500'>Très urgent: $countsCriticalDocs</span><br/><span class='text-amber-500'>Urgent: $countsUrgentDocs</span><br/><span class='text-primary-500'>Normal: $countsNormalDocs</span>"))
                ->color('primary'),
            Stat::make(
                label: new HtmlString("Documents à valider (<span class='text-danger-500'>$countsLateDocs en retard</span>)"),
                value: $countsDocsToValidate,
            )
                ->description(new HtmlString("<span class='text-danger-500'>Très urgent: $countsCriticalDocsToValidate</span><br/><span class='text-amber-500'>Urgent: $countsUrgentDocsToValidate</span><br/><span class='text-primary-500'>Normal: $countsNormalDocsToValidate</span>"))
                ->color('warning'),
            Stat::make(
                label: 'Documents à signer',
                value: $countsDocsToSign,
            )
                ->description(new HtmlString("<span class='text-danger-500'>Très urgent: $countsCriticalDocsToSign</span><br/><span class='text-amber-500'>Urgent: $countsUrgentDocsToSign</span><br/><span class='text-primary-500'>Normal: $countsNormalDocsToSign</span>"))
                ->color('success'),
        ];
    }

    private function getCouriersStats()
    {
        $totalCouriers = 0;
        $countsCouriersToPickup = 0;
        $countsCouriersToDeliver = 0;

        $countsLateCouriers = 0;

        $query = CourierUser::query()
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', '!=', CourierStatus::CANCELLED);

        //get late couriers
        $couriersToPickup =  CourierUser::query()
            ->where('status', CourierStatus::INITIATED)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get(['assignment_date']);


        //late couriers count

        $couriersToPickup->each(function ($courier) use (&$countsLateCouriers) {
            $pickupDelaySetting = Setting::where(
                'key',
                SettingKeys::COURIER_RECOVERY_DELAY,
            )->first();

            $pickupDelay = $pickupDelaySetting
                ? ($pickupDelaySetting->is_active
                    ? (int) $pickupDelaySetting->value
                    : (int) $pickupDelaySetting->default_value)
                : 48;

            $delayExpirationDate = $courier->assignment_date->addHours($pickupDelay);

            if ($delayExpirationDate <= now()) {
                $countsLateCouriers++;
            }
        });

        $countsCouriersByStatus = $query->groupBy('status')
            ->selectRaw('status,count(*) as count')
            ->pluck('count', 'status');

        if ($countsCouriersByStatus) {

            $totalCouriers = $countsCouriersByStatus->values()->sum();

            $countsCouriersToPickup = $countsCouriersByStatus[CourierStatus::INITIATED->value] ?? 0;

            $countsCouriersToDeliver = $countsCouriersByStatus[CourierStatus::RETRIEVED->value] ?? 0;
        }


        return [
            Stat::make(
                label: 'Total Courriers',
                value: $totalCouriers,
            )
                ->description('Nombre total de courriers')
                ->color('primary'),
            Stat::make(
                label: new HtmlString("Courriers à récupèrer (<span class='text-danger-500'>$countsLateCouriers en retard</span>)"),
                value: $countsCouriersToPickup,
            )
                ->description('Nombre de courriers à récupèrer')
                ->color('warning'),
            Stat::make(
                label: 'Courriers à distribuer',
                value: $countsCouriersToDeliver,
            )
                ->description('Nombre de courriers à distribuer')
                ->color('success'),

        ];
    }
}
