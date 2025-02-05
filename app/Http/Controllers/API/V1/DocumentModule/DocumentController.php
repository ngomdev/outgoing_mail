<?php

namespace App\Http\Controllers\API\V1\DocumentModule;

use App\Enums\RoleEnum;
use App\Enums\DocStatus;
use App\Models\Document;
use App\Models\CustomRole;
use App\Models\DocumentUser;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Notifications\SendDocActionNotification;
use App\Http\Resources\DocumentModule\AssignmentResource;
use App\Jobs\FirebaseNotificationJob;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $auth = $request->user();

        if (!$auth->tokenCan("documents:get")) {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à accéder à cette ressource."
            ], 403);
        }


        $initiatorRoleId = CustomRole::firstWhere('name', RoleEnum::INITIATOR->getLabel())?->id;

        $assignments = DocumentUser::where([
            ['user_id', $auth->id],
            ['role_id', '!=', $initiatorRoleId]
        ])
            ->whereHas('document', function ($query) {
                $query->whereNotIn('status', [DocStatus::DRAFT, DocStatus::CANCELLED]);
            })
            ->orderBy("updated_at")
            ->get();

        // Remove duplicates
        $uniqueAssignments = $assignments->filter(function ($assignment) use ($initiatorRoleId, $auth) {
            // Check if the user has both signataire and parapheur roles on this document
            $hasBothRoles = $assignment->document->documentUsers()
                ->where([
                    ['user_id', $auth->id],
                    ['role_id', '!=', $initiatorRoleId]
                ])
                ->count() === 2;
            if ($hasBothRoles) {
                return $assignment->role->name === RoleEnum::PARAPHEUR->getLabel();
            }
            return $assignment;
        });

        // Get assignments where user is currentvalidator or has to sign now
        $assignments = $uniqueAssignments->filter(function ($assignment) use ($auth, $request) {
            $document = $assignment->document;
            $signataire = $document->signataires()->where('user_id', $auth->id)->first();

            $requireSignature = !$document->currentValidator && $signataire && $document->status === DocStatus::VALIDATED;

            if ($request->has('viewAll')) {
                $hasValidated = $assignment->user->lastDocValidationHistory($document)?->is_active === true;
                $hasSigned = $signataire && $document->status === DocStatus::SIGNED;

                return $document->currentValidator?->user_id == $auth->id || $hasValidated || $requireSignature || $hasSigned;
            }

            return $document->currentValidator?->user_id == $auth->id || $requireSignature;
        });


        $paginated = PaginationHelper::paginate($assignments, 10);

        if ($request->has('viewAll')) {
            $paginated->withQueryString()->setPath(url()->current() . '?viewAll')->links();
        }

        return AssignmentResource::collection($paginated);
    }



    public function getDocuments(Request $request)
    {
        $auth = $request->user();

        if (!$auth->tokenCan("documents:get") && !$auth->hasAnyRole([RoleEnum::RES_SUIVI->getLabel(), RoleEnum::AG->getLabel()])) {

            // Documents where user is initiator
            $documentUsers = $auth->initiatedDocuments()
                ->whereHas('document', function ($query) {
                    $query->whereNotIn('status', [DocStatus::DRAFT, DocStatus::CANCELLED]);
                })
                ->whereNotNull('action_request_date')
                ->orderBy("updated_at")
                ->get()
                ->unique('document_id');
            if (!$documentUsers) {
                return response()->json([
                    "message" => "Vous n'êtes pas autorisé à accéder à cette ressource."
                ], 403);
            }
        } else {
            // Return all documents
            $documentUsers = DocumentUser::whereHas('document', function ($query) {
                $query->whereNotIn('status', [DocStatus::DRAFT, DocStatus::CANCELLED]);
            })
                ->whereNotNull('action_request_date')
                ->orderBy("updated_at")
                ->get()
                ->unique('document_id');
        }

        $paginated = PaginationHelper::paginate($documentUsers, 10);

        return AssignmentResource::collection($paginated);
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
        if (!$auth->tokenCan("documents:patch")) {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à éffectuer cette action."
            ], 403);
        }
    }


    public function passTurn(Request $request)
    {
        $request->validate([
            'document_id' => [
                "required",
                "exists:documents,id"
            ],
        ]);

        $auth = $request->user();

        $document = Document::find($request->document_id);

        if (!$document) {
            return response()->json([
                "code" => "Error",
                "message" => "Document introuvable!"
            ], 404);
        }

        if ($document->currentValidator->user->id !== $auth->id) {
            return response()->json([
                "code" => "Error",
                "message" => "Document introuvable!"
            ], 404);
        }

        if (!$document->nextValidator) {
            return response()->json([
                "code" => "Error",
                "message" => "Vous ne pouvez pas passer votre tour car vous etes le seul validateur restant."
            ], 422);
        }

        $document->currentValidator->moveToEnd();

        $document->currentValidator->update(
            [
                'action_request_date' => now()
            ]
        );

        // TODO Send Notification to next validator
        $user = $document->currentValidator->user;
        if ($user->is_active) {
            $user->notify(new SendDocActionNotification($document->currentValidator->user->name, $document));
            // Send firebase notification to user for validation action
            FirebaseNotificationJob::dispatch([$document->currentValidator], "validation");
        }

        return response()->json([
            "code" => "Success",
            "message" => "Vous venez de passer votre tour au prochain sur la liste."
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
