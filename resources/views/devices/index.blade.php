<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dispositivos</h2>

            @can('viewAny', App\Models\Patient::class)
                <x-link-button variant="secondary" :href="route('patients.index')">Escolher paciente</x-link-button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <form method="GET" action="{{ route('devices.index') }}" class="grid gap-2 sm:grid-cols-[1fr_auto] px-4 sm:px-0">
                <x-text-input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Buscar por número de série, modelo ou paciente..."
                    class="w-full"
                />
                <noscript><x-secondary-button type="submit">Buscar</x-secondary-button></noscript>
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Tipo</th>
                            <th class="px-6 py-3">Modelo</th>
                            <th class="px-6 py-3">Nº de série</th>
                            <th class="px-6 py-3">Paciente</th>
                            <th class="px-6 py-3">Fabricante</th>
                            <th class="px-6 py-3">Implante</th>
                            <th class="px-6 py-3">Situação</th>
                            <th class="px-6 py-3"><span class="sr-only">Ações</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($devices as $device)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $device->type->label() }}</td>
                                <td class="px-6 py-4">{{ $device->model }}</td>
                                <td class="px-6 py-4">{{ $device->serial_number }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('patients.show', $device->patient) }}" class="text-indigo-600 hover:text-indigo-900">{{ $device->patient->name }}</a>
                                </td>
                                <td class="px-6 py-4">{{ $device->manufacturer?->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $device->implanted_at?->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">{{ $device->status->label() }}</td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('devices.show', $device) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>

                                    @can('update', $device)
                                        <a href="{{ route('devices.edit', $device) }}" class="ms-3 text-indigo-600 hover:text-indigo-900">Editar</a>
                                    @endcan

                                    @can('delete', $device)
                                        <form method="POST" action="{{ route('devices.destroy', $device) }}" class="inline" onsubmit="return confirm('Remover este dispositivo e seus acompanhamentos?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ms-3 text-red-600 hover:text-red-800">Remover</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">Nenhum dispositivo encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 sm:px-0">
                {{ $devices->links() }}
            </div>
        </div>
    </div>
</x-app-layout>