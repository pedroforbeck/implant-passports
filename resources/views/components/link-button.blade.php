@props(['variant' => 'primary'])

@php
    $variantClasses = match ($variant) {
        'secondary' => 'bg-white border border-gray-300 text-gray-700 shadow-sm hover:bg-gray-50',
        default => 'bg-gray-800 border border-transparent text-white hover:bg-gray-700',
    };
@endphp

<a {{ $attributes->merge(['class' => "inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 {$variantClasses}"]) }}>
    {{ $slot }}
</a>
