<?php

namespace App\Http\Requests;

use App\Models\Device;

class UpdateCheckupRequest extends StoreCheckupRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('checkup'));
    }

    protected function device(): Device
    {
        return $this->route('checkup')->device;
    }
}
