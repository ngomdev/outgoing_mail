<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\RoleEnum;
use App\Enums\DocStatus;
use App\Models\Document;
use App\Enums\DocUrgency;
use App\Models\CourierUser;
use App\Enums\CourierStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class StatsController extends Controller
{
    public function getCourierStats(Request $request)
    {
        $auth = $request->user();

        if (!$auth->tokenCan("stats:get")) {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à accéder à cette ressource."
            ], 403);
        }

        $request->validate([
            'start_date' => [
                'required',
                'date_format:Y-m-d'
            ],
            "end_date" => [
                'required',
                'date_format:Y-m-d'
            ]
        ]);

        // If validation passes, retrieve start and end dates
        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();

        // Group CourierUser records by status and count them
        $countsByStatus = CourierUser::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', CourierStatus::CANCELLED)
            ->groupBy('status')
            ->selectRaw('status,count(*) as count')
            ->pluck('count', 'status');

        // Return the counts per status
        return response()->json($countsByStatus);
    }


    public function getDocumentStats(Request $request)
    {
        $auth = $request->user();

        if (!$auth->tokenCan("stats:get")) {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à accéder à cette ressource."
            ], 403);
        }

        $request->validate([
            'start_date' => [
                'required',
                'date_format:Y-m-d'
            ],
            "end_date" => [
                'required',
                'date_format:Y-m-d'
            ]
        ]);

        // If validation passes, retrieve start and end dates
        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();

        $countPerStatusAndUrgency = collect();

        $query = Document::query();

        if (!$auth->hasAnyRole([RoleEnum::RES_SUIVI->getLabel(), RoleEnum::AG->getLabel()])) {
            $query = $query->whereHas('documentUsers', function ($q) use ($auth, $query) {
                $q->where('user_id', $auth->id);
            });
        }

        $countsPerDocUrgency = $query
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', DocStatus::CANCELLED)
            ->groupBy('status', 'doc_urgency')
            ->selectRaw('status, doc_urgency, count(*) as count')
            ->get();

        if ($countsPerDocUrgency) {
            $countsPerStatus = $countsPerDocUrgency->groupBy('status');

            $countsPerStatus->each(function ($documents, $status) use ($countPerStatusAndUrgency) {
                $countsByUrgency = [
                    'normal_count' => $documents->firstWhere('doc_urgency', DocUrgency::NORMAL)?->count ?? 0,
                    'urgent_count' => $documents->firstWhere('doc_urgency', DocUrgency::URGENT)?->count ?? 0,
                    'critical_count' => $documents->firstWhere('doc_urgency', DocUrgency::CRITICAL)?->count ?? 0,
                    'total_count' => $documents->sum('count'),
                ];

                $countPerStatusAndUrgency->put($status, $countsByUrgency);
            });
        }

        // Return the counts per status and urgency
        return response()->json($countPerStatusAndUrgency);
    }
}
