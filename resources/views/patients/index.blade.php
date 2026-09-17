<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pacientes</h2>

            @can('create', App\Models\Patient::class)
                <x-link-button :href="route('patients.create')">Novo paciente</x-link-button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <form method="GET" action="{{ route('patients.index') }}" class="grid gap-2 sm:grid-cols-[1fr_auto] px-4 sm:px-0">
                <x-text-input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Buscar por nome ou CPF..."
                    class="w-full"
                />
                <noscript><x-secondary-button type="submit">Buscar</x-secondary-button></noscript>
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Nome</th>
                            <th class="px-6 py-3">CPF</th>
                            <th class="px-6 py-3">Nascimento</th>
                            <th class="px-6 py-3">Médico responsável</th>
                            <th class="px-6 py-3">Dispositivos</th>
                            <th class="px-6 py-3"><span class="sr-only">Ações</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($patients as $patient)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $patient->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $patient->cpf }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $patient->birth_date?->format('d/m/Y') ?? '—' }} <span class="text-gray-400">({{ $patient->age }} anos)</span></td>
                                <td class="px-6 py-4">{{ $patient->doctor?->name ?? '—' }}</td>
                                <td class="px-6 py-4">{{ $patient->devices_count }}</td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('patients.show', $patient) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>

                                    @can('update', $patient)
                                        <a href="{{ route('patients.edit', $patient) }}" class="ms-3 text-indigo-600 hover:text-indigo-900">Editar</a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">Nenhum paciente encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 sm:px-0">
                {{ $patients->links() }}
            </div>
        </div>
    </div>
</x-app-layout>