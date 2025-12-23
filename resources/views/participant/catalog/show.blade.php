@extends('layouts.participant')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Breadcrumb -->
        <nav class="text-sm font-medium text-gray-500">
            <a href="{{ route('participant.catalog.index') }}" class="hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Volver al Catálogo
            </a>
        </nav>

        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="relative h-48 bg-gradient-to-r from-blue-600 to-indigo-700">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="absolute bottom-0 left-0 p-8 w-full bg-gradient-to-t from-black/60 to-transparent">
                    <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                        <div>
                            <!-- Category Badge -->
                            @if($course->categoria)
                                <span
                                    class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold text-white uppercase tracking-wider mb-2">
                                    {{ $course->categoria->cat_nombre }}
                                </span>
                            @endif
                            <h1 class="text-3xl font-bold text-white mb-2">{{ $course->cur_nombre }}</h1>
                            <div class="flex items-center text-white/90 text-sm gap-6">
                                <span><i class="far fa-calendar ml-1"></i> inicio:
                                    {{ $course->cur_fecha_inicio ? $course->cur_fecha_inicio->format('d/m/Y') : 'Por definir' }}</span>
                                <span><i class="far fa-clock ml-1"></i> {{ $course->cur_horas ?? 0 }} horas</span>
                            </div>
                        </div>

                        <!-- Status Badge (Req 78) -->
                        @php
                            $isStarted = $course->cur_fecha_inicio && now()->gte($course->cur_fecha_inicio);
                            $isFinished = $course->cur_fecha_termino && now()->gt($course->cur_fecha_termino);
                            $statusText = 'Disponible';
                            $statusClass = 'bg-green-500';

                            if ($isFinished) {
                                $statusText = 'Finalizado';
                                $statusClass = 'bg-gray-500';
                            } elseif ($isStarted) {
                                $statusText = 'En Progreso';
                                $statusClass = 'bg-blue-500';
                            } elseif ($isFull) {
                                $statusText = 'Cupos Agotados';
                                $statusClass = 'bg-red-500';
                            }
                        @endphp
                        <div class="px-4 py-2 rounded-lg text-white font-bold text-sm shadow-sm {{ $statusClass }}">
                            {{ $statusText }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Description -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 border-b pb-2">Descripción General</h3>
                        <div class="prose text-gray-600">
                            {{ $course->cur_descripcion ?? 'Sin descripción disponible.' }}
                        </div>
                    </div>

                    <!-- Objectives & Contents (Req 77) -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 border-b pb-2">Contenidos y Objetivos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-4 rounded-xl">
                                <h4 class="font-bold text-blue-800 mb-2">Objetivos</h4>
                                <p class="text-sm text-gray-600 whitespace-pre-line">
                                    {{ $course->cur_objetivos ?? 'No especificados' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-xl">
                                <h4 class="font-bold text-blue-800 mb-2">Contenidos</h4>
                                <p class="text-sm text-gray-600 whitespace-pre-line">
                                    {{ $course->cur_contenidos ?? 'No especificados' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-bold text-gray-900">Metodología</h4>
                            <p class="text-sm text-gray-600">{{ $course->cur_metodologias ?? 'No especificada' }}</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Evaluación</h4>
                            <p class="text-sm text-gray-600">{{ $course->cur_aprobacion ?? 'No especificada' }}</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Bibliografía</h4>
                            <p class="text-sm text-gray-600">{{ $course->cur_bibliografia ?? 'No especificada' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar / Action Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 sticky top-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Inscripción</h3>

                        @if($isEnrolled)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4 text-center">
                                <i class="fas fa-check-circle text-green-500 text-3xl mb-2 block"></i>
                                <span class="font-bold text-green-800 block">Estás inscrito</span>
                            </div>

                            <!-- Cancel Button (Req 80) -->
                            @if(!$isStarted)
                                <form action="{{ route('participant.catalog.cancel', $course->cur_id) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de cancelar tu inscripción?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full block text-center py-3 px-4 border border-red-300 text-red-600 font-bold rounded-xl hover:bg-red-50 transition-colors">
                                        Cancelar Inscripción
                                    </button>
                                </form>
                                <p class="text-xs text-gray-400 text-center mt-2">Puedes cancelar antes del inicio del curso.</p>
                            @endif

                        @else
                            <!-- Enrollment Info -->
                            <div class="space-y-3 mb-6">
                                <!-- Slots (Req 79) -->
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-500 text-sm">Cupos Totales</span>
                                    <span class="font-bold text-gray-900">{{ $course->cur_cupos ?? 'Ilimitados' }}</span>
                                </div>
                                @if(!is_null($slotsRemaining))
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-500 text-sm">Disponibles</span>
                                        <span
                                            class="font-bold {{ $slotsRemaining > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $slotsRemaining }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-500 text-sm">Cierre Inscripción</span>
                                    <span
                                        class="font-bold text-gray-900">{{ $course->cur_fecha_inicio ? $course->cur_fecha_inicio->subDays(1)->format('d/m/Y') : '-' }}</span>
                                </div>
                            </div>

                            <!-- Enrollment Button -->
                            @if($isFinished)
                                <button disabled
                                    class="w-full bg-gray-300 text-gray-500 font-bold py-3 px-4 rounded-xl cursor-not-allowed">
                                    Curso Finalizado
                                </button>
                            @elseif($isFull)
                                <button disabled
                                    class="w-full bg-red-100 text-red-500 font-bold py-3 px-4 rounded-xl cursor-not-allowed">
                                    Cupos Agotados
                                </button>
                            @else
                                <form action="{{ route('participant.catalog.enroll', $course->cur_id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transform hover:-translate-y-0.5 transition-all">
                                        Inscribirme Ahora
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection