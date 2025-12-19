@extends('layouts.admin')

@section('title', 'Nuevo Curso')

@section('content')
    <div class="min-h-screen -m-4 p-4 sm:p-8 relative overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        {{-- Fondo dinámico --}}
        <div class="absolute inset-0 opacity-40">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-100 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-100 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-4xl mx-auto">
            {{-- Navegación --}}
            <a href="{{ isset($event) ? route('admin.events.show', $event->eve_id) : route('admin.courses.index') }}" 
               class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-6 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ isset($event) ? 'Volver al Evento' : 'Volver a Cursos' }}
            </a>
    
            {{-- Header Dark Hero --}}
            <div class="relative mb-8 bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl overflow-hidden shadow-2xl p-8">
                {{-- Decoración de fondo --}}
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute -right-20 -top-20 w-80 h-80 border-[20px] border-amber-400/30 rounded-full"></div>
                    <div class="absolute -left-16 -bottom-16 w-64 h-64 border-[16px] border-blue-400/20 rounded-full"></div>
                </div>

                <div class="relative flex items-center gap-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30 transform rotate-3 shrink-0">
                         <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Nuevo Curso</h1>
                        <p class="text-indigo-200 text-sm max-w-xl leading-relaxed opacity-90">
                            @if(isset($event))
                                Registrando curso para el evento: <span class="text-amber-400 font-semibold">{{ Str::limit($event->eve_nombre, 40) }}</span>
                            @else
                                Complete el formulario para crear un nuevo curso o taller en el catálogo.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
    
            {{-- Card del Formulario --}}
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                @if($errors->any())
                    <div class="mx-6 sm:mx-8 mt-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-xl text-sm shadow-sm relative">
                        <strong class="font-bold block mb-1">¡Atención!</strong>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
    
                <form id="create-course-form" action="{{ route('admin.courses.store') }}" method="POST" class="p-6 sm:p-8">
                    @csrf
    
                    <div class="space-y-6">
                        {{-- Campo Evento (Opcional) --}}
                        <div class="space-y-2">
                             <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide" for="eve_id">
                                Evento Asociado <span class="text-slate-400 font-normal normal-case">(Opcional)</span>
                            </label>
                            <div class="relative">
                                <select name="eve_id" id="eve_id"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm appearance-none">
                                    <option value="">-- Sin asociación (Catálogo General) --</option>
                                    @foreach($eventos as $evt)
                                        <option value="{{ $evt->eve_id }}" {{ (isset($evento) && $evento->eve_id == $evt->eve_id) || old('eve_id') == $evt->eve_id ? 'selected' : '' }}>
                                            {{ $evt->eve_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <i class="fa fa-calendar-check"></i>
                                </div>
                            </div>
                            <p class="text-xs text-slate-400">Si selecciona un evento, el curso quedará vinculado a él automáticamente.</p>
                        </div>
                        <div class="space-y-2">
                             <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide" for="cur_nombre">
                                Nombre del Curso <span class="text-amber-500">*</span>
                            </label>
                            <input type="text" name="cur_nombre" id="cur_nombre" value="{{ old('cur_nombre') }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm placeholder-slate-400"
                                placeholder="Ej: Taller de Comunicación Efectiva..." required>
                        </div>
    
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide" for="cur_categoria">
                                    Categoría
                                </label>
                                <div class="relative">
                                    <select name="cur_categoria" id="cur_categoria"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm appearance-none">
                                        <option value="">Seleccione...</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat->cur_categoria }}" {{ old('cur_categoria') == $cat->cur_categoria ? 'selected' : '' }}>
                                                {{ $cat->nom_categoria }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
    
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide" for="cur_modalidad">
                                    Modalidad
                                </label>
                                <div class="relative">
                                    <select name="cur_modalidad" id="cur_modalidad"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm appearance-none">
                                        <option value="1">Presencial</option>
                                        <option value="2">Online Sincrónico</option>
                                        <option value="3">Online Asincrónico</option>
                                        <option value="4">Híbrido</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <div id="link_field" style="display: none;" class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide" for="cur_link">
                                Link de Reunión
                            </label>
                            <input type="url" name="cur_link" id="cur_link" 
                                value="{{ old('cur_link') }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-blue-600 font-medium transition-all shadow-sm"
                                placeholder="https://zoom.us/j/...">
                        </div>
    
                        <div class="p-6 bg-slate-50 rounded-xl border border-slate-200 space-y-6">
                            <h4 class="text-xs font-bold text-slate-500 uppercase flex items-center gap-2 border-b border-slate-200 pb-2 mb-4">
                                <i class="far fa-clock text-amber-500"></i> Planificación
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="cur_fecha_inicio">
                                        Fecha Inicio
                                    </label>
                                    <input type="date" name="cur_fecha_inicio" id="cur_fecha_inicio"
                                        value="{{ old('cur_fecha_inicio') }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>
    
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="cur_fecha_termino">
                                        Fecha Término
                                    </label>
                                    <input type="date" name="cur_fecha_termino" id="cur_fecha_termino"
                                        value="{{ old('cur_fecha_termino') }}"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>
    
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="cur_horas">
                                        Horas
                                    </label>
                                    <input type="number" name="cur_horas" id="cur_horas"
                                        value="{{ old('cur_horas', 1) }}" min="1"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>
    
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700" for="cur_asistencia">
                                        % Asistencia
                                    </label>
                                    <input type="number" name="cur_asistencia" id="cur_asistencia"
                                        value="{{ old('cur_asistencia', 75) }}" min="0" max="100"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all shadow-sm">
                                </div>
                            </div>
                        </div>
    
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide" for="cur_descripcion">
                                Descripción
                            </label>
                            <textarea name="cur_descripcion" id="cur_descripcion" rows="3"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all resize-none shadow-sm"
                                placeholder="Breve descripción del curso...">{{ old('cur_descripcion') }}</textarea>
                        </div>
    
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide" for="cur_objetivos">
                                Objetivos
                            </label>
                            <textarea name="cur_objetivos" id="cur_objetivos" rows="3"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all resize-none shadow-sm"
                                placeholder="Objetivos principales...">{{ old('cur_objetivos') }}</textarea>
                        </div>
                        
                            </div>
                        </div>

                        {{-- Ubicación y Mapa (Solo Presencial/Híbrido) --}}
                        <div id="location_field" style="display: none;" class="space-y-4 pt-6 mt-6 border-t border-slate-100">
                            <h4 class="text-xs font-bold text-slate-500 uppercase flex items-center gap-2 mb-4">
                                <i class="fas fa-map-marker-alt text-red-500"></i> Ubicación del Curso
                            </h4>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide" for="cur_lugar">
                                    Dirección / Lugar
                                </label>
                                <div class="relative">
                                    <input type="text" name="cur_lugar" id="cur_lugar" value="{{ old('cur_lugar') }}"
                                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white text-slate-700 font-medium transition-all shadow-sm"
                                        placeholder="Ej: Auditorio Central, Av. Siempre Viva 123...">
                                    <i class="fa fa-map-pin absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                </div>
                                <p class="text-xs text-slate-400">Puede buscar una dirección o marcarla en el mapa.</p>
                            </div>

                            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                                <div id="map" class="w-full h-64 bg-slate-100 z-0"></div>
                            </div>
                            
                            {{-- Lat/Lng Ocultos --}}
                            <input type="hidden" name="cur_latitud" id="cur_latitud" value="{{ old('cur_latitud') }}">
                            <input type="hidden" name="cur_longitud" id="cur_longitud" value="{{ old('cur_longitud') }}">
                        </div>

                        {{-- Más campos (Collapsible) --}}
                        <details class="group bg-slate-50 rounded-xl border border-slate-200 overflow-hidden mt-6">
                            <summary class="cursor-pointer font-bold text-slate-600 hover:text-blue-600 hover:bg-slate-100 px-6 py-4 flex items-center justify-between transition-colors select-none">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-plus-circle text-amber-500"></i> Información Adicional (Opcional)
                                </span>
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </summary>
                            <div class="p-6 pt-0 border-t border-slate-200/50 space-y-4 mt-4">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider" for="cur_contenidos">Contenidos</label>
                                    <textarea name="cur_contenidos" id="cur_contenidos" rows="2"
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all resize-none shadow-sm">{{ old('cur_contenidos') }}</textarea>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider" for="cur_metodologias">Metodologías</label>
                                        <textarea name="cur_metodologias" id="cur_metodologias" rows="2"
                                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all resize-none shadow-sm">{{ old('cur_metodologias') }}</textarea>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider" for="cur_bibliografia">Bibliografía</label>
                                        <textarea name="cur_bibliografia" id="cur_bibliografia" rows="2"
                                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-700 font-medium transition-all resize-none shadow-sm">{{ old('cur_bibliografia') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </details>
    
                    </div>
    
                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                        <a href="{{ isset($event) ? route('admin.events.show', $event->eve_id) : route('admin.courses.index') }}" 
                           class="w-full sm:w-auto px-6 py-3 text-slate-500 hover:text-slate-700 hover:bg-slate-100 font-bold rounded-xl transition-all text-center border border-transparent hover:border-slate-200">
                            Cancelar
                        </a>
                        <button type="button" @click.prevent="$dispatch('confirm-action', { 
                            title: 'Crear Curso', 
                            message: '¿Confirma que la información ingresada es correcta para crear este nuevo curso?', 
                            type: 'enable',
                            formId: 'create-course-form',
                            confirmText: 'Sí, Crear Curso' 
                        })" 
                                class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Crear Curso
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Leaflet CSS & JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalidadSelect = document.getElementById('cur_modalidad');
            const linkField = document.getElementById('link_field');
            const locationField = document.getElementById('location_field');
            const mapContainer = document.getElementById('map');
            
            let map, marker;

            // Inicializar Mapa
            function initMap() {
                if(map) return; // Ya inicializado
                
                // Coordenadas por defecto (Centro de Santiago o configurable)
                const defaultLat = -33.4489;
                const defaultLng = -70.6693;
                
                const latInput = document.getElementById('cur_latitud');
                const lngInput = document.getElementById('cur_longitud');
                
                const initialLat = latInput.value || defaultLat;
                const initialLng = lngInput.value || defaultLng;

                map = L.map('map').setView([initialLat, initialLng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);

                // Update inputs on drag
                marker.on('dragend', function(e) {
                    const latlng = marker.getLatLng();
                    latInput.value = latlng.lat;
                    lngInput.value = latlng.lng;
                    getAddress(latlng.lat, latlng.lng);
                });

                // Update marker on map click
                map.on('click', function(e) {
                    marker.setLatLng(e.latlng);
                    latInput.value = e.latlng.lat;
                    lngInput.value = e.latlng.lng;
                    getAddress(e.latlng.lat, e.latlng.lng);
                });
                
                // Reverse Geocoding function
                async function getAddress(lat, lng) {
                    const placeInput = document.getElementById('cur_lugar');
                    placeInput.placeholder = "Buscando dirección...";
                    
                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                        const data = await response.json();
                        if(data && data.display_name) {
                            placeInput.value = data.display_name;
                        }
                    } catch (error) {
                        console.error('Error obteniendo dirección:', error);
                    }
                }
                
                // Invalidate size to fix rendering issues inside hidden divs
                 setTimeout(() => { map.invalidateSize(); }, 200);
            }

            function toggleFields() {
                const value = modalidadSelect.value;
                
                // Online (2, 3) -> Link
                // Presencial (1), Híbrido (4) -> Ubicación
                
                if (value == '2' || value == '3') {
                    linkField.style.display = 'block';
                    locationField.style.display = 'none';
                } else if (value == '1' || value == '4') {
                    linkField.style.display = (value == '4') ? 'block' : 'none'; // Híbrido lleva ambos
                    locationField.style.display = 'block';
                    initMap();
                } else {
                     linkField.style.display = 'none';
                     locationField.style.display = 'none';
                }
            }

            modalidadSelect.addEventListener('change', toggleFields);
            
            // Run on load
            toggleFields();
        });
    </script>
@endsection
