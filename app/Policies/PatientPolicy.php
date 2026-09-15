<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isDoctor();
    }

    public function view(User $user, Patient $patient): bool
    {
        return $this->isResponsibleDoctor($user, $patient)
            || $patient->user()->is($user);
    }

    public function create(User $user): bool
    {
        return $user->isDoctor();
    }

    public function update(User $user, Patient $patient): bool
    {
        return $this->isResponsibleDoctor($user, $patient);
    }

    public function delete(User $user, Patient $patient): bool
    {
        return false;
    }

    private function isResponsibleDoctor(User $user, Patient $patient): bool
    {
        return $user->isDoctor() && $patient->doctor()->is($user);
    }
}
