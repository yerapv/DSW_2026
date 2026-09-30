<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            ['sku' => 'PORT-001', 'nombre' => 'Portátil Lenovo ThinkPad E14', 'categoria' => 'Portátiles', 'descripcion' => 'Portátil de 14 pulgadas para trabajo y estudio.', 'precio' => 749.00, 'stock' => 12, 'activo' => true],
            ['sku' => 'PORT-002', 'nombre' => 'Portátil ASUS VivoBook 15', 'categoria' => 'Portátiles', 'descripcion' => 'Equipo ligero con pantalla Full HD.', 'precio' => 629.90, 'stock' => 8, 'activo' => true],
            ['sku' => 'MON-001', 'nombre' => 'Monitor LG UltraGear 27 pulgadas', 'categoria' => 'Monitores', 'descripcion' => 'Monitor IPS QHD de 27 pulgadas.', 'precio' => 289.99, 'stock' => 6, 'activo' => true],
            ['sku' => 'MON-002', 'nombre' => 'Monitor Dell P2422H', 'categoria' => 'Monitores', 'descripcion' => 'Monitor Full HD de 24 pulgadas.', 'precio' => 179.00, 'stock' => 0, 'activo' => true],
            ['sku' => 'PER-001', 'nombre' => 'Teclado mecánico Logitech G413', 'categoria' => 'Periféricos', 'descripcion' => 'Teclado mecánico con conexión USB.', 'precio' => 89.95, 'stock' => 15, 'activo' => true],
            ['sku' => 'PER-002', 'nombre' => 'Ratón inalámbrico Logitech MX Master 3S', 'categoria' => 'Periféricos', 'descripcion' => 'Ratón inalámbrico ergonómico.', 'precio' => 109.00, 'stock' => 5, 'activo' => true],
            ['sku' => 'ALM-001', 'nombre' => 'SSD Samsung 990 EVO 1 TB', 'categoria' => 'Almacenamiento', 'descripcion' => 'Unidad NVMe M.2 de alta velocidad.', 'precio' => 99.90, 'stock' => 20, 'activo' => true],
            ['sku' => 'ALM-002', 'nombre' => 'Disco duro externo Seagate 2 TB', 'categoria' => 'Almacenamiento', 'descripcion' => 'Almacenamiento portátil USB 3.0.', 'precio' => 74.50, 'stock' => 9, 'activo' => false],
            ['sku' => 'COM-001', 'nombre' => 'Tarjeta gráfica MSI GeForce RTX 4060', 'categoria' => 'Componentes', 'descripcion' => 'Tarjeta gráfica con 8 GB de memoria.', 'precio' => 329.00, 'stock' => 4, 'activo' => true],
            ['sku' => 'COM-002', 'nombre' => 'Memoria RAM Corsair Vengeance 16 GB', 'categoria' => 'Componentes', 'descripcion' => 'Módulo DDR5 de 16 GB.', 'precio' => 54.90, 'stock' => 18, 'activo' => true],
        ];

        foreach ($productos as $datos) {
            $categoria = Categoria::where('nombre', $datos['categoria'])->firstOrFail();
            unset($datos['categoria']);
            Producto::updateOrCreate(['sku' => $datos['sku']], [...$datos, 'categoria_id' => $categoria->id]);
        }
    }
}
