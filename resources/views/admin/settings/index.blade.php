@extends('layouts.admin')

@section('title', 'Configuración')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -m-4 p-6">
        {{-- Header --}}
        <div class="mb-6">
            {{-- Botón Volver Destacado --}}
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center px-5 py-2.5 mb-5 bg-gradient-to-r from-slate-700 to-slate-800 text-white font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
                <i class="fa fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                Volver al Dashboard
            </a>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Configuración del Sistema</h1>
                    <p class="text-slate-500 text-sm mt-1">Ajustes generales de la plataforma</p>
                </div>
            </div>
        </div>

        {{-- Búsqueda con diseño llamativo --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-slate-200">
            <div class="flex items-center mb-4">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-slate-600 to-slate-800 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-slate-500/30">
                    <i class="fa fa-search text-white"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Buscar Configuración</h3>
                    <p class="text-xs text-slate-500">Encuentra opciones rápidamente</p>
                </div>
            </div>
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <i class="fa fa-cog absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all placeholder-gray-400 shadow-sm"
                        placeholder="🔍 Buscar configuración...">
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all">
                        <i class="fa fa-search mr-2"></i> Buscar
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.settings.index') }}"
                            class="inline-flex items-center px-5 py-3.5 bg-gradient-to-r from-red-500 to-rose-600 text-white text-sm font-bold rounded-xl hover:from-red-600 hover:to-rose-700 shadow-lg shadow-red-500/30 hover:shadow-xl transition-all">
                            <i class="fa fa-times mr-2"></i> Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Contenido --}}
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-slate-100">

            @if(session('success'))
                <div class="mb-6 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form id="settings-form" action="{{ route('admin.settings.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                {{-- 1. Identidad de la Plataforma --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">
                        <i class="fa fa-id-card mr-2 text-blue-600"></i> Identidad Visual
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nombre --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre de la Plataforma</label>
                            <input type="text" name="platform_name" value="{{ $settings['platform_name'] ?? '' }}"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>

                        {{-- Logo --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Logo Institucional</label>
                            <div class="flex items-center gap-4">
                                @if(!empty($settings['logo_url']))
                                    <div
                                        class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center border border-slate-200 p-1">
                                        <img src="{{ $settings['logo_url'] }}" alt="Logo" class="max-w-full max-h-full">
                                    </div>
                                @endif
                                <input type="file" name="logo" accept="image/*"
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Contacto --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">
                        <i class="fa fa-envelope mr-2 text-blue-600"></i> Contacto y Notificaciones
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Email Institucional</label>
                            <input type="email" name="institution_email" value="{{ $settings['institution_email'] ?? '' }}"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <p class="text-xs text-slate-500 mt-1">Se usará para envíos automáticos.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Teléfono / Contacto</label>
                            <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                    </div>
                </div>

                {{-- 3. Configuración Regional --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">
                        <i class="fa fa-globe mr-2 text-blue-600"></i> Configuración Regional
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Zona Horaria</label>
                            <select name="timezone"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="America/Santiago" {{ ($settings['timezone'] ?? '') == 'America/Santiago' ? 'selected' : '' }}>America/Santiago (Chile)</option>
                                <option value="UTC" {{ ($settings['timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>UTC
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Formato de Fecha</label>
                            <select name="date_format"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="d/m/Y" {{ ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>
                                    dd/mm/aaaa (31/12/2024)</option>
                                <option value="Y-m-d" {{ ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>
                                    aaaa-mm-dd (2024-12-31)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- 4. Estado del Sistema --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">
                        <i class="fa fa-power-off mr-2 text-blue-600"></i> Estado del Sistema
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Estado General</label>
                            <select name="system_status" id="system_status"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="active" {{ ($settings['system_status'] ?? '') == 'active' ? 'selected' : '' }}>
                                    Activo (Normal)</option>
                                <option value="maintenance" {{ ($settings['system_status'] ?? '') == 'maintenance' ? 'selected' : '' }}>Mantenimiento (Bloqueado)</option>
                            </select>
                        </div>
                        <div id="maintenance_msg_container"
                            class="{{ ($settings['system_status'] ?? '') == 'maintenance' ? '' : 'hidden' }}">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Mensaje de Mantenimiento</label>
                            <textarea name="maintenance_message" rows="3"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $settings['maintenance_message'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="button" @click.prevent="$dispatch('confirm-action', { 
                            title: 'Guardar Configuración', 
                            message: '¿Estás seguro de que deseas guardar los cambios en la configuración del sistema?', 
                            type: 'warning',
                            formId: 'settings-form',
                            confirmText: 'Sí, Guardar' 
                        })"
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 transition-all transform hover:scale-105">
                        <i class="fa fa-save mr-2"></i> Guardar Cambios
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.getElementById('system_status').addEventListener('change', function () {
            const container = document.getElementById('maintenance_msg_container');
            if (this.value === 'maintenance') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        });
    </script>
@endsection