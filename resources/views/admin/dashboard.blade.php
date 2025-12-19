@extends('layouts.admin')

@section('title', 'Dashboard - Cursos Próximos')

@section('content')
    <div class="min-h-screen bg-slate-50 -m-4 p-6 font-sans text-slate-600">

        {{-- Header Principal --}}
        <div
            class="mb-6 bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 rounded-2xl shadow-2xl p-6 text-white relative overflow-hidden border-b-4 border-amber-500">
            <div class="absolute top-0 right-0 p-4 opacity-5">
                <i class="fa fa-university text-9xl transform translate-x-10 -translate-y-10 text-amber-100"></i>
            </div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight mb-1 text-white flex items-center">
                        <i class="fa fa-chart-line text-amber-400 mr-3"></i>
                        Panel de Administración
                    </h1>
                    <p class="text-blue-200 text-sm">Visión general de métricas y programación.</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] text-amber-500 font-bold uppercase tracking-wider">Hoy</p>
                        <p class="text-lg font-bold leading-none text-white">{{ now()->format('d M, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid Compacto --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div
                class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 hover:border-blue-500 transition-all group relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Cursos</p>
                    <h3 class="text-2xl font-extrabold text-slate-800 group-hover:text-blue-600 transition-colors">
                        {{ $totalCourses }}</h3>
                </div>
            </div>
            <div
                class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 hover:border-amber-500 transition-all group relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Eventos</p>
                    <h3 class="text-2xl font-extrabold text-slate-800 group-hover:text-amber-600 transition-colors">
                        {{ $totalEvents }}</h3>
                </div>
            </div>
            <div
                class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 hover:border-indigo-500 transition-all group relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Alumnos</p>
                    <h3 class="text-2xl font-extrabold text-slate-800 group-hover:text-indigo-600 transition-colors">
                        {{ $totalParticipants }}</h3>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-amber-500 transition-all">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider mb-1">Activos</p>
                    <h3 class="text-2xl font-extrabold text-slate-800">{{ $programas->total() }}</h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
            {{-- GRÁFICO 1: Categorías (Reducido y con Leyenda Notoria) --}}
            <div class="xl:col-span-1 bg-white rounded-xl shadow-lg border border-slate-200 p-5 flex flex-col">
                <h3
                    class="text-sm font-bold text-slate-800 mb-4 flex items-center uppercase tracking-wide border-b border-slate-100 pb-2">
                    <i class="fa fa-chart-pie text-amber-500 mr-2"></i> Categorías
                </h3>
                <div class="flex flex-col h-full">
                    {{-- Gráfico más pequeño --}}
                    <div class="relative h-40 mb-4">
                        <canvas id="categoriesChart"></canvas>
                    </div>
                    {{-- Leyenda Personalizada Notoria --}}
                    <div class="flex-1 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                        @foreach($topCategories as $index => $cat)
                            @php
                                $colors = ['#3b82f6', '#f59e0b', '#6366f1', '#10b981', '#ec4899', '#8b5cf6', '#06b6d4'];
                                $color = $colors[$index % count($colors)];
                            @endphp
                            <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50 transition-colors border-l-4"
                                style="border-left-color: {{ $color }}">
                                <span class="text-xs font-bold text-slate-700 truncate mr-2"
                                    title="{{ $cat['name'] }}">{{ Str::limit($cat['name'], 20) }}</span>
                                <span class="text-xs font-bold px-2 py-0.5 rounded text-white"
                                    style="background-color: {{ $color }}">{{ $cat['alumnos'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        {{-- GRÁFICO 2: Top Cursos Ranking (Lista Estilizada) --}}
            <div class="xl:col-span-2 bg-white rounded-xl shadow-lg border border-slate-200 p-5 flex flex-col">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center uppercase tracking-wide border-b border-slate-100 pb-2">
                    <i class="fa fa-trophy text-amber-500 mr-2"></i> Top Cursos (Más Solicitados)
                </h3>
                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                    <div class="space-y-3">
                        @foreach($topCourses->take(5) as $index => $course)
                            <div class="flex items-center gap-4 p-3 rounded-lg hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100 group">
                                {{-- Ranking Badge --}}
                                <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm
                                    {{ $index == 0 ? 'bg-amber-100 text-amber-600 shadow-sm ring-2 ring-amber-50' : '' }}
                                    {{ $index == 1 ? 'bg-slate-100 text-slate-500 ring-2 ring-slate-50' : '' }}
                                    {{ $index == 2 ? 'bg-orange-50 text-orange-600 ring-2 ring-orange-50' : '' }}
                                    {{ $index > 2 ? 'text-slate-400 bg-slate-50' : '' }}
                                ">
                                    @if($index == 0) <i class="fa fa-crown text-amber-500"></i>
                                    @elseif($index == 1) <span class="font-mono">2</span>
                                    @elseif($index == 2) <span class="font-mono">3</span>
                                    @else <span class="text-xs font-mono">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                
                                {{-- Info Curso --}}
                                <div class="flex-grow min-w-0">
                                    <div class="flex justify-between items-center mb-1">
                                        <h4 class="text-xs font-bold text-slate-700 truncate pr-2 group-hover:text-blue-600 transition-colors" title="{{ $course->cur_nombre }}">
                                            {{ $course->cur_nombre }}
                                        </h4>
                                        <span class="text-xs font-bold text-slate-600 shrink-0">{{ $course->programas_count }} <span class="text-[10px] text-slate-400 font-normal">Ediciones</span></span>
                                    </div>
                                    {{-- Barra de Progreso Sutil --}}
                                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-full rounded-full {{ $index == 0 ? 'bg-gradient-to-r from-amber-400 to-amber-500' : 'bg-blue-400' }}" 
                                             style="width: {{ ($course->programas_count / $topCourses->first()->programas_count) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            
            {{-- Columna Principal: Tabla (Ajustada) --}}
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 bg-white border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Cursos Próximos</h2>
                        </div>
                        <a href="{{ route('admin.courses.create') }}" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg font-bold text-xs shadow-md transition-transform hover:-translate-y-0.5 flex items-center">
                            <i class="fa fa-book-medical mr-2"></i> Nuevo Curso
                        </a>
                    </div>

                    {{-- Filtros pequeños --}}
                    <div class="px-6 py-3 bg-slate-50 border-b border-slate-200">
                        <form action="{{ route('admin.dashboard') }}" method="GET" class="flex gap-3">
                            <div class="relative flex-grow">
                                <i class="fa fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-8 pr-3 py-1.5 rounded-md border border-slate-300 focus:border-blue-500 outline-none text-xs" placeholder="Buscar...">
                            </div>
                            <select name="modalidad" class="rounded-md border border-slate-300 text-xs py-1.5 px-3 focus:border-blue-500 outline-none cursor-pointer" onchange="this.form.submit()">
                                <option value="">Modalidad</option>
                                <option value="1" {{ request('modalidad') == 1 ? 'selected' : '' }}>Presencial</option>
                                <option value="2" {{ request('modalidad') == 2 ? 'selected' : '' }}>Online</option>
                                <option value="3" {{ request('modalidad') == 3 ? 'selected' : '' }}>Asincrónico</option>
                                <option value="4" {{ request('modalidad') == 4 ? 'selected' : '' }}>Híbrido</option>
                            </select>
                        </form>
                    </div>

                    {{-- Tabla --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-100 text-slate-600 border-b border-slate-200 text-[10px] font-bold uppercase tracking-wider">
                                    <th class="px-6 py-3">Curso / ID</th>
                                    <th class="px-4 py-3 text-center">Modalidad</th>
                                    <th class="px-4 py-3 text-center">Cupos</th>
                                    <th class="px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($programas as $programa)
                                    <tr class="hover:bg-blue-50 transition-colors">
                                        <td class="px-6 py-3">
                                            <div class="font-bold text-slate-800 text-xs mb-0.5 limit-text">{{ Str::limit($programa->curso->cur_nombre ?? 'Sin Nombre', 40) }}</div>
                                            <div class="text-[10px] text-slate-500">
                                                <i class="fa fa-calendar-alt text-amber-500 mr-1"></i>
                                                {{ \Carbon\Carbon::parse($programa->pro_inicia)->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-white border border-slate-200 text-slate-600 shadow-sm">
                                                {{ match($programa->curso->cur_modalidad ?? 0) { 1 => 'Presencial', 2 => 'Online', 3 => 'Asincrónico', 4 => 'Híbrido', default => 'N/D' } }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold text-slate-700 text-xs">
                                            {{ $programa->inscripciones_count }} <span class="text-slate-400 font-normal">/ {{ $programa->pro_cupos }}</span>
                                        </td>
                                        <td class="px-6 py-3 text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin.programs.teachers', $programa->pro_id) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Docentes">
                                                    <i class="fa fa-chalkboard-teacher text-xs"></i>
                                                </a>
                                                <a href="{{ route('admin.programs.grades', $programa->pro_id) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition-colors" title="Calificaciones">
                                                    <i class="fa fa-user-graduate text-xs"></i>
                                                </a>
                                                <a href="{{ route('admin.programs.export', $programa->pro_id) }}" class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Descargar Nómina">
                                                    <i class="fa fa-file-excel text-xs"></i>
                                                </a>
                                                <div class="w-px h-4 bg-slate-200 mx-1"></div>
                                                <a href="{{ route('admin.programs.edit', $programa->pro_id) }}" class="px-2 py-1 bg-slate-800 text-white text-[10px] font-bold rounded hover:bg-blue-700 transition-colors">Editar</a>
                                                <form action="{{ route('admin.programs.destroy', $programa->pro_id) }}" method="POST" class="inline" id="del-{{ $programa->pro_id }}">
                                                    @csrf @method('DELETE')
                                                    <button type="button" @click="$dispatch('confirm-action', { type: 'delete', formId: 'del-{{ $programa->pro_id }}' })" class="px-2 py-1 text-slate-400 hover:text-red-600"><i class="fa fa-trash text-xs"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="py-10 text-center text-slate-400 text-xs">No hay cursos próximos.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     {{-- Paginación --}}
                     @if($programas->hasPages())
                     <div class="px-6 py-3 border-t border-slate-200 bg-slate-50">
                         {{ $programas->appends(request()->query())->links() }}
                     </div>
                 @endif
                </div>
            </div>

            {{-- Columna Lateral --}}
            <div class="space-y-6">
                 {{-- Widget Eventos --}}
                <div class="bg-indigo-900 rounded-xl p-5 text-white shadow-xl relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-amber-400 text-xs uppercase tracking-wide">Próximos Eventos</h4>
                            <a href="{{ route('admin.events.index') }}" class="text-[10px] text-indigo-300 hover:text-white">Ver todo</a>
                        </div>
                        <ul class="space-y-3">
                             @forelse($upcomingEvents->take(3) as $event)
                                <li class="flex items-center gap-3 cursor-pointer hover:bg-white/5 p-1 rounded transition-colors" onclick="window.location='{{ route('admin.events.show', $event->eve_id) }}'">
                                    <div class="bg-amber-500/20 text-center min-w-[2.5rem] rounded p-1 text-amber-300">
                                        <span class="block text-[8px] font-bold uppercase">{{ \Carbon\Carbon::parse($event->eve_inicia)->translatedFormat('M') }}</span>
                                        <span class="block text-xs font-bold leading-none">{{ \Carbon\Carbon::parse($event->eve_inicia)->format('d') }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-xs font-bold truncate text-white">{{ $event->eve_nombre }}</span>
                                        <span class="block text-[10px] text-indigo-300">{{ $event->cursos_count }} cursos</span>
                                    </div>
                                </li>
                            @empty
                                <li class="text-xs text-blue-300 italic">Sin eventos próximos</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                 {{-- Accesos --}}
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.users.create') }}" class="flex flex-col items-center justify-center p-3 bg-white border border-slate-200 rounded-xl shadow-sm hover:border-blue-400 hover:shadow-md transition-all group">
                         <i class="fa fa-user-plus text-blue-500 text-base mb-1"></i>
                        <span class="text-[10px] font-bold text-slate-600">Usuario</span>
                    </a>
                    <a href="{{ route('admin.certificates.index') }}" class="flex flex-col items-center justify-center p-3 bg-white border border-slate-200 rounded-xl shadow-sm hover:border-amber-400 hover:shadow-md transition-all group">
                         <i class="fa fa-certificate text-amber-500 text-base mb-1"></i>
                        <span class="text-[10px] font-bold text-slate-600">Certificados</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Charts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoriesData = @json($topCategories);

            // Colores vibrantes fijos
            const palette = ['#3b82f6', '#f59e0b', '#6366f1', '#10b981', '#ec4899', '#8b5cf6', '#06b6d4'];

            // 1. Gráfico de Categorías (Dona Compacta)
            if(categoriesData.length > 0) {
                const ctxCat = document.getElementById('categoriesChart').getContext('2d');
                new Chart(ctxCat, {
                    type: 'doughnut',
                    data: {
                        labels: categoriesData.map(c => c.name),
                        datasets: [{
                            data: categoriesData.map(c => c.alumnos),
                            backgroundColor: palette,
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false } // Leyenda oculta, usamos la custom
                        },
                        cutout: '65%'
                    }
                });
            }
        });
    </script>
@endsection