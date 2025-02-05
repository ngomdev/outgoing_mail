<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\FirebaseNotificationResource;

class FirebaseNotificationController extends Controller
{


    public function index(Request $request)
    {
        $auth = $request->user();

        if (!$auth->tokenCan("firebase-notifications:get")) {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à accéder à cette ressource."
            ], 403);
        }

        $authFirebaseNotifications = $auth->firebaseNotifications()
            ->orderByDesc('created_at')
            ->get();

        $paginated = PaginationHelper::paginate($authFirebaseNotifications, 10);

        return FirebaseNotificationResource::collection($paginated);
    }


    public function update(Request $request, $id)
    {
        $auth = $request->user();

        $notification = $auth->firebaseNotifications()->find($id);

        if (!$notification) {
            return response()->json([
                "code" => "ERROR",
                "message" => "Une erreur s'est produite. Réessayer plus tard."
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            "code" => "SUCCESS",
            "message" => "Notification marqué comme lu"
        ], 200);
    }

    public function markAllAsRead(Request $request)
    {
        $auth = $request->user();

        $notifications = $auth->firebaseNotifications;

        $notifications->each(function ($notification) {
            $notification->markAsRead();
        });

        return response()->json([
            "code" => "SUCCESS",
            "message" => "Toutes les notifications ont été marquées comme lu"
        ], 200);
    }

    public function deleteAllNotifications(Request $request)
    {
        $auth = $request->user();

        $notifications = $auth->firebaseNotifications;

        $notifications->each(function ($notification) {
            $notification->delete();
        });

        return response()->json([
            "code" => "SUCCESS",
            "message" => "Toutes les notifications ont été supprimés."
        ], 200);
    }
}
