<?php

namespace App\Http\Requests;

class UpdateDeviceRequest extends StoreDeviceRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('device'));
    }
}
