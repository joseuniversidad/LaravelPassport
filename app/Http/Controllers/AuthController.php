<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\RefreshToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $response = Http::post(config('app.url') . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*',
        ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        return response()->json($response->json());
    }
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
    public function logout(Request $request)
    {
        $token = $request->user()->token();

        RefreshToken::where('access_token_id', $token->id)
            ->update(['revoked' => true]);

        $token->revoke();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}
