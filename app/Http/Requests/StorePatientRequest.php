<?php

namespace App\Http\Requests;

use App\Enums\Role;
use App\Models\Patient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Patient::class);
    }

    protected function prepareForValidation(): void
    {
        if ($this->user()->isDoctor()) {
            $this->merge(['doctor_id' => $this->user()->id]);
        }
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $patient = $this->route('patient');

        return [
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', Rule::unique(Patient::class)->ignore($patient)],
            'birth_date' => ['required', 'date', 'before:today'],
            'blood_type' => ['nullable', Rule::in(Patient::BLOOD_TYPES)],
            'phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20', 'required_with:emergency_contact_name'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'doctor_id' => ['required', Rule::exists('users', 'id')->where('role', Role::Doctor->value)],
            'user_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', Role::Patient->value),
                Rule::unique(Patient::class)->ignore($patient),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'cpf' => 'CPF',
            'birth_date' => 'data de nascimento',
            'blood_type' => 'tipo sanguíneo',
            'phone' => 'telefone',
            'emergency_contact_name' => 'contato de emergência',
            'emergency_contact_phone' => 'telefone de emergência',
            'notes' => 'observações',
            'doctor_id' => 'médico responsável',
            'user_id' => 'conta de acesso',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cpf.regex' => 'Informe o CPF no formato 000.000.000-00.',
            'user_id.unique' => 'Esta conta já está vinculada a outro paciente.',
        ];
    }
}
