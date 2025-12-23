@extends('layouts.participant')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <a href="{{ route('participant.relator.my_courses') }}"
                    class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Volver a Panel Docente
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Crear Nuevo Curso</h1>
                <p class="text-gray-600 mt-1">Ingresa la información para publicar un nuevo curso y tu primera sesión.</p>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r shadow-sm" role="alert">
                    <p class="font-bold">Por favor corrige los siguientes errores:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('participant.relator.store_course') }}" method="POST"
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @csrf

                <div class="p-6 space-y-6">
                    <!-- Información Básica -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-4">Información
                            General</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label for="cur_nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre del
                                    Curso <span class="text-red-500">*</span></label>
                                <input type="text" name="cur_nombre" id="cur_nombre" value="{{ old('cur_nombre') }}"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                            </div>

                            <div>
                                <label for="eve_id" class="block text-sm font-medium text-gray-700 mb-1">Evento Asociado
                                    <span class="text-red-500">*</span></label>
                                <select name="eve_id" id="eve_id" required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                                    <option value="">Selecciona un evento...</option>
                                    @foreach($eventos as $evento)
                                        <option value="{{ $evento->eve_id }}" {{ old('eve_id') == $evento->eve_id ? 'selected' : '' }}>
                                            {{ $evento->eve_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">El curso pertenecerá a este evento.</p>
                            </div>

                            <div>
                                <label for="cur_categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoría
                                    <span class="text-red-500">*</span></label>
                                <select name="cur_categoria" id="cur_categoria" required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                                    <option value="">Selecciona una categoría...</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->cur_categoria }}" {{ old('cur_categoria') == $categoria->cur_categoria ? 'selected' : '' }}>
                                            {{ $categoria->nom_categoria }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles Académicos -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-4">Detalles
                            Académicos</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="cur_horas" class="block text-sm font-medium text-gray-700 mb-1">Horas Totales
                                    <span class="text-red-500">*</span></label>
                                <input type="number" name="cur_horas" id="cur_horas" value="{{ old('cur_horas') }}" required
                                    min="1"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                            </div>

                            <div>
                                <label for="cur_asistencia" class="block text-sm font-medium text-gray-700 mb-1">%
                                    Asistencia Mínima <span class="text-red-500">*</span></label>
                                <input type="number" name="cur_asistencia" id="cur_asistencia"
                                    value="{{ old('cur_asistencia', 75) }}" required min="0" max="100"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                            </div>

                            <div>
                                <label for="cur_modalidad" class="block text-sm font-medium text-gray-700 mb-1">Modalidad
                                    <span class="text-red-500">*</span></label>
                                <select name="cur_modalidad" id="cur_modalidad" required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                                    <option value="2" {{ old('cur_modalidad') == '2' ? 'selected' : '' }}>Online</option>
                                    <option value="1" {{ old('cur_modalidad') == '1' ? 'selected' : '' }}>Presencial</option>
                                    <option value="4" {{ old('cur_modalidad') == '4' ? 'selected' : '' }}>Híbrido</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="cur_descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción
                                del Curso <span class="text-red-500">*</span></label>
                            <textarea name="cur_descripcion" id="cur_descripcion" rows="3" required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">{{ old('cur_descripcion') }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="cur_objetivos" class="block text-sm font-medium text-gray-700 mb-1">Objetivos del Curso</label>
                            <textarea name="cur_objetivos" id="cur_objetivos" rows="3" required
                                placeholder="Principales aprendizajes esperados..."
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">{{ old('cur_objetivos') }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="cur_contenidos" class="block text-sm font-medium text-gray-700 mb-1">Contenidos del Curso</label>
                            <textarea name="cur_contenidos" id="cur_contenidos" rows="3" required
                                placeholder="Lista de temas a tratar..."
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">{{ old('cur_contenidos') }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="cur_metodologias" class="block text-sm font-medium text-gray-700 mb-1">Metodologías de Enseñanza</label>
                            <textarea name="cur_metodologias" id="cur_metodologias" rows="2" required
                                placeholder="Ej: Clases expositivas, talleres prácticos..."
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">{{ old('cur_metodologias') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="cur_biblografia" class="block text-sm font-medium text-gray-700 mb-1">Bibliografía</label>
                                <textarea name="cur_bibliografia" id="cur_bibliografia" rows="2" required
                                    placeholder="Textos o recursos de referencia..."
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">{{ old('cur_bibliografia', 'No aplica') }}</textarea>
                            </div>

                            <div>
                                <label for="cur_aprobacion" class="block text-sm font-medium text-gray-700 mb-1">Requisitos de Aprobación</label>
                                <textarea name="cur_aprobacion" id="cur_aprobacion" rows="2" required
                                    placeholder="Ej: Nota mínima 4.0 y 70% asistencia..."
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">{{ old('cur_aprobacion', 'Nota mínima 4.0') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Primera Sesión -->
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <h3 class="text-lg font-semibold text-blue-900 border-b border-blue-200 pb-2 mb-4">
                            <i class="fa fa-calendar-check mr-2"></i> Configuración de tu Primera Sesión
                        </h3>
                        <p class="text-sm text-blue-700 mb-4">Esta información creará tu primera sesión asignada
                            automáticamente para empezar a recibir alumnos.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="cur_fecha_inicio" class="block text-sm font-medium text-blue-900 mb-1">Fecha
                                    Inicio Sesión <span class="text-red-500">*</span></label>
                                <input type="date" name="cur_fecha_inicio" id="cur_fecha_inicio"
                                    value="{{ old('cur_fecha_inicio') }}" required
                                    class="w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors bg-white">
                            </div>

                            <div>
                                <label for="cur_fecha_termino" class="block text-sm font-medium text-blue-900 mb-1">Fecha
                                    Término Sesión <span class="text-red-500">*</span></label>
                                <input type="date" name="cur_fecha_termino" id="cur_fecha_termino"
                                    value="{{ old('cur_fecha_termino') }}" required
                                    class="w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors bg-white">
                            </div>

                            <div class="col-span-1 md:col-span-2 space-y-2" x-data="{
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
                                <label class="block text-sm font-medium text-blue-900 mb-1">
                                    Colaboradores (Opcional)
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
                                        class="w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors bg-white">

                                    <div x-show="open && search.length > 0"
                                        class="absolute z-10 w-full mt-1 bg-white border border-blue-200 rounded-lg shadow-lg max-h-60 overflow-auto"
                                        style="display: none;">
                                        <template
                                            x-for="user in users.filter(u => u.name.toLowerCase().includes(search.toLowerCase()))"
                                            :key="user.id">
                                            <div @click="add(user)"
                                                class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-gray-700 transition-colors">
                                                <span x-text="user.name"></span>
                                                <span class="text-xs text-gray-400" x-text="'(' + user.id + ')'"></span>
                                            </div>
                                        </template>
                                        <div x-show="users.filter(u => u.name.toLowerCase().includes(search.toLowerCase())).length === 0"
                                            class="px-4 py-3 text-gray-500 text-sm">
                                            No se encontraron resultados.
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-blue-700 mt-1">Busca y selecciona usuarios para agregarlos como colaboradores
                                    en esta sesión.</p>
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <label for="pro_horario" class="block text-sm font-medium text-blue-900 mb-1">Horario (Texto libre)</label>
                                <input type="text" name="pro_horario" id="pro_horario" value="{{ old('pro_horario') }}"
                                    placeholder="Ej: Lunes y Miércoles 18:00 - 20:00"
                                    class="w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors bg-white">
                            </div>

                            <!-- Campos Dinámicos según Modalidad -->
                            <div class="col-span-1 md:col-span-2" id="div_ubicacion" style="display: none;">
                                <label for="pro_lugar" class="block text-sm font-medium text-blue-900 mb-1">
                                    <i class="fa fa-map-marker-alt mr-1"></i> Ubicación / Sala
                                </label>
                                <input type="text" name="pro_lugar" id="pro_lugar" value="{{ old('pro_lugar') }}"
                                    placeholder="Ej: Sala 101, Fac. Ingeniería"
                                    class="w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors bg-white mb-2">
                                
                                <div id="map" class="w-full h-64 rounded-lg border border-blue-200 z-0"></div>
                                <p class="text-xs text-blue-700 mt-1">Haz clic en el mapa para marcar la ubicación exacta.</p>
                                <input type="hidden" name="pro_lat" id="pro_lat">
                                <input type="hidden" name="pro_lng" id="pro_lng">
                            </div>

                            <div class="col-span-1 md:col-span-2" id="div_link" style="display: none;">
                                <label for="cur_link" class="block text-sm font-medium text-blue-900 mb-1">
                                    <i class="fa fa-video mr-1"></i> Link de Videoconferencia
                                </label>
                                <input type="url" name="cur_link" id="cur_link" value="{{ old('cur_link') }}"
                                    placeholder="Ej: https://zoom.us/j/123456789"
                                    class="w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors bg-white">
                                <p class="text-xs text-blue-700 mt-1">Enlace para la conexión online (Zoom, Meet, Teams).</p>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Lógica Modalidad
                                const modalidadSelect = document.getElementById('cur_modalidad');
                                const divUbicacion = document.getElementById('div_ubicacion');
                                const divLink = document.getElementById('div_link');
                                const inputUbicacion = document.getElementById('pro_lugar');
                                const inputLink = document.getElementById('cur_link');
                                let mapInitialized = false;
                                let map, marker;

                                function toggleFields() {
                                    const modality = modalidadSelect.value;
                                    
                                    divUbicacion.style.display = 'none';
                                    divLink.style.display = 'none';

                                    // 1: Presencial, 4: Híbrido, 2: Online (Sincrónico)

                                    if (modality === '1' || modality === '4') {
                                        divUbicacion.style.display = 'block';
                                        inputUbicacion.required = true;
                                        // Inicializar mapa si no existe
                                        if (!mapInitialized) {
                                            initMap();
                                            mapInitialized = true;
                                        } else {
                                            // Redimensionar mapa al mostrarlo (fix gris Leaflet)
                                            setTimeout(() => { map.invalidateSize(); }, 100);
                                        }
                                    } else {
                                        inputUbicacion.required = false;
                                    }

                                    if (modality === '2' || modality === '4') {
                                        divLink.style.display = 'block';
                                        inputLink.required = true;
                                    } else {
                                        inputLink.required = false;
                                    }
                                }

                                function initMap() {
                                    // Coordenadas por defecto (ej. Concepción, Chile)
                                    const defaultLat = -36.8270;
                                    const defaultLng = -73.0503;

                                    map = L.map('map').setView([defaultLat, defaultLng], 14);

                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        attribution: '&copy; OpenStreetMap contributors'
                                    }).addTo(map);

                                    map.on('click', function(e) {
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
                                        const mapsLink = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
                                        const curLinkInput = document.getElementById('cur_link');
                                        
                                        // Solo sobreescribir si es Presencial o Híbrido (donde usa mapa)
                                        // y si el usuario no ha puesto manualmente un link (o está oculto/vacío)
                                        curLinkInput.value = mapsLink;
                                    });
                                }

                                modalidadSelect.addEventListener('change', toggleFields);
                                toggleFields();
                            });
                        </script>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                    <a href="{{ route('participant.relator.my_courses') }}"
                        class="mr-4 px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fa fa-save mr-2"></i> Guardar Curso y Sesión
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection