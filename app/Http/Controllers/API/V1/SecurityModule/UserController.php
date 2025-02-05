<?php

namespace App\Http\Controllers\API\V1\SecurityModule;

use App\Http\Controllers\Controller;
use App\Http\Resources\SecurityModule\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $auth = $request->user();

        if (!$auth->tokenCan("users:get")) {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à accéder à cette ressource."
            ], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
    public function update(Request $request)
    {
        $user = $request->user();

        if (!$user->tokenCan("users:patch")) {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à accéder à cette ressource."
            ], 403);
        }

        $validated = $request->validate([
            'fcm_token' => ['required', 'string'],
        ]);

        $user->update($validated);

        return response()->json([
            'code' => "SUCCESS",
            'message' => "Utilisateur mis à jour avec succés.",
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Display authenticated user resource
     */
    public function myProfile(Request $request)
    {
        $auth = $request->user();

        if (!$auth->tokenCan("users:get")) {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à accéder à cette ressource."
            ], 403);
        }

        return new UserResource($auth);
    }
}
