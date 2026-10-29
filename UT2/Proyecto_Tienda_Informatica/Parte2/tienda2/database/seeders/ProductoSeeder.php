<?php

namespace Database\Seeders;

use App\Enums\TipoProducto;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /** Mantiene en esta clase únicamente los datos de su tabla. */
    public function run(): void
    {
        // Recuperamos las FK; CategoriaSeeder debe haberse ejecutado antes.
        $portatiles = Categoria::where('nombre', 'Portátiles')->firstOrFail();
        $perifericos = Categoria::where('nombre', 'Periféricos')->firstOrFail();
        $componentes = Categoria::where('nombre', 'Componentes')->firstOrFail();

        // Doce artículos ficticios permiten comprobar paginate(5). Precios en céntimos.
        $productos = [
            ['Portátil Aula 15', 'Nexo', 64950, TipoProducto::Portatil, $portatiles->id, ['ram_gb' => 16]],
            ['Portátil Pro 14', 'Nexo', 99900, TipoProducto::Portatil, $portatiles->id, ['ram_gb' => 32]],
            ['Portátil Ligero 13', 'Nova', 57900, TipoProducto::Portatil, $portatiles->id, ['ram_gb' => 8]],
            ['Teclado USB', 'Key', 2490, TipoProducto::Periferico, $perifericos->id, ['conexion' => 'USB']],
            ['Ratón inalámbrico', 'Key', 1990, TipoProducto::Periferico, $perifericos->id, ['conexion' => 'Bluetooth']],
            ['Monitor 24 pulgadas', 'View', 12900, TipoProducto::Periferico, $perifericos->id, ['conexion' => 'HDMI']],
            ['Auriculares', 'Sound', 3490, TipoProducto::Periferico, $perifericos->id, ['conexion' => 'Jack 3,5 mm']],
            ['Webcam HD', 'View', 3990, TipoProducto::Periferico, $perifericos->id, ['conexion' => 'USB']],
            ['Disco SSD 1 TB', 'Chip', 7990, TipoProducto::Componente, $componentes->id, ['conexion' => 'NVMe']],
            ['Memoria RAM 16 GB', 'Chip', 4990, TipoProducto::Componente, $componentes->id, ['conexion' => 'DDR5']],
            ['Fuente 650 W', 'Power', 6990, TipoProducto::Componente, $componentes->id, ['conexion' => 'ATX']],
            ['Placa base B650', 'Chip', 14900, TipoProducto::Componente, $componentes->id, ['conexion' => 'AM5']],
        ];

        // La desestructuración asigna un nombre a cada posición del array.
        foreach ($productos as [$nombre, $marca, $precio, $tipo, $categoriaId, $caracteristicas]) {
            Producto::firstOrCreate(['nombre' => $nombre], [
                'marca' => $marca,
                'anio' => 2026,
                'precio_centimos' => $precio,
                'tipo' => $tipo,
                'categoria_id' => $categoriaId,
                'disponible' => true,
                'caracteristicas' => $caracteristicas,
            ]);
        }
    }
}
