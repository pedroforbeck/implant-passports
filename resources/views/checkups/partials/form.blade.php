<div>
    <x-input-label for="checked_at" value="Data da avaliação" />
    <x-text-input
        id="checked_at"
        name="checked_at"
        type="date"
        class="mt-1 block w-full"
        :value="old('checked_at', $checkup->checked_at?->format('Y-m-d'))"
        :min="$checkup->device?->implanted_at?->format('Y-m-d')"
        :max="now()->format('Y-m-d')"
        required
    />
    <x-input-error :messages="$errors->get('checked_at')" class="mt-2" />
</div>

<div>
    <x-input-label for="battery_level" value="Nível da bateria (%)" />
    <x-text-input
        id="battery_level"
        name="battery_level"
        type="number"
        min="0"
        max="100"
        class="mt-1 block w-full"
        :value="old('battery_level', $checkup->battery_level)"
        required
    />
    <x-input-error :messages="$errors->get('battery_level')" class="mt-2" />
    <p class="mt-1 text-xs text-gray-500">Use {{ App\Models\Checkup::LOW_BATTERY }}% ou menos para alertar sobre bateria baixa.</p>
</div>

<div>
    <x-input-label for="notes" value="Observações" />
    <textarea
        id="notes"
        name="notes"
        rows="5"
        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        placeholder="Resultados, ajustes programados, recomendações..."
    >{{ old('notes', $checkup->notes) }}</textarea>
    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
</div>