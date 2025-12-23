@extends('layouts.admin')

@section('title', 'Ofertas Académicas')

@section('content')
<div class="min-h-screen -m-4 p-4 sm:p-8 relative overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    {{-- Fondo dinámico --}}
    <div class="absolute inset-0 opacity-40">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-100 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-100 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">
                    Ofertas Académicas
                </h1>
                <p class="text-slate-500 mt-1">Gestión global de sesiones y programas disponibles</p>
            </div>
            
            <a href="{{ route('admin.courses.index') }}" 
               class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-500/30">
                <i class="fas fa-plus mr-2"></i> Crear Nueva Oferta
            </a>
        </div>

        {{-- Filtros y Búsqueda --}}
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-4 mb-6">
            <form action="{{ route('admin.offerings.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2 relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Buscar por nombre del curso..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                    <i class="fas fa-search absolute left-3.5 top-3.5 text-slate-400"></i>
                </div>

                <div>
                    <select name="modalidad" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        <option value="">Todas las Modalidades</option>
                        <option value="1" {{ request('modalidad') == '1' ? 'selected' : '' }}>Presencial</option>
                        <option value="2" {{ request('modalidad') == '2' ? 'selected' : '' }}>Online Sincrónico</option>
                        <option value="3" {{ request('modalidad') == '3' ? 'selected' : '' }}>Online Asincrónico</option>
                        <option value="4" {{ request('modalidad') == '4' ? 'selected' : '' }}>Híbrido</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full px-4 py-2.5 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-900 transition-all">
                        Filtrar Resultados
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabla de Ofertas --}}
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold tracking-wider">
                            <th class="px-6 py-4">Código / Programa</th>
                            <th class="px-6 py-4">Entidad / Evento</th>
                            <th class="px-6 py-4">Modalidad</th>
                            <th class="px-6 py-4">Fechas</th>
                            <th class="px-6 py-4 text-center">Cupos</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($programas as $programa)
                            <tr class="hover:bg-blue-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0">
                                            #{{ $programa->pro_id }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.courses.show', $programa->cur_id) }}" class="font-bold text-slate-700 hover:text-blue-600 transition-colors">
                                                {{ $programa->curso->cur_nombre }}
                                            </a>
                                            <div class="flex items-center gap-2 mt-1">
                                                @if($programa->relator)
                                                    <span class="text-xs text-slate-500 flex items-center" title="Relator Principal">
                                                        <i class="fas fa-user-tie mr-1 text-slate-400"></i>
                                                        {{ $programa->relator->rel_nombre }} {{ $programa->relator->rel_apellido }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($programa->curso->evento)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 text-xs font-medium border border-purple-100">
                                            {{ Str::limit($programa->curso->evento->eve_nombre, 20) }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">Sin Entidad</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @switch($programa->curso->cur_modalidad)
                                        @case(1) <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded">Presencial</span> @break
                                        @case(2) <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">Online Sinc.</span> @break
                                        @case(3) <span class="text-xs font-bold text-cyan-600 bg-cyan-50 px-2 py-1 rounded">Online Asinc.</span> @break
                                        @case(4) <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded">Híbrido</span> @break
                                    @endswitch
                                    @if($programa->pro_lugar)
                                        <div class="text-xs text-slate-500 mt-1 max-w-[150px] truncate" title="{{ $programa->pro_lugar }}">
                                            <i class="fas fa-map-marker-alt text-slate-400 mr-1"></i> {{ $programa->pro_lugar }}
                                        </div>
                                    @elseif($programa->curso->cur_link)
                                        <div class="text-xs text-blue-500 mt-1 truncate max-w-[150px]">
                                            <i class="fas fa-link mr-1"></i> Link Disponible
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-700">
                                        {{ \Carbon\Carbon::parse($programa->pro_inicia)->format('d/m/Y') }}
                                    </div>
                                    @if($programa->pro_finaliza)
                                        <div class="text-xs text-slate-400">
                                            al {{ \Carbon\Carbon::parse($programa->pro_finaliza)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-xs font-bold uppercase text-slate-400 mb-1">Cupos</span>
                                        <span class="text-sm font-bold {{ $programa->inscripciones_count >= $programa->pro_cupos ? 'text-red-500' : 'text-slate-700' }}">
                                            {{ $programa->inscripciones_count }} / {{ $programa->pro_cupos }}
                                        </span>
                                        <div class="w-16 h-1.5 bg-slate-100 rounded-full mt-1 overflow-hidden mb-2">
                                            @php
                                                $percentage = $programa->pro_cupos > 0 ? ($programa->inscripciones_count / $programa->pro_cupos) * 100 : 0;
                                                $color = $percentage >= 100 ? 'bg-red-500' : ($percentage > 70 ? 'bg-amber-400' : 'bg-emerald-400');
                                            @endphp
                                            <div class="h-full {{ $color }}" style="width: {{ min($percentage, 100) }}%"></div>
                                        </div>

                                        {{-- Calificaciones (Req 67) --}}
                                        <div class="flex gap-4 mt-1 border-t border-slate-100 pt-2 w-full justify-center">
                                            {{-- Promedio Alumnos --}}
                                            <div class="text-center" title="Promedio Notas Alumnos">
                                                <i class="fa fa-user-graduate text-slate-300 text-[10px] mb-0.5 block"></i>
                                                @php $avgAlumnos = $programa->inscripciones->where('ins_nota', '>', 0)->avg('ins_nota'); @endphp
                                                <span class="text-xs font-bold {{ $avgAlumnos ? ($avgAlumnos >= 4.0 ? 'text-emerald-600' : 'text-red-500') : 'text-slate-300' }}">
                                                    {{ $avgAlumnos ? number_format($avgAlumnos, 1) : '-' }}
                                                </span>
                                            </div>

                                            <div class="text-center" title="Satisfacción del Curso (1-5)">
                                                <i class="fas fa-star text-amber-400 text-[10px] mb-0.5 block"></i>
                                                @php $avgSatisfaccion = $programa->inscripciones->whereNotNull('ins_evaluacion')->avg('ins_evaluacion'); @endphp
                                                <span class="text-xs font-bold {{ $avgSatisfaccion ? ($avgSatisfaccion >= 4.0 ? 'text-emerald-600' : ($avgSatisfaccion >= 3.0 ? 'text-amber-500' : 'text-red-500')) : 'text-slate-300' }}">
                                                    {{ $avgSatisfaccion ? number_format($avgSatisfaccion, 1) : '-' }}
                                                </span>
                                            </div>

                                            {{-- Repetiría (Req Usuario) --}}
                                            <div class="text-center" title="% Recomienda el Curso">
                                                <i class="fas fa-thumbs-up text-blue-400 text-[10px] mb-0.5 block"></i>
                                                @php 
                                                    $evaluados = $programa->inscripciones->whereNotNull('ins_repetiria');
                                                    $totalEvaluados = $evaluados->count();
                                                    $siRepetiria = $evaluados->where('ins_repetiria', 1)->count();
                                                    $porcentaje = $totalEvaluados > 0 ? ($siRepetiria / $totalEvaluados) * 100 : null;
                                                @endphp
                                                <span class="text-xs font-bold {{ $porcentaje !== null ? ($porcentaje >= 80 ? 'text-emerald-600' : 'text-red-500') : 'text-slate-300' }}">
                                                    {{ $porcentaje !== null ? round($porcentaje) . '%' : '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        {{-- Toggle Estado --}}
                                        <div x-data="{ 
                                            loading: false, 
                                            active: {{ $programa->pro_estado ? 'true' : 'false' }},
                                            async toggle() {
                                                this.loading = true;
                                                try {
                                                    const response = await fetch('{{ route('admin.programs.toggle', $programa->pro_id) }}', {
                                                        method: 'POST',
                                                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                                        body: JSON.stringify({ _method: 'PATCH' })
                                                    });
                                                    const data = await response.json();
                                                    if (data.success) { this.active = !!data.new_state; }
                                                } catch (e) { alert('Error'); } finally { this.loading = false; }
                                            }
                                        }">
                                            <button @click="toggle()" :disabled="loading" class="p-2 rounded-lg transition-colors bg-white border border-slate-200 hover:bg-slate-50 text-slate-500" :class="active ? 'text-emerald-500' : 'text-red-500'" title="Cambiar Estado">
                                                <i class="fas" :class="loading ? 'fa-spinner fa-spin' : (active ? 'fa-toggle-on' : 'fa-toggle-off')"></i>
                                            </button>
                                        </div>

                                        <a href="{{ route('admin.programs.edit', $programa->pro_id) }}" class="p-2 rounded-lg bg-white border border-slate-200 text-blue-600 hover:bg-blue-50 transition-colors" title="Editar">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>

                                        <a href="{{ route('admin.programs.export', $programa->pro_id) }}" class="p-2 rounded-lg bg-white border border-slate-200 text-green-600 hover:bg-green-50 transition-colors" title="Descargar Excel Participantes">
                                            <i class="fas fa-file-excel"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.programs.destroy', $programa->pro_id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar esta oferta?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-white border border-slate-200 text-red-500 hover:bg-red-50 transition-colors" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-search text-3xl mb-3 text-slate-300"></i>
                                        <p>No se encontraron ofertas académicas con los filtros seleccionados.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Paginación --}}
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $programas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
