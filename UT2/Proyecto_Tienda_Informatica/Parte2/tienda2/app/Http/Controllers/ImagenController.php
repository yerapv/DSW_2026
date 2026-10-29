<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

// Sus rutas y formularios pertenecen al área /admin.
class ImagenController extends Controller
{
    /** El listado obtiene las rutas; Storage::url se utiliza en Blade. */
    public function index(): View
    {
        return view('admin.imagenes.index', ['imagenes' => Imagen::orderByDesc('id')->paginate(5)]);
    }

    /** La vista utilizará enctype multipart para enviar el archivo. */
    public function create(): View
    {
        return view('admin.imagenes.create');
    }

    /** Se valida el archivo antes de guardarlo en el disco público. */
    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);
        unset($datos['imagen']);
        $datos['ruta_imagen'] = $this->guardarImagen($request);

        try {
            Imagen::create($datos);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($datos['ruta_imagen']);
            throw $exception;
        }

        return redirect()->route('admin.imagenes.index');
    }

    public function show(Imagen $imagen): View
    {
        return view('admin.imagenes.show', compact('imagen'));
    }

    public function edit(Imagen $imagen): View
    {
        return view('admin.imagenes.edit', compact('imagen'));
    }

    /** Sin archivo nuevo se conserva la foto; con archivo nuevo se sustituye. */
    public function update(Request $request, Imagen $imagen): RedirectResponse
    {
        $datos = $this->validar($request);
        unset($datos['imagen']);
        $rutaAnterior = $imagen->ruta_imagen;

        if ($request->hasFile('imagen')) {
            $datos['ruta_imagen'] = $this->guardarImagen($request);
        }

        try {
            $imagen->update($datos);
        } catch (Throwable $exception) {
            if (isset($datos['ruta_imagen'])) {
                Storage::disk('public')->delete($datos['ruta_imagen']);
            }
            throw $exception;
        }

        // El archivo anterior solo se retira cuando se ha guardado la nueva ruta.
        if (isset($datos['ruta_imagen']) && $rutaAnterior) {
            Storage::disk('public')->delete($rutaAnterior);
        }

        return redirect()->route('admin.imagenes.index');
    }

    /** El evento deleted del modelo se encarga de eliminar el archivo físico. */
    public function destroy(Imagen $imagen): RedirectResponse
    {
        $imagen->delete();

        return redirect()->route('admin.imagenes.index');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'autor' => ['required', 'string', 'max:120'],
            'imagen' => [$request->isMethod('post') ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nombre.required' => 'Escribe un nombre para la fotografía del escaparate.',
            'nombre.string' => 'El nombre de la fotografía debe ser un texto.',
            'nombre.max' => 'El nombre de la fotografía no puede superar :max caracteres.',
            'autor.required' => 'Indica el autor de la fotografía del escaparate.',
            'autor.string' => 'El autor de la fotografía debe ser un texto.',
            'autor.max' => 'El nombre del autor de la fotografía no puede superar :max caracteres.',
            'imagen.required' => 'Selecciona la fotografía que quieres subir al escaparate.',
            'imagen.image' => 'El archivo del escaparate debe ser una imagen válida.',
            'imagen.mimes' => 'La fotografía del escaparate debe ser JPG, PNG o WebP.',
            'imagen.max' => 'La fotografía del escaparate no puede superar los 2 MB.',
            'imagen.uploaded' => 'No se pudo subir la fotografía del escaparate. Vuelve a seleccionarla.',
        ]);
    }

    private function guardarImagen(Request $request): string
    {
        $ruta = $request->file('imagen')->store('escaparate', 'public');
        if ($ruta === false) {
            throw new RuntimeException('No se pudo guardar la fotografía del escaparate.');
        }

        return $ruta;
    }
}
