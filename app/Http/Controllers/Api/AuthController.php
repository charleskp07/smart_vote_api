<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorMail;
use App\Models\TwoFactor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class AuthController extends Controller
{

    public function tokenLogin()
    {
        return [
            "message" => "Authentification requise pour avoir un token valide.",
        ];
    }


    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        try {

            if (Auth::attempt($data)) {

                $user = Auth::user();

                $code = rand(111111, 999999);

                TwoFactor::where('email', $user->email)->delete();

                TwoFactor::create([
                    'email' => $user->email,
                    'code' => Hash::make($code),
                ]);

                Mail::to($user->email)->send(new TwoFactorMail($user->name, $user->email, $code));

                Auth::logout();

                return response()->json([
                    'success' => true,
                    'message' => "Code envoyer à votre email",
                ], 200);
            }

            return response()->json([
                'success' => false,
                'error' => 'E-mail ou mot de passe invalide•s.',
            ], 400);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement, Réessayez !',
                'error' => $ex->getMessage()
            ], 500);
        }
    }


    public function verifyTwoFactorCode(Request $request)
    {
        $data = [
            'email' => $request->email,
            'code' => $request->code,
        ];

        try {

            $user = User::where('email', $data['email'])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'utilisateur non trouvé',
                ], 400);
            }

            $twoFactorCode = TwoFactor::where('email', $user->email)->first();

            if (!$twoFactorCode) {
                return response()->json([
                    'success' => false,
                    'error' => 'code non trouvé',
                ], 400);
            }

            if (Hash::check($data['code'], $twoFactorCode->code)) {

                $twoFactorCode->delete();

                $user->tokens()->delete();

                Auth::login($user);

                $token = $user->createToken($user->id)->plainTextToken;

                return [
                    'user' => $user,
                    'token' => $token,
                ];
            }

            return response()->json([
                'success' => false,
                'error' => 'code incorrect, veillez réessayer !!!',
            ], 400);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement, Réessayez !',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'utilisateur connecté non trouvé',
                ], 400);
            }

            $user->tokens()->delete();


            return response()->json([
                'success' => true,
                'message' => "Déconnexion réussie.",
            ], 200);

        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement, Réessayez !',
                'error' => $ex->getMessage()
            ], 500);
        }
    }
}
