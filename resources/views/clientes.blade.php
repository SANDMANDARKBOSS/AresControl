@extends('layouts.admin')

@section('title', 'Clientes - Ares Gym')

@section('content')
    <div class="flex flex-col w-full relative font-cuerpo-md animate-on-load">
        <!-- Decorative Ambient Element -->
        <div
            class="absolute right-0 top-0 w-[600px] h-[600px] bg-gradient-to-bl from-primary-container/10 via-background to-transparent blur-3xl pointer-events-none -z-10 -mt-20">
        </div>
        <div class="absolute left-10 top-40 w-32 h-32 bg-primary/5 rounded-full blur-2xl pointer-events-none -z-10"></div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/30 text-green-400 font-etiqueta-bold text-[13px] flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-400/60 hover:text-green-400">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-error/10 border border-error/30 text-error font-etiqueta-bold text-[13px] flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[20px]">error</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-error/60 hover:text-error">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        @endif

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-margin-sm">
            <div class="flex flex-col">
                <h1 class="font-titular-xl text-[48px] text-on-surface tracking-tighter uppercase leading-none"
                    style="font-family: 'Montserrat', sans-serif; font-weight: 800;">Listado<br />de Clientes</h1>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('grupos.index') }}"
                    class="bg-surface-container-high text-on-surface border border-outline-variant/20 px-8 py-4 rounded-lg font-titular-md text-[16px] uppercase tracking-wide shadow-sm hover:shadow-md hover:bg-surface-container-highest transition-all flex items-center gap-3 group">
                    <span
                        class="material-symbols-outlined group-hover:scale-110 transition-transform duration-300">groups</span>
                    Planes Grupales
                </a>
                <a href="{{ route('clientes.create') }}"
                    class="bg-primary-container text-on-primary-container px-8 py-4 rounded-lg font-titular-md text-[16px] uppercase tracking-wide shadow-[0_4px_20px_rgba(227,27,35,0.3)] hover:shadow-[0_4px_30px_rgba(227,27,35,0.5)] hover:scale-[1.02] transition-all flex items-center gap-3 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent pointer-events-none"></div>
                    <span
                        class="material-symbols-outlined group-hover:rotate-90 transition-transform duration-300">add</span>
                    Nuevo Cliente
                </a>
            </div>
        </div>

        <!-- KPIs Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-margin-md animate-on-load" style="animation-delay: 50ms">
            <div
                class="bg-surface-container-low rounded-xl p-6 border border-outline-variant/10 flex items-center gap-5 shadow-lg relative overflow-hidden">
                <div
                    class="absolute right-0 top-0 w-32 h-32 bg-green-500/5 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none">
                </div>
                <div
                    class="w-14 h-14 rounded-full bg-green-500/10 text-green-500 flex items-center justify-center border border-green-500/20">
                    <span class="material-symbols-outlined text-[28px]">group</span>
                </div>
                <div class="flex flex-col">
                    <span
                        class="text-on-surface-variant font-etiqueta-bold text-[11px] uppercase tracking-widest mb-1">Total
                        Activos / Aforo</span>
                    <span class="text-on-surface font-titular-xl text-[28px] leading-none">{{ $totalActivos }} <span
                            class="text-on-surface-variant text-[14px] font-titular-md tracking-normal">Atletas</span></span>
                </div>
            </div>

            <div
                class="bg-surface-container-low rounded-xl p-6 border border-outline-variant/10 flex items-center gap-5 shadow-lg relative overflow-hidden">
                <div
                    class="absolute right-0 top-0 w-32 h-32 bg-yellow-500/5 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none">
                </div>
                <div
                    class="w-14 h-14 rounded-full bg-yellow-500/10 text-yellow-500 flex items-center justify-center border border-yellow-500/20">
                    <span class="material-symbols-outlined text-[28px]">warning</span>
                </div>
                <div class="flex flex-col">
                    <span
                        class="text-on-surface-variant font-etiqueta-bold text-[11px] uppercase tracking-widest mb-1">Pagos
                        en Riesgo / Vencidos</span>
                    <span class="text-on-surface font-titular-xl text-[28px] leading-none">{{ $pagosEnRiesgo + $vencidos }}
                        <span
                            class="text-on-surface-variant text-[14px] font-titular-md tracking-normal">Pendientes</span></span>
                </div>
            </div>

            <div
                class="bg-surface-container-low rounded-xl p-6 border border-outline-variant/10 flex items-center gap-5 shadow-lg relative overflow-hidden">
                <div
                    class="absolute right-0 top-0 w-32 h-32 bg-primary/5 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none">
                </div>
                <div
                    class="w-14 h-14 rounded-full bg-primary/10 text-primary flex items-center justify-center border border-primary/20">
                    <span class="material-symbols-outlined text-[28px]">trending_up</span>
                </div>
                <div class="flex flex-col">
                    <span
                        class="text-on-surface-variant font-etiqueta-bold text-[11px] uppercase tracking-widest mb-1">Nuevos
                        Ingresos (Mes)</span>
                    <span class="text-on-surface font-titular-xl text-[28px] leading-none">+{{ $nuevosMes }} <span
                            class="text-on-surface-variant text-[14px] font-titular-md tracking-normal">Atletas</span></span>
                </div>
            </div>
        </div>

        <!-- Controls Section -->
        <div class="mb-margin-md relative z-10 animate-on-load" style="animation-delay: 100ms">
            <div
                class="flex flex-col gap-4 bg-surface-container/80 backdrop-blur-md rounded-xl p-4 shadow-xl border border-outline-variant/10">
                <div class="flex flex-col xl:flex-row gap-4 w-full">
                    <!-- Search -->
                    <div
                        class="flex-1 flex items-center gap-3 px-4 py-3 bg-surface-container-lowest rounded-lg shadow-inner group border border-outline-variant/10 focus-within:border-primary/50 transition-colors">
                        <span
                            class="material-symbols-outlined text-on-surface-variant group-focus-within:text-primary transition-colors">search</span>
                        <input id="searchInput"
                            class="bg-transparent border-none outline-none text-on-surface font-etiqueta-bold w-full placeholder:text-on-surface-variant/40 focus:ring-0"
                            placeholder="Buscar por Nombre o Cédula (Ej. 1724567894)..." type="text" onkeyup="filterClients()" />
                        <div class="hidden md:flex gap-1">
                            <kbd
                                class="px-2 py-1 bg-surface-container-high rounded text-etiqueta-sm font-etiqueta-sm text-on-surface-variant border border-outline-variant/20">CTRL</kbd>
                            <kbd
                                class="px-2 py-1 bg-surface-container-high rounded text-etiqueta-sm font-etiqueta-sm text-on-surface-variant border border-outline-variant/20">K</kbd>
                        </div>
                    </div>

                    <!-- Quick Filter Chips -->
                    <div class="flex gap-2 items-center overflow-x-auto pb-2 xl:pb-0 hide-scrollbar" id="filter-chips">
                        <button onclick="setFilter('all', this)"
                            class="filter-chip active bg-primary text-on-primary border border-primary/50 px-5 py-3 rounded-lg font-etiqueta-bold text-[12px] uppercase tracking-widest whitespace-nowrap transition-all shadow-md">
                            Todos ({{ count($clients) }})
                        </button>
                        <button onclick="setFilter('pending', this)"
                            class="filter-chip bg-surface-container-lowest text-on-surface border border-outline-variant/20 hover:bg-surface-container-high px-5 py-3 rounded-lg font-etiqueta-bold text-[12px] uppercase tracking-widest whitespace-nowrap transition-all">
                            Pagos Pendientes ({{ $pagosEnRiesgo + $vencidos }})
                        </button>
                        <button onclick="setFilter('new', this)"
                            class="filter-chip bg-surface-container-lowest text-on-surface border border-outline-variant/20 hover:bg-surface-container-high px-5 py-3 rounded-lg font-etiqueta-bold text-[12px] uppercase tracking-widest whitespace-nowrap transition-all">
                            Nuevos ({{ $nuevosMes }})
                        </button>
                        <button onclick="setFilter('inactive', this)"
                            class="filter-chip bg-surface-container-lowest text-on-surface border border-outline-variant/20 hover:bg-surface-container-high px-5 py-3 rounded-lg font-etiqueta-bold text-[12px] uppercase tracking-widest whitespace-nowrap transition-all">
                            Dados de Baja
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table / List -->
        <div class="flex flex-col w-full relative z-10 animate-on-load" style="animation-delay: 200ms">
            <!-- List Header -->
            <div
                class="hidden lg:grid grid-cols-[3fr_1.8fr_1.8fr_2fr_auto] gap-6 px-8 py-3 bg-surface-container-high rounded-t-xl border-b border-outline-variant/20 text-on-surface-variant font-etiqueta-bold text-etiqueta-sm uppercase tracking-widest">
                <div>Atleta / Contacto</div>
                <div>Plan Activo</div>
                <div>Estado de Pago</div>
                <div>Periodo de Adaptación</div>
                <div class="text-right pr-2">Acciones</div>
            </div>

            <!-- Roster List -->
            <div class="flex flex-col gap-3 lg:gap-0 lg:rounded-b-xl overflow-hidden border border-outline-variant/5 lg:border-t-0"
                id="client-list">
                @forelse ($clients as $client)
                    @php
                        $latestMembership = $client->memberships->first();
                        $daysDiff = 0;
                        $isActive = $client->user ? (bool)$client->user->status : true;

                        if ($latestMembership) {
                            $endDate = \Carbon\Carbon::parse($latestMembership->end_date);
                            $daysDiff = \Carbon\Carbon::now()->diffInDays($endDate, false); // negative if past
                        }

                        // Default values
                        $badgeText = 'Sin Plan';
                        $badgeBg = 'bg-surface-container-high';
                        $badgeTextCol = 'text-on-surface-variant';
                        $badgeIcon = 'radio_button_unchecked';
                        $filterCategory = 'all';

                        if (!$isActive) {
                            $badgeText = 'Inactivo / Baja';
                            $badgeBg = 'bg-surface-variant/50';
                            $badgeTextCol = 'text-on-surface-variant/70';
                            $badgeIcon = 'person_off';
                            $filterCategory .= ' inactive';
                        } elseif (!$latestMembership) {
                            $badgeText = 'Sin Plan';
                        } elseif ($daysDiff < 0) {
                            $badgeText = 'Vencido';
                            $badgeBg = 'bg-error/10';
                            $badgeTextCol = 'text-error';
                            $badgeIcon = 'cancel';
                            $filterCategory .= ' pending';
                        } elseif ($daysDiff <= 3) {
                            $badgeText = 'Por Vencer';
                            $badgeBg = 'bg-yellow-500/10';
                            $badgeTextCol = 'text-yellow-500';
                            $badgeIcon = 'warning';
                            $filterCategory .= ' pending';
                        } else {
                            $badgeText = 'Al Día';
                            $badgeBg = 'bg-green-500/10';
                            $badgeTextCol = 'text-green-500';
                            $badgeIcon = 'check_circle';
                            $filterCategory .= ' active';
                        }

                        // Is New?
                        if (\Carbon\Carbon::parse($client->created_at)->isCurrentMonth()) {
                            $filterCategory .= ' new';
                        }

                        // Formatear WhatsApp Inteligente con mensaje según estado
                        $phoneLink = '#';
                        $cleanPhone = $client->phone ? preg_replace('/[^0-9]/', '', $client->phone) : '';
                        if ($cleanPhone) {
                            if (str_starts_with($cleanPhone, '593')) {
                                $waNumber = $cleanPhone;
                            } else {
                                $waNumber = '593' . ltrim($cleanPhone, '0');
                            }

                            if (!$isActive) {
                                $waMsg = "¡Hola {$client->name}! 👋 Te saludamos de *Ares Gym*. ¿Cómo has estado? Nos encantaría verte de vuelta en el gimnasio. ¡Pasa por recepción cuando gustes reactivar tu plan!";
                            } elseif (!$latestMembership) {
                                $waMsg = "¡Hola {$client->name}! 💪 Te saludamos desde *Ares Gym*. ¿Listo para activar tu plan de entrenamiento? Pasa por recepción para elegir tu membresía y comenzar con todo. 🔥";
                            } elseif ($daysDiff < 0) {
                                $diasVencido = abs(intval($daysDiff));
                                $diasTexto = $diasVencido == 1 ? "1 día" : "{$diasVencido} días";
                                $waMsg = "¡Hola {$client->name}! 👋 Te saludamos desde *Ares Gym*. Te recordamos que tu membresía venció hace {$diasTexto}. Pasa por recepción a renovarla y no perder el ritmo de tus entrenamientos. 🏋️‍♂️🔥";
                            } elseif ($daysDiff <= 3) {
                                $diasTexto = $daysDiff == 0 ? "hoy" : ($daysDiff == 1 ? "mañana" : "en {$daysDiff} días");
                                $waMsg = "¡Hola {$client->name}! 👋 Te recordamos desde *Ares Gym* que tu plan activo vence {$diasTexto}. Pasa por recepción con anticipación para renovar tu membresía y mantener tus entrenamientos al día. 💥";
                            } else {
                                $waMsg = "¡Hola {$client->name}! 💪 Te saludamos desde *Ares Gym*. Te recordamos que tu membresía se encuentra activa. ¡Esperamos verte dándolo todo en tus entrenamientos de esta semana! 🔥";
                            }

                            $phoneLink = 'https://wa.me/' . $waNumber . '?text=' . urlencode($waMsg);
                        }
                    @endphp

                    <div data-category="{{ $filterCategory }}"
                        data-search="{{ strtolower($client->name . ' ' . $client->last_name . ' ' . $client->id_card . ' ' . $client->phone . ' ar-' . str_pad($client->id, 4, '0', STR_PAD_LEFT)) }}"
                        onclick="window.location.href='{{ route('clientes.show', $client->id) }}'"
                        class="client-card group grid grid-cols-1 lg:grid-cols-[3fr_1.8fr_1.8fr_2fr_auto] gap-4 lg:gap-6 px-6 lg:px-8 py-5 bg-surface-container-lowest border-b border-outline-variant/5 items-center transition-all duration-300 hover:bg-surface-container hover:border-primary/20 hover:shadow-lg cursor-pointer {{ !$isActive ? 'opacity-60 hover:opacity-100' : '' }}">

                        <!-- Atleta -->
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-full overflow-hidden bg-surface-container-high relative flex items-center justify-center border border-outline-variant/20 shadow-inner group-hover:border-primary/40 transition-colors">
                                <img src="{{ $client->gender === 'F' ? asset('images/avatarF.png') : asset('images/avatarM.jpeg') }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="client-name font-titular-md text-[16px] text-on-surface tracking-tight flex items-center gap-2 group-hover:text-primary transition-colors">
                                    {{ $client->name }} {{ $client->last_name }}
                                    @if(!$isActive)
                                        <span class="text-[10px] uppercase font-etiqueta-bold px-1.5 py-0.5 rounded bg-surface-variant text-on-surface-variant">Baja</span>
                                    @endif
                                </span>
                                <span
                                    class="client-id font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-wider mt-0.5">C.I. {{ $client->id_card }} &middot; #AR-{{ str_pad($client->id, 4, '0', STR_PAD_LEFT) }} &middot; {{ $client->phone ?: 'Sin Telf' }}</span>
                            </div>
                        </div>

                        <!-- Plan -->
                        <div class="flex items-center">
                            <span class="font-cuerpo-md text-[14px] text-on-surface font-bold">
                                {{ $latestMembership ? $latestMembership->plan->name : 'N/A' }}
                            </span>
                        </div>

                        <!-- Estado de Pago -->
                        <div class="flex items-center">
                            <span
                                class="px-3 py-1.5 rounded-full {{ $badgeBg }} border border-{{ str_replace('text-', '', $badgeTextCol) }}/20 {{ $badgeTextCol }} font-etiqueta-bold text-[11px] uppercase tracking-wider flex items-center gap-1.5 w-max">
                                <span class="material-symbols-outlined text-[14px]">{{ $badgeIcon }}</span>
                                {{ $badgeText }}
                            </span>
                            @if($isActive && $latestMembership && $daysDiff <= 3)
                                <span
                                    class="ml-2 font-etiqueta-sm text-[11px] text-on-surface-variant">({{ $daysDiff < 0 ? 'Hace ' . abs(intval($daysDiff)) . 'd' : 'En ' . intval($daysDiff) . 'd' }})</span>
                            @endif
                        </div>

                        <!-- Periodo de Adaptación -->
                        <div class="flex items-center">
                            @php
                                $cEntryDate = \Carbon\Carbon::parse($client->entry_date ?? $client->created_at)->startOfDay();
                                $cDiffDays = $cEntryDate->diffInDays(\Carbon\Carbon::now()->startOfDay(), false);
                                
                                if ($cDiffDays <= 0) {
                                    $cDay = 1;
                                    $cInAdaptation = true;
                                } elseif ($cDiffDays == 1) {
                                    $cDay = 2;
                                    $cInAdaptation = true;
                                } else {
                                    $cDay = 3;
                                    $cInAdaptation = false;
                                }
                            @endphp
                            @if($cInAdaptation)
                                <span class="px-3 py-1.5 bg-primary/10 text-primary rounded-xl border border-primary/20 font-etiqueta-bold text-[11px] tracking-wider uppercase flex items-center gap-1.5 animate-pulse" title="En periodo de adaptación inicial (3 días)">
                                    <span class="material-symbols-outlined text-[13px]">schedule</span>
                                    <span>Adaptación (Día {{ $cDay }}/3)</span>
                                </span>
                            @else
                                <span class="px-3 py-1.5 bg-green-500/10 text-green-500 rounded-xl border border-green-500/20 font-etiqueta-bold text-[11px] tracking-wider uppercase flex items-center gap-1.5" title="Adaptación cumplida - Apto para toma de medidas y plan nutricional">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                    <span>Apto Medición (3/3)</span>
                                </span>
                            @endif
                        </div>

                        <!-- Acciones Rápidas -->
                        <div class="flex gap-2 items-center justify-end" onclick="event.stopPropagation()">
                            <!-- 1. WhatsApp Inteligente -->
                            @if($client->phone && !empty($cleanPhone))
                                <a href="{{ $phoneLink }}" target="_blank"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white border border-[#25D366]/20 transition-all shadow-sm"
                                    title="WhatsApp: Recordatorio automático ({{ $badgeText }})">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                    </svg>
                                </a>
                            @endif

                            <!-- 2. Ver Ficha / Perfil Completo -->
                            <a href="{{ route('clientes.show', $client->id) }}"
                                class="px-3 py-1.5 rounded-lg bg-surface-container-high hover:bg-primary hover:text-white text-on-surface font-etiqueta-bold text-[11px] uppercase tracking-wider transition-all flex items-center gap-1 border border-outline-variant/20 shadow-sm"
                                title="Ver Perfil y Ficha Técnica">
                                <span class="material-symbols-outlined text-[15px]">visibility</span>
                                <span class="hidden sm:inline">Ver Ficha</span>
                            </a>

                            <!-- 3. Dar de Baja / Reactivar -->
                            @if($isActive)
                                <button type="button"
                                    onclick="openStatusModal({{ $client->id }}, '{{ addslashes($client->name . ' ' . $client->last_name) }}', 'deactivate')"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-error/10 text-error hover:bg-error hover:text-white border border-error/20 transition-all shadow-sm"
                                    title="Dar de baja al atleta">
                                    <span class="material-symbols-outlined text-[17px]">person_off</span>
                                </button>
                            @else
                                <button type="button"
                                    onclick="openStatusModal({{ $client->id }}, '{{ addslashes($client->name . ' ' . $client->last_name) }}', 'reactivate')"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-500/10 text-green-500 hover:bg-green-500 hover:text-white border border-green-500/20 transition-all shadow-sm"
                                    title="Reactivar atleta">
                                    <span class="material-symbols-outlined text-[17px]">person_add</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="text-center py-16 text-on-surface-variant font-etiqueta-bold uppercase tracking-widest bg-surface-container-lowest">
                        No hay clientes registrados en el sistema.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Baja / Reactivación -->
    <div id="statusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md hidden opacity-0 transition-opacity duration-300">
        <div class="bg-surface-container-high border border-outline-variant/20 rounded-2xl p-6 lg:p-8 max-w-md w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300 relative overflow-hidden" id="statusModalBox">
            <div class="flex items-center gap-4 mb-4">
                <div id="statusModalIconContainer" class="w-12 h-12 rounded-xl flex items-center justify-center">
                    <span id="statusModalIcon" class="material-symbols-outlined text-[24px]"></span>
                </div>
                <div>
                    <h3 id="statusModalTitle" class="font-titular-md text-[20px] text-on-surface"></h3>
                    <p id="statusModalClientName" class="font-etiqueta-bold text-[12px] text-on-surface-variant uppercase tracking-wider mt-0.5"></p>
                </div>
            </div>

            <p id="statusModalDesc" class="font-cuerpo-md text-[14px] text-on-surface-variant mb-6 leading-relaxed"></p>

            <form id="statusForm" method="POST" action="">
                @csrf
                <div class="flex items-center justify-end gap-3">
                    <button type="button" onclick="closeStatusModal()"
                        class="px-5 py-2.5 rounded-lg border border-outline-variant/20 text-on-surface-variant hover:bg-surface-container font-etiqueta-bold text-[12px] uppercase tracking-wider transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" id="statusSubmitBtn"
                        class="px-6 py-2.5 rounded-lg font-etiqueta-bold text-[12px] uppercase tracking-wider transition-all shadow-md">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

@endsection

@push('scripts')
    <script>
        // Modal de Baja / Reactivación
        function openStatusModal(clientId, clientName, action) {
            const modal = document.getElementById('statusModal');
            const modalBox = document.getElementById('statusModalBox');
            const form = document.getElementById('statusForm');
            const title = document.getElementById('statusModalTitle');
            const nameEl = document.getElementById('statusModalClientName');
            const desc = document.getElementById('statusModalDesc');
            const iconContainer = document.getElementById('statusModalIconContainer');
            const icon = document.getElementById('statusModalIcon');
            const submitBtn = document.getElementById('statusSubmitBtn');

            form.action = `/clientes/${clientId}/toggle-status`;
            nameEl.textContent = clientName;

            if (action === 'deactivate') {
                title.textContent = 'Dar de Baja al Atleta';
                desc.innerHTML = 'Al dar de baja, el atleta pasará a estado <strong>Inactivo</strong>. Todo su historial de pagos, asistencias y fichas médicas se mantendrán 100% conservados para consultas y reportes.';
                iconContainer.className = 'w-12 h-12 rounded-xl flex items-center justify-center bg-error/10 text-error border border-error/20';
                icon.textContent = 'person_off';
                submitBtn.className = 'px-6 py-2.5 rounded-lg font-etiqueta-bold text-[12px] uppercase tracking-wider transition-all shadow-md bg-error text-on-error hover:bg-error/90';
                submitBtn.textContent = 'Dar de Baja';
            } else {
                title.textContent = 'Reactivar Atleta';
                desc.innerHTML = 'El atleta volverá a figurar como <strong>Activo</strong> en el sistema para que pueda registrar nuevas membresías, pagos y asistencias.';
                iconContainer.className = 'w-12 h-12 rounded-xl flex items-center justify-center bg-green-500/10 text-green-500 border border-green-500/20';
                icon.textContent = 'person_add';
                submitBtn.className = 'px-6 py-2.5 rounded-lg font-etiqueta-bold text-[12px] uppercase tracking-wider transition-all shadow-md bg-green-600 text-white hover:bg-green-500';
                submitBtn.textContent = 'Reactivar Atleta';
            }

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalBox.classList.remove('scale-95');
                modalBox.classList.add('scale-100');
            }, 10);
        }

        function closeStatusModal() {
            const modal = document.getElementById('statusModal');
            const modalBox = document.getElementById('statusModalBox');
            modal.classList.add('opacity-0');
            modalBox.classList.remove('scale-100');
            modalBox.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Search Filter Logic (Nombre o Cédula)
        function filterClients() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase().trim();
            const list = document.getElementById('client-list');
            const cards = list.getElementsByClassName('client-card');

            const activeChip = document.querySelector('.filter-chip.active');
            const currentCat = activeChip ? activeChip.getAttribute('onclick').match(/'([^']+)'/)[1] : 'all';

            for (let i = 0; i < cards.length; i++) {
                const searchData = (cards[i].getAttribute('data-search') || '').toLowerCase();
                const cardText = (cards[i].innerText || '').toLowerCase();
                const cardCats = cards[i].getAttribute('data-category') || '';

                const matchesSearch = filter === '' || searchData.includes(filter) || cardText.includes(filter);
                const matchesCat = currentCat === 'all' || cardCats.includes(currentCat);

                if (matchesSearch && matchesCat) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }

        // Chips Filter Logic
        function setFilter(category, btnElement) {
            // Update active class on chips
            document.querySelectorAll('.filter-chip').forEach(btn => {
                btn.classList.remove('active', 'bg-primary', 'text-on-primary', 'border-primary/50', 'shadow-md');
                btn.classList.add('bg-surface-container-lowest', 'text-on-surface');
            });

            btnElement.classList.add('active', 'bg-primary', 'text-on-primary', 'border-primary/50', 'shadow-md');
            btnElement.classList.remove('bg-surface-container-lowest', 'text-on-surface');

            // Re-run filter to apply both category and search input
            filterClients();
        }

        // Keyboard shortcut to focus search (Cmd/Ctrl + K)
        document.addEventListener('keydown', function (e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('searchInput').focus();
            }
        });
    </script>
@endpush
