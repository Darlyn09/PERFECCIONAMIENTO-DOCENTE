@extends('layouts.participant')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -m-4 p-4 sm:p-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold text-slate-800 mb-2">Seguridad y Contraseña</h1>
            <p class="text-slate-500 mb-8">Gestiona el acceso a tu cuenta.</p>

            <!-- Tarjeta Seguridad -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 md:p-8">
                <h3 class="text-lg font-bold text-slate-800 mb-6 pb-2 border-b border-slate-100 flex items-center">
                    <i class="fa fa-lock text-emerald-500 mr-2"></i> Actualizar Contraseña
                </h3>

                @if(session('success'))
                    <div
                        class="mb-6 bg-emerald-50 text-emerald-600 p-4 rounded-lg text-sm flex items-center shadow-sm border border-emerald-100">
                        <i class="fa fa-check-circle text-lg mr-3"></i> {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 text-red-600 p-4 rounded-lg text-sm shadow-sm border border-red-100">
                        <div class="font-bold flex items-center mb-2">
                            <i class="fa fa-exclamation-circle mr-2"></i> Atención:
                        </div>
                        <ul class="list-disc pl-8 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="update-password-form" action="{{ route('participant.update_password') }}" method="POST"
                    class="max-w-lg">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Contraseña Actual</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                    <i class="fa fa-key"></i>
                                </span>
                                <input type="password" name="current_password" required
                                    placeholder="Ingresa tu contraseña actual"
                                    class="w-full pl-10 px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-50">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nueva Contraseña</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                    <i class="fa fa-lock"></i>
                                </span>
                                <input type="password" name="new_password" required placeholder="Mínimo 8 caracteres"
                                    class="w-full pl-10 px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Confirmar Nueva Contraseña</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                    <i class="fa fa-lock"></i>
                                </span>
                                <input type="password" name="new_password_confirmation" required
                                    placeholder="Repite la nueva contraseña"
                                    class="w-full pl-10 px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm">
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="button" @click.prevent="$dispatch('confirm-action', { 
                                    title: 'Actualizar Contraseña', 
                                    message: '¿Estás seguro de que deseas cambiar tu contraseña?', 
                                    type: 'warning',
                                    formId: 'update-password-form',
                                    confirmText: 'Sí, Cambiar Contraseña' 
                                })"
                                class="w-full md:w-auto px-8 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center">
                                <i class="fa fa-save mr-2"></i> Guardar Nueva Contraseña
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="mt-8 bg-blue-50 border border-blue-100 rounded-xl p-6 flex items-start">
                <i class="fa fa-shield-alt text-blue-500 text-3xl mr-4"></i>
                <div>
                    <h4 class="font-bold text-blue-800 mb-1">Mantén tu cuenta segura</h4>
                    <p class="text-sm text-blue-700">Te recomendamos usar una contraseña fuerte que incluya números, letras
                        y
                        símbolos, y cambiarla periódicamente.</p>
                </div>
            </div>
        </div>
    </div>
@endsection