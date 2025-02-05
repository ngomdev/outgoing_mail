<?php

namespace App\Http\Controllers\API\V1;


use App\Http\Resources\SecurityModule\UserResource;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function generateToken(Request $request)
    {
        $fields = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        // check email or nickname
        $user = User::where('email', $fields['login'])
            ->orWhere('phone', 'like', '%' .$fields['login'])
            ->first();

        // check password

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                "code" => "ERR_LOGIN_UNKNOWN_USER",
                "message" => "Identifiant ou mot de passe invalide."
            ], 422);
        } else {

            //check if user is blocked
            if (!$user->is_active) {
                return response([
                    "code" => "ERR_LOGIN_BLOCKED_USER",
                    "message" => "Votre compte est inactif. Veuillez contacter un administrateur."
                ], 403);
            }

            if ($user->hasAnyRole([RoleEnum::SUPER_ADMIN->getLabel(), RoleEnum::ADMIN->getLabel()])) {
                return response([
                    "code" => "ERR_LOGIN_UNAUTHORIZED_USER",
                    "message" => "Accès interdit."
                ], 403);
            }

            $accessToken = null;


            if ($user->hasRole(RoleEnum::COURSER->getLabel())) {
                $accessToken = $user->createToken('access_token', [
                    'users:get',
                    'users:patch',
                    'couriers:get',
                    'couriers:patch',
                    'settings:get',
                    'firebase-notifications:get'
                ])->plainTextToken;
            } elseif ($user->hasRole(RoleEnum::RES_SUIVI->getLabel())) {
                $accessToken = $user->createToken('access_token', [
                    'users:patch',
                    'couriers:get',
                    'documents:get',
                    'settings:get',
                    'stats:get',
                    'firebase-notifications:get'
                ])->plainTextToken;
            } else {
                $accessToken = $user->createToken('access_token', [
                    'users:patch',
                    'documents:get',
                    'settings:get',
                    'stats:get',
                    'firebase-notifications:get'
                ])->plainTextToken;
            }

            return response()->json([
                'code' => 'SUCC_LOGIN_SUCCESS',
                'access_token' => $accessToken,
                'auth' => new UserResource($user)
            ], 202);
        }
    }
}
