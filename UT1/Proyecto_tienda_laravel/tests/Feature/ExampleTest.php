<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_la_portada_redirige_al_catalogo_de_productos(): void
    {
        $this->get('/')->assertRedirect('/productos');
    }
}
