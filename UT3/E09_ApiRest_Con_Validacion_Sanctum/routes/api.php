<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FacturaController;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Añadir usuarios de forma automática
Route::get('registrar', function () {
    abort_unless(app()->environment('local'), 404);

    $credenciales = [
        'email' => "admin@email.com",
        'password' => "password"
    ];

    if (!Auth::attempt($credenciales)) {
        $user = new User();
        $user->name = "Admin";
        $user->email = $credenciales['email'];
        $user->password = Hash::make($credenciales['password']);
        $user->save();
    }

    if (Auth::attempt($credenciales)) {
        $user = Auth::user();
        $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
        $updateToken = $user->createToken('update-token', ['create', 'update']);
        $basicToken = $user->createToken('basic-token', []);

        return [
            'admin' => $adminToken->plainTextToken,
            'update' => $updateToken->plainTextToken,
            'basic' => $basicToken->plainTextToken
        ];
    }
});

// Definir registros de forma manual.

Route::post('registrar', [AuthController::class, 'registrar']);
//Route::get('usuarios', [AuthController::class, 'usuarios']);

Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('clientes', ClienteController::class);
    Route::apiResource('facturas', FacturaController::class);
});
