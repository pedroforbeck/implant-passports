<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Avaliações — {{ $device->type->label() }} {{ $device->model }}</h2>
                <p class="text-sm text-gray-500">
                    <a href="{{ route('patients.show', $device->patient) }}" class="text-indigo-600 hover:text-indigo-900">{{ $device->patient->name }}</a>
                    · <a href="{{ route('devices.show', $device) }}" class="text-indigo-600 hover:text-indigo-900">ver dispositivo</a>
                </p>
            </div>

            @can('create', [App\Models\Checkup::class, $device])
                <x-link-button :href="route('devices.checkups.create', $device)">Nova avaliação</x-link-button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Data</th>
                            <th class="px-6 py-3">Bateria</th>
                            <th class="px-6 py-3">Médico</th>
                            <th class="px-6 py-3">Observações</th>
                            <th class="px-6 py-3"><span class="sr-only">Ações</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($checkups as $checkup)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $checkup->checked_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    @if ($checkup->battery_level <= App\Models\Checkup::LOW_BATTERY)
                                        <span class="font-medium text-red-600">{{ $checkup->battery_level }}%</span>
                                    @else
                                        <span class="font-medium text-gray-900">{{ $checkup->battery_level }}%</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $checkup->doctor?->name ?? '—' }}</td>
                                <td class="px-6 py-4 max-w-md">{{ $checkup->notes ?? '—' }}</td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    @can('update', $checkup)
                                        <a href="{{ route('devices.checkups.edit', [$checkup->device, $checkup]) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                    @endcan

                                    @can('delete', $checkup)
                                        <form method="POST" action="{{ route('devices.checkups.destroy', [$checkup->device, $checkup]) }}" class="inline" onsubmit="return confirm('Remover esta avaliação?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ms-3 text-red-600 hover:text-red-800">Remover</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Nenhuma avaliação registrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 sm:px-0">
                {{ $checkups->links() }}
            </div>
        </div>
    </div>
</x-app-layout>