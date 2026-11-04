<?php

namespace App\Policies;

use App\Models\Nota;
use App\Models\User;

class NotaPolicy
{
    // Para cada función. Se verifica si el ID del usuario autenticado coincide con el ID del usuario propietario de la nota. 
    // Si coinciden, se permite la eliminación de la nota; de lo contrario, se deniega.

    public function view(User $u, Nota $n): bool
    {
        return $u->id === $n->user_id;
    }
    public function update(User $u, Nota $n): bool
    {
        return $u->id === $n->user_id;
    }
    public function delete(User $u, Nota $n): bool
    {
        return $u->id === $n->user_id;
    }
}
