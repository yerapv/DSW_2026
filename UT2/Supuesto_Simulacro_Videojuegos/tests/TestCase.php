<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

// Base de las pruebas que necesitan arrancar Laravel y utilizar config(), rutas o BD.
abstract class TestCase extends BaseTestCase
{
    // Laravel prepara la aplicación automáticamente desde bootstrap/app.php.
}
