@extends('layouts.admin')

@section('title', 'Evaluaciones')

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
                    <h1 class="text-2xl font-bold text-slate-800">Evaluaciones</h1>
                    <p class="text-slate-500 text-sm mt-1">Gestión de evaluaciones de cursos</p>
                </div>
                <button class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-500/25 hover:shadow-xl hover:from-emerald-600 hover:to-green-700 transition-all">
                    <i class="fa fa-plus mr-2"></i> Nueva Evaluación
                </button>
            </div>
        </div>

        {{-- Filtros con diseño llamativo --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-emerald-100">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-emerald-500/30">
                    <i class="fa fa-search text-white"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Buscar y Filtrar Evaluaciones</h3>
                    <p class="text-xs text-slate-500">Encuentra evaluaciones rápidamente</p>
                </div>
            </div>
            <form method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1 relative">
                    <i class="fa fa-clipboard-check absolute left-4 top-1/2 -translate-y-1/2 text-emerald-500"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-full pl-12 pr-4 py-3.5 bg-gradient-to-r from-emerald-50 to-green-50 border-2 border-emerald-200 rounded-xl text-sm font-medium focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 focus:bg-white transition-all placeholder-emerald-400"
                           placeholder="🔍 Buscar evaluación por nombre...">
                </div>
                <div class="w-full lg:w-52">
                    <div class="relative">
                        <i class="fa fa-graduation-cap absolute left-4 top-1/2 -translate-y-1/2 text-blue-500 z-10"></i>
                        <select name="curso" class="w-full pl-12 pr-4 py-3.5 bg-blue-50 border-2 border-blue-200 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all appearance-none cursor-pointer">
                            <option value="">📚 Todos los cursos</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white text-sm font-bold rounded-xl hover:from-emerald-600 hover:to-green-700 shadow-lg shadow-emerald-500/30 hover:shadow-xl transition-all">
                        <i class="fa fa-filter mr-2"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['search', 'curso']))
                        <a href="{{ route('admin.assessments.index') }}" class="inline-flex items-center px-5 py-3.5 bg-gradient-to-r from-red-500 to-rose-600 text-white text-sm font-bold rounded-xl hover:from-red-600 hover:to-rose-700 shadow-lg shadow-red-500/30 hover:shadow-xl transition-all">
                            <i class="fa fa-times mr-2"></i> Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Contenido --}}
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-slate-100">
            <div class="flex flex-col items-center justify-center py-12">
                <div class="w-20 h-20 bg-gradient-to-br from-emerald-100 to-green-100 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa fa-clipboard-list text-3xl text-emerald-500"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-700 mb-2">Gestión de Evaluaciones</h3>
                <p class="text-slate-500 text-center max-w-md">
                    Configure y revise las evaluaciones de los cursos y talleres.
                </p>
            </div>
        </div>
    </div>
@endsection
