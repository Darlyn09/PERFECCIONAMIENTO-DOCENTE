<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - SPD</title>
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
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div x-data="{ sidebarOpen: false }"
        class="min-h-screen flex flex-col flex-auto flex-shrink-0 antialiased bg-gray-50 text-gray-800">

        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
            class="fixed flex flex-col top-0 left-0 w-64 bg-blue-institutional h-full border-r border-blue-800 shadow-xl z-30 transition-transform duration-300 transform lg:translate-x-0">
            <div class="flex items-center justify-center h-14 border-b border-blue-800 bg-blue-institutional">
                <div class="font-bold text-xl uppercase tracking-wider text-gold-institutional">SPD Admin</div>
            </div>

            <div class="overflow-y-auto overflow-x-hidden flex-grow">
                <ul class="flex flex-col py-4 space-y-1">

                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-gold-institutional border-l-4 border-transparent hover:border-gold-institutional pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Inicio</span>
                        </a>
                    </li>

                    <li class="px-5 pt-6 pb-2">
                        <div class="flex flex-row items-center h-8">
                            <div class="text-xs font-semibold tracking-wide text-yellow-500 uppercase">Gestión
                                Académica
                            </div>
                        </div>
                    </li>

                    <!-- Eventos -->
                    <li>
                        <a href="{{ route('admin.events.index') }}"
                            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Eventos</span>
                        </a>
                    </li>

                    <!-- Mis Cursos (Relator) -->
                    @if(Auth::user()->relator)
                        <li>
                            <a href="{{ route('admin.relator.my_courses') }}"
                                class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                                <span class="inline-flex justify-center items-center ml-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Mis Cursos (Relator)</span>
                            </a>
                        </li>
                    @endif

                    <!-- Cursos -->
                    <li>
                        <a href="{{ route('admin.courses.index') }}"
                            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Cursos y Talleres</span>
                        </a>
                    </li>
                    <!-- Nueva Categoría -->
                    <li>
                        <a href="{{ route('admin.categorias.create') }}"
                            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Nueva Categoría</span>
                        </a>
                    </li>
                    <!-- Relatores (Docentes) -->
                    <li>
                        <a href="{{ route('admin.teachers.index') }}"
                            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Relatores</span>
                        </a>
                    </li>


                    <!-- Certificados -->
                    <li>
                        <a href="{{ route('admin.certificates.index') }}"
                            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Certificados</span>
                        </a>
                    </li>



                    {{-- <!-- Participantes -->
                    <li>
                        <a href="{{ route('admin.participants.index') }}"
                            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Participantes</span>
                        </a>
                    </li> --}}

                    <li class="px-5 pt-6 pb-2">
                        <div class="flex flex-row items-center h-8">
                            <div class="text-xs font-semibold tracking-wide text-yellow-500 uppercase">
                                Administración
                            </div>
                        </div>
                    </li>

                    <!-- Usuarios -->
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Usuarios</span>
                        </a>
                    </li>

                    <!-- Configuración -->
                    <li>
                        <a href="{{ route('admin.settings.index') }}"
                            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Configuración</span>
                        </a>
                    </li>

                    <!-- Ayuda -->
                    <li class="px-5 pt-6 pb-2">
                        <div class="flex flex-row items-center h-8">
                            <div class="text-xs font-semibold tracking-wide text-yellow-500 uppercase">Soporte</div>
                        </div>
                    </li>

                    <li>
                        <button @click="$dispatch('open-help-modal')"
                            class="w-full relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 text-blue-100 hover:text-yellow-300 border-l-4 border-transparent hover:border-yellow-400 pr-6 transition-colors duration-200">
                            <span class="inline-flex justify-center items-center ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate">Ayuda - Iconos</span>
                        </button>
                    </li>

                    <!-- Logout -->
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
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
            {{-- Barra superior solo para móvil y usuario --}}
            <div class="flex items-center justify-between h-14 bg-white border-b px-4 lg:px-6">
                {{-- Botón menú móvil --}}
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                        <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>

                {{-- Espaciador en desktop --}}
                <div class="hidden lg:block"></div>

                {{-- Usuario logueado --}}
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500">Administrador</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="p-4 flex-grow">
                @yield('content')
            </main>
        </div>

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden"></div>

        <!-- Modal de Ayuda - Iconos del Sistema -->
        <div x-data="{ open: false }" @open-help-modal.window="open = true" x-show="open" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto">

            <!-- Overlay con blur -->
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false"
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

            <!-- Modal Panel -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="open" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95" @click.away="open = false"
                    class="relative bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl max-w-2xl w-full max-h-[75vh] overflow-hidden border border-white/50">

                    <!-- Header del Modal -->
                    <div
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-white">Guía de Iconos</h2>
                                <p class="text-blue-100 text-xs">Referencia rápida del sistema</p>
                            </div>
                        </div>
                        <button @click="open = false"
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/10 text-white/80 hover:bg-white/20 hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Contenido del Modal -->
                    <div class="p-5 overflow-y-auto max-h-[calc(75vh-60px)]">

                        <!-- Iconos de Acciones -->
                        <div class="mb-5">
                            <h3 class="text-sm font-semibold text-slate-700 mb-3 uppercase tracking-wide">Acciones</h3>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <div class="w-7 h-7 bg-blue-500 rounded flex items-center justify-center mr-2">
                                        <i class="fa fa-eye text-white text-xs"></i>
                                    </div>
                                    <span class="text-xs text-slate-600">Ver</span>
                                </div>
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <div class="w-7 h-7 bg-amber-500 rounded flex items-center justify-center mr-2">
                                        <i class="fa fa-pen text-white text-xs"></i>
                                    </div>
                                    <span class="text-xs text-slate-600">Editar</span>
                                </div>
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <div class="w-7 h-7 bg-red-500 rounded flex items-center justify-center mr-2">
                                        <i class="fa fa-stop text-white text-xs"></i>
                                    </div>
                                    <span class="text-xs text-slate-600">Terminar</span>
                                </div>
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <div class="w-7 h-7 bg-green-500 rounded flex items-center justify-center mr-2">
                                        <i class="fa fa-plus text-white text-xs"></i>
                                    </div>
                                    <span class="text-xs text-slate-600">Nuevo</span>
                                </div>
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <div class="w-7 h-7 bg-red-500 rounded flex items-center justify-center mr-2">
                                        <i class="fa fa-trash text-white text-xs"></i>
                                    </div>
                                    <span class="text-xs text-slate-600">Eliminar</span>
                                </div>
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <div class="w-7 h-7 bg-indigo-500 rounded flex items-center justify-center mr-2">
                                        <i class="fa fa-filter text-white text-xs"></i>
                                    </div>
                                    <span class="text-xs text-slate-600">Filtrar</span>
                                </div>
                            </div>
                        </div>

                        <!-- Estados -->
                        <div class="mb-5">
                            <h3 class="text-sm font-semibold text-slate-700 mb-3 uppercase tracking-wide">Estados</h3>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">
                                    <i class="fa fa-circle text-[5px] mr-1.5 animate-pulse"></i> Activo
                                </span>
                                <span
                                    class="inline-flex items-center px-2.5 py-1 bg-slate-200 text-slate-600 text-xs font-medium rounded-full">
                                    <i class="fa fa-circle text-[5px] mr-1.5"></i> Terminado
                                </span>
                                <span
                                    class="inline-flex items-center px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">
                                    Habilitado
                                </span>
                                <span
                                    class="inline-flex items-center px-2.5 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">
                                    Inhabilitado
                                </span>
                            </div>
                        </div>

                        <!-- Módulos -->
                        <div class="mb-5">
                            <h3 class="text-sm font-semibold text-slate-700 mb-3 uppercase tracking-wide">Módulos</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <i class="fa fa-graduation-cap text-blue-500 w-6"></i>
                                    <span class="text-xs text-slate-600 ml-2">Cursos</span>
                                </div>
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <i class="fa fa-calendar-alt text-indigo-500 w-6"></i>
                                    <span class="text-xs text-slate-600 ml-2">Eventos</span>
                                </div>
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <i class="fa fa-user-tie text-purple-500 w-6"></i>
                                    <span class="text-xs text-slate-600 ml-2">Relatores</span>
                                </div>
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <i class="fa fa-users text-teal-500 w-6"></i>
                                    <span class="text-xs text-slate-600 ml-2">Participantes</span>
                                </div>
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <i class="fa fa-folder text-amber-500 w-6"></i>
                                    <span class="text-xs text-slate-600 ml-2">Categorías</span>
                                </div>
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <i class="fa fa-chalkboard text-cyan-500 w-6"></i>
                                    <span class="text-xs text-slate-600 ml-2">Programas</span>
                                </div>
                            </div>
                        </div>

                        <!-- Modalidades -->
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-slate-700 mb-3 uppercase tracking-wide">Modalidades
                            </h3>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <i class="fa fa-building text-slate-500 w-6"></i>
                                    <span class="text-xs text-slate-600 ml-2">Presencial</span>
                                </div>
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <i class="fa fa-video text-blue-500 w-6"></i>
                                    <span class="text-xs text-slate-600 ml-2">Online</span>
                                </div>
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <i class="fa fa-play-circle text-green-500 w-6"></i>
                                    <span class="text-xs text-slate-600 ml-2">Asincrónico</span>
                                </div>
                                <div class="flex items-center p-2 bg-slate-50/80 rounded-lg">
                                    <i class="fa fa-laptop-house text-purple-500 w-6"></i>
                                    <span class="text-xs text-slate-600 ml-2">Híbrido</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tip -->
                        <div class="bg-blue-50/80 rounded-lg p-3 flex items-center">
                            <i class="fa fa-lightbulb text-blue-500 mr-2"></i>
                            <p class="text-xs text-blue-700">Pasa el cursor sobre los botones para ver su descripción.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmación Global -->
        <div x-data="confirmModal()" x-show="isOpen" x-cloak @confirm-action.window="openModal($event.detail)"
            class="fixed inset-0 z-50 overflow-y-auto" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">

            {{-- Overlay --}}
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal()"></div>

            {{-- Modal --}}
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                    class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">

                    {{-- Header con icono --}}
                    <div class="p-6 pb-0">
                        <div class="flex items-center justify-center">
                            <div :class="iconBgClass"
                                class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg">
                                <template x-if="actionType === 'delete'">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </template>
                                <template x-if="actionType === 'disable'">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                        </path>
                                    </svg>
                                </template>
                                <template x-if="actionType === 'enable'">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </template>
                                <template x-if="actionType === 'edit'">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </template>
                                <template x-if="actionType === 'terminate'">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z">
                                        </path>
                                    </svg>
                                </template>
                                <template x-if="actionType === 'warning'">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Contenido --}}
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-slate-800 mb-2" x-text="title"></h3>
                        <p class="text-slate-500" x-text="message"></p>

                        <div x-show="itemName" class="mt-3 p-3 bg-slate-100 rounded-xl">
                            <p class="text-sm text-slate-600">
                                <span class="font-semibold" x-text="itemName"></span>
                            </p>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="p-6 pt-0 flex gap-3">
                        <button @click="closeModal()"
                            class="flex-1 px-5 py-3 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                            Cancelar
                        </button>
                        <button @click="confirmAction()" :class="confirmBtnClass"
                            class="flex-1 px-5 py-3 text-white font-semibold rounded-xl shadow-lg transition-all hover:shadow-xl">
                            <span x-text="confirmText"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function confirmModal() {
                return {
                    isOpen: false,
                    title: '',
                    message: '',
                    itemName: '',
                    actionType: 'warning',
                    formId: null,
                    redirectUrl: null,

                    get iconBgClass() {
                        const classes = {
                            'delete': 'bg-gradient-to-br from-red-500 to-red-700',
                            'disable': 'bg-gradient-to-br from-red-500 to-red-700',
                            'enable': 'bg-gradient-to-br from-emerald-500 to-emerald-700',
                            'edit': 'bg-gradient-to-br from-amber-500 to-amber-700',
                            'terminate': 'bg-gradient-to-br from-red-500 to-red-700',
                            'warning': 'bg-gradient-to-br from-amber-500 to-amber-700'
                        };
                        return classes[this.actionType] || classes['warning'];
                    },

                    get confirmBtnClass() {
                        const classes = {
                            'delete': 'bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800',
                            'disable': 'bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800',
                            'enable': 'bg-gradient-to-r from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800',
                            'edit': 'bg-gradient-to-r from-amber-500 to-amber-700 hover:from-amber-600 hover:to-amber-800',
                            'terminate': 'bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800',
                            'warning': 'bg-gradient-to-r from-amber-500 to-amber-700 hover:from-amber-600 hover:to-amber-800'
                        };
                        return classes[this.actionType] || classes['warning'];
                    },

                    get confirmText() {
                        const texts = {
                            'delete': 'Sí, eliminar',
                            'disable': 'Sí, inhabilitar',
                            'enable': 'Sí, habilitar',
                            'edit': 'Sí, editar',
                            'terminate': 'Sí, terminar',
                            'warning': 'Confirmar'
                        };
                        return texts[this.actionType] || texts['warning'];
                    },

                    openModal(detail) {
                        this.title = detail.title || '¿Confirmar acción?';
                        this.message = detail.message || '¿Estás seguro de realizar esta acción?';
                        this.itemName = detail.itemName || '';
                        this.actionType = detail.type || 'warning';
                        this.formId = detail.formId || null;
                        this.redirectUrl = detail.redirectUrl || null;
                        this.isOpen = true;
                    },

                    closeModal() {
                        this.isOpen = false;
                    },

                    confirmAction() {
                        if (this.formId) {
                            document.getElementById(this.formId).submit();
                        } else if (this.redirectUrl) {
                            window.location.href = this.redirectUrl;
                        }
                        this.closeModal();
                    }
                }
            }
        </script>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </div>
</body>

</html>