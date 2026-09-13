<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-900">
        <div class="min-h-screen flex flex-col">
            <header class="max-w-5xl w-full mx-auto flex items-center justify-between px-6 py-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold">
                    <x-application-logo class="h-9 w-9 fill-current text-rose-600" />
                    {{ config('app.name') }}
                </a>

                <nav class="flex items-center gap-4 text-sm">
                    @auth
                        <x-link-button :href="route('dashboard')">Ir para o painel</x-link-button>
                    @else
                        <a href="{{ route('login') }}" class="font-medium text-gray-700 hover:text-gray-900">Entrar</a>
                        <x-link-button :href="route('register')">Criar conta</x-link-button>
                    @endauth
                </nav>
            </header>

            <main class="flex-1 max-w-5xl w-full mx-auto px-6 py-16">
                <h1 class="max-w-3xl text-4xl font-semibold tracking-tight sm:text-5xl">
                    Os dados do seu implante cardíaco, sempre à mão.
                </h1>

                <p class="mt-6 max-w-2xl text-lg text-gray-600">
                    Registro de marca-passos, CDIs e outros dispositivos implantáveis: modelo, número de série,
                    compatibilidade com ressonância magnética e histórico de acompanhamentos.
                </p>

                <div class="mt-12 grid gap-6 sm:grid-cols-3">
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="font-semibold">Pacientes</h2>
                        <p class="mt-2 text-sm text-gray-600">Consultam o próprio passaporte com os dados de emergência e dos dispositivos.</p>
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="font-semibold">Médicos</h2>
                        <p class="mt-2 text-sm text-gray-600">Cadastram pacientes, dispositivos e registram as avaliações de bateria.</p>
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="font-semibold">Administradores</h2>
                        <p class="mt-2 text-sm text-gray-600">Gerenciam fabricantes, contas e perfis de acesso.</p>
                    </div>
                </div>
            </main>

            <footer class="py-6 text-center text-sm text-gray-500">
                Projeto acadêmico · Laravel {{ app()->version() }}
            </footer>
        </div>
    </body>
</html>
