<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Metadata\QueryParameter;

#[ApiResource]
#[QueryParameter(key: 'nombre', filter: EqualsFilter::class)]
class Usuario extends Model
{
    //
}
