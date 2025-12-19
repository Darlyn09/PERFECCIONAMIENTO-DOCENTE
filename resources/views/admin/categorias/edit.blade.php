@extends('layouts.admin')

@section('title', 'Editar Categoría')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -m-4 p-4 sm:p-6">
        {{-- Header --}}
        <div class="mb-6">
            {{-- Botón Volver --}}
            <a href="{{ route('admin.categorias.create') }}"
                class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-5 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm sm:text-base font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
                <i class="fa fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                <span class="hidden sm:inline">Volver a Categorías</span>
                <span class="sm:hidden">Volver</span>
            </a>

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 flex items-center">
                        <i class="fa fa-edit mr-3 text-indigo-600"></i>
                        Editar Categoría
                    </h1>
                    <p class="text-slate-500 text-sm mt-1">Modifica el nombre de la categoría</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-2xl mx-auto border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-slate-800 font-bold flex items-center text-lg">
                    <i class="fa fa-tag mr-3 text-indigo-500"></i>
                    Información de la Categoría
                </h2>
            </div>

            <div class="p-6 sm:p-8">
                @if(session('success'))
                    <div
                        class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-4 py-3 rounded-r-lg flex items-center">
                        <i class="fa fa-check-circle mr-3 text-emerald-500"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('admin.categorias.update', $categoria->cur_categoria) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-slate-700 text-sm font-semibold mb-2" for="nom_categoria">
                            <i class="fa fa-pencil-alt text-blue-500 mr-2"></i>Nombre de la Categoría
                        </label>
                        <input type="text" name="nom_categoria" id="nom_categoria"
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all @error('nom_categoria') border-red-400 bg-red-50 @enderror"
                            value="{{ old('nom_categoria', $categoria->nom_categoria) }}" required>
                        @error('nom_categoria')
                            <p class="text-red-500 text-xs mt-2 flex items-center">
                                <i class="fa fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Info sobre cursos asociados --}}
                    @php $cursosCount = $categoria->cursos()->count(); @endphp
                    @if($cursosCount > 0)
                        <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-100 flex items-start">
                            <i class="fa fa-info-circle text-blue-500 mt-1 mr-3 text-lg"></i>
                            <div>
                                <p class="text-blue-800 font-semibold text-sm">Cursos Asociados</p>
                                <p class="text-blue-600 text-xs mt-1">
                                    Esta categoría está asignada a <strong>{{ $cursosCount }}</strong> cursos.
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-4 mt-8">
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all">
                            <i class="fa fa-save mr-2"></i> Actualizar Categoría
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection