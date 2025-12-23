@extends('layouts.admin')

@section('title', 'Gestión de Relatores')

@section('content')
    <div x-data="{ 
                                            selected: [], 
                                            allSelected: false,
                                            toggleAll() {
                                                this.allSelected = !this.allSelected;
                                                if (this.allSelected) {
                                                    this.selected = {{ $relatores->pluck('rel_login')->toJson() }};
                                                } else {
                                                    this.selected = [];
                                                }
                                            }
                                        }"
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -m-4 p-4 sm:p-6 relative">

        {{-- Barras de Acción Flotante --}}
        <div x-show="selected.length > 0" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0"
            class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-slate-900 text-white px-6 py-3 rounded-2xl shadow-2xl z-50 flex items-center gap-4 border border-slate-700">

            <span class="text-sm font-medium">
                <span x-text="selected.length" class="font-bold text-amber-400"></span> relatores seleccionados
            </span>

            <div class="h-6 w-px bg-white/20"></div>

            <form id="mass-delete-form" action="{{ route('admin.relators.mass_destroy') }}" method="POST">
                @csrf
                {{-- Solución Alpine para array en form --}}
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>

                <button type="button" @click.prevent="$dispatch('confirm-action', { 
                                            title: 'Eliminar Relatores Seleccionados', 
                                            message: '¿Estás seguro de eliminar ' + selected.length + ' relatores? Esta acción no se puede deshacer.', 
                                            type: 'delete',
                                            formId: 'mass-delete-form' 
                                        })"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg text-sm font-bold transition-colors flex items-center shadow-lg shadow-red-500/30">
                    <i class="fa fa-trash-alt mr-2"></i> Eliminar
                </button>
            </form>

            <div class="h-6 w-px bg-white/20"></div>

            <button @click="selected = []; allSelected = false"
                class="text-slate-400 hover:text-white text-sm transition-colors">
                Cancelar
            </button>
        </div>

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
                                <i class="fa fa-chalkboard-teacher text-white text-2xl sm:text-3xl"></i>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Gestión de Relatores</h1>
                                <p class="text-indigo-200 text-sm">Administración de docentes y colaboradores académicos</p>
                            </div>
                        </div>

                        {{-- Botones de Acción --}}
                        {{-- Botones de Acción --}}
                        <div class="flex flex-wrap gap-3">
                            {{-- Botón Importar Excel --}}
                            <div x-data="{ open: false }">
                                <button @click="open = true"
                                    class="inline-flex items-center justify-center px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 text-white font-semibold rounded-xl hover:bg-emerald-500/20 hover:border-emerald-500/30 transition-all">
                                    <i class="fas fa-file-excel mr-2 text-emerald-400"></i> Importar Masivo
                                </button>

                                {{-- Modal --}}
                                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6"
                                    style="display: none;">
                                    <div class="fixed inset-0 bg-slate-900/60 transition-opacity" @click="open = false">
                                    </div>

                                    <div
                                        class="bg-white rounded-2xl shadow-2xl transform transition-all sm:max-w-lg w-full p-6 relative z-10">
                                        <div class="flex items-center justify-between mb-5">
                                            <h3 class="text-xl font-bold text-slate-800">Carga Masiva de Relatores</h3>
                                            <button @click="open = false" class="text-slate-400 hover:text-slate-500">
                                                <i class="fas fa-times text-xl"></i>
                                            </button>
                                        </div>

                                        <div
                                            class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 text-sm text-blue-800">
                                            <p class="font-bold mb-1"><i class="fas fa-info-circle mr-1"></i> Instrucciones:
                                            </p>
                                            <p>Sube un archivo Excel (.xlsx) con las siguientes columnas:</p>
                                            <ul class="list-disc list-inside mt-2 ml-1 text-blue-700">
                                                <li><b>correo</b> (Requerido)</li>
                                                <li>nombres</li>
                                                <li>apellidos</li>
                                                <li>rut (Opcional, login)</li>
                                                <li>cargo, facultad, telefono</li>
                                            </ul>
                                        </div>

                                        <form action="{{ route('admin.relators.import') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-6">
                                                <label class="block text-sm font-bold text-slate-700 mb-2">Seleccionar
                                                    Archivo Excel</label>
                                                <input type="file" name="file" accept=".xlsx,.xls,.numbers,.csv" required
                                                    class="block w-full text-sm text-slate-500
                                                                              file:mr-4 file:py-2.5 file:px-4
                                                                              file:rounded-xl file:border-0
                                                                              file:text-sm file:font-semibold
                                                                              file:bg-blue-50 file:text-blue-700
                                                                              hover:file:bg-blue-100
                                                                              transition-all border border-slate-300 rounded-xl px-2 py-2">
                                            </div>

                                            <div class="flex justify-end gap-3">
                                                <button type="button" @click="open = false"
                                                    class="px-5 py-2.5 text-slate-500 hover:bg-slate-100 rounded-xl font-bold transition-colors">
                                                    Cancelar
                                                </button>
                                                <button type="submit"
                                                    class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold shadow-lg shadow-green-600/30 transition-all">
                                                    <i class="fas fa-upload mr-2"></i> Subir y Procesar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('admin.relators.export', request()->query()) }}"
                                class="inline-flex items-center justify-center px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 text-white font-semibold rounded-xl hover:bg-white/20 transition-all">
                                <i class="fa fa-download mr-2 text-emerald-400"></i> Exportar
                            </a>
                            <a href="{{ route('admin.relators.create') }}"
                                class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                                <i class="fa fa-plus mr-2"></i> Nuevo Relator
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Rápidas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Relatores -->
                <div class="bg-white rounded-2xl p-4 shadow-lg border-l-4 border-blue-500 relative overflow-hidden group">
                    <div
                        class="absolute right-0 top-0 h-full w-16 bg-gradient-to-l from-blue-50 to-transparent opacity-50 transition-all group-hover:w-24">
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm text-slate-500 font-semibold mb-1">Total Relatores</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalRelatores }}</p>
                    </div>
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                        <i class="fa fa-users"></i>
                    </div>
                </div>

                <!-- Habilitados -->
                <div
                    class="bg-white rounded-2xl p-4 shadow-lg border-l-4 border-emerald-500 relative overflow-hidden group">
                    <div
                        class="absolute right-0 top-0 h-full w-16 bg-gradient-to-l from-emerald-50 to-transparent opacity-50 transition-all group-hover:w-24">
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm text-slate-500 font-semibold mb-1">Habilitados</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalHabilitados }}</p>
                    </div>
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                        <i class="fa fa-check-circle"></i>
                    </div>
                </div>

                <!-- Internos -->
                <div class="bg-white rounded-2xl p-4 shadow-lg border-l-4 border-indigo-500 relative overflow-hidden group">
                    <div
                        class="absolute right-0 top-0 h-full w-16 bg-gradient-to-l from-indigo-50 to-transparent opacity-50 transition-all group-hover:w-24">
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm text-slate-500 font-semibold mb-1">Internos</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalInternos }}</p>
                    </div>
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                        <i class="fa fa-university"></i>
                    </div>
                </div>

                <!-- Externos -->
                <div class="bg-white rounded-2xl p-4 shadow-lg border-l-4 border-amber-500 relative overflow-hidden group">
                    <div
                        class="absolute right-0 top-0 h-full w-16 bg-gradient-to-l from-amber-50 to-transparent opacity-50 transition-all group-hover:w-24">
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm text-slate-500 font-semibold mb-1">Externos</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalExternos }}</p>
                    </div>
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600">
                        <i class="fa fa-globe"></i>
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
                        <h3 class="font-bold text-slate-800 text-sm sm:text-base">Buscar Relatores</h3>
                        <p class="text-xs text-slate-500 hidden sm:block">Encuentra docentes por nombre, RUT o correo</p>
                    </div>
                </div>
                <form action="{{ route('admin.relators.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1 relative">
                        <i class="fa fa-user-tie absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all placeholder-gray-400 shadow-sm"
                            placeholder="🔍 Buscar por nombre, RUT o correo...">
                    </div>
                    <div class="flex gap-3">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all">
                            <i class="fa fa-filter mr-2"></i> Filtrar
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.relators.index') }}"
                                class="inline-flex items-center px-5 py-3.5 bg-gradient-to-r from-red-500 to-rose-600 text-white text-sm font-bold rounded-xl hover:from-red-600 hover:to-rose-700 shadow-lg shadow-red-500/30 hover:shadow-xl transition-all">
                                <i class="fa fa-times mr-2"></i> Limpiar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Listado de Relatores --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white shadow-lg overflow-hidden">
                {{-- Header de tabla --}}
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-4 sm:px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-white font-semibold flex items-center text-sm sm:text-base">
                            <i class="fa fa-users mr-2 sm:mr-3"></i>
                            Listado de Relatores
                        </h2>
                        <span
                            class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $relatores->total() }}
                            registros</span>
                    </div>
                </div>

                {{-- Vista Desktop (Tabla) --}}
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-6 py-4 w-12">
                                    <div class="flex items-center">
                                        <input type="checkbox" @click="toggleAll()" :checked="allSelected"
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Relator</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Contacto</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Facultad / Cargo</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Programas</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($relatores as $relator)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" value="{{ $relator->rel_login }}" x-model="selected"
                                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mr-4 shadow-lg shrink-0 text-white font-bold text-lg">
                                                {{ strtoupper(substr($relator->rel_nombre, 0, 1)) }}{{ strtoupper(substr($relator->rel_apellido, 0, 1)) }}
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.relators.show', $relator->rel_login) }}"
                                                    class="font-semibold text-slate-800 hover:text-blue-600 transition-colors block">
                                                    {{ $relator->rel_nombre }} {{ $relator->rel_apellido }}
                                                </a>
                                                <p class="text-xs text-slate-400 flex items-center mt-0.5">
                                                    <i class="fa fa-id-card mr-1.5"></i>
                                                    {{ $relator->rel_login }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1 text-sm text-slate-600">
                                            <div class="flex items-center">
                                                <i class="fa fa-envelope text-blue-400 w-4 mr-2"></i>
                                                <span class="truncate max-w-[150px]"
                                                    title="{{ $relator->rel_correo }}">{{ $relator->rel_correo }}</span>
                                            </div>
                                            @if($relator->rel_fono)
                                                <div class="flex items-center">
                                                    <i class="fa fa-phone text-emerald-400 w-4 mr-2"></i>
                                                    <span>{{ $relator->rel_fono }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            @if($relator->rel_facultad)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-lg border border-indigo-200">
                                                    <i class="fa fa-university text-indigo-400 mr-1.5"></i>
                                                    {{ Str::limit($relator->rel_facultad, 20) }}
                                                </span>
                                            @endif
                                            @if($relator->rel_cargo)
                                                <div class="text-xs text-slate-500 ml-1">
                                                    {{ Str::limit($relator->rel_cargo, 25) }}
                                                </div>
                                            @endif
                                            @if(!$relator->rel_facultad && !$relator->rel_cargo)
                                                <span class="text-slate-400 text-sm">—</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-200">
                                            {{ $relator->programas_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.relators.edit', $relator->rel_login) }}"
                                                class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 text-white rounded-xl hover:from-amber-500 hover:to-amber-700 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                                                title="Editar">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>

                                            <button type="button" @click="$dispatch('confirm-action', { 
                                                                                                                        title: 'Eliminar Relator', 
                                                                                                                        message: '¿Estás seguro de querer eliminar a {{ $relator->rel_nombre }} {{ $relator->rel_apellido }}?',
                                                                                                                        itemName: '{{ $relator->rel_login }}',
                                                                                                                        type: 'delete',
                                                                                                                        formId: 'delete-form-{{ str_replace('.', '-', $relator->rel_login) }}' 
                                                                                                                    })"
                                                class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-red-500 to-red-700 text-white rounded-xl hover:from-red-600 hover:to-red-800 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                                                title="Eliminar">
                                                <i class="fa fa-trash"></i>
                                            </button>

                                            <form id="delete-form-{{ str_replace('.', '-', $relator->rel_login) }}"
                                                action="{{ route('admin.relators.destroy', $relator->rel_login) }}"
                                                method="POST" class="hidden">
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
                                            <div
                                                class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                                                <i class="fa fa-user-slash text-2xl text-slate-300"></i>
                                            </div>
                                            <p class="font-medium">No se encontraron relatores.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="mt-0">
                    {{ $relatores->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection