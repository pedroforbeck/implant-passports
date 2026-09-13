<?php

namespace App\Policies;

use App\Models\Manufacturer;
use App\Models\User;

/**
 * O cadastro de fabricantes é exclusivo do administrador.
 */
class ManufacturerPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Manufacturer $manufacturer): bool
    {
        return false;
    }

    public function delete(User $user, Manufacturer $manufacturer): bool
    {
        return false;
    }
}
