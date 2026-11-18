<?php

namespace App\Http\Controllers;

use App\Models\Genero;
use App\Models\Videojuego;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class VideojuegoController extends Controller
{
    // GET /videojuegos: muestra el listado completo o los juegos de un género.
    // Request contiene los datos de la petición; la respuesta será una vista o una redirección.
    public function index(Request $request): View|RedirectResponse
    {
        // Prepara la consulta y carga también el género de cada juego.
        // with evita consultar la tabla de géneros una vez por cada fila del listado.
        $consulta = Videojuego::with('genero');
        // Lee el parámetro GET de una URL como /videojuegos?genero_id=2.
        $generoId = $request->query('genero_id');

        // null significa que no se envió el parámetro; '' representa la opción «Todos».
        if ($generoId !== null && $generoId !== '') {
            // integer exige un entero; exists comprueba que ese id esté en generos.
            $validador = Validator::make(['genero_id' => $generoId], [
                'genero_id' => ['integer', 'exists:generos,id'],
            ]);

            if ($validador->fails()) {
                // Vuelve a la ruta sin el filtro incorrecto y envía el error a la vista.
                return to_route('videojuegos.index')->withErrors([
                    'genero_id' => 'Selecciona un género válido.',
                ]);
            }

            // Añade una condición WHERE; todavía no se han recuperado los videojuegos.
            $consulta->where('genero_id', $generoId);
        }

        // get ejecuta la consulta y devuelve una colección de modelos ordenados por título.
        $videojuegos = $consulta->orderBy('titulo')->get();
        // El desplegable necesita todos los géneros, aunque el listado esté filtrado.
        $generos = Genero::orderBy('nombre')->get();

        // compact crea el array que la vista recibe como $videojuegos y $generos.
        return view('videojuegos.index', compact('videojuegos', 'generos'));
    }

    // GET /videojuegos/create: prepara el formulario de alta, sin guardar datos.
    public function create(): View
    {
        // Los géneros existentes se muestran como opciones del formulario.
        $generos = Genero::orderBy('nombre')->get();

        return view('videojuegos.create', compact('generos'));
    }

    // POST /videojuegos: valida los datos del formulario y crea el registro.
    public function store(Request $request): RedirectResponse
    {
        // validate devuelve solo los campos validados. Si falla, Laravel vuelve al
        // formulario con los errores y los valores anteriores para recuperarlos con old().
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:100'],
            // El precio admite cero y un máximo de dos decimales, dentro del tamaño de la columna.
            'precio' => ['required', 'numeric', 'min:0', 'max:999999.99', 'decimal:0,2'],
            // La selección debe corresponder a un género real de la base de datos.
            'genero_id' => ['required', 'integer', 'exists:generos,id'],
            // La portada es obligatoria en el alta; max indica el tamaño en KB.
            'imagen' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // file obtiene el archivo subido y store genera un nombre para guardarlo
        // en storage/app/public/videojuegos. El resultado es una ruta relativa.
        $datos['imagen'] = $request->file('imagen')->store('videojuegos', 'public');
        // Sustituimos el archivo recibido por su ruta y guardamos los datos con Eloquent.
        // create admite los campos definidos en $fillable en el modelo Videojuego.
        Videojuego::create($datos);

        // Redirige después del POST para evitar repetir el alta al recargar la página.
        return to_route('videojuegos.index');
    }

    // GET /videojuegos/{videojuego}/edit: Laravel busca el modelo por el id de la URL.
    // Este enlace automático de modelos devuelve un 404 si el registro no existe.
    public function edit(Videojuego $videojuego): View
    {
        $generos = Genero::orderBy('nombre')->get();

        // La vista recibe el juego que debe precargar y los géneros del desplegable.
        return view('videojuegos.edit', compact('videojuego', 'generos'));
    }

    // PUT/PATCH /videojuegos/{videojuego}: modifica un registro existente.
    public function update(Request $request, Videojuego $videojuego): RedirectResponse
    {
        // Se repiten las reglas del alta, pero nullable permite editar sin una imagen nueva.
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:100'],
            'precio' => ['required', 'numeric', 'min:0', 'max:999999.99', 'decimal:0,2'],
            'genero_id' => ['required', 'integer', 'exists:generos,id'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Retira el campo de archivo para no sobrescribir la ruta actual con un valor nulo.
        unset($datos['imagen']);
        // Conserva la ruta anterior para poder borrar ese archivo si se sustituye.
        $imagenAnterior = $videojuego->imagen;

        // hasFile comprueba si se ha enviado una nueva portada.
        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('videojuegos', 'public');
        }

        // Actualiza los campos validados; si no hay nueva imagen, su ruta no cambia.
        $videojuego->update($datos);

        if ($request->hasFile('imagen')) {
            // Borra la portada antigua después de guardar la nueva ruta en MySQL.
            Storage::disk('public')->delete($imagenAnterior);
        }

        return to_route('videojuegos.index');
    }

    // DELETE /videojuegos/{videojuego}: elimina el registro y su portada.
    public function destroy(Videojuego $videojuego): RedirectResponse
    {
        // La base de datos guarda la ruta; el archivo se encuentra en el disco público.
        $imagen = $videojuego->imagen;
        $videojuego->delete();
        // Borrar un registro no borra su archivo: hay que hacerlo explícitamente.
        Storage::disk('public')->delete($imagen);

        return to_route('videojuegos.index');
    }
}
