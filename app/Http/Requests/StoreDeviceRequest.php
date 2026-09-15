<?php

namespace App\Http\Requests;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Device;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', [Device::class, $this->route('patient')]);
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['mri_conditional' => $this->boolean('mri_conditional')]);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'manufacturer_id' => ['required', 'exists:manufacturers,id'],
            'type' => ['required', Rule::enum(DeviceType::class)],
            'model' => ['required', 'string', 'max:255'],
            'serial_number' => ['required', 'string', 'max:100', Rule::unique(Device::class)->ignore($this->route('device'))],
            'implanted_at' => ['required', 'date', 'before_or_equal:today'],
            'hospital' => ['nullable', 'string', 'max:255'],
            'mri_conditional' => ['boolean'],
            'status' => ['required', Rule::enum(DeviceStatus::class)],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'manufacturer_id' => 'fabricante',
            'type' => 'tipo',
            'model' => 'modelo',
            'serial_number' => 'número de série',
            'implanted_at' => 'data do implante',
            'hospital' => 'hospital',
            'mri_conditional' => 'compatibilidade com ressonância',
            'status' => 'situação',
            'notes' => 'observações',
        ];
    }
}
