@extends('layouts.admin')

@section('title', 'Finanzas y Control de Caja - Ares Gym')

@push('styles')
<style>
    .glass-card {
        background: rgba(31, 31, 31, 0.75);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }
    .custom-radio:checked + div {
        border-color: #e31b23;
        background-color: rgba(227, 27, 35, 0.14);
    }
    .custom-radio:checked + div .radio-icon {
        color: #e31b23;
    }
    .animate-fade-in {
        animation: fadeIn 0.25s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
@php
    $dailyPassPrice = (float)($globalDailyPassPrice ?? ($dailyPlan->price ?? 4.00));
@endphp

<div class="flex flex-col w-full animate-on-load gap-8 pb-12">
    
    <!-- Encabezado Principal -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <div class="flex items-center gap-2 text-primary tracking-widest font-etiqueta-sm uppercase mb-2">
                <span class="w-8 h-[1px] bg-primary"></span>
                <span>MÓDULO FINANCIERO & CAJA</span>
            </div>
            <h1 class="font-titular-xl text-[38px] md:text-[46px] text-on-surface uppercase tracking-tight leading-none" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">Control de Caja</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant mt-2">Arqueo de ingresos diarios, cobro de cuotas individuales, abonos y pases express.</p>
        </div>
        <div class="flex flex-wrap gap-3 items-center">
            <div class="hidden sm:flex flex-col text-right mr-2">
                <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest">Caja Operativa</span>
                <span class="font-titular-md text-sm font-bold text-on-surface">{{ now()->translatedFormat('d \d\e F, Y') }}</span>
            </div>
            <button type="button" onclick="openArqueoModal()" class="bg-surface-container-high text-on-surface border border-outline-variant/20 px-4 py-2.5 rounded-xl font-etiqueta-bold text-xs uppercase tracking-wide hover:bg-surface-container-highest hover:text-primary transition-all flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">receipt_long</span> Arqueo de Caja (PDF)
            </button>
            <a href="{{ route('memberships') }}" class="bg-primary text-on-primary px-4 py-2.5 rounded-xl font-titular-md text-xs uppercase tracking-wider shadow-[0_4px_15px_rgba(227,27,35,0.3)] hover:brightness-110 transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">badge</span> Ver Membresías
            </a>
        </div>
    </div>

    <!-- Subnavegación del Módulo Pagos: Terminal vs Cierres de Caja -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-outline-variant/10 pb-4 gap-4">
        <div class="flex items-center gap-2 bg-surface-container p-1 rounded-2xl border border-outline-variant/10">
            <a href="{{ route('pagos') }}" class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-titular-md text-xs uppercase tracking-wider transition-all {{ $activeTab !== 'cierres' ? 'bg-primary text-white shadow-[0_2px_10px_rgba(227,27,35,0.3)]' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' }}">
                <span class="material-symbols-outlined text-[18px]">payments</span>
                <span>Terminal de Cobros & Ingresos</span>
            </a>
            <a href="{{ route('pagos', ['tab' => 'cierres']) }}" class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-titular-md text-xs uppercase tracking-wider transition-all {{ $activeTab === 'cierres' ? 'bg-primary text-white shadow-[0_2px_10px_rgba(227,27,35,0.3)]' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' }}">
                <span class="material-symbols-outlined text-[18px]">point_of_sale</span>
                <span>Cierres de Caja & Arqueos</span>
                <span class="bg-surface-container-highest text-on-surface text-[10px] px-2 py-0.5 rounded-full font-mono font-bold">{{ $cashShiftsCount }}</span>
            </a>
        </div>

        @if($activeTab === 'cierres')
            <div class="flex items-center gap-3">
                <a href="{{ route('cash-shifts.excel') }}" class="bg-surface-container-high text-on-surface border border-outline-variant/20 px-4 py-2 rounded-xl font-etiqueta-bold text-xs uppercase tracking-wide hover:bg-surface-container-highest hover:text-green-400 transition-all flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[18px] text-green-400">download</span> Exportar CSV
                </a>
                <button type="button" onclick="openArqueoModal()" class="bg-primary text-white px-4 py-2 rounded-xl font-titular-md text-xs uppercase tracking-wider shadow-[0_4px_15px_rgba(227,27,35,0.3)] hover:brightness-110 transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">lock_reset</span> Realizar Arqueo (PDF)
                </button>
            </div>
        @endif
    </div>

    @if($activeTab === 'cierres')
        <!-- SUBSECCIÓN: HISTORIAL DE CIERRES DE CAJA & ARQUEOS -->
        <div class="flex flex-col gap-6 animate-fade-in">
            
            <!-- KPIs de Cierres de Caja -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="glass-card rounded-2xl p-5 flex flex-col justify-between relative overflow-hidden group border-b-4 border-b-primary">
                    <div class="flex justify-between items-start z-10">
                        <span class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Total Cierres</span>
                        <span class="material-symbols-outlined text-primary text-[24px]">history</span>
                    </div>
                    <div class="z-10 mt-2">
                        <h3 class="font-titular-xl text-[32px] text-on-surface leading-none font-extrabold" style="font-family: 'Montserrat', sans-serif;">{{ $cashShiftsCount }}</h3>
                        <p class="text-[11px] text-on-surface-variant mt-2 font-mono">Turnos cerrados en sistema</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-5 flex flex-col justify-between relative overflow-hidden group border-b-4 border-b-[#4ade80]">
                    <div class="flex justify-between items-start z-10">
                        <span class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Horario Operativo</span>
                        <span class="material-symbols-outlined text-[#4ade80] text-[24px]">schedule</span>
                    </div>
                    <div class="z-10 mt-2">
                        <h3 class="font-titular-xl text-[24px] text-on-surface leading-none font-extrabold" style="font-family: 'Montserrat', sans-serif;">05:00 – 22:00</h3>
                        <p class="text-[11px] text-[#4ade80] mt-2 font-bold uppercase">Jornada Ares Gym</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-5 flex flex-col justify-between relative overflow-hidden group border-b-4 border-b-[#60a5fa]">
                    <div class="flex justify-between items-start z-10">
                        <span class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Fondo Base Apertura</span>
                        <span class="material-symbols-outlined text-[#60a5fa] text-[24px]">savings</span>
                    </div>
                    <div class="z-10 mt-2">
                        <h3 class="font-titular-xl text-[32px] text-on-surface leading-none font-extrabold" style="font-family: 'Montserrat', sans-serif;">$50.00</h3>
                        <p class="text-[11px] text-on-surface-variant mt-2 font-mono">Caja Chica por defecto</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-5 flex flex-col justify-between relative overflow-hidden group border-b-4 border-b-[#facc15]">
                    <div class="flex justify-between items-start z-10">
                        <span class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Auditoría & PIN</span>
                        <span class="material-symbols-outlined text-[#facc15] text-[24px]">verified_user</span>
                    </div>
                    <div class="z-10 mt-2">
                        <h3 class="font-titular-md text-lg text-on-surface leading-snug font-extrabold uppercase">PDF Inmutable</h3>
                        <p class="text-[11px] text-[#facc15] mt-2 font-mono">Autorizado por Supervisor</p>
                    </div>
                </div>
            </div>

            <!-- Tabla de Cierres de Turno -->
            <div class="glass-card rounded-2xl p-6 border border-outline-variant/10 flex flex-col gap-5">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                        </div>
                        <div>
                            <h2 class="font-titular-md text-base uppercase text-on-surface font-extrabold">Historial Oficial de Cierres de Caja</h2>
                            <p class="text-xs text-on-surface-variant">Arqueos auditados con desglose de billetes, monedas y transferencias verificadas.</p>
                        </div>
                    </div>

                    <button type="button" onclick="openArqueoModal()" class="bg-primary text-white px-3.5 py-1.5 rounded-xl font-titular-md text-xs uppercase tracking-wider hover:brightness-110 transition-all flex items-center gap-1.5 shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">add_circle</span> Nuevo Arqueo Diario
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-body-md">
                        <thead>
                            <tr class="border-b border-outline-variant/10 text-[10px] uppercase tracking-wider text-on-surface-variant bg-surface-container/30">
                                <th class="py-3 px-4 font-bold">Folio / Turno</th>
                                <th class="py-3 px-4 font-bold">Fecha & Horario</th>
                                <th class="py-3 px-4 font-bold">Responsable</th>
                                <th class="py-3 px-4 font-bold text-right">Fondo Base</th>
                                <th class="py-3 px-4 font-bold text-right">Efectivo Declarado</th>
                                <th class="py-3 px-4 font-bold text-right">Transferencias</th>
                                <th class="py-3 px-4 font-bold text-center">Estado / Balance</th>
                                <th class="py-3 px-4 font-bold text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            @forelse($cashShifts as $shift)
                                @php
                                    $isCuadrado = ((float)$shift->cash_difference) == 0;
                                    $isSobrante = ((float)$shift->cash_difference) > 0;
                                @endphp
                                <tr class="hover:bg-surface-container/40 transition-colors">
                                    <td class="py-3.5 px-4 font-mono font-bold text-primary">
                                        #{{ $shift->id }} - {{ $shift->created_at ? $shift->created_at->format('Ymd') : '' }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-on-surface">{{ $shift->opening_time ? $shift->opening_time->format('d/m/Y') : '' }}</div>
                                        <div class="text-[10px] text-on-surface-variant font-mono">
                                            {{ $shift->opening_time ? $shift->opening_time->format('H:i') : '05:00' }} – {{ $shift->closing_time ? $shift->closing_time->format('H:i') : '22:00' }}
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-on-surface">{{ $shift->user ? ($shift->user->name . ' ' . $shift->user->last_name) : 'Administrador Ares Gym' }}</div>
                                        <div class="text-[10px] text-on-surface-variant">{{ $shift->user ? $shift->user->email : 'aresgym.local' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-mono text-on-surface-variant">
                                        ${{ number_format($shift->initial_base_cash, 2) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-mono font-bold text-[#4ade80]">
                                        ${{ number_format($shift->total_declared_cash, 2) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-mono font-bold text-[#60a5fa]">
                                        ${{ number_format($shift->total_system_transfers, 2) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if($isCuadrado)
                                            <span class="inline-flex items-center gap-1 text-[#4ade80] bg-[#4ade80]/10 border border-[#4ade80]/20 px-2.5 py-1 rounded-full font-etiqueta-bold text-[10px] uppercase tracking-wider">
                                                <span class="material-symbols-outlined text-[13px]">check_circle</span> Cuadrado
                                            </span>
                                        @elseif($isSobrante)
                                            <span class="inline-flex items-center gap-1 text-[#facc15] bg-[#facc15]/10 border border-[#facc15]/20 px-2.5 py-1 rounded-full font-etiqueta-bold text-[10px] uppercase tracking-wider">
                                                <span class="material-symbols-outlined text-[13px]">arrow_upward</span> Sobrante (+${{ number_format($shift->cash_difference, 2) }})
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-error bg-error/10 border border-error/20 px-2.5 py-1 rounded-full font-etiqueta-bold text-[10px] uppercase tracking-wider">
                                                <span class="material-symbols-outlined text-[13px]">warning</span> Faltante (${{ number_format($shift->cash_difference, 2) }})
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a href="{{ route('pagos.arqueo', ['shift_id' => $shift->id]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-highest text-on-surface hover:text-primary transition-all font-etiqueta-bold text-xs shadow-sm" title="Ver Documento PDF Oficial">
                                            <span class="material-symbols-outlined text-[16px] text-primary">picture_as_pdf</span>
                                            <span>Ver PDF</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-on-surface-variant">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <div class="w-12 h-12 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant">
                                                <span class="material-symbols-outlined text-[28px]">point_of_sale</span>
                                            </div>
                                            <p class="font-bold text-on-surface">No hay cierres de caja registrados todavía.</p>
                                            <p class="text-xs text-on-surface-variant max-w-sm">Genera tu primer arqueo de caja diario para auditar los ingresos en efectivo y transferencias.</p>
                                            <button type="button" onclick="openArqueoModal()" class="mt-2 bg-primary text-white px-4 py-2 rounded-xl font-titular-md text-xs uppercase tracking-wider hover:brightness-110 transition-all flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-[16px]">lock</span> Generar Primer Arqueo (PDF)
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($cashShifts->hasPages())
                    <div class="pt-4 border-t border-outline-variant/10">
                        {{ $cashShifts->appends(['tab' => 'cierres'])->links() }}
                    </div>
                @endif
            </div>

        </div>
    @else
        <!-- SUBSECCIÓN: TERMINAL DE COBROS, INGRESOS & TRANSACCIONES EN VIVO -->
        <!-- BLOQUE 1: KPIs Financieros en Tiempo Real -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- KPI 1: Caja del Día -->
        <div class="glass-card rounded-2xl p-5 flex flex-col justify-between relative overflow-hidden group border-b-4 border-b-[#4ade80]">
            <div class="absolute -right-6 -top-6 w-20 h-20 bg-[#4ade80]/10 rounded-full blur-xl group-hover:bg-[#4ade80]/20 transition-colors"></div>
            <div class="flex justify-between items-start z-10">
                <span class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Caja de Hoy</span>
                <span class="material-symbols-outlined text-[#4ade80] text-[24px]">point_of_sale</span>
            </div>
            <div class="z-10 mt-2">
                <h3 id="kpi-today-revenue" class="font-titular-xl text-[32px] text-on-surface leading-none font-extrabold" style="font-family: 'Montserrat', sans-serif;">${{ number_format($todayRevenue, 2) }}</h3>
                <div class="flex items-center gap-2 mt-2 text-[10px] font-mono">
                    <span id="kpi-today-cash" class="bg-[#4ade80]/15 text-[#4ade80] px-1.5 py-0.5 rounded font-bold">Efectivo: ${{ number_format($todayCash, 2) }}</span>
                    <span id="kpi-today-transfer" class="bg-[#60a5fa]/15 text-[#60a5fa] px-1.5 py-0.5 rounded font-bold">Transf: ${{ number_format($todayTransfer, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- KPI 2: Ingresos del Mes -->
        <div class="glass-card rounded-2xl p-5 flex flex-col justify-between relative overflow-hidden group border-b-4 border-b-primary">
            <div class="absolute -right-6 -top-6 w-20 h-20 bg-primary/10 rounded-full blur-xl group-hover:bg-primary/20 transition-colors"></div>
            <div class="flex justify-between items-start z-10">
                <span class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Ingresos del Mes</span>
                <span class="material-symbols-outlined text-primary text-[24px]">account_balance_wallet</span>
            </div>
            <div class="z-10 mt-2">
                <h3 id="kpi-month-revenue" class="font-titular-xl text-[32px] text-on-surface leading-none font-extrabold" style="font-family: 'Montserrat', sans-serif;">${{ number_format($totalMonthlyRevenue, 2) }}</h3>
                <p class="font-etiqueta-sm text-primary mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">calendar_month</span> {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                </p>
            </div>
        </div>

        <!-- KPI 3: Distribución Efectivo vs Transferencia -->
        <div class="glass-card rounded-2xl p-5 flex flex-col justify-between relative overflow-hidden group border-b-4 border-b-[#60a5fa]">
            <div class="flex justify-between items-start z-10">
                <span class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Distribución de Cobro</span>
                <span class="material-symbols-outlined text-[#60a5fa] text-[24px]">pie_chart</span>
            </div>
            <div class="z-10 w-full mt-2">
                <div class="flex justify-between font-etiqueta-bold text-[11px] uppercase mb-1.5 font-mono">
                    <span id="kpi-cash-pct" class="text-[#4ade80]">Efectivo ({{ $cashPercentage }}%)</span>
                    <span id="kpi-transfer-pct" class="text-[#60a5fa]">Transf. ({{ $transferPercentage }}%)</span>
                </div>
                <div class="w-full bg-surface-container-highest rounded-full h-2.5 overflow-hidden flex">
                    <div id="kpi-bar-cash" class="bg-[#4ade80] h-full transition-all duration-500" style="width: {{ $cashPercentage }}%"></div>
                    <div id="kpi-bar-transfer" class="bg-[#60a5fa] h-full transition-all duration-500" style="width: {{ $transferPercentage }}%"></div>
                </div>
            </div>
        </div>

        <!-- KPI 4: Deudas / Cartera por Cobrar -->
        <div class="glass-card rounded-2xl p-5 flex flex-col justify-between relative overflow-hidden group border-b-4 border-b-error">
            <div class="absolute -right-6 -top-6 w-20 h-20 bg-error/10 rounded-full blur-xl group-hover:bg-error/20 transition-colors pointer-events-none"></div>
            <div class="flex justify-between items-start z-10">
                <span class="font-etiqueta-bold text-[11px] text-error uppercase tracking-widest flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">warning</span> Por Cobrar / Deudas
                </span>
                <span class="material-symbols-outlined text-error text-[24px]">pending_actions</span>
            </div>
            <div class="z-10 mt-2">
                <h3 id="kpi-pending-debt" class="font-titular-xl text-[32px] text-error leading-none font-extrabold" style="font-family: 'Montserrat', sans-serif;">${{ number_format($totalPendingDebt, 2) }}</h3>
                <p id="kpi-pending-debt-count" class="font-etiqueta-sm text-error/80 mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">group</span> {{ $totalPendingDebtCount }} atletas con saldo adeudado
                </p>
            </div>
        </div>

    </div>

    <!-- Disposición Principal -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 mt-1">
        
        <!-- COLUMNA IZQUIERDA (35%): Terminal de Cobro en Caja -->
        <div class="xl:col-span-4 flex flex-col gap-6">
            <div class="glass-card rounded-[24px] p-6 lg:p-7 flex flex-col gap-5 shadow-xl border-t border-t-primary/30 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-full h-full bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary/5 via-transparent to-transparent pointer-events-none"></div>
                
                <div class="flex justify-between items-center relative z-10">
                    <div>
                        <h2 class="font-titular-md text-[20px] text-on-surface font-bold" style="font-family: 'Montserrat', sans-serif;">Terminal de Cobro</h2>
                        <p class="font-body-md text-on-surface-variant text-xs mt-0.5">Cobro de cuotas, pases y liquidación de saldos.</p>
                    </div>
                    <span class="material-symbols-outlined text-primary text-[26px]">payments</span>
                </div>

                <!-- Selector de Operación: Individual vs Grupal vs Pase Express -->
                <div class="grid grid-cols-3 gap-1.5 relative z-10">
                    <button type="button" id="tab-op-membership" onclick="switchOperationType('membership')" 
                            class="py-2 px-1.5 rounded-xl font-etiqueta-bold text-[10px] uppercase tracking-wider transition-all bg-primary text-on-primary shadow-sm flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">badge</span> Individual
                    </button>
                    <button type="button" id="tab-op-group" onclick="switchOperationType('group')" 
                            class="py-2 px-1.5 rounded-xl font-etiqueta-bold text-[10px] uppercase tracking-wider transition-all bg-surface-container text-on-surface-variant hover:bg-surface-container-high flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">group</span> Grupal 👥
                    </button>
                    <button type="button" id="tab-op-daily" onclick="switchOperationType('daily')" 
                            class="py-2 px-1.5 rounded-xl font-etiqueta-bold text-[10px] uppercase tracking-wider transition-all bg-surface-container text-on-surface-variant hover:bg-surface-container-high flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">confirmation_number</span> Express 🎟️
                    </button>
                </div>
                
                <!-- SECCIÓN A: Formulario de Cobro de Membresía Individual / Deudas -->
                <form id="cash-payment-form" onsubmit="handleCashFormSubmit(event)" class="flex flex-col gap-4 relative z-10">
                    @csrf
                    <input type="hidden" id="form-client-id">
                    <input type="hidden" id="form-membership-id">
                    <input type="hidden" id="form-action-type" value="normal">

                    <!-- Mensaje de Error en Formulario -->
                    <div id="form-error-banner" class="hidden p-3 bg-error/15 border border-error/40 rounded-xl text-error text-xs flex items-center gap-2 animate-fade-in">
                        <span class="material-symbols-outlined text-[18px] shrink-0">error</span>
                        <span id="form-error-text">---</span>
                    </div>

                    <!-- 1. Buscador Predictivo de Atleta -->
                    <div class="flex flex-col gap-1.5 relative group">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px] flex justify-between">
                            <span>Socio / Atleta Registrado <span class="text-error font-bold">*</span></span>
                            <span class="text-primary font-bold text-[9px]" id="client-search-status">Buscar por cédula o nombre</span>
                        </label>
                        
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[18px]">search</span>
                            <input type="text" id="live-client-search" autocomplete="off"
                                   class="w-full bg-surface-container-lowest text-on-surface font-body-md pl-10 pr-8 py-3 rounded-xl focus:outline-none transition-all duration-300 border border-outline-variant/20 focus:border-primary/50 text-xs shadow-inner" 
                                   placeholder="Escribe cédula o nombre del atleta..."
                                   onfocus="handleClientSearch(this.value)"
                                   oninput="handleClientSearch(this.value)">
                            <button type="button" id="btn-clear-client" onclick="resetClientSelection()" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-error">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </button>
                        </div>

                        <!-- Dropdown de resultados predictivos -->
                        <div id="client-search-results" class="hidden absolute left-0 right-0 top-full mt-1 bg-surface-container-high rounded-xl shadow-2xl border border-outline-variant/20 max-h-56 overflow-y-auto z-50 divide-y divide-outline-variant/10"></div>
                    </div>

                    <!-- 2. Alerta Interactiva de Deuda Pendiente (Si el atleta debe dinero) -->
                    <div id="client-debt-alert-box" class="hidden flex-col gap-2.5 p-3.5 bg-error/10 border-2 border-error/40 rounded-xl animate-fade-in">
                        <div class="flex items-start gap-2.5">
                            <span class="material-symbols-outlined text-error text-[22px] shrink-0 mt-0.5">warning</span>
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-titular-md text-xs font-bold text-on-surface" id="debt-client-name">---</span>
                                    <span class="bg-error text-white font-etiqueta-bold text-[8px] px-1.5 py-0.2 rounded uppercase font-mono">Deuda</span>
                                </div>
                                <p class="font-body-md text-[11px] text-on-surface-variant mt-0.5" id="debt-info-text">
                                    Mantiene un saldo adeudado de <strong class="text-error font-mono text-xs" id="debt-amount-display">$0.00</strong> (<span id="debt-plan-name">---</span>).
                                </p>
                            </div>
                        </div>

                        <!-- Opciones de Resolución de Deuda -->
                        <div class="flex flex-col gap-1.5 pt-2 border-t border-error/20">
                            <label class="flex items-center gap-2 bg-surface-container-lowest p-2.5 rounded-lg border border-outline-variant/20 hover:border-primary/40 cursor-pointer transition-all">
                                <input type="radio" name="debt_action_choice" value="liquidate_only" checked onchange="handleDebtChoiceChange('liquidate_only')" class="text-primary focus:ring-primary">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-on-surface">🔘 Solo Liquidar Deuda (<span class="text-error font-mono font-bold" id="opt-only-debt-amount">$0.00</span>)</span>
                                    <span class="text-[9px] text-on-surface-variant">Cobra lo adeudado y deja en $0.00 sin renovar.</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-2 bg-surface-container-lowest p-2.5 rounded-lg border border-outline-variant/20 hover:border-primary/40 cursor-pointer transition-all">
                                <input type="radio" name="debt_action_choice" value="renew_with_debt" onchange="handleDebtChoiceChange('renew_with_debt')" class="text-primary focus:ring-primary">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-on-surface">🔄 Liquidar + Renovar (<span class="text-[#4ade80] font-mono font-bold" id="opt-renew-debt-amount">$0.00</span>)</span>
                                    <span class="text-[9px] text-on-surface-variant">Cancela la deuda anterior y activa nuevo mes.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- 3. Selección de Plan Individual (Exclusivamente planes individuales) -->
                    <div id="plan-selection-group" class="flex flex-col gap-1.5">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px]">Plan de Suscripción Individual</label>
                        <select id="form-plan-select" onchange="handleFormPlanChange(this)" class="w-full bg-surface-container-lowest text-on-surface font-body-md p-2.5 rounded-xl border border-outline-variant/20 focus:outline-none focus:border-primary/50 text-xs cursor-pointer">
                            @foreach($subscriptionPlans as $pl)
                                <option value="{{ $pl->id }}" data-price="{{ $pl->price }}" data-name="{{ $pl->name }}" data-days="{{ $pl->validity_days }}">
                                    {{ $pl->name }} — ${{ number_format($pl->price, 2) }} ({{ $pl->validity_days }} días)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 4. Monto a Cobrar (READONLY - Bloqueado a tarifa oficial) -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px] flex justify-between items-center">
                            <span>Monto Total Oficial</span>
                            <span class="font-mono text-[10px] text-primary font-bold" id="form-concept-badge">Tarifa Catálogo</span>
                        </label>
                        <div class="relative group">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-primary font-titular-md text-[18px]">$</span>
                            <input type="number" step="0.01" id="form-amount-input" readonly 
                                   class="w-full bg-surface-container-high text-on-surface font-titular-md text-[20px] pl-9 pr-4 py-3 rounded-xl border border-outline-variant/30 shadow-inner font-bold cursor-not-allowed select-none focus:outline-none" 
                                   value="{{ number_format($subscriptionPlans->first()->price ?? 30.00, 2, '.', '') }}" required>
                        </div>
                    </div>

                    <!-- 5. Modalidad de Pago: Pago Completo vs Abono Parcial -->
                    <div id="payment-type-selector-box" class="flex flex-col gap-2">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px]">Modalidad de Pago</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="cursor-pointer relative">
                                <input type="radio" name="payment_type" value="completo" class="custom-radio sr-only" checked onchange="toggleFormAbono(false)">
                                <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-xl p-2.5 flex items-center justify-center gap-1.5 transition-all text-center">
                                    <span class="material-symbols-outlined text-on-surface-variant radio-icon text-[17px]">verified</span>
                                    <span class="font-etiqueta-bold text-[11px] text-on-surface uppercase">Completo</span>
                                </div>
                            </label>
                            <label class="cursor-pointer relative">
                                <input type="radio" name="payment_type" value="abono" class="custom-radio sr-only" onchange="toggleFormAbono(true)">
                                <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-xl p-2.5 flex items-center justify-center gap-1.5 transition-all text-center">
                                    <span class="material-symbols-outlined text-on-surface-variant radio-icon text-[17px]">schedule</span>
                                    <span class="font-etiqueta-bold text-[11px] text-on-surface uppercase">Abono Parcial</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Campos Dinámicos de Abono Parcial con Autorización de Supervisor -->
                    <div id="form-abono-fields-box" class="hidden flex-col gap-3 p-3.5 bg-surface-container-low rounded-xl border border-primary/25 animate-fade-in">
                        <div class="flex justify-between items-center text-[10px] text-on-surface-variant font-mono">
                            <span>Mínimo (25%): $<strong id="form-abono-min-text">0.00</strong></span>
                            <span>Máximo (75%): $<strong id="form-abono-max-text">0.00</strong></span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[9px]">Monto del Abono Hoy ($) <span class="text-primary font-bold">*</span></label>
                            <input type="number" step="0.01" id="form-abono-input" class="w-full bg-surface-container-lowest text-on-surface font-titular-md text-[16px] px-3 py-2 rounded-lg border border-outline-variant/20 focus:outline-none focus:border-primary/50 font-bold" placeholder="Monto a recibir">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[9px] flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px] text-primary">key</span>
                                <span>Clave de Autorización Supervisor <span class="text-error font-bold">*</span></span>
                            </label>
                            <input type="password" id="form-admin-pin" placeholder="Ingresa PIN de autorización" class="w-full bg-surface-container-lowest text-on-surface px-3 py-2 rounded-lg border border-outline-variant/20 focus:outline-none focus:border-primary/50 text-xs font-mono">
                        </div>
                    </div>

                    <!-- 6. Selector de Método de Pago -->
                    <div class="flex flex-col gap-2">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px]">Método de Pago</label>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <label class="cursor-pointer relative">
                                <input type="radio" name="payment_method" value="efectivo" class="custom-radio sr-only" checked onchange="toggleFormPaymentFields()">
                                <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-xl p-2.5 flex items-center justify-center gap-2 transition-all text-center">
                                    <span class="material-symbols-outlined text-on-surface-variant radio-icon text-[18px]">payments</span>
                                    <span class="font-etiqueta-bold text-[11px] text-on-surface uppercase tracking-wider">Efectivo 💵</span>
                                </div>
                            </label>
                            
                            <label class="cursor-pointer relative">
                                <input type="radio" name="payment_method" value="transferencia" class="custom-radio sr-only" onchange="toggleFormPaymentFields()">
                                <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-xl p-2.5 flex items-center justify-center gap-2 transition-all text-center">
                                    <span class="material-symbols-outlined text-on-surface-variant radio-icon text-[18px]">account_balance</span>
                                    <span class="font-etiqueta-bold text-[11px] text-on-surface uppercase tracking-wider">Transf. 🏦</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Voucher Transferencia -->
                    <div id="transfer-voucher-box" class="hidden flex-col gap-1 bg-surface-container-low p-3 rounded-xl border border-outline-variant/10 animate-fade-in">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[9px]">N° de Comprobante / Voucher / Referencia <span class="text-error font-bold">*</span></label>
                        <input type="text" id="form-voucher-input" class="w-full bg-surface-container-lowest text-on-surface font-body-md px-3 py-2 rounded-lg focus:outline-none focus:border-primary/50 border border-outline-variant/20 text-xs font-mono" placeholder="Ej. 182390123">
                    </div>

                    <!-- Botón de Registro -->
                    <button type="submit" id="btn-submit-cash-form" class="w-full bg-primary text-on-primary font-titular-md text-[14px] uppercase tracking-wider py-3.5 rounded-xl shadow-[0_0_15px_rgba(227,27,35,0.3)] hover:shadow-[0_0_25px_rgba(227,27,35,0.5)] hover:scale-[1.01] transition-all flex items-center justify-center gap-2 mt-1">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        <span id="btn-submit-text">Registrar y Confirmar Cobro</span>
                    </button>
                </form>

                <!-- SECCIÓN B: Formulario de Cobro para Planes Grupales (Búsqueda Directa del Grupo con Detalle Completo) -->
                <form id="group-payment-form" onsubmit="handleGroupPaymentSubmit(event)" class="hidden flex-col gap-4 relative z-10 animate-fade-in">
                    @csrf
                    <input type="hidden" id="form-group-membership-id">
                    <input type="hidden" id="form-group-action-type" value="group_payment">
                    <input type="hidden" id="form-group-plan-id">

                    <!-- Mensaje de Error en Formulario Grupal -->
                    <div id="group-form-error-banner" class="hidden p-3 bg-error/15 border border-error/40 rounded-xl text-error text-xs flex items-center gap-2 animate-fade-in">
                        <span class="material-symbols-outlined text-[18px] shrink-0">error</span>
                        <span id="group-form-error-text">---</span>
                    </div>

                    <!-- 1. Buscador Predictivo de Grupos y Planes Grupales -->
                    <div class="flex flex-col gap-1.5 relative group">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px] flex justify-between items-center">
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-primary text-[14px]">diversity_3</span> <span>Plan Grupal / Grupo Registrado <span class="text-error font-bold">*</span></span></span>
                            <span class="text-primary font-bold text-[9px]" id="group-search-status">Buscar por nombre o integrante</span>
                        </label>
                        
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[18px]">search</span>
                            <input type="text" id="live-group-search" autocomplete="off"
                                   class="w-full bg-surface-container-lowest text-on-surface font-body-md pl-10 pr-8 py-3 rounded-xl focus:outline-none transition-all duration-300 border border-outline-variant/20 focus:border-primary/50 text-xs shadow-inner" 
                                   placeholder="Escribe el nombre del grupo o de cualquier atleta integrante..."
                                   onfocus="handleGroupSearch(this.value)"
                                   oninput="handleGroupSearch(this.value)">
                            <button type="button" id="btn-clear-group" onclick="resetGroupSelection()" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-error">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </button>
                        </div>

                        <!-- Dropdown de resultados predictivos para grupos -->
                        <div id="group-search-results" class="hidden absolute left-0 right-0 top-full mt-1 bg-surface-container-high rounded-xl shadow-2xl border border-outline-variant/20 max-h-60 overflow-y-auto z-50 divide-y divide-outline-variant/10"></div>
                        
                        <div class="flex justify-between items-center text-[10px] text-on-surface-variant px-1 mt-0.5">
                            <span>¿Nuevo grupo? <a href="{{ route('clientes.create.group') }}" class="text-primary font-bold hover:underline inline-flex items-center gap-0.5">Crear Nuevo Grupo <span class="material-symbols-outlined text-[12px]">open_in_new</span></a></span>
                            <span class="text-[9.5px] text-on-surface-variant/80 italic">Atletas vinculados automáticamente</span>
                        </div>
                    </div>

                    <!-- 2. Alerta Interactiva de Deuda Pendiente del Grupo (Si el grupo debe dinero) -->
                    <div id="group-debt-alert-box" class="hidden flex-col gap-2.5 p-3.5 bg-error/10 border-2 border-error/40 rounded-xl animate-fade-in">
                        <div class="flex items-start gap-2.5">
                            <span class="material-symbols-outlined text-error text-[22px] shrink-0 mt-0.5">warning</span>
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-titular-md text-xs font-bold text-on-surface" id="group-debt-title">---</span>
                                    <span class="bg-error text-white font-etiqueta-bold text-[8px] px-1.5 py-0.2 rounded uppercase font-mono">Deuda Grupal</span>
                                </div>
                                <p class="font-body-md text-[11px] text-on-surface-variant mt-0.5">
                                    Este grupo mantiene un saldo pendiente de <strong class="text-error font-mono text-xs" id="group-debt-amount-display">$0.00</strong> (<span id="group-debt-plan-name">---</span>).
                                </p>
                            </div>
                        </div>

                        <!-- Opciones de Resolución de Deuda Grupal -->
                        <div class="flex flex-col gap-1.5 pt-2 border-t border-error/20">
                            <label class="flex items-center gap-2 bg-surface-container-lowest p-2.5 rounded-lg border border-outline-variant/20 hover:border-primary/40 cursor-pointer transition-all">
                                <input type="radio" name="group_debt_action_choice" value="liquidate_only" checked onchange="handleGroupDebtChoiceChange('liquidate_only')" class="text-primary focus:ring-primary">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-on-surface">🔘 Solo Liquidar Deuda Grupal (<span class="text-error font-mono font-bold" id="group-opt-only-debt-amount">$0.00</span>)</span>
                                    <span class="text-[9px] text-on-surface-variant">Cobra lo adeudado y deja en $0.00 sin renovar ciclo.</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-2 bg-surface-container-lowest p-2.5 rounded-lg border border-outline-variant/20 hover:border-primary/40 cursor-pointer transition-all">
                                <input type="radio" name="group_debt_action_choice" value="renew_with_debt" onchange="handleGroupDebtChoiceChange('renew_with_debt')" class="text-primary focus:ring-primary">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-on-surface">🔄 Liquidar + Renovar Plan Grupal (<span class="text-[#4ade80] font-mono font-bold" id="group-opt-renew-debt-amount">$0.00</span>)</span>
                                    <span class="text-[9px] text-on-surface-variant">Cancela saldo pendiente y renueva la membresía grupal para todos.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- 3. TARJETA COMPLETA CON EL DETALLE DEL GRUPO Y PLAN ASIGNADO -->
                    <div id="group-details-card" class="hidden flex-col gap-3 p-4 bg-surface-container-low border border-primary/25 rounded-2xl animate-fade-in relative overflow-hidden shadow-md">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-2xl pointer-events-none"></div>
                        
                        <!-- Header del Grupo -->
                        <div class="flex justify-between items-start border-b border-outline-variant/15 pb-2.5 relative z-10">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-primary/15 text-primary border border-primary/30 flex items-center justify-center font-bold">
                                    <span class="material-symbols-outlined text-[20px]">groups</span>
                                </div>
                                <div class="flex flex-col">
                                    <h3 class="font-titular-md text-[15px] font-bold text-on-surface leading-tight" id="group-card-name">---</h3>
                                    <span class="text-[10px] text-on-surface-variant font-mono" id="group-card-dates">Vigencia: ---</span>
                                </div>
                            </div>
                            <span id="group-card-status-badge" class="px-2.5 py-0.5 rounded-full text-[9px] font-etiqueta-bold uppercase tracking-wider bg-green-500/10 text-green-400 border border-green-500/20">
                                Activo
                            </span>
                        </div>

                        <!-- Plan Grupal Asignado -->
                        <div class="bg-surface-container p-3 rounded-xl border border-outline-variant/10 flex justify-between items-center text-xs relative z-10">
                            <div class="flex flex-col">
                                <span class="text-[9px] text-on-surface-variant uppercase font-bold tracking-wider">Plan Grupal Asignado</span>
                                <span class="font-bold text-on-surface text-[13px] flex items-center gap-1 text-primary" id="group-card-plan-name">---</span>
                            </div>
                            <div class="text-right flex flex-col">
                                <span class="text-[9px] text-on-surface-variant uppercase font-bold tracking-wider">Tarifa / Persona</span>
                                <span class="font-mono font-bold text-on-surface text-[13px]" id="group-card-unit-price">$0.00</span>
                            </div>
                        </div>

                        <!-- Listado Detallado de Atletas Integrantes -->
                        <div class="flex flex-col gap-1.5 relative z-10">
                            <div class="flex justify-between items-center text-[10px] text-on-surface-variant font-etiqueta-bold uppercase tracking-wider px-1">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-primary text-[14px]">person</span>
                                    <span>Atletas Integrantes (<span id="group-card-members-count">0</span>)</span>
                                </span>
                                <span class="text-[#4ade80] font-mono text-[9px] font-bold">✓ No requiere añadir</span>
                            </div>
                            
                            <div id="group-card-members-list" class="flex flex-col gap-1.5 max-h-36 overflow-y-auto pr-1">
                                <!-- Filas de atletas inyectadas por JS -->
                            </div>
                        </div>

                        <!-- Desglose de Liquidación y Total Oficial -->
                        <div class="bg-surface-container p-3 rounded-xl border border-outline-variant/10 flex flex-col gap-1.5 text-xs relative z-10">
                            <div class="flex justify-between items-center text-[11px] text-on-surface-variant font-body-md">
                                <span>Cálculo Plan: <span id="group-card-calc-formula" class="font-mono text-on-surface font-bold">0 × $0.00</span></span>
                                <span class="font-mono font-bold text-on-surface" id="group-card-subtotal">$0.00</span>
                            </div>
                            <div class="flex justify-between items-center text-[11px] text-on-surface-variant font-body-md" id="group-card-debt-row">
                                <span>Saldo Adeudado Anterior:</span>
                                <span class="font-mono font-bold text-error" id="group-card-debt-amount">$0.00</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-outline-variant/15 font-bold">
                                <span class="text-on-surface uppercase text-[10px] tracking-wider" id="group-card-total-label">Total a Cobrar Grupal:</span>
                                <span class="font-mono text-[18px] text-[#4ade80]" id="group-card-total-amount">$0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Monto a Cobrar Oficial (READONLY - Bloqueado al valor correspondiente del plan grupal) -->
                    <div id="group-amount-field-group" class="flex flex-col gap-1.5">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px] flex justify-between items-center">
                            <span>Monto Total Oficial a Cobrar</span>
                            <span class="font-mono text-[10px] text-primary font-bold" id="group-concept-badge">Tarifa Grupal</span>
                        </label>
                        <div class="relative group">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-primary font-titular-md text-[18px]">$</span>
                            <input type="number" step="0.01" id="group-amount-input" readonly 
                                   class="w-full bg-surface-container-high text-on-surface font-titular-md text-[20px] pl-9 pr-4 py-3 rounded-xl border border-outline-variant/30 shadow-inner font-bold cursor-not-allowed select-none focus:outline-none" 
                                   value="0.00" required>
                        </div>
                    </div>

                    <!-- 5. Modalidad de Pago: Pago Completo vs Abono Parcial Grupal -->
                    <div id="group-payment-type-box" class="flex flex-col gap-2">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px]">Modalidad de Pago</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="cursor-pointer relative">
                                <input type="radio" name="group_payment_type" value="completo" class="custom-radio sr-only" checked onchange="toggleGroupAbono(false)">
                                <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-xl p-2.5 flex items-center justify-center gap-1.5 transition-all text-center">
                                    <span class="material-symbols-outlined text-on-surface-variant radio-icon text-[17px]">verified</span>
                                    <span class="font-etiqueta-bold text-[11px] text-on-surface uppercase">Completo (100%)</span>
                                </div>
                            </label>
                            <label class="cursor-pointer relative">
                                <input type="radio" name="group_payment_type" value="abono" class="custom-radio sr-only" onchange="toggleGroupAbono(true)">
                                <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-xl p-2.5 flex items-center justify-center gap-1.5 transition-all text-center">
                                    <span class="material-symbols-outlined text-on-surface-variant radio-icon text-[17px]">schedule</span>
                                    <span class="font-etiqueta-bold text-[11px] text-on-surface uppercase">Abono Parcial</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Campos Dinámicos de Abono Parcial Grupal con Autorización de Supervisor -->
                    <div id="group-abono-fields-box" class="hidden flex-col gap-3 p-3.5 bg-surface-container-low rounded-xl border border-primary/25 animate-fade-in">
                        <div class="flex justify-between items-center text-[10px] text-on-surface-variant font-mono">
                            <span>Mínimo (25%): $<strong id="group-abono-min-text">0.00</strong></span>
                            <span>Máximo (75%): $<strong id="group-abono-max-text">0.00</strong></span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[9px]">Monto del Abono Grupal Hoy ($) <span class="text-primary font-bold">*</span></label>
                            <input type="number" step="0.01" id="group-abono-input" class="w-full bg-surface-container-lowest text-on-surface font-titular-md text-[16px] px-3 py-2 rounded-lg border border-outline-variant/20 focus:outline-none focus:border-primary/50 font-bold" placeholder="Monto a recibir">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[9px] flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px] text-primary">key</span>
                                <span>Clave de Autorización Supervisor <span class="text-error font-bold">*</span></span>
                            </label>
                            <input type="password" id="group-admin-pin" placeholder="Ingresa PIN de autorización" class="w-full bg-surface-container-lowest text-on-surface px-3 py-2 rounded-lg border border-outline-variant/20 focus:outline-none focus:border-primary/50 text-xs font-mono">
                        </div>
                    </div>

                    <!-- 6. Método de Pago (Efectivo vs Transferencia) -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px]">Método de Pago</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="cursor-pointer relative">
                                <input type="radio" name="group_payment_method" value="efectivo" class="custom-radio sr-only" checked onchange="toggleGroupTransfer(false)">
                                <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-xl p-2.5 flex items-center justify-center gap-1.5 transition-all text-center">
                                    <span class="material-symbols-outlined text-[16px]">payments</span>
                                    <span class="font-etiqueta-bold text-[10px] uppercase">Efectivo 💵</span>
                                </div>
                            </label>
                            <label class="cursor-pointer relative">
                                <input type="radio" name="group_payment_method" value="transferencia" class="custom-radio sr-only" onchange="toggleGroupTransfer(true)">
                                <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-xl p-2.5 flex items-center justify-center gap-1.5 transition-all text-center">
                                    <span class="material-symbols-outlined text-[16px]">account_balance</span>
                                    <span class="font-etiqueta-bold text-[10px] uppercase">Transf. 🏦</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Voucher Transferencia para Grupo -->
                    <div id="group-transfer-voucher-box" class="hidden flex-col gap-1 bg-surface-container-low p-2.5 rounded-xl border border-outline-variant/10 animate-fade-in">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[9px]">N° Comprobante / Referencia Bancaria <span class="text-error font-bold">*</span></label>
                        <input type="text" id="group-voucher-input" class="w-full bg-surface-container-lowest text-on-surface font-body-md px-3 py-2 rounded-lg focus:outline-none border border-outline-variant/20 text-xs font-mono" placeholder="Ej. 982312019">
                    </div>

                    <!-- Botón de Registro Grupal -->
                    <button type="submit" id="btn-submit-group-form" class="w-full bg-primary text-on-primary font-titular-md text-[13px] uppercase tracking-wider py-3.5 rounded-xl shadow-[0_0_15px_rgba(227,27,35,0.3)] hover:brightness-110 transition-all flex items-center justify-center gap-1.5 mt-1">
                        <span class="material-symbols-outlined text-[18px]">group_add</span>
                        <span id="btn-group-submit-text">Selecciona un grupo para cobrar</span>
                    </button>
                </form>

                <!-- SECCIÓN C: Formulario de Pase Express Diario -->
                <form id="daily-pass-form" onsubmit="handleDailyPassSubmit(event)" class="hidden flex-col gap-4 relative z-10 animate-fade-in">
                    @csrf
                    <div class="p-3.5 bg-primary/10 border border-primary/20 rounded-xl text-xs text-on-surface">
                        <div class="flex justify-between items-center font-bold">
                            <span class="flex items-center gap-1.5 text-primary"><span class="material-symbols-outlined text-[18px]">confirmation_number</span> Pase Express Diario</span>
                            <span class="text-primary font-mono text-base font-bold">${{ number_format($dailyPassPrice, 2) }}</span>
                        </div>
                        <p class="text-[10px] text-on-surface-variant mt-1">Acceso de 1 día para entrenar en Ares Gym.</p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px]">Nombre del Visitante <span class="text-error font-bold">*</span></label>
                        <input type="text" id="daily-guest-name" required placeholder="Ej. Juan Pérez" class="w-full bg-surface-container-lowest text-on-surface font-body-md px-3.5 py-2.5 rounded-xl border border-outline-variant/20 focus:outline-none focus:border-primary/50 text-xs">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px]">Tarifa Oficial ($)</label>
                        <input type="number" step="0.01" id="daily-amount" readonly value="{{ number_format($dailyPassPrice, 2, '.', '') }}" class="w-full bg-surface-container-high text-primary font-titular-md text-[18px] px-3.5 py-2 rounded-xl border border-outline-variant/20 focus:outline-none text-xs font-bold font-mono cursor-not-allowed select-none">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="daily_payment_method" value="efectivo" class="custom-radio sr-only" checked onchange="toggleDailyTransfer(false)">
                            <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-xl p-2.5 flex items-center justify-center gap-1.5 transition-all text-center">
                                <span class="material-symbols-outlined text-[16px]">payments</span>
                                <span class="font-etiqueta-bold text-[10px] uppercase">Efectivo 💵</span>
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" name="daily_payment_method" value="transferencia" class="custom-radio sr-only" onchange="toggleDailyTransfer(true)">
                            <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-xl p-2.5 flex items-center justify-center gap-1.5 transition-all text-center">
                                <span class="material-symbols-outlined text-[16px]">account_balance</span>
                                <span class="font-etiqueta-bold text-[10px] uppercase">Transf. 🏦</span>
                            </div>
                        </label>
                    </div>

                    <!-- Voucher Transferencia para Pase Diario -->
                    <div id="daily-transfer-voucher-box" class="hidden flex-col gap-1 bg-surface-container-low p-3 rounded-xl border border-outline-variant/10 animate-fade-in">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[9px]">N° de Comprobante / Referencia Bancaria <span class="text-error font-bold">*</span></label>
                        <input type="text" id="daily-voucher-input" class="w-full bg-surface-container-lowest text-on-surface font-body-md px-3 py-2 rounded-lg focus:outline-none focus:border-primary/50 border border-outline-variant/20 text-xs font-mono" placeholder="Ej. 782390123">
                    </div>

                    <button type="submit" id="btn-submit-daily-pass" class="w-full bg-primary text-on-primary font-titular-md text-[13px] uppercase tracking-wider py-3.5 rounded-xl shadow-sm hover:brightness-110 transition-all flex items-center justify-center gap-1.5 mt-1">
                        <span class="material-symbols-outlined text-[16px]">how_to_reg</span> Registrar Entrada Express (${{ number_format($dailyPassPrice, 2) }})
                    </button>
                </form>
            </div>
        </div>

        <!-- COLUMNA DERECHA (65%): Historial de Transacciones & Cobros -->
        <div class="xl:col-span-8 flex flex-col gap-6">
            <div class="glass-card rounded-[24px] p-6 lg:p-8 flex flex-col gap-6 shadow-xl border border-outline-variant/10">
                
                <!-- Barra de Búsqueda & Pestañas de Filtro -->
                <div class="flex flex-col lg:flex-row justify-between gap-4 border-b border-outline-variant/10 pb-5">
                    <form method="GET" action="{{ route('pagos') }}" class="relative w-full lg:w-5/12">
                        @if(request('filter'))
                            <input type="hidden" name="filter" value="{{ request('filter') }}">
                        @endif
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[18px]">search</span>
                        <input type="text" name="q" id="transaction-search-input" value="{{ request('q') }}" 
                               onkeyup="filterTransactionsTable(this.value)"
                               class="w-full bg-surface-container-lowest text-on-surface font-body-md pl-10 pr-8 py-2.5 rounded-xl focus:outline-none border border-outline-variant/20 focus:border-primary/50 text-xs shadow-inner" placeholder="Buscar por socio, cédula, voucher o plan...">
                        @if(request('q'))
                            <a href="{{ route('pagos', request()->except('q', 'page')) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-error">
                                <span class="material-symbols-outlined text-[15px]">close</span>
                            </a>
                        @endif
                    </form>
                    
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('pagos') }}" class="px-3 py-1.5 rounded-xl font-etiqueta-bold text-[10px] uppercase tracking-wider transition-colors {{ $filter === 'all' ? 'bg-primary text-on-primary' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
                            Todos (<span id="tab-all-count">{{ $tabAllCount }}</span>)
                        </a>
                        <a href="{{ route('pagos', ['filter' => 'efectivo', 'q' => request('q')]) }}" class="px-3 py-1.5 rounded-xl font-etiqueta-bold text-[10px] uppercase tracking-wider transition-colors {{ $filter === 'efectivo' ? 'bg-[#4ade80] text-black font-bold' : 'bg-surface-container hover:bg-surface-container-high text-[#4ade80]' }}">
                            Efectivo (<span id="tab-cash-count">{{ $tabCashCount }}</span>)
                        </a>
                        <a href="{{ route('pagos', ['filter' => 'transferencia', 'q' => request('q')]) }}" class="px-3 py-1.5 rounded-xl font-etiqueta-bold text-[10px] uppercase tracking-wider transition-colors {{ $filter === 'transferencia' ? 'bg-[#60a5fa] text-black font-bold' : 'bg-surface-container hover:bg-surface-container-high text-[#60a5fa]' }}">
                            Transf. (<span id="tab-transfer-count">{{ $tabTransferCount }}</span>)
                        </a>
                        <a href="{{ route('pagos', ['filter' => 'debt', 'q' => request('q')]) }}" class="px-3 py-1.5 rounded-xl font-etiqueta-bold text-[10px] uppercase tracking-wider transition-colors flex items-center gap-1 {{ $filter === 'debt' ? 'bg-error text-white font-bold' : 'bg-surface-container hover:bg-surface-container-high text-error' }}">
                            <span class="material-symbols-outlined text-[13px]">warning</span> Por Cobrar (<span id="tab-debt-count">{{ $tabDebtCount }}</span>)
                        </a>
                        <a href="{{ route('pagos', ['filter' => 'daily_passes', 'q' => request('q')]) }}" class="px-3 py-1.5 rounded-xl font-etiqueta-bold text-[10px] uppercase tracking-wider transition-colors {{ $filter === 'daily_passes' ? 'bg-[#fb923c] text-black font-bold' : 'bg-surface-container hover:bg-surface-container-high text-[#fb923c]' }}">
                            🎟️ Pases (<span id="tab-dailypasses-count">{{ $tabDailyPassesCount }}</span>)
                        </a>
                    </div>
                </div>

                <!-- Tabla de Transacciones -->
                <div class="overflow-x-auto relative">
                    @if($filter === 'daily_passes')
                        <!-- Tabla de Pases Express Diarios -->
                        <table class="w-full text-left border-collapse min-w-[750px]" id="daily-passes-table">
                            <thead>
                                <tr class="border-b border-outline-variant/20 text-on-surface-variant font-etiqueta-bold text-[10px] uppercase tracking-widest">
                                    <th class="py-3 px-4">Fecha / Hora</th>
                                    <th class="py-3 px-4">Visitante</th>
                                    <th class="py-3 px-4">Concepto</th>
                                    <th class="py-3 px-4">Método</th>
                                    <th class="py-3 px-4 text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody id="daily-passes-table-body" class="font-body-md text-on-surface text-xs divide-y divide-outline-variant/10">
                                @forelse($dailyPasses as $dp)
                                    <tr class="hover:bg-surface-container/40 transition-colors transaction-row">
                                        <td class="py-3.5 px-4 font-mono text-on-surface-variant">{{ \Carbon\Carbon::parse($dp->created_at)->format('d/m/Y - H:i') }}</td>
                                        <td class="py-3.5 px-4 font-bold text-on-surface search-target">{{ $dp->guest_name }}</td>
                                        <td class="py-3.5 px-4 text-primary font-bold search-target">Pase Express 1 Día</td>
                                        <td class="py-3.5 px-4">
                                            <span class="inline-flex items-center gap-1 text-[#4ade80] bg-[#4ade80]/10 px-2 py-0.5 rounded font-etiqueta-bold text-[10px] uppercase">
                                                {{ $dp->paymentMethod->name ?? 'Efectivo' }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-right font-titular-md text-[15px] font-bold text-[#4ade80]">${{ number_format($dp->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="py-8 text-center text-on-surface-variant">No hay pases diarios registrados.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if($dailyPasses->hasPages())
                            <div class="pt-4">{{ $dailyPasses->links() }}</div>
                        @endif
                    @else
                        <!-- Tabla Principal de Pagos y Membresías -->
                        <table class="w-full text-left border-collapse min-w-[800px]" id="payments-main-table">
                            <thead>
                                <tr class="border-b border-outline-variant/20 text-on-surface-variant font-etiqueta-bold text-[10px] uppercase tracking-widest">
                                    <th class="py-3 px-4 font-medium">Fecha / Hora</th>
                                    <th class="py-3 px-4 font-medium">Cliente / Atleta</th>
                                    <th class="py-3 px-4 font-medium">Plan / Concepto</th>
                                    <th class="py-3 px-4 font-medium">Método</th>
                                    <th class="py-3 px-4 font-medium">Comprobante</th>
                                    <th class="py-3 px-4 font-medium">Estado</th>
                                    <th class="py-3 px-4 font-medium text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="payments-table-body" class="font-body-md text-on-surface text-xs divide-y divide-outline-variant/10">
                                @forelse($payments as $p)
                                    @php
                                        $firstClient = $p->membership ? $p->membership->clients->first() : null;
                                        $clientName = $firstClient ? ($firstClient->name . ' ' . $firstClient->last_name) : ($p->billing_name ?: 'Atleta');
                                        $hasDebt = (float)$p->pending_balance > 0;
                                        $isCash = str_contains(strtolower($p->paymentMethod->name ?? ''), 'efectivo');
                                        $invoiceLink = $firstClient ? route('clientes.invoice', ['client' => $firstClient->id, 'payment_id' => $p->id, 'autoprint' => 1]) : route('pagos.invoice', ['payment' => $p->id, 'autoprint' => 1]);
                                    @endphp
                                    <tr class="hover:bg-surface-container/40 transition-colors group transaction-row {{ $hasDebt ? 'bg-error/5' : '' }}">
                                        <!-- Fecha -->
                                        <td class="py-3.5 px-4 text-on-surface-variant font-mono">
                                            {{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y - H:i') }}
                                        </td>

                                        <!-- Cliente -->
                                        <td class="py-3.5 px-4">
                                            @if($firstClient)
                                                <button type="button" onclick="openClientHistoryModal({{ $firstClient->id }}, '{{ addslashes($clientName) }}')" class="font-bold text-on-surface hover:text-primary transition-colors text-left flex items-center gap-1 group/btn" title="Ver Historial de Pagos">
                                                    <span>{{ $clientName }}</span>
                                                    <span class="material-symbols-outlined text-[14px] opacity-0 group-hover/btn:opacity-100 transition-opacity text-primary">history</span>
                                                </button>
                                                <div class="text-[10px] text-on-surface-variant font-mono search-target">C.I. {{ $firstClient->id_card }}</div>
                                            @else
                                                <div class="font-bold text-on-surface search-target">{{ $clientName }}</div>
                                            @endif
                                        </td>

                                        <!-- Plan / Concepto -->
                                        <td class="py-3.5 px-4">
                                            <div class="font-bold text-on-surface search-target">{{ $p->membership && $p->membership->plan ? $p->membership->plan->name : 'Membresía' }}</div>
                                            <div class="text-[11px] text-primary font-mono font-bold">${{ number_format($p->amount, 2) }} cobrados</div>
                                        </td>

                                        <!-- Método -->
                                        <td class="py-3.5 px-4">
                                            @if($isCash)
                                                <span class="inline-flex items-center gap-1 text-[#4ade80] bg-[#4ade80]/10 border border-[#4ade80]/20 px-2 py-0.5 rounded font-etiqueta-bold text-[10px] uppercase">
                                                    <span class="material-symbols-outlined text-[12px]">payments</span> Efectivo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[#60a5fa] bg-[#60a5fa]/10 border border-[#60a5fa]/20 px-2 py-0.5 rounded font-etiqueta-bold text-[10px] uppercase">
                                                    <span class="material-symbols-outlined text-[12px]">account_balance</span> Transf.
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Comprobante -->
                                        <td class="py-3.5 px-4 font-mono text-on-surface-variant text-[11px] search-target">
                                            {{ $p->voucher_number ? '#' . $p->voucher_number : '---' }}
                                        </td>

                                        <!-- Estado -->
                                        <td class="py-3.5 px-4">
                                            @if(!$hasDebt)
                                                <span class="text-[#4ade80] font-etiqueta-bold text-[10px] uppercase tracking-wider flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[13px]">check_circle</span> Completado
                                                </span>
                                            @else
                                                <div class="flex flex-col">
                                                    <span class="text-[#facc15] font-etiqueta-bold text-[9px] uppercase tracking-wider flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-[12px]">schedule</span> Abono Parcial
                                                    </span>
                                                    <span class="text-error font-mono font-bold text-[10px]">Debe ${{ number_format($p->pending_balance, 2) }}</span>
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Acciones Rápidas -->
                                        <td class="py-3.5 px-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                @if($hasDebt && $p->membership)
                                                    <button type="button" 
                                                            onclick="openLiquidateDebtModal({{ $p->membership->id }}, '{{ addslashes($clientName) }}', {{ $p->pending_balance }}, '{{ addslashes($p->membership->plan->name ?? '') }}')"
                                                            class="bg-error hover:bg-error/80 text-white px-2.5 py-1 rounded-lg font-etiqueta-bold text-[10px] uppercase tracking-wider shadow-sm transition-all flex items-center gap-1"
                                                            title="Cobrar y Liquidar Saldo">
                                                        <span class="material-symbols-outlined text-[13px]">price_check</span> Liquidar
                                                    </button>
                                                @endif

                                                @if($firstClient)
                                                    <!-- Botón Historial de Pagos -->
                                                    <button type="button" onclick="openClientHistoryModal({{ $firstClient->id }}, '{{ addslashes($clientName) }}')"
                                                            class="p-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors" title="Ver Historial de Pagos">
                                                        <span class="material-symbols-outlined text-[16px]">history</span>
                                                    </button>
                                                @endif

                                                <!-- Botón Impresión Directa del Recibo -->
                                                <a href="{{ $invoiceLink }}" target="_blank"
                                                   class="p-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors inline-flex items-center" title="Imprimir Recibo">
                                                    <span class="material-symbols-outlined text-[16px]">print</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center text-on-surface-variant">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <span class="material-symbols-outlined text-[36px] text-outline-variant">folder_off</span>
                                                <span>No se encontraron transacciones registradas con los filtros actuales.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if($payments->hasPages())
                            <div class="pt-4 border-t border-outline-variant/10">
                                {{ $payments->links() }}
                            </div>
                        @endif
                    @endif
                </div>

            </div>
        </div>

    </div>
    @endif
</div>

<!-- MODAL: Generar Arqueo y Cierre de Caja Oficial (Conteo Físico Real de Gaveta) -->
<div id="arqueoConfigModal" class="fixed inset-0 z-[115] hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-background/85 backdrop-blur-md" onclick="closeArqueoModal()"></div>
    <div class="bg-surface-container-low rounded-[28px] shadow-2xl border border-outline-variant/20 overflow-hidden flex flex-col w-[95%] max-w-2xl max-h-[92vh] relative z-10 animate-fade-in">
        
        <!-- Header -->
        <div class="p-5 pb-4 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container-high/50 sticky top-0 z-20 backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary border border-primary/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[24px]">receipt_long</span>
                </div>
                <div>
                    <h3 class="font-titular-md text-base font-extrabold uppercase text-on-surface">Cierre y Arqueo Diario Oficial</h3>
                    <p class="font-body-md text-xs text-on-surface-variant">Conteo Físico de Gaveta vs Saldo Teórico del Sistema (05:00 – 22:00)</p>
                </div>
            </div>
            <button type="button" onclick="closeArqueoModal()" class="text-on-surface-variant hover:text-on-surface p-1.5 rounded-lg hover:bg-surface-container-highest transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Formulario con Scroll Interno -->
        <form id="arqueoGenerateForm" onsubmit="generateOfficialArqueo(event)" class="p-6 overflow-y-auto flex flex-col gap-5">
            
            <!-- Resumen Teórico del Sistema -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 p-3.5 bg-surface-container rounded-2xl border border-outline-variant/10 text-center font-body-md">
                <div class="flex flex-col">
                    <span class="text-[9px] uppercase font-bold text-on-surface-variant tracking-wider">Fondo Base</span>
                    <span class="font-bold font-mono text-xs text-on-surface" id="kpi-modal-fondo-display">$50.00</span>
                </div>
                <div class="flex flex-col border-l border-outline-variant/10">
                    <span class="text-[9px] uppercase font-bold text-on-surface-variant tracking-wider">Efectivo Sistema</span>
                    <span class="font-bold font-mono text-xs text-[#4ade80]">${{ number_format($todayCash, 2) }}</span>
                </div>
                <div class="flex flex-col border-l border-outline-variant/10">
                    <span class="text-[9px] uppercase font-bold text-on-surface-variant tracking-wider">Transf. Bancos</span>
                    <span class="font-bold font-mono text-xs text-[#60a5fa]">${{ number_format($todayTransfer, 2) }}</span>
                </div>
                <div class="flex flex-col border-l border-outline-variant/10">
                    <span class="text-[9px] uppercase font-bold text-primary tracking-wider">Esperado en Gaveta</span>
                    <span class="font-bold font-mono text-xs text-primary" id="kpi-modal-esperado-gaveta">${{ number_format($todayCash + 50.00, 2) }}</span>
                </div>
            </div>

            <!-- Parámetros Básicos: Fondo Base & Fecha -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1">
                    <label class="font-etiqueta-bold text-xs uppercase text-on-surface tracking-wider flex justify-between items-center">
                        <span>1. Fondo Base Inicial ($)</span>
                        <span class="text-primary text-[10px] font-mono font-bold">Apertura</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant font-mono font-bold text-xs">$</span>
                        <input type="number" step="0.50" min="0" id="arqueo_fondo_base" name="fondo_base" value="50.00" required
                               oninput="recalculateArqueoPhysics()"
                               class="w-full bg-surface-container border border-outline-variant/30 rounded-xl pl-7 pr-3 py-2 text-on-surface font-mono font-bold text-xs focus:border-primary focus:outline-none">
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="font-etiqueta-bold text-xs uppercase text-on-surface tracking-wider">2. Fecha de Operación</label>
                    <input type="date" id="arqueo_date" name="date" value="{{ now()->toDateString() }}" required
                           class="w-full bg-surface-container border border-outline-variant/30 rounded-xl px-3 py-2 text-on-surface font-body-md text-xs focus:border-primary focus:outline-none">
                </div>
            </div>

            <!-- SECCIÓN CONTEO FÍSICO REAL DE GAVETA (BILLETES Y MONEDAS) -->
            <div class="bg-surface-container/60 border border-outline-variant/20 p-4 rounded-2xl flex flex-col gap-3">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-outline-variant/10 pb-2.5">
                    <div>
                        <h4 class="font-titular-md text-xs font-bold uppercase text-on-surface flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-primary">attach_money</span>
                            3. Conteo Físico Real de Gaveta (Billetes y Monedas)
                        </h4>
                        <p class="text-[10px] text-on-surface-variant">Ingresa la cantidad física exacta contada en caja para calcular el balance.</p>
                    </div>

                    <!-- Botones de Prueba y Simulación Rápida -->
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <button type="button" onclick="autoFillArqueoCuadrado()" class="px-2.5 py-1 rounded-lg bg-[#4ade80]/15 text-[#4ade80] hover:bg-[#4ade80]/25 text-[10px] font-bold uppercase tracking-wider flex items-center gap-1 transition-all" title="Llenar conteo con la cantidad exacta esperada">
                            <span class="material-symbols-outlined text-[12px]">check_circle</span> Cuadrar Auto
                        </button>
                        <button type="button" onclick="simulateArqueoFaltante()" class="px-2.5 py-1 rounded-lg bg-error/15 text-error hover:bg-error/25 text-[10px] font-bold uppercase tracking-wider flex items-center gap-1 transition-all" title="Simular $5.00 menos en caja física">
                            <span class="material-symbols-outlined text-[12px]">trending_down</span> Faltante (-$5)
                        </button>
                        <button type="button" onclick="simulateArqueoSobrante()" class="px-2.5 py-1 rounded-lg bg-[#facc15]/15 text-[#facc15] hover:bg-[#facc15]/25 text-[10px] font-bold uppercase tracking-wider flex items-center gap-1 transition-all" title="Simular $10.00 más en caja física">
                            <span class="material-symbols-outlined text-[12px]">trending_up</span> Sobrante (+$10)
                        </button>
                        <button type="button" onclick="clearArqueoPhysics()" class="p-1 rounded-lg bg-surface-container-high text-on-surface-variant hover:text-on-surface text-[10px]" title="Limpiar conteo a 0">
                            <span class="material-symbols-outlined text-[14px]">refresh</span>
                        </button>
                    </div>
                </div>

                <!-- Grilla de Billetes y Monedas (Estándar Comercial de Ecuador) -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs font-body-md">
                    <!-- Billete $20.00 (Máxima denominación estándar en Ecuador) -->
                    <div class="bg-surface-container p-2.5 rounded-xl border border-primary/25 shadow-sm flex flex-col gap-1 relative">
                        <div class="flex justify-between text-[10px] font-bold text-on-surface">
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-primary text-[13px]">payments</span> Billete $20</span>
                            <span class="font-mono text-primary font-bold" id="sub_b20">$0.00</span>
                        </div>
                        <input type="number" min="0" id="arq_b20" name="b20" value="0" oninput="recalculateArqueoPhysics()" class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-lg px-2 py-1 text-on-surface font-mono font-bold text-xs text-center focus:border-primary focus:outline-none" placeholder="0">
                    </div>

                    <!-- Billete $10.00 -->
                    <div class="bg-surface-container p-2.5 rounded-xl border border-outline-variant/15 flex flex-col gap-1">
                        <div class="flex justify-between text-[10px] font-bold text-on-surface-variant">
                            <span>Billete $10</span>
                            <span class="font-mono text-on-surface" id="sub_b10">$0.00</span>
                        </div>
                        <input type="number" min="0" id="arq_b10" name="b10" value="0" oninput="recalculateArqueoPhysics()" class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-lg px-2 py-1 text-on-surface font-mono font-bold text-xs text-center focus:border-primary focus:outline-none" placeholder="0">
                    </div>

                    <!-- Billete $5.00 -->
                    <div class="bg-surface-container p-2.5 rounded-xl border border-outline-variant/15 flex flex-col gap-1">
                        <div class="flex justify-between text-[10px] font-bold text-on-surface-variant">
                            <span>Billete $5</span>
                            <span class="font-mono text-on-surface" id="sub_b5">$0.00</span>
                        </div>
                        <input type="number" min="0" id="arq_b5" name="b5" value="0" oninput="recalculateArqueoPhysics()" class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-lg px-2 py-1 text-on-surface font-mono font-bold text-xs text-center focus:border-primary focus:outline-none" placeholder="0">
                    </div>

                    <!-- Billete $1.00 -->
                    <div class="bg-surface-container p-2.5 rounded-xl border border-outline-variant/15 flex flex-col gap-1">
                        <div class="flex justify-between text-[10px] font-bold text-on-surface-variant">
                            <span>Billete $1</span>
                            <span class="font-mono text-on-surface" id="sub_b1">$0.00</span>
                        </div>
                        <input type="number" min="0" id="arq_b1" name="b1" value="0" oninput="recalculateArqueoPhysics()" class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-lg px-2 py-1 text-on-surface font-mono font-bold text-xs text-center focus:border-primary focus:outline-none" placeholder="0">
                    </div>

                    <!-- Monedas Fraccionarias ($ total en monedas de 1¢ a $1.00) -->
                    <div class="col-span-2 sm:col-span-4 bg-surface-container p-3 rounded-xl border border-outline-variant/15 flex flex-col gap-2">
                        <div class="flex justify-between items-center text-[10px] font-bold text-on-surface-variant">
                            <span class="flex items-center gap-1.5 text-on-surface">
                                <span class="material-symbols-outlined text-[15px] text-[#facc15]">monetization_on</span>
                                <span>Monedas Fraccionarias ($ total contadas)</span>
                            </span>
                            <span class="font-mono font-bold text-on-surface text-xs" id="sub_coins">$0.00</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant font-mono text-xs">$</span>
                                <input type="number" step="0.01" min="0" id="arq_coins" name="coins" value="0.00" oninput="recalculateArqueoPhysics()" class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-lg pl-6 pr-3 py-1.5 text-on-surface font-mono font-bold text-xs focus:border-primary focus:outline-none" placeholder="0.00">
                            </div>
                            
                            <!-- Botones de Suma Rápida de Monedas -->
                            <div class="flex items-center gap-1 shrink-0">
                                <button type="button" onclick="addCoinValue(0.25)" class="px-2 py-1 rounded bg-surface-container-high hover:bg-surface-container-highest text-[10px] font-mono text-on-surface font-bold transition-all" title="Sumar 25 centavos">+0.25</button>
                                <button type="button" onclick="addCoinValue(0.50)" class="px-2 py-1 rounded bg-surface-container-high hover:bg-surface-container-highest text-[10px] font-mono text-on-surface font-bold transition-all" title="Sumar 50 centavos">+0.50</button>
                                <button type="button" onclick="addCoinValue(1.00)" class="px-2 py-1 rounded bg-surface-container-high hover:bg-surface-container-highest text-[10px] font-mono text-on-surface font-bold transition-all" title="Sumar 1 dólar metálico">+1.00</button>
                                <button type="button" onclick="document.getElementById('arq_coins').value='0.00'; recalculateArqueoPhysics();" class="p-1 rounded bg-surface-container-high hover:text-error text-[10px] text-on-surface-variant" title="Reiniciar monedas"><span class="material-symbols-outlined text-[13px]">backspace</span></button>
                            </div>
                        </div>
                        
                        <p class="text-[9.5px] text-on-surface-variant flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px] text-primary">info</span>
                            <span><strong>¿Qué es monedas fraccionarias?</strong> Es el total en dinero de las monedas metálicas de la gaveta (1¢, 5¢, 10¢, 25¢, 50¢ y $1.00).</span>
                        </p>
                    </div>

                    <!-- Acordeón / Desplegable para Billetes Especiales de $50 y $100 -->
                    <div class="col-span-2 sm:col-span-4">
                        <details class="group bg-surface-container/40 rounded-xl border border-outline-variant/10 p-2 text-xs">
                            <summary class="cursor-pointer font-etiqueta-bold text-[10px] uppercase text-on-surface-variant flex items-center justify-between select-none">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">tune</span>
                                    <span>Billetes Especiales de $50 / $100 (Casos Excepcionales)</span>
                                </span>
                                <span class="material-symbols-outlined text-[16px] group-open:rotate-180 transition-transform">expand_more</span>
                            </summary>
                            <div class="grid grid-cols-2 gap-2.5 mt-2.5 pt-2 border-t border-outline-variant/10">
                                <!-- $100 -->
                                <div class="bg-surface-container p-2 rounded-lg border border-outline-variant/15 flex flex-col gap-1">
                                    <div class="flex justify-between text-[10px] font-bold text-on-surface-variant">
                                        <span>Billete $100</span>
                                        <span class="font-mono text-on-surface" id="sub_b100">$0.00</span>
                                    </div>
                                    <input type="number" min="0" id="arq_b100" name="b100" value="0" oninput="recalculateArqueoPhysics()" class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-lg px-2 py-1 text-on-surface font-mono font-bold text-xs text-center focus:border-primary focus:outline-none">
                                </div>
                                <!-- $50 -->
                                <div class="bg-surface-container p-2 rounded-lg border border-outline-variant/15 flex flex-col gap-1">
                                    <div class="flex justify-between text-[10px] font-bold text-on-surface-variant">
                                        <span>Billete $50</span>
                                        <span class="font-mono text-on-surface" id="sub_b50">$0.00</span>
                                    </div>
                                    <input type="number" min="0" id="arq_b50" name="b50" value="0" oninput="recalculateArqueoPhysics()" class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-lg px-2 py-1 text-on-surface font-mono font-bold text-xs text-center focus:border-primary focus:outline-none">
                                </div>
                            </div>
                        </details>
                    </div>
                </div>

                <!-- TARJETA EN VIVO DE CONCILIACIÓN Y BALANCE -->
                <div class="bg-surface-container-lowest border border-outline-variant/25 p-3.5 rounded-xl flex flex-col sm:flex-row justify-between items-center gap-3">
                    <div class="flex items-center gap-4 text-xs">
                        <div class="flex flex-col">
                            <span class="text-[9px] uppercase font-bold text-on-surface-variant">Físico Contado:</span>
                            <span class="font-bold font-mono text-sm text-on-surface" id="arq_total_fisico_num">$58.00</span>
                        </div>
                        <div class="text-on-surface-variant font-bold">−</div>
                        <div class="flex flex-col">
                            <span class="text-[9px] uppercase font-bold text-on-surface-variant">Teórico Esperado:</span>
                            <span class="font-bold font-mono text-sm text-on-surface" id="arq_total_esperado_num">$58.00</span>
                        </div>
                        <div class="text-on-surface-variant font-bold">=</div>
                        <div class="flex flex-col">
                            <span class="text-[9px] uppercase font-bold text-on-surface-variant">Diferencia:</span>
                            <span class="font-bold font-mono text-base text-[#4ade80]" id="arq_diff_num">$0.00</span>
                        </div>
                    </div>

                    <div id="arq_status_badge">
                        <span class="inline-flex items-center gap-1 text-[#4ade80] bg-[#4ade80]/15 border border-[#4ade80]/30 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[14px]">check_circle</span> CUADRADO
                        </span>
                    </div>
                </div>
            </div>

            <!-- Campo 4: Observaciones / Notas de Auditoría -->
            <div class="flex flex-col gap-1.5">
                <label class="font-etiqueta-bold text-xs uppercase text-on-surface tracking-wider flex justify-between items-center">
                    <span>4. Observaciones / Justificación de Auditoría</span>
                    <span class="text-[10px] text-on-surface-variant font-normal">Editable</span>
                </label>
                <textarea id="arqueo_observations" name="observations" rows="2"
                          oninput="this.dataset.userEdited = 'true'"
                          class="w-full bg-surface-container border border-outline-variant/30 rounded-xl px-3.5 py-2 text-on-surface font-body-md text-xs focus:border-primary focus:outline-none"
                          placeholder="Notas sobre el cierre, motivo de inconsistencias o verificación de transferencias..."></textarea>
            </div>

            <!-- Campo 5: Clave de Administrador -->
            <div class="flex flex-col gap-1.5">
                <label class="font-etiqueta-bold text-xs uppercase text-on-surface tracking-wider flex items-center justify-between">
                    <span>5. Clave / PIN del Administrador</span>
                    <span class="text-error text-[10px] font-bold flex items-center gap-0.5">
                        <span class="material-symbols-outlined text-[13px]">lock</span> Requerido
                    </span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant material-symbols-outlined text-[18px]">key</span>
                    <input type="password" id="arqueo_admin_pin" name="admin_pin" required
                           class="w-full bg-surface-container border border-outline-variant/30 rounded-xl pl-10 pr-10 py-2.5 text-on-surface font-body-md text-xs focus:border-primary focus:outline-none"
                           placeholder="Ingrese su clave de administrador">
                    <button type="button" onclick="toggleArqueoPinVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface">
                        <span id="arqueo_pin_eye" class="material-symbols-outlined text-[18px]">visibility</span>
                    </button>
                </div>
                <div id="arqueo_error_msg" class="hidden text-xs text-error font-semibold bg-error/10 border border-error/20 p-2 rounded-lg mt-1 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">error</span>
                    <span id="arqueo_error_text"></span>
                </div>
            </div>

            <!-- Acciones -->
            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeArqueoModal()" class="flex-1 px-4 py-3 rounded-xl bg-surface-container-high text-on-surface font-etiqueta-bold uppercase text-xs hover:bg-surface-container-highest transition-colors">
                    Cancelar
                </button>
                <button type="submit" id="btn-submit-arqueo" class="flex-1 bg-primary text-on-primary font-titular-md text-xs uppercase tracking-wider py-3 rounded-xl shadow-[0_4px_15px_rgba(227,27,35,0.3)] hover:brightness-110 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">print</span> Guardar y Generar PDF
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Cobro Exitoso / Aprobado (Mensaje de Interacción Claro) -->
<div id="paymentSuccessModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-background/85 backdrop-blur-md" onclick="closeSuccessModal()"></div>
    <div class="bg-surface-container-low rounded-[28px] shadow-2xl border border-[#4ade80]/30 overflow-hidden flex flex-col w-[92%] max-w-md relative z-10 animate-fade-in p-6 text-center">
        
        <div class="w-16 h-16 rounded-full bg-[#4ade80]/15 text-[#4ade80] border border-[#4ade80]/30 flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-[34px]">check_circle</span>
        </div>

        <h2 class="font-titular-xl text-[22px] text-on-surface font-extrabold uppercase" style="font-family: 'Montserrat', sans-serif;">¡Pago Aprobado con Éxito!</h2>
        <p class="font-body-md text-xs text-on-surface-variant mt-1" id="success-modal-desc">La transacción ha sido registrada y procesada correctamente en caja.</p>

        <div class="bg-surface-container p-4 rounded-2xl border border-outline-variant/10 flex flex-col gap-2 text-xs mt-5 text-left font-body-md">
            <div class="flex justify-between"><span class="text-on-surface-variant">Operación:</span> <strong class="text-on-surface" id="success-concept">---</strong></div>
            <div class="flex justify-between"><span class="text-on-surface-variant">Monto Cobrado:</span> <strong class="text-[#4ade80] font-mono text-base" id="success-amount">$0.00</strong></div>
            <div class="flex justify-between"><span class="text-on-surface-variant">Método:</span> <span class="font-bold text-on-surface" id="success-method">---</span></div>
            <div class="flex justify-between" id="success-debt-row"><span class="text-on-surface-variant">Saldo Restante:</span> <span class="font-bold text-error font-mono" id="success-debt">$0.00</span></div>
        </div>

        <div class="flex gap-3 mt-6">
            <a id="btn-success-print" href="#" target="_blank" class="flex-1 bg-primary text-on-primary font-titular-md text-xs uppercase tracking-wider py-3 rounded-xl shadow-md hover:brightness-110 transition-all flex items-center justify-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">print</span> Imprimir Recibo
            </a>
            <button type="button" onclick="closeSuccessModal()" class="px-5 py-3 rounded-xl bg-surface-container-high text-on-surface font-etiqueta-bold uppercase text-xs hover:bg-surface-container-highest transition-colors">
                Listo
            </button>
        </div>
    </div>
</div>

<!-- MODAL: Liquidación Rápida de Deuda -->
<div id="liquidateModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm" onclick="closeLiquidateDebtModal()"></div>
    <div class="bg-surface-container-low rounded-[24px] shadow-2xl border border-outline-variant/20 overflow-hidden flex flex-col w-[92%] max-w-md relative z-10 animate-fade-in">
        
        <div class="p-6 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container relative">
            <div class="absolute top-0 right-0 w-28 h-28 bg-error/10 rounded-bl-full blur-xl pointer-events-none"></div>
            <div>
                <h2 class="font-titular-xl text-[20px] text-on-surface font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-error">price_check</span> Liquidar Saldo Pendiente
                </h2>
                <p id="liquidate-client-name" class="font-body-md text-on-surface-variant text-xs mt-0.5">Atleta</p>
            </div>
            <button type="button" onclick="closeLiquidateDebtModal()" class="w-8 h-8 rounded-full hover:bg-surface-container-high flex items-center justify-center text-on-surface-variant">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <form id="liquidate-debt-form" onsubmit="handleLiquidateDebtSubmit(event)" class="p-6 flex flex-col gap-4">
            @csrf
            <input type="hidden" id="liquidate-membership-id">

            <div class="bg-error/10 p-3.5 rounded-xl border border-error/30 flex flex-col gap-1.5 text-xs">
                <div class="flex justify-between"><span class="text-on-surface-variant">Plan Asociado:</span> <span id="liquidate-plan-name" class="font-bold text-on-surface">---</span></div>
                <div class="flex justify-between items-baseline"><span class="text-on-surface-variant">Total Saldo Adeudado:</span> <span id="liquidate-debt-display" class="font-bold text-error text-[18px] font-mono">$0.00</span></div>
            </div>

            <!-- Monto a Liquidar -->
            <div class="flex flex-col gap-1">
                <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px]">Monto a Recibir Hoy ($) <span class="text-error font-bold">*</span></label>
                <input type="number" step="0.01" id="liquidate-amount-input" class="w-full bg-surface-container-lowest text-on-surface font-titular-md text-[18px] px-3.5 py-2.5 rounded-xl border border-outline-variant/20 focus:outline-none focus:border-primary/50 font-bold" required>
            </div>

            <!-- Método de Pago -->
            <div class="grid grid-cols-2 gap-2">
                <label class="flex items-center gap-2 bg-surface-container-lowest p-2.5 rounded-lg border border-outline-variant/15 cursor-pointer text-xs">
                    <input type="radio" name="liquidate_payment_method" value="efectivo" checked onchange="toggleLiquidateTransfer(false)" class="text-primary"> Efectivo 💵
                </label>
                <label class="flex items-center gap-2 bg-surface-container-lowest p-2.5 rounded-lg border border-outline-variant/15 cursor-pointer text-xs">
                    <input type="radio" name="liquidate_payment_method" value="transferencia" onchange="toggleLiquidateTransfer(true)" class="text-primary"> Transf. 🏦
                </label>
            </div>

            <!-- Voucher Transferencia -->
            <div id="liquidate-voucher-box" class="hidden flex-col gap-1 animate-fade-in">
                <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px]">N° de Comprobante / Voucher</label>
                <input type="text" id="liquidate-voucher-input" placeholder="Ej. 98127364" class="w-full bg-surface-container-lowest text-on-surface p-2.5 rounded-lg border border-outline-variant/20 text-xs font-mono">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeLiquidateDebtModal()" class="text-on-surface-variant px-4 py-2.5 font-etiqueta-bold text-xs uppercase hover:bg-surface-container rounded-xl">Cancelar</button>
                <button type="submit" id="btn-submit-liquidate" class="bg-error text-white px-6 py-2.5 rounded-xl font-titular-md text-xs uppercase tracking-wider shadow-sm hover:brightness-110 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">price_check</span> Registrar Liquidación
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Historial Completo de Pagos del Atleta -->
<div id="clientPaymentHistoryModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm" onclick="closeClientHistoryModal()"></div>
    <div class="bg-surface-container-low rounded-[24px] shadow-2xl border border-outline-variant/20 overflow-hidden flex flex-col w-[94%] max-w-3xl max-h-[90vh] relative z-10 animate-fade-in">
        
        <div class="p-6 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container">
            <div>
                <h2 class="font-titular-xl text-[20px] text-on-surface font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">history</span> Historial de Pagos del Atleta
                </h2>
                <p id="history-client-name" class="font-body-md text-on-surface-variant text-xs mt-0.5">Cargando datos...</p>
            </div>
            <button type="button" onclick="closeClientHistoryModal()" class="w-8 h-8 rounded-full hover:bg-surface-container-high flex items-center justify-center text-on-surface-variant">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex flex-col gap-4" id="history-content-body">
            <div class="flex items-center justify-center py-12 text-on-surface-variant" id="history-loading-spinner">
                <span class="material-symbols-outlined animate-spin text-[32px] text-primary">sync</span>
                <span class="ml-2 text-xs">Cargando historial de transacciones...</span>
            </div>
            
            <div id="history-data-container" class="hidden flex-col gap-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="bg-surface-container p-3 rounded-xl border border-outline-variant/10 flex flex-col">
                        <span class="text-[10px] text-on-surface-variant uppercase font-bold">Cédula de Identidad</span>
                        <span class="text-xs font-mono font-bold text-on-surface" id="history-client-idcard">---</span>
                    </div>
                    <div class="bg-surface-container p-3 rounded-xl border border-outline-variant/10 flex flex-col">
                        <span class="text-[10px] text-on-surface-variant uppercase font-bold">Teléfono / WhatsApp</span>
                        <span class="text-xs font-mono font-bold text-on-surface" id="history-client-phone">---</span>
                    </div>
                    <div class="bg-surface-container p-3 rounded-xl border border-outline-variant/10 flex flex-col">
                        <span class="text-[10px] text-on-surface-variant uppercase font-bold">Inversión Total Acumulada</span>
                        <span class="text-sm font-mono font-bold text-[#4ade80]" id="history-total-spent">$0.00</span>
                    </div>
                </div>

                <div class="overflow-x-auto border border-outline-variant/15 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-surface-container border-b border-outline-variant/20 text-on-surface-variant font-etiqueta-bold text-[10px] uppercase">
                                <th class="py-2.5 px-3">Fecha</th>
                                <th class="py-2.5 px-3">Plan / Concepto</th>
                                <th class="py-2.5 px-3">Método</th>
                                <th class="py-2.5 px-3">Voucher</th>
                                <th class="py-2.5 px-3">Monto</th>
                                <th class="py-2.5 px-3">Estado</th>
                                <th class="py-2.5 px-3 text-right">Recibo</th>
                            </tr>
                        </thead>
                        <tbody id="history-table-rows" class="divide-y divide-outline-variant/10 font-body-md">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-outline-variant/10 flex justify-end gap-2 bg-surface-container">
            <button type="button" onclick="closeClientHistoryModal()" class="px-5 py-2 rounded-xl bg-surface-container-high text-on-surface font-etiqueta-bold uppercase text-xs hover:bg-surface-container-highest transition-colors">Cerrar</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let searchDebounce = null;
    let groupSearchDebounce = null;
    let currentSelectedClient = null;
    let currentSelectedGroup = null;

    // --- CAMBIAR TIPO DE OPERACIÓN (INDIVIDUAL VS GRUPAL VS PASE EXPRESS) ---
    function switchOperationType(type) {
        const tabMem = document.getElementById('tab-op-membership');
        const tabGroup = document.getElementById('tab-op-group');
        const tabDaily = document.getElementById('tab-op-daily');
        
        const formMem = document.getElementById('cash-payment-form');
        const formGroup = document.getElementById('group-payment-form');
        const formDaily = document.getElementById('daily-pass-form');

        // Reset classes
        const activeClass = 'py-2 px-1.5 rounded-xl font-etiqueta-bold text-[10px] uppercase tracking-wider transition-all bg-primary text-on-primary shadow-sm flex items-center justify-center gap-1';
        const inactiveClass = 'py-2 px-1.5 rounded-xl font-etiqueta-bold text-[10px] uppercase tracking-wider transition-all bg-surface-container text-on-surface-variant hover:bg-surface-container-high flex items-center justify-center gap-1';

        tabMem.className = inactiveClass;
        tabGroup.className = inactiveClass;
        tabDaily.className = inactiveClass;

        formMem.classList.add('hidden');
        formMem.classList.remove('flex');
        formGroup.classList.add('hidden');
        formGroup.classList.remove('flex');
        formDaily.classList.add('hidden');
        formDaily.classList.remove('flex');

        if (type === 'daily') {
            tabDaily.className = activeClass;
            formDaily.classList.remove('hidden');
            formDaily.classList.add('flex');
            document.getElementById('daily-guest-name').focus();
        } else if (type === 'group') {
            tabGroup.className = activeClass;
            formGroup.classList.remove('hidden');
            formGroup.classList.add('flex');
            const searchInput = document.getElementById('live-group-search');
            searchInput.focus();
            if (!currentSelectedGroup) {
                handleGroupSearch('');
            }
        } else {
            tabMem.className = activeClass;
            formMem.classList.remove('hidden');
            document.getElementById('live-client-search').focus();
        }
    }

    function toggleDailyTransfer(isTransfer) {
        const voucherBox = document.getElementById('daily-transfer-voucher-box');
        if (isTransfer) {
            voucherBox.classList.remove('hidden');
            voucherBox.classList.add('flex');
            document.getElementById('daily-voucher-input').focus();
        } else {
            voucherBox.classList.add('hidden');
            voucherBox.classList.remove('flex');
        }
    }

    // =========================================================================
    // --- LÓGICA DE BÚSQUEDA Y COBRO DE PLANES GRUPALES CON DETALLE COMPLETO ---
    // =========================================================================

    function handleGroupSearch(val) {
        clearTimeout(groupSearchDebounce);
        const resultsBox = document.getElementById('group-search-results');
        const clearBtn = document.getElementById('btn-clear-group');
        if (!resultsBox) return;

        if (val.trim().length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        groupSearchDebounce = setTimeout(async () => {
            try {
                const res = await fetch(`/api/payments/group-debt?q=${encodeURIComponent(val)}`);
                const groups = await res.json();

                if (!Array.isArray(groups) || groups.length === 0) {
                    resultsBox.innerHTML = `
                        <div class="p-4 text-xs text-on-surface-variant text-center flex flex-col items-center gap-1.5">
                            <span class="material-symbols-outlined text-[24px] text-on-surface-variant/60">group_off</span>
                            <span class="font-bold text-on-surface">No se encontraron grupos con ese nombre o integrante.</span>
                            <a href="{{ route('clientes.create.group') }}" class="text-primary font-bold text-[11px] hover:underline mt-1">Registrar un nuevo grupo &rarr;</a>
                        </div>
                    `;
                } else {
                    resultsBox.innerHTML = groups.map(g => {
                        const isExpired = g.is_expired;
                        const hasDebt = g.has_debt;
                        return `
                            <div onclick="selectGroupForPayment(${g.membership_id}, '${(g.group_name || '').replace(/'/g, "\\'")}')" 
                                 class="p-3 hover:bg-surface-container-highest cursor-pointer flex justify-between items-center text-xs transition-colors group/item">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-primary/20 text-primary flex items-center justify-center font-bold text-xs">
                                        <span class="material-symbols-outlined text-[18px]">groups</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="font-bold text-on-surface group-hover/item:text-primary transition-colors text-xs">${g.group_name}</span>
                                            <span class="bg-surface-container-highest text-on-surface text-[9px] px-1.5 py-0.2 rounded font-mono font-bold">${g.members_count} atletas</span>
                                            ${hasDebt ? '<span class="bg-error text-white text-[8px] px-1.5 py-0.2 rounded font-mono font-bold uppercase">Debe $' + parseFloat(g.debt_amount).toFixed(2) + '</span>' : ''}
                                            ${isExpired ? '<span class="bg-error/20 text-error text-[8px] px-1.5 py-0.2 rounded font-bold uppercase">Vencido</span>' : '<span class="bg-green-500/20 text-green-400 text-[8px] px-1.5 py-0.2 rounded font-bold uppercase">Activo</span>'}
                                        </div>
                                        <span class="text-on-surface-variant font-mono text-[10px] mt-0.5">
                                            Plan: <strong>${g.plan_name}</strong> ($${parseFloat(g.unit_price).toFixed(2)}/atleta) &middot; Total: <strong class="text-[#4ade80] font-bold">$${parseFloat(g.total_price).toFixed(2)}</strong>
                                        </span>
                                        <span class="text-[9.5px] text-on-surface-variant/80 italic line-clamp-1 mt-0.5">
                                            Integrantes: ${g.members_summary}
                                        </span>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-primary text-[18px] group-hover/item:translate-x-1 transition-transform">chevron_right</span>
                            </div>
                        `;
                    }).join('');
                }
                resultsBox.classList.remove('hidden');
            } catch (e) {
                console.error(e);
            }
        }, 150);
    }

    async function selectGroupForPayment(membershipId, groupName) {
        const resultsBox = document.getElementById('group-search-results');
        if (resultsBox) resultsBox.classList.add('hidden');
        hideGroupFormError();

        try {
            const res = await fetch(`/api/payments/group-debt?membership_id=${membershipId}`);
            const data = await res.json();

            if (res.ok && data.success) {
                currentSelectedGroup = data;
                const g = data.group;
                const members = data.members || [];

                document.getElementById('form-group-membership-id').value = g.membership_id;
                document.getElementById('form-group-plan-id').value = g.plan_id;
                document.getElementById('live-group-search').value = `${g.group_name} (${g.members_count} atletas)`;
                document.getElementById('btn-clear-group').classList.remove('hidden');

                // Header & Badges
                document.getElementById('group-card-name').textContent = g.group_name;
                document.getElementById('group-card-dates').textContent = `Vigencia: ${g.start_date} al ${g.end_date}`;
                
                const statusBadge = document.getElementById('group-card-status-badge');
                if (g.is_expired) {
                    statusBadge.textContent = 'Vencido';
                    statusBadge.className = 'px-2.5 py-0.5 rounded-full text-[9px] font-etiqueta-bold uppercase tracking-wider bg-error/15 text-error border border-error/30';
                } else {
                    statusBadge.textContent = `Activo (${g.days_left >= 0 ? g.days_left + ' días rest.' : 'Vence hoy'})`;
                    statusBadge.className = 'px-2.5 py-0.5 rounded-full text-[9px] font-etiqueta-bold uppercase tracking-wider bg-green-500/10 text-green-400 border border-green-500/20';
                }

                // Plan details
                document.getElementById('group-card-plan-name').textContent = g.plan_name;
                document.getElementById('group-card-unit-price').textContent = `$${parseFloat(g.unit_price).toFixed(2)} / persona`;

                // Render all members inside the group
                document.getElementById('group-card-members-count').textContent = g.members_count;
                const membersContainer = document.getElementById('group-card-members-list');
                membersContainer.innerHTML = members.map((m, idx) => `
                    <div class="flex items-center justify-between p-2 bg-surface-container rounded-lg border border-outline-variant/10 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-primary/20 text-primary text-[10px] font-bold flex items-center justify-center">${idx + 1}</span>
                            <span class="font-bold text-on-surface">${m.name} ${m.last_name}</span>
                        </div>
                        <div class="flex items-center gap-2 text-on-surface-variant font-mono text-[10px]">
                            <span>C.I. ${m.id_card}</span>
                            ${m.phone ? `<span class="text-on-surface-variant/60">&middot; ${m.phone}</span>` : ''}
                        </div>
                    </div>
                `).join('');

                // Financial summary
                document.getElementById('group-card-calc-formula').textContent = `${g.members_count} atletas × $${parseFloat(g.unit_price).toFixed(2)}`;
                document.getElementById('group-card-subtotal').textContent = `$${parseFloat(g.total_plan_price).toFixed(2)}`;

                const debtBox = document.getElementById('group-debt-alert-box');
                const debtRow = document.getElementById('group-card-debt-row');

                if (g.has_debt && g.debt_amount > 0) {
                    debtBox.classList.remove('hidden');
                    debtBox.classList.add('flex');
                    debtRow.classList.remove('hidden');
                    debtRow.classList.add('flex');

                    document.getElementById('group-debt-title').textContent = g.group_name;
                    document.getElementById('group-debt-amount-display').textContent = `$${parseFloat(g.debt_amount).toFixed(2)}`;
                    document.getElementById('group-debt-plan-name').textContent = g.plan_name;
                    document.getElementById('group-opt-only-debt-amount').textContent = `$${parseFloat(g.debt_amount).toFixed(2)}`;
                    document.getElementById('group-opt-renew-debt-amount').textContent = `$${parseFloat(g.total_renew_with_debt).toFixed(2)}`;
                    document.getElementById('group-card-debt-amount').textContent = `$${parseFloat(g.debt_amount).toFixed(2)}`;

                    // Default choice for debt: renew_with_debt or liquidate_only
                    const checkedRadio = document.querySelector('input[name="group_debt_action_choice"]:checked');
                    handleGroupDebtChoiceChange(checkedRadio ? checkedRadio.value : 'renew_with_debt');
                } else {
                    debtBox.classList.add('hidden');
                    debtBox.classList.remove('flex');
                    debtRow.classList.add('hidden');
                    debtRow.classList.remove('flex');

                    document.getElementById('form-group-action-type').value = 'group_payment';
                    document.getElementById('group-amount-input').value = g.total_plan_price.toFixed(2);
                    document.getElementById('group-card-total-amount').textContent = `$${g.total_plan_price.toFixed(2)}`;
                    document.getElementById('btn-group-submit-text').textContent = `Cobrar Plan Grupal ($${g.total_plan_price.toFixed(2)})`;
                    document.getElementById('group-concept-badge').textContent = 'Tarifa Oficial Grupo';
                }

                // Abono limits
                document.getElementById('group-abono-min-text').textContent = g.min_abono.toFixed(2);
                document.getElementById('group-abono-max-text').textContent = g.max_abono.toFixed(2);
                document.getElementById('group-abono-input').value = g.min_abono.toFixed(2);

                // Show details card
                const detailsCard = document.getElementById('group-details-card');
                detailsCard.classList.remove('hidden');
                detailsCard.classList.add('flex');

                showToast('info', `Grupo '${g.group_name}' cargado con ${g.members_count} atletas integrantes.`, 'Grupo Seleccionado');
            } else {
                showGroupFormError(data.message || 'No se pudo cargar la información del grupo.');
            }
        } catch (e) {
            console.error(e);
            showGroupFormError('Error al comunicarse con el servidor.');
        }
    }

    function resetGroupSelection() {
        currentSelectedGroup = null;
        document.getElementById('form-group-membership-id').value = '';
        document.getElementById('live-group-search').value = '';
        document.getElementById('btn-clear-group').classList.add('hidden');
        
        const resultsBox = document.getElementById('group-search-results');
        if (resultsBox) {
            resultsBox.classList.add('hidden');
            resultsBox.innerHTML = '';
        }

        const detailsCard = document.getElementById('group-details-card');
        if (detailsCard) {
            detailsCard.classList.add('hidden');
            detailsCard.classList.remove('flex');
        }

        const debtBox = document.getElementById('group-debt-alert-box');
        if (debtBox) {
            debtBox.classList.add('hidden');
            debtBox.classList.remove('flex');
        }

        document.getElementById('group-amount-input').value = '0.00';
        document.getElementById('btn-group-submit-text').textContent = 'Selecciona un grupo para cobrar';
        
        // Reset abono and transfer
        const radioCompleto = document.querySelector('input[name="group_payment_type"][value="completo"]');
        if (radioCompleto) radioCompleto.checked = true;
        toggleGroupAbono(false);

        const radioEfectivo = document.querySelector('input[name="group_payment_method"][value="efectivo"]');
        if (radioEfectivo) radioEfectivo.checked = true;
        toggleGroupTransfer(false);

        hideGroupFormError();
        document.getElementById('live-group-search').focus();
    }

    function handleGroupDebtChoiceChange(choice) {
        if (!currentSelectedGroup) return;
        const g = currentSelectedGroup.group;

        const amountInput = document.getElementById('group-amount-input');
        const submitText = document.getElementById('btn-group-submit-text');
        const paymentTypeBox = document.getElementById('group-payment-type-box');
        const cardTotal = document.getElementById('group-card-total-amount');

        if (choice === 'liquidate_only') {
            document.getElementById('form-group-action-type').value = 'liquidate_only';
            amountInput.value = g.debt_amount.toFixed(2);
            cardTotal.textContent = `$${g.debt_amount.toFixed(2)}`;
            submitText.textContent = `Liquidar Saldo Grupal ($${g.debt_amount.toFixed(2)})`;
            paymentTypeBox.classList.add('opacity-40', 'pointer-events-none');
            
            const radioCompleto = document.querySelector('input[name="group_payment_type"][value="completo"]');
            if (radioCompleto) radioCompleto.checked = true;
            toggleGroupAbono(false);
        } else {
            document.getElementById('form-group-action-type').value = 'renew_with_debt';
            amountInput.value = g.total_renew_with_debt.toFixed(2);
            cardTotal.textContent = `$${g.total_renew_with_debt.toFixed(2)}`;
            submitText.textContent = `Liquidar Deuda + Renovar Grupo ($${g.total_renew_with_debt.toFixed(2)})`;
            paymentTypeBox.classList.remove('opacity-40', 'pointer-events-none');

            // Update abono limits based on total renew with debt
            const minAb = Math.round(g.total_renew_with_debt * 0.25 * 100) / 100;
            const maxAb = Math.round(g.total_renew_with_debt * 0.75 * 100) / 100;
            document.getElementById('group-abono-min-text').textContent = minAb.toFixed(2);
            document.getElementById('group-abono-max-text').textContent = maxAb.toFixed(2);
            document.getElementById('group-abono-input').value = minAb.toFixed(2);
        }
    }

    function toggleGroupAbono(isAbono) {
        const abonoBox = document.getElementById('group-abono-fields-box');
        if (isAbono) {
            abonoBox.classList.remove('hidden');
            abonoBox.classList.add('flex');
            document.getElementById('group-abono-input').focus();
        } else {
            abonoBox.classList.add('hidden');
            abonoBox.classList.remove('flex');
        }
    }

    function toggleGroupTransfer(isTransfer) {
        const voucherBox = document.getElementById('group-transfer-voucher-box');
        if (isTransfer) {
            voucherBox.classList.remove('hidden');
            voucherBox.classList.add('flex');
            document.getElementById('group-voucher-input').focus();
        } else {
            voucherBox.classList.add('hidden');
            voucherBox.classList.remove('flex');
        }
    }

    function showGroupFormError(msg) {
        const banner = document.getElementById('group-form-error-banner');
        const text = document.getElementById('group-form-error-text');
        if (banner && text) {
            text.textContent = msg;
            banner.classList.remove('hidden');
            banner.classList.add('flex');
        }
    }

    function hideGroupFormError() {
        const banner = document.getElementById('group-form-error-banner');
        if (banner) {
            banner.classList.add('hidden');
            banner.classList.remove('flex');
        }
    }

    async function handleGroupPaymentSubmit(e) {
        e.preventDefault();
        hideGroupFormError();

        if (!currentSelectedGroup || !document.getElementById('form-group-membership-id').value) {
            showGroupFormError('Por favor busca y selecciona un grupo registrado para procesar el cobro.');
            document.getElementById('live-group-search').focus();
            return;
        }

        const g = currentSelectedGroup.group;
        const members = currentSelectedGroup.members || [];
        const membershipId = document.getElementById('form-group-membership-id').value;
        const method = document.querySelector('input[name="group_payment_method"]:checked').value;
        const voucher = document.getElementById('group-voucher-input') ? document.getElementById('group-voucher-input').value.trim() : '';
        const paymentType = document.querySelector('input[name="group_payment_type"]:checked').value;
        const abonoAmount = parseFloat(document.getElementById('group-abono-input').value || 0);
        const adminPin = document.getElementById('group-admin-pin') ? document.getElementById('group-admin-pin').value.trim() : '';
        const debtActionChoice = document.querySelector('input[name="group_debt_action_choice"]:checked')?.value || 'normal';

        if (method === 'transferencia' && !voucher) {
            showGroupFormError('El número de comprobante o referencia bancaria es obligatorio para pagos por transferencia.');
            document.getElementById('group-voucher-input').focus();
            return;
        }

        if (paymentType === 'abono') {
            const minAb = parseFloat(document.getElementById('group-abono-min-text').textContent || 0);
            const maxAb = parseFloat(document.getElementById('group-abono-max-text').textContent || 0);

            if (isNaN(abonoAmount) || abonoAmount < minAb || abonoAmount > maxAb) {
                showGroupFormError(`El abono parcial debe estar entre $${minAb.toFixed(2)} (25%) y $${maxAb.toFixed(2)} (75%).`);
                document.getElementById('group-abono-input').focus();
                return;
            }

            if (!adminPin) {
                showGroupFormError('Se requiere la clave de autorización del supervisor para aprobar un abono parcial.');
                document.getElementById('group-admin-pin').focus();
                return;
            }
        }

        const payableAmount = debtActionChoice === 'liquidate_only' 
            ? g.debt_amount 
            : (debtActionChoice === 'renew_with_debt' ? g.total_renew_with_debt : g.total_plan_price);
        
        const finalChargedAmount = paymentType === 'abono' ? abonoAmount : payableAmount;

        // Modal de confirmación interactivo mostrando el desglose detallado
        if (typeof Swal !== 'undefined') {
            const memberListHtml = members.map((m, i) => `<li style="padding: 2px 0;"><strong>${i + 1}. ${m.name} ${m.last_name}</strong> (C.I. ${m.id_card})</li>`).join('');
            const confirmRes = await Swal.fire({
                title: '¿Confirmar Cobro del Plan Grupal?',
                html: `
                    <div style="text-align: left; font-size: 13px; line-height: 1.6; color: #e2e2e2;">
                        <p style="margin-bottom: 4px;"><strong>Grupo:</strong> <span style="color:#ffffff; font-weight:800;">${g.group_name}</span></p>
                        <p style="margin-bottom: 4px;"><strong>Plan Asignado:</strong> ${g.plan_name} ($${parseFloat(g.unit_price).toFixed(2)} c/u)</p>
                        <p style="margin-top: 8px; font-weight: bold; color: #a1a1aa;">Atletas Integrantes (${g.members_count}):</p>
                        <ul style="margin: 4px 0 8px 16px; padding: 0; font-size: 12px; max-height: 120px; overflow-y: auto;">
                            ${memberListHtml}
                        </ul>
                        <p><strong>Modalidad:</strong> ${paymentType === 'abono' ? 'Abono Parcial Autorizado' : 'Pago Completo (100%)'}</p>
                        <p><strong>Método:</strong> ${method === 'efectivo' ? 'Efectivo 💵' : 'Transferencia #' + voucher + ' 🏦'}</p>
                        <div style="background: rgba(74, 222, 128, 0.1); border: 1px solid rgba(74, 222, 128, 0.3); border-radius: 8px; padding: 10px 14px; margin-top: 10px;">
                            <span style="font-size: 11px; color: #a1a1aa; text-transform: uppercase; font-weight: bold; display: block;">Total a Cobrar Ahora:</span>
                            <span style="font-size: 22px; font-weight: 800; color: #4ade80; font-family: monospace;">$${parseFloat(finalChargedAmount).toFixed(2)}</span>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, Cobrar Plan Grupal',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#e31b23',
                cancelButtonColor: '#262626',
                background: '#151515',
                color: '#e2e2e2',
                reverseButtons: true
            });
            if (!confirmRes.isConfirmed) return;
        }

        const btn = document.getElementById('btn-submit-group-form');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">sync</span> Procesando cobro grupal...';

        try {
            const res = await fetch("{{ route('pagos.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    action_type: 'group_payment',
                    membership_id: membershipId,
                    debt_action_choice: debtActionChoice,
                    payment_type: paymentType,
                    abono_amount: paymentType === 'abono' ? abonoAmount : null,
                    admin_pin: adminPin,
                    payment_method: method,
                    voucher_number: voucher
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                updateUIFinancialStats(data.stats);
                prependTransactionRow(data);

                showToast('success', data.message, 'Plan Grupal Cobrado');

                showSuccessModal({
                    concept: `Plan Grupal: ${g.group_name} (${g.members_count} atletas)`,
                    amount: data.amount,
                    method: data.payment_method + (data.voucher_number ? ` #${data.voucher_number}` : ''),
                    debt: data.new_pending_balance || 0,
                    clientId: data.client_id,
                    paymentId: data.payment_id
                });

                resetGroupSelection();
            } else {
                showGroupFormError(data.message || 'Error al procesar el pago del plan grupal.');
                showToast('error', data.message || 'Error en cobro.', 'Error');
            }
        } catch (err) {
            console.error(err);
            showGroupFormError('Error de comunicación con el servidor.');
        } finally {
            btn.disabled = false;
            if (currentSelectedGroup) {
                document.getElementById('btn-group-submit-text').textContent = `Cobrar Plan Grupal ($${parseFloat(finalChargedAmount).toFixed(2)})`;
            } else {
                document.getElementById('btn-group-submit-text').textContent = 'Selecciona un grupo para cobrar';
            }
        }
    }

    // --- ACTUALIZACIÓN DINÁMICA DE KPIs Y CONTADORES EN TIEMPO REAL ---
    function updateUIFinancialStats(stats) {
        if (!stats) return;
        if (document.getElementById('kpi-today-revenue')) {
            document.getElementById('kpi-today-revenue').textContent = `$${parseFloat(stats.todayRevenue).toFixed(2)}`;
        }
        if (document.getElementById('kpi-today-cash')) {
            document.getElementById('kpi-today-cash').textContent = `Efectivo: $${parseFloat(stats.todayCash).toFixed(2)}`;
        }
        if (document.getElementById('kpi-today-transfer')) {
            document.getElementById('kpi-today-transfer').textContent = `Transf: $${parseFloat(stats.todayTransfer).toFixed(2)}`;
        }
        if (document.getElementById('kpi-month-revenue')) {
            document.getElementById('kpi-month-revenue').textContent = `$${parseFloat(stats.totalMonthlyRevenue).toFixed(2)}`;
        }
        if (document.getElementById('kpi-pending-debt')) {
            document.getElementById('kpi-pending-debt').textContent = `$${parseFloat(stats.totalPendingDebt).toFixed(2)}`;
        }
        if (document.getElementById('kpi-pending-debt-count')) {
            document.getElementById('kpi-pending-debt-count').innerHTML = `<span class="material-symbols-outlined text-[14px]">group</span> ${stats.totalPendingDebtCount} atletas con saldo adeudado`;
        }
        
        if (document.getElementById('kpi-cash-pct')) {
            document.getElementById('kpi-cash-pct').textContent = `Efectivo (${stats.cashPercentage}%)`;
        }
        if (document.getElementById('kpi-transfer-pct')) {
            document.getElementById('kpi-transfer-pct').textContent = `Transf. (${stats.transferPercentage}%)`;
        }
        if (document.getElementById('kpi-bar-cash')) {
            document.getElementById('kpi-bar-cash').style.width = `${stats.cashPercentage}%`;
        }
        if (document.getElementById('kpi-bar-transfer')) {
            document.getElementById('kpi-bar-transfer').style.width = `${stats.transferPercentage}%`;
        }

        if (document.getElementById('tab-all-count')) document.getElementById('tab-all-count').textContent = stats.tabAllCount;
        if (document.getElementById('tab-cash-count')) document.getElementById('tab-cash-count').textContent = stats.tabCashCount;
        if (document.getElementById('tab-transfer-count')) document.getElementById('tab-transfer-count').textContent = stats.tabTransferCount;
        if (document.getElementById('tab-debt-count')) document.getElementById('tab-debt-count').textContent = stats.tabDebtCount;
        if (document.getElementById('tab-dailypasses-count')) document.getElementById('tab-dailypasses-count').textContent = stats.tabDailyPassesCount;
    }

    // --- INSERCIÓN EN TIEMPO REAL EN LA TABLA DE TRANSACCIONES ---
    function prependTransactionRow(p) {
        const tbody = document.getElementById('payments-table-body');
        if (!tbody) return;

        const emptyRow = tbody.querySelector('td[colspan]');
        if (emptyRow) {
            emptyRow.closest('tr').remove();
        }

        const isCash = (p.payment_method || '').toLowerCase().includes('efectivo');
        const hasDebt = parseFloat(p.new_pending_balance || 0) > 0;
        const invoiceLink = p.client_id ? `/clientes/${p.client_id}/invoice?payment_id=${p.payment_id || ''}&autoprint=1` : `/pagos/${p.payment_id || ''}/invoice?autoprint=1`;

        const tr = document.createElement('tr');
        tr.className = `hover:bg-surface-container/40 transition-all duration-700 group transaction-row animate-fade-in ${hasDebt ? 'bg-error/5' : 'bg-[#4ade80]/15'}`;

        tr.innerHTML = `
            <td class="py-3.5 px-4 text-on-surface-variant font-mono">
                ${p.created_at_formatted || 'Hoy'}
            </td>
            <td class="py-3.5 px-4">
                <button type="button" onclick="openClientHistoryModal(${p.client_id || 0}, '${(p.client_name || '').replace(/'/g, "\\'")}')" class="font-bold text-on-surface hover:text-primary transition-colors text-left flex items-center gap-1 group/btn" title="Ver Historial de Pagos">
                    <span>${p.client_name || 'Atleta'}</span>
                    <span class="material-symbols-outlined text-[14px] opacity-0 group-hover/btn:opacity-100 transition-opacity text-primary">history</span>
                </button>
                <div class="text-[10px] text-on-surface-variant font-mono search-target">C.I. ${p.client_id_card || 'S/N'}</div>
            </td>
            <td class="py-3.5 px-4">
                <div class="font-bold text-on-surface search-target">${p.plan_name || 'Membresía'}</div>
                <div class="text-[11px] text-primary font-mono font-bold">$${parseFloat(p.amount).toFixed(2)} cobrados</div>
            </td>
            <td class="py-3.5 px-4">
                ${isCash ? `
                    <span class="inline-flex items-center gap-1 text-[#4ade80] bg-[#4ade80]/10 border border-[#4ade80]/20 px-2 py-0.5 rounded font-etiqueta-bold text-[10px] uppercase">
                        <span class="material-symbols-outlined text-[12px]">payments</span> Efectivo
                    </span>
                ` : `
                    <span class="inline-flex items-center gap-1 text-[#60a5fa] bg-[#60a5fa]/10 border border-[#60a5fa]/20 px-2 py-0.5 rounded font-etiqueta-bold text-[10px] uppercase">
                        <span class="material-symbols-outlined text-[12px]">account_balance</span> Transf.
                    </span>
                `}
            </td>
            <td class="py-3.5 px-4 font-mono text-on-surface-variant text-[11px] search-target">
                ${p.voucher_number ? '#' + p.voucher_number : '---'}
            </td>
            <td class="py-3.5 px-4">
                ${!hasDebt ? `
                    <span class="text-[#4ade80] font-etiqueta-bold text-[10px] uppercase tracking-wider flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">check_circle</span> Completado
                    </span>
                ` : `
                    <div class="flex flex-col">
                        <span class="text-[#facc15] font-etiqueta-bold text-[9px] uppercase tracking-wider flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">schedule</span> Abono Parcial
                        </span>
                        <span class="text-error font-mono font-bold text-[10px]">Debe $${parseFloat(p.new_pending_balance).toFixed(2)}</span>
                    </div>
                `}
            </td>
            <td class="py-3.5 px-4 text-right">
                <div class="flex items-center justify-end gap-1.5">
                    <a href="${invoiceLink}" target="_blank" class="p-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors inline-flex items-center" title="Imprimir Recibo">
                        <span class="material-symbols-outlined text-[16px]">print</span>
                    </a>
                </div>
            </td>
        `;

        tbody.prepend(tr);

        setTimeout(() => {
            tr.classList.remove('bg-[#4ade80]/15');
        }, 3000);
    }

    function prependDailyPassRow(dp) {
        const tbody = document.getElementById('daily-passes-table-body');
        if (!tbody) return;

        const emptyRow = tbody.querySelector('td[colspan]');
        if (emptyRow) {
            emptyRow.closest('tr').remove();
        }

        const tr = document.createElement('tr');
        tr.className = 'hover:bg-surface-container/40 transition-all duration-700 transaction-row animate-fade-in bg-[#4ade80]/15';
        tr.innerHTML = `
            <td class="py-3.5 px-4 font-mono text-on-surface-variant">${dp.created_at_formatted || 'Hoy'}</td>
            <td class="py-3.5 px-4 font-bold text-on-surface search-target">${dp.guest_name}</td>
            <td class="py-3.5 px-4 text-primary font-bold search-target">Pase Express 1 Día</td>
            <td class="py-3.5 px-4">
                <span class="inline-flex items-center gap-1 text-[#4ade80] bg-[#4ade80]/10 px-2 py-0.5 rounded font-etiqueta-bold text-[10px] uppercase">
                    ${dp.payment_method}
                </span>
            </td>
            <td class="py-3.5 px-4 text-right font-titular-md text-[15px] font-bold text-[#4ade80]">$${parseFloat(dp.amount).toFixed(2)}</td>
        `;
        tbody.prepend(tr);
        setTimeout(() => tr.classList.remove('bg-[#4ade80]/15'), 3000);
    }

    // --- REGISTRAR PASE EXPRESS DIARIO ---
    async function handleDailyPassSubmit(e) {
        e.preventDefault();
        const guestName = document.getElementById('daily-guest-name').value.trim();
        const method = document.querySelector('input[name="daily_payment_method"]:checked').value;
        const voucher = document.getElementById('daily-voucher-input') ? document.getElementById('daily-voucher-input').value.trim() : '';
        const amount = parseFloat(document.getElementById('daily-amount').value || {{ $dailyPassPrice }});
        const btn = document.getElementById('btn-submit-daily-pass');

        if (!guestName) {
            showToast('warning', 'Por favor ingresa el nombre completo del visitante.', 'Campo Requerido');
            document.getElementById('daily-guest-name').focus();
            return;
        }

        if (method === 'transferencia' && !voucher) {
            showToast('warning', 'El número de comprobante o referencia es obligatorio para pases pagados por transferencia.', 'Comprobante Requerido');
            document.getElementById('daily-voucher-input').focus();
            return;
        }

        // Confirmación interactiva antes de cobrar
        if (typeof Swal !== 'undefined') {
            const confirmRes = await Swal.fire({
                title: '¿Registrar Pase Express Diario?',
                html: `
                    <div style="text-align: left; font-size: 13px; line-height: 1.6; color: #e2e2e2;">
                        <p><strong>Visitante:</strong> ${guestName}</p>
                        <p><strong>Acceso:</strong> Pase Diario (1 Día)</p>
                        <p><strong>Método:</strong> ${method === 'efectivo' ? 'Efectivo 💵' : 'Transferencia #' + voucher + ' 🏦'}</p>
                        <p style="font-size: 17px; margin-top: 8px; color: #4ade80;"><strong>Total a Cobrar:</strong> $${parseFloat(amount).toFixed(2)}</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, Confirmar y Cobrar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#e31b23',
                cancelButtonColor: '#262626',
                background: '#151515',
                color: '#e2e2e2',
                reverseButtons: true
            });
            if (!confirmRes.isConfirmed) return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[16px]">sync</span> Registrando entrada...';

        try {
            const res = await fetch("{{ route('pagos.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    action_type: 'daily_pass',
                    guest_name: guestName,
                    payment_method: method,
                    voucher_number: voucher,
                    amount: amount
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                updateUIFinancialStats(data.stats);
                prependDailyPassRow(data);

                document.getElementById('daily-guest-name').value = '';
                if (document.getElementById('daily-voucher-input')) document.getElementById('daily-voucher-input').value = '';
                
                showToast('success', data.message, 'Pase Express Registrado');
                showSuccessModal({
                    concept: 'Pase Express Diario (Acceso 1 Día)',
                    amount: amount,
                    method: method === 'efectivo' ? 'Efectivo 💵' : `Transferencia #${voucher} 🏦`,
                    debt: 0,
                    clientId: null,
                    paymentId: null
                });
            } else {
                showToast('error', data.message || 'Error al registrar el pase express.', 'Error en Cobro');
            }
        } catch (err) {
            console.error(err);
            showToast('error', 'Error en la conexión con el servidor.', 'Error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[16px]">how_to_reg</span> Registrar Entrada Express (${{ number_format($dailyPassPrice, 2) }})';
        }
    }

    // --- BÚSQUEDA PREDICTIVA DE CLIENTES Y DETECCIÓN DE DEUDAS ---
    function handleClientSearch(val) {
        clearTimeout(searchDebounce);
        const resultsBox = document.getElementById('client-search-results');
        const clearBtn = document.getElementById('btn-clear-client');

        if (val.trim().length < 2) {
            resultsBox.classList.add('hidden');
            resultsBox.innerHTML = '';
            clearBtn.classList.add('hidden');
            return;
        }

        clearBtn.classList.remove('hidden');

        searchDebounce = setTimeout(async () => {
            try {
                const res = await fetch(`/api/payments/client-debt?q=${encodeURIComponent(val)}`);
                const clients = await res.json();

                if (clients.length === 0) {
                    resultsBox.innerHTML = '<div class="p-3.5 text-xs text-on-surface-variant text-center">No se encontraron atletas con ese nombre o cédula.</div>';
                } else {
                    resultsBox.innerHTML = clients.map(c => `
                        <div onclick="selectClientForPayment(${c.id})" class="p-3 hover:bg-surface-container-highest cursor-pointer flex justify-between items-center text-xs transition-colors">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold text-[10px]">
                                    ${c.name.substring(0, 2).toUpperCase()}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-on-surface flex items-center gap-1.5">
                                        ${c.name}
                                        ${c.has_debt ? '<span class="bg-error text-white text-[9px] px-1.5 py-0.2 rounded font-mono font-bold uppercase">Debe $' + parseFloat(c.debt_amount).toFixed(2) + '</span>' : ''}
                                    </span>
                                    <span class="text-on-surface-variant font-mono text-[10px]">C.I. ${c.id_card} &middot; Plan: ${c.plan_name}</span>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-primary text-[18px]">chevron_right</span>
                        </div>
                    `).join('');
                }
                resultsBox.classList.remove('hidden');
            } catch (e) {
                console.error(e);
            }
        }, 150);
    }

    async function selectClientForPayment(clientId) {
        const resultsBox = document.getElementById('client-search-results');
        resultsBox.classList.add('hidden');
        hideFormError();

        try {
            const res = await fetch(`/api/payments/client-debt?client_id=${clientId}`);
            const data = await res.json();

            if (res.ok && data.success) {
                currentSelectedClient = data;
                document.getElementById('form-client-id').value = data.client.id;
                document.getElementById('live-client-search').value = `${data.client.name} (C.I. ${data.client.id_card})`;
                document.getElementById('btn-clear-client').classList.remove('hidden');

                const debtBox = document.getElementById('client-debt-alert-box');
                const planGroup = document.getElementById('plan-selection-group');
                const paymentTypeSelector = document.getElementById('payment-type-selector-box');
                const conceptBadge = document.getElementById('form-concept-badge');
                const submitText = document.getElementById('btn-submit-text');

                if (data.has_debt && data.debt_amount > 0) {
                    document.getElementById('form-membership-id').value = data.membership_id;
                    document.getElementById('debt-client-name').textContent = data.client.name;
                    document.getElementById('debt-amount-display').textContent = `$${parseFloat(data.debt_amount).toFixed(2)}`;
                    document.getElementById('debt-plan-name').textContent = data.plan_name;

                    document.getElementById('opt-only-debt-amount').textContent = `$${parseFloat(data.debt_amount).toFixed(2)}`;
                    document.getElementById('opt-renew-debt-amount').textContent = `$${parseFloat(data.total_renew_with_debt).toFixed(2)}`;

                    debtBox.classList.remove('hidden');
                    debtBox.classList.add('flex');
                    planGroup.classList.add('hidden');

                    document.querySelector('input[name="debt_action_choice"][value="liquidate_only"]').checked = true;
                    handleDebtChoiceChange('liquidate_only');
                    showToast('warning', `El atleta ${data.client.name} registra un saldo pendiente de $${parseFloat(data.debt_amount).toFixed(2)}.`, 'Deuda Detectada');
                } else {
                    debtBox.classList.add('hidden');
                    debtBox.classList.remove('flex');
                    planGroup.classList.remove('hidden');
                    paymentTypeSelector.classList.remove('hidden');
                    document.getElementById('form-action-type').value = 'normal';
                    conceptBadge.textContent = 'Tarifa Catálogo';
                    
                    const planSelect = document.getElementById('form-plan-select');
                    handleFormPlanChange(planSelect);

                    document.getElementById('btn-submit-text').textContent = 'Registrar y Confirmar Cobro';
                    showToast('info', `Atleta ${data.client.name} seleccionado (Sin deudas pendientes).`, 'Atleta Seleccionado');
                }
            }
        } catch (e) {
            console.error(e);
        }
    }

    function resetClientSelection() {
        currentSelectedClient = null;
        document.getElementById('form-client-id').value = '';
        document.getElementById('form-membership-id').value = '';
        document.getElementById('form-action-type').value = 'normal';
        document.getElementById('live-client-search').value = '';
        document.getElementById('form-voucher-input').value = '';
        document.getElementById('form-admin-pin').value = '';
        document.getElementById('btn-clear-client').classList.add('hidden');
        document.getElementById('client-debt-alert-box').classList.add('hidden');
        document.getElementById('client-debt-alert-box').classList.remove('flex');
        document.getElementById('plan-selection-group').classList.remove('hidden');
        document.getElementById('payment-type-selector-box').classList.remove('hidden');
        document.getElementById('form-concept-badge').textContent = 'Tarifa Catálogo';
        
        const planSelect = document.getElementById('form-plan-select');
        if (planSelect) handleFormPlanChange(planSelect);

        const radioEfectivo = document.querySelector('input[name="payment_method"][value="efectivo"]');
        if (radioEfectivo) {
            radioEfectivo.checked = true;
            toggleFormPaymentFields();
        }

        const radioCompleto = document.querySelector('input[name="payment_type"][value="completo"]');
        if (radioCompleto) {
            radioCompleto.checked = true;
            toggleFormAbono(false);
        }

        document.getElementById('btn-submit-text').textContent = 'Registrar y Confirmar Cobro';
        hideFormError();
    }

    function handleDebtChoiceChange(choice) {
        const amountInput = document.getElementById('form-amount-input');
        const conceptBadge = document.getElementById('form-concept-badge');
        const submitText = document.getElementById('btn-submit-text');
        const actionType = document.getElementById('form-action-type');
        const paymentTypeSelector = document.getElementById('payment-type-selector-box');

        if (!currentSelectedClient) return;

        if (choice === 'liquidate_only') {
            actionType.value = 'liquidate_only';
            amountInput.value = parseFloat(currentSelectedClient.debt_amount).toFixed(2);
            conceptBadge.textContent = 'Solo Saldo Adeudado';
            submitText.textContent = `Cobrar y Liquidar Deuda ($${parseFloat(currentSelectedClient.debt_amount).toFixed(2)})`;
            paymentTypeSelector.classList.add('hidden');
            toggleFormAbono(false);
        } else if (choice === 'renew_with_debt') {
            actionType.value = 'renew_with_debt';
            amountInput.value = parseFloat(currentSelectedClient.total_renew_with_debt).toFixed(2);
            conceptBadge.textContent = 'Deuda + Renovación Mes';
            submitText.textContent = `Cobrar Deuda + Renovar ($${parseFloat(currentSelectedClient.total_renew_with_debt).toFixed(2)})`;
            paymentTypeSelector.classList.remove('hidden');
            updateAbonoBounds(parseFloat(currentSelectedClient.total_renew_with_debt));
        }
    }

    function handleFormPlanChange(select) {
        const opt = select.options[select.selectedIndex];
        const price = opt ? parseFloat(opt.dataset.price || 30) : 30;
        document.getElementById('form-amount-input').value = price.toFixed(2);
        document.getElementById('form-concept-badge').textContent = `${opt.dataset.name} ($${price.toFixed(2)})`;
        updateAbonoBounds(price);
    }

    function updateAbonoBounds(total) {
        const minAbono = total * 0.25;
        const maxAbono = total * 0.75;
        document.getElementById('form-abono-min-text').textContent = minAbono.toFixed(2);
        document.getElementById('form-abono-max-text').textContent = maxAbono.toFixed(2);
        const abonoInput = document.getElementById('form-abono-input');
        abonoInput.setAttribute('min', minAbono.toFixed(2));
        abonoInput.setAttribute('max', maxAbono.toFixed(2));
        abonoInput.value = minAbono.toFixed(2);
    }

    function toggleFormAbono(isAbono) {
        const box = document.getElementById('form-abono-fields-box');
        if (isAbono) {
            box.classList.remove('hidden');
            box.classList.add('flex');
            const total = parseFloat(document.getElementById('form-amount-input').value || 30);
            updateAbonoBounds(total);
        } else {
            box.classList.add('hidden');
            box.classList.remove('flex');
        }
    }

    function toggleFormPaymentFields() {
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        const voucherBox = document.getElementById('transfer-voucher-box');
        if (method === 'transferencia') {
            voucherBox.classList.remove('hidden');
            voucherBox.classList.add('flex');
            document.getElementById('form-voucher-input').focus();
        } else {
            voucherBox.classList.add('hidden');
            voucherBox.classList.remove('flex');
        }
    }

    function showFormError(msg) {
        const banner = document.getElementById('form-error-banner');
        const text = document.getElementById('form-error-text');
        text.textContent = msg;
        banner.classList.remove('hidden');
        banner.classList.add('flex');
        showToast('error', msg, 'Validación Requerida');
    }

    function hideFormError() {
        const banner = document.getElementById('form-error-banner');
        banner.classList.add('hidden');
        banner.classList.remove('flex');
    }

    // --- FORMULARIO PRINCIPAL DE CAJA SUBMIT ---
    async function handleCashFormSubmit(e) {
        e.preventDefault();
        hideFormError();

        const clientId = document.getElementById('form-client-id').value;
        const actionType = document.getElementById('form-action-type').value;
        const membershipId = document.getElementById('form-membership-id').value;
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        const voucher = document.getElementById('form-voucher-input').value.trim();
        const amount = parseFloat(document.getElementById('form-amount-input').value || 0);
        const planId = document.getElementById('form-plan-select').value;
        const paymentType = document.querySelector('input[name="payment_type"]:checked').value;
        const abonoAmount = parseFloat(document.getElementById('form-abono-input').value || 0);
        const adminPin = document.getElementById('form-admin-pin').value.trim();
        const btn = document.getElementById('btn-submit-cash-form');

        if (!clientId && actionType !== 'daily_pass') {
            showFormError('Debe buscar y seleccionar un socio o atleta registrado.');
            document.getElementById('live-client-search').focus();
            return;
        }

        if (method === 'transferencia' && !voucher) {
            showFormError('El número de comprobante o referencia bancaria es estrictamente obligatorio para transferencias.');
            document.getElementById('form-voucher-input').focus();
            return;
        }

        if (paymentType === 'abono') {
            if (!adminPin) {
                showFormError('Debe ingresar la clave de supervisor para autorizar el abono parcial.');
                document.getElementById('form-admin-pin').focus();
                return;
            }
        }

        // Confirmación interactiva antes de procesar cobro en caja
        const clientSearchVal = document.getElementById('live-client-search').value || 'Atleta Seleccionado';
        const planSelect = document.getElementById('form-plan-select');
        const planName = planSelect ? planSelect.options[planSelect.selectedIndex]?.dataset.name || 'Membresía' : 'Membresía';
        const finalCharge = paymentType === 'abono' ? abonoAmount : amount;
        const methodText = method === 'efectivo' ? 'Efectivo 💵' : `Transferencia #${voucher} 🏦`;
        const opDesc = actionType === 'liquidate_only' ? 'Liquidación de Deuda' : (actionType === 'renew_with_debt' ? 'Deuda + Renovación' : 'Membresía ' + planName);

        if (typeof Swal !== 'undefined') {
            const confirmRes = await Swal.fire({
                title: '¿Confirmar Cobro en Caja?',
                html: `
                    <div style="text-align: left; font-size: 13px; line-height: 1.6; color: #e2e2e2;">
                        <p><strong>Atleta:</strong> ${clientSearchVal}</p>
                        <p><strong>Concepto:</strong> ${opDesc}</p>
                        <p><strong>Método de Pago:</strong> ${methodText}</p>
                        <p style="font-size: 18px; margin-top: 8px; color: #4ade80;"><strong>Monto a Cobrar:</strong> $${parseFloat(finalCharge).toFixed(2)}</p>
                        ${paymentType === 'abono' ? `<p style="color: #f59e0b; font-size: 12px; margin-top: 4px;"><strong>Saldo pendiente por pagar:</strong> $${parseFloat(amount - abonoAmount).toFixed(2)}</p>` : ''}
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, Confirmar y Cobrar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#e31b23',
                cancelButtonColor: '#262626',
                background: '#151515',
                color: '#e2e2e2',
                reverseButtons: true
            });
            if (!confirmRes.isConfirmed) return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">sync</span> Procesando cobro...';

        try {
            let endpoint = "{{ route('pagos.store') }}";
            let payload = {
                action_type: actionType,
                membership_id: membershipId,
                client_id: clientId,
                plan_id: planId,
                payment_method: method,
                voucher_number: voucher,
                amount_to_pay: amount,
                payment_type: paymentType,
                abono_amount: abonoAmount,
                admin_pin: adminPin
            };

            const res = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (res.ok && data.success) {
                updateUIFinancialStats(data.stats);
                prependTransactionRow(data);

                resetClientSelection();

                showToast('success', data.message, 'Operación Exitosa');
                
                showSuccessModal({
                    concept: actionType === 'liquidate_only' ? 'Liquidación de Deuda' : (actionType === 'renew_with_debt' ? 'Deuda + Renovación' : 'Cobro de Suscripción'),
                    amount: paymentType === 'abono' ? abonoAmount : amount,
                    method: method === 'efectivo' ? 'Efectivo 💵' : `Transferencia #${voucher} 🏦`,
                    debt: paymentType === 'abono' ? (amount - abonoAmount) : (data.new_pending_balance || 0),
                    clientId: data.client_id || clientId,
                    paymentId: data.payment_id
                });
            } else {
                showFormError(data.message || 'Error al registrar el cobro en caja.');
            }
        } catch (err) {
            console.error(err);
            showFormError('Error en la conexión con el servidor.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">check_circle</span> <span id="btn-submit-text">Registrar y Confirmar Cobro</span>';
        }
    }

    // --- MODAL DE COBRO EXITOSO (INTERACCIÓN & RECIBO) ---
    function showSuccessModal(info) {
        document.getElementById('success-concept').textContent = info.concept;
        document.getElementById('success-amount').textContent = `$${parseFloat(info.amount).toFixed(2)}`;
        document.getElementById('success-method').textContent = info.method;
        
        const debtRow = document.getElementById('success-debt-row');
        if (info.debt > 0) {
            document.getElementById('success-debt').textContent = `$${parseFloat(info.debt).toFixed(2)}`;
            debtRow.classList.remove('hidden');
        } else {
            debtRow.classList.add('hidden');
        }

        const printBtn = document.getElementById('btn-success-print');
        if (info.clientId) {
            printBtn.href = `/clientes/${info.clientId}/invoice?payment_id=${info.paymentId || ''}&autoprint=1`;
            printBtn.classList.remove('hidden');
        } else if (info.paymentId) {
            printBtn.href = `/pagos/${info.paymentId}/invoice?autoprint=1`;
            printBtn.classList.remove('hidden');
        } else {
            printBtn.classList.add('hidden');
        }

        document.getElementById('paymentSuccessModal').classList.remove('hidden');
    }

    function closeSuccessModal() {
        document.getElementById('paymentSuccessModal').classList.add('hidden');
        if (typeof showToast === 'function') {
            showToast('info', 'Terminal de caja lista para la siguiente operación.', 'Caja Activa');
        }
    }

    // --- MODAL DE LIQUIDACIÓN RÁPIDA (DESDE LA TABLA) ---
    function openLiquidateDebtModal(membershipId, clientName, debtAmount, planName) {
        document.getElementById('liquidate-membership-id').value = membershipId;
        document.getElementById('liquidate-client-name').textContent = clientName;
        document.getElementById('liquidate-plan-name').textContent = planName || 'Membresía';
        document.getElementById('liquidate-debt-display').textContent = `$${parseFloat(debtAmount).toFixed(2)}`;
        document.getElementById('liquidate-amount-input').value = parseFloat(debtAmount).toFixed(2);
        document.getElementById('liquidate-voucher-input').value = '';

        const radio = document.querySelector('input[name="liquidate_payment_method"][value="efectivo"]');
        if (radio) {
            radio.checked = true;
            toggleLiquidateTransfer(false);
        }

        document.getElementById('liquidateModal').classList.remove('hidden');
    }

    function closeLiquidateDebtModal() {
        document.getElementById('liquidateModal').classList.add('hidden');
    }

    function toggleLiquidateTransfer(isTransfer) {
        const voucherBox = document.getElementById('liquidate-voucher-box');
        if (isTransfer) {
            voucherBox.classList.remove('hidden');
            voucherBox.classList.add('flex');
            document.getElementById('liquidate-voucher-input').focus();
        } else {
            voucherBox.classList.add('hidden');
            voucherBox.classList.remove('flex');
        }
    }

    async function handleLiquidateDebtSubmit(e) {
        e.preventDefault();
        const membershipId = document.getElementById('liquidate-membership-id').value;
        const amount = parseFloat(document.getElementById('liquidate-amount-input').value || 0);
        const method = document.querySelector('input[name="liquidate_payment_method"]:checked').value;
        const voucher = document.getElementById('liquidate-voucher-input').value.trim();
        const btn = document.getElementById('btn-submit-liquidate');

        if (method === 'transferencia' && !voucher) {
            showToast('warning', 'El número de comprobante o referencia bancaria es estrictamente obligatorio para transferencias.', 'Comprobante Requerido');
            document.getElementById('liquidate-voucher-input').focus();
            return;
        }

        const clientNameDebt = document.getElementById('liquidate-client-name').textContent;
        if (typeof Swal !== 'undefined') {
            const confirmRes = await Swal.fire({
                title: '¿Confirmar Liquidación de Saldo?',
                html: `
                    <div style="text-align: left; font-size: 13px; line-height: 1.6; color: #e2e2e2;">
                        <p><strong>Atleta:</strong> ${clientNameDebt}</p>
                        <p><strong>Operación:</strong> Pago / Abono de Saldo Pendiente</p>
                        <p><strong>Método:</strong> ${method === 'efectivo' ? 'Efectivo 💵' : 'Transferencia #' + voucher + ' 🏦'}</p>
                        <p style="font-size: 17px; margin-top: 8px; color: #4ade80;"><strong>Monto a Cobrar:</strong> $${parseFloat(amount).toFixed(2)}</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, Liquidar Saldo',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#e31b23',
                cancelButtonColor: '#262626',
                background: '#151515',
                color: '#e2e2e2',
                reverseButtons: true
            });
            if (!confirmRes.isConfirmed) return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[16px]">sync</span> Liquidando saldo...';

        try {
            const res = await fetch("{{ route('pagos.liquidateDebt') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    membership_id: membershipId,
                    amount_to_pay: amount,
                    payment_method: method,
                    voucher_number: voucher
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                closeLiquidateDebtModal();

                updateUIFinancialStats(data.stats);
                prependTransactionRow(data);

                if (currentSelectedClient && currentSelectedClient.membership_id == membershipId) {
                    resetClientSelection();
                }

                showToast('success', data.message, 'Deuda Liquidada');
                showSuccessModal({
                    concept: 'Liquidación de Saldo Pendiente',
                    amount: amount,
                    method: method === 'efectivo' ? 'Efectivo 💵' : `Transferencia #${voucher} 🏦`,
                    debt: data.new_pending_balance || 0,
                    clientId: data.client_id,
                    paymentId: data.payment_id
                });
            } else {
                showToast('error', data.message || 'Error al liquidar saldo.', 'Error');
            }
        } catch (err) {
            console.error(err);
            showToast('error', 'Error en la conexión al liquidar saldo.', 'Error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[16px]">price_check</span> Registrar Liquidación';
        }
    }

    // --- FILTRADO EN VIVO DE LA TABLA DE TRANSACCIONES ---
    function filterTransactionsTable(query) {
        const q = (query || '').toLowerCase().trim();
        const rows = document.querySelectorAll('.transaction-row');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (!q || text.includes(q)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // --- HISTORIAL COMPLETO DE PAGOS DEL ATLETA ---
    async function openClientHistoryModal(clientId, clientName) {
        const modal = document.getElementById('clientPaymentHistoryModal');
        const nameHeader = document.getElementById('history-client-name');
        const loading = document.getElementById('history-loading-spinner');
        const container = document.getElementById('history-data-container');
        const rowsTbody = document.getElementById('history-table-rows');

        nameHeader.textContent = `Atleta: ${clientName}`;
        loading.classList.remove('hidden');
        loading.classList.add('flex');
        container.classList.add('hidden');
        modal.classList.remove('hidden');

        try {
            const res = await fetch(`/api/payments/client-history/${clientId}`);
            const data = await res.json();

            if (res.ok && data.success) {
                document.getElementById('history-client-idcard').textContent = data.client.id_card || 'Sin C.I.';
                document.getElementById('history-client-phone').textContent = data.client.phone || 'Sin número';
                document.getElementById('history-total-spent').textContent = `$${parseFloat(data.total_spent).toFixed(2)}`;

                if (data.payments.length === 0) {
                    rowsTbody.innerHTML = '<tr><td colspan="7" class="py-6 text-center text-on-surface-variant">No se registran pagos previos para este atleta.</td></tr>';
                } else {
                    rowsTbody.innerHTML = data.payments.map(p => `
                        <tr class="hover:bg-surface-container/40 transition-colors">
                            <td class="py-2.5 px-3 font-mono text-on-surface-variant">${p.date}</td>
                            <td class="py-2.5 px-3 font-bold text-on-surface">${p.plan_name}</td>
                            <td class="py-2.5 px-3">${p.payment_method}</td>
                            <td class="py-2.5 px-3 font-mono text-on-surface-variant">${p.voucher_number ? '#' + p.voucher_number : '---'}</td>
                            <td class="py-2.5 px-3 font-mono font-bold text-[#4ade80]">$${parseFloat(p.amount).toFixed(2)}</td>
                            <td class="py-2.5 px-3">
                                ${p.pending_balance > 0 
                                    ? '<span class="text-error font-bold text-[10px]">Debe $' + parseFloat(p.pending_balance).toFixed(2) + '</span>'
                                    : '<span class="text-[#4ade80] font-bold text-[10px]">Pagado</span>'}
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                <a href="/clientes/${p.client_id}/invoice?payment_id=${p.id}&autoprint=1" target="_blank" class="p-1 rounded bg-surface-container hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors inline-flex items-center" title="Imprimir Recibo">
                                    <span class="material-symbols-outlined text-[15px]">print</span>
                                </a>
                            </td>
                        </tr>
                    `).join('');
                }

                loading.classList.add('hidden');
                loading.classList.remove('flex');
                container.classList.remove('hidden');
            } else {
                showToast('error', 'No se pudo cargar el historial del atleta.', 'Error');
                closeClientHistoryModal();
            }
        } catch (e) {
            console.error(e);
            showToast('error', 'Error de conexión al cargar el historial.', 'Error');
            closeClientHistoryModal();
        }
    }

    function closeClientHistoryModal() {
        document.getElementById('clientPaymentHistoryModal').classList.add('hidden');
    }

    // --- VARIABLES & MÉTODOS DE ARQUEO DE CAJA ---
    let currentArqueoSystemCash = {{ $todayCash }};
    let currentArqueoSystemTransfer = {{ $todayTransfer }};

    function openArqueoModal() {
        document.getElementById('arqueo_admin_pin').value = '';
        document.getElementById('arqueo_error_msg').classList.add('hidden');
        document.getElementById('arqueo_observations').dataset.userEdited = '';
        
        autoFillArqueoCuadrado();

        document.getElementById('arqueoConfigModal').classList.remove('hidden');
        setTimeout(() => document.getElementById('arqueo_admin_pin').focus(), 150);
    }

    function closeArqueoModal() {
        document.getElementById('arqueoConfigModal').classList.add('hidden');
    }

    function toggleArqueoPinVisibility() {
        const input = document.getElementById('arqueo_admin_pin');
        const icon = document.getElementById('arqueo_pin_eye');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    function recalculateArqueoPhysics() {
        const fondoBase = parseFloat(document.getElementById('arqueo_fondo_base').value || 0);
        const b100 = parseInt(document.getElementById('arq_b100').value || 0);
        const b50  = parseInt(document.getElementById('arq_b50').value || 0);
        const b20  = parseInt(document.getElementById('arq_b20').value || 0);
        const b10  = parseInt(document.getElementById('arq_b10').value || 0);
        const b5   = parseInt(document.getElementById('arq_b5').value || 0);
        const b1   = parseInt(document.getElementById('arq_b1').value || 0);
        const coins = parseFloat(document.getElementById('arq_coins').value || 0);

        // Subtotales individuales
        document.getElementById('sub_b100').textContent = '$' + (b100 * 100).toFixed(2);
        document.getElementById('sub_b50').textContent  = '$' + (b50 * 50).toFixed(2);
        document.getElementById('sub_b20').textContent  = '$' + (b20 * 20).toFixed(2);
        document.getElementById('sub_b10').textContent  = '$' + (b10 * 10).toFixed(2);
        document.getElementById('sub_b5').textContent   = '$' + (b5 * 5).toFixed(2);
        document.getElementById('sub_b1').textContent   = '$' + (b1 * 1).toFixed(2);
        document.getElementById('sub_coins').textContent = '$' + coins.toFixed(2);

        // Actualizar KPIs superiores
        document.getElementById('kpi-modal-fondo-display').textContent = '$' + fondoBase.toFixed(2);
        const totalEsperado = currentArqueoSystemCash + fondoBase;
        document.getElementById('kpi-modal-esperado-gaveta').textContent = '$' + totalEsperado.toFixed(2);

        // Total físico contado
        const totalFisicoContado = (b100 * 100) + (b50 * 50) + (b20 * 20) + (b10 * 10) + (b5 * 5) + (b1 * 1) + coins;
        const diff = Math.round((totalFisicoContado - totalEsperado) * 100) / 100;

        document.getElementById('arq_total_fisico_num').textContent = '$' + totalFisicoContado.toFixed(2);
        document.getElementById('arq_total_esperado_num').textContent = '$' + totalEsperado.toFixed(2);

        const diffElem = document.getElementById('arq_diff_num');
        const badgeElem = document.getElementById('arq_status_badge');
        const obsElem = document.getElementById('arqueo_observations');

        if (diff === 0) {
            diffElem.textContent = '$0.00';
            diffElem.className = 'font-bold font-mono text-base text-[#4ade80]';
            badgeElem.innerHTML = `
                <span class="inline-flex items-center gap-1 text-[#4ade80] bg-[#4ade80]/15 border border-[#4ade80]/30 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[14px]">check_circle</span> CUADRADO ($0.00)
                </span>
            `;
            if (!obsElem.dataset.userEdited) {
                obsElem.value = `Arqueo cuadrado sin inconsistencias. Operación conforme. Fondo base de $${fondoBase.toFixed(2)} apartado para apertura.`;
            }
        } else if (diff < 0) {
            const absDiff = Math.abs(diff).toFixed(2);
            diffElem.textContent = '-$' + absDiff;
            diffElem.className = 'font-bold font-mono text-base text-error';
            badgeElem.innerHTML = `
                <span class="inline-flex items-center gap-1 text-error bg-error/15 border border-error/30 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[14px]">warning</span> FALTANTE (-$${absDiff})
                </span>
            `;
            if (!obsElem.dataset.userEdited) {
                obsElem.value = `FALTANTE de -$${absDiff} detectado en conteo físico de gaveta. Saldo esperado: $${totalEsperado.toFixed(2)}, Dinero contado: $${totalFisicoContado.toFixed(2)}. Motivo: Posible error al dar cambio o egreso menor no registrado.`;
            }
        } else {
            const posDiff = diff.toFixed(2);
            diffElem.textContent = '+$' + posDiff;
            diffElem.className = 'font-bold font-mono text-base text-[#facc15]';
            badgeElem.innerHTML = `
                <span class="inline-flex items-center gap-1 text-[#facc15] bg-[#facc15]/15 border border-[#facc15]/30 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[14px]">arrow_upward</span> SOBRANTE (+$${posDiff})
                </span>
            `;
            if (!obsElem.dataset.userEdited) {
                obsElem.value = `SOBRANTE de +$${posDiff} detectado en conteo físico de gaveta. Saldo esperado: $${totalEsperado.toFixed(2)}, Dinero contado: $${totalFisicoContado.toFixed(2)}. Motivo: Posible cobro no registrado en sistema o cambio no retirado.`;
            }
        }
    }

    function addCoinValue(val) {
        const coinInput = document.getElementById('arq_coins');
        const current = parseFloat(coinInput.value || 0);
        coinInput.value = (Math.round((current + val) * 100) / 100).toFixed(2);
        recalculateArqueoPhysics();
    }

    function autoFillArqueoCuadrado() {
        const fondoBase = parseFloat(document.getElementById('arqueo_fondo_base').value || 50.00);
        let rem = currentArqueoSystemCash + fondoBase;

        document.getElementById('arq_b100').value = 0;
        document.getElementById('arq_b50').value = 0;

        // Desglose estándar de Ecuador (Billetes de $20, $10, $5, $1 y Monedas)
        const b20  = Math.floor(rem / 20);  rem = Math.round((rem - (b20 * 20)) * 100) / 100;
        const b10  = Math.floor(rem / 10);  rem = Math.round((rem - (b10 * 10)) * 100) / 100;
        const b5   = Math.floor(rem / 5);   rem = Math.round((rem - (b5 * 5)) * 100) / 100;
        const b1   = Math.floor(rem / 1);   rem = Math.round((rem - (b1 * 1)) * 100) / 100;
        const coins = rem;

        document.getElementById('arq_b20').value  = b20;
        document.getElementById('arq_b10').value  = b10;
        document.getElementById('arq_b5').value   = b5;
        document.getElementById('arq_b1').value   = b1;
        document.getElementById('arq_coins').value = coins.toFixed(2);

        document.getElementById('arqueo_observations').dataset.userEdited = '';
        recalculateArqueoPhysics();
    }

    function simulateArqueoFaltante() {
        autoFillArqueoCuadrado();
        const b5 = parseInt(document.getElementById('arq_b5').value || 0);
        const b1 = parseInt(document.getElementById('arq_b1').value || 0);
        const b10 = parseInt(document.getElementById('arq_b10').value || 0);
        const b20 = parseInt(document.getElementById('arq_b20').value || 0);

        if (b5 > 0) {
            document.getElementById('arq_b5').value = b5 - 1;
        } else if (b1 >= 5) {
            document.getElementById('arq_b1').value = b1 - 5;
        } else if (b10 > 0) {
            document.getElementById('arq_b10').value = b10 - 1;
            document.getElementById('arq_b5').value = b5 + 1;
        } else if (b20 > 0) {
            document.getElementById('arq_b20').value = b20 - 1;
            document.getElementById('arq_b10').value = b10 + 1;
            document.getElementById('arq_b5').value = b5 + 1;
        }
        document.getElementById('arqueo_observations').dataset.userEdited = '';
        recalculateArqueoPhysics();
        showToast('info', 'Se configuró un FALTANTE de -$5.00 para prueba de auditoría.', 'Simulación Faltante');
    }

    function simulateArqueoSobrante() {
        autoFillArqueoCuadrado();
        document.getElementById('arq_b10').value = parseInt(document.getElementById('arq_b10').value || 0) + 1;
        document.getElementById('arqueo_observations').dataset.userEdited = '';
        recalculateArqueoPhysics();
        showToast('info', 'Se configuró un SOBRANTE de +$10.00 para prueba de auditoría.', 'Simulación Sobrante');
    }

    function clearArqueoPhysics() {
        document.getElementById('arq_b100').value = 0;
        document.getElementById('arq_b50').value = 0;
        document.getElementById('arq_b20').value = 0;
        document.getElementById('arq_b10').value = 0;
        document.getElementById('arq_b5').value = 0;
        document.getElementById('arq_b1').value = 0;
        document.getElementById('arq_coins').value = '0.00';
        document.getElementById('arqueo_observations').dataset.userEdited = '';
        recalculateArqueoPhysics();
    }

    async function generateOfficialArqueo(e) {
        e.preventDefault();
        const fondoBase = document.getElementById('arqueo_fondo_base').value || '50.00';
        const date = document.getElementById('arqueo_date').value || '';
        const adminPin = document.getElementById('arqueo_admin_pin').value || '';
        const observations = document.getElementById('arqueo_observations').value || '';
        
        const b100 = parseInt(document.getElementById('arq_b100').value || 0);
        const b50  = parseInt(document.getElementById('arq_b50').value || 0);
        const b20  = parseInt(document.getElementById('arq_b20').value || 0);
        const b10  = parseInt(document.getElementById('arq_b10').value || 0);
        const b5   = parseInt(document.getElementById('arq_b5').value || 0);
        const b1   = parseInt(document.getElementById('arq_b1').value || 0);
        const coins = parseFloat(document.getElementById('arq_coins').value || 0);

        const errorContainer = document.getElementById('arqueo_error_msg');
        const errorText = document.getElementById('arqueo_error_text');
        const submitBtn = document.getElementById('btn-submit-arqueo');

        errorContainer.classList.add('hidden');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span> Guardando y Auditando Arqueo...';

        try {
            const res = await fetch('{{ route("pagos.authorizeArqueo") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    date: date,
                    fondo_base: fondoBase,
                    admin_pin: adminPin,
                    b100: b100,
                    b50: b50,
                    b20: b20,
                    b10: b10,
                    b5: b5,
                    b1: b1,
                    coins: coins,
                    observations: observations
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                closeArqueoModal();
                window.open(data.url, '_blank');
                
                showToast('success', data.message || '¡Arqueo oficial guardado en base de datos y generado!', 'Arqueo Guardado');

                if (typeof Swal !== 'undefined') {
                    const isCuad = (data.difference || 0) == 0;
                    const isFalt = (data.difference || 0) < 0;
                    const diffText = isCuad ? 'CUADRADO ($0.00)' : (isFalt ? `FALTANTE (-$${Math.abs(data.difference).toFixed(2)})` : `SOBRANTE (+$${parseFloat(data.difference).toFixed(2)})`);
                    const colorBadge = isCuad ? '#4ade80' : (isFalt ? '#ef4444' : '#facc15');

                    Swal.fire({
                        title: '¡Arqueo de Caja Guardado!',
                        html: `
                            <div style="text-align: center; font-size: 13px; color: #e2e2e2; line-height: 1.6;">
                                <p style="font-size: 16px; font-weight: bold; color: #4ade80;">Folio Oficial #${data.shift_id || ''}</p>
                                <p style="margin-top: 4px; font-size: 14px; font-weight: bold; color: ${colorBadge};">Estado: ${diffText}</p>
                                <p style="margin-top: 8px; color: #a1a1aa;">El arqueo ha sido registrado permanentemente en el historial de cierres de caja.</p>
                                <p style="font-size: 11px; color: #71717a; margin-top: 4px;">El documento PDF se abrió en una nueva pestaña listo para su firma e impresión.</p>
                            </div>
                        `,
                        icon: isCuad ? 'success' : 'warning',
                        confirmButtonText: 'Ver Historial de Cierres',
                        confirmButtonColor: '#e31b23',
                        background: '#151515',
                        color: '#e2e2e2'
                    }).then(() => {
                        window.location.href = "{{ route('pagos', ['tab' => 'cierres']) }}";
                    });
                } else {
                    window.location.href = "{{ route('pagos', ['tab' => 'cierres']) }}";
                }
            } else {
                errorText.textContent = data.message || 'Clave de administrador incorrecta.';
                errorContainer.classList.remove('hidden');
            }
        } catch (err) {
            console.error(err);
            errorText.textContent = 'Error de conexión al validar la clave del administrador.';
            errorContainer.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">print</span> Guardar y Generar PDF';
        }
    }
</script>
@endpush
