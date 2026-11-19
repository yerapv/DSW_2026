<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function registrar(Request $request){
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'alias' => ['required', 'string', 'max:255'],
            'permisos' => ['nullable', 'string', 'regex:/^(create|update|delete)(,(create|update|delete))*$/'],
        ]);

        $usuario = User::create(
            [
                'name' => $datos['name'],
                'email' => $datos['email'],
                'password' => $datos['password']
            ]
        );

        $permisos = empty($datos['permisos']) ? [] : explode(',', $datos['permisos']);

        $token = $usuario->createToken($request->alias, $permisos)->plainTextToken;

        return response()->json(['data' => $usuario, 'Token' => $token]);

    }

    public function usuarios(){

        return response()->json(User::all());
    }


}
