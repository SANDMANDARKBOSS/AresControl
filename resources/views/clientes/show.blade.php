@extends('layouts.admin')

@section('title', 'Perfil del Cliente - Ares Gym')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .glass-card {
        background: rgba(31, 31, 31, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .metric-badge {
        background: linear-gradient(135deg, rgba(227,27,35,0.1) 0%, rgba(227,27,35,0.05) 100%);
        border: 1px solid rgba(227,27,35,0.2);
    }
    
    /* Print Styles */
    @media print {
        body {
            background-color: white !important;
            color: black !important;
        }
        
        /* Hide UI elements not needed in PDF */
        #sidebar, .top-nav, aside, header, nav, .print\:hidden, .material-symbols-outlined {
            display: none !important;
        }
        
        /* Reconfigure layout */
        main, #main-content, .content-wrapper, .flex-col.w-full {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .glass-card, .bg-surface-container, .bg-surface-container-low, .bg-primary {
            background: transparent !important;
            border: 1px solid #ccc !important;
            box-shadow: none !important;
            color: black !important;
            backdrop-filter: none !important;
        }
        
        .text-on-surface, .text-white, .text-on-primary, .text-on-surface-variant {
            color: black !important;
        }
        
        .text-primary {
            color: #E31B23 !important;
        }
        
        .bg-primary {
            border: 2px solid #E31B23 !important;
            color: black !important;
        }
        
        /* Force background graphics to print */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .grid {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 20px !important;
        }
        
        .lg\:col-span-4, .lg\:col-span-8 {
            width: 100% !important;
        }
        
        .gap-8 {
            gap: 1rem !important;
        }
        
        canvas {
            max-width: 100% !important;
            height: auto !important;
        }
    }
</style>
@endpush

@section('content')
<div class="flex flex-col w-full animate-on-load gap-8 pb-12">
    
    <!-- Print Only Header -->
    <div class="hidden print:flex flex-col items-center justify-center border-b-2 border-primary pb-6 mb-4">
        <h1 class="text-[40px] font-black text-primary uppercase tracking-tighter" style="font-family: 'Montserrat', sans-serif;">ARES<span class="text-on-surface">GYM</span></h1>
        <p class="font-etiqueta-bold text-[12px] uppercase tracking-widest mt-2 text-on-surface-variant">Ficha Técnica de Atleta</p>
    </div>

    <!-- Top Bar Navigation -->
    <div class="flex items-center justify-between print:hidden">
        <a href="{{ route('clientes.index') }}" class="font-etiqueta-bold text-etiqueta-sm text-on-surface-variant uppercase tracking-[0.2em] hover:text-primary transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span> Volver al Roster
        </a>
        <div class="flex flex-wrap gap-3 items-center">
            @if($client->user)
                <form method="POST" action="{{ route('clientes.toggleStatus', $client->id) }}" onsubmit="return confirm('{{ $client->user->status ? "¿Estás seguro de que deseas dar de baja a este atleta?" : "¿Deseas reactivar a este atleta?" }}');">
                    @csrf
                    @if($client->user->status)
                        <button type="submit" class="bg-error/10 text-error border border-error/30 px-5 py-2 rounded-xl font-etiqueta-bold text-[12px] uppercase tracking-wide hover:bg-error/20 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">person_off</span> Dar de Baja
                        </button>
                    @else
                        <button type="submit" class="bg-green-500/10 text-green-500 border border-green-500/30 px-5 py-2 rounded-xl font-etiqueta-bold text-[12px] uppercase tracking-wide hover:bg-green-500/20 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">person_add</span> Reactivar Atleta
                        </button>
                    @endif
                </form>
            @endif
            <button onclick="window.print()" class="bg-surface-container-high text-on-surface border border-outline-variant/20 px-5 py-2 rounded-xl font-etiqueta-bold text-[12px] uppercase tracking-wide hover:bg-surface-container-highest transition-all flex items-center gap-2 print:hidden">
                <span class="material-symbols-outlined text-[18px]">print</span> Imprimir Ficha
            </button>
            <a href="{{ route('clientes.edit', $client->id) }}" class="bg-primary/10 text-primary border border-primary/20 px-5 py-2 rounded-xl font-etiqueta-bold text-[12px] uppercase tracking-wide hover:bg-primary/20 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">edit</span> Editar Perfil
            </a>
        </div>
    </div>

    <!-- Main Profile Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Column (Identity & Status) -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            
            <!-- Avatar & Basic Info Card -->
            <div class="glass-card rounded-[24px] p-8 flex flex-col items-center relative overflow-hidden shadow-xl border-t border-t-primary/20">
                <div class="absolute top-0 w-full h-32 bg-gradient-to-b from-primary/10 to-transparent pointer-events-none"></div>
                
                <div class="relative w-32 h-32 mb-6">
                    <img src="{{ $client->gender === 'F' ? asset('images/avatarF.png') : asset('images/avatarM.jpeg') }}" alt="{{ $client->name }}" class="w-full h-full rounded-full border-4 border-surface shadow-lg z-10 relative object-cover">
                    @php $isActive = $client->memberships->first() && $client->memberships->first()->status == 'Activa'; @endphp
                    <div class="absolute bottom-1 right-1 w-6 h-6 {{ $isActive ? 'bg-[#4ade80]' : 'bg-error' }} rounded-full border-2 border-surface z-20 flex items-center justify-center" title="{{ $isActive ? 'Activo' : 'Inactivo' }}">
                        <span class="w-2 h-2 bg-background rounded-full"></span>
                    </div>
                </div>
                
                <h1 class="font-titular-xl text-[32px] text-on-surface text-center leading-none mb-2" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">{{ $client->name }} {{ $client->last_name }}</h1>
                <p class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest mb-6">ID: {{ $client->id_card }}</p>
                
                <div class="w-full flex flex-col gap-3">
                    <div class="flex items-center gap-4 bg-surface-container-low p-3 rounded-xl border border-outline-variant/10">
                        <span class="material-symbols-outlined text-primary">call</span>
                        <div>
                            <p class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-wider">Teléfono</p>
                            <p class="font-body-md text-on-surface text-sm">{{ $client->phone }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 bg-surface-container-low p-3 rounded-xl border border-outline-variant/10">
                        <span class="material-symbols-outlined text-primary">mail</span>
                        <div>
                            <p class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-wider">Correo</p>
                            <p class="font-body-md text-on-surface text-sm">{{ $client->user ? $client->user->email : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 bg-surface-container-low p-3 rounded-xl border border-outline-variant/10">
                        <span class="material-symbols-outlined text-primary">calendar_month</span>
                        <div>
                            <p class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-wider">Fecha Nacimiento</p>
                            <p class="font-body-md text-on-surface">{{ \Carbon\Carbon::parse($client->birth_date)->format('d M, Y') }} ({{ \Carbon\Carbon::parse($client->birth_date)->age }} años)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Adaptation Period Widget -->
            @php
                $entryDate = \Carbon\Carbon::parse($client->entry_date ?? $client->created_at)->startOfDay();
                $today = \Carbon\Carbon::now()->startOfDay();
                $diffDays = $entryDate->diffInDays($today, false);
                
                // Periodo de adaptación de 3 días (Día de ingreso = Día 1):
                // diffDays <= 0 -> Día 1 de 3 (En Adaptación)
                // diffDays == 1 -> Día 2 de 3 (En Adaptación)
                // diffDays >= 2 -> Días 1, 2 y 3 cumplidos -> Completada / Apto Medición (Tope 3/3 días, 100%)
                if ($diffDays <= 0) {
                    $currentDay = 1;
                    $isInAdaptation = true;
                    $progressPercentage = 33.3;
                } elseif ($diffDays == 1) {
                    $currentDay = 2;
                    $isInAdaptation = true;
                    $progressPercentage = 66.6;
                } else {
                    $currentDay = 3;
                    $isInAdaptation = false;
                    $progressPercentage = 100;
                }
            @endphp

            <div class="glass-card rounded-[24px] p-6 relative overflow-hidden shadow-lg {{ $isInAdaptation ? 'border-t-primary' : 'border-t-green-500' }}" style="border-top-width: 2px;">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-etiqueta-bold text-[12px] uppercase tracking-widest text-on-surface-variant">Fase Inicial</h3>
                    @if($isInAdaptation)
                        <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider animate-pulse flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">schedule</span> Adaptaci&oacute;n (D&iacute;a {{ $currentDay }}/3)
                        </span>
                    @else
                        <span class="bg-green-500/10 text-green-500 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">check_circle</span> Completada (3/3 D&iacute;as)
                        </span>
                    @endif
                </div>
                
                <h2 class="font-titular-md text-[18px] text-on-surface mb-2">Periodo de Adaptaci&oacute;n (3 D&iacute;as)</h2>
                <p class="font-body-md text-[13px] text-on-surface-variant mb-4">
                    @if($isInAdaptation)
                        El atleta se encuentra en el <strong>D&iacute;a {{ $currentDay }} de 3</strong> de adaptaci&oacute;n inicial. Las medidas y planes alimenticios se habilitar&aacute;n autom&aacute;ticamente tras cumplir los 3 d&iacute;as.
                    @else
                        Fase de adaptaci&oacute;n superada (3 d&iacute;as cumplidos). El atleta ya se encuentra <strong>apto y habilitado</strong> para la toma de medidas corporales y entrega de planes nutricionales.
                    @endif
                </p>

                <!-- Timeline/Progress Bar -->
                <div class="relative w-full h-2 bg-surface-container-highest rounded-full overflow-hidden mb-2">
                    <div class="absolute top-0 left-0 h-full {{ $isInAdaptation ? 'bg-primary' : 'bg-green-500' }} transition-all duration-1000" style="width: {{ $progressPercentage }}%"></div>
                </div>
                
                <div class="flex justify-between items-center font-etiqueta-bold text-[10px] uppercase tracking-widest text-on-surface-variant">
                    <span>D&iacute;a 1</span>
                    @if($isInAdaptation)
                        <span class="text-primary font-bold">D&iacute;a {{ $currentDay }} / 3</span>
                    @else
                        <span class="text-green-500 font-bold">Apto &bull; 3 / 3 D&iacute;as</span>
                    @endif
                </div>

                <!-- Servicios Habilitados (Informativo) -->
                <div class="mt-5 pt-4 border-t border-outline-variant/10 flex flex-col gap-2.5">
                    @if(!$isInAdaptation)
                        <div class="flex items-start gap-2.5 text-xs font-body-md text-[#4ade80] bg-[#4ade80]/10 border border-[#4ade80]/20 p-3 rounded-xl">
                            <span class="material-symbols-outlined text-[18px] shrink-0 mt-0.5">verified</span>
                            <div class="flex flex-col">
                                <p class="font-etiqueta-bold text-[11px] uppercase tracking-wider text-[#4ade80]">Servicios Habilitados</p>
                                <p class="font-body-md text-[12px] text-on-surface-variant mt-0.5">El cliente ha pasado el tiempo de adaptación por lo que puede acceder a estos servicios:</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-surface-container-low border border-outline-variant/15 py-2.5 px-3 rounded-xl font-etiqueta-bold text-[11px] uppercase tracking-wider text-on-surface flex items-center justify-center gap-1.5 shadow-sm text-center select-none">
                                <span class="material-symbols-outlined text-[16px] text-primary">straighten</span> Toma de Medidas
                            </div>
                            <div class="bg-surface-container-low border border-outline-variant/15 py-2.5 px-3 rounded-xl font-etiqueta-bold text-[11px] uppercase tracking-wider text-on-surface flex items-center justify-center gap-1.5 shadow-sm text-center select-none">
                                <span class="material-symbols-outlined text-[16px] text-primary">restaurant</span> Plan Nutricional
                            </div>
                        </div>
                    @else
                        <div class="flex items-start gap-2.5 text-xs font-body-md text-primary bg-primary/10 border border-primary/20 p-3 rounded-xl">
                            <span class="material-symbols-outlined text-[18px] shrink-0 mt-0.5">schedule</span>
                            <div class="flex flex-col">
                                <p class="font-etiqueta-bold text-[11px] uppercase tracking-wider text-primary">Servicios en Espera</p>
                                <p class="font-body-md text-[12px] text-on-surface-variant mt-0.5">Al culminar los 3 días de adaptación, el cliente podrá acceder a:</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-surface-container-lowest text-on-surface-variant/40 border border-outline-variant/10 py-2.5 px-3 rounded-xl font-etiqueta-bold text-[11px] uppercase tracking-wider flex items-center justify-center gap-1.5 text-center select-none">
                                <span class="material-symbols-outlined text-[14px]">lock</span> Toma de Medidas
                            </div>
                            <div class="bg-surface-container-lowest text-on-surface-variant/40 border border-outline-variant/10 py-2.5 px-3 rounded-xl font-etiqueta-bold text-[11px] uppercase tracking-wider flex items-center justify-center gap-1.5 text-center select-none">
                                <span class="material-symbols-outlined text-[14px]">lock</span> Plan Nutricional
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Active Membership Summary Card -->
            <div class="bg-primary border border-primary-container rounded-[24px] p-6 shadow-xl relative overflow-hidden text-on-primary">
                <!-- Geometric Accent -->
                <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                <div class="absolute top-4 right-4">
                    <span class="material-symbols-outlined text-white/30 text-[48px]">card_membership</span>
                </div>
                
                <h3 class="font-etiqueta-bold text-[12px] uppercase tracking-widest text-white/80 mb-1">Membresía Activa</h3>
                <h2 class="font-titular-md text-[24px] font-bold mb-6">{{ $client->memberships->first() ? $client->memberships->first()->plan->name : 'Sin Plan' }}</h2>
                
                @if($client->memberships->first())
                @php
                    $m = $client->memberships->first();
                    $start = \Carbon\Carbon::parse($m->start_date);
                    $end = \Carbon\Carbon::parse($m->end_date);
                    $now = \Carbon\Carbon::now();
                    $totalDays = $start->diffInDays($end) ?: 1;
                    $daysPassed = $start->diffInDays($now);
                    $daysLeft = $now->diffInDays($end, false);
                    $percent = min(100, max(0, ($daysPassed / $totalDays) * 100));
                @endphp
                <div class="flex flex-col gap-2 relative z-10">
                    <div class="flex justify-between font-body-md text-sm">
                        <span>{{ $daysLeft >= 0 ? 'Vence en:' : 'Vencido hace:' }}</span>
                        <span class="font-bold">{{ abs((int)$daysLeft) }} días</span>
                    </div>
                    <!-- Progress Bar -->
                    <div class="w-full bg-black/20 rounded-full h-2 overflow-hidden">
                        <div class="bg-white h-2 rounded-full shadow-[0_0_10px_rgba(255,255,255,0.5)]" style="width: {{ $percent }}%"></div>
                    </div>
                    <div class="flex justify-between font-etiqueta-sm text-[10px] text-white/60 mt-1 uppercase">
                        <span>Inicio: {{ $start->format('d M') }}</span>
                        <span>Fin: {{ $end->format('d M') }}</span>
                    </div>
                </div>
                @else
                <div class="flex flex-col gap-2 relative z-10">
                    <p class="font-body-md text-sm text-white/80">Este atleta no tiene una membresía activa o su plan ha finalizado.</p>
                </div>
                @endif
                
                <button class="w-full mt-6 bg-white text-primary font-titular-md text-[14px] uppercase tracking-wider py-3 rounded-xl hover:bg-surface-container-lowest transition-colors shadow-lg">
                    Renovar Plan
                </button>
            </div>
        </div>

        <!-- Right Column (Data & Tabs) -->
        <div class="lg:col-span-8 flex flex-col gap-6">
            
            @php
                // Protocol is active by default once adaptation is passed (unless athlete explicitly selected Solo Entrenamiento)
                $protocolStatus = ($client->accepts_measurements === false) ? false : true;
            @endphp

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-surface-container-low border border-outline-variant/10 rounded-xl p-4 flex flex-col">
                    <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest">Peso Actual</span>
                    <span class="font-titular-xl text-[28px] text-on-surface font-bold mt-1">{{ $latestMeasurement ? $latestMeasurement->weight : '--' }} <span class="text-[14px] text-primary">kg</span></span>
                </div>
                <div class="bg-surface-container-low border border-outline-variant/10 rounded-xl p-4 flex flex-col">
                    <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest">% Grasa</span>
                    <span class="font-titular-xl text-[28px] text-on-surface font-bold mt-1">{{ $latestMeasurement && $latestMeasurement->fat ? $latestMeasurement->fat : '--' }} <span class="text-[14px] text-primary">%</span></span>
                </div>
                <div class="bg-surface-container-low border border-outline-variant/10 rounded-xl p-4 flex flex-col">
                    <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest">Masa Muscular</span>
                    <span class="font-titular-xl text-[28px] text-on-surface font-bold mt-1">{{ $latestMeasurement && $latestMeasurement->muscle ? $latestMeasurement->muscle : '--' }} <span class="text-[14px] text-primary">kg</span></span>
                </div>
                <div class="bg-surface-container-low border border-outline-variant/10 rounded-xl p-4 flex flex-col">
                    <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest">Asistencias Mes</span>
                    <span class="font-titular-xl text-[28px] text-on-surface font-bold mt-1">{{ $client->attendances()->whereMonth('created_at', \Carbon\Carbon::now()->month)->count() }} <span class="text-[14px] text-primary">días</span></span>
                </div>
            </div>

            <!-- BLOQUE: DECISIÓN DEL ATLETA (MEDICIÓN & NUTRICIÓN) -->
            @if(!$isInAdaptation)
                <div class="glass-card rounded-[24px] p-6 shadow-xl border border-outline-variant/10 relative overflow-hidden">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl {{ $protocolStatus === true ? 'bg-green-500/10 text-green-500 border border-green-500/20' : 'bg-surface-container-high text-on-surface-variant border border-outline-variant/20' }} flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[24px]">
                                    {{ $protocolStatus === true ? 'verified_user' : 'do_not_disturb_on' }}
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <span class="font-etiqueta-bold text-[10px] uppercase tracking-widest {{ $protocolStatus === true ? 'text-green-500' : 'text-on-surface-variant' }}">
                                        {{ $protocolStatus === true ? 'Protocolo Habilitado & Activo' : 'Solo Entrenamiento' }}
                                    </span>
                                    <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                                    <span class="font-body-md text-xs text-on-surface-variant">Fase de Adaptación Superada</span>
                                </div>
                                <h3 class="font-titular-md text-[18px] text-on-surface mt-0.5">
                                    {{ $protocolStatus === true ? 'Seguimiento Antropométrico & Plan Alimentario Vinculado' : 'Entrenamiento sin Seguimiento de Medidas ni Nutrición' }}
                                </h3>
                                <p class="font-body-md text-[13px] text-on-surface-variant mt-1 max-w-2xl">
                                    {{ $protocolStatus === true ? 'El atleta ha superado el periodo de adaptación. Cuenta con acceso a la toma de medidas corporales y a su plan de alimentación para registrar la evolución en los gráficos.' : 'El atleta ha optado por la modalidad de solo entrenamiento sin tomas de medidas ni nutrición. Puede activar este protocolo en cualquier momento.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Decision Action Buttons -->
                        <div class="flex items-center gap-3 w-full md:w-auto shrink-0 justify-end mt-2 md:mt-0">
                            @if($protocolStatus === true)
                                <form action="{{ route('clientes.toggleMeasurements', $client->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="accepts" value="0">
                                    <button type="submit" onclick="return confirm('¿Desea cambiar la modalidad del atleta a Solo Entrenamiento (sin medidas ni nutrición)?')" class="bg-surface-container hover:bg-surface-container-high text-on-surface-variant border border-outline-variant/20 px-4 py-2 rounded-xl font-etiqueta-bold text-[11px] uppercase tracking-wider transition-all flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">swap_horiz</span> Cambiar a Solo Entrenamiento
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('clientes.toggleMeasurements', $client->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="accepts" value="1">
                                    <button type="submit" class="bg-primary text-on-primary hover:bg-[#ff2a35] px-5 py-2.5 rounded-xl font-titular-md text-[12px] uppercase tracking-wider transition-all flex items-center gap-2 shadow-[0_0_15px_rgba(227,27,35,0.3)]">
                                        <span class="material-symbols-outlined text-[16px]">add_task</span> Activar Medidas + Nutrición
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- GRID 2 COLUMNAS: DETALLE DE MEDIDAS & DETALLE DE NUTRICION -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- 1. DETALLE DE TOMA DE MEDIDAS -->
                <div class="glass-card rounded-[24px] p-6 shadow-lg border border-outline-variant/10 flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center border border-primary/20">
                                <span class="material-symbols-outlined text-[20px]">straighten</span>
                            </div>
                            <div>
                                <h3 class="font-titular-md text-[16px] text-on-surface leading-tight">Toma de Medidas</h3>
                                <p class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-wider">Antropometría Corporal</p>
                            </div>
                        </div>
                        @if($protocolStatus === true && !$isInAdaptation)
                            <span class="bg-[#4ade80]/10 text-[#4ade80] px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Habilitado</span>
                        @elseif($isInAdaptation)
                            <span class="bg-primary/10 text-primary px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Adaptación</span>
                        @else
                            <span class="bg-surface-container-high text-on-surface-variant px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">No Activo</span>
                        @endif
                    </div>

                    @if($protocolStatus === true && !$isInAdaptation)
                        @if($latestMeasurement)
                            <div class="grid grid-cols-3 gap-2 my-2 bg-surface-container-low p-3.5 rounded-xl border border-outline-variant/10 text-center">
                                <div class="flex flex-col">
                                    <span class="font-etiqueta-bold text-[9px] text-on-surface-variant uppercase">Pecho</span>
                                    <span class="font-titular-md text-[15px] text-on-surface font-bold">{{ $latestMeasurement->chest ? $latestMeasurement->chest . ' cm' : '--' }}</span>
                                </div>
                                <div class="flex flex-col border-x border-outline-variant/10">
                                    <span class="font-etiqueta-bold text-[9px] text-on-surface-variant uppercase">Cintura</span>
                                    <span class="font-titular-md text-[15px] text-on-surface font-bold">{{ $latestMeasurement->waist ? $latestMeasurement->waist . ' cm' : '--' }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-etiqueta-bold text-[9px] text-on-surface-variant uppercase">Cadera</span>
                                    <span class="font-titular-md text-[15px] text-on-surface font-bold">{{ $latestMeasurement->hip ? $latestMeasurement->hip . ' cm' : '--' }}</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center text-xs font-body-md text-on-surface-variant mb-4 px-1">
                                <span>Bíceps: <strong>{{ $latestMeasurement->left_bicep ?? '--' }} / {{ $latestMeasurement->right_bicep ?? '--' }} cm</strong></span>
                                <span>Muslos: <strong>{{ $latestMeasurement->left_leg ?? '--' }} / {{ $latestMeasurement->right_leg ?? '--' }} cm</strong></span>
                            </div>
                        @else
                            <div class="py-4 my-2 text-center bg-surface-container-low rounded-xl border border-dashed border-outline-variant/20">
                                <p class="font-body-md text-xs text-on-surface-variant">Protocolo activo. Aún no se ha registrado la primera toma antropométrica.</p>
                            </div>
                        @endif

                        <div class="flex items-center justify-between pt-3 border-t border-outline-variant/10 mt-2">
                            <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-wider">
                                {{ $latestMeasurement ? 'Última: ' . \Carbon\Carbon::parse($latestMeasurement->date)->format('d M, Y') : 'Pendiente' }}
                            </span>
                            <a href="{{ route('medidas') }}" class="bg-primary hover:bg-[#ff2a35] text-on-primary px-4 py-2 rounded-lg font-etiqueta-bold text-[11px] uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-[14px]">add</span> Registrar Medidas
                            </a>
                        </div>
                    @else
                        <div class="py-6 my-2 text-center bg-surface-container-low/50 rounded-xl border border-outline-variant/10 flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-[28px] text-on-surface-variant/40 mb-1">
                                {{ $isInAdaptation ? 'lock' : 'do_not_disturb' }}
                            </span>
                            <p class="font-body-md text-xs text-on-surface-variant max-w-xs">
                                {{ $isInAdaptation ? 'Módulo bloqueado durante los 3 días de adaptación inicial.' : 'El atleta ha optado por no realizar tomas antropométricas.' }}
                            </p>
                        </div>
                        <div class="pt-3 border-t border-outline-variant/10 mt-2 flex justify-end">
                            <button disabled class="bg-surface-container-high/40 text-on-surface-variant/40 px-4 py-2 rounded-lg font-etiqueta-bold text-[11px] uppercase tracking-wider cursor-not-allowed flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">lock</span> Bloqueado
                            </button>
                        </div>
                    @endif
                </div>

                <!-- 2. DETALLE DE PLAN NUTRICIONAL -->
                <div class="glass-card rounded-[24px] p-6 shadow-lg border border-outline-variant/10 flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-lg bg-green-500/10 text-green-500 flex items-center justify-center border border-green-500/20">
                                <span class="material-symbols-outlined text-[20px]">restaurant</span>
                            </div>
                            <div>
                                <h3 class="font-titular-md text-[16px] text-on-surface leading-tight">Plan Nutricional</h3>
                                <p class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-wider">Alimentación Vinculada</p>
                            </div>
                        </div>
                        @if($protocolStatus === true && !$isInAdaptation)
                            <span class="bg-[#4ade80]/10 text-[#4ade80] px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Obligatorio Activo</span>
                        @elseif($isInAdaptation)
                            <span class="bg-primary/10 text-primary px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Adaptación</span>
                        @else
                            <span class="bg-surface-container-high text-on-surface-variant px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">No Activo</span>
                        @endif
                    </div>

                    @if($protocolStatus === true && !$isInAdaptation)
                        @if($latestNutritionalPlan)
                            <div class="bg-surface-container-low p-3.5 rounded-xl border border-outline-variant/10 my-2 flex flex-col gap-1.5">
                                <div class="flex justify-between items-center">
                                    <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase">Periodo del Plan</span>
                                    <span class="font-body-md text-xs text-on-surface font-bold">{{ \Carbon\Carbon::parse($latestNutritionalPlan->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($latestNutritionalPlan->end_date)->format('d M, Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase">Comidas Asignadas</span>
                                    <span class="font-body-md text-xs text-primary font-bold">{{ $latestNutritionalPlan->meals->count() }} tiempos de comida</span>
                                </div>
                                <p class="font-body-md text-[11px] text-on-surface-variant italic truncate mt-1">"{{ $latestNutritionalPlan->note ?? 'Plan de requerimientos y macronutrientes personalizado.' }}"</p>
                            </div>
                        @else
                            <div class="py-4 my-2 text-center bg-surface-container-low rounded-xl border border-dashed border-outline-variant/20">
                                <p class="font-body-md text-xs text-on-surface-variant">Acceso habilitado por toma de medidas. Asigne la pauta de comidas para correlacionar los resultados.</p>
                            </div>
                        @endif

                        <div class="flex items-center justify-between pt-3 border-t border-outline-variant/10 mt-2">
                            <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-wider">
                                {{ $latestNutritionalPlan ? 'Actualizado' : 'Pendiente' }}
                            </span>
                            <a href="{{ route('nutricion') }}" class="bg-surface-container-high hover:bg-surface-container-highest text-on-surface hover:text-primary border border-outline-variant/20 px-4 py-2 rounded-lg font-etiqueta-bold text-[11px] uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-[14px] text-primary">menu_book</span> Gestionar Dieta
                            </a>
                        </div>
                    @else
                        <div class="py-6 my-2 text-center bg-surface-container-low/50 rounded-xl border border-outline-variant/10 flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-[28px] text-on-surface-variant/40 mb-1">
                                {{ $isInAdaptation ? 'lock' : 'do_not_disturb' }}
                            </span>
                            <p class="font-body-md text-xs text-on-surface-variant max-w-xs">
                                {{ $isInAdaptation ? 'Módulo bloqueado durante los 3 días de adaptación inicial.' : 'Sin acceso a plan alimentario al no requerir seguimiento de medidas.' }}
                            </p>
                        </div>
                        <div class="pt-3 border-t border-outline-variant/10 mt-2 flex justify-end">
                            <button disabled class="bg-surface-container-high/40 text-on-surface-variant/40 px-4 py-2 rounded-lg font-etiqueta-bold text-[11px] uppercase tracking-wider cursor-not-allowed flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">lock</span> Bloqueado
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Evolución Gráfica (Chart.js) -->
            <div class="glass-card rounded-[24px] p-6 shadow-lg border border-outline-variant/10 flex flex-col">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="font-titular-md text-[20px] text-on-surface flex items-center gap-2" style="font-family: 'Montserrat', sans-serif;">
                            <span class="material-symbols-outlined text-primary">monitoring</span> Evolución Antropométrica
                        </h2>
                        <p class="font-body-md text-xs text-on-surface-variant mt-0.5">Curva de progreso según medidas y plan alimentario vinculado</p>
                    </div>

                    <!-- Metric Toggle Buttons -->
                    <div class="flex items-center gap-1.5 bg-surface-container-low p-1 rounded-xl border border-outline-variant/10">
                        <button type="button" onclick="switchChartMetric('weight', this)" class="metric-btn active px-3 py-1.5 rounded-lg font-etiqueta-bold text-[10px] uppercase tracking-wider transition-all bg-primary text-on-primary shadow-sm">
                            Peso (kg)
                        </button>
                        <button type="button" onclick="switchChartMetric('fat', this)" class="metric-btn px-3 py-1.5 rounded-lg font-etiqueta-bold text-[10px] uppercase tracking-wider transition-all text-on-surface-variant hover:text-on-surface">
                            % Grasa
                        </button>
                        <button type="button" onclick="switchChartMetric('muscle', this)" class="metric-btn px-3 py-1.5 rounded-lg font-etiqueta-bold text-[10px] uppercase tracking-wider transition-all text-on-surface-variant hover:text-on-surface">
                            Masa Muscular
                        </button>
                    </div>
                </div>

                <div class="w-full h-[250px] relative">
                    @if($protocolStatus === true && count(json_decode($chartWeights ?? '[]')) > 0)
                        <canvas id="evolutionChart"></canvas>
                    @elseif($protocolStatus === false)
                        <div class="w-full h-full flex flex-col items-center justify-center text-on-surface-variant border-2 border-dashed border-outline-variant/20 rounded-xl p-6 text-center">
                            <span class="material-symbols-outlined text-[36px] mb-2 opacity-40">visibility_off</span>
                            <p class="font-titular-md text-sm uppercase tracking-wider text-on-surface">Seguimiento Desactivado</p>
                            <p class="font-body-md text-xs text-on-surface-variant mt-1 max-w-sm">El cliente eligió la modalidad de solo entrenamiento sin toma de medidas ni plan alimentario.</p>
                        </div>
                    @elseif($isInAdaptation)
                        <div class="w-full h-full flex flex-col items-center justify-center text-on-surface-variant border-2 border-dashed border-outline-variant/20 rounded-xl p-6 text-center">
                            <span class="material-symbols-outlined text-[36px] mb-2 text-primary opacity-60">schedule</span>
                            <p class="font-titular-md text-sm uppercase tracking-wider text-on-surface">En Periodo de Adaptación (Día {{ $currentDay }}/3)</p>
                            <p class="font-body-md text-xs text-on-surface-variant mt-1 max-w-sm">Los gráficos se habilitarán una vez cumplidos los 3 días y confirmado el protocolo de medidas y nutrición.</p>
                        </div>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-on-surface-variant border-2 border-dashed border-outline-variant/20 rounded-xl p-6 text-center">
                            <span class="material-symbols-outlined text-[36px] mb-2 opacity-50">query_stats</span>
                            <p class="font-etiqueta-bold text-sm uppercase tracking-widest opacity-60">Sin datos registrados</p>
                            <p class="font-body-md text-xs text-on-surface-variant mt-1">Registre la primera toma de medidas para iniciar la visualización de la curva de evolución.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Historial de Pagos -->
            <div class="glass-card rounded-[24px] p-6 shadow-lg border border-outline-variant/10 flex flex-col gap-4">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="font-titular-md text-[20px] text-on-surface flex items-center gap-2" style="font-family: 'Montserrat', sans-serif;">
                        <span class="material-symbols-outlined text-[#4ade80]">receipt_long</span> Historial de Pagos
                    </h2>
                </div>
                
                <div class="flex flex-col gap-3 mt-2">
                    @forelse ($recentPayments as $payment)
                        <div class="flex justify-between items-center bg-surface-container-low hover:bg-surface-container transition-colors p-4 rounded-xl border border-outline-variant/5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center text-[#4ade80]">
                                    <span class="material-symbols-outlined">check_circle</span>
                                </div>
                                <div>
                                    <p class="font-body-md text-on-surface text-[15px] font-bold">{{ $payment->membership->plan->name ?? 'Membresía' }}</p>
                                    <p class="font-etiqueta-bold text-on-surface-variant tracking-wider uppercase text-[10px] mt-0.5">{{ \Carbon\Carbon::parse($payment->created_at)->format('d M, Y') }} • {{ $payment->paymentMethod->name ?? 'Múltiple' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="font-titular-md text-on-surface text-[18px]">${{ number_format($payment->amount, 2) }}</span>
                                <a href="{{ route('clientes.invoice', ['client' => $client->id, 'payment_id' => $payment->id]) }}" target="_blank" class="text-on-surface-variant hover:text-primary transition-colors" title="Ver / Imprimir Recibo">
                                    <span class="material-symbols-outlined">receipt_long</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-on-surface-variant font-etiqueta-bold uppercase tracking-widest bg-surface-container-lowest rounded-xl border border-outline-variant/10">
                            No hay pagos registrados.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let evolutionChartInstance = null;

    const chartLabels = {!! $chartLabels ?? '[]' !!};
    const datasetWeights = {!! $chartWeights ?? '[]' !!};
    const datasetFats = {!! $chartFats ?? '[]' !!};
    const datasetMuscles = {!! $chartMuscles ?? '[]' !!};

    const metricConfigs = {
        weight: {
            label: 'Peso Corporal (kg)',
            data: datasetWeights,
            unit: ' kg',
            color: '#e31b23',
            gradientStart: 'rgba(227, 27, 35, 0.45)',
            gradientEnd: 'rgba(227, 27, 35, 0.0)'
        },
        fat: {
            label: '% Grasa Corporal',
            data: datasetFats,
            unit: ' %',
            color: '#facc15',
            gradientStart: 'rgba(250, 204, 21, 0.45)',
            gradientEnd: 'rgba(250, 204, 21, 0.0)'
        },
        muscle: {
            label: 'Masa Muscular (kg)',
            data: datasetMuscles,
            unit: ' kg',
            color: '#4ade80',
            gradientStart: 'rgba(74, 222, 128, 0.45)',
            gradientEnd: 'rgba(74, 222, 128, 0.0)'
        }
    };

    function initChart(metricKey = 'weight') {
        const canvas = document.getElementById('evolutionChart');
        if (!canvas || !chartLabels || chartLabels.length === 0) return;

        Chart.defaults.color = '#b4b5b5'; 
        Chart.defaults.font.family = 'Inter, sans-serif';

        const ctx = canvas.getContext('2d');
        const config = metricConfigs[metricKey];

        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, config.gradientStart);
        gradient.addColorStop(1, config.gradientEnd);

        if (evolutionChartInstance) {
            evolutionChartInstance.destroy();
        }

        evolutionChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: config.label,
                    data: config.data,
                    borderColor: config.color,
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#131313',
                    pointBorderColor: config.color,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f1f1f',
                        titleColor: '#e2e2e2',
                        bodyColor: '#e2e2e2',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + config.unit;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return value + config.unit;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    }

    function switchChartMetric(metricKey, button) {
        document.querySelectorAll('.metric-btn').forEach(btn => {
            btn.classList.remove('bg-primary', 'text-on-primary', 'shadow-sm', 'active');
            btn.classList.add('text-on-surface-variant');
        });

        button.classList.remove('text-on-surface-variant');
        button.classList.add('bg-primary', 'text-on-primary', 'shadow-sm', 'active');

        initChart(metricKey);
    }

    document.addEventListener('DOMContentLoaded', function() {
        initChart('weight');
    });
</script>
@endpush

