@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">

        {{-- Header Principal --}}
        <div class="mb-4 sm:mb-6">
            {{-- Botón Volver Destacado --}}
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-5 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
                <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                <span class="hidden sm:inline">Volver al Dashboard</span>
                <span class="sm:hidden">Volver</span>
            </a>

            <div
                class="relative mb-6 bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-2xl overflow-hidden shadow-2xl">
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
                                <i class="fas fa-certificate text-white text-2xl sm:text-3xl"></i>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Diseño de Certificados</h1>
                                <p class="text-blue-200 text-sm">Gestiona las plantillas de diplomas y certificados</p>
                            </div>
                        </div>

                        {{-- Botón Nuevo --}}
                        <a href="{{ route('admin.certificates.create') }}"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            <i class="fas fa-plus mr-2"></i> Nueva Plantilla
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search & Filter Bar --}}
        <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.certificates.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-400 focus:outline-none focus:placeholder-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 sm:text-sm transition-all"
                        placeholder="Buscar certificado por nombre...">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Buscar
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.certificates.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-lg shadow-sm flex items-center">
                <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
                <button type="button" class="ml-auto text-emerald-500 hover:text-emerald-700"
                    onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
                <button type="button" class="ml-auto text-red-500 hover:text-red-700" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Cards Grid -->
        @if(empty($certificates))
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                    <i class="fas fa-certificate text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No hay plantillas creadas</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">
                    Comienza creando una plantilla por defecto o una específica para un curso.
                </p>
                <a href="{{ route('admin.certificates.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-white border border-blue-600 text-blue-600 font-bold rounded-lg hover:bg-blue-50 transition-colors shadow-sm">
                    Crear primera plantilla
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($certificates as $cert)
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col h-full group">

                        <!-- Preview Header (Mini Visual) -->
                        <div class="h-48 relative overflow-hidden flex items-center justify-center"
                            style="background-color: {{ $cert->getConfig('bg_color', '#f8fafc') }}; 
                                                                       {{ $cert->imagen_fondo ? "background-image: url('" . asset('storage/' . $cert->imagen_fondo) . "'); background-size: cover; background-position: center;" : '' }}">

                            <!-- Badges -->
                            <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                                @if($cert->tipo == 'default')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 shadow-sm border border-emerald-200">
                                        <i class="fas fa-star mr-1 text-emerald-500"></i> Por Defecto
                                    </span>
                                @endif
                                @if($cert->referencia_id)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 shadow-sm border border-blue-200">
                                        <i class="fas fa-book mr-1 text-blue-500"></i> Curso Específico
                                    </span>
                                @endif
                            </div>

                            <!-- Miniatura CSS -->
                            <div class="bg-white shadow-lg border border-gray-200 p-4 text-center transform scale-75 origin-center w-full max-w-[200px] aspect-[4/3] flex flex-col justify-center items-center"
                                style="border-color: {{ $cert->getConfig('border_color', '#ddd') }};
                                                                           border-width: {{ ($cert->getConfig('border_width', 10) / 4) }}px;">

                                <div class="text-[8px] font-bold mb-1 leading-tight"
                                    style="color: {{ $cert->getConfig('title_color', '#000') }};">
                                    {{ Str::limit($cert->getConfig('title_text', 'CERTIFICADO'), 20) }}
                                </div>
                                <div class="text-[6px] text-gray-500 mb-1">
                                    {{ Str::limit($cert->getConfig('body_text', 'Otorgado a'), 30) }}
                                </div>
                                <div class="text-[8px] font-bold mb-1">ALUMNO EJEMPLO</div>
                                <div class="w-16 h-px bg-gray-300 mx-auto mt-2"></div>
                            </div>
                        </div>

                        <div class="p-5 flex-1 flex flex-col">
                            <h5 class="text-lg font-bold text-gray-800 mb-2 truncate" title="{{ $cert->nombre }}">
                                {{ $cert->nombre }}
                            </h5>

                            <div class="mb-4">
                                @if($cert->referencia_id && $cert->curso)
                                    <div class="flex items-start text-xs text-blue-600 bg-blue-50 p-2 rounded-lg">
                                        <i class="fas fa-link mt-0.5 mr-2"></i>
                                        <span class="line-clamp-2">{{ $cert->curso->cur_nombre }}</span>
                                    </div>
                                @elseif($cert->tipo === 'default')
                                    <div class="flex items-center text-xs text-emerald-600 bg-emerald-50 p-2 rounded-lg">
                                        <i class="fas fa-globe mt-0.5 mr-2"></i>
                                        <span>Aplica a todos los cursos sin plantilla</span>
                                    </div>
                                @else
                                    <div class="flex items-center text-xs text-amber-600 bg-amber-50 p-2 rounded-lg">
                                        <i class="fas fa-exclamation-triangle mt-0.5 mr-2"></i>
                                        <span>Sin asignación activa</span>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                                <span class="text-xs text-gray-400 font-medium">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $cert->updated_at->diffForHumans() }}
                                </span>

                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.certificates.preview', ['id' => $cert->id]) }}" target="_blank"
                                        class="inline-flex items-center justify-center w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-xl hover:from-blue-600 hover:to-blue-800 transition-all shadow-md hover:shadow-lg hover:scale-105"
                                        title="Vista Previa">
                                        <i class="far fa-eye text-xs"></i>
                                    </a>

                                    <!-- Edit Trigger (Direct Link) -->
                                    <a href="{{ route('admin.certificates.edit', ['id' => $cert->id]) }}"
                                        class="inline-flex items-center justify-center w-9 h-9 bg-gradient-to-br from-amber-400 to-amber-600 text-white rounded-xl hover:from-amber-500 hover:to-amber-700 transition-all shadow-md hover:shadow-lg hover:scale-105"
                                        title="Editar">
                                        <i class="fas fa-pencil-alt text-xs"></i>
                                    </a>

                                    <form id="delete-certificate-{{ $cert->id }}"
                                        action="{{ route('admin.certificates.destroy', $cert->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            @click="$dispatch('confirm-action', {
                                                                                                                                                title: 'Eliminar Plantilla',
                                                                                                                                                message: '¿Estás seguro de que deseas eliminar esta plantilla? Esta acción no se puede deshacer.',
                                                                                                                                                itemName: '{{ addslashes($cert['name']) }}',
                                                                                                                                                type: 'delete',
                                                                                                                                                formId: 'delete-certificate-{{ $cert['id'] }}'
                                                                                                                                            })"
                                            class="inline-flex items-center justify-center w-9 h-9 bg-gradient-to-br from-red-500 to-red-700 text-white rounded-xl hover:from-red-600 hover:to-red-800 transition-all shadow-md hover:shadow-lg hover:scale-105"
                                            title="Eliminar">
                                            <i class="far fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $certificates->appends(request()->query())->links() }}
            </div>
        @endif

    </div>
@endsection