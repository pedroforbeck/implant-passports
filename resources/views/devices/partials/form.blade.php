<div class="grid gap-6 sm:grid-cols-2">
    <div>
        <x-input-label for="manufacturer_id" value="Fabricante" />
        <x-select-input id="manufacturer_id" name="manufacturer_id" class="mt-1 block w-full" required>
            <option value="">Selecione...</option>
            @foreach ($manufacturers as $manufacturer)
                <option value="{{ $manufacturer->id }}" @selected(old('manufacturer_id', $device->manufacturer_id) == $manufacturer->id)>{{ $manufacturer->name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('manufacturer_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="type" value="Tipo" />
        <x-select-input id="type" name="type" class="mt-1 block w-full" required>
            <option value="">Selecione...</option>
            @foreach ($types as $type)
                <option value="{{ $type->value }}" @selected(old('type', $device->type?->value) === $type->value)>{{ $type->label() }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="model" value="Modelo" />
        <x-text-input id="model" name="model" type="text" class="mt-1 block w-full" :value="old('model', $device->model)" required />
        <x-input-error :messages="$errors->get('model')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="serial_number" value="Número de série" />
        <x-text-input id="serial_number" name="serial_number" type="text" class="mt-1 block w-full" :value="old('serial_number', $device->serial_number)" required />
        <x-input-error :messages="$errors->get('serial_number')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="implanted_at" value="Data do implante" />
        <x-text-input id="implanted_at" name="implanted_at" type="date" class="mt-1 block w-full" :value="old('implanted_at', $device->implanted_at?->format('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('implanted_at')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" value="Situação" />
        <x-select-input id="status" name="status" class="mt-1 block w-full" required>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected(old('status', $device->status?->value ?? App\Enums\DeviceStatus::Active->value) === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="hospital" value="Hospital / instituição" />
        <x-text-input id="hospital" name="hospital" type="text" class="mt-1 block w-full" :value="old('hospital', $device->hospital)" placeholder="Onde o dispositivo foi implantado" />
        <x-input-error :messages="$errors->get('hospital')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <label class="flex items-center">
            <input
                type="checkbox"
                name="mri_conditional"
                value="1"
                @checked(old('mri_conditional', $device->mri_conditional))
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
            >
            <span class="ms-2 text-sm text-gray-700">Compatível com ressonância magnética (MRI)</span>
        </label>
        <x-input-error :messages="$errors->get('mri_conditional')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="notes" value="Observações" />
        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $device->notes) }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
    </div>
</div>