@extends('layouts.admin')

@section('title', 'Nuevo Plan Grupal - Ares Gym')

@push('styles')
<style>
  /* Scoped styles for the form transitions */
  .hidden-step {
    visibility: hidden;
  }
</style>
@endpush

@section('content')
<div class="flex flex-col w-full relative animate-on-load">
    <div class="px-margin-lg py-margin-md max-w-[1400px] mx-auto w-full">
        <div class="flex items-end justify-between mb-margin-lg relative z-10">
            <div>
                <a href="{{ route('clientes.index') }}" class="font-etiqueta-bold text-etiqueta-sm text-on-surface-variant uppercase tracking-[0.2em] mb-4 flex items-center gap-2 hover:text-primary transition-colors w-max">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span> Volver
                </a>
                <h1 class="font-titular-xl text-[48px] text-on-surface uppercase tracking-tight leading-none" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">Plan Grupal</h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant mt-2 max-w-2xl">Creación de grupo, gestión de integrantes y facturación unificada.</p>
            </div>
            <div class="text-right hidden md:block">
                <p class="font-etiqueta-bold text-etiqueta-sm text-primary uppercase tracking-widest">Protocolo Activo</p>
                <p class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant opacity-70 mt-1">SISTEMA-GRP-02</p>
            </div>
        </div>

        <!-- Stepper Navigation -->
        <div class="relative w-full mb-margin-lg">
            <div class="absolute top-1/2 left-0 w-full h-[2px] bg-surface-container-high -translate-y-1/2 z-0 rounded-full"></div>
            <div class="absolute top-1/2 left-0 w-1/4 h-[2px] bg-primary-container -translate-y-1/2 z-0 transition-all duration-500 ease-in-out" id="stepper-progress"></div>
            <div class="relative z-10 flex justify-between items-center w-full">
                <button type="button" class="step-btn group flex flex-col items-center gap-3 cursor-default" data-step="1">
                    <div class="w-12 h-12 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-titular-md text-titular-md shadow-[0_0_20px_rgba(227,27,35,0.4)] transition-all duration-300 step-indicator" style="font-family: 'Montserrat', sans-serif;">1</div>
                    <span class="font-etiqueta-bold text-etiqueta-sm uppercase tracking-widest text-on-surface transition-colors duration-300 step-label">Identidad</span>
                </button>
                <button type="button" class="step-btn group flex flex-col items-center gap-3 cursor-default opacity-50" data-step="2">
                    <div class="w-12 h-12 rounded-full bg-surface-container-highest text-on-surface-variant flex items-center justify-center font-titular-md text-titular-md transition-all duration-300 step-indicator" style="font-family: 'Montserrat', sans-serif;">2</div>
                    <span class="font-etiqueta-bold text-etiqueta-sm uppercase tracking-widest text-on-surface-variant transition-colors duration-300 step-label">Integrantes</span>
                </button>
                <button type="button" class="step-btn group flex flex-col items-center gap-3 cursor-default opacity-50" data-step="3">
                    <div class="w-12 h-12 rounded-full bg-surface-container-highest text-on-surface-variant flex items-center justify-center font-titular-md text-titular-md transition-all duration-300 step-indicator" style="font-family: 'Montserrat', sans-serif;">3</div>
                    <span class="font-etiqueta-bold text-etiqueta-sm uppercase tracking-widest text-on-surface-variant transition-colors duration-300 step-label">Pago</span>
                </button>
                <button type="button" class="step-btn group flex flex-col items-center gap-3 cursor-default opacity-50" data-step="4">
                    <div class="w-12 h-12 rounded-full bg-surface-container-highest text-on-surface-variant flex items-center justify-center font-titular-md text-titular-md transition-all duration-300 step-indicator" style="font-family: 'Montserrat', sans-serif;">4</div>
                    <span class="font-etiqueta-bold text-etiqueta-sm uppercase tracking-widest text-on-surface-variant transition-colors duration-300 step-label">Confirmación</span>
                </button>
            </div>
        </div>

        <!-- Forms Container -->
        <form id="group-form" method="POST" action="{{ route('clientes.storeGroup') }}" class="relative w-full overflow-x-hidden bg-surface-container-low rounded-[24px] shadow-2xl min-h-[600px] border border-outline-variant/10">
            @csrf
            <!-- Ambient Background Graphic -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-primary-container/5 rounded-full blur-[100px] pointer-events-none -mr-48 -mt-48 mix-blend-screen"></div>
            
            <div class="form-step relative p-margin-md md:p-margin-lg transition-all duration-500 transform translate-x-0 opacity-100 flex flex-col" id="step-1">
                <div class="flex items-center gap-2 text-primary tracking-widest font-etiqueta-sm uppercase mb-2">
                    <span class="w-8 h-[1px] bg-primary"></span>
                    <span>Paso 01</span>
                </div>
                <h2 class="font-titular-xl text-[32px] text-on-surface mb-8" style="font-family: 'Montserrat', sans-serif; font-weight: 700;">Identidad del Grupo</h2>
                
                <div class="max-w-3xl flex flex-col gap-8">
                    <div class="bg-surface-container p-6 rounded-xl border border-outline-variant/20 flex flex-col gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant uppercase tracking-widest">Nombre del Grupo</label>
                                <div class="relative group">
                                    <input id="group-name-input" name="group_name" class="w-full bg-surface-container-lowest text-on-surface font-body-md p-4 rounded-xl focus:outline-none transition-all duration-300 shadow-inner group-focus-within:bg-surface-container-highest border border-transparent focus:border-primary/50" placeholder="Ej. Los Espartanos" type="text" oninput="updateGroupName()"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant uppercase tracking-widest">Descripción / Objetivo</label>
                                <div class="relative group">
                                    <input class="w-full bg-surface-container-lowest text-on-surface font-body-md p-4 rounded-xl focus:outline-none transition-all duration-300 shadow-inner group-focus-within:bg-surface-container-highest border border-transparent focus:border-primary/50" placeholder="Ej. Entrenamiento de alto rendimiento" type="text"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-surface-container-highest p-6 rounded-xl flex items-start gap-4 border-l-4 border-primary shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <span class="material-symbols-outlined text-primary mt-1">info</span>
                        <div class="relative z-10 flex flex-col gap-1">
                            <span class="font-titular-md text-titular-md text-on-surface">Regla de Grupo</span>
                            <span class="font-body-md text-body-md text-on-surface-variant">
                                La fecha de inicio y fin de la membresía es idéntica y sincronizada para todos los integrantes del grupo. Se requiere un mínimo de 2 integrantes.
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-auto pt-8 flex justify-between">
                    <a href="{{ route('clientes.index') }}" class="text-on-surface-variant px-6 py-4 font-etiqueta-bold text-[16px] uppercase tracking-wider hover:text-on-surface transition-all flex items-center gap-2 border border-outline-variant/20 rounded-xl hover:bg-surface-container-high">
                        <span class="material-symbols-outlined">close</span> Cancelar
                    </a>
                    <button type="button" class="bg-primary-container text-on-primary-container px-8 py-4 rounded-xl font-titular-md text-[16px] uppercase tracking-wider hover:brightness-110 transition-all flex items-center gap-3 shadow-[0_4px_15px_rgba(227,27,35,0.3)] hover:shadow-[0_4px_25px_rgba(227,27,35,0.5)]" onclick="nextStep(1)">
                        Siguiente <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </div>
            </div>

            <div class="form-step p-margin-md md:p-margin-lg transition-all duration-500 transform translate-x-full opacity-0 flex flex-col hidden-step pointer-events-none" id="step-2">
                <div class="flex items-center gap-2 text-primary tracking-widest font-etiqueta-sm uppercase mb-6">
                    <span class="w-8 h-[1px] bg-primary"></span>
                    <span>Paso 02</span>
                </div>
                
                <div class="flex flex-col lg:flex-row gap-margin-lg h-full pb-8">
                    <!-- Left Column: Member List -->
                    <div class="flex-1 flex flex-col gap-margin-md">
                        <div class="bg-surface-container p-6 rounded-xl shadow-md flex flex-col gap-6 relative border border-outline-variant/10">
                            <!-- Subtle background texture -->
                            <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none">
                                <svg fill="currentColor" height="120" viewbox="0 0 24 24" width="120" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2L2 7L12 12L22 7L12 2Z"></path>
                                    <path d="M2 17L12 22L22 17" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                    <path d="M2 12L12 17L22 12" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                            </div>
                            
                            <div class="flex items-center justify-between z-10">
                                <h2 class="font-titular-xl text-[28px] text-on-surface" style="font-family: 'Montserrat', sans-serif; font-weight: 700;">Integrantes</h2>
                                <span class="font-etiqueta-bold text-etiqueta-sm bg-surface-container-high text-on-surface-variant px-3 py-1 rounded-full uppercase tracking-wider" id="member-count-badge">2 Members</span>
                            </div>
                            
                            <div class="flex flex-col gap-4 z-10" id="member-list">
                                <!-- Member Item 1 -->

                                <div class="flex flex-col xl:flex-row gap-4 p-4 bg-surface rounded-lg items-start relative group transition-all hover:shadow-sm border border-outline-variant/5" id="row-0">
                                    <div class="absolute inset-y-0 left-0 w-1 bg-surface-variant rounded-l-lg group-hover:bg-primary transition-colors"></div>
                                    <div class="w-10 h-10 bg-surface-container-high rounded-full flex items-center justify-center font-etiqueta-bold text-etiqueta-sm text-on-surface-variant group-hover:text-primary transition-colors mt-2">01</div>
                                    <div class="flex-1 w-full flex flex-col">
                                        
        <div class="flex justify-between items-center w-full mb-4">
            <span class="font-etiqueta-sm text-on-surface-variant uppercase">Datos del Integrante</span>
            <div class="flex bg-surface-container-high rounded-lg p-1">
                <button type="button" class="px-3 py-1 text-[12px] font-etiqueta-bold rounded bg-primary text-on-primary mode-btn" onclick="toggleClientMode(this, '0', 'new')">Nuevo</button>
                <button type="button" class="px-3 py-1 text-[12px] font-etiqueta-bold rounded text-on-surface-variant hover:text-on-surface mode-btn" onclick="toggleClientMode(this, '0', 'existing')">Existente</button>
            </div>
        </div>
    
                                        
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 w-full new-client-form">
            <div class="flex flex-col gap-1">
                <label class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant uppercase">Nombre</label>
                <input name="members[0][name]" class="bg-surface-container-lowest text-on-surface font-body-md p-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary w-full border border-outline-variant/10" type="text" placeholder="Nombre"/>
            </div>
            <div class="flex flex-col gap-1">
                <label class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant uppercase">Apellido</label>
                <input name="members[0][last_name]" class="bg-surface-container-lowest text-on-surface font-body-md p-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary w-full border border-outline-variant/10" type="text" placeholder="Apellido"/>
            </div>
            <div class="flex flex-col gap-1">
                <label class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant uppercase">ID / Cédula</label>
                <input name="members[0][id_card]" class="bg-surface-container-lowest text-on-surface font-body-md p-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary w-full border border-outline-variant/10" type="text" placeholder="Cédula"/>
            </div>
            <div class="flex flex-col gap-1">
                <label class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant uppercase">Teléfono</label>
                <input name="members[0][phone]" class="bg-surface-container-lowest text-on-surface font-body-md p-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary w-full border border-outline-variant/10" type="text" placeholder="Teléfono"/>
            </div>
        </div>
    
                                        
        <div class="w-full hidden existing-client-form relative">
            <input type="hidden" name="members[0][existing_client_id]" class="existing-client-id">
            <div class="relative w-full search-container">
                <input type="text" class="w-full bg-surface-container-lowest text-on-surface font-body-md p-3 pl-10 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary border border-outline-variant/10 client-search-input" placeholder="Buscar por nombre o cédula..." oninput="searchClients(this, '0')">
                <span class="material-symbols-outlined absolute left-3 top-3 text-on-surface-variant">search</span>
                <div class="absolute top-full left-0 w-full bg-surface-container mt-1 rounded-lg shadow-xl border border-outline-variant/10 z-50 hidden search-results max-h-[200px] overflow-y-auto"></div>
            </div>
            <div class="mt-4 p-4 rounded-xl bg-surface-container-highest border border-outline-variant/10 hidden selected-client-card relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full blur-xl -mr-8 -mt-8"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div class="flex flex-col">
                        <span class="font-titular-md text-[18px] text-on-surface selected-client-name">Juan Perez</span>
                        <span class="font-body-sm text-on-surface-variant flex items-center gap-2">
                            <span class="material-symbols-outlined text-[14px]">badge</span> <span class="selected-client-ci">1234567890</span>
                        </span>
                    </div>
                    <button type="button" class="text-error hover:bg-error-container p-2 rounded-lg transition-colors remove-selected-btn" onclick="clearSelectedClient('0')">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>
        </div>
    
                                    </div>
                                    <div class="mt-2"><div class="w-10 h-10 flex items-center justify-center text-on-surface-variant opacity-50 cursor-not-allowed" title="Titular no puede ser removido"><span class="material-symbols-outlined">lock</span></div></div>
                                </div>
    
                                
                                <!-- Member Item 2 -->

                                <div class="flex flex-col xl:flex-row gap-4 p-4 bg-surface rounded-lg items-start relative group transition-all hover:shadow-sm border border-outline-variant/5 member-row" id="row-1">
                                    <div class="absolute inset-y-0 left-0 w-1 bg-surface-variant rounded-l-lg group-hover:bg-primary transition-colors"></div>
                                    <div class="w-10 h-10 bg-surface-container-high rounded-full flex items-center justify-center font-etiqueta-bold text-etiqueta-sm text-on-surface-variant group-hover:text-primary transition-colors mt-2">02</div>
                                    <div class="flex-1 w-full flex flex-col">
                                        
        <div class="flex justify-between items-center w-full mb-4">
            <span class="font-etiqueta-sm text-on-surface-variant uppercase">Datos del Integrante</span>
            <div class="flex bg-surface-container-high rounded-lg p-1">
                <button type="button" class="px-3 py-1 text-[12px] font-etiqueta-bold rounded bg-primary text-on-primary mode-btn" onclick="toggleClientMode(this, '1', 'new')">Nuevo</button>
                <button type="button" class="px-3 py-1 text-[12px] font-etiqueta-bold rounded text-on-surface-variant hover:text-on-surface mode-btn" onclick="toggleClientMode(this, '1', 'existing')">Existente</button>
            </div>
        </div>
    
                                        
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 w-full new-client-form">
            <div class="flex flex-col gap-1">
                <label class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant uppercase">Nombre</label>
                <input name="members[1][name]" class="bg-surface-container-lowest text-on-surface font-body-md p-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary w-full border border-outline-variant/10" type="text" placeholder="Nombre"/>
            </div>
            <div class="flex flex-col gap-1">
                <label class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant uppercase">Apellido</label>
                <input name="members[1][last_name]" class="bg-surface-container-lowest text-on-surface font-body-md p-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary w-full border border-outline-variant/10" type="text" placeholder="Apellido"/>
            </div>
            <div class="flex flex-col gap-1">
                <label class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant uppercase">ID / Cédula</label>
                <input name="members[1][id_card]" class="bg-surface-container-lowest text-on-surface font-body-md p-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary w-full border border-outline-variant/10" type="text" placeholder="Cédula"/>
            </div>
            <div class="flex flex-col gap-1">
                <label class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant uppercase">Teléfono</label>
                <input name="members[1][phone]" class="bg-surface-container-lowest text-on-surface font-body-md p-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary w-full border border-outline-variant/10" type="text" placeholder="Teléfono"/>
            </div>
        </div>
    
                                        
        <div class="w-full hidden existing-client-form relative">
            <input type="hidden" name="members[1][existing_client_id]" class="existing-client-id">
            <div class="relative w-full search-container">
                <input type="text" class="w-full bg-surface-container-lowest text-on-surface font-body-md p-3 pl-10 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary border border-outline-variant/10 client-search-input" placeholder="Buscar por nombre o cédula..." oninput="searchClients(this, '1')">
                <span class="material-symbols-outlined absolute left-3 top-3 text-on-surface-variant">search</span>
                <div class="absolute top-full left-0 w-full bg-surface-container mt-1 rounded-lg shadow-xl border border-outline-variant/10 z-50 hidden search-results max-h-[200px] overflow-y-auto"></div>
            </div>
            <div class="mt-4 p-4 rounded-xl bg-surface-container-highest border border-outline-variant/10 hidden selected-client-card relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full blur-xl -mr-8 -mt-8"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div class="flex flex-col">
                        <span class="font-titular-md text-[18px] text-on-surface selected-client-name">Juan Perez</span>
                        <span class="font-body-sm text-on-surface-variant flex items-center gap-2">
                            <span class="material-symbols-outlined text-[14px]">badge</span> <span class="selected-client-ci">1234567890</span>
                        </span>
                    </div>
                    <button type="button" class="text-error hover:bg-error-container p-2 rounded-lg transition-colors remove-selected-btn" onclick="clearSelectedClient('1')">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>
        </div>
    
                                    </div>
                                    <div class="mt-2"><button type="button" class="w-10 h-10 flex items-center justify-center text-on-surface-variant hover:text-error hover:bg-error-container rounded-full transition-colors remove-btn"><span class="material-symbols-outlined">delete</span></button></div>
                                </div>
    
                            </div>
                            
                            <div class="flex flex-col sm:flex-row gap-4 mt-4 z-10">
                                <button type="button" class="flex items-center justify-center gap-2 bg-surface-container-high hover:bg-surface-bright text-on-surface py-4 px-6 rounded-lg font-etiqueta-bold transition-all group border border-outline-variant/20 flex-1" id="add-member-btn">
                                    <span class="material-symbols-outlined group-hover:scale-110 transition-transform">add</span>
                                    <span>Añadir Integrante</span>
                                </button>
                                <button type="button" class="flex items-center justify-center gap-2 bg-primary-container/20 text-primary hover:bg-primary-container/40 py-4 px-6 rounded-lg font-etiqueta-bold transition-all group border border-primary/20 flex-1" onclick="fillRandomData()">
                                    <span class="material-symbols-outlined group-hover:scale-110 transition-transform">magic_button</span>
                                    <span>Rellenar Datos (Test)</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column: Summary & Pricing -->
                    <div class="w-full lg:w-[450px] flex flex-col gap-margin-md">
                        <!-- Pricing Logic Visualizer -->
                        <div class="bg-surface-container p-6 rounded-xl flex flex-col gap-4 shadow-sm relative overflow-hidden border border-outline-variant/10">
                            <h3 class="font-titular-md text-[24px] text-on-surface" style="font-family: 'Montserrat', sans-serif;">Escala de Precios</h3>
                            <div class="flex flex-col gap-2 relative z-10">
                                <div class="flex justify-between items-center p-3 rounded-lg bg-surface-container-highest transition-colors" id="tier-1">
                                    <span class="font-body-md text-on-surface">2-3 Personas</span>
                                    <span class="font-etiqueta-bold text-primary">$25.00 <span class="text-on-surface-variant font-etiqueta-sm">/c.u.</span></span>
                                </div>
                                <div class="flex justify-between items-center p-3 rounded-lg transition-colors" id="tier-2">
                                    <span class="font-body-md text-on-surface-variant">4+ Personas</span>
                                    <span class="font-etiqueta-bold text-on-surface-variant">$20.00 <span class="text-on-surface-variant font-etiqueta-sm">/c.u.</span></span>
                                </div>
                            </div>
                            <!-- Decorative subtle grid -->
                            <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(#e2e2e2 1px, transparent 1px); background-size: 20px 20px;"></div>
                        </div>
                        
                        <!-- Summary Card -->
                        <div class="bg-surface-container-low p-8 rounded-xl shadow-xl flex flex-col gap-6 relative overflow-hidden border border-outline-variant/10">
                            <!-- Geometric Accent -->
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/10 rounded-full blur-2xl pointer-events-none"></div>
                            <h3 class="font-titular-md text-[24px] text-on-surface uppercase tracking-wider" style="font-family: 'Montserrat', sans-serif;">Resumen</h3>
                            <div class="flex flex-col gap-4">
                                <div class="flex justify-between items-end pb-4 border-b border-surface-container-highest">
                                    <span class="font-body-md text-on-surface-variant">Grupo</span>
                                    <span class="font-etiqueta-bold text-on-surface uppercase" id="summary-group-name">Sin Nombre</span>
                                </div>
                                <div class="flex justify-between items-end pb-4 border-b border-surface-container-highest">
                                    <span class="font-body-md text-on-surface-variant">Total Integrantes</span>
                                    <span class="font-etiqueta-bold text-on-surface" id="summary-count">02</span>
                                </div>
                                <div class="flex justify-between items-end pb-4 border-b border-surface-container-highest">
                                    <span class="font-body-md text-on-surface-variant">Precio por Persona</span>
                                    <span class="font-etiqueta-bold text-on-surface" id="summary-price-per">$25.00</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pt-4 mt-2">
                                <span class="font-titular-md text-[24px] text-on-surface">Total</span>
                                <span class="font-headline-xl text-[48px] text-primary" id="summary-total" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">$50.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-auto pt-4 flex justify-between items-center">
                    <div class="flex gap-4">
                        <button type="button" class="text-on-surface-variant px-6 py-4 font-etiqueta-bold text-[16px] uppercase tracking-wider hover:text-on-surface transition-all flex items-center gap-2 border border-outline-variant/20 rounded-xl hover:bg-surface-container-high" onclick="prevStep(2)">
                            <span class="material-symbols-outlined">arrow_back</span> Atrás
                        </button>
                        <a href="{{ route('clientes.index') }}" class="text-error px-6 py-4 font-etiqueta-bold text-[16px] uppercase tracking-wider hover:bg-error-container hover:text-error transition-all flex items-center gap-2 border border-error/20 rounded-xl">
                            <span class="material-symbols-outlined">delete</span> Eliminar Grupo
                        </a>
                    </div>
                    <button type="button" class="bg-primary-container text-on-primary-container px-12 py-4 rounded-xl font-titular-md text-[18px] uppercase tracking-wider hover:brightness-110 transition-all shadow-[0_4px_15px_rgba(227,27,35,0.3)] hover:shadow-[0_4px_25px_rgba(227,27,35,0.5)] flex items-center gap-2" onclick="nextStep(2)">
                        Pagar <span class="material-symbols-outlined">payment</span>
                    </button>
                </div>
            </div>

            <!-- Step 3: Pago y Abono Grupal -->
            <div class="form-step p-margin-md md:p-margin-lg transition-all duration-500 transform translate-x-full opacity-0 flex flex-col hidden-step pointer-events-none" id="step-3">
                <div class="flex items-center gap-2 text-primary tracking-widest font-etiqueta-sm uppercase mb-2">
                    <span class="w-8 h-[1px] bg-primary"></span>
                    <span>Paso 03</span>
                </div>
                <h2 class="font-titular-xl text-[32px] text-on-surface mb-6" style="font-family: 'Montserrat', sans-serif; font-weight: 700;">Información de Pago</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 max-w-4xl">
                    
                    <!-- Tipo de Pago: Completo vs Abono -->
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest flex items-center justify-between">
                            <span>Modalidad de Pago</span>
                            <span id="group-abono-auth-badge" class="hidden font-etiqueta-bold text-[10px] text-green-500 uppercase tracking-wider bg-green-500/10 px-2.5 py-0.5 rounded-full border border-green-500/20 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[12px]">verified</span> Autorizado por Admin
                            </span>
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer group">
                                <input checked="" class="peer sr-only" name="payment_type" onchange="handleGroupPaymentTypeChange(this)" type="radio" value="completo"/>
                                <div class="p-4 bg-surface-container rounded-xl border-2 border-transparent peer-checked:border-primary-container peer-checked:bg-surface-container-high transition-all text-center flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">paid</span>
                                    <span class="font-titular-md text-on-surface">Pago Completo</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input class="peer sr-only" name="payment_type" id="group-payment-type-abono" onchange="handleGroupPaymentTypeChange(this)" type="radio" value="abono"/>
                                <div class="p-4 bg-surface-container rounded-xl border-2 border-transparent peer-checked:border-primary-container peer-checked:bg-surface-container-high transition-all text-center flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">receipt</span>
                                    <span class="font-titular-md text-on-surface">Abono (Parcial)</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Bloque de Abono Grupal -->
                    <div id="group-abono-container" class="hidden md:col-span-2 bg-surface-container-low border border-outline-variant/15 p-5 rounded-2xl flex flex-col gap-4 transition-all">
                        <div id="group-abono-unauthorized-box" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-primary/10 border border-primary/20 rounded-xl">
                            <div class="flex items-center gap-3 text-primary">
                                <span class="material-symbols-outlined text-[24px]">lock</span>
                                <div>
                                    <p class="font-etiqueta-bold text-[12px] uppercase tracking-wider text-on-surface">Autorización Requerida para Abono</p>
                                    <p class="font-body-md text-xs text-on-surface-variant">El registro de abonos grupales (25% al 75%) requiere autorización del administrador.</p>
                                </div>
                            </div>
                            <button type="button" onclick="openGroupAbonoModal()" class="bg-primary text-on-primary hover:bg-[#ff2a35] px-5 py-2.5 rounded-xl font-titular-md text-[12px] uppercase tracking-wider transition-all flex items-center gap-2 shrink-0 shadow-sm justify-center">
                                <span class="material-symbols-outlined text-[16px]">key</span> Autorizar Abono
                            </button>
                        </div>

                        <div id="group-abono-fields-box" class="hidden flex flex-col gap-5">
                            
                            <!-- Header con Reglas Claras -->
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 bg-surface-container/60 p-3.5 rounded-xl border border-outline-variant/10">
                                <div class="flex items-center gap-2 text-on-surface text-xs font-etiqueta-bold uppercase tracking-wider">
                                    <span class="material-symbols-outlined text-primary text-[18px]">info</span>
                                    <span>Rango de Abono Permitido: <span class="text-primary font-bold">25% a 75%</span></span>
                                </div>
                                <div class="flex items-center gap-2 sm:gap-3 text-[11px] font-mono text-on-surface-variant">
                                    <span class="bg-surface-container-highest px-2.5 py-1 rounded-md">Mín (25%): $<span id="group-abono-min-display">0.00</span></span>
                                    <span class="bg-surface-container-highest px-2.5 py-1 rounded-md">Máx (75%): $<span id="group-abono-max-display">0.00</span></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                                <!-- Columna Izquierda: Input y Atajos -->
                                <div class="flex flex-col gap-3">
                                    <div class="flex flex-col gap-1.5">
                                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest flex justify-between items-center">
                                            <span>Monto a Abonar Hoy ($)</span>
                                            <span id="group-abono-percentage-badge" class="text-[11px] font-mono text-primary font-bold">0%</span>
                                        </label>
                                        <div class="relative group">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant font-bold text-lg pointer-events-none">$</span>
                                            <input type="number" step="0.01" min="0.01" id="group_abono_amount_input" name="abono_amount" placeholder="0.00" 
                                                   class="w-full bg-surface-container-lowest text-on-surface font-titular-md text-[20px] font-bold pl-9 pr-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary border border-outline-variant/10 shadow-inner transition-all" 
                                                   onkeydown="filterGroupAbonoKeys(event)" 
                                                   oninput="calculateGroupAbonoBalance()" 
                                                   onblur="clampGroupAbonoOnBlur()" 
                                                   onchange="clampGroupAbonoOnBlur()" 
                                                   onpaste="handleGroupAbonoPaste(event)">
                                        </div>
                                    </div>

                                    <!-- Botones de Atajo Rápido Interactivos (Presets 25%, 50%, 75%) -->
                                    <div class="flex flex-col gap-1.5">
                                        <span class="text-[10px] uppercase tracking-wider text-on-surface-variant/80 font-etiqueta-bold">Selección Rápida Grupal:</span>
                                        <div class="grid grid-cols-3 gap-2">
                                            <button type="button" onclick="setGroupAbonoPercentage(25)" class="bg-surface-container hover:bg-surface-container-highest border border-outline-variant/15 text-on-surface py-2 px-1 rounded-lg text-center transition-all group flex flex-col items-center">
                                                <span class="text-[10px] font-bold text-on-surface-variant group-hover:text-primary">25% (Mín)</span>
                                                <span class="text-[12px] font-mono font-bold text-on-surface">$<span id="group-btn-val-25">0.00</span></span>
                                            </button>
                                            <button type="button" onclick="setGroupAbonoPercentage(50)" class="bg-surface-container hover:bg-surface-container-highest border border-outline-variant/15 text-on-surface py-2 px-1 rounded-lg text-center transition-all group flex flex-col items-center">
                                                <span class="text-[10px] font-bold text-on-surface-variant group-hover:text-primary">50% (Medio)</span>
                                                <span class="text-[12px] font-mono font-bold text-on-surface">$<span id="group-btn-val-50">0.00</span></span>
                                            </button>
                                            <button type="button" onclick="setGroupAbonoPercentage(75)" class="bg-surface-container hover:bg-surface-container-highest border border-outline-variant/15 text-on-surface py-2 px-1 rounded-lg text-center transition-all group flex flex-col items-center">
                                                <span class="text-[10px] font-bold text-on-surface-variant group-hover:text-primary">75% (Máx)</span>
                                                <span class="text-[12px] font-mono font-bold text-on-surface">$<span id="group-btn-val-75">0.00</span></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna Derecha: Desglose Visual y Barra de Progreso -->
                                <div class="flex flex-col gap-3">
                                    <div class="bg-surface-container p-4 rounded-xl border border-outline-variant/10 flex flex-col gap-2 shadow-sm">
                                        <div class="flex justify-between items-center text-xs text-on-surface-variant font-body-md">
                                            <span>Total del Plan Grupal:</span>
                                            <span class="font-bold text-on-surface text-[14px]">$<span id="group-abono-total-display">0.00</span></span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs text-on-surface-variant font-body-md">
                                            <span>Monto Abonado:</span>
                                            <span class="font-bold text-green-500 text-[14px]">$<span id="group-abono-paid-display">0.00</span></span>
                                        </div>
                                        <div class="pt-2 border-t border-outline-variant/10 flex justify-between items-center">
                                            <span class="font-etiqueta-bold text-[11px] uppercase tracking-wider text-error">Saldo Adeudado:</span>
                                            <span class="font-titular-md text-[18px] text-error font-bold">$<span id="group-abono-debt-display">0.00</span></span>
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
                                            <div id="group-abono-progress-bar" class="h-full bg-primary transition-all duration-300 rounded-full" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mensajes Dinámicos de Interacción y Feedback en Vivo -->
                            <div id="group-abono-interaction-msg" class="p-3.5 rounded-xl border text-xs font-body-md flex items-center gap-2.5 transition-all bg-surface-container-high border-outline-variant/20 text-on-surface-variant">
                                <span id="group-abono-msg-icon" class="material-symbols-outlined text-[18px] text-primary shrink-0">info</span>
                                <span id="group-abono-msg-text">Ingrese un monto entre el 25% y 75% del valor total del plan grupal.</span>
                            </div>

                        </div>
                    </div>

                    <!-- Método de Pago -->
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest">Método de Pago</label>
                        <div class="flex gap-4">
                            <label class="flex-1 relative cursor-pointer group">
                                <input checked="" class="peer sr-only" name="payment_method" onchange="toggleReceiptField(this)" type="radio" value="efectivo"/>
                                <div class="p-4 bg-surface-container rounded-xl border-2 border-transparent peer-checked:border-primary-container peer-checked:bg-surface-container-high transition-all text-center">
                                    <span class="font-titular-md text-on-surface">Efectivo</span>
                                </div>
                            </label>
                            <label class="flex-1 relative cursor-pointer group">
                                <input class="peer sr-only" name="payment_method" onchange="toggleReceiptField(this)" type="radio" value="transferencia"/>
                                <div class="p-4 bg-surface-container rounded-xl border-2 border-transparent peer-checked:border-primary-container peer-checked:bg-surface-container-high transition-all text-center">
                                    <span class="font-titular-md text-on-surface">Transferencia</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-2 md:col-span-2 hidden transition-all duration-300" id="receipt-field">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest">Número de Comprobante / Referencia</label>
                        <div class="relative group">
                            <input name="voucher_number" class="w-full bg-surface-container-lowest text-on-surface font-body-md p-4 rounded-xl focus:outline-none transition-all duration-300 shadow-inner group-focus-within:bg-surface-container-highest border border-transparent focus:border-primary/50" placeholder="Ej. 123456789" type="text"/>
                        </div>
                    </div>
                    
                    <!-- Aviso de Titularidad del Recibo Grupal -->
                    <div class="md:col-span-2 bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/10 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[22px]">groups</span>
                        <p class="font-body-md text-xs text-on-surface-variant">
                            El recibo de pago se generará a nombre del grupo y su integrante principal. No se requieren datos adicionales de facturación.
                        </p>
                    </div>
                </div>
                
                <div class="mt-auto pt-8 flex justify-between items-center">
                    <button type="button" class="text-on-surface-variant px-6 py-4 font-etiqueta-bold text-[16px] uppercase tracking-wider hover:text-on-surface transition-all flex items-center gap-2 border border-outline-variant/20 rounded-xl hover:bg-surface-container-high" onclick="prevStep(3)">
                        <span class="material-symbols-outlined">arrow_back</span> Atrás
                    </button>
                    <button type="button" class="bg-primary-container text-on-primary-container px-8 py-4 rounded-xl font-titular-md text-[16px] uppercase tracking-wider hover:brightness-110 transition-all flex items-center gap-3 shadow-[0_4px_15px_rgba(227,27,35,0.3)] hover:shadow-[0_4px_25px_rgba(227,27,35,0.5)]" onclick="nextStep(3)">
                        Confirmar Pago <span class="material-symbols-outlined">done</span>
                    </button>
                </div>
            </div>

            <div class="form-step absolute inset-0 p-margin-md md:p-margin-lg transition-all duration-500 transform translate-x-full opacity-0 flex flex-col hidden-step pointer-events-none items-center justify-center" id="step-4">
                <div class="flex flex-col items-center justify-center text-center">
                    <div class="w-32 h-32 bg-primary-container/20 rounded-full flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-[72px] text-primary-container animate-pulse">check_circle</span>
                    </div>
                    <h2 class="font-titular-xl text-[48px] text-on-surface mb-4" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">¡Grupo Creado!</h2>
                    <p class="font-body-lg text-body-lg text-on-surface-variant max-w-md">El plan grupal ha sido registrado con éxito y los integrantes ya pueden empezar su protocolo.</p>
                </div>
                <div class="mt-12 flex flex-wrap justify-center w-full gap-4">
                    <a id="group-wa-btn" href="#" target="_blank" class="hidden bg-[#25D366] text-white px-8 py-4 rounded-xl font-titular-md text-[16px] uppercase tracking-wider hover:brightness-110 transition-all flex items-center gap-3 shadow-[0_4px_15px_rgba(37,211,102,0.4)]">
                        Enviar Comprobante por WhatsApp <span class="material-symbols-outlined">send</span>
                    </a>
                    <a id="invoice-btn" href="#" target="_blank" class="hidden bg-surface-container-high text-on-surface px-8 py-4 rounded-xl font-titular-md text-[16px] uppercase tracking-wider hover:bg-surface-container-highest transition-all flex items-center gap-3 shadow-[0_4px_15px_rgba(0,0,0,0.3)] border border-outline-variant/10">
                        Ver / Imprimir Recibo <span class="material-symbols-outlined">receipt_long</span>
                    </a>
                    <button type="button" class="bg-surface-container text-on-surface px-8 py-4 rounded-xl font-titular-md text-[16px] uppercase tracking-wider hover:bg-surface-container-high transition-all flex items-center gap-3 border border-outline-variant/10" onclick="location.reload()">
                        Nuevo Registro <span class="material-symbols-outlined">refresh</span>
                    </button>
                    <a href="{{ route('clientes.index') }}" class="text-on-surface-variant px-8 py-4 font-titular-md text-[16px] uppercase tracking-wider hover:text-primary transition-colors flex items-center gap-3">
                        Ir al Roster
                    </a>
                </div>
            </div>
            
        </form>
    </div>
</div>

<!-- Modal de Autorización de Abono Grupal -->
<div id="group-abono-auth-modal" class="fixed inset-0 z-[110] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="absolute inset-0 bg-black/75 backdrop-blur-sm" onclick="closeGroupAbonoModal()"></div>
    <div class="bg-[#1e1e24] p-8 rounded-[24px] shadow-2xl relative z-10 max-w-md w-full transform scale-95 transition-transform duration-300 border border-outline-variant/20 flex flex-col gap-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 border border-primary/20">
                <span class="material-symbols-outlined text-[28px]">lock_person</span>
            </div>
            <div>
                <h3 class="font-titular-md text-[20px] text-on-surface">Autorización de Abono Grupal</h3>
                <p class="font-body-md text-xs text-on-surface-variant mt-0.5">Autorización requerida para pago parcial</p>
            </div>
        </div>

        <p class="font-body-md text-xs text-on-surface-variant leading-relaxed">
            Ingrese la clave del <strong>Administrador o Supervisor</strong> para autorizar el registro grupal con saldo pendiente.
        </p>

        <div class="flex flex-col gap-2">
            <label class="font-etiqueta-bold text-[11px] uppercase tracking-widest text-on-surface-variant">Clave de Administrador</label>
            <input type="password" id="group-abono-admin-password" placeholder="••••••••" class="w-full bg-surface-container-lowest text-on-surface font-body-md p-4 rounded-xl border border-outline-variant/20 focus:border-primary focus:outline-none transition-colors" onkeydown="if(event.key==='Enter') verifyGroupAbonoPassword()">
        </div>

        <div id="group-abono-error-msg" class="hidden p-3.5 bg-error/10 border border-error/20 rounded-xl text-error text-xs font-body-md flex items-start gap-2">
            <span class="material-symbols-outlined text-[18px] shrink-0 mt-0.5">error</span>
            <span id="group-abono-error-text">Clave incorrecta.</span>
        </div>

        <div id="group-abono-success-msg" class="hidden p-3.5 bg-green-500/10 border border-green-500/20 rounded-xl text-green-500 text-xs font-body-md flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            <span>¡Autorización concedida con éxito!</span>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="button" onclick="cancelGroupAbonoModal()" class="flex-1 bg-surface-container-high hover:bg-surface-container-highest text-on-surface py-3.5 rounded-xl font-titular-md text-[13px] uppercase tracking-wider transition-colors border border-outline-variant/15">
                Cancelar
            </button>
            <button type="button" id="btn-verify-group-abono" onclick="verifyGroupAbonoPassword()" class="flex-1 bg-primary hover:bg-[#ff2a35] text-on-primary py-3.5 rounded-xl font-titular-md text-[13px] uppercase tracking-wider transition-all flex items-center justify-center gap-2 shadow-[0_0_15px_rgba(227,27,35,0.3)]">
                <span class="material-symbols-outlined text-[16px]">verified</span> Validar
            </button>
        </div>
    </div>
</div>

<!-- Error Modal con Redirección Inteligente -->
<div id="error-modal" class="fixed inset-0 z-[100] flex items-center justify-center pointer-events-none opacity-0 transition-all duration-300">
    <div class="absolute inset-0 bg-black/75 backdrop-blur-sm" onclick="closeErrorModal()"></div>
    <div class="bg-surface-container-high p-8 rounded-2xl shadow-2xl relative z-10 w-[90%] max-w-md border border-outline-variant/20 flex flex-col gap-4 transform scale-95 transition-transform duration-300" id="error-modal-content">
        <div class="flex items-center gap-4 text-error">
            <span class="material-symbols-outlined text-[32px]">warning</span>
            <h3 class="font-titular-md text-[24px]">Atención</h3>
        </div>
        <div class="max-h-[300px] overflow-y-auto">
            <p id="error-modal-text" class="text-on-surface-variant font-body-md whitespace-pre-line leading-relaxed"></p>
        </div>
        <button type="button" id="btn-close-group-error-modal" class="mt-4 bg-primary text-on-primary px-6 py-3.5 rounded-xl font-etiqueta-bold hover:brightness-110 transition-all w-full flex items-center justify-center gap-2 shadow-[0_4px_15px_rgba(227,27,35,0.3)]" onclick="closeErrorModal()">
            Entendido
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
  let currentStep = 1;
  const totalSteps = 4;

  function updateGroupName() {
      const input = document.getElementById('group-name-input').value;
      const display = document.getElementById('summary-group-name');
      display.textContent = input.trim() === '' ? 'Sin Nombre' : input;
  }

  function updateStepperUI(step) {
    const progress = document.getElementById('stepper-progress');
    const widthPercentage = ((step - 1) / (totalSteps - 1)) * 100;
    progress.style.width = widthPercentage === 0 ? '0%' : widthPercentage === 100 ? '100%' : `${widthPercentage}%`;

    const buttons = document.querySelectorAll('.step-btn');
    buttons.forEach((btn, index) => {
      const btnStep = index + 1;
      const indicator = btn.querySelector('.step-indicator');
      const label = btn.querySelector('.step-label');

      if (btnStep <= step) {
        btn.classList.remove('opacity-50');
        indicator.classList.remove('bg-surface-container-highest', 'text-on-surface-variant');
        indicator.classList.add('bg-primary-container', 'text-on-primary-container', 'shadow-[0_0_20px_rgba(227,27,35,0.4)]');
        label.classList.remove('text-on-surface-variant');
        label.classList.add('text-on-surface');
      } else {
        btn.classList.add('opacity-50');
        indicator.classList.add('bg-surface-container-highest', 'text-on-surface-variant');
        indicator.classList.remove('bg-primary-container', 'text-on-primary-container', 'shadow-[0_0_20px_rgba(227,27,35,0.4)]');
        label.classList.add('text-on-surface-variant');
        label.classList.remove('text-on-surface');
      }
    });
  }

  function showStep(step) {
    const steps = document.querySelectorAll('.form-step');
    steps.forEach((el, index) => {
      const elStep = index + 1;
      
      el.classList.remove('translate-x-0', 'translate-x-full', '-translate-x-full', 'opacity-100', 'opacity-0', 'hidden-step', 'pointer-events-none', 'relative', 'absolute', 'inset-0');

      if (elStep === step) {
        el.classList.add('translate-x-0', 'opacity-100', 'relative');
      } else if (elStep < step) {
        el.classList.add('-translate-x-full', 'opacity-0', 'hidden-step', 'pointer-events-none', 'absolute', 'inset-0');
      } else {
        el.classList.add('translate-x-full', 'opacity-0', 'hidden-step', 'pointer-events-none', 'absolute', 'inset-0');
      }
    });
  }

  let isGroupAbonoAuthorized = false;

  function openGroupAbonoModal() {
      document.getElementById('group-abono-admin-password').value = '';
      document.getElementById('group-abono-error-msg').classList.add('hidden');
      document.getElementById('group-abono-success-msg').classList.add('hidden');
      
      const modal = document.getElementById('group-abono-auth-modal');
      modal.classList.remove('opacity-0', 'pointer-events-none');
      setTimeout(() => document.getElementById('group-abono-admin-password').focus(), 100);
  }

  function closeGroupAbonoModal() {
      const modal = document.getElementById('group-abono-auth-modal');
      modal.classList.add('opacity-0', 'pointer-events-none');
  }

  function cancelGroupAbonoModal() {
      closeGroupAbonoModal();
      if (!isGroupAbonoAuthorized) {
          const radioCompleto = document.querySelector('input[name="payment_type"][value="completo"]');
          if (radioCompleto) {
              radioCompleto.checked = true;
              handleGroupPaymentTypeChange(radioCompleto);
          }
      }
  }

  async function verifyGroupAbonoPassword() {
      const password = document.getElementById('group-abono-admin-password').value;
      const errorBox = document.getElementById('group-abono-error-msg');
      const errorText = document.getElementById('group-abono-error-text');
      const successBox = document.getElementById('group-abono-success-msg');
      const btnVerify = document.getElementById('btn-verify-group-abono');

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
              isGroupAbonoAuthorized = true;
              successBox.classList.remove('hidden');
              setTimeout(() => {
                  closeGroupAbonoModal();
                  applyGroupAbonoAuthorizedState();
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
  function filterGroupAbonoKeys(e) {
      if (['e', 'E', '+', '-'].includes(e.key)) {
          e.preventDefault();
      }
  }

  function handleGroupAbonoPaste(e) {
      setTimeout(() => {
          const input = e.target;
          input.value = input.value.replace(/[^0-9.]/g, '');
          calculateGroupAbonoBalance();
      }, 10);
  }

  function setGroupAbonoPercentage(pct) {
      const summaryTotalEl = document.getElementById('summary-total');
      let totalAmount = 0;
      if (summaryTotalEl) {
          totalAmount = parseFloat(summaryTotalEl.textContent.replace('$', '').trim()) || 0;
      }
      const targetVal = (totalAmount * (pct / 100)).toFixed(2);
      const abonoInput = document.getElementById('group_abono_amount_input');
      abonoInput.value = targetVal;
      calculateGroupAbonoBalance();
  }

  function applyGroupAbonoAuthorizedState() {
      document.getElementById('group-abono-auth-badge').classList.remove('hidden');
      document.getElementById('group-abono-unauthorized-box').classList.add('hidden');
      document.getElementById('group-abono-fields-box').classList.remove('hidden');
      
      calculateGroupAbonoBalance();
  }

  function handleGroupPaymentTypeChange(radio) {
      const container = document.getElementById('group-abono-container');
      if (radio.value === 'abono') {
          container.classList.remove('hidden');
          if (!isGroupAbonoAuthorized) {
              openGroupAbonoModal();
          } else {
              applyGroupAbonoAuthorizedState();
          }
      } else {
          container.classList.add('hidden');
      }
  }

  function calculateGroupAbonoBalance() {
      const summaryTotalEl = document.getElementById('summary-total');
      let totalAmount = 0;
      if (summaryTotalEl) {
          totalAmount = parseFloat(summaryTotalEl.textContent.replace('$', '').trim()) || 0;
      }

      const minAbono = Math.round(totalAmount * 0.25 * 100) / 100;
      const maxAbono = Math.round(totalAmount * 0.75 * 100) / 100;
      const midAbono = Math.round(totalAmount * 0.50 * 100) / 100;

      // Actualizar displays de límites y botones de atajo
      const minDisplay = document.getElementById('group-abono-min-display');
      const maxDisplay = document.getElementById('group-abono-max-display');
      const btn25 = document.getElementById('group-btn-val-25');
      const btn50 = document.getElementById('group-btn-val-50');
      const btn75 = document.getElementById('group-btn-val-75');

      if (minDisplay) minDisplay.textContent = minAbono.toFixed(2);
      if (maxDisplay) maxDisplay.textContent = maxAbono.toFixed(2);
      if (btn25) btn25.textContent = minAbono.toFixed(2);
      if (btn50) btn50.textContent = midAbono.toFixed(2);
      if (btn75) btn75.textContent = maxAbono.toFixed(2);

      const abonoInput = document.getElementById('group_abono_amount_input');
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

      const percentage = totalAmount > 0 ? (abonoVal / totalAmount) * 100 : 0;
      const pctBadge = document.getElementById('group-abono-percentage-badge');
      if (pctBadge) pctBadge.textContent = `${percentage.toFixed(1)}%`;

      // Barra de progreso y color interactivo
      const progressBar = document.getElementById('group-abono-progress-bar');
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

      const debt = Math.max(0, totalAmount - abonoVal);

      document.getElementById('group-abono-total-display').textContent = totalAmount.toFixed(2);
      document.getElementById('group-abono-paid-display').textContent = (hasValue ? abonoVal : 0).toFixed(2);
      document.getElementById('group-abono-debt-display').textContent = debt.toFixed(2);

      // Mensaje interactivo y validación visual en tiempo real
      const msgBox = document.getElementById('group-abono-interaction-msg');
      const msgIcon = document.getElementById('group-abono-msg-icon');
      const msgText = document.getElementById('group-abono-msg-text');

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
          if (msgText) msgText.textContent = `¡Abono grupal válido! Estás abonando el ${percentage.toFixed(1)}% ($${abonoVal.toFixed(2)}). Saldo adeudado: $${debt.toFixed(2)}.`;
          abonoInput.classList.remove('border-error', 'border-amber-500');
          abonoInput.classList.add('border-green-500');
      }
  }

  function goToStep(step) {
    if (step === 4 && currentStep < 3) return; 
    currentStep = step;
    updateStepperUI(currentStep);
    showStep(currentStep);
  }

  async function nextStep(current) {
    if (current === 1) {
        const groupName = document.getElementById('group-name-input').value.trim();
        if (!groupName) {
            showErrorModal('Por favor, ingresa un nombre para el grupo.', 1, 'group-name-input');
            return;
        }
        if (current < totalSteps) goToStep(current + 1);
        return;
    }

    if (current === 2) {
        if (current < totalSteps) goToStep(current + 1);
        return;
    }

    if (current === 3) {
      const paymentType = document.querySelector('input[name="payment_type"]:checked').value;
      if (paymentType === 'abono') {
          if (!isGroupAbonoAuthorized) {
              showErrorModal('El registro como abono debe ser autorizado con la clave de administrador.', 3);
              openGroupAbonoModal();
              return;
          }
          const summaryTotalEl = document.getElementById('summary-total');
          let totalAmount = 0;
          if (summaryTotalEl) {
              totalAmount = parseFloat(summaryTotalEl.textContent.replace('$', '').trim()) || 0;
          }
          const minAbono = Math.round(totalAmount * 0.25 * 100) / 100;
          const maxAbono = Math.round(totalAmount * 0.75 * 100) / 100;
          const abonoVal = parseFloat(document.getElementById('group_abono_amount_input').value) || 0;
          
          if (abonoVal <= 0) {
              showErrorModal('Debe ingresar un monto válido a abonar (mayor a $0.00). No se permiten valores negativos ni en cero.', 3, 'group_abono_amount_input');
              return;
          }
          if (abonoVal < minAbono) {
              showErrorModal(`El abono parcial ($${abonoVal.toFixed(2)}) no puede ser inferior al 25% ($${minAbono.toFixed(2)}) del total grupal ($${totalAmount.toFixed(2)}). Ingrese un valor entre $${minAbono.toFixed(2)} y $${maxAbono.toFixed(2)}.`, 3, 'group_abono_amount_input');
              return;
          }
          if (abonoVal > maxAbono) {
              showErrorModal(`El abono parcial ($${abonoVal.toFixed(2)}) no puede ser superior al 75% ($${maxAbono.toFixed(2)}) del total grupal ($${totalAmount.toFixed(2)}). Si cancela el total o desea abonar más, seleccione Pago Completo.`, 3, 'group_abono_amount_input');
              return;
          }
      }

      const method = document.querySelector('input[name="payment_method"]:checked');
      if (method && method.value === 'transferencia') {
          const voucher = document.querySelector('input[name="voucher_number"]').value.trim();
          if (!voucher) {
              showErrorModal('Para pagos con transferencia, ingrese el número de comprobante.', 3, 'voucher_number');
              return;
          }
      }

      // Submitting the form
      const form = document.getElementById('group-form');
      const formData = new FormData(form);
      const submitBtn = event ? event.currentTarget : null;
      const originalText = submitBtn ? submitBtn.innerHTML : '';
      
      if (submitBtn) {
          submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> Procesando...';
          submitBtn.classList.add('opacity-50', 'pointer-events-none');
      }

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
          if (result.first_client_id) {
            const invoiceBtn = document.getElementById('invoice-btn');
            invoiceBtn.href = `/clientes/${result.first_client_id}/invoice`;
            invoiceBtn.classList.remove('hidden');

            const waBtn = document.getElementById('group-wa-btn');
            const groupName = formData.get('group_name') || 'Plan Grupal';
            const totalAmountText = document.getElementById('summary-total')?.textContent?.trim() || '$0.00';
            const totalNum = parseFloat(totalAmountText.replace('$', '').trim()) || 0;
            const paymentType = formData.get('payment_type') || 'completo';
            const paymentMethod = formData.get('payment_method') === 'efectivo' ? 'Efectivo 💵' : 'Transferencia Bancaria 🏦';
            const isAbono = paymentType === 'abono';
            const abonoAmount = parseFloat(formData.get('abono_amount')) || 0;
            const paidVal = isAbono ? abonoAmount.toFixed(2) : totalNum.toFixed(2);
            const debtVal = isAbono ? Math.max(0, totalNum - abonoAmount).toFixed(2) : '0.00';
            const firstPhone = (document.querySelector('input[name="members[0][phone]"]')?.value || '').replace(/[^0-9]/g, '');
            const waNumber = firstPhone.startsWith('593') ? firstPhone : ('593' + firstPhone.replace(/^0+/, ''));
            const invoiceUrl = `${window.location.origin}/clientes/${result.first_client_id}/invoice`;

            let waText = `🏋️‍♂️ *ARES GYM - COMPROBANTE DE PLAN GRUPAL* 🏋️‍♂️\n`;
            waText += `----------------------------------------\n`;
            waText += `👥 *Grupo:* ${groupName}\n`;
            waText += `💳 *Método de Pago:* ${paymentMethod}\n`;
            waText += `💰 *Monto Pagado:* $${paidVal}\n`;
            if (isAbono) {
                waText += `⚠️ *Saldo Pendiente:* $${debtVal}\n`;
                waText += `📌 *Tipo:* Abono Grupal Parcial Autorizado\n`;
            } else {
                waText += `✅ *Estado:* Pago Grupal Completo - Al Día\n`;
            }
            waText += `----------------------------------------\n`;
            waText += `🧾 *Ver Factura / Recibo Digital:* ${invoiceUrl}\n\n`;
            waText += `¡Bienvenidos a Ares Gym! A entrenar en equipo con toda la disciplina y energía. 💪🔥`;

            if (waBtn) {
                waBtn.href = `https://wa.me/${waNumber}?text=${encodeURIComponent(waText)}`;
                waBtn.classList.remove('hidden');
            }
          }
          goToStep(4);
        } else {
          let errorMsg = result.message || 'Error al registrar grupo';
          let targetStep = 3;
          let targetInput = 'group_abono_amount_input';

          if (result.errors) {
            const firstKey = Object.keys(result.errors)[0];
            errorMsg = result.errors[firstKey][0];
            if (firstKey.startsWith('members')) {
                targetStep = 1;
                targetInput = null;
            } else if (firstKey === 'group_name') {
                targetStep = 1;
                targetInput = 'group-name-input';
            } else if (firstKey === 'plan_id') {
                targetStep = 2;
                targetInput = null;
            } else if (firstKey === 'abono_amount') {
                targetStep = 3;
                targetInput = 'group_abono_amount_input';
            }
          }
          showErrorModal(errorMsg, targetStep, targetInput);
        }
      } catch (error) {
        console.error(error);
        showErrorModal('Ocurrió un error en la conexión o al guardar el registro.', 3);
      } finally {
        if (submitBtn) {
            submitBtn.innerHTML = originalText;
            submitBtn.classList.remove('opacity-50', 'pointer-events-none');
        }
      }
    } else {
      if (current < totalSteps) goToStep(current + 1);
    }
  }

  function prevStep(current) {
    if (current > 1) goToStep(current - 1);
  }

  function toggleReceiptField(radio) {
    const field = document.getElementById('receipt-field');
    if(radio.value === 'transferencia') {
      field.classList.remove('hidden');
    } else {
      field.classList.add('hidden');
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    updateStepperUI(1);
    
    let memberCount = 2; // Initial state
    let memberIndex = 2; // To avoid name collisions on delete
    const memberList = document.getElementById('member-list');
    const addBtn = document.getElementById('add-member-btn');
    
    // UI Elements to update
    const countBadge = document.getElementById('member-count-badge');
    const summaryCount = document.getElementById('summary-count');
    const summaryPricePer = document.getElementById('summary-price-per');
    const summaryTotal = document.getElementById('summary-total');
    
    const tier1 = document.getElementById('tier-1');
    const tier2 = document.getElementById('tier-2');

    function updatePricing() {
      let pricePerPerson = memberCount >= 4 ? 20.0 : 25.0;
      let total = memberCount * pricePerPerson;

      countBadge.textContent = `${memberCount} Members`;
      summaryCount.textContent = memberCount.toString().padStart(2, '0');
      summaryPricePer.textContent = `$${pricePerPerson.toFixed(2)}`;
      summaryTotal.textContent = `$${total.toFixed(2)}`;

      if (memberCount >= 4) {
        tier1.classList.remove('bg-surface-container-highest');
        tier1.querySelector('.font-body-md').classList.replace('text-on-surface', 'text-on-surface-variant');
        tier1.querySelector('.font-etiqueta-bold').classList.replace('text-primary', 'text-on-surface-variant');
        
        tier2.classList.add('bg-surface-container-highest');
        tier2.querySelector('.font-body-md').classList.replace('text-on-surface-variant', 'text-on-surface');
        tier2.querySelector('.font-etiqueta-bold').classList.replace('text-on-surface-variant', 'text-primary');
      } else {
        tier2.classList.remove('bg-surface-container-highest');
        tier2.querySelector('.font-body-md').classList.replace('text-on-surface', 'text-on-surface-variant');
        tier2.querySelector('.font-etiqueta-bold').classList.replace('text-primary', 'text-on-surface-variant');
        
        tier1.classList.add('bg-surface-container-highest');
        tier1.querySelector('.font-body-md').classList.replace('text-on-surface-variant', 'text-on-surface');
        tier1.querySelector('.font-etiqueta-bold').classList.replace('text-on-surface-variant', 'text-primary');
      }
      
      const rows = memberList.querySelectorAll('.flex-col.md\\:flex-row > div:nth-child(2)');
      rows.forEach((numDiv, index) => {
          numDiv.textContent = (index + 1).toString().padStart(2, '0');
      });
    }

    addBtn.addEventListener('click', () => {
      memberCount++;
      const newRow = document.createElement('div');
      newRow.className = 'flex flex-col xl:flex-row gap-4 p-4 bg-surface rounded-lg items-center relative group transition-all hover:shadow-sm border border-outline-variant/5 member-row opacity-0 translate-y-4';
      
      newRow.innerHTML = `
        <div class="absolute inset-y-0 left-0 w-1 bg-surface-variant rounded-l-lg group-hover:bg-primary transition-colors"></div>
        <div class="w-10 h-10 bg-surface-container-high rounded-full flex items-center justify-center font-etiqueta-bold text-etiqueta-sm text-on-surface-variant group-hover:text-primary transition-colors mt-2">${String(memberCount).padStart(2, '0')}</div>
        
        <div class="flex-1 w-full flex flex-col" id="row-${memberIndex}">
            <div class="flex justify-between items-center w-full mb-4">
                <span class="font-etiqueta-sm text-on-surface-variant uppercase">Datos del Integrante</span>
                <div class="flex bg-surface-container-high rounded-lg p-1">
                    <button type="button" class="px-3 py-1 text-[12px] font-etiqueta-bold rounded bg-primary text-on-primary mode-btn" onclick="toggleClientMode(this, '${memberIndex}', 'new')">Nuevo</button>
                    <button type="button" class="px-3 py-1 text-[12px] font-etiqueta-bold rounded text-on-surface-variant hover:text-on-surface mode-btn" onclick="toggleClientMode(this, '${memberIndex}', 'existing')">Existente</button>
                </div>
            </div>

            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 w-full new-client-form">
               <div class="flex flex-col gap-1">
                 <label class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant uppercase">Nombre</label>
                 <input name="members[${memberIndex}][name]" type="text" placeholder="Nombre" class="bg-surface-container-lowest text-on-surface font-body-md p-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary w-full border border-outline-variant/10 placeholder:text-surface-variant">
               </div>
               <div class="flex flex-col gap-1">
                 <label class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant uppercase">Apellido</label>
                 <input name="members[${memberIndex}][last_name]" type="text" placeholder="Apellido" class="bg-surface-container-lowest text-on-surface font-body-md p-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary w-full border border-outline-variant/10 placeholder:text-surface-variant">
               </div>
               <div class="flex flex-col gap-1">
                 <label class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant uppercase">ID / Cédula</label>
                 <input name="members[${memberIndex}][id_card]" type="text" placeholder="Cédula" class="bg-surface-container-lowest text-on-surface font-body-md p-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary w-full border border-outline-variant/10 placeholder:text-surface-variant">
               </div>
               <div class="flex flex-col gap-1">
                 <label class="font-etiqueta-sm text-etiqueta-sm text-on-surface-variant uppercase">Teléfono</label>
                 <input name="members[${memberIndex}][phone]" type="text" placeholder="Teléfono" class="bg-surface-container-lowest text-on-surface font-body-md p-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary w-full border border-outline-variant/10 placeholder:text-surface-variant">
               </div>
            </div>

            <div class="w-full hidden existing-client-form relative">
                <input type="hidden" name="members[${memberIndex}][existing_client_id]" class="existing-client-id">
                <div class="relative w-full search-container">
                    <input type="text" class="w-full bg-surface-container-lowest text-on-surface font-body-md p-3 pl-10 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary border border-outline-variant/10 client-search-input" placeholder="Buscar por nombre o cédula..." oninput="searchClients(this, '${memberIndex}')">
                    <span class="material-symbols-outlined absolute left-3 top-3 text-on-surface-variant">search</span>
                    <div class="absolute top-full left-0 w-full bg-surface-container mt-1 rounded-lg shadow-xl border border-outline-variant/10 z-50 hidden search-results max-h-[200px] overflow-y-auto"></div>
                </div>
                <div class="mt-4 p-4 rounded-xl bg-surface-container-highest border border-outline-variant/10 hidden selected-client-card relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full blur-xl -mr-8 -mt-8"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <div class="flex flex-col">
                            <span class="font-titular-md text-[18px] text-on-surface selected-client-name"></span>
                            <span class="font-body-sm text-on-surface-variant flex items-center gap-2">
                                <span class="material-symbols-outlined text-[14px]">badge</span> <span class="selected-client-ci"></span>
                            </span>
                        </div>
                        <button type="button" class="text-error hover:bg-error-container p-2 rounded-lg transition-colors remove-selected-btn" onclick="clearSelectedClient('${memberIndex}')">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-2">
            <button type="button" class="w-10 h-10 flex items-center justify-center text-on-surface-variant hover:text-error hover:bg-error-container rounded-full transition-colors remove-btn">
                <span class="material-symbols-outlined">delete</span>
            </button>
        </div>
      `;

      memberList.appendChild(newRow);
      memberIndex++;
      
      requestAnimationFrame(() => {
          newRow.classList.remove('opacity-0', 'translate-y-4');
      });

      newRow.querySelector('.remove-btn').addEventListener('click', function() {
        if (memberCount > 2) {
            newRow.classList.add('opacity-0', '-translate-x-4');
            setTimeout(() => {
                newRow.remove();
                memberCount--;
                updatePricing();
            }, 300);
        } else {
            showErrorModal('Un plan grupal debe tener al menos 2 integrantes.');
        }
      });

      updatePricing();
    });

    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (memberCount > 2) {
                const row = e.target.closest('.member-row');
                row.classList.add('opacity-0', '-translate-x-4');
                setTimeout(() => {
                    row.remove();
                    memberCount--;
                    updatePricing();
                }, 300);
            } else {
                showErrorModal('Un plan grupal debe tener al menos 2 integrantes.');
            }
        })
    })
  });
  </script>
  <script>
    
    function toggleClientMode(btn, index, mode) {
        const row = document.getElementById('row-' + index);
        const newForm = row.querySelector('.new-client-form');
        const existingForm = row.querySelector('.existing-client-form');
        
        // Update buttons
        row.querySelectorAll('.mode-btn').forEach(b => {
            b.classList.remove('bg-primary', 'text-on-primary');
            b.classList.add('text-on-surface-variant', 'hover:text-on-surface');
        });
        btn.classList.remove('text-on-surface-variant', 'hover:text-on-surface');
        btn.classList.add('bg-primary', 'text-on-primary');
        
        if (mode === 'new') {
            newForm.classList.remove('hidden');
            existingForm.classList.add('hidden');
            // Re-enable inputs
            newForm.querySelectorAll('input').forEach(i => i.disabled = false);
            existingForm.querySelector('.existing-client-id').value = '';
        } else {
            newForm.classList.add('hidden');
            existingForm.classList.remove('hidden');
            // Disable new inputs so they aren't validated/submitted
            newForm.querySelectorAll('input').forEach(i => i.disabled = true);
        }
    }

    let searchTimeouts = {};
    function searchClients(input, index) {
        clearTimeout(searchTimeouts[index]);
        const resultsContainer = input.parentElement.querySelector('.search-results');
        const query = input.value.trim();
        
        if (query.length < 3) {
            resultsContainer.classList.add('hidden');
            return;
        }
        
        searchTimeouts[index] = setTimeout(async () => {
            try {
                const response = await fetch(`/clientes/search?q=${encodeURIComponent(query)}`);
                const clients = await response.json();
                
                resultsContainer.innerHTML = '';
                if (clients.length === 0) {
                    resultsContainer.innerHTML = '<div class="p-4 text-on-surface-variant font-body-sm text-center">No se encontraron resultados.</div>';
                } else {
                    clients.forEach(c => {
                        const div = document.createElement('div');
                        div.className = 'p-3 hover:bg-surface-container-highest cursor-pointer border-b border-outline-variant/10 last:border-0 transition-colors flex flex-col';
                        div.innerHTML = `<span class="font-titular-md text-on-surface text-[14px]">${c.name} ${c.last_name}</span><span class="font-body-sm text-on-surface-variant">${c.id_card}</span>`;
                        div.onclick = () => selectClient(index, c.id, `${c.name} ${c.last_name}`, c.id_card);
                        resultsContainer.appendChild(div);
                    });
                }
                resultsContainer.classList.remove('hidden');
            } catch (e) {
                console.error("Error searching clients", e);
            }
        }, 300);
    }
    
    function selectClient(index, id, name, idCard) {
        const row = document.getElementById('row-' + index);
        const searchContainer = row.querySelector('.search-container');
        const cardContainer = row.querySelector('.selected-client-card');
        
        row.querySelector('.existing-client-id').value = id;
        row.querySelector('.selected-client-name').textContent = name;
        row.querySelector('.selected-client-ci').textContent = idCard;
        
        searchContainer.classList.add('hidden');
        cardContainer.classList.remove('hidden');
        
        // Hide search results
        row.querySelector('.search-results').classList.add('hidden');
        row.querySelector('.client-search-input').value = '';
    }
    
    function clearSelectedClient(index) {
        const row = document.getElementById('row-' + index);
        const searchContainer = row.querySelector('.search-container');
        const cardContainer = row.querySelector('.selected-client-card');
        
        row.querySelector('.existing-client-id').value = '';
        
        cardContainer.classList.add('hidden');
        searchContainer.classList.remove('hidden');
    }

    let pendingGroupRedirectStep = null;
    let pendingGroupRedirectInput = null;

    function showErrorModal(message, targetStep = null, targetInputId = null) {
        pendingGroupRedirectStep = targetStep;
        pendingGroupRedirectInput = targetInputId;

        document.getElementById('error-modal-text').innerText = message;
        const modal = document.getElementById('error-modal');
        modal.classList.remove('pointer-events-none', 'opacity-0');
        modal.querySelector('#error-modal-content').classList.remove('scale-95');

        const btnClose = document.getElementById('btn-close-group-error-modal');
        if (btnClose) {
            btnClose.innerHTML = targetStep ? `Corregir en Paso ${targetStep} <span class="material-symbols-outlined text-[18px]">arrow_forward</span>` : 'Entendido';
        }
    }
    
    function closeErrorModal() {
        const modal = document.getElementById('error-modal');
        modal.classList.add('pointer-events-none', 'opacity-0');
        modal.querySelector('#error-modal-content').classList.add('scale-95');

        const stepToRedirect = pendingGroupRedirectStep;
        const inputToFocus = pendingGroupRedirectInput;
        pendingGroupRedirectStep = null;
        pendingGroupRedirectInput = null;

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

    function clampGroupAbonoOnBlur() {
        const summaryTotalEl = document.getElementById('summary-total');
        let totalAmount = 0;
        if (summaryTotalEl) {
            totalAmount = parseFloat(summaryTotalEl.textContent.replace('$', '').trim()) || 0;
        }
        const maxAbono = Math.round(totalAmount * 0.75 * 100) / 100;
        const abonoInput = document.getElementById('group_abono_amount_input');
        let abonoVal = parseFloat(abonoInput.value) || 0;

        if (abonoVal > maxAbono) {
            abonoInput.value = maxAbono.toFixed(2);
            calculateGroupAbonoBalance();
        }
    }
    
    function fillRandomData() {
        document.getElementById('group-name-input').value = 'Grupo Test ' + Math.floor(Math.random() * 1000);
        if(typeof window.updateGroupName === 'function') window.updateGroupName();
        
        const firstNames = ['Carlos', 'Andres', 'Juan', 'Luis', 'Pedro', 'Maria', 'Ana', 'Laura', 'Sofia', 'Lucia'];
        const lastNames = ['Perez', 'Gomez', 'Lopez', 'Garcia', 'Martinez', 'Rodriguez', 'Fernandez', 'Ruiz', 'Diaz', 'Alvarez'];
        
        const rows = document.querySelectorAll('#member-list div[id^="row-"]');
        rows.forEach(row => {
            const newForm = row.querySelector('.new-client-form');
            if (newForm && !newForm.classList.contains('hidden')) {
                const inputs = newForm.querySelectorAll('input');
                if (inputs.length >= 4) {
                    inputs[0].value = firstNames[Math.floor(Math.random() * firstNames.length)];
                    inputs[1].value = lastNames[Math.floor(Math.random() * lastNames.length)];
                    inputs[2].value = '09' + Math.floor(10000000 + Math.random() * 90000000);
                    inputs[3].value = '09' + Math.floor(10000000 + Math.random() * 90000000);
                }
            }
        });
    }
  </script>
@endpush

