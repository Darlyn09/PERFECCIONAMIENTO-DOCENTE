@extends('layouts.admin')

@section('title', 'Gestión de Eventos')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -m-4 p-4 sm:p-6">

        {{-- Botón Volver --}}
        <a href="{{ route('admin.dashboard') }}"
            class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-5 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
            <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            <span class="hidden sm:inline">Volver al Dashboard</span>
            <span class="sm:hidden">Volver</span>
        </a>

        {{-- Header Principal (Estilo Dashboard) --}}
        <div
            class="relative mb-8 bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl overflow-hidden shadow-2xl p-6 sm:p-8">
            {{-- Decoración de fondo --}}
            <div class="absolute inset-0 opacity-20">
                <div class="absolute -right-20 -top-20 w-80 h-80 border-[20px] border-amber-400/30 rounded-full"></div>
                <div class="absolute -left-16 -bottom-16 w-64 h-64 border-[16px] border-blue-400/20 rounded-full"></div>
            </div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-4 mb-2">
                        <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-white tracking-tight">Eventos Académicos</h1>
                    </div>
                    <p class="text-blue-200 text-sm max-w-xl">Administra y organiza la programación de actividades, cursos y
                        talleres académicos del sistema.</p>
                </div>

                <a href="{{ route('admin.events.create') }}"
                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-xl shadow-lg hover:from-blue-600 hover:to-blue-700 transition-all transform hover:-translate-y-0.5 border border-blue-400/30">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nuevo Evento
                </a>
            </div>
        </div>

        {{-- Filtros Rápidos (Estilo Píldora) --}}
        <div class="flex flex-wrap items-center gap-2 mb-8 pb-4 border-b border-slate-200">
            <a href="{{ route('admin.events.index') }}"
                class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ !request('periodo') ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Todos ({{ $totalEventos }})
            </a>
            <a href="{{ route('admin.events.index', ['periodo' => 'vigente']) }}"
                class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ request('periodo') == 'vigente' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block mr-2"></span>
                En Curso ({{ $vigentes }})
            </a>
            <a href="{{ route('admin.events.index', ['periodo' => 'proximo']) }}"
                class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ request('periodo') == 'proximo' ? 'bg-amber-500 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Próximos ({{ $proximos }})
            </a>
            <a href="{{ route('admin.events.index', ['periodo' => 'finalizado']) }}"
                class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ request('periodo') == 'finalizado' ? 'bg-slate-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Finalizados ({{ $finalizados }})
            </a>
        </div>

        {{-- Filtros Avanzados (Diseño Moderno) --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 mb-8 relative overflow-hidden">
            {{-- Decoración sutil --}}
            <div class="absolute right-0 top-0 w-32 h-32 bg-slate-50 rounded-bl-full -z-0"></div>

            <form action="{{ route('admin.events.index') }}" method="GET" class="relative z-10">
                {{-- Mantener filtros existentes --}}
                @if(request('periodo')) <input type="hidden" name="periodo" value="{{ request('periodo') }}"> @endif
                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

                    {{-- Selector de Orden --}}
                    <div class="md:col-span-5 lg:col-span-4">
                        <label for="orden" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                            <i class="fas fa-sort mr-1"></i> Ordenar por
                        </label>
                        <div class="relative">
                            <select name="orden" id="orden" onchange="this.form.submit()"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-slate-200 text-slate-700 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none cursor-pointer hover:bg-white hover:shadow-md">
                                <option value="fecha_desc" {{ request('orden') == 'fecha_desc' ? 'selected' : '' }}>📅 Fecha
                                    (Más reciente)</option>
                                <option value="fecha_asc" {{ request('orden') == 'fecha_asc' ? 'selected' : '' }}>📅 Fecha
                                    (Más antigua)</option>
                                <option value="nombre_asc" {{ request('orden') == 'nombre_asc' ? 'selected' : '' }}>🔤 Nombre
                                    (A-Z)</option>
                                <option value="nombre_desc" {{ request('orden') == 'nombre_desc' ? 'selected' : '' }}>🔤
                                    Nombre (Z-A)</option>
                            </select>
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Selector de Tipo --}}
                    <div class="md:col-span-5 lg:col-span-4">
                        <label for="tipo" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                            <i class="fas fa-filter mr-1"></i> Tipo de Evento
                        </label>
                        <div class="relative">
                            <select name="tipo" id="tipo" onchange="this.form.submit()"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-slate-200 text-slate-700 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none cursor-pointer hover:bg-white hover:shadow-md">
                                <option value="">🔍 Todos los tipos</option>
                                <option value="1" {{ request('tipo') == 1 ? 'selected' : '' }}>🎓 Capacitación</option>
                                <option value="2" {{ request('tipo') == 2 ? 'selected' : '' }}>🎤 Seminario</option>
                                <option value="3" {{ request('tipo') == 3 ? 'selected' : '' }}>🤝 Congreso</option>
                                <option value="4" {{ request('tipo') == 4 ? 'selected' : '' }}>🛠 Taller</option>
                                <option value="5" {{ request('tipo') == 5 ? 'selected' : '' }}>📅 Jornada</option>
                            </select>
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Botón Limpiar --}}
                    @if(request()->hasAny(['orden', 'tipo']))
                        <div class="md:col-span-2 flex pb-0.5">
                            <a href="{{ route('admin.events.index', request()->only(['periodo', 'search'])) }}"
                                class="inline-flex items-center justify-center w-full px-4 py-2.5 text-sm font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 hover:text-slate-700 rounded-xl transition-all group">
                                <svg class="w-4 h-4 mr-2 text-slate-400 group-hover:text-slate-600 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Limpiar
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div> {{-- Lista de Eventos (Diseño Grilla Premium) --}}
        @if($events->count() > 0)
            @php
                $eventosAgrupados = $events->groupBy(function ($event) {
                    return \Carbon\Carbon::parse($event->eve_inicia)->format('Y-m');
                });
            @endphp

            <div class="space-y-10">
                @foreach($eventosAgrupados as $mesAnio => $eventosDelMes)
                    @php
                        $fechaMes = \Carbon\Carbon::createFromFormat('Y-m', $mesAnio);
                        $nombreMes = ucfirst($fechaMes->translatedFormat('F Y'));
                    @endphp

                    <div>
                        {{-- Encabezado de Mes --}}
                        <div class="flex items-center gap-4 mb-6">
                            <h3 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                                <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
                                {{ $nombreMes }}
                            </h3>
                            <span
                                class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full border border-slate-200">
                                {{ $eventosDelMes->count() }} eventos
                            </span>
                            <div class="flex-1 h-px bg-slate-200"></div>
                        </div>

                        {{-- Grilla de Tarjetas --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                            @foreach($eventosDelMes as $event)
                                @php
                                    $fechaInicio = \Carbon\Carbon::parse($event->eve_inicia);
                                    $fechaFin = $event->eve_finaliza ? \Carbon\Carbon::parse($event->eve_finaliza) : null;
                                    $esVigente = $fechaInicio->lte(now()) && (!$fechaFin || $fechaFin->gte(now()));
                                @endphp

                                <div
                                    class="group relative bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border-2 border-slate-300 hover:border-blue-500 transition-colors duration-300 flex flex-col h-full overflow-hidden">
                                    {{-- Banner de Estado Superior --}}
                                    <div class="absolute top-0 inset-x-0 h-1 {{ $esVigente ? 'bg-emerald-500' : 'bg-blue-600' }}">
                                    </div>

                                    <div class="p-5 flex flex-col h-full">
                                        {{-- Encabezado Tarjeta --}}
                                        <div class="flex justify-between items-start mb-3">
                                            <div
                                                class="flex flex-col items-center bg-slate-100 border border-slate-300 rounded-lg p-2 min-w-[60px]">
                                                <span
                                                    class="text-[10px] font-bold text-slate-600 uppercase">{{ $fechaInicio->translatedFormat('M') }}</span>
                                                <span
                                                    class="text-xl font-extrabold text-slate-900">{{ $fechaInicio->format('d') }}</span>
                                            </div>

                                            <div class="flex gap-1">
                                                <a href="{{ route('admin.events.edit', $event->eve_id) }}"
                                                    class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-md transition-colors"
                                                    title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>

                                        {{-- Título y Tipo --}}
                                        <div class="mb-auto">
                                            @if($event->eve_tipo)
                                                <span
                                                    class="inline-block px-2 py-0.5 mb-2 text-[10px] font-bold tracking-wide uppercase rounded-md 
                                                                                                                {{ $event->eve_tipo == 1 ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                                    {{ match ($event->eve_tipo) { 1 => 'Capacitación', 2 => 'Seminario', 3 => 'Congreso', 4 => 'Taller', 5 => 'Jornada', default => 'Evento'} }}
                                                </span>
                                            @endif

                                            <h4
                                                class="text-base font-bold text-slate-900 leading-snug mb-1 group-hover:text-blue-700 transition-colors line-clamp-2">
                                                <a href="{{ route('admin.events.show', $event->eve_id) }}">
                                                    {{ $event->eve_nombre }}
                                                </a>
                                            </h4>

                                            @if($esVigente)
                                                <span class="inline-flex items-center text-[10px] font-bold text-emerald-600 mb-1">
                                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1 animate-pulse"></span>
                                                    En Curso
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Footer Info --}}
                                        <div class="pt-3 mt-3 border-t border-slate-100 grid grid-cols-2 gap-2">
                                            <div>
                                                <p class="text-[10px] text-slate-400 uppercase font-bold mb-0.5">Cursos Activos</p>
                                                <p class="text-xs font-bold text-slate-700 flex items-center">
                                                    <svg class="w-3.5 h-3.5 mr-1 text-indigo-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                        </path>
                                                    </svg>
                                                    {{ $event->cursos_activos_count ?? 0 }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[10px] text-slate-400 uppercase font-bold mb-0.5">Término</p>
                                                <p class="text-xs font-bold text-slate-700">
                                                    {{ $fechaFin ? $fechaFin->format('d/m') : '-' }}
                                                </p>
                                            </div>
                                        </div>

                                            {{-- Botón (Hover Only Actions) --}}
                                            <div
                                                class="absolute inset-x-0 bottom-0 p-3 bg-white/95 backdrop-blur-sm border-t border-slate-100 translate-y-full group-hover:translate-y-0 transition-transform duration-200 flex gap-2 z-10">
                                                
                                                {{-- Ver --}}
                                                <a href="{{ route('admin.events.show', $event->eve_id) }}" 
                                                    class="flex-1 inline-flex justify-center items-center py-2 px-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm hover:shadow transition-all">
                                                    Ver Detalles
                                                </a>

                                                {{-- Toggle --}}
                                                <form id="toggle-form-{{ $event->eve_id }}" action="{{ route('admin.events.toggle', $event->eve_id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="button" @click.prevent="$dispatch('confirm-action', { 
                                                        title: 'Cambiar Estado', 
                                                        message: '¿Estás seguro de que deseas {{ $event->eve_estado == 1 ? 'deshabilitar' : 'habilitar' }} este evento?', 
                                                        type: '{{ $event->eve_estado == 1 ? 'disable' : 'enable' }}',
                                                        itemName: '{{ $event->eve_nombre }}',
                                                        formId: 'toggle-form-{{ $event->eve_id }}',
                                                        confirmText: 'Sí, {{ $event->eve_estado == 1 ? 'Deshabilitar' : 'Habilitar' }}'
                                                    })" 
                                                    class="p-2 w-10 h-full flex items-center justify-center {{ $event->eve_estado == 1 ? 'text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100' : 'text-slate-400 bg-slate-50 hover:bg-slate-100 border border-slate-200' }} rounded-lg transition-colors" title="Estado: {{ $event->eve_estado == 1 ? 'Activo' : 'Inactivo' }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </form>

                                                {{-- Eliminar --}}
                                                <form id="delete-form-{{ $event->eve_id }}" action="{{ route('admin.events.destroy', $event->eve_id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="button" @click.prevent="$dispatch('confirm-action', { 
                                                        title: 'Eliminar Evento', 
                                                        message: '¿Estás seguro de que deseas eliminar este evento? Esta acción no se puede deshacer.', 
                                                        type: 'delete',
                                                        itemName: '{{ $event->eve_nombre }}',
                                                        formId: 'delete-form-{{ $event->eve_id }}',
                                                        confirmText: 'Sí, Eliminar'
                                                    })" 
                                                    class="p-2 w-10 h-full flex items-center justify-center text-rose-500 bg-rose-50 hover:bg-rose-100 border border-rose-100 hover:border-rose-200 rounded-lg transition-all" title="Eliminar definitivamente">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            <div class="mt-12">
                {{ $events->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800">No hay eventos encontrados</h3>
                <p class="text-slate-500 mt-2">Intenta ajustar los filtros o crea un nuevo evento.</p>
                @if(request()->hasAny(['search', 'periodo']))
                    <a href="{{ route('admin.events.index') }}"
                        class="inline-block mt-4 text-blue-600 font-semibold hover:underline">Limpiar filtros</a>
                @endif
            </div>
        @endif
    </div>
@endsection