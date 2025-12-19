@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -m-4 p-4 sm:p-6">
        {{-- Header Principal del Sistema --}}
        <div
            class="relative mb-6 sm:mb-8 bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-xl sm:rounded-2xl overflow-hidden shadow-2xl">
            {{-- Patrón de fondo --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-full h-full"
                    style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
                </div>
            </div>

            {{-- Decoración geométrica (oculta en móvil) --}}
            <div class="hidden sm:block absolute right-0 top-0 w-64 h-64 transform translate-x-20 -translate-y-20">
                <div class="w-full h-full bg-gradient-to-br from-blue-500/20 to-indigo-500/20 rounded-full blur-3xl"></div>
            </div>

            {{-- Contenido --}}
            <div class="relative px-4 sm:px-8 py-6 sm:py-10">
                <div class="flex flex-col gap-4 sm:gap-6">
                    <div>
                        {{-- Logo/Título --}}
                        <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-4">
                            <div
                                class="w-10 sm:w-14 h-10 sm:h-14 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i class="fa fa-graduation-cap text-lg sm:text-2xl text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-3xl md:text-4xl font-bold text-white tracking-tight">
                                    Sistema <span
                                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">SPD</span>
                                </h1>
                                <p class="text-blue-200 text-xs sm:text-sm font-medium tracking-wide uppercase mt-0.5">
                                    Perfeccionamiento Docente
                                </p>
                            </div>
                        </div>

                        {{-- Subtítulo (oculto en móvil pequeño) --}}
                        <p class="hidden sm:block text-slate-300 max-w-lg leading-relaxed text-sm sm:text-base">
                            Plataforma integral de gestión para capacitaciones, cursos y programas de formación profesional
                            continua.
                        </p>
                    </div>

                    {{-- Info rápida móvil --}}
                    <div class="grid grid-cols-3 gap-2 sm:hidden">
                        <div class="text-center bg-white/10 backdrop-blur-sm rounded-lg py-2">
                            <p class="text-lg font-bold text-white">{{ $totalCourses }}</p>
                            <p class="text-[10px] text-blue-200 uppercase">Cursos</p>
                        </div>
                        <div class="text-center bg-white/10 backdrop-blur-sm rounded-lg py-2">
                            <p class="text-lg font-bold text-white">{{ $totalEvents }}</p>
                            <p class="text-[10px] text-blue-200 uppercase">Eventos</p>
                        </div>
                        <div class="text-center bg-white/10 backdrop-blur-sm rounded-lg py-2">
                            <p class="text-lg font-bold text-white">{{ $totalParticipants }}</p>
                            <p class="text-[10px] text-blue-200 uppercase">Participantes</p>
                        </div>
                    </div>

                    {{-- Info rápida desktop --}}
                    <div class="hidden sm:flex justify-end">
                        <div
                            class="flex items-center gap-4 sm:gap-6 bg-white/10 backdrop-blur-sm rounded-xl px-4 sm:px-6 py-3 sm:py-4 border border-white/10">
                            <div class="text-center">
                                <p class="text-xl sm:text-2xl font-bold text-white">{{ $totalCourses }}</p>
                                <p class="text-[10px] sm:text-xs text-blue-200 uppercase tracking-wide">Cursos</p>
                            </div>
                            <div class="w-px h-8 sm:h-10 bg-white/20"></div>
                            <div class="text-center">
                                <p class="text-xl sm:text-2xl font-bold text-white">{{ $totalEvents }}</p>
                                <p class="text-[10px] sm:text-xs text-blue-200 uppercase tracking-wide">Eventos</p>
                            </div>
                            <div class="w-px h-8 sm:h-10 bg-white/20"></div>
                            <div class="text-center">
                                <p class="text-xl sm:text-2xl font-bold text-white">{{ $totalParticipants }}</p>
                                <p class="text-[10px] sm:text-xs text-blue-200 uppercase tracking-wide">Participantes</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Fecha actual --}}
                <div
                    class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-white/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
                    <div class="flex items-center text-slate-400 text-xs sm:text-sm">
                        <i class="fa fa-calendar-day mr-2 text-blue-400"></i>
                        <span
                            class="hidden sm:inline">{{ \Carbon\Carbon::now()->translatedFormat('l, d \\d\\e F \\d\\e Y') }}</span>
                        <span class="sm:hidden">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="flex items-center text-emerald-400 text-xs sm:text-sm">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 animate-pulse"></span>
                            Sistema activo
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            {{-- Columna Principal --}}
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                {{-- Próximos Eventos --}}
                <div class="bg-white/80 backdrop-blur-sm rounded-xl border border-white shadow-lg">
                    <div
                        class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-transparent rounded-t-xl">
                        <h2 class="font-semibold text-slate-800 flex items-center text-sm sm:text-base">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 sm:mr-3"></span>
                            Próximos Eventos
                        </h2>
                        <a href="{{ route('admin.events.index') }}"
                            class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Ver todos →
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($upcomingEvents as $event)
                            <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-blue-50/50 transition-colors">
                                <div class="flex items-start gap-3 sm:gap-4">
                                    {{-- Fecha --}}
                                    <div class="flex-shrink-0 w-11 sm:w-14 text-center">
                                        <div
                                            class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white rounded-t-lg py-1 text-[10px] sm:text-xs font-medium uppercase">
                                            {{ $event->eve_inicia ? \Carbon\Carbon::parse($event->eve_inicia)->translatedFormat('M') : '---' }}
                                        </div>
                                        <div
                                            class="bg-slate-100 rounded-b-lg py-1.5 sm:py-2 border-x border-b border-slate-200">
                                            <span class="text-lg sm:text-xl font-bold text-slate-800">
                                                {{ $event->eve_inicia ? \Carbon\Carbon::parse($event->eve_inicia)->format('d') : '--' }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('admin.events.show', $event->eve_id) }}"
                                            class="font-semibold text-slate-800 hover:text-blue-600 transition-colors text-sm sm:text-base line-clamp-2">
                                            {{ $event->eve_nombre }}
                                        </a>
                                        @if(\Carbon\Carbon::parse($event->eve_inicia)->isPast())
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 ml-2">
                                                En curso
                                            </span>
                                        @endif

                                        {{-- Date Range Display --}}
                                        <div class="mt-1 flex items-center text-xs text-slate-500 font-medium">
                                            <i class="fa fa-calendar-alt mr-1.5 text-blue-400"></i>
                                            Del {{ \Carbon\Carbon::parse($event->eve_inicia)->format('d/m/Y') }}
                                            <span class="mx-1">al</span>
                                            {{ \Carbon\Carbon::parse($event->eve_finaliza)->format('d/m/Y') }}
                                        </div>

                                        <div
                                            class="flex items-center gap-3 sm:gap-4 mt-1.5 sm:mt-2 text-xs sm:text-sm text-slate-500">
                                            @if($event->eve_tipo)
                                                @php
                                                    $tiposEvento = [1 => 'Capacitación', 2 => 'Seminario', 3 => 'Congreso', 4 => 'Taller', 5 => 'Jornada'];
                                                @endphp
                                                <span class="flex items-center">
                                                    <i class="fa fa-tag mr-1 sm:mr-1.5 text-amber-500"></i>
                                                    <span
                                                        class="hidden sm:inline">{{ $tiposEvento[$event->eve_tipo] ?? 'Otro' }}</span>
                                                    <span
                                                        class="sm:hidden">{{ Str::limit($tiposEvento[$event->eve_tipo] ?? 'Otro', 6) }}</span>
                                                </span>
                                            @endif
                                            <span class="flex items-center">
                                                <i class="fa fa-book mr-1 sm:mr-1.5 text-indigo-500"></i>
                                                {{ $event->cursos_count ?? 0 }} <span
                                                    class="hidden sm:inline ml-1">cursos</span>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Acción --}}
                                    <a href="{{ route('admin.events.show', $event->eve_id) }}"
                                        class="flex-shrink-0 px-3 sm:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white text-xs sm:text-sm font-medium rounded-lg hover:from-blue-600 hover:to-indigo-600 transition-all shadow-md hover:shadow-lg">
                                        Ver
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                                <div
                                    class="w-14 sm:w-16 h-14 sm:h-16 mx-auto mb-3 sm:mb-4 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center">
                                    <i class="fa fa-calendar-plus text-xl sm:text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 mb-3 sm:mb-4 text-sm">No hay eventos próximos programados</p>
                                <a href="{{ route('admin.events.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-colors shadow-sm transform hover:-translate-y-0.5">
                                    <i class="fa fa-plus mr-2"></i> Crear evento
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Grid de Estadísticas (Gráficos) --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                    {{-- 1. Gráfico: Categorías Más Tomadas (Dona) --}}
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl border border-white shadow-lg h-full">
                        <div
                            class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-transparent rounded-t-xl">
                            <h2 class="font-semibold text-slate-800 flex items-center text-sm sm:text-base">
                                <span class="w-2 h-2 bg-purple-500 rounded-full mr-2 sm:mr-3"></span>
                                Categorías Más Tomadas
                            </h2>
                        </div>
                        <div class="p-6">
                            @if(isset($topCategories) && count($topCategories) > 0 && $topCategories->sum('alumnos') > 0)
                                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                                    {{-- Gráfico Dona SVG --}}
                                    <div class="relative w-40 h-40 shrink-0">
                                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                                            @php
                                                $totalCat = $topCategories->sum('alumnos');
                                                $accCat = 0;
                                            @endphp
                                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#f1f5f9"
                                                stroke-width="5"></circle>
                                            @foreach($topCategories as $cat)
                                                @php
                                                    $pct = ($cat['alumnos'] / $totalCat) * 100;
                                                    $strokeOffset = -1 * $accCat;
                                                    $accCat += $pct;
                                                    $colorClass = 'text-gray-500';
                                                    if (str_contains($cat['color'], 'blue'))
                                                        $colorClass = 'text-blue-500';
                                                    elseif (str_contains($cat['color'], 'indigo'))
                                                        $colorClass = 'text-indigo-500';
                                                    elseif (str_contains($cat['color'], 'emerald'))
                                                        $colorClass = 'text-emerald-500';
                                                    elseif (str_contains($cat['color'], 'amber'))
                                                        $colorClass = 'text-amber-500';
                                                    elseif (str_contains($cat['color'], 'rose'))
                                                        $colorClass = 'text-rose-500';
                                                    elseif (str_contains($cat['color'], 'violet'))
                                                        $colorClass = 'text-violet-500';
                                                    elseif (str_contains($cat['color'], 'cyan'))
                                                        $colorClass = 'text-cyan-500';
                                                @endphp
                                                <circle cx="18" cy="18" r="15.915" fill="transparent"
                                                    class="{{ $colorClass }} stroke-current hover:opacity-80 transition-opacity cursor-pointer"
                                                    stroke-width="5" stroke-dasharray="{{ $pct }} {{ 100 - $pct }}"
                                                    stroke-dashoffset="{{ $strokeOffset }}">
                                                    <title>{{ $cat['name'] }}: {{ $cat['alumnos'] }}</title>
                                                </circle>
                                            @endforeach
                                            <circle cx="18" cy="18" r="10" fill="white"></circle>
                                        </svg>
                                        <div
                                            class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                            <span class="text-xl font-bold text-slate-700">{{ $totalCat }}</span>
                                            <span class="text-[9px] text-slate-400 uppercase">Alumnos</span>
                                        </div>
                                    </div>
                                    {{-- Leyenda Compacta --}}
                                    <div class="flex-1 w-full text-xs space-y-2">
                                        @foreach($topCategories as $cat)
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center truncate">
                                                    <span
                                                        class="w-2.5 h-2.5 rounded-full bg-{{ $cat['color'] }}-500 mr-2 shrink-0"></span>
                                                    <span class="truncate"
                                                        title="{{ $cat['name'] }}">{{ Str::limit($cat['name'], 20) }}</span>
                                                </div>
                                                <span class="font-bold text-slate-600 ml-2">{{ $cat['alumnos'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-slate-400 text-sm py-8">Sin datos suficientes</div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. Gráfico: Cursos Más Tomados (Barras Horizontales) --}}
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl border border-white shadow-lg h-full">
                        <div
                            class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-transparent rounded-t-xl">
                            <h2 class="font-semibold text-slate-800 flex items-center text-sm sm:text-base">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 sm:mr-3"></span>
                                Cursos Más Tomados
                            </h2>
                        </div>
                        <div class="p-6">
                            @if(isset($topCourses) && count($topCourses) > 0 && $topCourses->sum('programas_count') > 0)
                                @php
                                    $maxPrograms = $topCourses->max('programas_count');
                                @endphp
                                <div class="space-y-4">
                                    @foreach($topCourses as $course)
                                        @php
                                            $width = 0;
                                            if ($maxPrograms > 0) {
                                                $width = ($course['programas_count'] / $maxPrograms) * 100;
                                            }
                                        @endphp
                                        <div class="group">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-xs font-medium text-slate-600 truncate max-w-[200px]"
                                                    title="{{ $course['cur_nombre'] }}">
                                                    {{ $course['cur_nombre'] }}
                                                </span>
                                                <span class="text-xs font-bold text-slate-700">{{ $course['programas_count'] }}
                                                    <span class="text-[9px] font-normal text-slate-400">ediciones</span></span>
                                            </div>
                                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                                <div class="bg-{{ $course['color'] }}-500 h-2.5 rounded-full transition-all duration-1000 ease-out group-hover:bg-{{ $course['color'] }}-600 relative"
                                                    style="width: {{ $width }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-slate-400 text-sm py-8">
                                    <i class="fa fa-chart-bar text-4xl mb-3 opacity-20"></i>
                                    <p>Sin datos de cursos</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Áreas de Capacitación --}}
                <div class="bg-white/80 backdrop-blur-sm rounded-xl border border-white shadow-lg">
                    <div
                        class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-transparent rounded-t-xl">
                        <h2 class="font-semibold text-slate-800 flex items-center text-sm sm:text-base">
                            <span class="w-2 h-2 bg-indigo-500 rounded-full mr-2 sm:mr-3"></span>
                            Áreas de Capacitación
                        </h2>
                    </div>

                    <!-- Tailwind safelist -->
                    <!-- bg-blue-500 bg-indigo-500 bg-purple-500 bg-amber-500 bg-emerald-500 bg-cyan-500 bg-violet-500 bg-sky-500 bg-teal-500 -->

                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            @foreach($courseAreas as $area)
                                <a href="{{ route('admin.courses.index', ['categoria' => $area['id'] ?? '']) }}"
                                    class="flex items-center p-3 sm:p-4 rounded-xl bg-gradient-to-r from-slate-50 to-white border border-slate-100 hover:border-{{ $area['color'] }}-300 hover:shadow-md transition-all group">
                                    <div
                                        class="w-9 sm:w-11 h-9 sm:h-11 rounded-lg sm:rounded-xl bg-gradient-to-br from-{{ $area['color'] }}-400 to-{{ $area['color'] }}-600 flex items-center justify-center mr-3 sm:mr-4 shadow-md">
                                        @if(str_starts_with($area['icon'], 'M'))
                                            <svg class="w-4 sm:w-5 h-4 sm:h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ $area['icon'] }}"></path>
                                            </svg>
                                        @else
                                            <i class="fa {{ $area['icon'] }} text-white text-sm"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="font-semibold text-slate-700 group-hover:text-{{ $area['color'] }}-600 transition-colors truncate text-sm sm:text-base">
                                            {{ $area['name'] }}
                                        </p>
                                        <p class="text-xs sm:text-sm text-slate-400">{{ $area['courses_count'] }} cursos</p>
                                    </div>
                                    <div
                                        class="w-7 sm:w-8 h-7 sm:h-8 rounded-lg bg-slate-100 group-hover:bg-{{ $area['color'] }}-100 flex items-center justify-center transition-colors">
                                        <i
                                            class="fa fa-chevron-right text-xs text-slate-400 group-hover:text-{{ $area['color'] }}-500"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @if($courseAreas->hasPages())
                        {{-- Paginación --}}
                        <div class="border-t border-slate-100 bg-gradient-to-b from-slate-50 to-white rounded-b-2xl">
                            {{ $courseAreas->links() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Columna Lateral --}}
            <div class="space-y-4 sm:space-y-6">
                {{-- Acciones Rápidas --}}
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl shadow-xl text-white">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-white/10">
                        <h2 class="font-semibold flex items-center text-sm sm:text-base">
                            <i class="fa fa-bolt text-amber-400 mr-2"></i>
                            Acciones Rápidas
                        </h2>
                    </div>
                    <div class="p-3 sm:p-4 grid grid-cols-2 lg:grid-cols-1 gap-2">
                        <a href="{{ route('admin.events.create') }}"
                            class="flex items-center w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg bg-white/5 hover:bg-white/10 transition-colors group">
                            <div
                                class="w-8 sm:w-9 h-8 sm:h-9 bg-blue-500 rounded-lg flex items-center justify-center mr-2 sm:mr-3 shadow-lg">
                                <i class="fa fa-calendar-plus text-white text-xs sm:text-sm"></i>
                            </div>
                            <span class="font-medium text-xs sm:text-sm truncate">Nuevo Evento</span>
                            <i
                                class="fa fa-plus ml-auto text-slate-500 group-hover:text-white transition-colors hidden sm:block"></i>
                        </a>

                        <a href="{{ route('admin.courses.create') }}"
                            class="flex items-center w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg bg-white/5 hover:bg-white/10 transition-colors group">
                            <div
                                class="w-8 sm:w-9 h-8 sm:h-9 bg-indigo-500 rounded-lg flex items-center justify-center mr-2 sm:mr-3 shadow-lg">
                                <i class="fa fa-book-open text-white text-xs sm:text-sm"></i>
                            </div>
                            <span class="font-medium text-xs sm:text-sm truncate">Nuevo Curso</span>
                            <i
                                class="fa fa-plus ml-auto text-slate-500 group-hover:text-white transition-colors hidden sm:block"></i>
                        </a>

                        <a href="{{ route('admin.teachers.create') }}"
                            class="flex items-center w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg bg-white/5 hover:bg-white/10 transition-colors group">
                            <div
                                class="w-8 sm:w-9 h-8 sm:h-9 bg-purple-500 rounded-lg flex items-center justify-center mr-2 sm:mr-3 shadow-lg">
                                <i class="fa fa-user-plus text-white text-xs sm:text-sm"></i>
                            </div>
                            <span class="font-medium text-xs sm:text-sm truncate">Nuevo Relator</span>
                            <i
                                class="fa fa-plus ml-auto text-slate-500 group-hover:text-white transition-colors hidden sm:block"></i>
                        </a>

                        <a href="{{ route('admin.categorias.create') }}"
                            class="flex items-center w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg bg-white/5 hover:bg-white/10 transition-colors group">
                            <div
                                class="w-8 sm:w-9 h-8 sm:h-9 bg-amber-500 rounded-lg flex items-center justify-center mr-2 sm:mr-3 shadow-lg">
                                <i class="fa fa-folder-plus text-white text-xs sm:text-sm"></i>
                            </div>
                            <span class="font-medium text-xs sm:text-sm truncate">Nueva Categoría</span>
                            <i
                                class="fa fa-plus ml-auto text-slate-500 group-hover:text-white transition-colors hidden sm:block"></i>
                        </a>
                    </div>
                </div>

                {{-- Soporte --}}
                <div class="bg-gradient-to-br from-slate-100 to-slate-50 rounded-xl p-4 sm:p-5 border border-slate-200">
                    <div class="flex items-center gap-3 mb-2 sm:mb-3">
                        <div
                            class="w-9 sm:w-10 h-9 sm:h-10 bg-slate-200 rounded-lg sm:rounded-xl flex items-center justify-center">
                            <i class="fa fa-headset text-slate-600 text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-700 text-sm sm:text-base">¿Necesitas ayuda?</h3>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 mb-3 sm:mb-4">Contacta al equipo de soporte técnico.</p>
                    <a href="mailto:soporte@spd.cl"
                        class="inline-flex items-center text-xs sm:text-sm text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fa fa-envelope mr-2"></i>
                        soporte@spd.cl
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection