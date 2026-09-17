<div class="grid gap-6 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input-label for="name" value="Nome completo" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $patient->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="cpf" value="CPF" />
        <x-text-input id="cpf" name="cpf" type="text" class="mt-1 block w-full" :value="old('cpf', $patient->cpf)" placeholder="000.000.000-00" required />
        <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="birth_date" value="Data de nascimento" />
        <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date', $patient->birth_date?->format('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="blood_type" value="Tipo sanguíneo" />
        <x-select-input id="blood_type" name="blood_type" class="mt-1 block w-full">
            <option value="">Selecione...</option>
            @foreach (App\Models\Patient::BLOOD_TYPES as $type)
                <option value="{{ $type }}" @selected(old('blood_type', $patient->blood_type) === $type)>{{ $type }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('blood_type')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="phone" value="Telefone" />
        <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $patient->phone)" placeholder="(00) 00000-0000" />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="emergency_contact_name" value="Contato de emergência" />
        <x-text-input id="emergency_contact_name" name="emergency_contact_name" type="text" class="mt-1 block w-full" :value="old('emergency_contact_name', $patient->emergency_contact_name)" />
        <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="emergency_contact_phone" value="Telefone de emergência" />
        <x-text-input id="emergency_contact_phone" name="emergency_contact_phone" type="tel" class="mt-1 block w-full" :value="old('emergency_contact_phone', $patient->emergency_contact_phone)" placeholder="(00) 00000-0000" />
        <x-input-error :messages="$errors->get('emergency_contact_phone')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="doctor_id" value="Médico responsável" />

        @if (auth()->user()->isAdmin())
            <x-select-input id="doctor_id" name="doctor_id" class="mt-1 block w-full" required>
                <option value="">Selecione...</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}" @selected(old('doctor_id', $patient->doctor_id) == $doctor->id)>{{ $doctor->name }}</option>
                @endforeach
            </x-select-input>
        @else
            <x-text-input type="text" class="mt-1 block w-full" :value="$doctors->firstWhere('id', old('doctor_id', $patient->doctor_id))?->name ?? auth()->user()->name" disabled />
        @endif

        <x-input-error :messages="$errors->get('doctor_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="user_id" value="Conta de acesso (paciente)" />
        <x-select-input id="user_id" name="user_id" class="mt-1 block w-full">
            <option value="">Sem conta vinculada</option>
            @foreach ($patientAccounts as $account)
                <option value="{{ $account->id }}" @selected(old('user_id', $patient->user_id) == $account->id)>{{ $account->name }} ({{ $account->email }})</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="notes" value="Observações" />
        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $patient->notes) }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
    </div>
</div>