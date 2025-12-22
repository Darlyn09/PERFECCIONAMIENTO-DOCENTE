@extends('layouts.admin')

@section('title', 'Calificaciones y Asistencia')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('admin.courses.show', $programa->cur_id) }}"
                    class="inline-flex items-center text-slate-500 hover:text-blue-600 font-medium mb-2 transition-colors">
                    <i class="fa fa-arrow-left mr-2"></i> Volver al Curso
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Calificaciones y Asistencia</h1>
                <p class="text-slate-500">Gestión académica para {{ $programa->curso->cur_nombre }} (ID:
                    {{ $programa->pro_id }})</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.programs.grades_template', $programa->pro_id) }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:text-blue-600 font-bold rounded-xl transition-all shadow-sm">
                    <i class="fa fa-download mr-2"></i> Plantilla Excel
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Columna Central: Tabla de Calificaciones --}}
            <div class="lg:col-span-3">
                <form action="{{ route('admin.programs.update_grades', $programa->pro_id) }}" method="POST">
                    @csrf
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h2 class="font-bold text-slate-800">
                                <i class="fa fa-user-graduate text-blue-institutional mr-2"></i> Listado de Alumnos
                            </h2>
                            <button type="submit"
                                class="inline-flex items-center px-5 py-2 bg-blue-institutional hover:bg-blue-900 text-white font-bold rounded-lg shadow-lg shadow-blue-900/30 transition-all">
                                <i class="fa fa-save mr-2"></i> Guardar Cambios
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-slate-50 text-xs text-slate-500 uppercase font-semibold">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Participante</th>
                                        <th class="px-6 py-3 text-center w-32">Nota (1.0 - 7.0)</th>
                                        <th class="px-6 py-3 text-center w-32">Asistencia %</th>
                                        <th class="px-6 py-3 text-center w-32">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($programa->inscripciones as $ins)
                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                            <td class="px-6 py-3">
                                                <div class="font-bold text-slate-800">{{ $ins->participante->par_nombre }}
                                                    {{ $ins->participante->par_apellidos }}</div>
                                                <div class="text-xs text-slate-400">
                                                    {{ $ins->participante->par_rut ?? $ins->participante->par_login }}</div>
                                            </td>
                                            <td class="px-6 py-3">
                                                <input type="number" step="0.1" min="1" max="7" name="notas[{{ $ins->ins_id }}]"
                                                    value="{{ $ins->ins_nota }}"
                                                    class="w-full text-center border-slate-200 rounded-lg focus:ring-blue-institutional focus:border-blue-institutional font-bold text-slate-700"
                                                    placeholder="-">
                                            </td>
                                            <td class="px-6 py-3">
                                                <input type="number" step="1" min="0" max="100"
                                                    name="asistencias[{{ $ins->ins_id }}]" value="{{ $ins->ins_asistencia }}"
                                                    class="w-full text-center border-slate-200 rounded-lg focus:ring-blue-institutional focus:border-blue-institutional text-slate-700"
                                                    placeholder="%">
                                            </td>
                                            <td class="px-6 py-3 text-center">
                                                @if($ins->ins_nota >= 4.0)
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Aprobado</span>
                                                @elseif($ins->ins_nota > 0)
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">Reprobado</span>
                                                @else
                                                    <span class="text-slate-400 text-xs">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                                No hay alumnos inscritos en este programa.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Columna Derecha: Importación --}}
            <div class="space-y-6">
                <div
                    class="bg-gradient-to-br from-blue-institutional to-slate-900 rounded-2xl shadow-xl p-6 text-white border border-blue-900">
                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                        <i class="fa fa-file-excel text-emerald-400"></i> Carga Masiva
                    </h3>
                    <p class="text-indigo-200 text-sm mb-4">
                        Actualiza notas y asistencia subiendo un archivo CSV que siga el formato de la plantilla.
                    </p>

                    <form action="{{ route('admin.programs.import_grades', $programa->pro_id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs uppercase font-bold text-indigo-300 mb-1">Archivo CSV</label>
                            <input type="file" name="file" accept=".csv,.txt" class="block w-full text-sm text-slate-300
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-xs file:font-semibold
                                    file:bg-indigo-600 file:text-white
                                    hover:file:bg-indigo-500 cursor-pointer
                                " />
                        </div>
                        <button type="submit"
                            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 rounded-xl transition-colors shadow-lg shadow-emerald-500/20">
                            Subir Calificaciones
                        </button>
                    </form>
                </div>

                {{-- Stats Rápidas --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h4 class="font-bold text-slate-700 mb-4">Resumen</h4>
                    <ul class="space-y-3">
                        <li class="flex justify-between text-sm">
                            <span class="text-slate-500">Inscritos</span>
                            <span class="font-bold text-slate-800">{{ $programa->inscripciones->count() }}</span>
                        </li>
                        <li class="flex justify-between text-sm">
                            <span class="text-slate-500">Evaluados</span>
                            <span
                                class="font-bold text-slate-800">{{ $programa->inscripciones->where('ins_nota', '>', 0)->count() }}</span>
                        </li>
                        <li class="flex justify-between text-sm">
                            <span class="text-slate-500">Promedio Curso</span>
                            <span class="font-bold text-blue-600">
                                {{ number_format($programa->inscripciones->avg('ins_nota'), 1) }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection