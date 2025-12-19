<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal del Estudiante - SPD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased text-gray-800">
    <div x-data="{ sidebarOpen: false }" x-init="$watch('sidebarOpen', value => {
        if (value) { document.body.classList.add('overflow-hidden'); }
        else { document.body.classList.remove('overflow-hidden'); }
    })" @resize.window="if (window.innerWidth >= 1024) sidebarOpen = false"
        class="min-h-screen flex flex-col flex-auto flex-shrink-0">

        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
            class="fixed flex flex-col top-0 left-0 w-64 bg-blue-900 h-full border-r border-blue-800 shadow-xl z-30 transition-transform duration-300 transform lg:translate-x-0">

            <!-- Sidebar Header -->
            <div class="flex items-center justify-center h-14 border-b border-blue-800 bg-blue-900">
                <div class="font-bold text-xl uppercase tracking-wider text-yellow-400">SPD Student</div>
            </div>

            <!-- Sidebar Content -->
            <div class="overflow-y-auto overflow-x-hidden flex-grow">
                <ul class="flex flex-col py-4 space-y-1">

                    <li class="px-5 pb-2">
                        <div class="flex flex-row items-center h-8">
                            <div class="text-xs font-semibold tracking-wide text-yellow-500 uppercase">Principal</div>
                        </div>
                    </li>

                    <!-- Dashboard / Mis Cursos -->
                    <li>
                        <a href="{{ route('participant.dashboard') }}"
                            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Mis Cursos</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('participant.agenda') }}"
                            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Agenda Académica</span>
                        </a>
                    </li>

                    @if(Auth::guard('participant')->user()->relator)
                        <li>
                            <a href="{{ route('participant.relator.my_courses') }}"
                                class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                                <span class="inline-flex justify-center items-center ml-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Portal Docente</span>
                            </a>
                        </li>
                    @endif

                    <li class="px-5 pt-6 pb-2">
                        <div class="flex flex-row items-center h-8">
                            <div class="text-xs font-semibold tracking-wide text-yellow-500 uppercase">Mi Cuenta</div>
                        </div>
                    </li>

                    <!-- Perfil -->
                    <li>
                        <a href="{{ route('participant.profile') }}"
                            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Mi Perfil</span>
                        </a>
                    </li>

                    <!-- Seguridad -->
                    <li>
                        <a href="{{ route('participant.security') }}"
                            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Seguridad</span>
                        </a>
                    </li>

                    <li class="px-5 pt-6 pb-2">
                        <div class="flex flex-row items-center h-8">
                            <div class="text-xs font-semibold tracking-wide text-yellow-500 uppercase">Ayuda</div>
                        </div>
                    </li>

                    <!-- Soporte -->
                    <li>
                        <a href="{{ route('participant.contact') }}"
                            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Soporte y Ayuda</span>
                        </a>
                    </li>

                    <!-- Cerrar Sesión -->
                    <li>
                        <form method="POST" action="{{ route('participant.logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full relative flex flex-row items-center h-11 focus:outline-none hover:bg-red-800 text-blue-100 hover:text-white border-l-4 border-transparent hover:border-red-500 pr-6 transition-colors duration-200">
                                <span class="inline-flex justify-center items-center ml-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Cerrar Sesión</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <!-- ./Sidebar -->

        <div class="flex flex-col h-full lg:ml-64 transition-all duration-300">
            {{-- Navbar --}}
            <header class="flex items-center justify-between h-14 bg-white border-b px-4 lg:px-6 sticky top-0 z-40">

                {{-- Botón menú móvil --}}
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                        <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>

                <div class="hidden lg:block"></div>

                {{-- User Profile --}}
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-gray-700">
                            {{ Auth::guard('participant')->user()->par_nombre ?? 'Estudiante' }}
                        </p>
                        <p class="text-xs text-gray-500">Participante</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md">
                        {{ strtoupper(substr(Auth::guard('participant')->user()->par_nombre ?? 'E', 0, 1)) }}
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-4 flex-grow bg-gray-100">
                @yield('content')
            </main>
        </div>

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden"></div>

    </div>
</body>

</html>