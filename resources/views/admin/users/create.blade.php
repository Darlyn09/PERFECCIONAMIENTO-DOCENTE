@extends('layouts.admin')

@section('title', 'Nuevo Usuario')

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
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-6 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Volver a Usuarios
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
                        class="w-16 h-16 bg-gradient-to-br from-amber-400 to-amber-500 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30 transform rotate-3 shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Nuevo Usuario</h1>
                        <p class="text-indigo-200 text-sm max-w-xl leading-relaxed opacity-90">
                            Registrar nuevo usuario en el sistema. Asegúrese de ingresar el RUT correctamente.
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

                <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 sm:p-8">
                    @csrf

                    <div class="space-y-6">
                        {{-- Sección Identificación --}}
                        <div class="p-6 bg-slate-50 rounded-xl border border-slate-200 space-y-6">
                            <h4
                                class="text-xs font-bold text-slate-500 uppercase flex items-center gap-2 border-b border-slate-200 pb-2 mb-4">
                                <i class="fas fa-id-card text-amber-500"></i> Identificación y Perfil
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="par_login">
                                        Login (RUT) <span class="text-amber-500">*</span>
                                    </label>
                                    <input type="text" name="par_login" id="par_login" value="{{ old('par_login') }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                        placeholder="Ej: 12345678-9" required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="par_perfil">
                                        Perfil <span class="text-amber-500">*</span>
                                    </label>
                                    <select name="par_perfil" id="par_perfil"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                        required>
                                        <option value="participante" {{ old('par_perfil') == 'participante' ? 'selected' : '' }}>Participante</option>
                                        <option value="admin" {{ old('par_perfil') == 'admin' ? 'selected' : '' }}>
                                            Administrador</option>
                                        <option value="relator" {{ old('par_perfil') == 'relator' ? 'selected' : '' }}>Relator
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="par_nombre">
                                        Nombres <span class="text-amber-500">*</span>
                                    </label>
                                    <input type="text" name="par_nombre" id="par_nombre" value="{{ old('par_nombre') }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                        required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="par_apellido">
                                        Apellidos <span class="text-amber-500">*</span>
                                    </label>
                                    <input type="text" name="par_apellido" id="par_apellido"
                                        value="{{ old('par_apellido') }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                        required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="par_correo">
                                        Correo Electrónico <span class="text-amber-500">*</span>
                                    </label>
                                    <input type="email" name="par_correo" id="par_correo" value="{{ old('par_correo') }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                        required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="par_password">
                                        Contraseña <span class="text-amber-500">*</span>
                                    </label>
                                    <input type="password" name="par_password" id="par_password"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                        required placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        {{-- Sección Institucional --}}
                        <div class="space-y-6">
                            <h4
                                class="text-xs font-bold text-slate-500 uppercase flex items-center gap-2 border-b border-slate-200 pb-2 mb-4">
                                <i class="fas fa-university text-blue-500"></i> Información Institucional
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="par_facultad">
                                        Facultad
                                    </label>
                                    <input type="text" name="par_facultad" id="par_facultad"
                                        value="{{ old('par_facultad') }}"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="par_departamento">
                                        Departamento
                                    </label>
                                    <input type="text" name="par_departamento" id="par_departamento"
                                        value="{{ old('par_departamento') }}"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                        for="par_sede">
                                        Sede
                                    </label>
                                    <input type="text" name="par_sede" id="par_sede" value="{{ old('par_sede') }}"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex flex-col-reverse sm:flex-row items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                        <a href="{{ route('admin.users.index') }}"
                            class="w-full sm:w-auto px-6 py-3 text-slate-500 hover:text-slate-700 hover:bg-slate-100 font-bold rounded-xl transition-all text-center border border-transparent hover:border-slate-200">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Registrar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection