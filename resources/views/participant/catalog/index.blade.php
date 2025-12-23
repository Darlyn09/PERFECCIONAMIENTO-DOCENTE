@extends('layouts.participant')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Catálogo de Cursos</h1>
                <p class="text-gray-500 mt-1">Explora nuestra oferta académica e inscríbete.</p>
            </div>
        </div>

        <!-- Filters (Req 74) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form method="GET" action="{{ route('participant.catalog.index') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full pl-10 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Buscar curso...">
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <select name="category"
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todas</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->cat_id }}" {{ request('category') == $cat->cat_id ? 'selected' : '' }}>
                                {{ $cat->cat_nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                    <input type="date" name="date_start" value="{{ request('date_start') }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Submit -->
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors shadow-sm">
                        <i class="fas fa-filter mr-2"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Course Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
                @php
                    // Logic for Status (Req 78)
                    $isStarted = $course->cur_fecha_inicio && now()->gte($course->cur_fecha_inicio);
                    $isFinished = $course->cur_fecha_termino && now()->gt($course->cur_fecha_termino);

                    $statusLabel = 'Disponible';
                    $statusColor = 'bg-green-100 text-green-800';

                    if ($isFinished) {
                        $statusLabel = 'Finalizado';
                        $statusColor = 'bg-gray-100 text-gray-800';
                    } elseif ($isStarted) {
                        $statusLabel = 'En Progreso';
                        $statusColor = 'bg-blue-100 text-blue-800';
                    }
                @endphp
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                    <!-- Image Placeholder or Course Image -->
                    <div class="h-40 bg-gradient-to-r from-blue-500 to-indigo-600 relative overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center opacity-30 text-white text-6xl">
                            <i
                                class="fas fa-graduation-cap transform group-hover:scale-110 transition-transform duration-500"></i>
                        </div>
                        <!-- Status Badge -->
                        <span
                            class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="p-6">
                        <!-- Category -->
                        @if($course->categoria)
                            <div class="text-xs font-bold text-blue-600 uppercase mb-2 tracking-wide">
                                {{ $course->categoria->cat_nombre }}
                            </div>
                        @endif

                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $course->cur_nombre }}</h3>
                        <p class="text-sm text-gray-500 mb-4 line-clamp-3">{{ Str::limit($course->cur_descripcion, 100) }}</p>

                        <!-- Details Info -->
                        <div class="space-y-2 text-sm text-gray-600 mb-6">
                            <div class="flex items-center">
                                <i class="far fa-calendar-alt w-5 text-gray-400"></i>
                                <span>Inicio:
                                    {{ $course->cur_fecha_inicio ? $course->cur_fecha_inicio->format('d/m/Y') : 'Por definir' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="far fa-clock w-5 text-gray-400"></i>
                                <span>Duración: {{ $course->cur_horas ?? '-' }} horas</span>
                            </div>
                        </div>

                        <a href="{{ route('participant.catalog.show', $course->cur_id) }}"
                            class="block w-full text-center bg-gray-50 hover:bg-gray-100 text-gray-800 font-bold py-2 px-4 rounded-xl transition-colors border border-gray-200">
                            Ver Detalles <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <div class="bg-gray-50 rounded-full h-20 w-20 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">No se encontraron cursos</h3>
                    <p class="text-gray-500">Intenta ajustar los filtros de búsqueda.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $courses->links() }}
        </div>
    </div>
@endsection