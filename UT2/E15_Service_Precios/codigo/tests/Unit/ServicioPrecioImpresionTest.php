<?php

namespace Tests\Unit;

use App\Services\ServicioPrecioImpresion;
use Tests\TestCase;

class ServicioPrecioImpresionTest extends TestCase
{
    public function test_blanco_y_negro(): void
    {
        $this->assertSame(500, (new ServicioPrecioImpresion())->calcular(100, false, false));
    }
    public function test_color(): void
    {
        $this->assertSame(800, (new ServicioPrecioImpresion())->calcular(100, true, false));
    }
    public function test_encuadernado(): void
    {
        $this->assertSame(750, (new ServicioPrecioImpresion())->calcular(100, false, true));
    }
}
