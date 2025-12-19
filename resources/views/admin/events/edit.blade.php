@extends('layouts.admin')

@section('title', 'Editar Evento')

@section('content')
    <div
        class="min-h-screen -m-4 p-4 sm:p-8 relative overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        {{-- Fondo dinámico (reducido) --}}
        <div class="absolute inset-0 opacity-40">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-100 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-100 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-4xl mx-auto">
            {{-- Botón Volver --}}
            <a href="{{ route('admin.events.show', $event->eve_id) }}"
                class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-6 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Volver al Evento
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
                        class="w-16 h-16 bg-gradient-to-br from-amber-400 to-amber-500 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30 transform -rotate-3 shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Editar Evento</h1>
                        <p class="text-indigo-200 text-sm max-w-xl leading-relaxed opacity-90">
                            Modificando: <span class="font-semibold text-white">{{ $event->eve_nombre }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Card del Formulario --}}
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                @if(session('success'))
                    <div
                        class="mx-6 sm:mx-8 mt-6 flex items-center gap-3 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-4 py-3 rounded-r-xl text-sm shadow-sm relative">
                        <i class="fas fa-check-circle text-lg"></i>
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

                <form id="edit-event-form" action="{{ route('admin.events.update', $event->eve_id) }}" method="POST"
                    class="p-6 sm:p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide" for="eve_nombre">
                                Nombre del Evento <span class="text-amber-500">*</span>
                            </label>
                            <input type="text" name="eve_nombre" id="eve_nombre"
                                value="{{ old('eve_nombre', $event->eve_nombre) }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm placeholder-slate-400"
                                required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="eve_tipo">
                                    Tipo de Evento
                                </label>
                                <div class="relative">
                                    <select name="eve_tipo" id="eve_tipo"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm appearance-none">
                                        <option value="1" {{ old('eve_tipo', $event->eve_tipo) == 1 ? 'selected' : '' }}>
                                            Capacitación</option>
                                        <option value="2" {{ old('eve_tipo', $event->eve_tipo) == 2 ? 'selected' : '' }}>
                                            Seminario</option>
                                        <option value="3" {{ old('eve_tipo', $event->eve_tipo) == 3 ? 'selected' : '' }}>
                                            Congreso</option>
                                        <option value="4" {{ old('eve_tipo', $event->eve_tipo) == 4 ? 'selected' : '' }}>
                                            Taller</option>
                                        <option value="5" {{ old('eve_tipo', $event->eve_tipo) == 5 ? 'selected' : '' }}>
                                            Jornada</option>
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
                                    for="eve_estado">
                                    Estado
                                </label>
                                <div class="relative">
                                    <select name="eve_estado" id="eve_estado"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm appearance-none">
                                        <option value="1" {{ old('eve_estado', $event->eve_estado) == 1 ? 'selected' : '' }}>
                                            Activo</option>
                                        <option value="0" {{ old('eve_estado', $event->eve_estado) == 0 ? 'selected' : '' }}>
                                            Inactivo</option>
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
                        </div>

                        <div class="p-6 bg-slate-50 rounded-xl border border-slate-200 space-y-6">
                            <h4
                                class="text-xs font-bold text-slate-500 uppercase flex items-center gap-2 border-b border-slate-200 pb-2 mb-4">
                                <i class="far fa-calendar-alt text-blue-500"></i> Cronograma
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="eve_inicia">
                                        Fecha de Inicio <span class="text-amber-500">*</span>
                                    </label>
                                    <input type="date" name="eve_inicia" id="eve_inicia"
                                        value="{{ old('eve_inicia', $event->eve_inicia) }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm"
                                        required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="eve_finaliza">
                                        Fecha de Término
                                    </label>
                                    <input type="date" name="eve_finaliza" id="eve_finaliza"
                                        value="{{ old('eve_finaliza', $event->eve_finaliza) }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="p-6 bg-slate-50 rounded-xl border border-slate-200 space-y-6">
                            <h4
                                class="text-xs font-bold text-slate-500 uppercase flex items-center gap-2 border-b border-slate-200 pb-2 mb-4">
                                <i class="far fa-clock text-amber-500"></i> Inscripciones
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="eve_abre">
                                        Apertura
                                    </label>
                                    <input type="date" name="eve_abre" id="eve_abre"
                                        value="{{ old('eve_abre', $event->eve_abre) }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="eve_cierra">
                                        Cierre
                                    </label>
                                    <input type="date" name="eve_cierra" id="eve_cierra"
                                        value="{{ old('eve_cierra', $event->eve_cierra) }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                for="eve_descripcion">
                                Descripción
                            </label>
                            <textarea name="eve_descripcion" id="eve_descripcion" rows="4"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all resize-none shadow-sm"
                                placeholder="Descripción del evento...">{{ old('eve_descripcion', $event->eve_descripcion) }}</textarea>
                        </div>
                    </div>

                    <div
                        class="flex flex-col-reverse sm:flex-row items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                        <a href="{{ route('admin.events.show', $event->eve_id) }}"
                            class="w-full sm:w-auto px-6 py-3 text-slate-500 hover:text-slate-700 hover:bg-slate-100 font-bold rounded-xl transition-all text-center border border-transparent hover:border-slate-200">
                            Cancelar
                        </a>
                        <button type="button" @click.prevent="$dispatch('confirm-action', { 
                                title: 'Actualizar Evento', 
                                message: '¿Estás seguro de que deseas guardar los cambios realizados en este evento?', 
                                type: 'edit',
                                formId: 'edit-event-form',
                                confirmText: 'Sí, Actualizar' 
                            })"
                            class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection