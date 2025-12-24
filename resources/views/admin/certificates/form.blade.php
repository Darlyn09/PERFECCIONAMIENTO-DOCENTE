@extends($layout ?? 'layouts.admin')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/@jaames/iro@5"></script>
    <div class="{{ request('mode') == 'modal' ? 'w-full px-2 py-4' : 'container mx-auto px-4 py-6' }}"
        x-data="{ 
                                                                                                                                        activeTab: 'general',
                                                                                                                                        isLoadingPreview: false,
                                                                                                                                        certType: '{{ old('type', isset($certificate) ? data_get($certificate, 'tipo') : 'defecto') }}',

                                                                                                                                        init() {
                                                                                                                                           // Initial load
                                                                                                                                           this.updatePreview();
                                                                                                                                        },

                                                                                                                                        updatePreview() {
                                                                                                                                            if (typeof tinymce !== 'undefined' && tinymce.activeEditor) {
                                                                                                                                                tinymce.triggerSave();
                                                                                                                                            }
                                                                                                                                            this.isLoadingPreview = true;
                                                                                                                                            const form = document.getElementById('certForm');
                                                                                                                                            const formData = new FormData(form);

                                                                                                                                            const url = '{{ route('admin.certificates.preview') }}';
                                                                                                                                            // alert('Debug URL: ' + url); // Uncomment if needed

                                                                                                                                            fetch(url, {
                                                                                                                                                method: 'POST',
                                                                                                                                                headers: {
                                                                                                                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                                                                                                                },
                                                                                                                                                body: formData
                                                                                                                                            })
                                                                                                                                            .then(response => response.text())
                                                                                                                                            .then(html => {
                                                                                                                                                const iframe = document.getElementById('previewFrame');
                                                                                                                                                const doc = iframe.contentDocument || iframe.contentWindow.document;
                                                                                                                                                doc.open();
                                                                                                                                                doc.write(html);
                                                                                                                                                doc.close();
                                                                                                                                                this.isLoadingPreview = false;
                                                                                                                                            })
                                                                                                                                            .catch(error => {
                                                                                                                                                console.error('Error updating preview:', error);
                                                                                                                                                // alert('Preview Error: ' + error); 
                                                                                                                                                this.isLoadingPreview = false;
                                                                                                                                            });
                                                                                                                                        }
                                                                                                                                    }" x-init="init()">


        <!-- Header -->
        @if(request('mode') != 'modal')
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ isset($certificate) ? 'Editar Plantilla' : 'Nueva Plantilla' }}
                    </h1>
                    <p class="text-gray-500">Configura el diseño y contenido del certificado.</p>
                </div>
                <a href="{{ route('admin.certificates.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm font-medium text-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        @else
            <!-- Minimal Modal Header -->
            <div class="mb-4 pb-2 border-b border-gray-200 flex justify-between items-center bg-white sticky top-0 z-10 p-2">
                <h2 class="text-lg font-bold text-gray-800">Editando: {{ $certificate['nombre'] ?? 'Certificado' }}</h2>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
                <ul class="list-disc list-inside text-red-700 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Left Column: Settings Form -->
            <div class="lg:col-span-6 xl:col-span-6">
                <form
                    action="{{ isset($certificate) ? route('admin.certificates.update', array_merge(['id' => $certificate['id']], request()->only('mode'))) : route('admin.certificates.store', request()->only('mode')) }}"
                    method="POST" enctype="multipart/form-data" id="certForm" @change="updatePreview"
                    @input.debounce.800ms="updatePreview">

                    @csrf
                    @if(isset($certificate))
                        @method('PUT')
                    @endif

                    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                        <!-- Hidden Dimensions -->
                        <input type="hidden" name="width" value="{{ $certificate['width'] ?? 800 }}">
                        <input type="hidden" name="height" value="{{ $certificate['height'] ?? 600 }}">

                        <!-- Tabs Header -->
                        <div class="border-b border-gray-200 bg-gray-100 overflow-x-auto">
                            <nav class="flex -mb-px" aria-label="Tabs">
                                <button type="button" @click="activeTab = 'general'"
                                    :class="activeTab === 'general' ? 'border-blue-600 text-blue-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                    class="whitespace-nowrap py-4 px-6 border-b-4 font-bold text-sm flex items-center transition-all">
                                    <i class="fas fa-cog mr-2"></i> General
                                </button>
                                <button type="button" @click="activeTab = 'design'"
                                    :class="activeTab === 'design' ? 'border-blue-600 text-blue-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                    class="whitespace-nowrap py-4 px-6 border-b-4 font-bold text-sm flex items-center transition-all">
                                    <i class="fas fa-palette mr-2"></i> Diseño
                                </button>
                                <button type="button" @click="activeTab = 'content'"
                                    :class="activeTab === 'content' ? 'border-blue-600 text-blue-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                    class="whitespace-nowrap py-4 px-6 border-b-4 font-bold text-sm flex items-center transition-all">
                                    <i class="fas fa-align-center mr-2"></i> Contenido
                                </button>
                                <button type="button" @click="activeTab = 'signature'"
                                    :class="activeTab === 'signature' ? 'border-blue-600 text-blue-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                    class="whitespace-nowrap py-4 px-6 border-b-4 font-bold text-sm flex items-center transition-all">
                                    <i class="fas fa-signature mr-2"></i> Firma
                                </button>
                            </nav>
                        </div>

                        <div class="p-6 bg-gray-50">
                            <!-- GENERAL TAB -->
                            <div x-show="activeTab === 'general'">
                                <div class="mb-5">
                                    <label
                                        class="block text-sm font-extrabold text-gray-800 mb-2 uppercase tracking-wide">Nombre
                                        de la Plantilla</label>
                                    <input type="text" name="name"
                                        class="w-full rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all py-3 px-4 bg-white text-gray-900 font-bold placeholder-gray-400"
                                        value="{{ old('name', $certificate['nombre'] ?? '') }}"
                                        placeholder="Ej: Certificado General 2025" required>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                                    <!-- Type Selection -->
                                    <div>
                                        <label
                                            class="block text-sm font-extrabold text-gray-800 mb-2 uppercase tracking-wide">Tipo
                                            de Certificado</label>
                                        <div class="relative">
                                            <select name="type" x-model="certType"
                                                class="w-full appearance-none rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all py-3 px-4 bg-white text-gray-900 font-medium text-base">
                                                <option value="defecto">Por Defecto</option>
                                                <option value="curso">Específico de Curso</option>
                                                <option value="evento">Específico de Evento</option>
                                            </select>
                                            <div
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1" x-show="certType == 'defecto'">
                                            Se usará este diseño si no existe uno específico para el curso/evento.
                                        </p>
                                    </div>

                                    <!-- Reference Selection (Conditional) -->
                                    <div x-show="certType == 'curso' || certType == 'evento'" x-cloak>
                                        <label
                                            class="block text-sm font-extrabold text-gray-800 mb-2 uppercase tracking-wide">
                                            <span
                                                x-text="certType == 'curso' ? 'Seleccionar Curso' : 'Seleccionar Evento'"></span>
                                        </label>

                                        <!-- Custom Searchable Select -->
                                        <div x-data="{
                                                                                                                                        options: [
                                                                                                                                            @foreach($courses as $id => $name)
                                                                                                                                                { id: '{{ $id }}', label: '{{ e($name) }}' },
                                                                                                                                            @endforeach
                                                                                                                                        ],
                                                                                                                                        open: false,
                                                                                                                                        search: '',
                                                                                                                                        selectedId: '{{ old('referencia_id', isset($certificate) ? data_get($certificate, 'referencia_id') : '') }}',

                                                                                                                                        get selectedLabel() {
                                                                                                                                            const selected = this.options.find(o => o.id == this.selectedId);
                                                                                                                                            return selected ? selected.label : '-- Seleccionar --';
                                                                                                                                        },

                                                                                                                                        get filteredOptions() {
                                                                                                                                            if (this.search === '') return this.options;
                                                                                                                                            return this.options.filter(option => 
                                                                                                                                                option.label.toLowerCase().includes(this.search.toLowerCase())
                                                                                                                                            );
                                                                                                                                        },

                                                                                                                                        select(option) {
                                                                                                                                            this.selectedId = option.id;
                                                                                                                                            this.open = false;
                                                                                                                                            this.search = '';
                                                                                                                                        }
                                                                                                                                    }"
                                            class="relative">

                                            <input type="hidden" name="referencia_id" :value="selectedId">

                                            <!-- Button Trigger -->
                                            <button type="button"
                                                @click="open = !open; $nextTick(() => $refs.searchInput.focus())"
                                                @click.away="open = false"
                                                class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-left shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all flex justify-between items-center">
                                                <span class="block truncate font-medium text-gray-900"
                                                    x-text="selectedLabel"></span>
                                                <span
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400">
                                                    <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                                </span>
                                            </button>

                                            <!-- Dropdown Menu -->
                                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="transform opacity-0 scale-95"
                                                x-transition:enter-end="transform opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="transform opacity-100 scale-100"
                                                x-transition:leave-end="transform opacity-0 scale-95"
                                                class="absolute z-20 mt-1 w-full bg-white shadow-xl rounded-xl border border-gray-200 py-1 max-h-60 overflow-auto focus:outline-none"
                                                style="display: none;">

                                                <!-- Sticky Search Header -->
                                                <div class="sticky top-0 z-10 bg-white border-b border-gray-100 p-2">
                                                    <div class="relative">
                                                        <i
                                                            class="fas fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                                                        <input x-ref="searchInput" x-model="search" type="text"
                                                            class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 bg-gray-50"
                                                            placeholder="Buscar...">
                                                    </div>
                                                </div>

                                                <!-- Options List -->
                                                <ul class="py-1">
                                                    <template x-for="option in filteredOptions" :key="option.id">
                                                        <li @click="select(option)"
                                                            class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50 transition-colors"
                                                            :class="{'bg-blue-50 text-blue-900 font-bold': selectedId == option.id, 'text-gray-900': selectedId != option.id}">
                                                            <span class="block truncate" x-text="option.label"></span>

                                                            <!-- Checkmark for selected -->
                                                            <span x-show="selectedId == option.id"
                                                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
                                                                <i class="fas fa-check"></i>
                                                            </span>
                                                        </li>
                                                    </template>
                                                    <li x-show="filteredOptions.length === 0"
                                                        class="py-2 px-3 text-gray-500 text-sm text-center italic">
                                                        No se encontraron resultados.
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Page Configuration -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5 pt-4 border-t border-gray-200">
                                    <div>
                                        <label
                                            class="block text-sm font-extrabold text-gray-800 mb-2 uppercase tracking-wide">Tamaño
                                            de Hoja</label>
                                        <div class="relative">
                                            <select name="page_size" @change="updatePreview"
                                                class="w-full appearance-none rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all py-3 px-4 bg-white text-gray-900 font-medium text-base">
                                                <option value="custom" {{ (old('page_size', $certificate['page_size'] ?? 'custom') == 'custom') ? 'selected' : '' }}>Personalizado (Px)</option>
                                                <option value="letter" {{ (old('page_size', $certificate['page_size'] ?? '') == 'letter') ? 'selected' : '' }}>Carta (Letter)</option>
                                                <option value="a4" {{ (old('page_size', $certificate['page_size'] ?? '') == 'a4') ? 'selected' : '' }}>A4</option>
                                            </select>
                                            <div
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-extrabold text-gray-800 mb-2 uppercase tracking-wide">Orientación</label>
                                        <div class="relative">
                                            <select name="orientation" @change="updatePreview"
                                                class="w-full appearance-none rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all py-3 px-4 bg-white text-gray-900 font-medium text-base">
                                                <option value="landscape" {{ (old('orientation', $certificate['orientation'] ?? 'landscape') == 'landscape') ? 'selected' : '' }}>Horizontal (Paisaje)
                                                </option>
                                                <option value="portrait" {{ (old('orientation', $certificate['orientation'] ?? '') == 'portrait') ? 'selected' : '' }}>Vertical (Retrato)</option>
                                            </select>
                                            <div
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- DESIGN TAB -->
                            <div x-show="activeTab === 'design'" style="display: none;">
                                <div class="bg-white p-4 rounded-xl border border-gray-300 shadow-sm space-y-4">
                                    <h6 class="text-xs font-bold text-gray-500 uppercase">Configuración de Fondo</h6>
                                    <div>
                                        <div class="mb-5">
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Color de Fondo</label>
                                            <div x-data="{ 
                                                            open: false,
                                                            color: '{{ old('bg_color', isset($certificate) ? $certificate->getConfig('bg_color') : '#ffffff') }}',
                                                            picker: null,
                                                            initPicker() {
                                                                if (this.picker) return;
                                                                console.log('Initializing Background Picker'); // Debug
                                                                this.picker = new iro.ColorPicker(this.$refs.pickerContainer, {
                                                                    width: 160,
                                                                    color: this.color,
                                                                    padding: 0,
                                                                    layout: [
                                                                        { component: iro.ui.Wheel, options: {} },
                                                                        { component: iro.ui.Slider, options: { sliderType: 'value' } },
                                                                    ]
                                                                });
                                                                this.picker.on('color:change', (c) => {
                                                                    this.color = c.hexString;
                                                                    updatePreview();
                                                                });
                                                            }
                                                        }" class="relative z-50">
                                                <input type="hidden" name="bg_color" x-model="color">

                                                <div class="flex items-center gap-3">
                                                    <!-- Trigger Button -->
                                                    <button type="button"
                                                        @click="open = !open; if(open) $nextTick(() => initPicker())"
                                                        @click.away="open = false"
                                                        class="relative w-10 h-10 rounded-full shadow-md ring-2 ring-gray-100 transition-transform hover:scale-105 focus:outline-none"
                                                        style="background: conic-gradient(from 0deg, #ff0000, #ffff00, #00ff00, #00ffff, #0000ff, #ff00ff, #ff0000);">
                                                        <div class="absolute inset-1.5 rounded-full border-2 border-white shadow-inner"
                                                            :style="'background-color: ' + color"></div>
                                                    </button>
                                                    <div class="text-xs text-gray-500 font-mono" x-text="color"></div>
                                                </div>

                                                <!-- Custom Popover -->
                                                <div x-show="open" x-transition style="display: none;"
                                                    class="absolute top-12 left-0 z-[9999] p-4 bg-white rounded-xl shadow-2xl border border-gray-100 w-auto flex flex-col items-center">
                                                    <div x-ref="pickerContainer"></div>
                                                    <div
                                                        class="mt-2 w-full text-center text-xs text-gray-400 uppercase font-bold">
                                                        Seleccionar</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Imagen de Fondo</label>
                                        @if(isset($certificate) && $certificate['imagen_fondo'])
                                            <div class="mb-2 text-xs text-green-600 font-bold"><i class="fas fa-check"></i>
                                                Imagen actual cargada</div>
                                        @endif
                                        <input type="file" name="bg_image" accept="image/*" @change="updatePreview"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200" />
                                    </div>
                                </div>

                                <!-- Extended Design Settings -->
                                <div class="mt-6 bg-white p-4 rounded-xl border border-gray-300 shadow-sm space-y-4">
                                    <h6 class="text-xs font-bold text-gray-500 uppercase flex items-center justify-between">
                                        <span>Estilos de Texto (Opcional - Si no usa el Editor)</span>
                                        <i class="fas fa-font text-gray-400"></i>
                                    </h6>

                                    <!-- Title Styling -->
                                    <div class="grid grid-cols-2 gap-4 border-b border-gray-100 pb-4">
                                        <div class="col-span-2 text-xs font-bold text-blue-600 uppercase">Título Principal
                                        </div>
                                        <div x-data="{ 
                                                                    open: false,
                                                                    color: '{{ old('title_color', isset($certificate) ? $certificate->getConfig('title_color') : '#000000') }}',
                                                                    picker: null,
                                                                    initPicker() {
                                                                        if (this.picker) return;
                                                                        this.picker = new iro.ColorPicker(this.$refs.pickerContainer, {
                                                                            width: 160,
                                                                            color: this.color,
                                                                            padding: 0,
                                                                            layout: [
                                                                                { component: iro.ui.Wheel, options: {} },
                                                                                { component: iro.ui.Slider, options: { sliderType: 'value' } }
                                                                            ]
                                                                        });
                                                                        this.picker.on('color:change', (c) => {
                                                                            this.color = c.hexString;
                                                                            updatePreview();
                                                                        });
                                                                    }
                                                                }" class="relative z-40">
                                            <label class="block text-xs font-bold text-gray-700 mb-2">Color</label>
                                            <input type="hidden" name="title_color" x-model="color">

                                            <!-- Color Wheel Circle Trigger -->
                                            <div class="relative group w-10 h-10 transition-transform hover:scale-105 cursor-pointer"
                                                @click="open = !open; if(open) $nextTick(() => initPicker())"
                                                @click.away="open = false">
                                                <!-- Rainbow Border/Background -->
                                                <div class="absolute inset-0 rounded-full shadow-md ring-2 ring-gray-100"
                                                    style="background: conic-gradient(from 0deg, #ff0000, #ffff00, #00ff00, #00ffff, #0000ff, #ff00ff, #ff0000);">
                                                </div>

                                                <!-- Current Color Inner Circle -->
                                                <div class="absolute inset-1.5 rounded-full border-2 border-white shadow-inner"
                                                    :style="'background-color: ' + color"></div>
                                            </div>

                                            <!-- Custom Popover -->
                                            <div x-show="open" x-transition style="display: none;"
                                                class="absolute top-12 left-0 z-[9999] p-4 bg-white rounded-xl shadow-2xl border border-gray-100 w-auto flex flex-col items-center">
                                                <div x-ref="pickerContainer"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Tamaño (px)</label>
                                            <input type="number" name="title_size"
                                                class="w-full text-xs rounded border-gray-300"
                                                value="{{ old('title_size', isset($certificate) ? $certificate->getConfig('title_size') : 40) }}"
                                                @input="updatePreview">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Margen
                                                Superior</label>
                                            <input type="number" name="title_margin"
                                                class="w-full text-xs rounded border-gray-300"
                                                value="{{ old('title_margin', isset($certificate) ? $certificate->getConfig('title_margin') : 40) }}"
                                                @input="updatePreview">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Fuente</label>
                                            <select name="title_font" class="w-full text-xs rounded border-gray-300"
                                                @change="updatePreview">
                                                <option value="'Arial', sans-serif" {{ (old('title_font', isset($certificate) ? $certificate->getConfig('title_font') : '') == "'Arial', sans-serif") ? 'selected' : '' }}>Arial</option>
                                                <option value="'Georgia', serif" {{ (old('title_font', isset($certificate) ? $certificate->getConfig('title_font') : '') == "'Georgia', serif") ? 'selected' : '' }}>Georgia (Elegante)</option>
                                                <option value="'Courier New', monospace" {{ (old('title_font', isset($certificate) ? $certificate->getConfig('title_font') : '') == "'Courier New', monospace") ? 'selected' : '' }}>Courier (Máquina)
                                                </option>
                                                <option value="'Verdana', sans-serif" {{ (old('title_font', isset($certificate) ? $certificate->getConfig('title_font') : '') == "'Verdana', sans-serif") ? 'selected' : '' }}>Verdana (Legible)
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Body Styling -->
                                    <div class="grid grid-cols-2 gap-4 pt-2">
                                        <div class="col-span-2 text-xs font-bold text-blue-600 uppercase">Cuerpo de Texto
                                        </div>
                                        <div x-data="{ 
                                                                open: false,
                                                                color: '{{ old('body_color', isset($certificate) ? $certificate->getConfig('body_color') : '#333333') }}',
                                                                picker: null,
                                                                initPicker() {
                                                                    if (this.picker) return;
                                                                    this.picker = new iro.ColorPicker(this.$refs.pickerContainer, {
                                                                        width: 160,
                                                                        color: this.color,
                                                                        padding: 0,
                                                                        layout: [
                                                                            { component: iro.ui.Wheel, options: {} },
                                                                            { component: iro.ui.Slider, options: { sliderType: 'value' } },
                                                                        ]
                                                                    });
                                                                    this.picker.on('color:change', (c) => {
                                                                        this.color = c.hexString;
                                                                        updatePreview();
                                                                    });
                                                                }
                                                            }" class="relative z-30">
                                            <label class="block text-xs font-bold text-gray-700 mb-2">Color</label>
                                            <input type="hidden" name="body_color" x-model="color">

                                            <!-- Color Wheel Circle Trigger -->
                                            <div class="relative group w-10 h-10 transition-transform hover:scale-105 cursor-pointer"
                                                @click="open = !open; if(open) $nextTick(() => initPicker())"
                                                @click.away="open = false">
                                                <!-- Rainbow Border/Background -->
                                                <div class="absolute inset-0 rounded-full shadow-md ring-2 ring-gray-100"
                                                    style="background: conic-gradient(from 0deg, #ff0000, #ffff00, #00ff00, #00ffff, #0000ff, #ff00ff, #ff0000);">
                                                </div>

                                                <!-- Current Color Inner Circle -->
                                                <div class="absolute inset-1.5 rounded-full border-2 border-white shadow-inner"
                                                    :style="'background-color: ' + color"></div>
                                            </div>

                                            <!-- Custom Popover -->
                                            <div x-show="open" x-transition style="display: none;"
                                                class="absolute bottom-full mb-2 left-0 z-[9999] p-4 bg-white rounded-xl shadow-2xl border border-gray-100 w-auto flex flex-col items-center">
                                                <div x-ref="pickerContainer"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Tamaño (px)</label>
                                            <input type="number" name="body_size"
                                                class="w-full text-xs rounded border-gray-300"
                                                value="{{ old('body_size', isset($certificate) ? $certificate->getConfig('body_size') : 20) }}"
                                                @input="updatePreview">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Margen
                                                Estudiante</label>
                                            <input type="number" name="student_margin"
                                                class="w-full text-xs rounded border-gray-300"
                                                value="{{ old('student_margin', isset($certificate) ? $certificate->getConfig('student_margin') : 20) }}"
                                                @input="updatePreview">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Margen Curso</label>
                                            <input type="number" name="course_margin"
                                                class="w-full text-xs rounded border-gray-300"
                                                value="{{ old('course_margin', isset($certificate) ? $certificate->getConfig('course_margin') : 20) }}"
                                                @input="updatePreview">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 bg-white p-4 rounded-xl border border-gray-300 shadow-sm space-y-4">
                                    <h6 class="text-xs font-bold text-gray-500 uppercase">Configuración del QR</h6>
                                    <div>
                                        <label class="relative inline-flex items-center cursor-pointer mb-3">
                                            <input type="checkbox" name="show_qr" value="1" class="sr-only peer" {{ old('show_qr', isset($certificate) ? filter_var($certificate->getConfig('show_qr'), FILTER_VALIDATE_BOOLEAN) : false) ? 'checked' : '' }} @change="updatePreview">
                                            <div
                                                class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                            </div>
                                            <span class="ml-3 text-sm font-bold text-gray-800">Activar Código QR</span>
                                        </label>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1">Posición</label>
                                                <div class="flex flex-col gap-2">
                                                    <label
                                                        class="inline-flex items-center p-2 border rounded-lg hover:bg-gray-50 cursor-pointer"
                                                        :class="{'bg-blue-50 border-blue-500': $el.querySelector('input').checked}">
                                                        <input type="radio" name="qr_position" value="left" {{ (old('qr_position', isset($certificate) ? $certificate->getConfig('qr_position') : 'left') == 'left') ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500"
                                                            @change="updatePreview">
                                                        <span class="ml-2 text-sm font-medium">Izquierda</span>
                                                    </label>
                                                    <label
                                                        class="inline-flex items-center p-2 border rounded-lg hover:bg-gray-50 cursor-pointer"
                                                        :class="{'bg-blue-50 border-blue-500': $el.querySelector('input').checked}">
                                                        <input type="radio" name="qr_position" value="right" {{ (old('qr_position', isset($certificate) ? $certificate->getConfig('qr_position') : 'left') == 'right') ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500"
                                                            @change="updatePreview">
                                                        <span class="ml-2 text-sm font-medium">Derecha</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1">Tamaño</label>
                                                <input type="range" name="qr_size" min="50" max="200"
                                                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                                    value="{{ old('qr_size', isset($certificate) ? $certificate->getConfig('qr_size') : 100) }}"
                                                    @input="updatePreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- CONTENT TAB -->
                            <!-- CONTENT TAB -->
                            <div x-show="activeTab === 'content'" style="display: none;"
                                x-data="{
                                                                                                                                editorInitialized: false,
                                                                                                                                initEditor() {
                                                                                                                                    if (this.editorInitialized) return;
                                                                                                                                    if (typeof tinymce === 'undefined') return;

                                                                                                                                    // Wait a tick for x-show to render the element visible
                                                                                                                                    this.$nextTick(() => {
                                                                                                                                        tinymce.init({
                                                                                                                                            selector: '#certContent',
                                                                                                                                            height: 500,
                                                                                                                                            menubar: false,
                                                                                                                                            plugins: 'link image code table lists',
                                                                                                                                            toolbar: 'undo redo | formatselect | fontselect fontsizeselect | ' +
                                                                                                                                                'bold italic backcolor | alignleft aligncenter ' +
                                                                                                                                                'alignright alignjustify | bullist numlist outdent indent | ' +
                                                                                                                                                'removeformat | help',
                                                                                                                                            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
                                                                                                                                            setup: (editor) => {
                                                                                                                                                editor.on('change keyup', function(e) {
                                                                                                                                                    editor.save(); // Sync to textarea
                                                                                                                                                    // Trigger native input event so Alpine's @input listener on the form fires updatePreview
                                                                                                                                                    const event = new Event('input', { bubbles: true });
                                                                                                                                                    document.getElementById('certContent').dispatchEvent(event);
                                                                                                                                                });
                                                                                                                                                this.editorInitialized = true;
                                                                                                                                            }
                                                                                                                                        });
                                                                                                                                    });
                                                                                                                                },
                                                                                                                                insertVariable(variable) {
                                                                                                                                    if (tinymce.get('certContent')) {
                                                                                                                                        tinymce.get('certContent').insertContent(variable);
                                                                                                                                    }
                                                                                                                                }
                                                                                                                             }"
                                x-effect="if (activeTab === 'content') initEditor()">

                                <div class="space-y-4">
                                    <!-- Variables Help Block -->
                                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-6">
                                        <h6 class="text-xs font-bold text-blue-800 uppercase mb-2"><i
                                                class="fas fa-info-circle mr-1"></i> Variables Disponibles</h6>
                                        <p class="text-xs text-blue-700 mb-2">Haz clic para insertar la variable en el
                                            editor:</p>
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button" @click="insertVariable('{nombre_participante}')"
                                                class="px-2 py-1 bg-white border border-blue-300 text-blue-700 text-xs font-mono rounded hover:bg-blue-100 transition-colors shadow-sm">{nombre_participante}</button>
                                            <button type="button" @click="insertVariable('{rut_participante}')"
                                                class="px-2 py-1 bg-white border border-blue-300 text-blue-700 text-xs font-mono rounded hover:bg-blue-100 transition-colors shadow-sm">{rut_participante}</button>
                                            <button type="button" @click="insertVariable('{nombre_curso}')"
                                                class="px-2 py-1 bg-white border border-blue-300 text-blue-700 text-xs font-mono rounded hover:bg-blue-100 transition-colors shadow-sm">{nombre_curso}</button>
                                            <button type="button" @click="insertVariable('{nombre_relator}')"
                                                class="px-2 py-1 bg-white border border-blue-300 text-blue-700 text-xs font-mono rounded hover:bg-blue-100 transition-colors shadow-sm">{nombre_relator}</button>
                                            <button type="button" @click="insertVariable('{fecha_inicio}')"
                                                class="px-2 py-1 bg-white border border-blue-300 text-blue-700 text-xs font-mono rounded hover:bg-blue-100 transition-colors shadow-sm">{fecha_inicio}</button>
                                            <button type="button" @click="insertVariable('{fecha_termino}')"
                                                class="px-2 py-1 bg-white border border-blue-300 text-blue-700 text-xs font-mono rounded hover:bg-blue-100 transition-colors shadow-sm">{fecha_termino}</button>
                                            <button type="button" @click="insertVariable('{horas_curso}')"
                                                class="px-2 py-1 bg-white border border-blue-300 text-blue-700 text-xs font-mono rounded hover:bg-blue-100 transition-colors shadow-sm">{horas_curso}</button>
                                            <button type="button" @click="insertVariable('{nota}')"
                                                class="px-2 py-1 bg-white border border-blue-300 text-blue-700 text-xs font-mono rounded hover:bg-blue-100 transition-colors shadow-sm">{nota}</button>
                                        </div>
                                    </div>

                                    <!-- Rich Text Editor -->
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Contenido del
                                            Certificado</label>
                                        <textarea id="certContent" name="content_html" rows="20"
                                            class="w-full rounded-lg border border-gray-300">
                                                                                                                        @php
                                                                                                                            $savedContent = old('content_html');
                                                                                                                            if (!$savedContent && isset($certificate)) {
                                                                                                                                $savedContent = data_get($certificate, 'configuracion.content_html');
                                                                                                                            }
                                                                                                                        @endphp

                                                                                                                        @if($savedContent)
                                                                                                                            {!! $savedContent !!}
                                                                                                                        @else
                                                                                                                            <div style="text-align: center;">
                                                                                                                                <h1 style="color: #333333; font-size: 40px; margin-bottom: 20px;">CERTIFICADO DE APROBACIÓN</h1>
                                                                                                                                <p style="color: #666666; font-size: 20px; margin-bottom: 5px;">Se otorga a:</p>
                                                                                                                                <p style="color: #000000; font-size: 30px; font-weight: bold; margin-bottom: 20px;">{nombre_participante}</p>
                                                                                                                                <p style="color: #666666; font-size: 20px; margin-bottom: 5px;">Por aprobar el curso:</p>
                                                                                                                                <p style="color: #000000; font-size: 25px; font-weight: bold; margin-bottom: 20px;">{nombre_curso}</p>
                                                                                                                                <p style="color: #666666; font-size: 16px;">Fecha: {fecha_termino}</p>
                                                                                                                            </div>
                                                                                                                        @endif
                                                                                                                                    </textarea>
                                    </div>
                                </div>

                                <!-- TinyMCE Init Script (CDNJS to avoid API Key Requirement) -->
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"
                                    referrerpolicy="origin"></script>
                            </div>

                            <!-- SIGNATURE TAB -->
                            <div x-show="activeTab === 'signature'" style="display: none;">
                                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                                    <label class="block text-sm font-bold text-blue-800 mb-2">Firma Digital</label>
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nombre /
                                            Cargo</label>
                                        <input type="text" name="signature_text"
                                            class="w-full rounded-lg border border-blue-200 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 p-3"
                                            value="{{ old('signature_text', isset($certificate) ? $certificate->getConfig('signature_text') : 'Director Académico') }}">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Imagen de
                                            Firma</label>
                                        @if(isset($certificate) && $certificate['firma_imagen'])
                                            <div class="mb-2 text-xs text-green-600 font-bold"><i class="fas fa-check"></i>
                                                Firma actual cargada</div>
                                        @endif
                                        <input type="file" name="signature_image" @change="updatePreview"
                                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-100 px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                            <button type="button" @click.prevent="
                                                                                                                            if (typeof tinymce !== 'undefined' && tinymce.get('certContent')) { tinymce.get('certContent').save(); }
                                                                                                                            $dispatch('confirm-action', { 
                                                                                                                            title: 'Guardar Plantilla', 
                                                                                                                            message: '¿Confirma que desea guardar los cambios en esta plantilla?', 
                                                                                                                            type: 'enable',
                                                                                                                            formId: 'certForm',
                                                                                                                            confirmText: 'Sí, Guardar' 
                                                                                                                        })"
                                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg transform hover:-translate-y-0.5 transition-all flex items-center">
                                <i class="fas fa-save mr-2"></i> GUARDAR CAMBIOS
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column: Live Preview -->
            <div class="lg:col-span-6 xl:col-span-6 sticky top-6">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gray-900 text-white px-6 py-4 flex justify-between items-center">
                        <h3 class="font-bold text-base uppercase tracking-wider"><i
                                class="fas fa-eye mr-2 text-blue-400"></i> Vista Previa</h3>
                        <span class="text-xs text-blue-200 font-mono" x-show="isLoadingPreview"><i
                                class="fas fa-circle-notch fa-spin mr-1"></i> Renderizando...</span>
                    </div>

                    <div
                        class="relative bg-gray-200 p-6 min-h-[500px] flex items-center justify-center border-b border-gray-300">
                        <div class="w-full aspect-[4/3] bg-white shadow-2xl rounded-lg overflow-hidden ring-1 ring-black/5">
                            <iframe id="previewFrame" class="w-full h-full border-0"
                                title="Vista Previa del Certificado"></iframe>
                        </div>
                    </div>

                    <div class="px-6 py-3 bg-gray-50 text-xs text-center text-gray-500 font-medium">
                        La vista previa se actualiza automáticamente al editar.
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection