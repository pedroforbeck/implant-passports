<?php

namespace App\Policies;

use App\Models\Device;
use App\Models\Patient;
use App\Models\User;

class DevicePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isDoctor();
    }

    public function view(User $user, Device $device): bool
    {
        return $user->can('view', $device->patient);
    }

    public function create(User $user, Patient $patient): bool
    {
        return $user->can('update', $patient);
    }

    public function update(User $user, Device $device): bool
    {
        return $user->can('update', $device->patient);
    }

    public function delete(User $user, Device $device): bool
    {
        return $user->can('update', $device->patient);
    }
}
