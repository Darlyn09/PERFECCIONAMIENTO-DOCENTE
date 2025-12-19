@extends('layouts.admin')

@section('title', 'Gestión de Categorías')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -m-4 p-4 sm:p-6">
        {{-- Header --}}
        {{-- Header Principal --}}
        <div class="mb-4 sm:mb-6">
            {{-- Botón Volver Destacado --}}
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-5 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
                <i class="fa fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                <span class="hidden sm:inline">Volver al Dashboard</span>
                <span class="sm:hidden">Volver</span>
            </a>

            <div class="relative mb-6 bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl overflow-hidden shadow-2xl">
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
                                <i class="fa fa-tags text-white text-2xl sm:text-3xl"></i>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Gestión de Categorías</h1>
                                <p class="text-indigo-200 text-sm">Administración de clasificaciones para cursos y talleres</p>
                            </div>
                        </div>

                        {{-- Stats --}}
                        <div class="bg-white/10 backdrop-blur-md rounded-xl px-6 py-3 border border-white/20">
                            <p class="text-3xl font-bold text-white text-center">{{ $totalCategorias }}</p>
                            <p class="text-indigo-200 text-xs text-center uppercase tracking-wider">Total</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <div class="mb-4 sm:mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-4 py-3 rounded-r-lg flex items-center animate-pulse">
                <i class="fa fa-check-circle mr-3 text-emerald-500"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-4 sm:mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-lg flex items-center">
                <i class="fa fa-exclamation-circle mr-3 text-red-500"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            {{-- Formulario Nueva Categoría --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-4 border border-slate-200">
                    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                        <h2 class="text-slate-800 font-bold flex items-center">
                            <i class="fa fa-plus-circle mr-2 text-indigo-500"></i>
                            Nueva Categoría
                        </h2>
                    </div>
                    <div class="p-5">
                        <form id="create-category-form" action="{{ route('admin.categorias.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-5">
                                <label class="block text-slate-700 text-sm font-semibold mb-2" for="nom_categoria">
                                    <i class="fa fa-tag text-blue-500 mr-2"></i>Nombre de la Categoría
                                </label>
                                <input type="text" name="nom_categoria" id="nom_categoria"
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all @error('nom_categoria') border-red-400 bg-red-50 @enderror"
                                    value="{{ old('nom_categoria') }}" 
                                    placeholder="Ej: Metodologías Ágiles" 
                                    required>
                                @error('nom_categoria')
                                    <p class="text-red-500 text-xs mt-2 flex items-center">
                                        <i class="fa fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <button type="button" @click.prevent="$dispatch('confirm-action', { 
                                title: 'Crear Categoría', 
                                message: '¿Confirma que desea crear esta nueva categoría?', 
                                type: 'enable',
                                formId: 'create-category-form',
                                confirmText: 'Sí, Crear' 
                            })"
                                class="w-full inline-flex items-center justify-center px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all">
                                <i class="fa fa-save mr-2"></i> Guardar Categoría
                            </button>
                        </form>
                        
                        {{-- Tips --}}
                        <div class="mt-5 p-4 bg-amber-50 rounded-xl border border-amber-200">
                            <h4 class="text-sm font-semibold text-amber-800 mb-2 flex items-center">
                                <i class="fa fa-lightbulb mr-2"></i> Tips
                            </h4>
                            <ul class="text-xs text-amber-700 space-y-1">
                                <li>• Usa nombres descriptivos y claros</li>
                                <li>• Evita abreviaciones confusas</li>
                                <li>• Las categorías ayudan a organizar cursos</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Listado de Categorías --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-4 sm:px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-white font-semibold flex items-center text-sm sm:text-base">
                                <i class="fa fa-list mr-2 sm:mr-3 text-amber-400"></i>
                                Categorías Existentes
                            </h2>
                            <span class="text-slate-400 text-xs sm:text-sm">{{ $categorias->total() }} registros</span>
                        </div>
                    </div>

                    {{-- Búsqueda --}}
                    <div class="p-4 border-b border-slate-100 bg-slate-50">
                        <form action="{{ route('admin.categorias.create') }}" method="GET" class="flex gap-2">
                            <div class="flex-1 relative">
                                <i class="fa fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input name="search" value="{{ request('search') }}"
                                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all"
                                       type="text" placeholder="Buscar categoría...">
                            </div>
                            <button type="submit" class="px-4 py-2.5 bg-amber-500 text-white text-sm font-semibold rounded-xl hover:bg-amber-600 transition-all">
                                <i class="fa fa-search"></i>
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.categorias.create') }}" class="px-4 py-2.5 bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-300 transition-all">
                                    <i class="fa fa-times"></i>
                                </a>
                            @endif
                        </form>
                    </div>

                    {{-- Vista Móvil y Tablet (Tarjetas) --}}
                    <div class="block lg:hidden divide-y divide-slate-100">
                        @forelse($categorias as $categoria)
                            <div class="p-4 hover:bg-amber-50/50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center flex-1 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center mr-3 shadow-lg flex-shrink-0">
                                            <i class="fa fa-tag text-white"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-800 truncate">{{ $categoria->nom_categoria }}</p>
                                            <p class="text-xs text-slate-400">
                                                {{ $categoria->cursos()->count() }} cursos
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 ml-2">
                                        <button type="button"
                                           @click="$dispatch('confirm-action', {
                                               title: '¿Editar esta categoría?',
                                               message: 'Serás redirigido al formulario de edición.',
                                               itemName: '{{ addslashes($categoria->nom_categoria) }}',
                                               type: 'edit',
                                               redirectUrl: '{{ route('admin.categorias.edit', $categoria->cur_categoria) }}'
                                           })"
                                           class="w-9 h-9 inline-flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-lg shadow-md hover:scale-105 transition-all"
                                           title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        @if($categoria->cursos()->count() == 0)
                                            <form id="delete-categoria-{{ $categoria->cur_categoria }}" action="{{ route('admin.categorias.destroy', $categoria->cur_categoria) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        @click="$dispatch('confirm-action', {
                                                            title: '¿Eliminar esta categoría?',
                                                            message: 'Esta acción no se puede deshacer.',
                                                            itemName: '{{ addslashes($categoria->nom_categoria) }}',
                                                            type: 'delete',
                                                            formId: 'delete-categoria-{{ $categoria->cur_categoria }}'
                                                        })"
                                                        class="w-9 h-9 inline-flex items-center justify-center bg-gradient-to-br from-rose-500 to-red-600 text-white rounded-lg shadow-md hover:scale-105 transition-all"
                                                        title="Eliminar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <div class="w-16 h-16 mx-auto mb-4 bg-amber-100 rounded-full flex items-center justify-center">
                                    <i class="fa fa-tags text-2xl text-amber-500"></i>
                                </div>
                                <p class="text-slate-600 font-medium">No hay categorías registradas</p>
                                <p class="text-slate-400 text-sm mt-1">Crea la primera usando el formulario</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Vista Desktop (Tabla) --}}
                    <div class="hidden lg:block">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Categoría</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Cursos Asociados</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($categorias as $categoria)
                                    <tr class="hover:bg-amber-50/50 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform">
                                                    <i class="fa fa-tag text-white"></i>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-slate-800">{{ $categoria->nom_categoria }}</p>
                                                    <p class="text-xs text-slate-400">ID: {{ $categoria->cur_categoria }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php $cursosCount = $categoria->cursos()->count(); @endphp
                                            @if($cursosCount > 0)
                                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-sm font-semibold rounded-full">
                                                    <i class="fa fa-book-open mr-1.5 text-xs"></i>
                                                    {{ $cursosCount }} {{ $cursosCount == 1 ? 'curso' : 'cursos' }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-500 text-sm font-medium rounded-full">
                                                    Sin cursos
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                {{-- Botón Editar --}}
                                                <button type="button"
                                                   @click="$dispatch('confirm-action', {
                                                       title: '¿Editar esta categoría?',
                                                       message: 'Serás redirigido al formulario de edición.',
                                                       itemName: '{{ addslashes($categoria->nom_categoria) }}',
                                                       type: 'edit',
                                                       redirectUrl: '{{ route('admin.categorias.edit', $categoria->cur_categoria) }}'
                                                   })"
                                                   class="group/btn relative inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/30 hover:shadow-xl hover:scale-105"
                                                   title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                {{-- Botón Eliminar (solo si no tiene cursos) --}}
                                                @if($cursosCount == 0)
                                                    <form id="delete-categoria-desktop-{{ $categoria->cur_categoria }}" action="{{ route('admin.categorias.destroy', $categoria->cur_categoria) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                                @click="$dispatch('confirm-action', {
                                                                    title: '¿Eliminar esta categoría?',
                                                                    message: 'Esta acción no se puede deshacer.',
                                                                    itemName: '{{ addslashes($categoria->nom_categoria) }}',
                                                                    type: 'delete',
                                                                    formId: 'delete-categoria-desktop-{{ $categoria->cur_categoria }}'
                                                                })"
                                                                class="group/btn relative inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-rose-500 to-red-600 text-white rounded-xl hover:from-rose-600 hover:to-red-700 transition-all shadow-lg shadow-rose-500/30 hover:shadow-xl hover:scale-105"
                                                                title="Eliminar">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="inline-flex items-center justify-center w-10 h-10 bg-slate-200 text-slate-400 rounded-xl cursor-not-allowed" title="No se puede eliminar: tiene cursos asociados">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                        </svg>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-16 h-16 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center mb-4">
                                                    <i class="fa fa-tags text-2xl text-amber-500"></i>
                                                </div>
                                                <h3 class="text-lg font-semibold text-slate-700 mb-1">No hay categorías</h3>
                                                <p class="text-slate-500 text-sm">Crea la primera categoría usando el formulario</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    @if($categorias->hasPages())
                        {{-- Paginación --}}
                        <div class="border-t border-slate-100 bg-gradient-to-b from-slate-50 to-white rounded-b-2xl">
                            {{ $categorias->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
