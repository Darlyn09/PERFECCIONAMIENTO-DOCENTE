@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Mis Cursos Asignados</h1>
        </div>

        @if($cursos->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <p class="text-gray-500">No tiene cursos asignados actualmente.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cursos as $curso)
                    <div
                        class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                    {{ $curso->categoria->cat_nombre ?? 'General' }}
                                </span>
                                @if($curso->evento)
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold ml-2">
                                        <i class="fa fa-calendar-star mr-1"></i> {{ Str::limit($curso->evento->eve_nombre, 20) }}
                                    </span>
                                @endif
                                <span class="text-sm text-gray-500 flex items-center">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    {{ \Carbon\Carbon::parse($curso->cur_fecha_inicio)->format('d/m/Y') }}
                                </span>
                            </div>

                            <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ $curso->cur_nombre }}</h3>

                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $curso->cur_descripcion ?? 'Sin descripción' }}
                            </p>

                            <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                                <div class="text-sm text-gray-500">
                                    <strong>{{ $curso->inscripciones->count() }}</strong> Alumnos
                                </div>
                                <a href="{{ route('admin.relator.course_students', $curso->cur_id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Ver Alumnos
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $cursos->links() }}
            </div>
        @endif
    </div>
@endsection