@extends('layouts.admin')

@section('title', 'Gestión de Relatores')

@section('content')
    <div class="min-h-screen -m-4 p-4 sm:p-6 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">

        {{-- Encabezado --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">
                    Relatores
                </h1>
                <p class="text-slate-500 text-sm mt-1">
                    Gestión de docentes y colaboradores académicos
                </p>
            </div>

            <a href="{{ route('admin.relators.create') }}"
                class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-blue-500/30 transform hover:-translate-y-0.5">
                <i class="fa fa-plus mr-2"></i>
                Nuevo Relator
            </a>
        </div>

        {{-- Filtros y Búsqueda --}}
        <div class="mb-6 bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-sm border border-slate-100">
            <form action="{{ route('admin.relators.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <i class="fa fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar por nombre, login o correo..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all text-sm">
                </div>
                <button type="submit"
                    class="px-5 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition-colors text-sm">
                    Filtrar
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.relators.index') }}"
                        class="px-5 py-2.5 bg-slate-100 text-red-600 font-medium rounded-lg hover:bg-red-50 transition-colors text-sm flex items-center justify-center">
                        <i class="fa fa-times"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Tabla de Datos --}}
        <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                            <th class="px-6 py-4">Relator</th>
                            <th class="px-6 py-4">Contacto</th>
                            <th class="px-6 py-4">Facultad / Cargo</th>
                            <th class="px-6 py-4 text-center">Estado</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($relatores as $relator)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-bold mr-3 border border-blue-200">
                                            {{ strtoupper(substr($relator->rel_nombre, 0, 1)) }}{{ strtoupper(substr($relator->rel_apellido, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div
                                                class="font-semibold text-slate-700 group-hover:text-blue-600 transition-colors">
                                                <a href="{{ route('admin.relators.show', $relator->rel_login) }}"
                                                    class="hover:underline">
                                                    {{ $relator->rel_nombre }} {{ $relator->rel_apellido }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-slate-400 font-mono">
                                                ID: {{ $relator->rel_login }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-600 space-y-1">
                                        <div class="flex items-center">
                                            <i class="fa fa-envelope w-4 text-slate-400"></i>
                                            <span class="truncate max-w-[150px]"
                                                title="{{ $relator->rel_correo }}">{{ $relator->rel_correo }}</span>
                                        </div>
                                        @if($relator->rel_fono)
                                            <div class="flex items-center">
                                                <i class="fa fa-phone w-4 text-slate-400"></i>
                                                <span>{{ $relator->rel_fono }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        <div class="font-medium text-slate-700">
                                            {{ $relator->rel_facultad ?? 'No especificada' }}
                                        </div>
                                        <div class="text-slate-500 text-xs">{{ $relator->rel_cargo ?? '---' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{-- Simulación de estado si no existe campo estado, o usar lógica existente --}}
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Activo
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.relators.edit', $relator->rel_login) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-amber-500 hover:bg-amber-50 transition-colors"
                                            title="Editar">
                                            <i class="fa fa-pen"></i>
                                        </a>

                                        <button type="button" @click="$dispatch('confirm-action', { 
                                                            title: 'Eliminar Relator', 
                                                            message: '¿Estás seguro de querer eliminar a {{ $relator->rel_nombre }} {{ $relator->rel_apellido }}?',
                                                            itemName: '{{ $relator->rel_login }}',
                                                            type: 'delete',
                                                            formId: 'delete-form-{{ str_replace('.', '-', $relator->rel_login) }}' 
                                                        })"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-colors"
                                            title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>

                                        <form id="delete-form-{{ str_replace('.', '-', $relator->rel_login) }}"
                                            action="{{ route('admin.relators.destroy', $relator->rel_login) }}" method="POST"
                                            class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fa fa-user-slash text-4xl mb-3 text-slate-300"></i>
                                        <p>No se encontraron relatores.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($relatores->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $relatores->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection