<?php

namespace App\Http\Requests;

class UpdateManufacturerRequest extends StoreManufacturerRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('manufacturer'));
    }
}
