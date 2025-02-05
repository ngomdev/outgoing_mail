<?php

namespace App\Http\Controllers\API\V1\SecurityModule;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PasswordResetCode;
use App\Http\Controllers\Controller;
use App\Services\FirebaseNotificationService;
use App\Notifications\PasswordResetNotification;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Validation\Rules\Password as PasswordRule;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class PasswordResetController extends Controller
{
    use WithRateLimiting;

    public function verifyUser(Request $request)
    {

        try {
            $this->rateLimit(3, 300); // Allow 3 attempts every 5 minutes (300 seconds)

            $request->validate([
                'login' => 'required',
            ]);

            if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
                $request->validate([
                    'login' => 'email',
                ]);
            } else {
                $request->validate([
                    'login' => ['regex:/^(77|78|76|75|70)[0-9]{7}$/'],
                ]);
            }

            $user = User::where('phone', 'like', '%' .$request->login)->orWhere('email', $request->login)->first();

            if (!$user) {
                return response()->json([
                    'code' => 'ERROR',
                    'message' => "Email ou telephone invalide."
                ], 404);
            }

            //check if user is blocked
            if (!$user->is_active) {
                return response([
                    "code" => "ERR_LOGIN_BLOCKED_USER",
                    "message" => "Votre compte est inactif. Veuillez contacter un administrateur."
                ], 403);
            }


            // Delete user old password reset codes
            $user->passwordResetCodes()->where('expired_at', '<=', now())->delete();

            $expiration = now()->addMinutes(15);

            $validCode = $user->passwordResetCodes()->where([
                ['is_active', true],
                ['expired_at', '>', now()]
            ])->first();

            if (!$validCode) {
                // Generate a random four-digit code
                $code = random_int(1000, 9999);
                // Now create new code for user
                $user->passwordResetCodes()->create([
                    'code' => $code,
                    'expired_at' => $expiration
                ]);
            } else {
                // Get code
                $code = $validCode->code;
                // update expiration date
                $validCode->update([
                    'expired_at' => $expiration
                ]);
            }

            $expirationDate = Carbon::parse($expiration);

            if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
                // Send email to user
                $user->notify(new PasswordResetNotification($code, $expirationDate));
                $message = "Un email pour réinitialiser votre mot de passe vous a été envoyé!\nConsultez votre boîte mail.";
            } else {
                // Send sms to user
                $remainingTime = ceil(now()->diffInMinutes($expirationDate));

                $smsBody = "Bonjour $user->name.\\nVous avez demandé la réinitialisation de votre mot de passe.\\nPour procéder à cette opération, veuillez utiliser le code suivant :\\nCode de réinitialisation : $code \\nCe code expirera dans $remainingTime minutes.\\n Veuillez ne pas partager ce code avec d'autres personnes.\\nSi vous n'avez pas demandé cette réinitialisation, veuillez ignorer ce message.";

                (new FirebaseNotificationService())->sendSms($user->phone, $smsBody);

                $message = "Un sms pour réinitialiser votre mot de passe vous a été envoyé!\nConsultez vos messages.";
            }

            return response()->json([
                'code' => 'SUCCESS',
                'message' => $message,
                'remainingTime' =>  ceil(now()->diffInSeconds($expirationDate))
            ], 200);
        } catch (TooManyRequestsException $e) {
            // Handle the rate limiting error
            $minutes = ceil($e->secondsUntilAvailable / 60);
            return response()->json([
                'code' => 'ERROR',
                'message' => "Trop de tentatives. Réessayez dans {$minutes} minute(s).",
            ], 429);
        }
    }

    public function verifyCode(Request $request)
    {

        try {
            $this->rateLimit(3, 300); // Allow 3 attempts every 5 minutes (300 seconds)

            $request->validate([
                'code' => [
                    'required',
                    'digits:4',
                    'numeric'
                ]
            ]);

            // Check if code exists
            $passwordResetCode = PasswordResetCode::firstWhere('code', $request->code);

            if (!$passwordResetCode) {
                return response()->json([
                    'code' => 'ERROR',
                    'message' => "Code invalide."
                ], 404);
            }

            if ($passwordResetCode->expired_at <= now()) {
                return response()->json([
                    'code' => 'ERROR',
                    'message' => "Code expiré."
                ], 422);
            }

            return response()->json([
                'code' => 'SUCCESS',
                'message' => "Code valide."
            ], 200);
        } catch (TooManyRequestsException $e) {
            // Handle the rate limiting error
            $minutes = ceil($e->secondsUntilAvailable / 60);
            return response()->json([
                'code' => 'ERROR',
                'message' => "Trop de tentatives. Réessayez dans {$minutes} minute(s).",
            ], 429);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'code' => [
                'required',
            ],
            'password' => [
                'required',
                'max:30',
                'confirmed',
                PasswordRule::default()
                    ->mixedCase()
                    ->symbols()
            ],
        ]);

        $passwordResetCode = PasswordResetCode::firstWhere([
            ['code', $request->code],
            ['expired_at', '>', now()]
        ]);

        if (!$passwordResetCode) {
            return response()->json([
                'code' => 'ERROR',
                'message' => "Code expiré."
            ], 422);
        }
        // get user
        $user = $passwordResetCode->user;

        // update user password
        $user->password = bcrypt($request->password);
        $user->save();

        // delete current code
        $passwordResetCode->delete();

        return response()->json([
            'code' => 'SUCCESS',
            'message' => "Mot de passe réinitialisé avec succés!"
        ], 200);
    }
}
