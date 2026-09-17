<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Usuários</h2>

            @can('create', App\Models\User::class)
                <x-link-button :href="route('users.create')">Novo usuário</x-link-button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <form method="GET" action="{{ route('users.index') }}" class="flex gap-2 px-4 sm:px-0">
                <x-select-input name="role" onchange="this.form.submit()">
                    <option value="">Todos os perfis</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->value }}" @selected(request('role') === $role->value)>{{ $role->label() }}</option>
                    @endforeach
                </x-select-input>
                <noscript><x-secondary-button type="submit">Filtrar</x-secondary-button></noscript>
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Nome</th>
                            <th class="px-6 py-3">E-mail</th>
                            <th class="px-6 py-3">Perfil</th>
                            <th class="px-6 py-3">Cadastro</th>
                            <th class="px-6 py-3"><span class="sr-only">Ações</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($users as $account)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $account->name }}</td>
                                <td class="px-6 py-4">{{ $account->email }}</td>
                                <td class="px-6 py-4">{{ $account->role->label() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $account->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    @can('update', $account)
                                        <a href="{{ route('users.edit', $account) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                    @endcan

                                    @can('delete', $account)
                                        <form method="POST" action="{{ route('users.destroy', $account) }}" class="inline" onsubmit="return confirm('Remover esta conta?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ms-3 text-red-600 hover:text-red-800">Remover</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Nenhum usuário encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 sm:px-0">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
