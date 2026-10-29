<?php

namespace App\Http\Controllers;

use App\Enums\TipoProducto;
use App\Http\Requests\GuardarProductoRequest;
use App\Models\Categoria;
use App\Models\LineaCarrito;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

// Sus rutas y formularios pertenecen al área /admin.
class ProductoController extends Controller
{
    /** Aplica filtros, carga la categoría y pagina los productos por nombre. */
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'q' => ['nullable', 'string', 'max:150'],
            'tipo' => ['nullable', Rule::enum(TipoProducto::class)],
        ], [
            'q.string' => 'La búsqueda debe ser un texto.',
            'q.max' => 'La búsqueda no puede superar 150 caracteres.',
            'tipo.enum' => 'Selecciona un tipo de producto válido.',
        ]);
        $productos = Producto::query()->with('categoria')
            ->when($request->filled('q'), fn ($q) => $q->where('nombre', 'like', '%'.$request->query('q').'%'))
            ->when($filtros['tipo'] ?? null, fn ($q, $tipo) => $q->where('tipo', $tipo))
            ->orderBy('nombre')->paginate(5)->withQueryString();

        return view('admin.productos.index', compact('productos'));
    }

    /** El select del formulario necesita las categorías existentes. */
    public function create(): View
    {
        return view('admin.productos.create', ['categorias' => Categoria::orderBy('nombre')->get()]);
    }

    /** Valida, crea el producto y redirige al listado. */
    public function store(GuardarProductoRequest $request): RedirectResponse
    {
        $datos = $this->datosProducto($request);
        $ruta = $this->guardarImagen($request);
        $datos['ruta_imagen'] = $ruta;

        try {
            Producto::create($datos);
        } catch (Throwable $exception) {
            // Si falla el INSERT, evitamos dejar una foto sin producto asociado.
            Storage::disk('public')->delete($ruta);
            throw $exception;
        }

        return redirect()->route('admin.productos.index');
    }

    /** Route Model Binding busca el id de la URL y devuelve 404 si no existe. */
    public function show(Producto $producto): View
    {
        $producto->load('categoria');

        return view('admin.productos.show', compact('producto'));
    }

    /** La edición reutiliza el parcial _form con los valores del modelo. */
    public function edit(Producto $producto): View
    {
        return view('admin.productos.edit', [
            'producto' => $producto,
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    /** El mismo FormRequest valida el PUT y update() ejecuta el UPDATE. */
    public function update(GuardarProductoRequest $request, Producto $producto): RedirectResponse
    {
        $datos = $this->datosProducto($request);
        $rutaAnterior = $producto->ruta_imagen;

        // Un campo de archivo vacío no sustituye ni borra la fotografía anterior.
        if ($request->hasFile('imagen')) {
            $datos['ruta_imagen'] = $this->guardarImagen($request);
        }

        try {
            $producto->update($datos);
        } catch (Throwable $exception) {
            // Si falla el UPDATE, conservamos la foto anterior y retiramos la nueva.
            if (isset($datos['ruta_imagen'])) {
                Storage::disk('public')->delete($datos['ruta_imagen']);
            }
            throw $exception;
        }

        // Borramos la anterior solo después de guardar correctamente la nueva ruta.
        if (isset($datos['ruta_imagen']) && $rutaAnterior) {
            Storage::disk('public')->delete($rutaAnterior);
        }

        return redirect()->route('admin.productos.index');
    }

    /** DELETE elimina el registro; las FK siguen protegiendo sus dependencias. */
    public function destroy(Producto $producto): RedirectResponse
    {
        if (LineaCarrito::where('producto_id', $producto->id)->exists()) {
            return back()->withErrors(['producto' => 'Este producto figura en el carrito de demostración y no se puede eliminar.']);
        }
        $producto->delete();

        return redirect()->route('admin.productos.index');
    }

    /** Agrupa las características correspondientes al tipo de producto. */
    private function datosProducto(GuardarProductoRequest $request): array
    {
        $datos = $request->validated();
        // Convierte euros a céntimos con enteros, sin redondeos de coma flotante.
        $precio = explode('.', (string) $datos['precio']);
        $datos['precio_centimos'] = (int) $precio[0] * 100 + (int) str_pad($precio[1] ?? '', 2, '0');
        $datos['tipo'] = TipoProducto::from((int) $datos['tipo']);
        $caracteristicas = [];
        if ($datos['tipo'] === TipoProducto::Portatil) {
            $caracteristicas['ram_gb'] = $datos['ram_gb'] ?? null;
        }
        if (in_array($datos['tipo'], [TipoProducto::Periferico, TipoProducto::Componente], true)) {
            $caracteristicas['conexion'] = $datos['conexion'] ?? null;
        }

        // ram_gb y conexion van dentro del JSON; imagen es un archivo, no una columna.
        unset($datos['precio'], $datos['ram_gb'], $datos['conexion'], $datos['imagen']);
        $datos['caracteristicas'] = $caracteristicas;

        return $datos;
    }

    /** Guarda la fotografía validada en la carpeta pública de productos. */
    private function guardarImagen(GuardarProductoRequest $request): string
    {
        $ruta = $request->file('imagen')->store('productos', 'public');
        if ($ruta === false) {
            throw new RuntimeException('No se pudo guardar la fotografía del producto.');
        }

        return $ruta;
    }
}
