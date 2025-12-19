@extends('layouts.admin')

@section('title', 'Editar Relator')

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
            <a href="{{ route('admin.relators.index') }}"
                class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-6 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Volver a Relatores
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
                        class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30 transform -rotate-3 shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Editar Relator</h1>
                        <p class="text-indigo-200 text-sm max-w-xl leading-relaxed opacity-90">
                            Editando: <span class="text-amber-400 font-semibold">{{ $relator->rel_nombre }}
                                {{ $relator->rel_apellido }}</span>
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

                <form id="edit-relator-form" action="{{ route('admin.relators.update', $relator->rel_login) }}"
                    method="POST" class="p-6 sm:p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div class="p-6 bg-slate-50 rounded-xl border border-slate-200 space-y-6">
                            <h4
                                class="text-xs font-bold text-slate-500 uppercase flex items-center gap-2 border-b border-slate-200 pb-2 mb-4">
                                <i class="fas fa-id-card text-amber-500"></i> Identificación
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="rel_login">
                                        Login / Identificador
                                    </label>
                                    <input type="text" value="{{ $relator->rel_login }}" disabled
                                        class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 font-medium cursor-not-allowed shadow-inner font-mono">
                                    <p class="text-xs text-slate-400">El identificador no es editable</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="rel_correo">
                                        Correo Electrónico <span class="text-amber-500">*</span>
                                    </label>
                                    <input type="email" name="rel_correo" id="rel_correo"
                                        value="{{ old('rel_correo', $relator->rel_correo) }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                        required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="rel_nombre">
                                        Nombres <span class="text-amber-500">*</span>
                                    </label>
                                    <input type="text" name="rel_nombre" id="rel_nombre"
                                        value="{{ old('rel_nombre', $relator->rel_nombre) }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                        required>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="rel_apellido">
                                        Apellidos <span class="text-amber-500">*</span>
                                    </label>
                                    <input type="text" name="rel_apellido" id="rel_apellido"
                                        value="{{ old('rel_apellido', $relator->rel_apellido) }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h4
                                class="text-xs font-bold text-slate-500 uppercase flex items-center gap-2 border-b border-slate-200 pb-2 mb-4">
                                <i class="fas fa-briefcase text-blue-500"></i> Información Institucional
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="rel_cargo">
                                        Cargo
                                    </label>
                                    <input type="text" name="rel_cargo" id="rel_cargo"
                                        value="{{ old('rel_cargo', $relator->rel_cargo) }}"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm">
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="rel_facultad">
                                        Facultad / Unidad
                                    </label>
                                    <input type="text" name="rel_facultad" id="rel_facultad"
                                        value="{{ old('rel_facultad', $relator->rel_facultad) }}"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                        placeholder="Ej: Facultad de Ingeniería">
                                    <p class="text-xs text-slate-400 mt-1">Dejar vacío si es relator externo</p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="rel_fono">
                                    Teléfono
                                </label>
                                <input type="text" name="rel_fono" id="rel_fono"
                                    value="{{ old('rel_fono', $relator->rel_fono) }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                    placeholder="+56 9 ...">
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex flex-col-reverse sm:flex-row items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                        <a href="{{ route('admin.relators.index') }}"
                            class="w-full sm:w-auto px-6 py-3 text-slate-500 hover:text-slate-700 hover:bg-slate-100 font-bold rounded-xl transition-all text-center border border-transparent hover:border-slate-200">
                            Cancelar
                        </a>
                        <button type="button" @click.prevent="$dispatch('confirm-action', { 
                                    title: 'Actualizar Relator', 
                                    message: '¿Estás seguro de que deseas guardar los cambios realizados en el perfil del relator?', 
                                    type: 'edit',
                                    formId: 'edit-relator-form',
                                    confirmText: 'Sí, Guardar Cambios' 
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