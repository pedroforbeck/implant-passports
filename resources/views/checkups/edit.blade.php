<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar avaliação</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="mb-6 text-sm text-gray-600">
                    Dispositivo:
                    <a href="{{ route('devices.show', $device) }}" class="font-medium text-indigo-600 hover:text-indigo-900">
                        {{ $device->type->label() }} {{ $device->model }} ({{ $device->serial_number }})
                    </a>
                </div>

                <form method="POST" action="{{ route('devices.checkups.update', [$checkup->device, $checkup]) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @include('checkups.partials.form')

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('devices.checkups.index', $checkup->device) }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
                        <x-primary-button>Salvar alterações</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>