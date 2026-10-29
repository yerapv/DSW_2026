<?php

// Une URL y verbo HTTP con un método del Controller. name() permite usar route().
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPedidoController;
use App\Http\Controllers\AvisoController;
use App\Http\Controllers\BienvenidaController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\FichaClienteController;
use App\Http\Controllers\FinanciacionController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\MetodoPagoController;
use App\Http\Controllers\MontajeController;
use App\Http\Controllers\NovedadController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BienvenidaController::class, 'index'])->name('inicio');
// El botón Usuario abre sus opciones directamente, sin login ni registro.
Route::get('/usuario', [UsuarioController::class, 'index'])->name('usuario.index');
Route::get('/novedades', [NovedadController::class, 'index'])->name('novedades');
Route::get('/envios', [EnvioController::class, 'index'])->name('envios');

Route::get('/ficha-cliente', [FichaClienteController::class, 'create'])->name('fichas.create');
Route::post('/ficha-cliente', [FichaClienteController::class, 'store'])->name('fichas.store');
Route::get('/ficha-cliente/{cliente}', [FichaClienteController::class, 'show'])->name('fichas.show');

// La tienda permite consultar avisos y productos; su gestión está en /admin.
Route::get('/avisos', [TiendaController::class, 'avisos'])->name('avisos.index');
// Solo index/show: no se exponen acciones de escritura en el catálogo público.
Route::get('/productos', [TiendaController::class, 'index'])->name('productos.index');
Route::get('/productos/{producto}', [TiendaController::class, 'show'])->name('productos.show');

Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');

Route::get('/montaje', [MontajeController::class, 'create'])->name('montaje.create');
Route::post('/montaje', [MontajeController::class, 'calculate'])->name('montaje.calculate');
Route::get('/financiacion', [FinanciacionController::class, 'create'])->name('financiacion.create');
Route::post('/financiacion', [FinanciacionController::class, 'store'])->name('financiacion.store');

Route::get('/imagenes', [TiendaController::class, 'imagenes'])->name('imagenes.index');

Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/lineas', [CarritoController::class, 'add'])->name('carrito.add');
Route::post('/carrito/presupuesto', [CarritoController::class, 'quote'])->name('carrito.quote');
Route::get('/carrito/presupuestos/{presupuesto}', [CarritoController::class, 'showQuote'])->name('carrito.presupuestos.show');

Route::get('/carrito/pago', [PagoController::class, 'create'])->name('pago.create');
Route::post('/carrito/pagar', [PagoController::class, 'store'])->name('pago.store');
Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');

// ADMINISTRACIÓN: URLs y nombres separados para no mezclar los formularios.
// prefix('admin') añade /admin; name('admin.') distingue sus enlaces en Blade.
// El acceso es directo, sin middleware de inicio de sesión en esta fase.
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::match(['put', 'patch'], '/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    Route::get('/metodos-pago', [MetodoPagoController::class, 'index'])->name('metodos-pago.index');
    Route::get('/metodos-pago/create', [MetodoPagoController::class, 'create'])->name('metodos-pago.create');
    Route::post('/metodos-pago', [MetodoPagoController::class, 'store'])->name('metodos-pago.store');
    Route::get('/metodos-pago/{metodo}', [MetodoPagoController::class, 'show'])->name('metodos-pago.show');
    Route::get('/metodos-pago/{metodo}/edit', [MetodoPagoController::class, 'edit'])->name('metodos-pago.edit');
    Route::match(['put', 'patch'], '/metodos-pago/{metodo}', [MetodoPagoController::class, 'update'])->name('metodos-pago.update');
    Route::delete('/metodos-pago/{metodo}', [MetodoPagoController::class, 'destroy'])->name('metodos-pago.destroy');
    Route::get('/pedidos/historico', [AdminPedidoController::class, 'historico'])->name('pedidos.historico');
    Route::get('/pedidos', [AdminPedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{pedido}', [AdminPedidoController::class, 'show'])->name('pedidos.show');
    Route::get('/pedidos/{pedido}/edit', [AdminPedidoController::class, 'edit'])->name('pedidos.edit');
    Route::match(['put', 'patch'], '/pedidos/{pedido}', [AdminPedidoController::class, 'update'])->name('pedidos.update');

    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{producto}', [ProductoController::class, 'show'])->name('productos.show');
    Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::match(['put', 'patch'], '/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');

    Route::get('/avisos', [AvisoController::class, 'index'])->name('avisos.index');
    Route::get('/avisos/create', [AvisoController::class, 'create'])->name('avisos.create');
    Route::post('/avisos', [AvisoController::class, 'store'])->name('avisos.store');
    Route::get('/avisos/{aviso}', [AvisoController::class, 'show'])->name('avisos.show');
    Route::get('/avisos/{aviso}/edit', [AvisoController::class, 'edit'])->name('avisos.edit');
    Route::match(['put', 'patch'], '/avisos/{aviso}', [AvisoController::class, 'update'])->name('avisos.update');
    Route::delete('/avisos/{aviso}', [AvisoController::class, 'destroy'])->name('avisos.destroy');

    Route::get('/imagenes', [ImagenController::class, 'index'])->name('imagenes.index');
    Route::get('/imagenes/create', [ImagenController::class, 'create'])->name('imagenes.create');
    Route::post('/imagenes', [ImagenController::class, 'store'])->name('imagenes.store');
    Route::get('/imagenes/{imagen}', [ImagenController::class, 'show'])->name('imagenes.show');
    Route::get('/imagenes/{imagen}/edit', [ImagenController::class, 'edit'])->name('imagenes.edit');
    Route::match(['put', 'patch'], '/imagenes/{imagen}', [ImagenController::class, 'update'])->name('imagenes.update');
    Route::delete('/imagenes/{imagen}', [ImagenController::class, 'destroy'])->name('imagenes.destroy');
});
