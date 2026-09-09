<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Passport;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
        'scopes'   => 'sometimes|array',
    ]);

    $scopes = $request->input('scopes', ['productos.read']);

    $response = Http::post(config('app.url').'/oauth/token', [
        'grant_type'    => 'password',
        'client_id'     => env('PASSPORT_PASSWORD_CLIENT_ID'),
        'client_secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'),
        'username'      => $request->email,
        'password'      => $request->password,
        'scope'         => implode(' ', $scopes),
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
        $tokenId = $request->user()->token()->id;

        // Revocar el access token actual
        $request->user()->token()->revoke();

        // Revocar también el refresh token asociado
        Passport::refreshToken()
            ->where('access_token_id', $tokenId)
            ->update(['revoked' => true]);

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}
