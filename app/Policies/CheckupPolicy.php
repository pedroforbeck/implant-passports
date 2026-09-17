<?php

namespace App\Policies;

use App\Models\Checkup;
use App\Models\Device;
use App\Models\User;

class CheckupPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function create(User $user, Device $device): bool
    {
        return $user->can('update', $device);
    }

    public function update(User $user, Checkup $checkup): bool
    {
        return $user->can('update', $checkup->device);
    }

    public function delete(User $user, Checkup $checkup): bool
    {
        return false;
    }
}
