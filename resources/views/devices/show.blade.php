<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $device->type->label() }} — {{ $device->model }}</h2>
                <p class="text-sm text-gray-500">
                    <a href="{{ route('patients.show', $device->patient) }}" class="text-indigo-600 hover:text-indigo-900">{{ $device->patient->name }}</a>
                    · registro nº {{ $device->serial_number }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <x-link-button variant="secondary" :href="route('devices.index')">Todos os dispositivos</x-link-button>

                @can('update', $device)
                    <x-link-button variant="secondary" :href="route('devices.edit', $device)">Editar</x-link-button>
                @endcan

                @can('delete', $device)
                    <form method="POST" action="{{ route('devices.destroy', $device) }}" onsubmit="return confirm('Remover este dispositivo e seus acompanhamentos?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-red-600 hover:bg-red-500 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">Remover</button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 lg:col-span-2">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">Dados do dispositivo</h3>

                    <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2 text-sm">
                        <div>
                            <dt class="text-gray-500">Fabricante</dt>
                            <dd class="font-medium text-gray-900">{{ $device->manufacturer?->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Modelo</dt>
                            <dd class="font-medium text-gray-900">{{ $device->model }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Número de série</dt>
                            <dd class="font-medium text-gray-900">{{ $device->serial_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Data do implante</dt>
                            <dd class="font-medium text-gray-900">{{ $device->implanted_at?->format('d/m/Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Hospital / instituição</dt>
                            <dd class="font-medium text-gray-900">{{ $device->hospital ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Situação</dt>
                            <dd class="font-medium text-gray-900">{{ $device->status->label() }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-gray-500">Compatível com ressonância (MRI)</dt>
                            <dd class="font-medium text-gray-900">{{ $device->mri_conditional ? 'Sim' : 'Não' }}</dd>
                        </div>
                    </dl>

                    @if ($device->notes)
                        <div class="mt-4">
                            <dt class="text-gray-500 text-sm">Observações</dt>
                            <dd class="mt-1 text-gray-700 whitespace-pre-line">{{ $device->notes }}</dd>
                        </div>
                    @endif
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-lg text-gray-800">Última avaliação</h3>

                        @can('create', [App\Models\Checkup::class, $device])
                            <x-link-button :href="route('devices.checkups.create', $device)">Nova avaliação</x-link-button>
                        @endcan
                    </div>

                    @if ($device->latestCheckup)
                        <dl class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">Data</dt>
                                <dd class="font-medium text-gray-900">{{ $device->latestCheckup->checked_at->format('d/m/Y') }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">Bateria</dt>
                                <dd class="font-medium @if ($device->latestCheckup->battery_level <= App\Models\Checkup::LOW_BATTERY) text-red-600 @else text-gray-900 @endif">
                                    {{ $device->latestCheckup->battery_level }}%
                                </dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">Médico</dt>
                                <dd class="font-medium text-gray-900">{{ $device->latestCheckup->doctor?->name ?? '—' }}</dd>
                            </div>

                            @if ($device->latestCheckup->battery_level <= App\Models\Checkup::LOW_BATTERY)
                                <p class="rounded-md bg-red-50 border border-red-200 px-3 py-2 text-red-700">Bateria baixa: considere agendar substituição.</p>
                            @endif

                            @if ($device->latestCheckup->notes)
                                <div>
                                    <dt class="text-gray-500">Observações</dt>
                                    <dd class="mt-1 text-gray-700 whitespace-pre-line">{{ $device->latestCheckup->notes }}</dd>
                                </div>
                            @endif
                        </dl>
                    @else
                        <p class="text-sm text-gray-500">Nenhuma avaliação registrada.</p>
                    @endif

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('devices.checkups.index', $device) class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Ver histórico de avaliações →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>