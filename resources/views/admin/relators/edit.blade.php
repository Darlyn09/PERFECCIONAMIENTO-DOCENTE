@extends('layouts.admin')

@section('title', 'Editar Relator')

@section('content')
    <div class="min-h-screen -m-4 p-4 sm:p-6 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">

        <div class="max-w-4xl mx-auto">
            {{-- Breadcrumb / Header --}}
            <div class="mb-6">
                <a href="{{ route('admin.relators.index') }}"
                    class="text-sm text-slate-500 hover:text-blue-600 mb-2 inline-flex items-center transition-colors">
                    <i class="fa fa-arrow-left mr-2"></i> Volver al listado
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Editar Relator</h1>
                <p class="text-slate-500 text-sm">Editando información de: <span
                        class="font-medium text-blue-600">{{ $relator->rel_nombre }} {{ $relator->rel_apellido }}</span></p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                <form action="{{ route('admin.relators.update', $relator->rel_login) }}" method="POST" class="p-6 sm:p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        {{-- Login (ID) - Solo Lectura --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Login / Identificador
                            </label>
                            <input type="text" value="{{ $relator->rel_login }}" disabled
                                class="w-full px-4 py-2 rounded-lg bg-slate-50 border-slate-200 text-slate-500 font-mono cursor-not-allowed">
                            <p class="text-xs text-amber-600 mt-1"><i class="fa fa-lock mr-1"></i> El identificador no se
                                puede modificar.</p>
                        </div>

                        {{-- Nombre --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Nombres <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="rel_nombre" value="{{ old('rel_nombre', $relator->rel_nombre) }}"
                                required
                                class="w-full px-4 py-2 rounded-lg border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                            @error('rel_nombre')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Apellido --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Apellidos <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="rel_apellido" value="{{ old('rel_apellido', $relator->rel_apellido) }}"
                                required
                                class="w-full px-4 py-2 rounded-lg border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                            @error('rel_apellido')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Correo --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class="fa fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                                <input type="email" name="rel_correo" value="{{ old('rel_correo', $relator->rel_correo) }}"
                                    required
                                    class="w-full pl-10 pr-4 py-2 rounded-lg border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                            </div>
                            @error('rel_correo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Facultad --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Facultad / Unidad
                            </label>
                            <div class="relative">
                                <i
                                    class="fa fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="rel_facultad"
                                    value="{{ old('rel_facultad', $relator->rel_facultad) }}"
                                    class="w-full pl-10 pr-4 py-2 rounded-lg border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                            </div>
                        </div>

                        {{-- Cargo --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Cargo Académico
                            </label>
                            <input type="text" name="rel_cargo" value="{{ old('rel_cargo', $relator->rel_cargo) }}"
                                class="w-full px-4 py-2 rounded-lg border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                        </div>

                        {{-- Télefono --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Teléfono
                            </label>
                            <div class="relative">
                                <i
                                    class="fa fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="rel_fono" value="{{ old('rel_fono', $relator->rel_fono) }}"
                                    class="w-full pl-10 pr-4 py-2 rounded-lg border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.relators.index') }}"
                            class="px-5 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-blue-500/30">
                            Actualizar Relator
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection