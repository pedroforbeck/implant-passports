<?php

namespace App\Http\Requests;

class UpdatePatientRequest extends StorePatientRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('patient'));
    }
}
