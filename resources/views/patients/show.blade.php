<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $patient->name }}</h2>
                <p class="text-sm text-gray-500">Paciente desde {{ $patient->created_at->format('d/m/Y') }}</p>
            </div>

            <div class="flex items-center gap-2">
                @can('update', $patient)
                    <x-link-button variant="secondary" :href="route('patients.edit', $patient)">Editar</x-link-button>
                @endcan

                @can('create', [App\Models\Device::class, $patient])
                    <x-link-button :href="route('patients.devices.create', $patient)">Novo dispositivo</x-link-button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 lg:col-span-2">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">Dados do paciente</h3>

                    <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2 text-sm">
                        <div>
                            <dt class="text-gray-500">CPF</dt>
                            <dd class="font-medium text-gray-900">{{ $patient->cpf }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Data de nascimento</dt>
                            <dd class="font-medium text-gray-900">{{ $patient->birth_date?->format('d/m/Y') }} ({{ $patient->age }} anos)</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Tipo sanguíneo</dt>
                            <dd class="font-medium text-gray-900">{{ $patient->blood_type ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Telefone</dt>
                            <dd class="font-medium text-gray-900">{{ $patient->phone ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Contato de emergência</dt>
                            <dd class="font-medium text-gray-900">{{ $patient->emergency_contact_name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Telefone de emergência</dt>
                            <dd class="font-medium text-gray-900">{{ $patient->emergency_contact_phone ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Médico responsável</dt>
                            <dd class="font-medium text-gray-900">{{ $patient->doctor?->name ?? '—' }}</dd>
                        </div>
                    </dl>

                    @if ($patient->notes)
                        <div class="mt-4">
                            <dt class="text-gray-500 text-sm">Observações</dt>
                            <dd class="mt-1 text-gray-700 whitespace-pre-line">{{ $patient->notes }}</dd>
                        </div>
                    @endif
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">Resumo</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-500">Dispositivos</dt>
                            <dd class="font-medium text-gray-900">{{ $patient->devices->count() }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-500">Ativos</dt>
                            <dd class="font-medium text-gray-900">{{ $patient->devices->where('status', App\Enums\DeviceStatus::Active)->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-lg text-gray-800">Dispositivos implantados</h3>
                </div>

                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Tipo</th>
                            <th class="px-6 py-3">Modelo</th>
                            <th class="px-6 py-3">Fabricante</th>
                            <th class="px-6 py-3">Nº de série</th>
                            <th class="px-6 py-3">Implante</th>
                            <th class="px-6 py-3">Bateria</th>
                            <th class="px-6 py-3">Situação</th>
                            <th class="px-6 py-3"><span class="sr-only">Ações</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($patient->devices as $device)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $device->type->label() }}</td>
                                <td class="px-6 py-4">{{ $device->model }}</td>
                                <td class="px-6 py-4">{{ $device->manufacturer?->name ?? '—' }}</td>
                                <td class="px-6 py-4">{{ $device->serial_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $device->implanted_at?->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($device->latestCheckup)
                                        @if ($device->latestCheckup->battery_level <= App\Models\Checkup::LOW_BATTERY)
                                            <span class="font-medium text-red-600">{{ $device->latestCheckup->battery_level }}%</span>
                                        @else
                                            <span class="font-medium text-gray-900">{{ $device->latestCheckup->battery_level }}%</span>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $device->status->label() }}</td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('devices.show', $device) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">Nenhum dispositivo cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>