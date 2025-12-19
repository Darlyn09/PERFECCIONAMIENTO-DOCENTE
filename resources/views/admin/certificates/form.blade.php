@extends($layout ?? 'layouts.admin')

@section('content')
    <div class="{{ request('mode') == 'modal' ? 'w-full px-2 py-4' : 'container mx-auto px-4 py-6' }}" x-data="{ 
                                                activeTab: 'general',
                                                isLoadingPreview: false,

                                                extractClass(val) { return val; },

                                                updateBorderOutput(val) { 
                                                    document.getElementById('borderOutput').value = val + 'px'; 
                                                },

                                                init() {
                                                   // Initial load
                                                   this.updatePreview();
                                                },

                                                updatePreview() {
                                                    this.isLoadingPreview = true;
                                                    const form = document.getElementById('certForm');
                                                    const formData = new FormData(form);

                                                    fetch('{{ route('admin.certificates.preview') }}', {
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
                 <h2 class="text-lg font-bold text-gray-800">Editando: {{ $certificate['name'] ?? 'Certificado' }}</h2>
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
                        method="POST" enctype="multipart/form-data" id="certForm" @change="updatePreview" @input.debounce.800ms="updatePreview">

                        @csrf
                        @if(isset($certificate))
                            @method('PUT')
                        @endif

                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
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
                                        <label class="block text-sm font-extrabold text-gray-800 mb-2 uppercase tracking-wide">Nombre de la Plantilla</label>
                                        <input type="text" name="name"
                                            class="w-full rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all py-3 px-4 bg-white text-gray-900 font-bold placeholder-gray-400"
                                            value="{{ old('name', $certificate['name'] ?? '') }}"
                                            placeholder="Ej: Certificado Corporativo 2024" required>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                                        <div>
                                            <label class="block text-sm font-extrabold text-gray-800 mb-2 uppercase tracking-wide">Asignar a Curso</label>
                                            <div class="relative">
                                                <select name="course_id"
                                                    class="w-full appearance-none rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all py-3 px-4 bg-white text-gray-900 font-medium text-base">
                                                    <option value="">-- Seleccionar Curso (Opcional) --</option>
                                                    @if(isset($certificate['course_id']) && $certificate['course_id'])
                                                        @php $curActual = \App\Models\Curso::find($certificate['course_id']); @endphp
                                                        @if($curActual)
                                                            <option value="{{ $curActual->cur_id }}" selected>
                                                                {{ $curActual->cur_nombre }}
                                                            </option>
                                                        @endif
                                                    @endif
                                                    @foreach($courses as $curso)
                                                        <option value="{{ $curso->cur_id }}" {{ old('course_id') == $curso->cur_id ? 'selected' : '' }}>
                                                            {{ $curso->cur_nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                                    <i class="fas fa-chevron-down"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Estado por Defecto</label>
                                            <div class="flex items-center mt-2 bg-blue-50 p-3 rounded-xl border border-blue-200">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="is_default" value="1" class="sr-only peer" {{ old('is_default', $certificate['is_default'] ?? false) ? 'checked' : '' }}>
                                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                    <span class="ml-3 text-sm font-bold text-gray-800">Usar como Plantilla por Defecto</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Page Configuration (Added) -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5 pt-4 border-t border-gray-200">
                                        <div>
                                            <label class="block text-sm font-extrabold text-gray-800 mb-2 uppercase tracking-wide">Tamaño de Hoja</label>
                                            <div class="relative">
                                                <select name="page_size" @change="updatePreview"
                                                    class="w-full appearance-none rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all py-3 px-4 bg-white text-gray-900 font-medium text-base">
                                                    <option value="custom" {{ (old('page_size', $certificate['page_size'] ?? 'custom') == 'custom') ? 'selected' : '' }}>Personalizado (Px)</option>
                                                    <option value="letter" {{ (old('page_size', $certificate['page_size'] ?? '') == 'letter') ? 'selected' : '' }}>Carta (Letter)</option>
                                                    <option value="a4" {{ (old('page_size', $certificate['page_size'] ?? '') == 'a4') ? 'selected' : '' }}>A4</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                                    <i class="fas fa-chevron-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-extrabold text-gray-800 mb-2 uppercase tracking-wide">Orientación</label>
                                            <div class="relative">
                                                <select name="orientation" @change="updatePreview"
                                                    class="w-full appearance-none rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all py-3 px-4 bg-white text-gray-900 font-medium text-base">
                                                    <option value="landscape" {{ (old('orientation', $certificate['orientation'] ?? 'landscape') == 'landscape') ? 'selected' : '' }}>Horizontal (Paisaje)</option>
                                                    <option value="portrait" {{ (old('orientation', $certificate['orientation'] ?? '') == 'portrait') ? 'selected' : '' }}>Vertical (Retrato)</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                                    <i class="fas fa-chevron-down"></i>
                                                </div>
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
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Color de Fondo</label>
                                            <div class="flex items-center space-x-2">
                                                <input type="color" name="bg_color" class="h-10 w-12 rounded border border-gray-300 cursor-pointer" value="{{ old('bg_color', $certificate['settings']['bg_color'] ?? '#ffffff') }}">
                                                <input type="text" readonly class="flex-1 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 font-mono px-3 py-2" value="{{ old('bg_color', $certificate['settings']['bg_color'] ?? '#ffffff') }}">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Imagen de Fondo</label>
                                            <input type="file" name="bg_image" accept="image/*" @change="updatePreview"
                                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200"/>
                                        </div>
                                    </div>

                                    <div class="mt-6 bg-white p-4 rounded-xl border border-gray-300 shadow-sm space-y-4">
                                         <h6 class="text-xs font-bold text-gray-500 uppercase">Configuración del QR</h6>
                                         <div>
                                            <label class="relative inline-flex items-center cursor-pointer mb-3">
                                                <input type="checkbox" name="show_qr" value="1" class="sr-only peer" {{ old('show_qr', filter_var($certificate['settings']['show_qr'] ?? false, FILTER_VALIDATE_BOOLEAN)) ? 'checked' : '' }} @change="updatePreview">
                                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                <span class="ml-3 text-sm font-bold text-gray-800">Activar Código QR</span>
                                            </label>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-bold text-gray-700 mb-1">Posición</label>
                                                    <div class="flex flex-col gap-2">
                                                        <label class="inline-flex items-center p-2 border rounded-lg hover:bg-gray-50 cursor-pointer" :class="{'bg-blue-50 border-blue-500': $el.querySelector('input').checked}">
                                                            <input type="radio" name="qr_position" value="left" 
                                                                {{ (old('qr_position', $certificate['settings']['qr_position'] ?? 'left') == 'left') ? 'checked' : '' }}
                                                                class="text-blue-600 focus:ring-blue-500" @change="updatePreview">
                                                            <span class="ml-2 text-sm font-medium">Izquierda</span>
                                                        </label>
                                                        <label class="inline-flex items-center p-2 border rounded-lg hover:bg-gray-50 cursor-pointer" :class="{'bg-blue-50 border-blue-500': $el.querySelector('input').checked}">
                                                            <input type="radio" name="qr_position" value="right"
                                                                {{ (old('qr_position', $certificate['settings']['qr_position'] ?? 'left') == 'right') ? 'checked' : '' }}
                                                                class="text-blue-600 focus:ring-blue-500" @change="updatePreview">
                                                            <span class="ml-2 text-sm font-medium">Derecha</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tamaño</label>
                                                    <input type="range" name="qr_size" min="50" max="200" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" value="{{ old('qr_size', $certificate['settings']['qr_size'] ?? 100) }}" @input="updatePreview">
                                                </div>
                                            </div>
                                         </div>
                                    </div>
                                </div>

                                <!-- CONTENT TAB -->
                                <div x-show="activeTab === 'content'" style="display: none;">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Título Principal</label>
                                            <input type="text" name="title_text" class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 p-3 bg-white font-bold"
                                                value="{{ old('title_text', $certificate['settings']['title_text'] ?? 'CERTIFICADO DE APROBACIÓN') }}">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                             <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1">Color Título</label>
                                                <input type="color" name="title_color" class="h-10 w-full rounded border border-gray-300"
                                                    value="{{ old('title_color', $certificate['settings']['title_color'] ?? '#333333') }}">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1">Tamaño Fuente</label>
                                                <input type="number" name="title_size" class="w-full rounded-lg border border-gray-300 p-2"
                                                    value="{{ old('title_size', $certificate['settings']['title_size'] ?? 40) }}">
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Tipografía Título</label>
                                            <select name="title_font" @change="updatePreview" class="w-full rounded-lg border border-gray-300 p-2 bg-white">
                                                <option value="'Arial', sans-serif" {{ (old('title_font', $certificate['settings']['title_font'] ?? '') == "'Arial', sans-serif") ? 'selected' : '' }}>Arial</option>
                                                <option value="'Helvetica', sans-serif" {{ (old('title_font', $certificate['settings']['title_font'] ?? '') == "'Helvetica', sans-serif") ? 'selected' : '' }}>Helvetica</option>
                                                <option value="'Times New Roman', serif" {{ (old('title_font', $certificate['settings']['title_font'] ?? '') == "'Times New Roman', serif") ? 'selected' : '' }}>Times New Roman</option>
                                                <option value="'Georgia', serif" {{ (old('title_font', $certificate['settings']['title_font'] ?? '') == "'Georgia', serif") ? 'selected' : '' }}>Georgia</option>
                                                <option value="'Courier New', monospace" {{ (old('title_font', $certificate['settings']['title_font'] ?? '') == "'Courier New', monospace") ? 'selected' : '' }}>Courier New</option>
                                                <option value="'Brush Script MT', cursive" {{ (old('title_font', $certificate['settings']['title_font'] ?? '') == "'Brush Script MT', cursive") ? 'selected' : '' }}>Brush Script (Cursiva)</option>
                                            </select>
                                        </div>

                                        <hr class="border-gray-200 my-4">

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Texto Introductorio</label>
                                            <input type="text" name="body_text" class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 p-3 bg-white"
                                                value="{{ old('body_text', $certificate['settings']['body_text'] ?? 'Se otorga el presente reconocimiento a:') }}">
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1">Tipografía Cuerpo</label>
                                                <select name="body_font" @change="updatePreview" class="w-full rounded-lg border border-gray-300 p-2 bg-white">
                                                    <option value="'Arial', sans-serif" {{ (old('body_font', $certificate['settings']['body_font'] ?? '') == "'Arial', sans-serif") ? 'selected' : '' }}>Arial</option>
                                                    <option value="'Helvetica', sans-serif" {{ (old('body_font', $certificate['settings']['body_font'] ?? '') == "'Helvetica', sans-serif") ? 'selected' : '' }}>Helvetica</option>
                                                    <option value="'Times New Roman', serif" {{ (old('body_font', $certificate['settings']['body_font'] ?? '') == "'Times New Roman', serif") ? 'selected' : '' }}>Times New Roman</option>
                                                    <option value="'Georgia', serif" {{ (old('body_font', $certificate['settings']['body_font'] ?? '') == "'Georgia', serif") ? 'selected' : '' }}>Georgia</option>
                                                    <option value="'Courier New', monospace" {{ (old('body_font', $certificate['settings']['body_font'] ?? '') == "'Courier New', monospace") ? 'selected' : '' }}>Courier New</option>
                                                </select>
                                            </div>
                                            <div><!-- Spacer --></div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Texto Secundario</label>
                                            <input type="text" name="secondary_text" class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 p-3 bg-white"
                                                value="{{ old('secondary_text', $certificate['settings']['secondary_text'] ?? 'Por haber aprobado satisfactoriamente el curso:') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- SIGNATURE TAB -->
                                <div x-show="activeTab === 'signature'" style="display: none;">
                                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                                        <label class="block text-sm font-bold text-blue-800 mb-2">Firma Digital</label>
                                         <div class="mb-4">
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nombre / Cargo</label>
                                            <input type="text" name="signature_text" class="w-full rounded-lg border border-blue-200 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 p-3"
                                                value="{{ old('signature_text', $certificate['settings']['signature_text'] ?? 'Director Académico') }}">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Imagen de Firma</label>
                                            <input type="file" name="signature_image" @change="updatePreview"
                                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-100 px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                                <button type="button" @click.prevent="$dispatch('confirm-action', { 
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
                            <h3 class="font-bold text-base uppercase tracking-wider"><i class="fas fa-eye mr-2 text-blue-400"></i> Vista Previa</h3>
                            <span class="text-xs text-blue-200 font-mono" x-show="isLoadingPreview"><i class="fas fa-circle-notch fa-spin mr-1"></i> Renderizando...</span>
                        </div>

                        <div class="relative bg-gray-200 p-6 min-h-[500px] flex items-center justify-center border-b border-gray-300">
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