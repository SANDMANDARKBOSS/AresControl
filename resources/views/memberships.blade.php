@extends('layouts.admin')

@section('title', 'Membresías - Ares Gym')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .glass-card {
        background: rgba(31, 31, 31, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .table-row-hover:hover {
        background-color: rgba(255, 255, 255, 0.03);
    }
</style>
@endpush

@section('content')
<div class="flex flex-col w-full animate-on-load gap-8 pb-12">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <div class="flex items-center gap-2 text-primary tracking-widest font-etiqueta-sm uppercase mb-2">
                <span class="w-8 h-[1px] bg-primary"></span>
                <span>GESTIÓN COMERCIAL & CARTERA</span>
            </div>
            <h1 class="font-titular-xl text-[44px] md:text-[48px] text-on-surface uppercase tracking-tight leading-none" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">Membresías</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant mt-2">Control de planes, vigencias y cartera de atletas en tiempo real.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="button" onclick="openCatalogModal()" class="bg-surface-container-high text-on-surface border border-outline-variant/20 px-5 py-3 rounded-xl font-etiqueta-bold text-[13px] uppercase tracking-wider shadow-sm hover:bg-surface-container-highest hover:text-primary transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">edit_note</span> Editar Catálogo
            </button>
            <button type="button" onclick="openAssignModal()" class="bg-primary-container text-on-primary-container px-6 py-3 rounded-xl font-titular-md text-[15px] uppercase tracking-wider shadow-[0_4px_15px_rgba(227,27,35,0.3)] hover:shadow-[0_4px_25px_rgba(227,27,35,0.5)] hover:brightness-110 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">assignment_add</span> Nueva Asignación
            </button>
        </div>
    </div>

    <!-- SECCIÓN D: Panel de Estadísticas (KPIs y Gráfico Reales) -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- KPIs -->
        <div class="xl:col-span-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            
            <!-- KPI 1: Socios Activos -->
            <div class="glass-card rounded-2xl p-6 flex flex-col justify-between relative overflow-hidden group hover:-translate-y-1 transition-all border-l-4 border-l-[#4ade80]">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#4ade80]/10 rounded-full blur-xl group-hover:bg-[#4ade80]/20 transition-colors"></div>
                <div class="flex justify-between items-start z-10">
                    <span class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Suscripciones Activas</span>
                    <span class="material-symbols-outlined text-[#4ade80] text-[26px]">how_to_reg</span>
                </div>
                <div class="z-10 mt-3">
                    <h3 class="font-titular-xl text-[42px] text-on-surface leading-none font-extrabold">{{ $totalActiveMemberships }}</h3>
                    <p class="font-etiqueta-sm text-[#4ade80] mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">group</span> {{ $activeClientsCount }} Atletas con vigencia
                    </p>
                </div>
            </div>
            
            <!-- KPI 2: Plan Más Vendido -->
            <div class="glass-card rounded-2xl p-6 flex flex-col justify-between relative overflow-hidden group hover:-translate-y-1 transition-all border-l-4 border-l-primary">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary/10 rounded-full blur-xl group-hover:bg-primary/20 transition-colors"></div>
                <div class="flex justify-between items-start z-10">
                    <span class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Plan Más Popular</span>
                    <span class="material-symbols-outlined text-primary text-[26px]">emoji_events</span>
                </div>
                <div class="z-10 mt-3">
                    <h3 class="font-titular-md text-[20px] text-on-surface leading-tight font-bold line-clamp-1" title="{{ $topPlanName }}">{{ $topPlanName }}</h3>
                    <p class="font-etiqueta-sm text-primary mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">pie_chart</span> Representa el {{ $topPlanPercentage }}% del total
                    </p>
                </div>
            </div>

            <!-- KPI 3: Pendiente de Cobro -->
            <div class="glass-card rounded-2xl p-6 flex flex-col justify-between relative overflow-hidden group hover:-translate-y-1 transition-all border-l-4 border-l-[#facc15] sm:col-span-2 lg:col-span-1">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#facc15]/10 rounded-full blur-xl group-hover:bg-[#facc15]/20 transition-colors pointer-events-none"></div>
                <div class="flex justify-between items-start z-10">
                    <span class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Pendiente de Cobro</span>
                    <span class="material-symbols-outlined text-[#facc15] text-[26px]">alarm</span>
                </div>
                <div class="z-10 mt-3">
                    <h3 class="font-titular-xl text-[42px] text-on-surface leading-none font-extrabold text-[#facc15]">{{ $pendingDebtCountGlobal ?? $tabPendingPaymentCount }}</h3>
                    <p class="font-etiqueta-sm text-[#facc15] mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">payments</span> Atletas con saldo pendiente
                    </p>
                </div>
            </div>
        </div>

        <!-- Gráfico Distribución de Planes -->
        <div class="glass-card rounded-2xl p-6 flex flex-col justify-between relative overflow-hidden min-h-[220px]">
            <div class="flex justify-between items-center z-10 mb-2">
                <h3 class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Distribución de Planes</h3>
                <span class="text-[11px] font-mono text-on-surface-variant/80">{{ count($plansDistribution) }} Planes</span>
            </div>
            <div class="relative w-full h-[150px] flex items-center justify-center">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- SECCIÓN A: Catálogo Vigente de Planes -->
    <div class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
            <h2 class="font-titular-md text-[22px] text-on-surface font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">local_offer</span> Catálogo Vigente de Planes
            </h2>
            <span class="text-xs text-on-surface-variant font-mono">Precios y vigencias actualizadas</span>
        </div>
        
        <div class="flex overflow-x-auto hide-scrollbar gap-5 pb-2 snap-x">
            @forelse($plans as $p)
                @php
                    $isVip = str_contains(strtolower($p->name), 'anual') || str_contains(strtolower($p->name), 'vip');
                    $isGroup = $p->max_capacity > 1 || str_contains(strtolower($p->name), 'grupal');
                    $isDaily = $p->validity_days == 1 || str_contains(strtolower($p->name), 'diario');
                @endphp
                <div class="min-w-[270px] max-w-[290px] bg-surface-container rounded-2xl p-5 border {{ $isVip ? 'border-primary/50 ring-1 ring-primary/40' : 'border-outline-variant/10' }} flex flex-col justify-between snap-start hover:bg-surface-container-high transition-all group relative overflow-hidden">
                    @if($isVip)
                        <div class="absolute top-0 right-0 bg-primary text-on-primary font-etiqueta-bold text-[9px] px-3 py-0.5 rounded-bl-lg uppercase tracking-wider">VIP</div>
                    @endif
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <span class="font-etiqueta-bold text-[10px] uppercase tracking-widest {{ $isGroup ? 'text-[#fb923c]' : ($isVip ? 'text-primary' : 'text-on-surface-variant') }}">
                                {{ $isGroup ? "Grupal ({$p->min_capacity}-{$p->max_capacity})" : ($isDaily ? 'Pase Diario' : 'Individual') }}
                            </span>
                            <span class="material-symbols-outlined {{ $isVip ? 'text-primary' : 'text-on-surface-variant' }} text-[20px]">
                                {{ $isGroup ? 'groups' : ($isVip ? 'diamond' : ($isDaily ? 'confirmation_number' : 'person')) }}
                            </span>
                        </div>
                        <h3 class="font-titular-md text-[18px] text-on-surface font-bold line-clamp-1" title="{{ $p->name }}">{{ $p->name }}</h3>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="font-titular-xl text-[32px] text-primary font-extrabold">${{ number_format($p->price, 2) }}</span>
                            <span class="font-body-md text-on-surface-variant text-xs">/ {{ $p->validity_days }} {{ $p->validity_days == 1 ? 'día' : 'días' }}</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-outline-variant/10 flex flex-col gap-2.5">
                        @if($isDaily)
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-on-surface-variant">Visitas Totales:</span>
                                <span class="font-mono font-bold text-on-surface bg-surface-container-highest px-2 py-0.5 rounded">
                                    <span class="text-primary">{{ $dailyPassesCount }} pases registrados</span>
                                </span>
                            </div>
                            <div class="text-[10px]">
                                <span class="inline-block bg-primary/10 text-primary border border-primary/20 font-bold px-2 py-0.5 rounded">Pase Express de 1 Día</span>
                            </div>
                        @else
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-on-surface-variant">Suscripciones:</span>
                                <span class="font-mono font-bold text-on-surface bg-surface-container-highest px-2 py-0.5 rounded">
                                    <span class="text-[#4ade80]">{{ $p->active_memberships_count }} activas</span> / {{ $p->total_memberships_count ?? 0 }} total
                                </span>
                            </div>
                            <div class="text-[10px]">
                                <span class="inline-block bg-[#4ade80]/10 text-[#4ade80] border border-[#4ade80]/20 font-bold px-2 py-0.5 rounded">Incluye 2 medidas + Nutrición</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="w-full p-8 text-center bg-surface-container rounded-2xl text-on-surface-variant">
                    No se encontraron planes configurados en el catálogo.
                </div>
            @endforelse
        </div>
    </div>

    <!-- SECCIÓN C: Tabla de Cartera y Control de Vencimientos -->
    <div id="cartera-section" class="glass-card rounded-[24px] p-6 lg:p-8 flex flex-col gap-6 shadow-xl border border-outline-variant/10 relative overflow-hidden scroll-mt-6">
        <div class="absolute top-0 right-0 w-full h-full bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary/5 via-transparent to-transparent pointer-events-none"></div>
        
        <div class="flex flex-col gap-4 relative z-10">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h2 class="font-titular-md text-[24px] text-on-surface font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">calendar_month</span> Cartera Activa & Control de Vigencias
                    </h2>
                    <p class="font-body-md text-on-surface-variant text-sm">Monitoreo del semáforo de vigencias, estados por plan y gestión de cobro.</p>
                </div>
                
                <!-- Barra de Filtros: Plan + Búsqueda -->
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <!-- 1. Selector de Plan (Solo suscripciones recurrentes) -->
                    <div class="relative flex-1 sm:w-60 min-w-[200px]">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[18px] pointer-events-none">local_offer</span>
                        <select id="table-plan-select" onchange="handlePlanFilterChange(this.value)"
                                class="w-full bg-surface-container-lowest text-on-surface font-body-md pl-9 pr-8 py-2.5 rounded-xl border border-outline-variant/20 focus:border-primary/50 text-xs transition-all shadow-inner appearance-none cursor-pointer focus:outline-none">
                            <option value="">🏷️ Todos los Planes ({{ $globalTotalMemberships }})</option>
                            @foreach($subscriptionPlans as $pl)
                                <option value="{{ $pl->id }}" {{ $selectedPlanId == $pl->id ? 'selected' : '' }}>
                                    {{ $pl->name }} (${{ number_format($pl->price, 2) }}) [{{ $pl->total_memberships_count ?? 0 }}]
                                </option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[16px] pointer-events-none">expand_more</span>
                    </div>

                    <!-- 2. Barra de Búsqueda -->
                    <form onsubmit="handleTableSearchSubmit(event)" class="relative flex-1 sm:w-64 min-w-[220px]">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                        <input type="text" id="membership-search-input" value="{{ request('q') }}" placeholder="Buscar por Nombre, Cédula o Grupo..." 
                                class="w-full bg-surface-container-lowest text-on-surface font-body-md pl-10 pr-8 py-2.5 rounded-xl focus:outline-none border border-outline-variant/20 focus:border-primary/50 text-xs transition-all shadow-inner"
                                onkeyup="handleTableLiveSearch(this.value)">
                        @if(request('q'))
                            <button type="button" onclick="clearTableSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-error">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Barra de Semáforos / Pestañas de Cartera -->
            <div class="flex flex-wrap items-center gap-2 border-b border-outline-variant/10 pb-3">
                @php
                    $routeWithParams = function($newFilter) use ($selectedPlanId) {
                        $params = request()->except('page');
                        $params['filter'] = $newFilter;
                        if ($selectedPlanId) $params['plan_id'] = $selectedPlanId;
                        return route('memberships', $params) . '#cartera-section';
                    };
                @endphp
                <a href="{{ $routeWithParams('all') }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-etiqueta-bold uppercase tracking-wider transition-all {{ $filter === 'all' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container hover:bg-surface-container-high text-on-surface-variant' }}">
                    Todos ({{ $tabTotalCount }})
                </a>
                <a href="{{ $routeWithParams('active') }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-etiqueta-bold uppercase tracking-wider transition-all {{ $filter === 'active' ? 'bg-[#4ade80] text-black shadow-sm' : 'bg-surface-container hover:bg-surface-container-high text-[#4ade80]' }}">
                    ● Activas ({{ $tabActiveCount }})
                </a>
                <a href="{{ $routeWithParams('warning') }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-etiqueta-bold uppercase tracking-wider transition-all {{ $filter === 'warning' ? 'bg-[#facc15] text-black shadow-sm' : 'bg-surface-container hover:bg-surface-container-high text-[#facc15]' }}">
                    ▲ Alerta 5-7d ({{ $tabWarningCount }})
                </a>
                <a href="{{ $routeWithParams('critical') }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-etiqueta-bold uppercase tracking-wider transition-all {{ $filter === 'critical' ? 'bg-error text-white shadow-sm' : 'bg-surface-container hover:bg-surface-container-high text-error' }}">
                    ⚠ Crítico 1-3d ({{ $tabCriticalCount }})
                </a>
                <a href="{{ $routeWithParams('pending_payment') }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-etiqueta-bold uppercase tracking-wider transition-all {{ in_array($filter, ['pending_payment', 'expiring', 'debt']) ? 'bg-[#fb923c] text-black shadow-sm' : 'bg-surface-container hover:bg-surface-container-high text-[#fb923c]' }}">
                    💰 Requieren Cobro ({{ $tabPendingPaymentCount }})
                </a>
                <a href="{{ $routeWithParams('expired') }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-etiqueta-bold uppercase tracking-wider transition-all {{ $filter === 'expired' ? 'bg-surface-container-highest text-on-surface shadow-sm' : 'bg-surface-container hover:bg-surface-container-high text-on-surface-variant' }}">
                    ✕ Vencidas ({{ $tabExpiredCount }})
                </a>
                <a href="{{ $routeWithParams('groups') }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-etiqueta-bold uppercase tracking-wider transition-all {{ $filter === 'groups' ? 'bg-[#a855f7] text-white shadow-sm' : 'bg-surface-container hover:bg-surface-container-high text-[#a855f7]' }}">
                    👥 Grupales ({{ $tabGroupsCount }})
                </a>
            </div>

            <!-- Chips de Filtros Activos y Conteo de Resultados -->
            <div class="flex flex-wrap items-center gap-2 text-xs">
                @if($selectedPlan)
                    <span class="inline-flex items-center gap-1.5 bg-primary/15 text-primary border border-primary/30 px-2.5 py-1 rounded-lg font-etiqueta-bold text-[11px]">
                        <span>Plan: <strong>{{ $selectedPlan->name }}</strong></span>
                        <a href="{{ route('memberships', request()->except('plan_id', 'page')) }}#cartera-section" class="hover:text-white transition-colors" title="Quitar filtro de plan">
                            <span class="material-symbols-outlined text-[14px]">cancel</span>
                        </a>
                    </span>
                @endif

                @if(request('q'))
                    <span class="inline-flex items-center gap-1.5 bg-surface-container-highest text-on-surface border border-outline-variant/20 px-2.5 py-1 rounded-lg font-etiqueta-bold text-[11px]">
                        <span>Búsqueda: <strong>"{{ request('q') }}"</strong></span>
                        <a href="{{ route('memberships', request()->except('q', 'page')) }}#cartera-section" class="hover:text-error transition-colors" title="Quitar búsqueda">
                            <span class="material-symbols-outlined text-[14px]">cancel</span>
                        </a>
                    </span>
                @endif

                @if($selectedPlan || request('q') || $filter !== 'all')
                    <a href="{{ route('memberships') }}#cartera-section" class="text-xs text-on-surface-variant hover:text-primary transition-colors underline font-etiqueta-bold flex items-center gap-0.5 ml-1">
                        <span class="material-symbols-outlined text-[14px]">filter_alt_off</span> Limpiar todo
                    </a>
                @endif

                <span class="text-on-surface-variant font-mono text-[11px] ml-auto">
                    Mostrando <strong>{{ $memberships->total() }}</strong> resultado(s)
                </span>
            </div>
        </div>

        <div class="overflow-x-auto relative z-10 mt-2 pb-2">
            <table class="w-full text-left border-collapse min-w-[950px]" id="memberships-table">
                <thead>
                    <tr class="border-b border-outline-variant/20 text-on-surface-variant font-etiqueta-bold text-[11px] uppercase tracking-widest">
                        <th class="py-3.5 px-4 font-medium">Cliente / Grupo</th>
                        <th class="py-3.5 px-4 font-medium">Plan Contratado</th>
                        <th class="py-3.5 px-4 font-medium">Inicio</th>
                        <th class="py-3.5 px-4 font-medium">Fin</th>
                        <th class="py-3.5 px-4 font-medium">Semáforo de Vigencia</th>
                        <th class="py-3.5 px-4 font-medium">Estado de Pago</th>
                        <th class="py-3.5 px-4 font-medium text-right">Acciones Rápidas</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-on-surface text-xs divide-y divide-outline-variant/10" id="memberships-tbody">
                    @forelse($memberships as $m)
                        @php
                            $todayObj = \Carbon\Carbon::today();
                            $endDateObj = \Carbon\Carbon::parse($m->end_date)->startOfDay();
                            $startDateObj = \Carbon\Carbon::parse($m->start_date)->startOfDay();
                            $diffDays = (int)$todayObj->diffInDays($endDateObj, false);

                            $isExpired = ($diffDays < 0) || in_array($m->status, ['Vencida', 'Inactiva', 'Cancelada']);
                            $isCritical = !$isExpired && ($diffDays >= 0 && $diffDays <= 3);
                            $isWarning = !$isExpired && ($diffDays >= 4 && $diffDays <= 7);
                            $isNormal = !$isExpired && ($diffDays > 7);

                            $firstClient = $m->clients->first();
                            $clientCount = $m->clients->count();
                            $isGroup = !empty($m->group_name) || $clientCount > 1;

                            // Total and Paid calculations
                            $planPrice = (float)($m->plan->price ?? 0);
                            $totalContractAmount = $isGroup ? ($planPrice * max(1, $clientCount)) : $planPrice;
                            $totalPaid = (float)$m->payments->sum('amount');
                            $pendingBalance = (float)($m->payments->sortByDesc('id')->first()->pending_balance ?? max(0, $totalContractAmount - $totalPaid));
                            $isFullyPaid = $totalPaid >= $totalContractAmount || $pendingBalance <= 0;
                            $isAbono = $totalPaid > 0 && $pendingBalance > 0;

                            // Phone for WhatsApp
                            $rawPhone = $firstClient ? ($firstClient->phone ?? '') : '';
                            $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                            $waPhone = str_starts_with($cleanPhone, '593') ? $cleanPhone : ('593' . ltrim($cleanPhone, '0'));
                            
                            $waMsg = "🏋️‍♂️ *ARES GYM - RECORDATORIO DE MEMBRESÍA* 🏋️‍♂️\n";
                            $waMsg .= "Hola " . ($firstClient ? $firstClient->name : 'Atleta') . ", te saludamos desde Ares Gym.\n";
                            $waMsg .= "Te recordamos que tu plan *" . ($m->plan->name ?? 'Membresía') . "* ";
                            if ($isExpired) {
                                $waMsg .= "venció el *" . $endDateObj->format('d/m/Y') . "* (hace " . abs($diffDays) . " días).\n";
                                $waMsg .= "¡Renueva tu suscripción para seguir entrenando con todo el power! 💪🔥";
                            } elseif ($diffDays === 0) {
                                $waMsg .= "*¡VENCE HOY " . $endDateObj->format('d/m/Y') . "*!\n";
                                $waMsg .= "¡Renueva a tiempo para no perder tus beneficios y medidas! 💪🔥";
                            } else {
                                $waMsg .= "vence el *" . $endDateObj->format('d/m/Y') . "* (" . $diffDays . " días restantes).\n";
                                $waMsg .= "¡Estamos listos para apoyarte en tu disciplina y metas! 💪🔥";
                            }
                            $waUrl = "https://wa.me/{$waPhone}?text=" . urlencode($waMsg);
                        @endphp
                        <tr class="table-row-hover transition-colors group membership-row" 
                            data-search="{{ strtolower(($isGroup ? $m->group_name . ' ' : '') . ($firstClient ? $firstClient->name . ' ' . $firstClient->last_name . ' ' . $firstClient->id_card . ' ' . $firstClient->phone : '') . ' ' . ($m->plan->name ?? '')) }}">
                            
                            <!-- Cliente / Grupo (En grupos muestra SOLAMENTE el nombre del grupo y badge) -->
                            <td class="py-3.5 px-4">
                                @if($isGroup)
                                    <div class="font-titular-md text-sm font-bold flex items-center gap-2 text-on-surface">
                                        <span class="material-symbols-outlined text-[#fb923c] text-[18px]">groups</span>
                                        <span>{{ $m->group_name ?: 'Grupo ' . ($m->plan->name ?? '') }}</span>
                                        <span class="bg-[#fb923c]/15 text-[#fb923c] text-[10px] px-2 py-0.5 rounded-full font-mono font-bold">{{ $clientCount }} Atletas</span>
                                    </div>
                                    <div class="text-[11px] text-on-surface-variant/80 font-body-md mt-0.5 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[13px] text-primary">badge</span> Plan Grupal &middot; Fecha de corte unificada
                                    </div>
                                @else
                                    <div class="font-titular-md text-sm font-bold text-on-surface">
                                        {{ $firstClient ? ($firstClient->name . ' ' . $firstClient->last_name) : 'Atleta sin asignar' }}
                                    </div>
                                    <div class="text-[11px] text-on-surface-variant font-mono mt-0.5">
                                        {{ $firstClient ? 'C.I. ' . $firstClient->id_card : '---' }}
                                        @if($firstClient && $firstClient->phone)
                                            <span class="text-on-surface-variant/50">|</span> {{ $firstClient->phone }}
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <!-- Plan Contratado -->
                            <td class="py-3.5 px-4">
                                <a href="{{ route('memberships', ['plan_id' => $m->plan_id]) }}#cartera-section" class="font-bold text-on-surface hover:text-primary transition-colors flex items-center gap-1">
                                    <span>{{ $m->plan->name ?? 'Plan Personalizado' }}</span>
                                </a>
                                <div class="text-[10px] text-primary font-mono font-bold">${{ number_format($planPrice, 2) }} / {{ $m->plan->validity_days ?? 30 }}d</div>
                            </td>

                            <!-- Inicio -->
                            <td class="py-3.5 px-4 text-on-surface-variant font-mono text-[11px]">
                                {{ $startDateObj->format('d M, Y') }}
                            </td>

                            <!-- Fecha de Corte (Unificada para el grupo o atleta) -->
                            <td class="py-3.5 px-4 font-mono text-[11px] {{ $isExpired ? 'text-error font-bold' : 'text-on-surface font-bold' }}">
                                {{ $endDateObj->format('d M, Y') }}
                            </td>

                            <!-- Semáforo / Días Restantes -->
                            <td class="py-3.5 px-4">
                                @if($isExpired)
                                    <span class="inline-flex items-center gap-1.5 text-error bg-error/10 px-2.5 py-1 rounded-lg font-etiqueta-bold text-[11px] border border-error/20">
                                        <span class="w-2 h-2 rounded-full bg-error ring-4 ring-error/20"></span>
                                        @if($diffDays < 0)
                                            Vencida (hace {{ abs($diffDays) }} {{ abs($diffDays) === 1 ? 'día' : 'días' }})
                                        @else
                                            Vencida / Inactiva
                                        @endif
                                    </span>
                                @elseif($isCritical)
                                    <span class="inline-flex items-center gap-1.5 text-error bg-error/10 px-2.5 py-1 rounded-lg font-etiqueta-bold text-[11px] border border-error/30 animate-pulse">
                                        <span class="w-2 h-2 rounded-full bg-error ring-4 ring-error/20"></span>
                                        @if($diffDays === 0)
                                            ¡Vence Hoy!
                                        @elseif($diffDays === 1)
                                            Vence Mañana (1d)
                                        @else
                                            {{ $diffDays }} días (Crítico)
                                        @endif
                                    </span>
                                @elseif($isWarning)
                                    <span class="inline-flex items-center gap-1.5 text-[#facc15] bg-[#facc15]/10 px-2.5 py-1 rounded-lg font-etiqueta-bold text-[11px] border border-[#facc15]/30">
                                        <span class="w-2 h-2 rounded-full bg-[#facc15] ring-4 ring-[#facc15]/20"></span>
                                        {{ $diffDays }} días (Por vencer)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-[#4ade80] bg-[#4ade80]/10 px-2.5 py-1 rounded-lg font-etiqueta-bold text-[11px] border border-[#4ade80]/30">
                                        <span class="w-2 h-2 rounded-full bg-[#4ade80] ring-4 ring-[#4ade80]/20"></span>
                                        {{ $diffDays }} días activa
                                    </span>
                                @endif
                            </td>

                            <!-- Estado de Pago -->
                            <td class="py-3.5 px-4">
                                @if($isFullyPaid)
                                    <span class="inline-flex items-center gap-1 text-[#4ade80] font-etiqueta-bold text-[11px] uppercase tracking-wider">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Pagado (${{ number_format($totalPaid, 2) }})
                                    </span>
                                @elseif($isAbono)
                                    <div class="flex flex-col">
                                        <span class="text-[#facc15] font-etiqueta-bold text-[11px] uppercase tracking-wider flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">pending</span> Abono (${{ number_format($totalPaid, 2) }})
                                        </span>
                                        <span class="text-[10px] text-error font-mono font-bold">Saldo: ${{ number_format($pendingBalance, 2) }}</span>
                                    </div>
                                @else
                                    <span class="text-error font-etiqueta-bold text-[11px] uppercase tracking-wider flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">cancel</span> Pendiente ($0)
                                    </span>
                                @endif
                            </td>

                            <!-- Acciones Rápidas -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Botón WhatsApp Recordatorio -->
                                    @if(!empty($cleanPhone))
                                        <a href="{{ $waUrl }}" target="_blank" title="Enviar recordatorio a WhatsApp" 
                                           class="bg-[#25D366]/10 hover:bg-[#25D366] text-[#25D366] hover:text-white p-2 rounded-lg transition-all flex items-center justify-center">
                                            <i class="fab fa-whatsapp text-[14px]"></i>
                                        </a>
                                    @endif

                                    @if($pendingBalance > 0)
                                        <!-- Botón Liquidar Deuda Rápido -->
                                        <button type="button" onclick="openRenewModal({{ $m->id }}, '{{ addslashes($isGroup ? ($m->group_name ?: 'Grupo ' . ($m->plan->name ?? '')) : ($firstClient ? ($firstClient->name . ' ' . $firstClient->last_name) : 'Atleta')) }}', '{{ addslashes($m->plan->name ?? '') }}', {{ $totalContractAmount }}, '{{ $m->end_date }}', {{ $m->plan->validity_days ?? 30 }}, {{ $pendingBalance }}, true)" 
                                                class="bg-error/15 hover:bg-error text-error hover:text-white px-2.5 py-1.5 rounded-lg font-etiqueta-bold text-[11px] uppercase tracking-wider transition-all flex items-center gap-1 border border-error/30 shadow-sm"
                                                title="Liquidar saldo adeudado">
                                            <span class="material-symbols-outlined text-[13px]">price_check</span> Cobrar Deuda
                                        </button>
                                    @endif

                                    <!-- Botón Renovar Rápido (Uno por uno) -->
                                    <button type="button" onclick="openRenewModal({{ $m->id }}, '{{ addslashes($isGroup ? ($m->group_name ?: 'Grupo ' . ($m->plan->name ?? '')) : ($firstClient ? ($firstClient->name . ' ' . $firstClient->last_name) : 'Atleta')) }}', '{{ addslashes($m->plan->name ?? '') }}', {{ $totalContractAmount }}, '{{ $m->end_date }}', {{ $m->plan->validity_days ?? 30 }}, {{ $pendingBalance }}, false)" 
                                            class="bg-primary-container/20 hover:bg-primary text-on-primary-container hover:text-on-primary px-2.5 py-1.5 rounded-lg font-etiqueta-bold text-[11px] uppercase tracking-wider transition-all flex items-center gap-1 border border-primary/30 shadow-sm">
                                        <span class="material-symbols-outlined text-[13px]">refresh</span> Renovar
                                    </button>

                                    <!-- Botón Eliminar Membresía -->
                                    <button type="button" onclick="confirmDeleteMembership({{ $m->id }})" title="Eliminar suscripción" 
                                            class="text-on-surface-variant hover:text-error hover:bg-error/10 p-1.5 rounded-lg transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-on-surface-variant font-body-md">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[42px] text-outline-variant">folder_off</span>
                                    <span>No se encontraron membresías que coincidan con los filtros seleccionados.</span>
                                    @if($selectedPlan || request('q') || $filter !== 'all')
                                        <a href="{{ route('memberships') }}#cartera-section" class="mt-2 text-primary font-bold text-xs hover:underline flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">refresh</span> Restablecer filtros
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($memberships->hasPages())
            <div class="relative z-10 pt-4 border-t border-outline-variant/10">
                {{ $memberships->links() }}
            </div>
        @endif
    </div>
</div>

<!-- MODAL 1: Nueva Asignación de Membresía (Rediseñado - Uno a Uno con Buscador + Combobox y Autorización de Abono) -->
<div id="assignModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm" onclick="closeAssignModal()"></div>
    <div class="bg-surface-container-low rounded-[24px] shadow-2xl border border-outline-variant/20 overflow-hidden flex flex-col max-h-[92vh] w-[92%] max-w-2xl relative z-10 animate-fade-in">
        
        <!-- Header -->
        <div class="p-6 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-bl-full blur-2xl pointer-events-none"></div>
            <div>
                <h2 class="font-titular-xl text-[24px] text-on-surface leading-none font-bold">Nueva Asignación de Membresía</h2>
                <p class="font-body-md text-on-surface-variant text-xs mt-1">Asignar suscripción individual a un atleta registrado.</p>
            </div>
            <button type="button" onclick="closeAssignModal()" class="w-9 h-9 rounded-full hover:bg-surface-container-high flex items-center justify-center text-on-surface-variant transition-colors z-10">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        
        <!-- Form Body -->
        <form id="assign-membership-form" onsubmit="handleAssignFormSubmit(event)" class="p-6 overflow-y-auto flex flex-col gap-5">
            @csrf
            <input type="hidden" name="client_id" id="selected-single-client-id" required>
            
            <!-- 1. Selección de Atleta (Búsqueda Predictiva + Combo Box Desplegable) -->
            <div class="flex flex-col gap-2">
                <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[11px] flex justify-between items-center">
                    <span>1. Seleccionar Atleta</span>
                    <span class="text-[10px] text-primary font-bold">Asignación Individual (1 a 1)</span>
                </label>

                <!-- Tarjeta de Atleta Seleccionado (Si ya se eligió uno) -->
                <div id="selected-athlete-box" class="hidden p-3.5 bg-primary/10 rounded-xl border border-primary/30 flex items-center justify-between animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm">
                            <span class="material-symbols-outlined text-[20px]">person</span>
                        </div>
                        <div class="flex flex-col">
                            <span id="selected-athlete-name" class="font-titular-md text-sm font-bold text-on-surface">---</span>
                            <span id="selected-athlete-info" class="font-mono text-xs text-on-surface-variant">C.I. ---</span>
                        </div>
                    </div>
                    <button type="button" onclick="clearSelectedAthlete()" class="text-xs text-on-surface-variant hover:text-error bg-surface-container px-3 py-1.5 rounded-lg font-etiqueta-bold uppercase tracking-wider transition-colors">
                        Cambiar
                    </button>
                </div>

                <!-- Controles de Selección de Atleta (Buscador + Select) -->
                <div id="athlete-select-controls" class="flex flex-col gap-2">
                    <!-- A. Buscador Predictivo -->
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                        <input type="text" id="assign-client-search" placeholder="Escribe nombre o cédula para buscar atleta..." 
                               class="w-full bg-surface-container-lowest text-on-surface font-body-md pl-10 pr-4 py-2.5 rounded-xl focus:outline-none border border-outline-variant/20 focus:border-primary/50 text-xs transition-all shadow-inner"
                               oninput="handleClientSearchInput(this.value)">
                        
                        <!-- Search Results Dropdown -->
                        <div id="assign-client-results" class="absolute left-0 right-0 top-full mt-1 bg-surface-container-high rounded-xl shadow-2xl border border-outline-variant/20 max-h-48 overflow-y-auto hidden z-30"></div>
                    </div>

                    <!-- B. Combo Box Desplegable Completo -->
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px] pointer-events-none">list</span>
                        <select id="assign-client-combobox" onchange="handleClientComboboxChange(this)"
                                class="w-full bg-surface-container-lowest text-on-surface font-body-md pl-10 pr-8 py-2.5 rounded-xl border border-outline-variant/20 focus:outline-none focus:border-primary/50 text-xs appearance-none cursor-pointer">
                            <option value="">-- O selecciona un atleta del listado ({{ count($allClients) }}) --</option>
                            @foreach($allClients as $cl)
                                <option value="{{ $cl->id }}" data-name="{{ $cl->name }} {{ $cl->last_name }}" data-card="{{ $cl->id_card }}" data-phone="{{ $cl->phone ?? '' }}">
                                    {{ $cl->name }} {{ $cl->last_name }} — C.I. {{ $cl->id_card }}
                                </option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[16px] pointer-events-none">expand_more</span>
                    </div>
                </div>
            </div>

            <!-- 2. Selección de Plan (Solo suscripciones recurrentes) -->
            <div class="flex flex-col gap-1.5">
                <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[11px]">2. Seleccionar Plan de Suscripción</label>
                <div class="relative">
                    <select name="plan_id" id="assign-plan-select" required onchange="handleAssignPlanChange(this)"
                            class="w-full bg-surface-container-lowest text-on-surface font-body-md p-3 rounded-xl border border-outline-variant/20 focus:outline-none focus:border-primary/50 text-xs appearance-none cursor-pointer">
                        <option value="" disabled selected>Elige un plan del catálogo...</option>
                        @foreach($subscriptionPlans as $p)
                            <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-days="{{ $p->validity_days }}" data-cap="{{ $p->max_capacity }}">
                                {{ $p->name }} — ${{ number_format($p->price, 2) }} ({{ $p->validity_days }} días)
                            </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[16px] pointer-events-none">expand_more</span>
                </div>
            </div>

            <!-- 3. Fechas de Inicio y Corte (Ambas Editables y Obligatorias) -->
            <div class="bg-surface-container p-4 rounded-xl border border-outline-variant/10 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1">
                    <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px]">
                        Fecha de Inicio <span class="text-error font-bold">*</span>
                    </label>
                    <input type="date" name="start_date" id="assign-start-date" value="{{ date('Y-m-d') }}" onchange="handleAssignStartDateChange()" required
                           class="bg-surface-container-lowest text-on-surface font-body-md p-2.5 rounded-lg focus:outline-none border border-outline-variant/20 focus:border-primary/50 text-xs">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="font-etiqueta-sm text-primary uppercase tracking-widest text-[10px] flex items-center justify-between">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">edit_calendar</span> Fecha de Corte (Editable) <span class="text-error font-bold">*</span>
                        </span>
                    </label>
                    <input type="date" name="end_date" id="assign-end-date" required onchange="handleAssignEndDateChange()"
                           class="bg-surface-container-lowest text-on-surface font-body-md p-2.5 rounded-lg border border-outline-variant/20 focus:border-primary/50 text-xs font-bold focus:outline-none">
                </div>
            </div>

            <!-- Banner de Error de Validación en Formulario -->
            <div id="assign-form-error-banner" class="hidden p-3.5 bg-error/15 border border-error/30 rounded-xl text-error text-xs font-body-md flex items-center gap-2 animate-fade-in">
                <span class="material-symbols-outlined text-[18px] shrink-0">error</span>
                <span id="assign-form-error-text">Por favor complete todos los campos obligatorios para registrar la asignación.</span>
            </div>

            <!-- 4. Pago y Abono con Autorización -->
            <div class="bg-surface-container p-4 rounded-xl border border-outline-variant/10 flex flex-col gap-3">
                <div class="flex justify-between items-center text-xs">
                    <span class="font-etiqueta-bold text-on-surface uppercase">Monto Total del Plan:</span>
                    <span class="font-titular-md text-primary text-lg font-bold" id="assign-total-display">$0.00</span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 bg-surface-container-lowest p-2.5 rounded-lg border border-outline-variant/15 cursor-pointer">
                        <input type="radio" name="payment_type" value="completo" checked onchange="toggleAssignAbono(false)" class="text-primary focus:ring-primary">
                        <span class="text-xs font-bold text-on-surface">Pago Completo</span>
                    </label>
                    <label class="flex items-center gap-2 bg-surface-container-lowest p-2.5 rounded-lg border border-outline-variant/15 cursor-pointer">
                        <input type="radio" name="payment_type" id="assign-payment-type-abono" value="abono" onchange="toggleAssignAbono(true)" class="text-primary focus:ring-primary">
                        <div class="flex items-center gap-1">
                            <span class="text-xs font-bold text-on-surface">Abono Parcial</span>
                            <span id="assign-auth-badge" class="hidden text-[9px] text-[#4ade80] bg-[#4ade80]/15 px-1.5 py-0.5 rounded font-bold uppercase">✓ Autorizado</span>
                        </div>
                    </label>
                </div>

                <!-- Abono Range Box -->
                <div id="assign-abono-box" class="hidden flex-col gap-2 p-3 bg-surface-container-lowest rounded-lg border border-outline-variant/15 animate-fade-in">
                    <div class="flex justify-between items-center text-[10px] text-on-surface-variant font-mono">
                        <span>Rango permitido: 25% a 75%</span>
                        <span>Mín: $<span id="assign-abono-min">0.00</span> — Máx: $<span id="assign-abono-max">0.00</span></span>
                    </div>
                    <input type="number" step="0.01" name="abono_amount" id="assign-abono-input" placeholder="0.00" 
                           class="w-full bg-surface-container text-on-surface font-headline-md text-base px-3 py-2 rounded-lg border border-outline-variant/20 focus:outline-none focus:border-primary/50"
                           onkeydown="if(['e','E','+','-'].includes(event.key)) event.preventDefault();">
                </div>

                <!-- Método de Pago -->
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 bg-surface-container-lowest p-2.5 rounded-lg border border-outline-variant/15 cursor-pointer">
                        <input type="radio" name="payment_method" value="efectivo" checked onchange="toggleAssignTransfer(false)" class="text-primary focus:ring-primary">
                        <span class="text-xs text-on-surface">Efectivo 💵</span>
                    </label>
                    <label class="flex items-center gap-2 bg-surface-container-lowest p-2.5 rounded-lg border border-outline-variant/15 cursor-pointer">
                        <input type="radio" name="payment_method" value="transferencia" onchange="toggleAssignTransfer(true)" class="text-primary focus:ring-primary">
                        <span class="text-xs text-on-surface">Transferencia 🏦</span>
                    </label>
                </div>

                <!-- Voucher Transferencia -->
                <div id="assign-voucher-box" class="hidden flex-col gap-1 animate-fade-in">
                    <label class="text-[10px] text-on-surface-variant uppercase font-etiqueta-bold">N° de Comprobante / Referencia</label>
                    <input type="text" name="voucher_number" id="assign-voucher-input" placeholder="Ej. 192837465"
                           class="w-full bg-surface-container-lowest text-on-surface p-2.5 rounded-lg border border-outline-variant/20 focus:outline-none focus:border-primary/50 text-xs">
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeAssignModal()" class="text-on-surface-variant px-5 py-3 font-etiqueta-bold text-xs uppercase tracking-wider hover:bg-surface-container rounded-xl transition-colors">
                    Cancelar
                </button>
                <button type="submit" id="btn-save-assign" class="bg-primary text-on-primary px-7 py-3 rounded-xl font-titular-md text-xs uppercase tracking-wider shadow-[0_4px_15px_rgba(227,27,35,0.3)] hover:shadow-[0_4px_25px_rgba(227,27,35,0.5)] hover:brightness-110 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">save</span> Guardar Asignación
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: Renovación Rápida & Liquidación de Deuda -->
<div id="renewModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm" onclick="closeRenewModal()"></div>
    <div class="bg-surface-container-low rounded-[24px] shadow-2xl border border-outline-variant/20 overflow-hidden flex flex-col w-[92%] max-w-md relative z-10 animate-fade-in">
        <div class="p-6 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container">
            <div>
                <h2 id="renew-modal-title" class="font-titular-xl text-[20px] text-on-surface font-bold">Renovar Membresía</h2>
                <p id="renew-client-name" class="font-body-md text-on-surface-variant text-xs mt-0.5">Atleta</p>
            </div>
            <button type="button" onclick="closeRenewModal()" class="w-8 h-8 rounded-full hover:bg-surface-container-high flex items-center justify-center text-on-surface-variant">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <form id="renew-form" onsubmit="handleRenewSubmit(event)" class="p-6 flex flex-col gap-4">
            @csrf
            <input type="hidden" id="renew-membership-id" name="membership_id">
            <input type="hidden" id="renew-action-mode" name="action_mode" value="renew">

            <!-- Banner Alerta de Deuda -->
            <div id="renew-debt-warning-box" class="hidden p-3.5 bg-error/15 border border-error/30 rounded-xl flex-col gap-2.5 animate-fade-in">
                <div class="flex items-center gap-2 text-error font-bold text-xs">
                    <span class="material-symbols-outlined text-[20px] shrink-0">warning</span>
                    <span>Saldo Pendiente Anterior: <strong id="renew-debt-amount-text" class="font-mono text-sm">$0.00</strong></span>
                </div>
                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-error/20 text-xs">
                    <label class="flex items-center gap-1.5 cursor-pointer bg-surface-container-lowest p-2 rounded-lg border border-outline-variant/15">
                        <input type="radio" name="renew_debt_choice" value="renew" checked onchange="handleRenewDebtChoice('renew')" class="text-primary">
                        <span class="font-bold text-on-surface text-[11px]">Deuda + Nuevo Mes</span>
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer bg-surface-container-lowest p-2 rounded-lg border border-outline-variant/15">
                        <input type="radio" name="renew_debt_choice" value="only_debt" onchange="handleRenewDebtChoice('only_debt')" class="text-primary">
                        <span class="font-bold text-error text-[11px]">Solo Liquidar Deuda</span>
                    </label>
                </div>
            </div>

            <div class="bg-surface-container p-3.5 rounded-xl border border-outline-variant/10 flex flex-col gap-1.5 text-xs">
                <div class="flex justify-between"><span class="text-on-surface-variant">Plan Contratado:</span> <span id="renew-plan-name" class="font-bold text-on-surface">---</span></div>
                <div class="flex justify-between items-baseline"><span class="text-on-surface-variant">Total a Cancelar:</span> <span id="renew-total-price" class="font-bold text-primary text-[17px] font-mono">$0.00</span></div>
                <div id="renew-date-row" class="flex justify-between"><span class="text-on-surface-variant">Nueva Fecha de Corte:</span> <span id="renew-new-date" class="font-bold text-[#4ade80]">---</span></div>
            </div>

            <!-- Modalidad de Pago: Completo vs Abono -->
            <div id="renew-payment-type-group" class="grid grid-cols-2 gap-2">
                <label class="flex items-center gap-2 bg-surface-container-lowest p-2.5 rounded-lg border border-outline-variant/15 cursor-pointer text-xs">
                    <input type="radio" name="payment_type" value="completo" checked onchange="toggleRenewAbono(false)" class="text-primary"> 
                    <span class="font-bold">Pago Completo</span>
                </label>
                <label class="flex items-center gap-2 bg-surface-container-lowest p-2.5 rounded-lg border border-outline-variant/15 cursor-pointer text-xs">
                    <input type="radio" name="payment_type" id="renew-payment-type-abono" value="abono" onchange="toggleRenewAbono(true)" class="text-primary"> 
                    <div class="flex items-center gap-1">
                        <span class="font-bold">Abono</span>
                        <span id="renew-auth-badge" class="hidden text-[9px] text-[#4ade80] bg-[#4ade80]/15 px-1 py-0.5 rounded font-bold uppercase">✓</span>
                    </div>
                </label>
            </div>

            <!-- Renew Abono Box -->
            <div id="renew-abono-box" class="hidden flex-col gap-1.5 p-3 bg-surface-container-lowest rounded-lg border border-outline-variant/15 animate-fade-in">
                <div class="flex justify-between items-center text-[10px] text-on-surface-variant font-mono">
                    <span>Mín (25%): $<span id="renew-abono-min">0.00</span></span>
                    <span>Máx (75%): $<span id="renew-abono-max">0.00</span></span>
                </div>
                <input type="number" step="0.01" name="abono_amount" id="renew-abono-input" placeholder="Monto del abono" 
                       class="w-full bg-surface-container text-on-surface font-headline-md text-sm px-3 py-2 rounded-lg border border-outline-variant/20 focus:outline-none focus:border-primary/50">
            </div>

            <!-- Método de Pago -->
            <div class="grid grid-cols-2 gap-2">
                <label class="flex items-center gap-2 bg-surface-container-lowest p-2.5 rounded-lg border border-outline-variant/15 cursor-pointer text-xs">
                    <input type="radio" name="payment_method" value="efectivo" checked onchange="toggleRenewTransfer(false)" class="text-primary"> Efectivo 💵
                </label>
                <label class="flex items-center gap-2 bg-surface-container-lowest p-2.5 rounded-lg border border-outline-variant/15 cursor-pointer text-xs">
                    <input type="radio" name="payment_method" value="transferencia" onchange="toggleRenewTransfer(true)" class="text-primary"> Transf. 🏦
                </label>
            </div>

            <!-- Renew Voucher Input -->
            <div id="renew-voucher-box" class="hidden flex-col gap-1 animate-fade-in">
                <input type="text" name="voucher_number" id="renew-voucher-input" placeholder="N° de Comprobante / Voucher"
                       class="w-full bg-surface-container-lowest text-on-surface p-2 rounded-lg border border-outline-variant/20 text-xs">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeRenewModal()" class="text-on-surface-variant px-4 py-2.5 font-etiqueta-bold text-xs uppercase hover:bg-surface-container rounded-xl">Cancelar</button>
                <button type="submit" id="btn-submit-renew" class="bg-primary text-on-primary px-6 py-2.5 rounded-xl font-titular-md text-xs uppercase tracking-wider shadow-sm hover:brightness-110 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">refresh</span> <span id="renew-btn-text">Confirmar Renovación</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 3: Gestor del Catálogo de Planes (Crear, Editar y Eliminar) -->
<div id="catalogModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm" onclick="closeCatalogModal()"></div>
    <div class="bg-surface-container-low rounded-[24px] shadow-2xl border border-outline-variant/20 overflow-hidden flex flex-col w-[95%] max-w-4xl max-h-[92vh] relative z-10 animate-fade-in">
        
        <!-- Modal Header -->
        <div class="p-6 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-bl-full blur-2xl pointer-events-none"></div>
            <div>
                <h2 class="font-titular-xl text-[22px] text-on-surface font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">edit_document</span> Gestor del Catálogo de Planes
                </h2>
                <p class="font-body-md text-on-surface-variant text-xs mt-0.5">Crea nuevos planes, ajusta precios y vigencias en todo el sistema.</p>
            </div>
            <button type="button" onclick="closeCatalogModal()" class="w-8 h-8 rounded-full hover:bg-surface-container-high flex items-center justify-center text-on-surface-variant">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex flex-col gap-6">
            
            <!-- 1. SECCIÓN: Crear Nuevo Plan -->
            <div class="bg-surface-container p-5 rounded-2xl border border-primary/20 relative overflow-hidden">
                <div class="flex items-center gap-2 text-primary font-etiqueta-bold text-xs uppercase tracking-wider mb-4">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>Crear Nuevo Plan para el Catálogo</span>
                </div>

                <form id="create-plan-form" onsubmit="handleCreatePlanSubmit(event)" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 items-end">
                    @csrf
                    <div class="md:col-span-2">
                        <label class="text-[10px] text-on-surface-variant uppercase font-etiqueta-bold">Nombre del Plan <span class="text-error">*</span></label>
                        <input type="text" name="name" required placeholder="Ej. Plan Trimestral Parejas" 
                               class="w-full bg-surface-container-lowest text-on-surface p-2.5 rounded-xl border border-outline-variant/20 text-xs font-bold focus:outline-none focus:border-primary/50 shadow-inner">
                    </div>
                    <div>
                        <label class="text-[10px] text-on-surface-variant uppercase font-etiqueta-bold">Precio ($) <span class="text-error">*</span></label>
                        <input type="number" step="0.01" min="0.50" name="price" required placeholder="0.00" 
                               class="w-full bg-surface-container-lowest text-primary p-2.5 rounded-xl border border-outline-variant/20 text-xs font-extrabold focus:outline-none focus:border-primary/50 shadow-inner">
                    </div>
                    <div>
                        <label class="text-[10px] text-on-surface-variant uppercase font-etiqueta-bold">Vigencia (Días) <span class="text-error">*</span></label>
                        <input type="number" min="1" name="validity_days" value="30" required 
                               class="w-full bg-surface-container-lowest text-on-surface p-2.5 rounded-xl border border-outline-variant/20 text-xs focus:outline-none focus:border-primary/50 shadow-inner">
                    </div>
                    <div class="flex gap-2">
                        <div class="w-1/2">
                            <label class="text-[9px] text-on-surface-variant uppercase font-etiqueta-bold">Cap. Mín</label>
                            <input type="number" min="1" name="min_capacity" value="1" required 
                                   class="w-full bg-surface-container-lowest text-on-surface p-2.5 rounded-xl border border-outline-variant/20 text-xs focus:outline-none text-center">
                        </div>
                        <div class="w-1/2">
                            <label class="text-[9px] text-on-surface-variant uppercase font-etiqueta-bold">Cap. Máx</label>
                            <input type="number" min="1" name="max_capacity" value="1" required 
                                   class="w-full bg-surface-container-lowest text-on-surface p-2.5 rounded-xl border border-outline-variant/20 text-xs focus:outline-none text-center">
                        </div>
                    </div>
                    
                    <!-- Error Box for Create Plan -->
                    <div id="create-plan-error-box" class="hidden sm:col-span-2 md:col-span-5 p-3 bg-error/15 border border-error/30 rounded-xl text-error text-xs font-body-md flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] shrink-0">error</span>
                        <span id="create-plan-error-text">Error al crear el plan.</span>
                    </div>

                    <div class="sm:col-span-2 md:col-span-5 flex justify-end mt-1">
                        <button type="submit" id="btn-create-plan" 
                                class="bg-primary hover:bg-[#ff2a35] text-on-primary px-6 py-2.5 rounded-xl font-titular-md text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2 shadow-[0_0_15px_rgba(227,27,35,0.3)]">
                            <span class="material-symbols-outlined text-[16px]">add</span> Crear y Publicar Plan
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. SECCIÓN: Planes Actuales en el Catálogo -->
            <div class="flex flex-col gap-3">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-etiqueta-bold text-on-surface uppercase tracking-wider">
                        Planes Existentes en Catálogo ({{ count($plans) }})
                    </span>
                    <span class="text-[11px] text-on-surface-variant font-mono">Modifica valores o elimina planes obsoletos</span>
                </div>

                <div class="flex flex-col gap-3">
                    @forelse($plans as $plan)
                        <form onsubmit="handlePlanUpdateSubmit(event, {{ $plan->id }})" 
                              class="bg-surface-container p-3.5 rounded-2xl border border-outline-variant/10 flex flex-col md:flex-row md:items-center justify-between gap-3 hover:border-outline-variant/30 transition-all">
                            @csrf
                            @method('PUT')
                            
                            <!-- Nombre -->
                            <div class="flex-1 min-w-[200px]">
                                <label class="text-[9px] text-on-surface-variant uppercase font-etiqueta-bold">Nombre del Plan</label>
                                <input type="text" name="name" value="{{ $plan->name }}" required 
                                       class="w-full bg-surface-container-lowest text-on-surface p-2 rounded-lg border border-outline-variant/20 text-xs font-bold focus:outline-none focus:border-primary/50">
                            </div>

                            <!-- Precio -->
                            <div class="w-28">
                                <label class="text-[9px] text-on-surface-variant uppercase font-etiqueta-bold">Precio ($)</label>
                                <input type="number" step="0.01" min="0.50" name="price" value="{{ $plan->price }}" required 
                                       class="w-full bg-surface-container-lowest text-primary p-2 rounded-lg border border-outline-variant/20 text-xs font-extrabold focus:outline-none focus:border-primary/50">
                            </div>

                            <!-- Vigencia -->
                            <div class="w-24">
                                <label class="text-[9px] text-on-surface-variant uppercase font-etiqueta-bold">Vigencia (Días)</label>
                                <input type="number" min="1" name="validity_days" value="{{ $plan->validity_days }}" required 
                                       class="w-full bg-surface-container-lowest text-on-surface p-2 rounded-lg border border-outline-variant/20 text-xs focus:outline-none focus:border-primary/50 text-center">
                            </div>

                            <!-- Capacidad Mín/Máx -->
                            <div class="flex gap-1.5 w-32">
                                <div class="w-1/2">
                                    <label class="text-[9px] text-on-surface-variant uppercase font-etiqueta-bold">Mín</label>
                                    <input type="number" min="1" name="min_capacity" value="{{ $plan->min_capacity }}" required 
                                           class="w-full bg-surface-container-lowest text-on-surface p-2 rounded-lg border border-outline-variant/20 text-xs text-center">
                                </div>
                                <div class="w-1/2">
                                    <label class="text-[9px] text-on-surface-variant uppercase font-etiqueta-bold">Máx</label>
                                    <input type="number" min="1" name="max_capacity" value="{{ $plan->max_capacity ?: $plan->min_capacity }}" required 
                                           class="w-full bg-surface-container-lowest text-on-surface p-2 rounded-lg border border-outline-variant/20 text-xs text-center">
                                </div>
                            </div>

                            <!-- Status & Acciones -->
                            <div class="flex items-center gap-2 pt-2 md:pt-0 shrink-0">
                                <span class="bg-surface-container-highest px-2 py-1 rounded-md font-mono text-[10px] text-on-surface-variant whitespace-nowrap" title="Membresías activas">
                                    {{ $plan->active_memberships_count }} activas
                                </span>
                                
                                <button type="submit" title="Guardar cambios de este plan" 
                                        class="bg-surface-container-highest hover:bg-primary hover:text-on-primary text-on-surface px-3 py-2 rounded-lg font-etiqueta-bold text-xs uppercase tracking-wider transition-all flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[15px]">save</span>
                                </button>
                                
                                <button type="button" onclick="handlePlanDeleteSubmit({{ $plan->id }}, '{{ addslashes($plan->name) }}')" title="Eliminar este plan" 
                                        class="text-on-surface-variant hover:text-error hover:bg-error/10 p-2 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </div>
                        </form>
                    @empty
                        <div class="text-center p-6 text-xs text-on-surface-variant bg-surface-container rounded-xl">
                            No hay planes registrados en el catálogo.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 4: Autorización de Abono Parcial (Clave de Administrador / Supervisor) -->
<div id="abono-auth-modal" class="fixed inset-0 z-[110] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="cancelAbonoModal()"></div>
    <div class="bg-[#1e1e24] p-8 rounded-[24px] shadow-2xl relative z-10 max-w-md w-full transform scale-95 transition-transform duration-300 border border-outline-variant/20 flex flex-col gap-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 border border-primary/20">
                <span class="material-symbols-outlined text-[28px]">lock_person</span>
            </div>
            <div>
                <h3 class="font-titular-md text-[20px] text-on-surface">Autorización de Abono</h3>
                <p class="font-body-md text-xs text-on-surface-variant mt-0.5">Autorización requerida para pago parcial</p>
            </div>
        </div>

        <p class="font-body-md text-xs text-on-surface-variant leading-relaxed">
            Ingrese la clave del <strong>Administrador o Supervisor</strong> para autorizar el registro con saldo pendiente.
        </p>

        <!-- Input Clave -->
        <div class="flex flex-col gap-2">
            <label class="font-etiqueta-bold text-[11px] uppercase tracking-widest text-on-surface-variant">Clave de Administrador</label>
            <div class="relative">
                <input type="password" id="abono-admin-password" placeholder="••••••••" class="w-full bg-surface-container-lowest text-on-surface font-body-md p-4 rounded-xl border border-outline-variant/20 focus:border-primary focus:outline-none transition-colors" onkeydown="if(event.key==='Enter') verifyAbonoPassword()">
            </div>
        </div>

        <!-- Mensaje de Error -->
        <div id="abono-error-msg" class="hidden p-3.5 bg-error/10 border border-error/20 rounded-xl text-error text-xs font-body-md flex items-start gap-2">
            <span class="material-symbols-outlined text-[18px] shrink-0 mt-0.5">error</span>
            <span id="abono-error-text">Clave incorrecta.</span>
        </div>

        <!-- Mensaje de Éxito -->
        <div id="abono-success-msg" class="hidden p-3.5 bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-xl text-[#4ade80] text-xs font-body-md flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            <span>¡Autorización concedida con éxito!</span>
        </div>

        <!-- Botones del Modal -->
        <div class="flex gap-3 pt-2">
            <button type="button" onclick="cancelAbonoModal()" class="flex-1 bg-surface-container-high hover:bg-surface-container-highest text-on-surface py-3.5 rounded-xl font-titular-md text-[13px] uppercase tracking-wider transition-colors border border-outline-variant/15">
                Cancelar
            </button>
            <button type="button" id="btn-verify-abono" onclick="verifyAbonoPassword()" class="flex-1 bg-primary hover:bg-[#ff2a35] text-on-primary py-3.5 rounded-xl font-titular-md text-[13px] uppercase tracking-wider transition-all flex items-center justify-center gap-2 shadow-[0_0_15px_rgba(227,27,35,0.3)]">
                <span class="material-symbols-outlined text-[16px]">verified</span> Validar
            </button>
        </div>
    </div>
</div>

<!-- TOAST NOTIFICATION CONTAINER -->
<div id="toast-container" class="fixed top-6 right-6 z-[200] flex flex-col gap-3 pointer-events-none"></div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    // --- VARIABLES GLOBALES ---
    let isAbonoAuthorized = false;
    let abonoModalTarget = 'assign'; // 'assign' | 'renew'
    const plansDistributionData = @json($plansDistribution);

    // --- SISTEMA MODERNO DE TOAST NOTIFICATIONS ---
    function showToast(type, message, title = '') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'pointer-events-auto flex items-start gap-3 p-4 rounded-2xl shadow-2xl border backdrop-blur-md transition-all duration-300 transform translate-x-12 opacity-0 min-w-[300px] max-w-md';

        let icon = 'check_circle';
        let bgClass = 'bg-[#1f2937]/90 border-outline-variant/20 text-on-surface';
        let iconCol = 'text-[#4ade80]';

        if (type === 'success') {
            bgClass = 'bg-[#1e293b]/95 border-[#4ade80]/40 text-on-surface shadow-[0_4px_25px_rgba(74,222,128,0.15)]';
            iconCol = 'text-[#4ade80]';
            icon = 'check_circle';
            title = title || '¡Éxito!';
        } else if (type === 'error') {
            bgClass = 'bg-[#1e293b]/95 border-error/40 text-on-surface shadow-[0_4px_25px_rgba(227,27,35,0.15)]';
            iconCol = 'text-error';
            icon = 'error';
            title = title || 'Error';
        } else if (type === 'warning') {
            bgClass = 'bg-[#1e293b]/95 border-[#facc15]/40 text-on-surface shadow-[0_4px_25px_rgba(250,204,21,0.15)]';
            iconCol = 'text-[#facc15]';
            icon = 'warning';
            title = title || 'Advertencia';
        }

        toast.classList.add(...bgClass.split(' '));

        toast.innerHTML = `
            <div class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center shrink-0 ${iconCol}">
                <span class="material-symbols-outlined text-[20px]">${icon}</span>
            </div>
            <div class="flex-1">
                <h4 class="font-titular-md text-[13px] font-bold text-on-surface leading-tight">${title}</h4>
                <p class="font-body-md text-xs text-on-surface-variant mt-0.5 leading-relaxed">${message}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined text-[16px]">close</span>
            </button>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('translate-x-12', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        }, 10);

        setTimeout(() => {
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-12', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // --- CHART.JS CONFIGURATION ---
    document.addEventListener('DOMContentLoaded', function() {
        Chart.defaults.color = '#b4b5b5'; 
        Chart.defaults.font.family = 'Montserrat, Inter, sans-serif';

        const ctxDist = document.getElementById('distributionChart')?.getContext('2d');
        if (ctxDist && plansDistributionData.length > 0) {
            const labels = plansDistributionData.map(p => p.name);
            const counts = plansDistributionData.map(p => p.count);
            const palette = ['#e31b23', '#fb923c', '#facc15', '#4ade80', '#38bdf8', '#a855f7', '#ec4899'];

            new Chart(ctxDist, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: counts,
                        backgroundColor: palette.slice(0, labels.length),
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    cutout: '65%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'right',
                            labels: {
                                color: '#e2e2e2',
                                font: { size: 9, weight: 'bold' },
                                boxWidth: 10
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1f1f1f',
                            titleColor: '#e2e2e2',
                            bodyColor: '#e2e2e2',
                            borderColor: '#5d3f3c',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': ' + context.raw + ' suscripciones';
                                }
                            }
                        }
                    }
                }
            });
        }
    });

    // --- FILTROS DE TABLA (PLAN, BÚSQUEDA Y SEMÁFORO) ---
    function handlePlanFilterChange(planId) {
        const url = new URL(window.location.href);
        if (planId) {
            url.searchParams.set('plan_id', planId);
        } else {
            url.searchParams.delete('plan_id');
        }
        url.searchParams.delete('page');
        window.location.href = url.pathname + url.search + '#cartera-section';
    }

    function handleTableSearchSubmit(e) {
        if (e) e.preventDefault();
        const input = document.getElementById('membership-search-input');
        const val = input ? input.value.trim() : '';
        const url = new URL(window.location.href);
        if (val) {
            url.searchParams.set('q', val);
        } else {
            url.searchParams.delete('q');
        }
        url.searchParams.delete('page');
        window.location.href = url.pathname + url.search + '#cartera-section';
    }

    function clearTableSearch() {
        const input = document.getElementById('membership-search-input');
        if (input) input.value = '';
        handleTableSearchSubmit();
    }

    function handleTableLiveSearch(query) {
        const q = query.toLowerCase().trim();
        const rows = document.querySelectorAll('#memberships-tbody .membership-row');
        
        rows.forEach(row => {
            const searchData = row.getAttribute('data-search') || '';
            if (searchData.includes(q)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // --- MODALES ---
    function openAssignModal() {
        document.getElementById('assignModal').classList.remove('hidden');
        document.getElementById('assign-form-error-banner').classList.add('hidden');
    }

    function closeAssignModal() {
        document.getElementById('assignModal').classList.add('hidden');
    }



    let currentRenewPlanPrice = 0;
    let currentRenewPendingDebt = 0;
    let currentRenewNextDate = '';

    function openRenewModal(membershipId, clientName, planName, price, endDate, validityDays, pendingDebt = 0, openAsOnlyDebt = false) {
        document.getElementById('renew-membership-id').value = membershipId;
        document.getElementById('renew-client-name').textContent = clientName;
        document.getElementById('renew-plan-name').textContent = planName;

        currentRenewPlanPrice = parseFloat(price) || 0;
        currentRenewPendingDebt = parseFloat(pendingDebt) || 0;

        // Calculate next expiration date
        const baseDate = new Date(endDate);
        const today = new Date();
        const startFrom = baseDate < today ? today : baseDate;
        const nextDate = new Date(startFrom);
        nextDate.setDate(nextDate.getDate() + parseInt(validityDays || 30));
        currentRenewNextDate = nextDate.toLocaleDateString('es-EC');
        document.getElementById('renew-new-date').textContent = currentRenewNextDate;

        const debtBox = document.getElementById('renew-debt-warning-box');
        if (currentRenewPendingDebt > 0) {
            document.getElementById('renew-debt-amount-text').textContent = `$${currentRenewPendingDebt.toFixed(2)}`;
            debtBox.classList.remove('hidden');
            debtBox.classList.add('flex');

            if (openAsOnlyDebt) {
                document.querySelector('input[name="renew_debt_choice"][value="only_debt"]').checked = true;
                handleRenewDebtChoice('only_debt');
            } else {
                document.querySelector('input[name="renew_debt_choice"][value="renew"]').checked = true;
                handleRenewDebtChoice('renew');
            }
        } else {
            debtBox.classList.add('hidden');
            debtBox.classList.remove('flex');
            handleRenewDebtChoice('renew');
        }

        document.getElementById('renewModal').classList.remove('hidden');
    }

    function handleRenewDebtChoice(choice) {
        const totalDisplay = document.getElementById('renew-total-price');
        const dateRow = document.getElementById('renew-date-row');
        const btnText = document.getElementById('renew-btn-text');
        const modalTitle = document.getElementById('renew-modal-title');
        const actionMode = document.getElementById('renew-action-mode');
        const paymentTypeGroup = document.getElementById('renew-payment-type-group');

        let total = currentRenewPlanPrice;

        if (choice === 'only_debt') {
            actionMode.value = 'only_debt';
            total = currentRenewPendingDebt;
            totalDisplay.textContent = `$${total.toFixed(2)}`;
            dateRow.classList.add('hidden');
            btnText.textContent = 'Registrar Cobro de Deuda';
            modalTitle.textContent = 'Liquidar Saldo Pendiente';
            document.querySelector('#renew-form input[name="payment_type"][value="completo"]').checked = true;
            toggleRenewAbono(false);
            paymentTypeGroup.classList.add('hidden');
        } else {
            actionMode.value = 'renew';
            total = currentRenewPlanPrice + currentRenewPendingDebt;
            totalDisplay.textContent = `$${total.toFixed(2)}` + (currentRenewPendingDebt > 0 ? ` ($${currentRenewPlanPrice.toFixed(2)} + $${currentRenewPendingDebt.toFixed(2)} deuda)` : '');
            dateRow.classList.remove('hidden');
            btnText.textContent = 'Confirmar Renovación';
            modalTitle.textContent = currentRenewPendingDebt > 0 ? 'Pagar Deuda & Renovar' : 'Renovar Membresía';
            paymentTypeGroup.classList.remove('hidden');

            // Setup min/max abono
            const minAbono = total * 0.25;
            const maxAbono = total * 0.75;
            document.getElementById('renew-abono-min').textContent = minAbono.toFixed(2);
            document.getElementById('renew-abono-max').textContent = maxAbono.toFixed(2);
            const renewInput = document.getElementById('renew-abono-input');
            renewInput.setAttribute('min', minAbono.toFixed(2));
            renewInput.setAttribute('max', maxAbono.toFixed(2));
        }
    }

    function closeRenewModal() {
        document.getElementById('renewModal').classList.add('hidden');
    }

    function openCatalogModal() {
        document.getElementById('catalogModal').classList.remove('hidden');
        document.getElementById('create-plan-error-box')?.classList.add('hidden');
    }

    function closeCatalogModal() {
        document.getElementById('catalogModal').classList.add('hidden');
    }

    // --- SELECCIÓN DE ATLETA (UNO POR UNO: BUSCADOR + COMBOBOX) ---
    let searchDebounceTimer;
    function handleClientSearchInput(val) {
        clearTimeout(searchDebounceTimer);
        const resultsBox = document.getElementById('assign-client-results');
        
        if (val.trim().length < 2) {
            resultsBox.classList.add('hidden');
            resultsBox.innerHTML = '';
            return;
        }

        searchDebounceTimer = setTimeout(async () => {
            try {
                const res = await fetch(`/api/clients-search?q=${encodeURIComponent(val)}`);
                const clients = await res.json();
                
                if (clients.length === 0) {
                    resultsBox.innerHTML = '<div class="p-3 text-xs text-on-surface-variant text-center">No se encontraron atletas</div>';
                } else {
                    resultsBox.innerHTML = clients.map(c => `
                        <div onclick="selectSingleAthlete(${c.id}, '${c.name.replace(/'/g, "\\'")} ${c.last_name ? c.last_name.replace(/'/g, "\\'") : ''}', '${c.id_card}', '${c.phone || ''}')" 
                             class="p-2.5 hover:bg-surface-container-highest cursor-pointer flex justify-between items-center text-xs border-b border-outline-variant/10 last:border-0 transition-colors">
                            <span class="font-bold text-on-surface">${c.name} ${c.last_name || ''}</span>
                            <span class="text-on-surface-variant font-mono">C.I. ${c.id_card}</span>
                        </div>
                    `).join('');
                }
                resultsBox.classList.remove('hidden');
            } catch (e) {
                console.error(e);
            }
        }, 200);
    }

    function handleClientComboboxChange(select) {
        const opt = select.options[select.selectedIndex];
        if (!opt.value) return;

        selectSingleAthlete(
            opt.value,
            opt.dataset.name,
            opt.dataset.card,
            opt.dataset.phone
        );
    }

    function selectSingleAthlete(id, name, idCard, phone) {
        document.getElementById('selected-single-client-id').value = id;
        document.getElementById('selected-athlete-name').textContent = name;
        document.getElementById('selected-athlete-info').textContent = `C.I. ${idCard} ${phone ? '· ' + phone : ''}`;

        document.getElementById('selected-athlete-box').classList.remove('hidden');
        document.getElementById('athlete-select-controls').classList.add('hidden');
        document.getElementById('assign-form-error-banner').classList.add('hidden');

        // Reset search & combobox
        document.getElementById('assign-client-search').value = '';
        document.getElementById('assign-client-results').classList.add('hidden');
        document.getElementById('assign-client-combobox').value = id;
    }

    function clearSelectedAthlete() {
        document.getElementById('selected-single-client-id').value = '';
        document.getElementById('selected-athlete-box').classList.add('hidden');
        document.getElementById('athlete-select-controls').classList.remove('hidden');
        document.getElementById('assign-client-combobox').value = '';
    }

    // --- RECALCULAR TOTALES Y FECHAS EN ASIGNACIÓN (EDITABLES) ---
    function handleAssignPlanChange(select) {
        recalculateAssignEndDate();
        recalculateAssignTotals();
        document.getElementById('assign-form-error-banner').classList.add('hidden');
    }

    function handleAssignStartDateChange() {
        recalculateAssignEndDate();
    }

    function handleAssignEndDateChange() {
        const startVal = document.getElementById('assign-start-date').value;
        const endVal = document.getElementById('assign-end-date').value;
        if (startVal && endVal) {
            const start = new Date(startVal);
            const end = new Date(endVal);
            if (end < start) {
                showToast('warning', 'La fecha de corte no puede ser anterior a la fecha de inicio.', 'Validación de Fechas');
            }
        }
    }

    function recalculateAssignEndDate() {
        const select = document.getElementById('assign-plan-select');
        const selectedOpt = select.options[select.selectedIndex];
        const days = selectedOpt ? parseInt(selectedOpt.dataset.days || 30) : 30;

        const startDateVal = document.getElementById('assign-start-date').value;
        if (startDateVal) {
            const start = new Date(startDateVal);
            const end = new Date(start);
            end.setDate(end.getDate() + days);
            document.getElementById('assign-end-date').value = end.toISOString().split('T')[0];
        }
    }

    function recalculateAssignTotals() {
        const select = document.getElementById('assign-plan-select');
        const selectedOpt = select.options[select.selectedIndex];
        const unitPrice = selectedOpt ? parseFloat(selectedOpt.dataset.price || 0) : 0;

        document.getElementById('assign-total-display').textContent = `$${unitPrice.toFixed(2)}`;

        const minAbono = unitPrice * 0.25;
        const maxAbono = unitPrice * 0.75;
        document.getElementById('assign-abono-min').textContent = minAbono.toFixed(2);
        document.getElementById('assign-abono-max').textContent = maxAbono.toFixed(2);

        const abonoInput = document.getElementById('assign-abono-input');
        abonoInput.setAttribute('min', minAbono.toFixed(2));
        abonoInput.setAttribute('max', maxAbono.toFixed(2));
    }

    // --- ABONO AUTORIZACIÓN MODAL LOGIC ---
    function toggleAssignAbono(show) {
        const box = document.getElementById('assign-abono-box');
        if (show) {
            if (!isAbonoAuthorized) {
                abonoModalTarget = 'assign';
                openAbonoAuthModal();
            } else {
                box.classList.remove('hidden');
                box.classList.add('flex');
            }
        } else {
            box.classList.add('hidden');
            box.classList.remove('flex');
        }
    }

    function toggleRenewAbono(show) {
        const box = document.getElementById('renew-abono-box');
        if (show) {
            if (!isAbonoAuthorized) {
                abonoModalTarget = 'renew';
                openAbonoAuthModal();
            } else {
                box.classList.remove('hidden');
                box.classList.add('flex');
            }
        } else {
            box.classList.add('hidden');
            box.classList.remove('flex');
        }
    }

    function openAbonoAuthModal() {
        const modal = document.getElementById('abono-auth-modal');
        const passInput = document.getElementById('abono-admin-password');
        document.getElementById('abono-error-msg').classList.add('hidden');
        document.getElementById('abono-success-msg').classList.add('hidden');
        passInput.value = '';

        modal.classList.remove('pointer-events-none', 'opacity-0');
        modal.classList.add('pointer-events-auto', 'opacity-100');
        setTimeout(() => passInput.focus(), 100);
    }

    function cancelAbonoModal() {
        const modal = document.getElementById('abono-auth-modal');
        modal.classList.remove('pointer-events-auto', 'opacity-100');
        modal.classList.add('pointer-events-none', 'opacity-0');

        if (!isAbonoAuthorized) {
            if (abonoModalTarget === 'assign') {
                document.querySelector('input[name="payment_type"][value="completo"]').checked = true;
                toggleAssignAbono(false);
            } else {
                document.querySelector('#renew-form input[name="payment_type"][value="completo"]').checked = true;
                toggleRenewAbono(false);
            }
        }
    }

    async function verifyAbonoPassword() {
        const passInput = document.getElementById('abono-admin-password');
        const password = passInput.value.trim();
        const errorMsg = document.getElementById('abono-error-msg');
        const errorText = document.getElementById('abono-error-text');
        const successMsg = document.getElementById('abono-success-msg');
        const btn = document.getElementById('btn-verify-abono');

        if (!password) {
            errorText.textContent = 'Ingrese la clave de autorización.';
            errorMsg.classList.remove('hidden');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[16px]">sync</span> Verificando...';

        try {
            const res = await fetch("{{ route('clientes.authorizeAbono') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ password: password })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                isAbonoAuthorized = true;
                errorMsg.classList.add('hidden');
                successMsg.classList.remove('hidden');

                // Unlock target abono box and show badge
                if (abonoModalTarget === 'assign') {
                    document.getElementById('assign-auth-badge').classList.remove('hidden');
                    const box = document.getElementById('assign-abono-box');
                    box.classList.remove('hidden');
                    box.classList.add('flex');
                } else {
                    document.getElementById('renew-auth-badge').classList.remove('hidden');
                    const box = document.getElementById('renew-abono-box');
                    box.classList.remove('hidden');
                    box.classList.add('flex');
                }

                showToast('success', '¡Abono autorizado por el administrador!', 'Autorización Concedida');

                setTimeout(() => {
                    cancelAbonoModal();
                }, 600);
            } else {
                errorText.textContent = data.message || 'Clave de autorización incorrecta.';
                errorMsg.classList.remove('hidden');
            }
        } catch (err) {
            console.error(err);
            errorText.textContent = 'Error al conectar con el servidor.';
            errorMsg.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[16px]">verified</span> Validar';
        }
    }

    function toggleAssignTransfer(show) {
        const box = document.getElementById('assign-voucher-box');
        if (show) {
            box.classList.remove('hidden');
            box.classList.add('flex');
        } else {
            box.classList.add('hidden');
            box.classList.remove('flex');
        }
    }

    function toggleRenewTransfer(show) {
        const box = document.getElementById('renew-voucher-box');
        if (show) {
            box.classList.remove('hidden');
            box.classList.add('flex');
        } else {
            box.classList.add('hidden');
            box.classList.remove('flex');
        }
    }

    // --- FORM SUBMIT HANDLERS (CON VALIDACIÓN ESTRICTA DE TODOS LOS CAMPOS) ---
    async function handleAssignFormSubmit(e) {
        e.preventDefault();
        const errorBanner = document.getElementById('assign-form-error-banner');
        const errorText = document.getElementById('assign-form-error-text');
        errorBanner.classList.add('hidden');

        // 1. Validar Atleta
        const clientId = document.getElementById('selected-single-client-id').value;
        if (!clientId) {
            errorText.textContent = 'Debe seleccionar un atleta registrado para asignar la membresía.';
            errorBanner.classList.remove('hidden');
            showToast('warning', 'Por favor selecciona un atleta para asignar la membresía.', 'Atleta Requerido');
            return;
        }

        // 2. Validar Plan
        const planSelect = document.getElementById('assign-plan-select');
        if (!planSelect.value) {
            errorText.textContent = 'Debe seleccionar un plan del catálogo.';
            errorBanner.classList.remove('hidden');
            showToast('warning', 'Selecciona un plan de suscripción.', 'Plan Requerido');
            return;
        }

        // 3. Validar Fechas
        const startDate = document.getElementById('assign-start-date').value;
        const endDate = document.getElementById('assign-end-date').value;
        if (!startDate || !endDate) {
            errorText.textContent = 'Las fechas de inicio y corte son obligatorias.';
            errorBanner.classList.remove('hidden');
            showToast('warning', 'Completa las fechas de inicio y corte.', 'Fechas Obligatorias');
            return;
        }

        if (new Date(endDate) < new Date(startDate)) {
            errorText.textContent = 'La fecha de corte no puede ser anterior a la fecha de inicio.';
            errorBanner.classList.remove('hidden');
            showToast('error', 'La fecha de corte debe ser posterior o igual a la de inicio.', 'Error en Fechas');
            return;
        }

        // 4. Validar Modalidad de Pago
        const isAbono = document.getElementById('assign-payment-type-abono').checked;
        if (isAbono) {
            if (!isAbonoAuthorized) {
                abonoModalTarget = 'assign';
                openAbonoAuthModal();
                showToast('warning', 'Se requiere autorización del administrador para pago parcial (abono).', 'Autorización Requerida');
                return;
            }
            const abonoVal = parseFloat(document.getElementById('assign-abono-input').value || 0);
            const minAbono = parseFloat(document.getElementById('assign-abono-min').textContent || 0);
            const maxAbono = parseFloat(document.getElementById('assign-abono-max').textContent || 0);
            if (!abonoVal || abonoVal < minAbono || abonoVal > maxAbono) {
                errorText.textContent = `El monto de abono debe estar entre $${minAbono.toFixed(2)} (25%) y $${maxAbono.toFixed(2)} (75%).`;
                errorBanner.classList.remove('hidden');
                showToast('warning', `El abono debe estar entre $${minAbono.toFixed(2)} y $${maxAbono.toFixed(2)}.`, 'Monto Inválido');
                return;
            }
        }

        // 5. Validar Transferencia y Comprobante
        const isTransfer = document.querySelector('input[name="payment_method"][value="transferencia"]').checked;
        if (isTransfer) {
            const voucher = document.getElementById('assign-voucher-input').value.trim();
            if (!voucher) {
                errorText.textContent = 'El número de comprobante es obligatorio para pagos con transferencia.';
                errorBanner.classList.remove('hidden');
                showToast('warning', 'Ingresa el número de comprobante o voucher.', 'Comprobante Requerido');
                return;
            }
        }

        const form = document.getElementById('assign-membership-form');
        const formData = new FormData(form);
        const btn = document.getElementById('btn-save-assign');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[16px]">sync</span> Guardando...';

        try {
            const res = await fetch("{{ route('memberships.store') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (res.ok && data.success) {
                showToast('success', data.message, 'Asignación Exitosa');
                setTimeout(() => location.reload(), 700);
            } else {
                let errorMsg = data.message || 'Error al guardar la asignación';
                if (data.errors) {
                    errorMsg += ': ' + Object.values(data.errors).flat().join(', ');
                }
                errorText.textContent = errorMsg;
                errorBanner.classList.remove('hidden');
                showToast('error', errorMsg, 'Validación Requerida');
            }
        } catch (error) {
            console.error(error);
            showToast('error', 'Ocurrió un error en la conexión con el servidor', 'Error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[16px]">save</span> Guardar Asignación';
        }
    }

    async function handleRenewSubmit(e) {
        e.preventDefault();
        const membershipId = document.getElementById('renew-membership-id').value;
        const form = document.getElementById('renew-form');
        const formData = new FormData(form);
        const isTransfer = document.querySelector('#renew-form input[name="payment_method"][value="transferencia"]').checked;
        const voucher = document.getElementById('renew-voucher-input').value.trim();

        if (isTransfer && !voucher) {
            showToast('warning', 'El número de comprobante es obligatorio para pagos por transferencia.', 'Comprobante Requerido');
            return;
        }

        const btn = document.getElementById('btn-submit-renew');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[16px]">sync</span> Procesando...';

        try {
            const res = await fetch(`/membresias/${membershipId}/renew`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (res.ok && data.success) {
                showToast('success', data.message, 'Operación Exitosa');
                setTimeout(() => location.reload(), 700);
            } else {
                showToast('error', data.message || 'Error al procesar la solicitud.', 'Validación');
            }
        } catch (error) {
            console.error(error);
            showToast('error', 'Error en la conexión con el servidor.', 'Error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[16px]">refresh</span> <span id="renew-btn-text">Confirmar Renovación</span>';
        }
    }

    async function handleCreatePlanSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const btn = document.getElementById('btn-create-plan');
        const errBox = document.getElementById('create-plan-error-box');
        const errText = document.getElementById('create-plan-error-text');
        if (errBox) errBox.classList.add('hidden');

        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[16px]">sync</span> Creando...';

        try {
            const res = await fetch("{{ route('plans.store') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (res.ok && data.success) {
                showToast('success', data.message, 'Plan Creado');
                setTimeout(() => location.reload(), 700);
            } else {
                let errorMsg = data.message || 'Error al crear el plan';
                if (data.errors) {
                    errorMsg += ': ' + Object.values(data.errors).flat().join(', ');
                }
                if (errBox && errText) {
                    errText.textContent = errorMsg;
                    errBox.classList.remove('hidden');
                }
                showToast('error', errorMsg, 'Error de Validación');
            }
        } catch (error) {
            console.error(error);
            showToast('error', 'Error en la conexión al crear el plan.', 'Error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[16px]">add</span> Crear y Publicar Plan';
        }
    }

    async function handlePlanUpdateSubmit(e, planId) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        try {
            const res = await fetch(`/planes/${planId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (res.ok && data.success) {
                showToast('success', data.message, 'Plan Actualizado');
                setTimeout(() => location.reload(), 700);
            } else {
                showToast('error', data.message || 'Error al actualizar el plan', 'Error');
            }
        } catch (error) {
            console.error(error);
            showToast('error', 'Error al guardar el plan', 'Error');
        }
    }

    async function handlePlanDeleteSubmit(planId, planName) {
        if (!confirm(`¿Estás seguro de que deseas eliminar el plan '${planName}' del catálogo?\n\nNota: Solo se pueden eliminar planes sin suscripciones registradas.`)) return;

        try {
            const res = await fetch(`/planes/${planId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (res.ok && data.success) {
                showToast('success', data.message, 'Plan Eliminado');
                setTimeout(() => location.reload(), 700);
            } else {
                showToast('error', data.message || 'No se pudo eliminar el plan.', 'No Permitido');
            }
        } catch (error) {
            console.error(error);
            showToast('error', 'Error al conectar con el servidor.', 'Error');
        }
    }

    async function confirmDeleteMembership(membershipId) {
        if (!confirm('¿Estás seguro de que deseas eliminar esta suscripción? Esta acción no se puede deshacer.')) return;

        try {
            const res = await fetch(`/membresias/${membershipId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (res.ok && data.success) {
                showToast('success', data.message, 'Membresía Eliminada');
                setTimeout(() => location.reload(), 700);
            } else {
                showToast('error', data.message || 'Error al eliminar', 'Error');
            }
        } catch (error) {
            console.error(error);
            showToast('error', 'Error al conectar con el servidor', 'Error');
        }
    }
</script>
@endpush

