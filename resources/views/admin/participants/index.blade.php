@extends('layouts.admin')

@section('title', 'Participantes')

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
                    <h1 class="text-2xl font-bold text-slate-800">Participantes</h1>
                    <p class="text-slate-500 text-sm mt-1">Gestión de inscripciones y asistentes</p>
                </div>
            </div>
        </div>

        {{-- Búsqueda con diseño llamativo --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-teal-100">
            <div class="flex items-center mb-4">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-teal-500/30">
                    <i class="fa fa-search text-white"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Buscar Participantes</h3>
                    <p class="text-xs text-slate-500">Encuentra asistentes rápidamente</p>
                </div>
            </div>

            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <i class="fa fa-users absolute left-4 top-1/2 -translate-y-1/2 text-teal-500"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full pl-12 pr-4 py-3.5 bg-gradient-to-r from-teal-50 to-cyan-50 border-2 border-teal-200 rounded-xl text-sm font-medium focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20 focus:bg-white transition-all placeholder-teal-400"
                        placeholder="🔍 Buscar por nombre, login o correo...">
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-teal-600 to-cyan-600 text-white text-sm font-bold rounded-xl hover:from-teal-700 hover:to-cyan-700 shadow-lg shadow-teal-500/30 hover:shadow-xl transition-all">
                        <i class="fa fa-search mr-2"></i> Buscar
                    </button>

                    @if(request('search'))
                        <a href="{{ route('admin.participants.index') }}"
                            class="inline-flex items-center px-5 py-3.5 bg-gradient-to-r from-red-500 to-rose-600 text-white text-sm font-bold rounded-xl hover:from-red-600 hover:to-rose-700 shadow-lg shadow-red-500/30 hover:shadow-xl transition-all">
                            <i class="fa fa-times mr-2"></i> Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Contenido (Tabla real desde BD) --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-slate-100 overflow-x-auto">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Listado de Participantes</h3>

                </div>

                @if(isset($participantes))
                    <div class="text-xs text-slate-500">
                        Total: <span class="font-semibold text-slate-700">{{ $participantes->total() }}</span>
                    </div>
                @endif
            </div>

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-slate-600 text-left">
                        <th class="py-3 pr-4">Login</th>
                        <th class="py-3 pr-4">Nombre</th>
                        <th class="py-3 pr-4">Apellido</th>
                        <th class="py-3 pr-4">Correo</th>
                        <th class="py-3 pr-4">Cargo</th>
                        <th class="py-3 pr-4">Facultad</th>
                        <th class="py-3 pr-4">Departamento</th>
                        <th class="py-3 pr-4">Sede</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($participantes as $p)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 pr-4 font-mono">{{ $p->par_login }}</td>
                            <td class="py-3 pr-4">{{ $p->par_nombre }}</td>
                            <td class="py-3 pr-4">{{ $p->par_apellido }}</td>
                            <td class="py-3 pr-4">{{ $p->par_correo }}</td>
                            <td class="py-3 pr-4">{{ $p->par_cargo ?? '-' }}</td>
                            <td class="py-3 pr-4">{{ $p->par_facultad ?? '-' }}</td>
                            <td class="py-3 pr-4">{{ $p->par_departamento ?? '-' }}</td>
                            <td class="py-3 pr-4">{{ $p->par_sede ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-slate-500">
                                No se encontraron participantes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Paginación --}}
            <div class="mt-6">
                {{ $participantes->links() }}
            </div>
        </div>
    </div>
@endsection