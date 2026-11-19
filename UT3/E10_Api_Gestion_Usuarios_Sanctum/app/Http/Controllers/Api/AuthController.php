<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Registrar un usuario nuevo para generar un token
    public function registrar(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    // Iniciar sesión de usuario, si es correcto obtenemos el token

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales no son correctas.'],
            ]);
        }

        $user->tokens()->delete(); // borra tokens anteriores

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login correcto',
            'user' => $user,
            'token' => $token,
        ]);
    }


    // Obtenemos el perfil del usuario con la información del usuario.
    public function perfil(Request $request)
    {
        return response()->json($request->user());
    }

    // Cerrar sesión destruimos el token de validación.
    public function logout(Request $request)
    {
        // Eliminamos todos los tokens access del usuario.
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout correcto',
        ]);
    }
}
