@extends('layouts.admin')

@section('title', 'Editar Sesión')

@section('content')
    <div
        class="min-h-screen -m-4 p-4 sm:p-8 relative overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        {{-- Fondo dinámico --}}
        <div class="absolute inset-0 opacity-40">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-100 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-100 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-4xl mx-auto">
            {{-- Navegación --}}
            <a href="{{ route('admin.courses.show', $programa->cur_id) }}"
                class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-6 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Volver al Curso
            </a>

            {{-- Header Dark Hero --}}
            <div
                class="relative mb-8 bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl overflow-hidden shadow-2xl p-8">
                {{-- Decoración de fondo --}}
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute -right-20 -top-20 w-80 h-80 border-[20px] border-amber-400/30 rounded-full"></div>
                    <div class="absolute -left-16 -bottom-16 w-64 h-64 border-[16px] border-blue-400/20 rounded-full"></div>
                </div>

                <div class="relative flex items-center gap-6">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30 transform -rotate-3 shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Editar Sesión</h1>
                        <p class="text-indigo-200 text-sm max-w-xl leading-relaxed opacity-90">
                            Curso: <span
                                class="text-amber-400 font-semibold">{{ $programa->curso->cur_nombre ?? 'Curso' }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Card del Formulario --}}
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                @if(session('success'))
                    <div
                        class="mx-6 sm:mx-8 mt-6 flex items-center gap-3 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-4 py-3 rounded-r-xl text-sm shadow-sm">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div
                        class="mx-6 sm:mx-8 mt-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-xl text-sm shadow-sm relative">
                        <strong class="font-bold block mb-1">¡Atención!</strong>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.programs.update', $programa->pro_id) }}" method="POST" class="p-6 sm:p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="pro_inicia">
                                    Fecha de Inicio <span class="text-amber-500">*</span>
                                </label>
                                <input type="date" name="pro_inicia" id="pro_inicia"
                                    value="{{ old('pro_inicia', $programa->pro_inicia) }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                    required>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="pro_finaliza">
                                    Fecha de Término
                                </label>
                                <input type="date" name="pro_finaliza" id="pro_finaliza"
                                    value="{{ old('pro_finaliza', $programa->pro_finaliza) }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="pro_hora_inicio">
                                    Hora de Inicio
                                </label>
                                <input type="time" name="pro_hora_inicio" id="pro_hora_inicio"
                                    value="{{ old('pro_hora_inicio', $programa->pro_hora_inicio) }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="pro_hora_termino">
                                    Hora de Término
                                </label>
                                <input type="time" name="pro_hora_termino" id="pro_hora_termino"
                                    value="{{ old('pro_hora_termino', $programa->pro_hora_termino) }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="pro_lugar">
                                    Lugar
                                </label>
                                <input type="text" name="pro_lugar" id="pro_lugar"
                                    value="{{ old('pro_lugar', $programa->pro_lugar) }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                    placeholder="Ej: Sala 101">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="pro_cupos">
                                    Cupos
                                </label>
                                <input type="number" name="pro_cupos" id="pro_cupos"
                                    value="{{ old('pro_cupos', $programa->pro_cupos) }}" min="1"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="rel_login">
                                    Relator
                                </label>
                                <div class="relative">
                                    <select name="rel_login" id="rel_login"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm appearance-none">
                                        <option value="">Seleccionar...</option>
                                        @foreach($relatores as $relator)
                                            <option value="{{ $relator->rel_login }}" {{ old('rel_login', $programa->rel_login) == $relator->rel_login ? 'selected' : '' }}>
                                                {{ $relator->rel_nombre }} {{ $relator->rel_apellido }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="pro_colaboradores">
                                    Colaboradores
                                </label>
                                <input type="text" name="pro_colaboradores" id="pro_colaboradores"
                                    value="{{ old('pro_colaboradores', $programa->pro_colaboradores) }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide" for="pro_horario">
                                Notas / Descripción
                            </label>
                            <textarea name="pro_horario" id="pro_horario" rows="2"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all resize-none shadow-sm">{{ old('pro_horario', $programa->pro_horario) }}</textarea>
                        </div>

                        <div class="p-6 bg-slate-50 rounded-xl border border-slate-200 space-y-4">
                            <h4
                                class="text-xs font-bold text-slate-500 uppercase flex items-center gap-2 border-b border-slate-200 pb-2 mb-4">
                                <i class="far fa-calendar-check text-amber-500"></i> Periodo de Inscripción
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="pro_abre">
                                        Apertura Inscripciones
                                    </label>
                                    <input type="date" name="pro_abre" id="pro_abre"
                                        value="{{ old('pro_abre', $programa->pro_abre) }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="pro_cierra">
                                        Cierre Inscripciones
                                    </label>
                                    <input type="date" name="pro_cierra" id="pro_cierra"
                                        value="{{ old('pro_cierra', $programa->pro_cierra) }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex flex-col-reverse md:flex-row items-center justify-between gap-4 mt-8 pt-6 border-t border-slate-100">
                        {{-- Eliminar --}}
                        <button type="button"
                            onclick="if(confirm('¿Está seguro de eliminar esta sesión?')) document.getElementById('delete-form').submit();"
                            class="w-full md:w-auto px-5 py-3 text-rose-600 hover:text-white hover:bg-rose-500 font-bold rounded-xl border-2 border-rose-100 hover:border-rose-500 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Eliminar Sesión
                        </button>

                        <div class="flex flex-col-reverse sm:flex-row items-center gap-4 w-full md:w-auto">
                            <a href="{{ route('admin.courses.show', $programa->cur_id) }}"
                                class="w-full sm:w-auto px-6 py-3 text-slate-500 hover:text-slate-700 hover:bg-slate-100 font-bold rounded-xl transition-all text-center border border-transparent hover:border-slate-200">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>
                </form>

                <form id="delete-form" action="{{ route('admin.programs.destroy', $programa->pro_id) }}" method="POST"
                    class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
@endsection