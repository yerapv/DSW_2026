<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retornar todos los usuarios en formato JSON
        return response()->json(Usuario::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validar los datos de entrada
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:8',
        ]);

        // crear un nuevo usuario con los datos recibidos en la solicitud, devuelve un objeto
        $usuario = Usuario::create($request->all());

        // insert devuelve un booleano indicando si la operación fue exitosa o no

        //$usuarioInsertado = Usuario::insert($request->all());

        // Retornar una respuesta JSON indicando que el usuario fue creado
        return response()->json(['mensaje' => 'Usuario creado', 'usuario' => $usuario], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        // Verificar si el usuario existe
        if ($usuario == null || !$usuario) {
            // Retornar una respuesta JSON indicando que el usuario no fue encontrado
            return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
        }
        // Retornar el usuario encontrado
        return Usuario::find($usuario->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        // Validar los datos de entrada
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $usuario->id,
            'password' => 'required|string|min:8',
        ]);

        // Actualizar el usuario con los datos validados
        $usuario->update($request->all());

        // Retornar una respuesta JSON indicando que el usuario fue actualizado
        return response()->json(['mensaje' => 'Usuario actualizado'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        // Eliminar el usuario
        $usuario->delete();

        return response()->json(['mensaje' => 'Usuario eliminado'], 200);
    }
}
