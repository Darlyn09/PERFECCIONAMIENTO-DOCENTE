@extends('layouts.admin')

@section('title', 'Asignación de Docentes')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('admin.courses.show', $programa->cur_id) }}"
                    class="inline-flex items-center text-slate-500 hover:text-blue-600 font-medium mb-2 transition-colors">
                    <i class="fa fa-arrow-left mr-2"></i> Volver al Curso
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Cuerpo Docente del Programa</h1>
                <p class="text-slate-500">Gestión de profesores asignados a la oferta #{{ $programa->pro_id }} de
                    {{ $programa->curso->cur_nombre }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Columna Izquierda: Listado --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <h2 class="font-bold text-slate-800 flex items-center">
                            <i class="fa fa-users text-blue-institutional mr-2"></i> Relatores Asignados
                        </h2>
                    </div> <span class="bg-blue-50 text-blue-institutional font-bold px-3 py-1 rounded-full text-xs">
                        {{ $programa->relatores->count() }} Docentes
                    </span>
                </div>

                @if($programa->relatores->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($programa->relatores as $relator)
                                @php
                                    $isCertified = !empty($relator->pivot->rr_certificado);
                                @endphp
                                <div
                                    class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50 transition-all group">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <div
                                                class="w-14 h-14 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-500 font-bold text-xl shadow-inner border border-slate-200">
                                                {{ substr($relator->rel_nombre, 0, 1) }}{{ substr($relator->rel_apellido, 0, 1) }}
                                            </div>
                                            @if($isCertified)
                                                <div class="absolute -bottom-1 -right-1 bg-emerald-500 text-white w-6 h-6 rounded-full flex items-center justify-center border-2 border-white shadow-sm"
                                                    title="Certificado">
                                                    <i class="fas fa-check text-[10px]"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-lg">
                                                {{ $relator->rel_nombre }} {{ $relator->rel_apellido }}
                                            </h4>
                                            <p class="text-sm text-slate-500 flex items-center gap-2">
                                                <i class="far fa-envelope text-slate-400"></i> {{ $relator->rel_correo }}
                                            </p>
                                            @if($relator->rel_login === $programa->rel_login)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 mt-1 border border-blue-100">
                                                    RELATOR PRINCIPAL
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 self-end sm:self-center">
                                        {{-- Botón Certificar / Descertificar --}}
                                        <form
                                            action="{{ route('admin.programs.certify_teacher', ['id' => $programa->pro_id, 'relLogin' => $relator->rel_login]) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2 shadow-sm border
                                                            {{ $isCertified
                            ? 'bg-emerald-50 text-emerald-600 border-emerald-200 hover:bg-emerald-100 hover:shadow-emerald-100'
                            : 'bg-white text-slate-600 border-slate-200 hover:border-blue-300 hover:text-blue-600 hover:shadow-blue-50' }}"
                                                title="{{ $isCertified ? 'Certificado el ' . \Carbon\Carbon::parse($relator->pivot->rr_certificado)->format('d/m/Y H:i') : 'Clic para Certificar' }}">
                                                <i class="fas {{ $isCertified ? 'fa-check-circle' : 'fa-certificate' }}"></i>
                                                {{ $isCertified ? 'Certificado' : 'Certificar' }}
                                            </button>
                                        </form>

                                        {{-- Botón Descargar (Solo si está certificado) --}}
                                        @if($isCertified)
                                            <a href="{{ route('admin.relators.certificate', ['id' => $programa->pro_id, 'relLogin' => $relator->rel_login]) }}"
                                                target="_blank"
                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-institutional text-white hover:bg-blue-900 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5"
                                                title="Descargar Certificado PDF">
                                                <i class="fas fa-file-download"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-16 text-center">
                        <div
                            class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-slate-100">
                            <i class="fa fa-user-slash text-3xl text-slate-300"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-lg mb-2">Sin docentes asignados</h3>
                        <p class="text-slate-500 max-w-sm mx-auto">No se han encontrado docentes vinculados a esta sesión.
                            Verifica la asignación en el curso.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Columna Derecha: Información y Extras --}}
        <div class="space-y-6">
            <div
                class="bg-gradient-to-br from-white to-blue-50/50 border border-blue-100 p-6 rounded-2xl shadow-sm relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-blue-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
                </div>
                <div
                    class="absolute -bottom-8 -left-8 w-24 h-24 bg-purple-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
                </div>

                <div class="relative z-10">
                    <h3 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-500"></i> Información
                    </h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Los docentes visualizados aquí corresponden al <strong>Relator Principal</strong> y los
                        <strong>Colaboradores</strong> asignados automáticamente desde la configuración de la sesión.
                    </p>
                    <div class="mt-4 pt-4 border-t border-blue-200/50">
                        <p class="text-xs text-blue-800 font-medium">
                            <i class="fas fa-check text-emerald-500 mr-1"></i> La certificación registra la fecha y hora de
                            emisión.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection