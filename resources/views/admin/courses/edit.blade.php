@extends('layouts.admin')

@section('title', 'Editar Curso')

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
            <a href="{{ route('admin.courses.show', $course->cur_id) }}"
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
                        class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30 transform -rotate-3 shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Editar Curso</h1>
                        <p class="text-indigo-200 text-sm max-w-xl leading-relaxed opacity-90">
                            Editando: <span class="text-amber-400 font-semibold">{{ $course->cur_nombre }}</span>
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

                <form action="{{ route('admin.courses.update', $course->cur_id) }}" method="POST" class="p-6 sm:p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide" for="cur_nombre">
                                Nombre del Curso <span class="text-amber-500">*</span>
                            </label>
                            <input type="text" name="cur_nombre" id="cur_nombre"
                                value="{{ old('cur_nombre', $course->cur_nombre) }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm placeholder-slate-400"
                                required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                    for="cur_categoria">
                                    Categoría
                                </label>
                                <div class="relative">
                                    <select name="cur_categoria" id="cur_categoria"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm appearance-none">
                                        <option value="">Seleccione...</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat->cur_categoria }}" {{ old('cur_categoria', $course->cur_categoria) == $cat->cur_categoria ? 'selected' : '' }}>
                                                {{ $cat->nom_categoria }}
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
                                    for="cur_modalidad">
                                    Modalidad
                                </label>
                                <div class="relative">
                                    <select name="cur_modalidad" id="cur_modalidad"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm appearance-none">
                                        <option value="1" {{ old('cur_modalidad', $course->cur_modalidad) == 1 ? 'selected' : '' }}>Presencial</option>
                                        <option value="2" {{ old('cur_modalidad', $course->cur_modalidad) == 2 ? 'selected' : '' }}>Online Sincrónico</option>
                                        <option value="3" {{ old('cur_modalidad', $course->cur_modalidad) == 3 ? 'selected' : '' }}>Online Asincrónico</option>
                                        <option value="4" {{ old('cur_modalidad', $course->cur_modalidad) == 4 ? 'selected' : '' }}>Híbrido</option>
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

                        <div id="lugar_field" style="display: none;" class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                for="pro_lugar_mass">
                                Ubicacion de la sesion
                            </label>
                            <input type="text" name="pro_lugar_mass" id="pro_lugar_mass"
                                value="{{ old('pro_lugar_mass', $course->programas->first()->pro_lugar ?? '') }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm mb-3"
                                placeholder="Ej: Av. Libertador Bernardo O'Higgins 1234, Santiago">

                            </label>
                            <input type="text" name="pro_lugar_mass" id="pro_lugar_mass"
                                value="{{ old('pro_lugar_mass', $course->programas->first()->pro_lugar ?? '') }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm mb-3"
                                placeholder="Ej: Av. Libertador Bernardo O'Higgins 1234, Santiago">

                            {{-- Mapa Leaflet --}}
                            <div
                                class="relative w-full h-80 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 z-0">
                                <div id="map" class="w-full h-full z-0"></div>
                            </div>

                            {{-- Inputs Ocultos Coordenadas --}}
                            <input type="hidden" name="pro_lat" id="pro_lat">
                            <input type="hidden" name="pro_lng" id="pro_lng">

                            <p class="text-xs text-slate-500 mt-2">
                                <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i>
                                Haz clic en el mapa para marcar la ubicación exacta. Esto generará un enlace de ubicación
                                automáticamente.
                            </p>
                        </div>

                        <div id="link_field" style="display: none;" class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide" for="cur_link">
                                Link de Reunión
                            </label>
                            <input type="url" name="cur_link" id="cur_link" value="{{ old('cur_link', $course->cur_link) }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-blue-600 font-medium transition-all shadow-sm"
                                placeholder="https://zoom.us/j/...">
                        </div>

                        <div class="p-6 bg-slate-50 rounded-xl border border-slate-200 space-y-6">
                            <h4
                                class="text-xs font-bold text-slate-500 uppercase flex items-center gap-2 border-b border-slate-200 pb-2 mb-4">
                                <i class="far fa-clock text-amber-500"></i> Planificación
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="cur_fecha_inicio">
                                        Fecha Inicio
                                    </label>
                                    <input type="date" name="cur_fecha_inicio" id="cur_fecha_inicio"
                                        value="{{ old('cur_fecha_inicio', $course->cur_fecha_inicio) }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="cur_fecha_termino">
                                        Fecha Término
                                    </label>
                                    <input type="date" name="cur_fecha_termino" id="cur_fecha_termino"
                                        value="{{ old('cur_fecha_termino', $course->cur_fecha_termino) }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="cur_horas">
                                        Horas
                                    </label>
                                    <input type="number" name="cur_horas" id="cur_horas"
                                        value="{{ old('cur_horas', $course->cur_horas) }}" min="1"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="cur_asistencia">
                                        % Asistencia
                                    </label>
                                    <input type="number" name="cur_asistencia" id="cur_asistencia"
                                        value="{{ old('cur_asistencia', $course->cur_asistencia) }}" min="0" max="100"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                for="cur_descripcion">
                                Descripción
                            </label>
                            <textarea name="cur_descripcion" id="cur_descripcion" rows="3"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all resize-none shadow-sm"
                                placeholder="Breve descripción del curso...">{{ old('cur_descripcion', $course->cur_descripcion) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide"
                                for="cur_objetivos">
                                Objetivos
                            </label>
                            <textarea name="cur_objetivos" id="cur_objetivos" rows="3"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all resize-none shadow-sm"
                                placeholder="Objetivos principales...">{{ old('cur_objetivos', $course->cur_objetivos) }}</textarea>
                        </div>

                        {{-- Más campos (Collapsible) --}}
                        <details class="group bg-slate-50 rounded-xl border border-slate-200 overflow-hidden">
                            <summary
                                class="cursor-pointer font-bold text-slate-600 hover:text-blue-600 hover:bg-slate-100 px-6 py-4 flex items-center justify-between transition-colors select-none">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-plus-circle text-amber-500"></i> Información Adicional (Opcional)
                                </span>
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform text-slate-400"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </summary>
                            <div class="p-6 pt-0 border-t border-slate-200/50 space-y-4 mt-4">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider"
                                        for="cur_contenidos">Contenidos</label>
                                    <textarea name="cur_contenidos" id="cur_contenidos" rows="2"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all resize-none shadow-sm">{{ old('cur_contenidos', $course->cur_contenidos) }}</textarea>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider"
                                            for="cur_metodologias">Metodologías</label>
                                        <textarea name="cur_metodologias" id="cur_metodologias" rows="2"
                                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all resize-none shadow-sm">{{ old('cur_metodologias', $course->cur_metodologias) }}</textarea>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider"
                                            for="cur_bibliografia">Bibliografía</label>
                                        <textarea name="cur_bibliografia" id="cur_bibliografia" rows="2"
                                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all resize-none shadow-sm">{{ old('cur_bibliografia', $course->cur_bibliografia) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </details>

                    </div>

                    <div
                        class="flex flex-col-reverse sm:flex-row items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                        <a href="{{ route('admin.courses.show', $course->cur_id) }}"
                            class="w-full sm:w-auto px-6 py-3 text-slate-500 hover:text-slate-700 hover:bg-slate-100 font-bold rounded-xl transition-all text-center border border-transparent hover:border-slate-200">
                            Cancelar
                        </a>
                        <button type="submit"
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalidadSelect = document.getElementById('cur_modalidad');
            const linkField = document.getElementById('link_field');
            const lugarField = document.getElementById('lugar_field');
            const linkInput = document.getElementById('cur_link'); // Input del link

            let mapInitialized = false;
            let map, marker;

            function toggleFields() {
                const value = modalidadSelect.value;
                // 1: Presencial, 2: Online Sinc, 3: Online Asinc, 4: Híbrido

                // Link: Online Sinc (2), Online Asinc (3), Híbrido (4)
                // Nota: Para Presencial también generamos link (de mapa) pero el campo puede estar oculto o visible según diseño.
                // Aquí seguiremos la lógica original: visible solo para online/híbrido
                linkField.style.display = (value == '2' || value == '3' || value == '4') ? 'block' : 'none';

                // Lugar: Presencial (1) y Híbrido (4)
                if (value == '1' || value == '4') {
                    lugarField.style.display = 'block';

                    if (!mapInitialized) {
                        initMap();
                        mapInitialized = true;
                    } else {
                        // Fix gris Leaflet al mostrar div oculto
                        setTimeout(() => { map.invalidateSize(); }, 200);
                    }
                } else {
                    lugarField.style.display = 'none';
                }
            }

            function initMap() {
                // Coordenadas iniciales (Intentar extraer de link existente o default Concepción)
                // Si el link actual tiene formato google maps, podríamos intentar parsearlo, pero por simpleza usaremos default
                const defaultLat = -36.8270;
                const defaultLng = -73.0503;

                map = L.map('map').setView([defaultLat, defaultLng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // Click en mapa
                map.on('click', function (e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;

                    if (marker) {
                        marker.setLatLng(e.latlng);
                    } else {
                        marker = L.marker(e.latlng).addTo(map);
                    }

                    document.getElementById('pro_lat').value = lat;
                    document.getElementById('pro_lng').value = lng;

                    // Generar Link de Google Maps y asignarlo a cur_link
                    // Esto permite que el curso tenga una ubicación cliqueable incluso si es presencial
                    const mapsLink = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;

                    if (linkInput) {
                        linkInput.value = mapsLink;
                    }
                });
            }

            modalidadSelect.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
@endsection
```