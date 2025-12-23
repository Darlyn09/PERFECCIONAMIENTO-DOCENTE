@extends('layouts.admin')

@section('title', 'Nueva Sesión')

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
            <a href="{{ route('admin.courses.show', $curso->cur_id) }}"
                class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-6 bg-slate-700 text-white text-sm font-semibold rounded-xl shadow-lg hover:bg-slate-800 hover:shadow-xl transition-all group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Volver al Curso
            </a>

            {{-- Header Dark Hero --}}
            <div class="relative mb-8 bg-blue-institutional rounded-2xl overflow-hidden shadow-2xl p-8">
                {{-- Decoración de fondo --}}
                <div class="absolute inset-0 opacity-20">
                    <div
                        class="absolute -right-20 -top-20 w-80 h-80 border-[20px] border-gold-institutional/30 rounded-full">
                    </div>
                    <div class="absolute -left-16 -bottom-16 w-64 h-64 border-[16px] border-blue-400/20 rounded-full"></div>
                </div>

                <div class="relative flex items-center gap-6">
                    <div
                        class="w-16 h-16 bg-blue-secondary rounded-2xl flex items-center justify-center shadow-lg shadow-blue-900/30 transform rotate-3 shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Nueva Sesión</h1>
                        <p class="text-indigo-200 text-sm max-w-xl leading-relaxed opacity-90">
                            Agregando sesión al curso: <span
                                class="text-gold-institutional font-semibold">{{ $curso->cur_nombre }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Card del Formulario --}}
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
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

                <form action="{{ route('admin.programs.store') }}" method="POST" class="p-6 sm:p-8">
                    @csrf
                    <input type="hidden" name="cur_id" value="{{ $curso->cur_id }}">

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="pro_inicia">
                                    Fecha de Inicio <span class="text-gold-institutional">*</span>
                                </label>
                                <input type="date" name="pro_inicia" id="pro_inicia" value="{{ old('pro_inicia') }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-institutional focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                    required>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="pro_finaliza">
                                    Fecha de Término
                                </label>
                                <input type="date" name="pro_finaliza" id="pro_finaliza" value="{{ old('pro_finaliza') }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-institutional focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="pro_hora_inicio">
                                    Hora de Inicio
                                </label>
                                <input type="time" name="pro_hora_inicio" id="pro_hora_inicio"
                                    value="{{ old('pro_hora_inicio') }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-institutional focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="pro_hora_termino">
                                    Hora de Término
                                </label>
                                <input type="time" name="pro_hora_termino" id="pro_hora_termino"
                                    value="{{ old('pro_hora_termino') }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-institutional focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="pro_lugar">
                                    Lugar
                                </label>
                                <input type="text" name="pro_lugar" id="pro_lugar" value="{{ old('pro_lugar') }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-institutional focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                    placeholder="Ej: Sala 101">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="pro_cupos">
                                    Cupos
                                </label>
                                <input type="number" name="pro_cupos" id="pro_cupos" value="{{ old('pro_cupos', 30) }}"
                                    min="1"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-institutional focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm">
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
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-institutional focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm appearance-none">
                                        <option value="">Seleccionar...</option>
                                        @foreach($relatores as $relator)
                                            <option value="{{ $relator->rel_login }}" {{ old('rel_login') == $relator->rel_login ? 'selected' : '' }}>
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

                            <div class="space-y-2" x-data="{
                                            search: '',
                                            open: false,
                                            selected: [],
                                            users: {{ $participantes->map(fn($p) => ['id' => $p->par_login, 'name' => $p->par_nombre . ' ' . $p->par_apellido])->toJson() }},
                                            add(user) {
                                                if (!this.selected.some(u => u.id === user.id)) {
                                                    this.selected.push(user);
                                                }
                                                this.search = '';
                                                this.open = false;
                                            },
                                            remove(index) {
                                                this.selected.splice(index, 1);
                                            },
                                            get valueString() {
                                                return this.selected.map(u => u.name).join(', ');
                                            }
                                        }">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="pro_colaboradores">
                                    Colaboradores (Usuarios del Sistema)
                                </label>

                                <input type="hidden" name="pro_colaboradores" :value="valueString">

                                <div class="flex flex-wrap gap-2 mb-2" x-show="selected.length > 0">
                                    <template x-for="(user, index) in selected" :key="user.id">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <span x-text="user.name"></span>
                                            <button type="button" @click="remove(index)"
                                                class="ml-2 text-blue-600 hover:text-blue-900 focus:outline-none">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </span>
                                    </template>
                                </div>

                                <div class="relative">
                                    <input type="text" x-model="search" @focus="open = true" @click.away="open = false"
                                        placeholder="Buscar usuario..."
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-institutional focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm">

                                    <div x-show="open && search.length > 0"
                                        class="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-60 overflow-auto"
                                        style="display: none;">
                                        <template
                                            x-for="user in users.filter(u => u.name.toLowerCase().includes(search.toLowerCase()))"
                                            :key="user.id">
                                            <div @click="add(user)"
                                                class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-slate-700 transition-colors">
                                                <span x-text="user.name"></span>
                                                <span class="text-xs text-slate-400" x-text="'(' + user.id + ')'"></span>
                                            </div>
                                        </template>
                                        <div x-show="users.filter(u => u.name.toLowerCase().includes(search.toLowerCase())).length === 0"
                                            class="px-4 py-3 text-slate-500 text-sm">
                                            No se encontraron resultados.
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Busca y selecciona usuarios para agregarlos como
                                    colaboradores.</p>
                            </div>


                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide" for="pro_horario">
                                Notas / Descripción
                            </label>
                            <textarea name="pro_horario" id="pro_horario" rows="2"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-institutional focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all resize-none shadow-sm"
                                placeholder="Notas adicionales...">{{ old('pro_horario') }}</textarea>
                        </div>

                        <div class="p-6 bg-slate-50 rounded-xl border border-slate-200 space-y-4">
                            <h4
                                class="text-xs font-bold text-slate-500 uppercase flex items-center gap-2 border-b border-slate-200 pb-2 mb-4">
                                <i class="far fa-calendar-check text-gold-institutional"></i> Periodo de Inscripción
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="pro_abre">
                                        Apertura Inscripciones
                                    </label>
                                    <input type="date" name="pro_abre" id="pro_abre" value="{{ old('pro_abre') }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-institutional focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="pro_cierra">
                                        Cierre Inscripciones
                                    </label>
                                    <input type="date" name="pro_cierra" id="pro_cierra" value="{{ old('pro_cierra') }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-institutional focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex flex-col-reverse sm:flex-row items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                        <a href="{{ route('admin.courses.show', $curso->cur_id) }}"
                            class="w-full sm:w-auto px-6 py-3 text-slate-500 hover:text-slate-700 hover:bg-slate-100 font-bold rounded-xl transition-all text-center border border-transparent hover:border-slate-200">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-institutional to-blue-secondary hover:shadow-xl hover:-translate-y-0.5 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Crear Sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection