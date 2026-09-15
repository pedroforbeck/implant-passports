<?php

namespace App\Http\Requests;

use App\Models\Checkup;
use App\Models\Device;
use Illuminate\Foundation\Http\FormRequest;

class StoreCheckupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', [Checkup::class, $this->device()]);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'checked_at' => [
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:'.$this->device()->implanted_at->toDateString(),
            ],
            'battery_level' => ['required', 'integer', 'between:0,100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'checked_at' => 'data da avaliação',
            'battery_level' => 'nível de bateria',
            'notes' => 'observações',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'checked_at.after_or_equal' => 'A avaliação não pode ser anterior ao implante ('
                .$this->device()->implanted_at->format('d/m/Y').').',
        ];
    }

    protected function device(): Device
    {
        return $this->route('device');
    }
}
