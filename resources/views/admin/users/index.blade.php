@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -m-4 p-4 sm:p-6">
        {{-- Header --}}
        <div class="mb-4 sm:mb-6">
            {{-- Botón Volver Destacado --}}
            <a href="javascript:history.back()"
                class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-4 sm:mb-5 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm sm:text-base font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
                <i class="fa fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                <span class="hidden sm:inline">Volver</span>
                <span class="sm:hidden">Volver</span>
            </a>

            {{-- Header Principal --}}
            <div
                class="relative mb-6 bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl overflow-hidden shadow-2xl">
                {{-- Decoración de fondo --}}
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute -right-20 -top-20 w-80 h-80 border-[20px] border-amber-400/30 rounded-full"></div>
                    <div class="absolute -left-16 -bottom-16 w-64 h-64 border-[16px] border-blue-400/20 rounded-full"></div>
                </div>

                <div class="relative px-6 sm:px-8 py-6 sm:py-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        {{-- Info --}}
                        <div class="flex items-center gap-5">
                            <div
                                class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-xl shadow-amber-500/30">
                                <i class="fa fa-users text-white text-2xl sm:text-3xl"></i>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Gestión de Usuarios</h1>
                                <p class="text-indigo-200 text-sm">Administración de cuentas y permisos de acceso</p>
                            </div>
                        </div>

                        {{-- Botón Nuevo --}}
                        <a href="{{ route('admin.users.create') }}"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            <i class="fa fa-user-plus mr-2"></i> Nuevo Usuario
                        </a>
                    </div>
                </div>
            </div>

            {{-- Búsqueda y Filtros --}}
            <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-6 border border-slate-200">
                <div class="flex items-center mb-4">
                    <div
                        class="w-8 sm:w-10 h-8 sm:h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg sm:rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-blue-500/30">
                        <i class="fa fa-search text-white text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm sm:text-base">Buscar Usuarios</h3>
                        <p class="text-xs text-slate-500 hidden sm:block">Encuentra cuentas rápidamente</p>
                    </div>
                </div>
                <form method="GET" class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1 relative">
                        <i class="fa fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all placeholder-gray-400 shadow-sm"
                            placeholder="🔍 Buscar por nombre, RUT o correo...">
                    </div>
                    <div class="w-full lg:w-48 relative">
                        <i class="fa fa-shield-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 z-10"></i>
                        <select name="rol"
                            class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all appearance-none cursor-pointer shadow-sm">
                            <option value="">👤 Todos los roles</option>
                            <option value="admin" {{ request('rol') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('rol') == 'user' ? 'selected' : '' }}>Usuario</option>
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all">
                            <i class="fa fa-filter mr-2"></i> Filtrar
                        </button>
                        @if(request()->hasAny(['search', 'rol']))
                            <a href="{{ route('admin.users.index') }}"
                                class="inline-flex items-center px-5 py-3.5 bg-gradient-to-r from-red-500 to-rose-600 text-white text-sm font-bold rounded-xl hover:from-red-600 hover:to-rose-700 shadow-lg shadow-red-500/30 hover:shadow-xl transition-all">
                                <i class="fa fa-times mr-2"></i> Limpiar
                            </a>
                        @endif
                    </div>
                </form>
            </div>


            {{-- Stats Rápidas --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-2xl p-4 shadow-lg border-l-4 border-blue-500 relative overflow-hidden group">
                    <div
                        class="absolute right-0 top-0 h-full w-16 bg-gradient-to-l from-blue-50 to-transparent opacity-50 transition-all group-hover:w-24">
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm text-slate-500 font-semibold mb-1">Total Usuarios</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalUsuarios }}</p>
                    </div>
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-lg border-l-4 border-indigo-500 relative overflow-hidden group">
                    <div
                        class="absolute right-0 top-0 h-full w-16 bg-gradient-to-l from-indigo-50 to-transparent opacity-50 transition-all group-hover:w-24">
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm text-slate-500 font-semibold mb-1">Administradores</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalAdmins }}</p>
                    </div>
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                        <i class="fa fa-user-shield"></i>
                    </div>
                </div>
                <div
                    class="bg-white rounded-2xl p-4 shadow-lg border-l-4 border-emerald-500 relative overflow-hidden group">
                    <div
                        class="absolute right-0 top-0 h-full w-16 bg-gradient-to-l from-emerald-50 to-transparent opacity-50 transition-all group-hover:w-24">
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm text-slate-500 font-semibold mb-1">Usuarios Regulares</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalRegular }}</p>
                    </div>
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                        <i class="fa fa-user"></i>
                    </div>
                </div>
            </div>

            {{-- Listado de Usuarios --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white shadow-lg overflow-hidden">
                {{-- Header de tabla --}}
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-4 sm:px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-white font-semibold flex items-center text-sm sm:text-base">
                            <i class="fa fa-users mr-2 sm:mr-3"></i>
                            Listado de Usuarios Registrados
                        </h2>
                        <span
                            class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $usuarios->total() }}
                            registros</span>
                    </div>
                </div>

                {{-- Vista Móvil (Cards) --}}
                <div class="block xl:hidden divide-y divide-slate-100">
                    @forelse($usuarios as $u)
                        <div class="p-4 hover:bg-slate-50/50 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center flex-1 min-w-0">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mr-3 shadow-lg flex-shrink-0">
                                        <span class="text-lg font-bold text-white">
                                            {{ strtoupper(substr($u->par_nombre ?? 'U', 0, 1)) }}{{ strtoupper(substr($u->par_apellido ?? '', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.users.show', $u->par_login) }}"
                                            class="font-bold text-slate-800 hover:text-blue-600 transition-colors block truncate">
                                            {{ $u->par_nombre }} {{ $u->par_apellido }}
                                        </a>
                                        <p class="text-xs text-slate-400 flex items-center mt-0.5">
                                            <i class="fa fa-id-card mr-1"></i>
                                            {{ $u->par_login }}
                                        </p>
                                    </div>
                                </div>
                                <div class="ml-2">
                                    <span
                                        class="inline-flex items-center px-2 py-1 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-full">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                                        Activo
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-2 mb-4 text-sm">
                                <div class="flex items-center text-slate-600">
                                    <i class="fa fa-envelope text-blue-400 mr-2 w-4"></i>
                                    <span class="truncate">{{ $u->par_correo }}</span>
                                </div>
                                @if($u->par_cargo)
                                    <div class="flex items-center text-slate-600">
                                        <i class="fa fa-briefcase text-amber-400 mr-2 w-4"></i>
                                        {{ $u->par_cargo }}
                                    </div>
                                @endif
                                @if($u->par_facultad)
                                    <div class="flex items-center text-slate-600">
                                        <i class="fa fa-university text-indigo-400 mr-2 w-4"></i>
                                        {{ $u->par_facultad }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.show', $u->par_login) }}"
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                                    <i class="fa fa-eye mr-2"></i> Ver
                                </a>
                                <a href="{{ route('admin.users.edit', $u->par_login) }}"
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-amber-400 to-amber-600 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                                    <i class="fa fa-pencil-alt mr-2"></i> Editar
                                </a>
                                {{-- Assuming delete uses a form, implementing a simple button for now or reusing the form logic
                                --}}
                                <form action="{{ route('admin.users.destroy', $u->par_login) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('¿Eliminar usuario?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-10 h-10 inline-flex items-center justify-center bg-red-100 text-red-600 rounded-xl hover:bg-red-200 transition-colors">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500">No se encontraron usuarios.</div>
                    @endforelse
                </div>

                {{-- Vista Desktop (Tabla) --}}
                <div class="hidden xl:block">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Usuario</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Contacto</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Cargo / Facultad</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Estado</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($usuarios as $u)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mr-4 shadow-lg shrink-0">
                                                <span class="text-lg font-bold text-white">
                                                    {{ strtoupper(substr($u->par_nombre ?? 'U', 0, 1)) }}{{ strtoupper(substr($u->par_apellido ?? '', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.users.show', $u->par_login) }}"
                                                    class="font-semibold text-slate-800 hover:text-blue-600 transition-colors block">
                                                    {{ $u->par_nombre }} {{ $u->par_apellido }}
                                                </a>
                                                <p class="text-xs text-slate-400 flex items-center mt-0.5">
                                                    <i class="fa fa-id-badge mr-1.5"></i>
                                                    {{ $u->par_login }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center text-slate-600 text-sm">
                                            <i class="fa fa-envelope text-slate-400 w-4 mr-2"></i>
                                            {{ $u->par_correo }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            @if($u->par_cargo)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-medium rounded-lg border border-amber-200">
                                                    <i class="fa fa-briefcase text-amber-400 mr-1.5"></i>
                                                    {{ Str::limit($u->par_cargo, 20) }}
                                                </span>
                                            @endif
                                            @if($u->par_facultad)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-lg border border-indigo-200">
                                                    <i class="fa fa-university text-indigo-400 mr-1.5"></i>
                                                    {{ Str::limit($u->par_facultad, 20) }}
                                                </span>
                                            @endif
                                            @if(!$u->par_cargo && !$u->par_facultad)
                                                <span class="text-slate-400 text-sm">—</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                                            Activo
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.users.show', $u->par_login) }}"
                                                class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-xl hover:from-blue-600 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                                                title="Ver perfil">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $u->par_login) }}"
                                                class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 text-white rounded-xl hover:from-amber-500 hover:to-amber-700 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                                                title="Editar">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $u->par_login) }}" method="POST"
                                                onsubmit="return confirm('¿Eliminar usuario?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-red-500 to-red-700 text-white rounded-xl hover:from-red-600 hover:to-red-800 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                                                    title="Eliminar">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                                                <i class="fa fa-users text-2xl text-slate-300"></i>
                                            </div>
                                            <span class="text-lg font-medium text-slate-600">No se encontraron usuarios</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="mt-0">
                    {{ $usuarios->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection