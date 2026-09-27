@extends('layouts.admin')

@section('title', 'Nuevo Cliente - Ares Gym')

@push('styles')
<style>
  /* Scoped styles for the form transitions */
  .hidden-step {
    position: absolute !important;
    visibility: hidden;
    pointer-events: none;
  }
</style>
@endpush

@section('content')
<div class="flex flex-col w-full relative animate-on-load h-full min-h-screen">
    <div class="px-8 md:px-12 py-8 w-full flex flex-col flex-1">
        <div class="flex items-end justify-between mb-margin-lg relative z-10">
            <div class="flex flex-col gap-2">
                <a href="{{ route('clientes.index') }}" class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-[0.2em] mb-2 flex items-center gap-2 hover:text-primary transition-colors w-max">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span> Volver
                </a>
                @if(isset($groupName))
                <div class="inline-flex bg-[#facc15]/20 text-[#facc15] px-4 py-2 rounded-lg items-center gap-2 border border-[#facc15]/30 w-max mb-2">
                    <span class="material-symbols-outlined text-[18px]">group</span>
                    <span class="font-etiqueta-bold text-[12px] uppercase tracking-wider">Registrando atleta para el grupo: {{ $groupName }}</span>
                </div>
                @endif
                <h1 class="font-headline-xl text-[48px] text-on-surface uppercase tracking-tight leading-none" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">Nuevo Atleta</h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant mt-2 max-w-2xl">Registro de ingreso, selección de disciplina y protocolo de adaptación ARES.</p>
            </div>
            <div class="text-right hidden md:block">
                <div class="flex items-center gap-3 justify-end mb-2">
                    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Modo Desarrollador</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="dev-validation-toggle" class="sr-only peer" onchange="toggleDevValidations()">
                        <div class="w-9 h-5 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary-container"></div>
                    </label>
                </div>
                <p class="font-label-md text-label-md text-primary uppercase tracking-widest">Protocolo Activo</p>
                <p class="font-label-sm text-label-sm text-on-surface-variant opacity-70 mt-1">SISTEMA-REG-01</p>
            </div>
        </div>

        <!-- Stepper Navigation -->
        <div class="relative w-full mb-margin-lg">
            <div class="absolute top-1/2 left-0 w-full h-[2px] bg-surface-container-high -translate-y-1/2 z-0 rounded-full"></div>
            <div class="absolute top-1/2 left-0 w-1/4 h-[2px] bg-primary-container -translate-y-1/2 z-0 transition-all duration-500 ease-in-out" id="stepper-progress"></div>
            <div class="relative z-10 flex justify-between items-center w-full">
                <button class="step-btn group flex flex-col items-center gap-3 cursor-default" data-step="1" >
                    <div class="w-12 h-12 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-headline-md text-headline-md shadow-[0_0_20px_rgba(227,27,35,0.4)] transition-all duration-300 step-indicator">1</div>
                    <span class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface transition-colors duration-300 step-label">Identidad</span>
                </button>
                <button class="step-btn group flex flex-col items-center gap-3 cursor-default opacity-50" data-step="2" >
                    <div class="w-12 h-12 rounded-full bg-surface-container-highest text-on-surface-variant flex items-center justify-center font-headline-md text-headline-md transition-all duration-300 step-indicator">2</div>
                    <span class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant transition-colors duration-300 step-label">Disciplina</span>
                </button>
                <button class="step-btn group flex flex-col items-center gap-3 cursor-default opacity-50" data-step="3" >
                    <div class="w-12 h-12 rounded-full bg-surface-container-highest text-on-surface-variant flex items-center justify-center font-headline-md text-headline-md transition-all duration-300 step-indicator">3</div>
                    <span class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant transition-colors duration-300 step-label">Pago</span>
                </button>
                <button class="step-btn group flex flex-col items-center gap-3 cursor-default opacity-50" data-step="4" >
                    <div class="w-12 h-12 rounded-full bg-surface-container-highest text-on-surface-variant flex items-center justify-center font-headline-md text-headline-md transition-all duration-300 step-indicator">4</div>
                    <span class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant transition-colors duration-300 step-label">Confirmación</span>
                </button>
            </div>
        </div>

        <!-- Forms Container -->
        <form id="client-form" onsubmit="submitForm(event)" action="{{ route('clientes.store') }}" method="POST" class="relative w-full overflow-hidden bg-surface-container-low rounded-[24px] shadow-2xl p-margin-md md:p-margin-lg flex-1 flex flex-col md:flex-row gap-8 items-start transition-all duration-500" novalidate>
            @csrf
            @if(isset($groupName))
                <input type="hidden" name="group_name" value="{{ $groupName }}">
                <input type="hidden" name="group_membership_id" value="{{ $groupMembershipId }}">
            @endif
            <div id="form-steps-container" class="flex-1 relative w-full transition-all duration-500">
                <!-- Ambient Background Graphic -->
                <div class="absolute top-0 right-0 w-96 h-96 bg-primary-container/5 rounded-full blur-[100px] pointer-events-none -mr-48 -mt-48 mix-blend-screen"></div>
                
                <!-- Step 1: Datos Personales -->
                <div class="form-step relative p-margin-md md:p-margin-lg transition-all duration-500 transform translate-x-0 opacity-100 flex flex-col" id="step-1">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-full bg-primary-container/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-[24px]">person</span>
                        </div>
                        <div>
                            <h2 class="font-headline-lg text-headline-lg text-on-surface">Datos Personales</h2>
                            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mt-1">Identificación del atleta</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="flex flex-col gap-2">
                            <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Nombres</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-on-surface-variant group-focus-within:text-primary transition-colors">badge</span>
                                </div>
                                <input class="w-full bg-surface-container-high text-on-surface font-body-lg pl-14 pr-4 py-5 rounded-2xl border-2 border-transparent focus:border-primary-container focus:bg-surface-container-highest focus:outline-none transition-all duration-300 shadow-sm hover:border-outline-variant" name="name" placeholder="Ej. Marcus" type="text" required/>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Apellidos</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-on-surface-variant group-focus-within:text-primary transition-colors">badge</span>
                                </div>
                                <input class="w-full bg-surface-container-high text-on-surface font-body-lg pl-14 pr-4 py-5 rounded-2xl border-2 border-transparent focus:border-primary-container focus:bg-surface-container-highest focus:outline-none transition-all duration-300 shadow-sm hover:border-outline-variant" name="last_name" placeholder="Ej. Aurelius" type="text" required/>
                            </div>
                        </div>

                        <!-- Género -->
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Género (Avatar)</label>
                            <div class="flex gap-4">
                                <label class="flex-1 cursor-pointer group">
                                    <input type="radio" name="gender" value="M" class="peer sr-only" required checked>
                                    <div class="p-4 bg-surface-container-high rounded-2xl border-2 border-transparent peer-checked:border-primary peer-checked:bg-primary/5 transition-all flex flex-col items-center gap-2 hover:border-outline-variant">
                                        <img src="{{ asset('images/avatarM.jpeg') }}" class="w-12 h-12 rounded-full object-cover border-2 border-transparent peer-checked:border-primary group-hover:scale-105 transition-transform">
                                        <span class="font-etiqueta-bold text-on-surface text-[12px] uppercase">Masculino</span>
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer group">
                                    <input type="radio" name="gender" value="F" class="peer sr-only" required>
                                    <div class="p-4 bg-surface-container-high rounded-2xl border-2 border-transparent peer-checked:border-primary peer-checked:bg-primary/5 transition-all flex flex-col items-center gap-2 hover:border-outline-variant">
                                        <img src="{{ asset('images/avatarF.png') }}" class="w-12 h-12 rounded-full object-cover border-2 border-transparent peer-checked:border-primary group-hover:scale-105 transition-transform">
                                        <span class="font-etiqueta-bold text-on-surface text-[12px] uppercase">Femenino</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Cédula / Identidad</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-on-surface-variant group-focus-within:text-primary transition-colors">id_card</span>
                                </div>
                                <input class="w-full bg-surface-container-high text-on-surface font-headline-sm pl-14 pr-4 py-5 rounded-2xl border-2 border-transparent focus:border-primary-container focus:bg-surface-container-highest focus:outline-none transition-all duration-300 shadow-sm hover:border-outline-variant tracking-wider" name="id_card" placeholder="1234567890" type="text" maxlength="10" required/>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Teléfono de Contacto</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-on-surface-variant group-focus-within:text-primary transition-colors">call</span>
                                </div>
                                <input class="w-full bg-surface-container-high text-on-surface font-headline-sm pl-14 pr-4 py-5 rounded-2xl border-2 border-transparent focus:border-primary-container focus:bg-surface-container-highest focus:outline-none transition-all duration-300 shadow-sm hover:border-outline-variant tracking-wider" name="phone" placeholder="099 999 9999" type="tel" required/>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Fecha de Nacimiento</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-on-surface-variant group-focus-within:text-primary transition-colors">calendar_month</span>
                                </div>
                                <input class="w-full md:w-1/2 bg-surface-container-high text-on-surface font-body-lg pl-14 pr-4 py-5 rounded-2xl border-2 border-transparent focus:border-primary-container focus:bg-surface-container-highest focus:outline-none transition-all duration-300 shadow-sm hover:border-outline-variant" name="birth_date" type="date" max="{{ date('Y-m-d') }}" required/>
                            </div>
                        </div>
                    </div>
                    <div class="mt-auto pt-8 flex justify-end border-t border-surface-container-highest mt-8">
                        <button type="button" class="bg-primary-container text-on-primary-container px-8 py-4 rounded-xl font-headline-md text-[16px] uppercase tracking-wider hover:brightness-110 transition-all flex items-center gap-3 shadow-[0_4px_15px_rgba(227,27,35,0.3)] hover:shadow-[0_4px_25px_rgba(227,27,35,0.5)] opacity-50 cursor-not-allowed" id="btnNext1" onclick="nextStep(1)">
                            Siguiente <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Selección de Plan -->
                <div class="form-step relative p-margin-md md:p-margin-lg transition-all duration-500 transform translate-x-full opacity-0 flex flex-col hidden-step pointer-events-none" id="step-2">
                    <div class="flex justify-between items-end mb-6">
                        <h2 class="font-headline-lg text-headline-lg text-on-surface">Membresías Individuales</h2>
                        <div class="bg-primary-container/10 px-4 py-2 rounded-lg border border-primary-container/30 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary-container text-sm">info</span>
                            <span class="font-label-sm text-label-sm text-primary uppercase">Incluye Medición + Nutrición*</span>
                        </div>
                    </div>
                    @if(isset($groupName))
                    <div class="mb-6 bg-[#facc15]/10 border border-[#facc15]/30 p-4 rounded-xl flex items-start gap-3">
                        <span class="material-symbols-outlined text-[#facc15]">lock</span>
                        <div class="flex flex-col">
                            <span class="font-headline-md text-on-surface text-sm">Plan Grupal Bloqueado</span>
                            <span class="font-body-md text-on-surface-variant text-xs mt-1">Este atleta será asignado automáticamente a la membresía del grupo <strong>{{ $groupName }}</strong>. No es necesario ni posible seleccionar otro plan.</span>
                        </div>
                    </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pb-8">
                        @foreach($plans as $index => $plan)
                            @if(isset($groupPlanId) && $groupPlanId != $plan->id)
                                @continue
                            @endif
                        <label class="relative cursor-pointer group">
                            <input {{ (isset($groupPlanId) && $groupPlanId == $plan->id) ? "checked" : ($index === 0 && !isset($groupPlanId) ? "checked" : "") }} class="peer sr-only" name="plan_id" value="{{ $plan->id }}" type="radio" onchange="handlePlanChange()"/>
                            <div class="h-full p-6 bg-surface-container rounded-2xl border-2 border-transparent peer-checked:border-primary-container peer-checked:bg-surface-container-high transition-all duration-300 flex flex-col gap-4 shadow-sm hover:bg-surface-container-high relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-16 h-16 bg-primary-container/10 rounded-bl-full peer-checked:bg-primary-container/20 transition-colors"></div>
                                <div class="flex justify-between items-start">
                                    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">{{ $plan->validity_days }} Días</span>
                                    <span class="material-symbols-outlined text-on-surface-variant opacity-0 peer-checked:opacity-100 peer-checked:text-primary-container transition-all">check_circle</span>
                                </div>
                                <div>
                                    <h3 class="font-headline-md text-headline-md text-on-surface">{{ $plan->name }}</h3>
                                    <div class="flex items-baseline gap-1 mt-1">
                                        <span class="font-headline-lg text-headline-lg text-primary">${{ number_format($plan->price, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    <div class="mt-auto pt-4 flex justify-between">
                        <button type="button" class="text-on-surface-variant px-6 py-4 font-headline-md text-[16px] uppercase tracking-wider hover:text-primary transition-all flex items-center gap-2" onclick="prevStep(2)">
                            <span class="material-symbols-outlined">arrow_back</span> Atrás
                        </button>
                        <button type="button" class="bg-primary-container text-on-primary-container px-8 py-4 rounded-xl font-headline-md text-[16px] uppercase tracking-wider hover:brightness-110 transition-all flex items-center gap-3 shadow-[0_4px_15px_rgba(227,27,35,0.3)] hover:shadow-[0_4px_25px_rgba(227,27,35,0.5)]" id="btnNext2" onclick="nextStep(2)">
                            Siguiente <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Información de Pago y Abonos -->
                <div class="form-step relative p-margin-md md:p-margin-lg transition-all duration-500 transform translate-x-full opacity-0 flex flex-col hidden-step pointer-events-none" id="step-3">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="font-headline-lg text-headline-lg text-on-surface">Información de Pago</h2>
                            <p class="font-body-md text-sm text-on-surface-variant mt-1">El recibo se emitirá directamente a nombre del cliente registrado.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        
                        <!-- Tipo de Pago: Completo vs Abono -->
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest flex items-center justify-between">
                                <span>Modalidad de Pago</span>
                                <span id="abono-auth-badge" class="hidden font-etiqueta-bold text-[10px] text-green-500 uppercase tracking-wider bg-green-500/10 px-2.5 py-0.5 rounded-full border border-green-500/20 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">verified</span> Autorizado por Admin
                                </span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative cursor-pointer group">
                                    <input checked="" class="peer sr-only" name="payment_type" onchange="handlePaymentTypeChange(this)" type="radio" value="completo"/>
                                    <div class="p-4 bg-surface-container rounded-xl border-2 border-transparent peer-checked:border-primary-container peer-checked:bg-surface-container-high transition-all text-center flex items-center justify-center gap-2.5">
                                        <span class="material-symbols-outlined text-[20px] text-on-surface-variant group-hover:text-on-surface">paid</span>
                                        <span class="font-headline-md text-[15px] text-on-surface font-bold">Pago Completo</span>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer group">
                                    <input class="peer sr-only" name="payment_type" id="payment-type-abono" onchange="handlePaymentTypeChange(this)" type="radio" value="abono"/>
                                    <div class="p-4 bg-surface-container rounded-xl border-2 border-transparent peer-checked:border-primary-container peer-checked:bg-surface-container-high transition-all text-center flex items-center justify-center gap-2.5">
                                        <span class="material-symbols-outlined text-[20px] text-on-surface-variant group-hover:text-primary">receipt</span>
                                        <span class="font-headline-md text-[15px] text-on-surface font-bold">Abono (Parcial)</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Bloque de Configuración de Abono (Oculto por defecto) -->
                        <div id="abono-container" class="hidden md:col-span-2 bg-surface-container-low border border-outline-variant/15 p-5 rounded-2xl flex flex-col gap-4 transition-all">
                            
                            <!-- Si NO está autorizado -->
                            <div id="abono-unauthorized-box" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-primary/10 border border-primary/20 rounded-xl">
                                <div class="flex items-center gap-3 text-primary">
                                    <span class="material-symbols-outlined text-[24px]">lock</span>
                                    <div>
                                        <p class="font-etiqueta-bold text-[12px] uppercase tracking-wider text-on-surface">Autorización Administrativa Requerida</p>
                                        <p class="font-body-md text-xs text-on-surface-variant">El registro de abonos parciales (25% al 75%) requiere clave del administrador.</p>
                                    </div>
                                </div>
                                <button type="button" onclick="openAbonoModal()" class="bg-primary text-on-primary hover:bg-[#ff2a35] px-5 py-2.5 rounded-xl font-titular-md text-[12px] uppercase tracking-wider transition-all flex items-center gap-2 shrink-0 shadow-sm justify-center">
                                    <span class="material-symbols-outlined text-[16px]">key</span> Autorizar Abono
                                </button>
                            </div>

                            <!-- Si ESTÁ autorizado: Inputs, atajos de porcentaje, barra interactiva y desglose -->
                            <div id="abono-fields-box" class="hidden flex flex-col gap-5">
                                
                                <!-- Header con Reglas Claras -->
                                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 bg-surface-container/60 p-3.5 rounded-xl border border-outline-variant/10">
                                    <div class="flex items-center gap-2 text-on-surface text-xs font-etiqueta-bold uppercase tracking-wider">
                                        <span class="material-symbols-outlined text-primary text-[18px]">info</span>
                                        <span>Rango de Abono Permitido: <span class="text-primary font-bold">25% a 75%</span></span>
                                    </div>
                                    <div class="flex items-center gap-2 sm:gap-3 text-[11px] font-mono text-on-surface-variant">
                                        <span class="bg-surface-container-highest px-2.5 py-1 rounded-md">Mín (25%): $<span id="abono-min-display">0.00</span></span>
                                        <span class="bg-surface-container-highest px-2.5 py-1 rounded-md">Máx (75%): $<span id="abono-max-display">0.00</span></span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                                    <!-- Columna Izquierda: Input y Atajos -->
                                    <div class="flex flex-col gap-3">
                                        <div class="flex flex-col gap-1.5">
                                            <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest flex justify-between items-center">
                                                <span>Monto a Abonar Hoy ($)</span>
                                                <span id="abono-percentage-badge" class="text-[11px] font-mono text-primary font-bold">0%</span>
                                            </label>
                                            <div class="relative group">
                                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant font-bold text-lg pointer-events-none">$</span>
                                                <input type="number" step="0.01" min="0.01" id="abono_amount_input" name="abono_amount" placeholder="0.00" 
                                                       class="w-full bg-surface-container-lowest text-on-surface font-headline-md text-[20px] font-bold pl-9 pr-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary border border-outline-variant/10 shadow-inner transition-all" 
                                                       onkeydown="filterAbonoKeys(event)" 
                                                       oninput="calculateAbonoBalance()" 
                                                       onblur="clampAbonoOnBlur()" 
                                                       onchange="clampAbonoOnBlur()" 
                                                       onpaste="handleAbonoPaste(event)">
                                            </div>
                                        </div>

                                        <!-- Botones de Atajo Rápido Interactivos (Presets 25%, 50%, 75%) -->
                                        <div class="flex flex-col gap-1.5">
                                            <span class="text-[10px] uppercase tracking-wider text-on-surface-variant/80 font-etiqueta-bold">Selección Rápida:</span>
                                            <div class="grid grid-cols-3 gap-2">
                                                <button type="button" onclick="setAbonoPercentage(25)" class="abono-preset-btn bg-surface-container hover:bg-surface-container-highest border border-outline-variant/15 text-on-surface py-2 px-1 rounded-lg text-center transition-all group flex flex-col items-center">
                                                    <span class="text-[10px] font-bold text-on-surface-variant group-hover:text-primary">25% (Mín)</span>
                                                    <span class="text-[12px] font-mono font-bold text-on-surface">$<span id="btn-val-25">0.00</span></span>
                                                </button>
                                                <button type="button" onclick="setAbonoPercentage(50)" class="abono-preset-btn bg-surface-container hover:bg-surface-container-highest border border-outline-variant/15 text-on-surface py-2 px-1 rounded-lg text-center transition-all group flex flex-col items-center">
                                                    <span class="text-[10px] font-bold text-on-surface-variant group-hover:text-primary">50% (Medio)</span>
                                                    <span class="text-[12px] font-mono font-bold text-on-surface">$<span id="btn-val-50">0.00</span></span>
                                                </button>
                                                <button type="button" onclick="setAbonoPercentage(75)" class="abono-preset-btn bg-surface-container hover:bg-surface-container-highest border border-outline-variant/15 text-on-surface py-2 px-1 rounded-lg text-center transition-all group flex flex-col items-center">
                                                    <span class="text-[10px] font-bold text-on-surface-variant group-hover:text-primary">75% (Máx)</span>
                                                    <span class="text-[12px] font-mono font-bold text-on-surface">$<span id="btn-val-75">0.00</span></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Columna Derecha: Desglose Visual y Barra de Progreso -->
                                    <div class="flex flex-col gap-3">
                                        <div class="bg-surface-container p-4 rounded-xl border border-outline-variant/10 flex flex-col gap-2 shadow-sm">
                                            <div class="flex justify-between items-center text-xs text-on-surface-variant font-body-md">
                                                <span>Valor Total del Plan:</span>
                                                <span class="font-bold text-on-surface text-[14px]">$<span id="abono-plan-price-display">0.00</span></span>
                                            </div>
                                            <div class="flex justify-between items-center text-xs text-on-surface-variant font-body-md">
                                                <span>Monto Abonado:</span>
                                                <span class="font-bold text-green-500 text-[14px]">$<span id="abono-paid-display">0.00</span></span>
                                            </div>
                                            <div class="pt-2 border-t border-outline-variant/10 flex justify-between items-center">
                                                <span class="font-etiqueta-bold text-[11px] uppercase tracking-wider text-error">Saldo Adeudado:</span>
                                                <span class="font-titular-md text-[18px] text-error font-bold">$<span id="abono-debt-display">0.00</span></span>
                                            </div>
                                        </div>

                                        <!-- Barra Visual Interactiva de Porcentaje -->
                                        <div class="flex flex-col gap-1 px-1">
                                            <div class="flex justify-between text-[9px] uppercase tracking-wider text-on-surface-variant font-mono">
                                                <span>0%</span>
                                                <span class="text-amber-400 font-bold">25% Mín</span>
                                                <span class="text-green-400 font-bold">50%</span>
                                                <span class="text-amber-400 font-bold">75% Máx</span>
                                                <span>100%</span>
                                            </div>
                                            <div class="w-full bg-surface-container-lowest h-2.5 rounded-full overflow-hidden border border-outline-variant/10 relative">
                                                <div id="abono-progress-bar" class="h-full bg-primary transition-all duration-300 rounded-full" style="width: 0%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mensajes Dinámicos de Interacción y Feedback en Vivo -->
                                <div id="abono-interaction-msg" class="p-3.5 rounded-xl border text-xs font-body-md flex items-center gap-2.5 transition-all bg-surface-container-high border-outline-variant/20 text-on-surface-variant">
                                    <span id="abono-msg-icon" class="material-symbols-outlined text-[18px] text-primary shrink-0">info</span>
                                    <span id="abono-msg-text">Ingrese un monto entre el 25% y 75% del valor del plan seleccionado.</span>
                                </div>

                            </div>
                        </div>

                        <!-- Método de Pago -->
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Método de Pago</label>
                            <div class="flex gap-4">
                                <label class="flex-1 relative cursor-pointer group">
                                    <input checked="" class="peer sr-only" name="payment_method" onchange="toggleReceiptField(this)" type="radio" value="efectivo"/>
                                    <div class="p-4 bg-surface-container rounded-xl border-2 border-transparent peer-checked:border-primary-container peer-checked:bg-surface-container-high transition-all text-center">
                                        <span class="font-headline-md text-headline-md text-on-surface">Efectivo</span>
                                    </div>
                                </label>
                                <label class="flex-1 relative cursor-pointer group">
                                    <input class="peer sr-only" name="payment_method" onchange="toggleReceiptField(this)" type="radio" value="transferencia"/>
                                    <div class="p-4 bg-surface-container rounded-xl border-2 border-transparent peer-checked:border-primary-container peer-checked:bg-surface-container-high transition-all text-center">
                                        <span class="font-headline-md text-headline-md text-on-surface">Transferencia</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Campo de comprobante (Transferencia) -->
                        <div class="flex flex-col gap-2 md:col-span-2 hidden transition-all duration-300" id="receipt-field">
                            <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Número de Comprobante / Voucher</label>
                            <div class="relative group">
                                <input class="w-full bg-surface-container-lowest text-on-surface font-body-md p-4 rounded-xl focus:outline-none transition-all duration-300 shadow-inner group-focus-within:bg-surface-container-highest border border-transparent focus:border-primary/50" name="voucher_number" placeholder="Ej. 123456789" type="text"/>
                            </div>
                        </div>

                        <!-- Aviso de Titularidad del Recibo -->
                        <div class="md:col-span-2 bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/10 flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[22px]">badge</span>
                            <p class="font-body-md text-xs text-on-surface-variant">
                                El recibo de pago y comprobante interno se registrará automáticamente a nombre del atleta ingresado en el Paso 1 (<strong id="client-preview-name" class="text-on-surface">Atleta</strong>, C.I. <strong id="client-preview-id" class="text-on-surface">---</strong>).
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-auto pt-8 flex justify-between">
                        <button type="button" class="text-on-surface-variant px-6 py-4 font-headline-md text-[16px] uppercase tracking-wider hover:text-primary transition-all flex items-center gap-2" onclick="prevStep(3)">
                            <span class="material-symbols-outlined">arrow_back</span> Atrás
                        </button>
                        <button class="bg-primary-container text-on-primary-container px-8 py-4 rounded-xl font-headline-md text-[16px] uppercase tracking-wider hover:brightness-110 transition-all flex items-center gap-3 shadow-[0_4px_15px_rgba(227,27,35,0.3)] hover:shadow-[0_4px_25px_rgba(227,27,35,0.5)]" type="button" onclick="submitForm()" id="submitBtn">
                            Confirmar Registro <span class="material-symbols-outlined">done</span>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Confirmación -->
                <div class="form-step relative p-margin-md md:p-margin-lg transition-all duration-500 transform translate-x-full opacity-0 flex flex-col h-full overflow-y-auto hidden-step pointer-events-none items-center justify-center" id="step-4">
                    <div class="flex flex-col items-center justify-center text-center w-full max-w-lg mx-auto" id="step-4-content">
                        <div class="w-32 h-32 bg-primary/10 rounded-full flex items-center justify-center mb-6" id="step-4-icon-container">
                            <span class="material-symbols-outlined text-[72px] text-primary animate-pulse" id="step-4-icon">visibility</span>
                        </div>
                        <h2 class="font-headline-xl text-[48px] text-on-surface mb-4" style="font-family: 'Montserrat', sans-serif; font-weight: 800;" id="step-4-title">Verificación</h2>
                        <p class="font-body-lg text-body-lg text-on-surface-variant" id="step-4-message">Por favor, revisa el recibo a la derecha. Si los datos son correctos, presiona <strong>Confirmar</strong> en el recibo para registrar al atleta.</p>
                    </div>
                    <div class="mt-12 justify-center w-full gap-4 hidden" id="step-4-actions">
                        <a href="{{ route('clientes.index') }}" class="bg-surface-container-high text-on-surface px-8 py-4 rounded-xl font-headline-md text-[16px] uppercase tracking-wider hover:bg-surface-container-highest transition-all flex items-center gap-3 shadow-[0_4px_15px_rgba(0,0,0,0.3)] border border-outline-variant/10">
                            Ir al Roster
                        </a>
                        <button type="button" class="bg-primary-container text-on-primary-container px-8 py-4 rounded-xl font-headline-md text-[16px] uppercase tracking-wider hover:brightness-110 transition-all flex items-center gap-3 shadow-[0_4px_15px_rgba(227,27,35,0.3)]" onclick="location.reload()">
                            Nuevo Registro <span class="material-symbols-outlined">refresh</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Live Receipt Sidebar -->
            <div id="receipt-sidebar" class="w-full md:w-[400px] hidden md:flex flex-col bg-[#1A1A1A] rounded-[24px] p-8 shadow-2xl border border-surface-container-highest transition-all duration-700 transform sticky top-8">
                <div class="flex items-center gap-3 mb-6 pb-6 border-b border-surface-container-highest border-dashed">
                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">receipt_long</span>
                    </div>
                    <div>
                        <h3 class="font-headline-sm text-[18px] text-on-surface uppercase tracking-wider">Resumen</h3>
                        <p class="font-label-sm text-label-sm text-on-surface-variant tracking-widest mt-1">ARES GYM REGISTRO</p>
                    </div>
                </div>
                
                <div class="flex flex-col gap-5 flex-1">
                    <div class="flex flex-col gap-1">
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Atleta</span>
                        <span id="receipt-name" class="font-body-lg text-on-surface text-lg">---</span>
                        <span id="receipt-id" class="font-body-sm text-on-surface-variant opacity-70">---</span>
                    </div>
                    
                    <div class="flex flex-col gap-1">
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Plan Seleccionado</span>
                        <span id="receipt-plan" class="font-body-lg text-primary text-lg font-bold">---</span>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Modalidad de Pago</span>
                        <span id="receipt-type-badge" class="px-2.5 py-1 rounded bg-surface-container-high text-on-surface text-[12px] font-etiqueta-bold uppercase tracking-wider w-max">Pago Completo</span>
                    </div>
                    
                    <div class="flex flex-col gap-1">
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Método de Pago</span>
                        <div class="flex items-center gap-2">
                            <span id="receipt-method-icon" class="material-symbols-outlined text-[20px] text-on-surface-variant">payments</span>
                            <span id="receipt-method" class="font-body-lg text-on-surface capitalize">---</span>
                        </div>
                    </div>

                    <!-- Desglose de Abono en Sidebar -->
                    <div id="receipt-abono-breakdown" class="hidden flex-col gap-2 pt-3 border-t border-surface-container-highest border-dashed text-xs font-body-md">
                        <div class="flex justify-between items-center text-on-surface-variant">
                            <span>Total Plan:</span>
                            <span class="text-on-surface font-bold">$<span id="receipt-plan-total-val">0.00</span></span>
                        </div>
                        <div class="flex justify-between items-center text-green-500 font-bold">
                            <span>Abono Pagado:</span>
                            <span>$<span id="receipt-abono-paid-val">0.00</span></span>
                        </div>
                        <div class="flex justify-between items-center text-error font-bold">
                            <span>Saldo Adeudado:</span>
                            <span>$<span id="receipt-debt-val">0.00</span></span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t border-surface-container-highest border-dashed flex justify-between items-end">
                    <span id="receipt-total-label" class="font-headline-sm text-on-surface-variant uppercase tracking-wider text-xs">Total a Pagar</span>
                    <div class="text-right">
                        <span class="font-headline-lg text-[32px] text-on-surface font-bold">$<span id="receipt-total">0.00</span></span>
                    </div>
                </div>

                <!-- Review Actions (Hidden by default, shown in Step 4) -->
                <div id="receipt-actions" class="mt-8 flex flex-col gap-4 hidden opacity-0 transition-opacity duration-500">
                    <button type="button" onclick="submitFormFinal(event)" class="w-full bg-primary-container text-on-primary-container py-4 rounded-xl font-headline-md text-[16px] uppercase tracking-wider hover:brightness-110 transition-all flex items-center justify-center gap-3 shadow-[0_4px_15px_rgba(227,27,35,0.3)]">
                        Confirmar <span class="material-symbols-outlined">check_circle</span>
                    </button>
                    <button type="button" onclick="goToStep(3)" class="w-full bg-surface-container-highest text-on-surface py-4 rounded-xl font-headline-sm uppercase tracking-wider hover:brightness-110 transition-all">
                        Corregir
                    </button>
                </div>
            </div>
        </form>

        <!-- Modal de Autorización de Abono -->
        <div id="abono-auth-modal" class="fixed inset-0 z-[110] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
            <div class="absolute inset-0 bg-black/75 backdrop-blur-sm" onclick="closeAbonoModal()"></div>
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
                <div id="abono-success-msg" class="hidden p-3.5 bg-green-500/10 border border-green-500/20 rounded-xl text-green-500 text-xs font-body-md flex items-center gap-2">
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

        <!-- Error Modal Genérico con Redirección Inteligente -->
        <div id="error-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
            <div class="absolute inset-0 bg-black/75 backdrop-blur-sm" onclick="closeErrorModal()"></div>
            <div class="bg-surface-container-high p-8 rounded-[24px] shadow-2xl relative z-10 max-w-md w-full transform scale-95 transition-transform duration-300 border border-outline-variant/20" id="error-modal-content">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-error/20 mx-auto mb-6">
                    <span class="material-symbols-outlined text-[32px] text-error">warning</span>
                </div>
                <h3 class="font-headline-md text-[24px] text-center text-on-surface mb-2">Atención</h3>
                <p id="error-modal-message" class="font-body-md text-on-surface-variant text-center mb-8 leading-relaxed"></p>
                <button type="button" id="btn-close-error-modal" class="w-full bg-primary text-on-primary py-4 rounded-xl font-headline-sm uppercase tracking-wider hover:brightness-110 transition-all shadow-[0_4px_15px_rgba(227,27,35,0.3)] flex items-center justify-center gap-2" onclick="closeErrorModal()">
                    Entendido
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 4;
    let skipValidations = false;
    let isAbonoAuthorized = false;
    let pendingRedirectStep = null;
    let pendingRedirectInput = null;

    function toggleDevValidations() {
        skipValidations = document.getElementById('dev-validation-toggle').checked;
        if(skipValidations) {
            document.querySelector('input[name="name"]').value = "Marcus";
            document.querySelector('input[name="last_name"]').value = "Aurelius";
            document.querySelector('input[name="id_card"]').value = Math.floor(1000000000 + Math.random() * 9000000000).toString();
            document.querySelector('input[name="phone"]').value = "0991234567";
            document.querySelector('input[name="birth_date"]').value = "1990-01-01";
            
            const firstPlan = document.querySelector('input[name="plan_id"]');
            if(firstPlan) firstPlan.checked = true;
            
            const methodEfectivo = document.querySelector('input[name="payment_method"][value="efectivo"]');
            if(methodEfectivo) methodEfectivo.checked = true;
        } else {
            document.getElementById('client-form').reset();
        }
        checkFormValidity();
    }

    function validarCedula(cedula) {
        if (!cedula) return false;
        return cedula.length === 10 && /^\d+$/.test(cedula);
    }

    function showErrorModal(message, targetStep = null, targetInputId = null) {
        pendingRedirectStep = targetStep;
        pendingRedirectInput = targetInputId;

        document.getElementById('error-modal-message').textContent = message;
        const modal = document.getElementById('error-modal');
        const modalContent = document.getElementById('error-modal-content');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modalContent.classList.remove('scale-95');

        const btnClose = document.getElementById('btn-close-error-modal');
        if (btnClose) {
            btnClose.innerHTML = targetStep ? `Corregir en Paso ${targetStep} <span class="material-symbols-outlined text-[18px]">arrow_forward</span>` : 'Entendido';
        }
    }

    function closeErrorModal() {
        const modal = document.getElementById('error-modal');
        const modalContent = document.getElementById('error-modal-content');
        modal.classList.add('opacity-0', 'pointer-events-none');
        modalContent.classList.add('scale-95');

        const stepToRedirect = pendingRedirectStep;
        const inputToFocus = pendingRedirectInput;
        pendingRedirectStep = null;
        pendingRedirectInput = null;

        if (stepToRedirect) {
            goToStep(stepToRedirect);
        }
        if (inputToFocus) {
            setTimeout(() => {
                const el = document.getElementById(inputToFocus) || document.querySelector(`[name="${inputToFocus}"]`);
                if (el) {
                    el.focus();
                    if (el.scrollIntoView) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    el.classList.add('ring-4', 'ring-error/50', 'border-error');
                    setTimeout(() => el.classList.remove('ring-4', 'ring-error/50'), 3000);
                }
            }, 250);
        }
    }

    function clampAbonoOnBlur() {
        const planRadio = document.querySelector('input[name="plan_id"]:checked');
        let planPrice = 0;
        if (planRadio) {
            const planCard = planRadio.closest('label');
            const priceText = planCard.querySelector('.text-primary').textContent;
            planPrice = parseFloat(priceText.replace('$', '').trim()) || 0;
        }
        const maxAbono = Math.round(planPrice * 0.75 * 100) / 100;
        const abonoInput = document.getElementById('abono_amount_input');
        let abonoVal = parseFloat(abonoInput.value) || 0;

        if (abonoVal > maxAbono) {
            abonoInput.value = maxAbono.toFixed(2);
            calculateAbonoBalance();
        }
    }

    /* Modal de Autorización de Abono */
    function openAbonoModal() {
        document.getElementById('abono-admin-password').value = '';
        document.getElementById('abono-error-msg').classList.add('hidden');
        document.getElementById('abono-success-msg').classList.add('hidden');
        
        const modal = document.getElementById('abono-auth-modal');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        setTimeout(() => document.getElementById('abono-admin-password').focus(), 100);
    }

    function closeAbonoModal() {
        const modal = document.getElementById('abono-auth-modal');
        modal.classList.add('opacity-0', 'pointer-events-none');
    }

    function cancelAbonoModal() {
        closeAbonoModal();
        if (!isAbonoAuthorized) {
            // Revert back to pago completo
            const radioCompleto = document.querySelector('input[name="payment_type"][value="completo"]');
            if (radioCompleto) {
                radioCompleto.checked = true;
                handlePaymentTypeChange(radioCompleto);
            }
        }
    }

    async function verifyAbonoPassword() {
        const password = document.getElementById('abono-admin-password').value;
        const errorBox = document.getElementById('abono-error-msg');
        const errorText = document.getElementById('abono-error-text');
        const successBox = document.getElementById('abono-success-msg');
        const btnVerify = document.getElementById('btn-verify-abono');

        errorBox.classList.add('hidden');
        successBox.classList.add('hidden');

        if (!password) {
            errorText.textContent = 'Ingrese la clave de administrador para autorizar.';
            errorBox.classList.remove('hidden');
            return;
        }

        btnVerify.disabled = true;
        btnVerify.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">hourglass_empty</span> Verificando...';

        try {
            const response = await fetch("{{ route('clientes.authorizeAbono') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ password: password })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                isAbonoAuthorized = true;
                successBox.classList.remove('hidden');
                
                setTimeout(() => {
                    closeAbonoModal();
                    applyAbonoAuthorizedState();
                }, 700);
            } else {
                errorText.textContent = data.message || 'Clave de autorización incorrecta.';
                errorBox.classList.remove('hidden');
            }
        } catch (e) {
            errorText.textContent = 'Error de conexión al validar la autorización.';
            errorBox.classList.remove('hidden');
        } finally {
            btnVerify.disabled = false;
            btnVerify.innerHTML = '<span class="material-symbols-outlined text-[16px]">verified</span> Validar';
        }
    }

    // Bloqueo estricto de teclas no permitidas: 'e', 'E', '+', '-'
    function filterAbonoKeys(e) {
        if (['e', 'E', '+', '-'].includes(e.key)) {
            e.preventDefault();
        }
    }

    function handleAbonoPaste(e) {
        setTimeout(() => {
            const input = e.target;
            input.value = input.value.replace(/[^0-9.]/g, '');
            calculateAbonoBalance();
        }, 10);
    }

    function setAbonoPercentage(pct) {
        const planRadio = document.querySelector('input[name="plan_id"]:checked');
        let planPrice = 0;
        if (planRadio) {
            const planCard = planRadio.closest('label');
            const priceText = planCard.querySelector('.text-primary').textContent;
            planPrice = parseFloat(priceText.replace('$', '').trim()) || 0;
        }
        const targetVal = (planPrice * (pct / 100)).toFixed(2);
        const abonoInput = document.getElementById('abono_amount_input');
        abonoInput.value = targetVal;
        calculateAbonoBalance();
    }

    function applyAbonoAuthorizedState() {
        document.getElementById('abono-auth-badge').classList.remove('hidden');
        document.getElementById('abono-unauthorized-box').classList.add('hidden');
        document.getElementById('abono-fields-box').classList.remove('hidden');
        
        // Populate current plan price
        const planRadio = document.querySelector('input[name="plan_id"]:checked');
        let planPrice = 0;
        if (planRadio) {
            const planCard = planRadio.closest('label');
            const priceText = planCard.querySelector('.text-primary').textContent;
            planPrice = parseFloat(priceText.replace('$', '').trim()) || 0;
        }
        document.getElementById('abono-plan-price-display').textContent = planPrice.toFixed(2);
        
        const abonoInput = document.getElementById('abono_amount_input');
        if (!abonoInput.value || parseFloat(abonoInput.value) <= 0) {
            // Default suggestion: 50%
            abonoInput.value = (planPrice / 2).toFixed(2);
        }
        calculateAbonoBalance();
    }

    function handlePaymentTypeChange(radio) {
        const abonoContainer = document.getElementById('abono-container');
        if (radio.value === 'abono') {
            abonoContainer.classList.remove('hidden');
            if (!isAbonoAuthorized) {
                openAbonoModal();
            } else {
                applyAbonoAuthorizedState();
            }
        } else {
            abonoContainer.classList.add('hidden');
        }
        updateReceipt();
        checkFormValidity();
    }

    function handlePlanChange() {
        if (isAbonoAuthorized) {
            const planRadio = document.querySelector('input[name="plan_id"]:checked');
            if (planRadio) {
                const planCard = planRadio.closest('label');
                const priceText = planCard.querySelector('.text-primary').textContent;
                const planPrice = parseFloat(priceText.replace('$', '').trim()) || 0;
                document.getElementById('abono-plan-price-display').textContent = planPrice.toFixed(2);
            }
            calculateAbonoBalance();
        }
        updateReceipt();
        checkFormValidity();
    }

    function calculateAbonoBalance() {
        const planRadio = document.querySelector('input[name="plan_id"]:checked');
        let planPrice = 0;
        if (planRadio) {
            const planCard = planRadio.closest('label');
            const priceText = planCard.querySelector('.text-primary').textContent;
            planPrice = parseFloat(priceText.replace('$', '').trim()) || 0;
        }

        const minAbono = Math.round(planPrice * 0.25 * 100) / 100;
        const maxAbono = Math.round(planPrice * 0.75 * 100) / 100;
        const midAbono = Math.round(planPrice * 0.50 * 100) / 100;

        // Actualizar displays de límites y botones de atajo
        const minDisplay = document.getElementById('abono-min-display');
        const maxDisplay = document.getElementById('abono-max-display');
        const btn25 = document.getElementById('btn-val-25');
        const btn50 = document.getElementById('btn-val-50');
        const btn75 = document.getElementById('btn-val-75');

        if (minDisplay) minDisplay.textContent = minAbono.toFixed(2);
        if (maxDisplay) maxDisplay.textContent = maxAbono.toFixed(2);
        if (btn25) btn25.textContent = minAbono.toFixed(2);
        if (btn50) btn50.textContent = midAbono.toFixed(2);
        if (btn75) btn75.textContent = maxAbono.toFixed(2);

        const abonoInput = document.getElementById('abono_amount_input');
        abonoInput.setAttribute('min', minAbono.toFixed(2));
        abonoInput.setAttribute('max', maxAbono.toFixed(2));

        let rawVal = abonoInput.value;
        if (rawVal.includes('e') || rawVal.includes('E') || rawVal.includes('-') || rawVal.includes('+')) {
            rawVal = rawVal.replace(/[^0-9.]/g, '');
            abonoInput.value = rawVal;
        }

        let abonoVal = parseFloat(rawVal);
        const hasValue = !isNaN(abonoVal) && rawVal.trim() !== '';
        if (!hasValue) abonoVal = 0;

        const percentage = planPrice > 0 ? (abonoVal / planPrice) * 100 : 0;
        const pctBadge = document.getElementById('abono-percentage-badge');
        if (pctBadge) pctBadge.textContent = `${percentage.toFixed(1)}%`;

        // Barra de progreso y color interactivo
        const progressBar = document.getElementById('abono-progress-bar');
        if (progressBar) {
            const clampedPct = Math.min(100, Math.max(0, percentage));
            progressBar.style.width = `${clampedPct}%`;
            if (percentage >= 25 && percentage <= 75) {
                progressBar.className = 'h-full bg-green-500 transition-all duration-300 rounded-full';
            } else if (percentage > 75) {
                progressBar.className = 'h-full bg-error transition-all duration-300 rounded-full';
            } else {
                progressBar.className = 'h-full bg-amber-400 transition-all duration-300 rounded-full';
            }
        }

        const debt = Math.max(0, planPrice - abonoVal);

        document.getElementById('abono-plan-price-display').textContent = planPrice.toFixed(2);
        document.getElementById('abono-paid-display').textContent = (hasValue ? abonoVal : 0).toFixed(2);
        document.getElementById('abono-debt-display').textContent = debt.toFixed(2);

        // Mensaje interactivo y validación visual en tiempo real
        const msgBox = document.getElementById('abono-interaction-msg');
        const msgIcon = document.getElementById('abono-msg-icon');
        const msgText = document.getElementById('abono-msg-text');

        if (!hasValue || abonoVal <= 0) {
            if (msgBox) msgBox.className = 'p-3.5 rounded-xl border text-xs font-body-md flex items-center gap-2.5 transition-all bg-error/10 border-error/30 text-error';
            if (msgIcon) {
                msgIcon.textContent = 'warning';
                msgIcon.className = 'material-symbols-outlined text-[18px] text-error shrink-0';
            }
            if (msgText) msgText.textContent = `Ingrese un monto válido. El abono mínimo permitido es del 25% ($${minAbono.toFixed(2)}).`;
            abonoInput.classList.add('border-error');
            abonoInput.classList.remove('border-green-500', 'border-amber-500');
        } else if (abonoVal < minAbono) {
            const diff = (minAbono - abonoVal).toFixed(2);
            if (msgBox) msgBox.className = 'p-3.5 rounded-xl border text-xs font-body-md flex items-center gap-2.5 transition-all bg-amber-500/10 border-amber-500/30 text-amber-400';
            if (msgIcon) {
                msgIcon.textContent = 'report_problem';
                msgIcon.className = 'material-symbols-outlined text-[18px] text-amber-400 shrink-0';
            }
            if (msgText) msgText.textContent = `Monto insuficiente ($${abonoVal.toFixed(2)} - ${percentage.toFixed(1)}%). El abono no puede ser inferior al 25% ($${minAbono.toFixed(2)}). Faltan $${diff}.`;
            abonoInput.classList.add('border-amber-500');
            abonoInput.classList.remove('border-green-500', 'border-error');
        } else if (abonoVal > maxAbono) {
            if (msgBox) msgBox.className = 'p-3.5 rounded-xl border text-xs font-body-md flex items-center gap-2.5 transition-all bg-error/10 border-error/30 text-error';
            if (msgIcon) {
                msgIcon.textContent = 'cancel';
                msgIcon.className = 'material-symbols-outlined text-[18px] text-error shrink-0';
            }
            if (msgText) msgText.textContent = `Monto excedido ($${abonoVal.toFixed(2)} - ${percentage.toFixed(1)}%). El abono no puede superar el 75% ($${maxAbono.toFixed(2)}). Para cancelar el total elija "Pago Completo".`;
            abonoInput.classList.add('border-error');
            abonoInput.classList.remove('border-green-500', 'border-amber-500');
        } else {
            if (msgBox) msgBox.className = 'p-3.5 rounded-xl border text-xs font-body-md flex items-center gap-2.5 transition-all bg-green-500/10 border-green-500/30 text-green-400';
            if (msgIcon) {
                msgIcon.textContent = 'check_circle';
                msgIcon.className = 'material-symbols-outlined text-[18px] text-green-400 shrink-0';
            }
            if (msgText) msgText.textContent = `¡Abono válido! Estás abonando el ${percentage.toFixed(1)}% ($${abonoVal.toFixed(2)}). Saldo adeudado: $${debt.toFixed(2)}.`;
            abonoInput.classList.remove('border-error', 'border-amber-500');
            abonoInput.classList.add('border-green-500');
        }

        updateReceipt();
        checkFormValidity();
    }

    function toggleReceiptField(radio) {
        const field = document.getElementById('receipt-field');
        if (radio.value === 'transferencia') {
            field.classList.remove('hidden');
        } else {
            field.classList.add('hidden');
        }
        updateReceipt();
        checkFormValidity();
    }

    function validateStep(step) {
        if (skipValidations) return true;

        if (step === 1) {
            const name = document.querySelector('input[name="name"]').value.trim();
            const last_name = document.querySelector('input[name="last_name"]').value.trim();
            const id_card = document.querySelector('input[name="id_card"]').value.trim();
            const phone = document.querySelector('input[name="phone"]').value.trim();
            const birth_date = document.querySelector('input[name="birth_date"]').value;

            if (!name) {
                showErrorModal("Por favor, ingresa los nombres del atleta.", 1, 'name');
                return false;
            }
            if (!last_name) {
                showErrorModal("Por favor, ingresa los apellidos del atleta.", 1, 'last_name');
                return false;
            }
            if (!id_card) {
                showErrorModal("Por favor, ingresa la cédula del atleta.", 1, 'id_card');
                return false;
            }
            if (!validarCedula(id_card)) {
                showErrorModal("La cédula debe contener exactamente 10 dígitos numéricos.", 1, 'id_card');
                return false;
            }
            if (!phone) {
                showErrorModal("Por favor, ingresa el número de teléfono/WhatsApp.", 1, 'phone');
                return false;
            }
            if (!birth_date) {
                showErrorModal("Por favor, selecciona la fecha de nacimiento.", 1, 'birth_date');
                return false;
            }
        }
        
        if (step === 2) {
            const plan = document.querySelector('input[name="plan_id"]:checked');
            if (!plan) {
                showErrorModal("Debes seleccionar un plan o membresía para el atleta.", 2);
                return false;
            }
        }

        if (step === 3) {
            const paymentType = document.querySelector('input[name="payment_type"]:checked').value;
            if (paymentType === 'abono') {
                if (!isAbonoAuthorized) {
                    showErrorModal("El registro como abono debe ser autorizado por el Administrador con la clave.", 3, 'abono-admin-password');
                    openAbonoModal();
                    return false;
                }
                const planRadio = document.querySelector('input[name="plan_id"]:checked');
                let planPrice = 0;
                if (planRadio) {
                    const planCard = planRadio.closest('label');
                    const priceText = planCard.querySelector('.text-primary').textContent;
                    planPrice = parseFloat(priceText.replace('$', '').trim()) || 0;
                }
                const minAbono = Math.round(planPrice * 0.25 * 100) / 100;
                const maxAbono = Math.round(planPrice * 0.75 * 100) / 100;
                const abonoVal = parseFloat(document.getElementById('abono_amount_input').value) || 0;
                
                if (abonoVal <= 0) {
                    showErrorModal("Debe ingresar un monto válido para el abono (mayor a $0.00). No se permiten valores negativos ni en cero.", 3, 'abono_amount_input');
                    return false;
                }
                if (abonoVal < minAbono) {
                    showErrorModal(`El abono parcial ($${abonoVal.toFixed(2)}) no puede ser inferior al 25% ($${minAbono.toFixed(2)}) del plan seleccionado ($${planPrice.toFixed(2)}). Ingrese un valor entre $${minAbono.toFixed(2)} y $${maxAbono.toFixed(2)}.`, 3, 'abono_amount_input');
                    return false;
                }
                if (abonoVal > maxAbono) {
                    showErrorModal(`El abono parcial ($${abonoVal.toFixed(2)}) no puede ser superior al 75% ($${maxAbono.toFixed(2)}) del plan seleccionado ($${planPrice.toFixed(2)}). Si cancela el total o desea abonar más, seleccione Pago Completo.`, 3, 'abono_amount_input');
                    return false;
                }
            }

            const method = document.querySelector('input[name="payment_method"]:checked');
            if (!method) {
                showErrorModal("Selecciona un método de pago.", 3);
                return false;
            }
            if (method.value === 'transferencia') {
                const voucher = document.querySelector('input[name="voucher_number"]').value.trim();
                if (!voucher) {
                    showErrorModal("Para pagos con transferencia, debes ingresar el número de comprobante o referencia.", 3, 'voucher_number');
                    return false;
                }
            }
        }
        return true;
    }

    function nextStep(step) {
        if (!validateStep(step)) return;
        goToStep(step + 1);
    }

    function prevStep(step) {
        goToStep(step - 1);
    }

    function updateReceipt() {
        const name = document.querySelector('input[name="name"]').value;
        const lastName = document.querySelector('input[name="last_name"]').value;
        const idCard = document.querySelector('input[name="id_card"]').value;
        
        const athleteName = (name || lastName) ? `${name} ${lastName}`.trim() : '---';
        const athleteId = idCard ? `C.I. ${idCard}` : '---';

        document.getElementById('receipt-name').textContent = athleteName;
        document.getElementById('receipt-id').textContent = athleteId;

        // Preview inside Step 3 banner
        const previewName = document.getElementById('client-preview-name');
        const previewId = document.getElementById('client-preview-id');
        if (previewName) previewName.textContent = (name || lastName) ? `${name} ${lastName}`.trim() : 'Atleta';
        if (previewId) previewId.textContent = idCard || '---';

        // Plan info
        const planRadio = document.querySelector('input[name="plan_id"]:checked');
        let planPrice = 0;
        if (planRadio) {
            const planCard = planRadio.closest('label');
            const planName = planCard.querySelector('h3').textContent;
            const priceText = planCard.querySelector('.text-primary').textContent;
            planPrice = parseFloat(priceText.replace('$', '').trim()) || 0;
            document.getElementById('receipt-plan').textContent = planName;
        } else {
            document.getElementById('receipt-plan').textContent = '---';
        }

        // Payment Type (Abono vs Completo)
        const paymentTypeRadio = document.querySelector('input[name="payment_type"]:checked');
        const isAbono = paymentTypeRadio && paymentTypeRadio.value === 'abono';
        const badge = document.getElementById('receipt-type-badge');
        const abonoBreakdown = document.getElementById('receipt-abono-breakdown');
        const totalLabel = document.getElementById('receipt-total-label');

        if (isAbono) {
            const abonoAmount = parseFloat(document.getElementById('abono_amount_input').value) || 0;
            const debt = Math.max(0, planPrice - abonoAmount);

            badge.className = 'px-2.5 py-1 rounded bg-[#7c1f2a] text-white text-[11px] font-etiqueta-bold uppercase tracking-wider w-max shadow-sm';
            badge.textContent = 'Abono Parcial';
            
            abonoBreakdown.classList.remove('hidden');
            abonoBreakdown.classList.add('flex');
            document.getElementById('receipt-plan-total-val').textContent = planPrice.toFixed(2);
            document.getElementById('receipt-abono-paid-val').textContent = abonoAmount.toFixed(2);
            document.getElementById('receipt-debt-val').textContent = debt.toFixed(2);

            totalLabel.textContent = 'Monto a Cobrar Hoy (Abono)';
            document.getElementById('receipt-total').textContent = abonoAmount.toFixed(2);
        } else {
            badge.className = 'px-2.5 py-1 rounded bg-surface-container-high text-on-surface text-[12px] font-etiqueta-bold uppercase tracking-wider w-max';
            badge.textContent = 'Pago Completo';

            abonoBreakdown.classList.add('hidden');
            abonoBreakdown.classList.remove('flex');

            totalLabel.textContent = 'Total a Pagar';
            document.getElementById('receipt-total').textContent = planPrice.toFixed(2);
        }

        // Method
        const method = document.querySelector('input[name="payment_method"]:checked');
        if (method) {
            document.getElementById('receipt-method').innerHTML = `<span class="material-symbols-outlined text-[16px]">${method.value === 'efectivo' ? 'payments' : 'account_balance'}</span> ${method.value === 'efectivo' ? 'Efectivo' : 'Transferencia'}`;
        } else {
            document.getElementById('receipt-method').innerHTML = `<span class="material-symbols-outlined text-[16px]">payments</span> ---`;
        }
    }

    function checkFormValidity() {
        updateReceipt();
        const btn1 = document.getElementById('btnNext1');
        const btn2 = document.getElementById('btnNext2');
        const btn3 = document.getElementById('submitBtn');

        if (skipValidations) {
            if(btn1) btn1.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
            if(btn2) btn2.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
            if(btn3) btn3.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
            return;
        }

        const idInput = document.querySelector('input[name="id_card"]');
        const isIdValid = validarCedula(idInput.value);
        if (idInput.value.length === 10) {
            if (isIdValid) {
                idInput.classList.remove('border-error', 'focus:border-error');
                idInput.classList.add('border-green-500', 'focus:border-green-500');
            } else {
                idInput.classList.add('border-error', 'focus:border-error');
                idInput.classList.remove('border-green-500', 'focus:border-green-500');
            }
        } else {
            idInput.classList.remove('border-green-500', 'focus:border-green-500', 'border-error', 'focus:border-error');
        }

        const s1Valid = document.querySelector('input[name="name"]').value && 
                        document.querySelector('input[name="last_name"]').value &&
                        validarCedula(document.querySelector('input[name="id_card"]').value) &&
                        document.querySelector('input[name="phone"]').value &&
                        document.querySelector('input[name="birth_date"]').value;
        
        if(btn1) s1Valid ? btn1.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none') : btn1.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');

        const plan = document.querySelector('input[name="plan_id"]:checked');
        const s2Valid = !!plan;
        
        if(btn2) s2Valid ? btn2.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none') : btn2.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');

        const paymentType = document.querySelector('input[name="payment_type"]:checked');
        const method = document.querySelector('input[name="payment_method"]:checked');
        let s3Valid = !!method;

        if (paymentType && paymentType.value === 'abono') {
            if (!isAbonoAuthorized) {
                s3Valid = false;
            } else {
                const planRadio = document.querySelector('input[name="plan_id"]:checked');
                let planPrice = 0;
                if (planRadio) {
                    const planCard = planRadio.closest('label');
                    const priceText = planCard.querySelector('.text-primary').textContent;
                    planPrice = parseFloat(priceText.replace('$', '').trim()) || 0;
                }
                const minAbono = Math.round(planPrice * 0.25 * 100) / 100;
                const maxAbono = Math.round(planPrice * 0.75 * 100) / 100;
                const abonoVal = parseFloat(document.getElementById('abono_amount_input').value) || 0;
                if (abonoVal < minAbono || abonoVal > maxAbono) {
                    s3Valid = false;
                }
            }
        }

        if(method && method.value === 'transferencia') {
            s3Valid = s3Valid && !!document.querySelector('input[name="voucher_number"]').value;
        }
        
        if(btn3) s3Valid ? btn3.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none') : btn3.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', checkFormValidity);
            input.addEventListener('change', checkFormValidity);
        });
        checkFormValidity();
    });

    function submitForm(e) {
        if(e) e.preventDefault();
        if(!validateStep(1)) return;
        if(!validateStep(2)) return;
        if(!validateStep(3)) return;
        goToStep(4);
    }

    async function submitFormFinal(e) {
        if(e) e.preventDefault();
        
        if(!validateStep(1)) return;
        if(!validateStep(2)) return;
        if(!validateStep(3)) return;

        const form = document.getElementById('client-form');
        const formData = new FormData(form);
        
        const step4Content = document.getElementById('step-4-content');
        const step4Actions = document.getElementById('step-4-actions');
        
        step4Content.innerHTML = `
            <div class="w-32 h-32 bg-primary-container/20 rounded-full flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-[72px] text-primary-container animate-spin">hourglass_empty</span>
            </div>
            <h2 class="font-headline-xl text-[48px] text-on-surface mb-4" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">Procesando...</h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant">Registrando atleta en la base de datos.</p>
        `;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });
            
            const result = await response.json();
            
            if(response.ok && result.success) {
                step4Content.innerHTML = `
                    <div class="w-32 h-32 bg-[#22c55e]/20 rounded-full flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-[72px] text-[#22c55e] animate-pulse">check_circle</span>
                    </div>
                    <h2 class="font-headline-xl text-[48px] text-on-surface mb-4" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">¡Éxito!</h2>
                    <p class="font-body-lg text-body-lg text-on-surface-variant max-w-md">${result.message}</p>
                `;
                
                // Generar Enlace y Mensaje de WhatsApp para el Comprobante
                const planRadio = document.querySelector('input[name="plan_id"]:checked');
                const planName = planRadio ? (planRadio.closest('label').querySelector('.plan-title')?.textContent?.trim() || 'Membresía') : 'Membresía';
                const planPriceText = planRadio ? (planRadio.closest('label').querySelector('.text-primary')?.textContent?.trim() || '$0.00') : '$0.00';
                const paymentType = formData.get('payment_type') || 'completo';
                const paymentMethod = formData.get('payment_method') === 'efectivo' ? 'Efectivo 💵' : 'Transferencia Bancaria 🏦';
                const abonoAmount = parseFloat(formData.get('abono_amount')) || 0;
                const planPriceNum = parseFloat(planPriceText.replace('$', '').trim()) || 0;
                const isAbono = paymentType === 'abono';
                const paidVal = isAbono ? abonoAmount.toFixed(2) : planPriceNum.toFixed(2);
                const debtVal = isAbono ? Math.max(0, planPriceNum - abonoAmount).toFixed(2) : '0.00';
                const rawPhone = (formData.get('phone') || '').replace(/[^0-9]/g, '');
                const waNumber = rawPhone.startsWith('593') ? rawPhone : ('593' + rawPhone.replace(/^0+/, ''));
                const invoiceUrl = `${window.location.origin}/clientes/${result.client_id}/invoice`;

                let waReceiptText = `🏋️‍♂️ *ARES GYM - COMPROBANTE DE PAGO* 🏋️‍♂️\n`;
                waReceiptText += `----------------------------------------\n`;
                waReceiptText += `👤 *Atleta:* ${formData.get('name')} ${formData.get('last_name')}\n`;
                waReceiptText += `🆔 *Cédula:* ${formData.get('id_card')}\n`;
                waReceiptText += `📋 *Plan:* ${planName} (${planPriceText})\n`;
                waReceiptText += `💳 *Método de Pago:* ${paymentMethod}\n`;
                waReceiptText += `💰 *Monto Pagado:* $${paidVal}\n`;
                if (isAbono) {
                    waReceiptText += `⚠️ *Saldo Pendiente:* $${debtVal}\n`;
                    waReceiptText += `📌 *Tipo:* Abono Parcial Autorizado\n`;
                } else {
                    waReceiptText += `✅ *Estado:* Pago Completo - Al Día\n`;
                }
                waReceiptText += `----------------------------------------\n`;
                waReceiptText += `🧾 *Ver Factura / Recibo Digital:* ${invoiceUrl}\n\n`;
                waReceiptText += `¡Gracias por tu disciplina y preferencia en Ares Gym! A darle con todo. 💪🔥`;

                const waReceiptUrl = `https://wa.me/${waNumber}?text=${encodeURIComponent(waReceiptText)}`;

                step4Actions.innerHTML = `
                    <a href="${waReceiptUrl}" target="_blank" class="bg-[#25D366] text-white px-8 py-4 rounded-xl font-headline-md text-[16px] uppercase tracking-wider hover:brightness-110 transition-all flex items-center gap-3 shadow-[0_4px_15px_rgba(37,211,102,0.4)]">
                        Enviar Comprobante por WhatsApp <span class="material-symbols-outlined">send</span>
                    </a>
                    <a href="/clientes/${result.client_id}/invoice" target="_blank" class="bg-surface-container-high text-on-surface px-8 py-4 rounded-xl font-headline-md text-[16px] uppercase tracking-wider hover:bg-surface-container-highest transition-all flex items-center gap-3 shadow-[0_4px_15px_rgba(0,0,0,0.3)] border border-outline-variant/10">
                        Ver / Imprimir Recibo <span class="material-symbols-outlined">receipt_long</span>
                    </a>
                    <button type="button" class="bg-surface-container text-on-surface px-8 py-4 rounded-xl font-headline-md text-[16px] uppercase tracking-wider hover:bg-surface-container-high transition-all flex items-center gap-3 border border-outline-variant/10" onclick="location.reload()">
                        Nuevo Registro <span class="material-symbols-outlined">refresh</span>
                    </button>
                    <a href="/clientes" class="text-on-surface-variant px-8 py-4 font-headline-md text-[16px] uppercase tracking-wider hover:text-primary transition-colors flex items-center gap-3">
                        Ir al Roster
                    </a>
                `;
                step4Actions.classList.remove('hidden');
                step4Actions.classList.add('flex', 'flex-wrap');
                
                const receiptActions = document.getElementById('receipt-actions');
                if(receiptActions) receiptActions.classList.add('hidden');
            } else {
                throw result;
            }
        } catch (error) {
            let errorMsg = error.message || 'Ocurrió un error inesperado al procesar el registro.';
            let targetStep = 3;
            let targetInput = 'abono_amount_input';

            if(error.errors) {
                const firstKey = Object.keys(error.errors)[0];
                errorMsg = error.errors[firstKey][0];
                if (firstKey === 'abono_amount' || firstKey === 'payment_method' || firstKey === 'voucher_number') {
                    targetStep = 3;
                    targetInput = firstKey === 'abono_amount' ? 'abono_amount_input' : firstKey;
                } else if (firstKey === 'plan_id') {
                    targetStep = 2;
                    targetInput = null;
                } else if (['name', 'last_name', 'id_card', 'phone', 'birth_date'].includes(firstKey)) {
                    targetStep = 1;
                    targetInput = firstKey;
                }
            }
            
            // Restablecer contenido del Paso 4 a la vista normal de verificación
            step4Content.innerHTML = `
                <div class="w-32 h-32 bg-primary/10 rounded-full flex items-center justify-center mb-6" id="step-4-icon-container">
                    <span class="material-symbols-outlined text-[72px] text-primary animate-pulse" id="step-4-icon">visibility</span>
                </div>
                <h2 class="font-headline-xl text-[48px] text-on-surface mb-4" style="font-family: 'Montserrat', sans-serif; font-weight: 800;" id="step-4-title">Verificación</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant" id="step-4-message">Por favor, revisa el recibo a la derecha. Si los datos son correctos, presiona <strong>Confirmar</strong> en el recibo para registrar al atleta.</p>
            `;

            // Redirigir directamente al paso y campo correspondiente con foco y modal
            goToStep(targetStep);
            showErrorModal(errorMsg, targetStep, targetInput);
        }
    }

    function goToStep(step) {
        const formContainer = document.getElementById('client-form');
        const stepsContainer = document.getElementById('form-steps-container');
        const receiptSidebar = document.getElementById('receipt-sidebar');
        const receiptActions = document.getElementById('receipt-actions');
        
        if(step === 4) {
            receiptActions.classList.remove('hidden', 'opacity-0');
            receiptActions.classList.add('flex', 'opacity-100');
            receiptSidebar.classList.remove('hidden');
        } else {
            receiptSidebar.classList.add('md:w-[400px]', 'hidden', 'md:flex');
            receiptSidebar.classList.remove('w-full', 'max-w-xl', 'mx-auto', 'flex'); 
            receiptActions.classList.add('hidden', 'opacity-0');
            receiptActions.classList.remove('flex', 'opacity-100');
        }

        document.querySelectorAll('.form-step').forEach(el => {
            el.classList.add('translate-x-full', 'opacity-0', 'pointer-events-none', 'hidden-step');
            el.classList.remove('translate-x-0', 'opacity-100');
        });
        const current = document.getElementById(`step-${step}`);
        if(current) {
            current.classList.remove('translate-x-full', 'opacity-0', 'pointer-events-none', 'hidden-step');
            current.classList.add('translate-x-0', 'opacity-100');
        }
        
        document.querySelectorAll('.step-btn').forEach(btn => {
            const btnStep = parseInt(btn.getAttribute('data-step'));
            if(btnStep < step) {
                btn.classList.remove('opacity-50');
                btn.querySelector('.step-indicator').className = 'w-12 h-12 rounded-full flex items-center justify-center font-titular-md text-titular-md transition-all duration-300 step-indicator bg-primary text-on-primary shadow-[0_0_15px_rgba(227,27,35,0.3)]';
                btn.querySelector('.step-indicator').innerHTML = '<span class="material-symbols-outlined">check</span>';
                btn.querySelector('.step-label').className = 'font-etiqueta-bold text-etiqueta-sm uppercase tracking-widest transition-colors duration-300 step-label text-primary';
            } else if(btnStep === step) {
                btn.classList.remove('opacity-50');
                btn.querySelector('.step-indicator').className = 'w-12 h-12 rounded-full flex items-center justify-center font-titular-md text-titular-md transition-all duration-300 step-indicator bg-primary-container text-on-primary-container shadow-[0_0_20px_rgba(227,27,35,0.4)]';
                btn.querySelector('.step-indicator').innerHTML = btnStep;
                btn.querySelector('.step-label').className = 'font-etiqueta-bold text-etiqueta-sm uppercase tracking-widest transition-colors duration-300 step-label text-on-surface';
            } else {
                btn.classList.add('opacity-50');
                btn.querySelector('.step-indicator').className = 'w-12 h-12 rounded-full flex items-center justify-center font-titular-md text-titular-md transition-all duration-300 step-indicator bg-surface-container-highest text-on-surface-variant';
                btn.querySelector('.step-indicator').innerHTML = btnStep;
                btn.querySelector('.step-label').className = 'font-etiqueta-bold text-etiqueta-sm uppercase tracking-widest transition-colors duration-300 step-label text-on-surface-variant';
            }
        });
        
        const progress = document.getElementById('stepper-progress');
        if(progress) {
            progress.style.width = ((step - 1) / (totalSteps - 1) * 100) + '%';
        }
        currentStep = step;
    }
</script>
@endpush
