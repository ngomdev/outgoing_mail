<?php

namespace App\Http\Controllers\API\V1\CourierModule;

use App\Models\User;
use App\Enums\RoleEnum;
use App\Models\CourierUser;
use App\Enums\CourierStatus;
use App\Enums\RecipientType;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Notifications\CourierDelivered;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\CourierModule\CourierResource;
use App\Http\Requests\CourierModule\FinishDeliveryRequest;
use App\Jobs\FirebaseNotificationJob;

class CourierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $auth = $request->user();

        if (!$auth->tokenCan("couriers:get")) {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à accéder à cette ressource."
            ], 403);
        }

        if ($auth->hasRole(RoleEnum::COURSER->getLabel())) {
            $deliveries = $auth->deliveries
                ->whereNotNull("assignment_date")
                ->whereNotIn("status", [CourierStatus::DRAFT])
                ->sortBy("updated_at");

            if ($request->has("limit")) {
                $deliveries = $deliveries->take($request->limit);
            }
        } elseif ($auth->hasAnyRole([RoleEnum::RES_SUIVI->getLabel()])) {
            $deliveries = CourierUser::whereNotIn("status", [CourierStatus::DRAFT, CourierStatus::CANCELLED])
                ->orderBy("updated_at")
                ->get();

            if ($request->has("limit")) {
                $deliveries = $deliveries->take($request->limit);
            }
        }

        $paginated = PaginationHelper::paginate($deliveries, 10);

        return CourierResource::collection($paginated);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $auth = $request->user();
        if (!$auth->tokenCan("couriers:patch")) {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à éffectuer cette action."
            ], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function finishCourse(FinishDeliveryRequest $request)
    {
        $auth = $request->user();
        if (!$auth->tokenCan("couriers:patch")) {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à éffectuer cette action."
            ], 403);
        }

        $courierUser = CourierUser::find($request->delivery_id);
        $courier = $courierUser->courier;

        $filePath = null;
        $action = null;
        if ($request->status === 'rejected') {
            $newStatus = CourierStatus::REJECTED;
            $action = "rejeté";
        } else {
            $newStatus = CourierStatus::DELIVERED;
            $action = "distribué";

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $extension = $file->extension();
                if ($extension == 'mkv') {
                    $extension = 'mp4';
                }

                $fileName = uniqid() . "-sign.$extension";
                $filePath = Storage::disk('public')->putFileAs(
                    "courier-attachments/$courier->id",
                    $file,
                    $fileName
                );
            }
        }

        $courierUser->update([
            "status" => $newStatus,
            "deposit_date" => now(),
            "signature_path" => $filePath,
            "rejection_motive" => $request->rejection_motive,
            "lat" => $request->lat,
            "lng" => $request->lng
        ]);

        if ($courierUser->type === RecipientType::MAIN) {
            $courier->update([
                "status" => $newStatus
            ]);
        }

        $courierUser->refresh();

        $notifiables = User::where('is_active', true)
            ->whereHas("roles", fn ($q) => $q->where("name", RoleEnum::RES_SUIVI->getLabel()))->get();

        Notification::send($notifiables, new CourierDelivered($courierUser));

        // Send firebase notifications to all responsable suivi users

        FirebaseNotificationJob::dispatch($notifiables, $action, $courierUser);

        return new CourierResource($courierUser);
    }

    public function searchCouriers(Request $request)
    {
        $searchTerm = $request->q;

        $couriers = CourierUser::with('recipient', 'courier', 'contact')
            // recipient
            ->whereHas('recipient', function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('phone', 'like', "%{$searchTerm}%")
                    ->orWhere('address', 'like', "%{$searchTerm}%");
            })
            // courier
            ->orWhereHas('courier', function ($query) use ($searchTerm) {
                $query->where('courier_number', 'like', "%{$searchTerm}%")
                    ->orWhere('object', 'like', "%{$searchTerm}%");
            })
            // contact
            ->orWhereHas('contact', function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('phone', 'like', "%{$searchTerm}%");
            })
            ->get();

        $paginated = PaginationHelper::paginate($couriers, 5)
            ->appends(['q' => $request->q]);

        return CourierResource::collection($paginated);
    }


    public function filterCouriers(Request $request)
    {
        $request->validate([
            'start_date' => [
                'required_with:end_date',
                'date'
            ],
            "end_date" => [
                'required_with:start_date',
                'date'
            ]
        ]);

        $couriers = CourierUser::query()
            ->whereNotIn('status', [CourierStatus::DRAFT, CourierStatus::CANCELLED]);

        if ($request->start_date && $request->end_date) {
            $couriers = $couriers
                ->whereBetween('assignment_date', [$request->start_date, $request->end_date]);
        }

        $couriers = $couriers->get();

        return CourierResource::collection($couriers);
    }
}
