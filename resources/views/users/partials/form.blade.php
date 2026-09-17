<form method="POST" action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}" class="space-y-6">
    @csrf
    @if ($user->exists)
        @method('PUT')
    @endif

    <div>
        <x-input-label for="name" value="Nome" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="E-mail" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    @unless ($user->exists)
        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <x-input-label for="password" value="Senha" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Confirmar senha" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>
    @endunless

    <div>
        <x-input-label for="role" value="Perfil de acesso" />
        <x-select-input id="role" name="role" class="mt-1 block w-full" required>
            @foreach ($roles as $role)
                <option value="{{ $role->value }}" @selected(old('role', $user->role->value) === $role->value)>{{ $role->label() }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('role')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end gap-4">
        <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
        <x-primary-button>{{ $user->exists ? 'Salvar alterações' : 'Criar usuário' }}</x-primary-button>
    </div>
</form>