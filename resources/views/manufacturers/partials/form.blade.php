<div class="grid gap-6 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input-label for="name" value="Nome" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $manufacturer->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="country" value="País" />
        <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $manufacturer->country)" />
        <x-input-error :messages="$errors->get('country')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="website" value="Site" />
        <x-text-input id="website" name="website" type="url" class="mt-1 block w-full" :value="old('website', $manufacturer->website)" placeholder="https://" />
        <x-input-error :messages="$errors->get('website')" class="mt-2" />
    </div>
</div>
