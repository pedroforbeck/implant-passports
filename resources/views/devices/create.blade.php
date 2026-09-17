<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Novo dispositivo</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="mb-6 text-sm text-gray-600">
                    Paciente: <a href="{{ route('patients.show', $patient) }}" class="font-medium text-indigo-600 hover:text-indigo-900">{{ $patient->name }}</a>
                </div>

                <form method="POST" action="{{ route('patients.devices.store', $patient) }}" class="space-y-6">
                    @csrf

                    @include('devices.partials.form')

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('patients.show', $patient) }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
                        <x-primary-button>Cadastrar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>