@extends('layouts.admin')

@section('title', 'Asignación de Docentes')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('admin.courses.show', $programa->cur_id) }}"
                    class="inline-flex items-center text-slate-500 hover:text-blue-600 font-medium mb-2 transition-colors">
                    <i class="fa fa-arrow-left mr-2"></i> Volver al Curso
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Cuerpo Docente del Programa</h1>
                <p class="text-slate-500">Gestión de profesores asignados a la oferta #{{ $programa->pro_id }} de
                    {{ $programa->curso->cur_nombre }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Columna Izquierda: Listado --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <h2 class="font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa fa-chalkboard-teacher text-blue-500"></i> Docentes Asignados
                        </h2>
                        <span class="bg-blue-100 text-blue-700 font-bold px-3 py-1 rounded-full text-xs">
                            {{ $programa->relatores->count() }} Docentes
                        </span>
                    </div>

                    @if($programa->relatores->count() > 0)
                        <div class="divide-y divide-slate-100">
                            @foreach($programa->relatores as $relator)
                                <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors group">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-lg">
                                            {{ substr($relator->rel_nombre, 0, 1) }}{{ substr($relator->rel_apellido, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800">{{ $relator->rel_nombre }}
                                                {{ $relator->rel_apellido }}</h4>
                                            <p class="text-sm text-slate-500">{{ $relator->rel_correo }}</p>
                                        </div>
                                    </div>
                                    <form
                                        action="{{ route('admin.programs.detach_teacher', ['id' => $programa->pro_id, 'relLogin' => $relator->rel_login]) }}"
                                        method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-500 p-2 transition-colors"
                                            title="Desvincular">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-10 text-center text-slate-500">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa fa-user-slash text-2xl text-slate-300"></i>
                            </div>
                            <p>No hay docentes asignados a este programa.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Columna Derecha: Acciones --}}
            <div class="space-y-6">
                {{-- Form asignación Manual --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fa fa-plus-circle text-emerald-500"></i> Asignar Docente
                    </h3>
                    <form action="{{ route('admin.programs.assign_teacher', $programa->pro_id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Seleccionar Docente</label>
                            <select name="rel_login"
                                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Seleccione --</option>
                                @foreach($relatoresDisponibles as $rel)
                                    <option value="{{ $rel->rel_login }}">{{ $rel->rel_nombre }} {{ $rel->rel_apellido }}
                                        ({{ $rel->rel_login }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition-colors shadow-lg shadow-blue-500/30">
                            Asignar
                        </button>
                    </form>
                </div>

                {{-- Carga Masiva --}}
                <div
                    class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-lg border border-slate-700 p-6 text-white">
                    <h3 class="font-bold mb-4 flex items-center gap-2 text-amber-400">
                        <i class="fa fa-file-upload"></i> Carga Masiva
                    </h3>
                    <p class="text-sm text-slate-300 mb-4">Sube un archivo CSV con los RUTs (Login) de los docentes en la
                        primera columna.</p>

                    <form action="{{ route('admin.programs.import_teachers', $programa->pro_id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept=".csv,.txt" class="block w-full text-sm text-slate-300
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-xs file:font-semibold
                                file:bg-slate-700 file:text-white
                                hover:file:bg-slate-600 mb-4 cursor-pointer
                            " />
                        <button type="submit"
                            class="w-full bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold py-2.5 rounded-xl transition-colors shadow-lg shadow-amber-500/20">
                            Subir Archivo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection