@extends('layouts.participant')

@section('content')
@section('content')
    <div class="max-w-4xl mx-auto py-6">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Centro de Ayuda</h1>
            <p class="text-slate-500 mt-1">¿Tienes dudas o problemas? Estamos aquí para ayudarte.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Formulario de Contacto -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <h2 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-2">
                        <i class="fa fa-envelope-open-text text-blue-600 mr-2"></i> Enviar Mensaje
                    </h2>

                    @if(session('success'))
                        <div class="mb-4 bg-emerald-50 text-emerald-600 p-4 rounded-lg flex items-center shadow-sm">
                            <i class="fa fa-check-circle text-xl mr-3"></i>
                            <div>
                                <p class="font-bold">¡Mensaje Enviado!</p>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('participant.contact.send') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Asunto</label>
                                <select name="subject"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50">
                                    <option value="Soporte Técnico">Soporte Técnico</option>
                                    <option value="Consulta Académica">Consulta Académica</option>
                                    <option value="Problemas con Certificados">Problemas con Certificados</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Mensaje</label>
                                <textarea name="message" rows="5" required
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50 placeholder-slate-400"
                                    placeholder="Describe tu problema o consulta aquí..."></textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-colors shadow-md flex justify-center items-center">
                                <i class="fa fa-paper-plane mr-2"></i> Enviar Solicitud
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Adicional -->
            <div class="space-y-6">
                <!-- FAQ Card -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl shadow-lg p-6 text-white">
                    <h3 class="font-bold text-lg mb-4 flex items-center">
                        <i class="fa fa-circle-question mr-2 text-blue-400"></i> Preguntas Frecuentes
                    </h3>
                    <div class="space-y-4 text-slate-300 text-sm">
                        <div class="border-b border-slate-700 pb-2">
                            <p class="font-semibold text-white mb-1">¿Cómo descargo mi certificado?</p>
                            <p class="opacity-80">Ve a "Mis Cursos" y busca el botón de certificado en los cursos
                                finalizados.</p>
                        </div>
                        <div class="border-b border-slate-700 pb-2">
                            <p class="font-semibold text-white mb-1">¿Cómo cambio mi clave?</p>
                            <p class="opacity-80">En la sección "Seguridad" encontrarás el formulario de cambio de
                                contraseña.</p>
                        </div>
                    </div>
                </div>

                <!-- Contacto Directo -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-bold text-slate-800 mb-4">Contacto Directo</h3>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-start">
                            <i class="fa fa-phone mt-1 w-5 text-blue-600"></i>
                            <span>(41) 220 4000</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-envelope mt-1 w-5 text-blue-600"></i>
                            <span>soporte-spd@udec.cl</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-location-dot mt-1 w-5 text-blue-600"></i>
                            <span>Barrio Universitario, Concepción.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection