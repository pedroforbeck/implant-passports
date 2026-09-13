<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fabricantes</h2>

            @can('create', App\Models\Manufacturer::class)
                <x-link-button :href="route('manufacturers.create')">Novo fabricante</x-link-button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Nome</th>
                            <th class="px-6 py-3">País</th>
                            <th class="px-6 py-3">Site</th>
                            <th class="px-6 py-3"><span class="sr-only">Ações</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($manufacturers as $manufacturer)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $manufacturer->name }}</td>
                                <td class="px-6 py-4">{{ $manufacturer->country ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if ($manufacturer->website)
                                        <a href="{{ $manufacturer->website }}" target="_blank" rel="noopener" class="text-indigo-600 hover:text-indigo-900">{{ parse_url($manufacturer->website, PHP_URL_HOST) ?? $manufacturer->website }}</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('manufacturers.edit', $manufacturer) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>

                                    <form method="POST" action="{{ route('manufacturers.destroy', $manufacturer) }}" class="inline" onsubmit="return confirm('Remover este fabricante?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ms-3 text-red-600 hover:text-red-800">Remover</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">Nenhum fabricante cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 sm:px-0">
                {{ $manufacturers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
