<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::middleware('auth:api')->get('/token-info', function (Request $request) {
    $token = $request->user()->token();

    return response()->json([
        'token_id' => $token->id,
        'scopes'   => $token->scopes,
        'user'     => $request->user()->only('id', 'name', 'email'),
        'expires'  => $token->expires_at,
    ]);
});

Route::get('/productos', function () {
    return response()->json([
        'message' => 'Puedes ver los productos'
    ]);
})->middleware('scope:productos.read');

Route::post('/productos/editar', function () {
    return response()->json([
        'message' => 'Puedes leer y escribir productos'
    ]);
})->middleware('scopes:productos.read,productos.write');

Route::get('/productos/verificar', function (Request $request) {
    if ($request->user()->tokenCan('productos.read')) {
        return response()->json([
            'message' => 'El token tiene permiso para leer productos'
        ]);
    }

    return response()->json([
        'message' => 'El token NO tiene permiso para leer productos'
    ], 403);
})->middleware('auth:api');