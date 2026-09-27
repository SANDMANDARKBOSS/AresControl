@extends('layouts.admin')

@section('title', 'Dashboard - Ares Gym')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .glass-card {
        background: rgba(31, 31, 31, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
</style>
@endpush

@section('content')
<div class="flex flex-col w-full animate-on-load gap-8">
    
    <!-- Page Header -->
    <div class="flex justify-between items-end">
        <div>
            <div class="flex items-center gap-2 text-primary tracking-widest font-etiqueta-sm uppercase mb-2">
                <span class="w-8 h-[1px] bg-primary"></span>
                <span>PANEL PRINCIPAL</span>
            </div>
            <h1 class="font-titular-xl text-[48px] text-on-surface uppercase tracking-tight leading-none" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">Visión Global</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant mt-2">Resumen operativo y estado financiero del día.</p>
        </div>
        <div class="hidden md:flex gap-4">
            <div class="text-right">
                <p class="font-etiqueta-bold text-etiqueta-sm text-on-surface-variant uppercase tracking-widest">Fecha Operativa</p>
                <p class="font-titular-md text-[20px] text-on-surface">{{ now()->format('d M, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- BLOQUE 1: KPIs (Tarjetas de Resumen Rápido) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- KPI 1: Ingresos -->
        <div class="glass-card rounded-2xl p-6 flex flex-col gap-4 relative overflow-hidden group hover:-translate-y-1 transition-transform">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary/10 rounded-full blur-xl group-hover:bg-primary/20 transition-colors"></div>
            <div class="flex justify-between items-start z-10">
                <span class="font-etiqueta-bold text-etiqueta-sm text-on-surface-variant uppercase tracking-widest">Ingresos del Mes</span>
                <span class="material-symbols-outlined text-primary">payments</span>
            </div>
            <div class="z-10">
                <h3 class="font-titular-xl text-[36px] text-on-surface" style="font-family: 'Montserrat', sans-serif; font-weight: 700;">$4,250</h3>
                <div class="flex items-center gap-3 mt-2">
                    <span class="font-etiqueta-sm text-[11px] text-on-surface-variant bg-surface-container-highest px-2 py-1 rounded-md">EFECT: $1,250</span>
                    <span class="font-etiqueta-sm text-[11px] text-on-surface-variant bg-surface-container-highest px-2 py-1 rounded-md">TRANS: $3,000</span>
                </div>
            </div>
        </div>
        
        <!-- KPI 2: Socios Activos -->
        <div class="glass-card rounded-2xl p-6 flex flex-col gap-4 relative overflow-hidden group hover:-translate-y-1 transition-transform">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary/10 rounded-full blur-xl group-hover:bg-primary/20 transition-colors"></div>
            <div class="flex justify-between items-start z-10">
                <span class="font-etiqueta-bold text-etiqueta-sm text-on-surface-variant uppercase tracking-widest">Socios Activos</span>
                <span class="material-symbols-outlined text-primary">group</span>
            </div>
            <div class="z-10">
                <h3 class="font-titular-xl text-[36px] text-on-surface" style="font-family: 'Montserrat', sans-serif; font-weight: 700;">145</h3>
                <div class="flex items-center gap-1 mt-2 text-[#4ade80]">
                    <span class="material-symbols-outlined text-[16px]">trending_up</span>
                    <span class="font-etiqueta-sm text-etiqueta-sm">+12 este mes</span>
                </div>
            </div>
        </div>

        <!-- KPI 3: Pendiente de Cobro -->
        <div class="glass-card rounded-2xl p-6 flex flex-col gap-4 relative overflow-hidden group hover:-translate-y-1 transition-transform border-b-4 border-b-[#facc15]">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#facc15]/10 rounded-full blur-xl group-hover:bg-[#facc15]/20 transition-colors pointer-events-none"></div>
            <div class="flex justify-between items-start z-10">
                <span class="font-etiqueta-bold text-etiqueta-sm text-on-surface-variant uppercase tracking-widest">Pendiente de Cobro</span>
                <span class="material-symbols-outlined text-[#facc15]">warning</span>
            </div>
            <div class="z-10">
                <h3 class="font-titular-xl text-[36px] text-on-surface" style="font-family: 'Montserrat', sans-serif; font-weight: 700;">18</h3>
                <div class="flex items-center gap-1 mt-2 text-[#facc15]">
                    <span class="material-symbols-outlined text-[16px]">schedule</span>
                    <span class="font-etiqueta-sm text-etiqueta-sm">Por vencer (7-15 d)</span>
                </div>
            </div>
        </div>

        <!-- KPI 4: Asistencia Hoy -->
        <div class="glass-card rounded-2xl p-6 flex flex-col gap-4 relative overflow-hidden group hover:-translate-y-1 transition-transform border-b-4 border-b-primary">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary/10 rounded-full blur-xl group-hover:bg-primary/20 transition-colors"></div>
            <div class="flex justify-between items-start z-10">
                <span class="font-etiqueta-bold text-etiqueta-sm text-on-surface-variant uppercase tracking-widest">Asistencia Hoy</span>
                <span class="material-symbols-outlined text-primary">local_fire_department</span>
            </div>
            <div class="z-10">
                <h3 class="font-titular-xl text-[36px] text-on-surface" style="font-family: 'Montserrat', sans-serif; font-weight: 700;">87</h3>
                <div class="w-full bg-surface-container-highest rounded-full h-1.5 mt-4 overflow-hidden">
                    <div class="bg-primary h-1.5 rounded-full" style="width: 60%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Left Column: BLOQUE 2 & 3 -->
        <div class="xl:col-span-2 flex flex-col gap-6">
            
            <!-- BLOQUE 2: Control de Asistencia del Día -->
            <div class="glass-card rounded-[24px] p-6 lg:p-8 flex flex-col gap-8 shadow-2xl relative overflow-hidden border-t border-t-primary/20">
                <!-- Decorative background -->
                <div class="absolute top-0 right-0 w-full h-full bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary/5 via-transparent to-transparent pointer-events-none"></div>
                
                <!-- Buscador / Anotador Rápido -->
                <div class="relative z-10">
                    <h2 class="font-titular-md text-[24px] text-on-surface mb-4" style="font-family: 'Montserrat', sans-serif;">Punto de Control (Check-In)</h2>
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="relative flex-1 group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">qr_code_scanner</span>
                            <input type="text" id="checkin-input" class="w-full bg-surface-container-lowest text-on-surface font-body-lg pl-12 pr-4 py-4 rounded-xl focus:outline-none transition-all duration-300 shadow-inner group-focus-within:bg-surface-container-highest border border-outline-variant/20 focus:border-primary/50" placeholder="Escanear o digitar Cédula / Nombre..." autocomplete="off">
                            
                            <!-- Dropdown de Autocompletado (Simulado) -->
                            <div id="checkin-dropdown" class="absolute w-full mt-2 bg-surface-container-high border border-outline-variant/20 rounded-xl shadow-2xl z-50 hidden flex-col overflow-hidden">
                                <button class="flex items-center justify-between p-4 hover:bg-surface-bright transition-colors text-left" onclick="processCheckin('valid')">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-etiqueta-bold">LA</div>
                                        <div>
                                            <p class="font-body-md text-on-surface font-bold">Leo Ares</p>
                                            <p class="font-etiqueta-sm text-on-surface-variant">ID: 172839401</p>
                                        </div>
                                    </div>
                                    <span class="font-etiqueta-sm text-[#4ade80] bg-[#4ade80]/10 px-2 py-1 rounded uppercase tracking-wider">Activo</span>
                                </button>
                                <button class="flex items-center justify-between p-4 hover:bg-surface-bright transition-colors text-left border-t border-outline-variant/10" onclick="processCheckin('expired')">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-surface-container-highest text-on-surface-variant flex items-center justify-center font-etiqueta-bold">MA</div>
                                        <div>
                                            <p class="font-body-md text-on-surface font-bold">Marcus Aurelius</p>
                                            <p class="font-etiqueta-sm text-on-surface-variant">ID: 091234567</p>
                                        </div>
                                    </div>
                                    <span class="font-etiqueta-sm text-primary bg-primary/10 px-2 py-1 rounded uppercase tracking-wider">Vencido / Deuda</span>
                                </button>
                            </div>
                        </div>
                        <button class="bg-primary hover:bg-primary/90 text-on-primary px-8 py-4 rounded-xl font-titular-md text-[16px] uppercase tracking-wider transition-all shadow-[0_0_15px_rgba(227,27,35,0.3)] hover:shadow-[0_0_25px_rgba(227,27,35,0.5)] flex items-center justify-center gap-2" onclick="triggerCheckin()">
                            <span class="material-symbols-outlined">how_to_reg</span> Registrar Entrada
                        </button>
                    </div>
                    
                    <!-- Alerta de Vencimiento / Deuda (Oculta por defecto) -->
                    <div id="checkin-alert" class="mt-4 bg-error-container/20 border border-error/50 rounded-xl p-4 flex items-start gap-4 hidden transition-all animate-pulse">
                        <span class="material-symbols-outlined text-error mt-0.5">warning</span>
                        <div>
                            <p class="font-titular-md text-error text-[18px]">¡Alerta de Acceso Denegado!</p>
                            <p class="font-body-md text-on-surface-variant mt-1" id="checkin-alert-msg">El cliente <strong class="text-on-surface">Marcus Aurelius</strong> presenta una deuda pendiente de $30.00 (Membresía Vencida hace 2 días).</p>
                        </div>
                        <button class="ml-auto bg-error text-on-error px-4 py-2 rounded-lg font-etiqueta-bold text-[12px] uppercase tracking-wider hover:brightness-110" onclick="hideAlert()">Ignorar / Dejar Pasar</button>
                    </div>
                    <!-- Notificación de Éxito -->
                    <div id="checkin-success" class="mt-4 bg-[#4ade80]/10 border border-[#4ade80]/30 rounded-xl p-4 flex items-center gap-4 hidden transition-all">
                        <span class="material-symbols-outlined text-[#4ade80]">check_circle</span>
                        <p class="font-titular-md text-[#4ade80] text-[18px]">Entrada registrada: Leo Ares</p>
                    </div>
                </div>

                <!-- Gráficos de Asistencia -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-4">
                    <!-- Gráfico Dona -->
                    <div class="bg-surface-container-low rounded-xl p-6 border border-outline-variant/10 flex flex-col items-center">
                        <h3 class="font-etiqueta-bold text-etiqueta-sm text-on-surface-variant uppercase tracking-widest self-start mb-4">Ratio de Asistencia Hoy</h3>
                        <div class="relative w-48 h-48">
                            <canvas id="attendanceDoughnut"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="font-titular-xl text-[32px] text-on-surface leading-none" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">65%</span>
                                <span class="font-etiqueta-sm text-on-surface-variant">Asistieron</span>
                            </div>
                        </div>
                        <div class="flex gap-6 mt-6 w-full justify-center">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-primary"></div>
                                <span class="font-etiqueta-sm text-on-surface-variant">Asistieron (87)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-surface-container-highest"></div>
                                <span class="font-etiqueta-sm text-on-surface-variant">Faltan (58)</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gráfico Barras (Picos) -->
                    <div class="bg-surface-container-low rounded-xl p-6 border border-outline-variant/10 flex flex-col">
                        <h3 class="font-etiqueta-bold text-etiqueta-sm text-on-surface-variant uppercase tracking-widest mb-4">Picos de Hora</h3>
                        <div class="flex-1 w-full relative min-h-[150px]">
                            <canvas id="peaksBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- BLOQUE 3 (Mitad): Pagos Pendientes / Abonos -->
            <div class="glass-card rounded-[24px] p-6 lg:p-8 flex flex-col gap-6 shadow-xl border border-outline-variant/10">
                <div class="flex justify-between items-end">
                    <div>
                        <h2 class="font-titular-md text-[24px] text-on-surface" style="font-family: 'Montserrat', sans-serif;">Pagos Pendientes & Abonos</h2>
                        <p class="font-body-md text-on-surface-variant">Liquidación mensual y deudores.</p>
                    </div>
                    <button class="font-etiqueta-bold text-etiqueta-sm text-primary uppercase tracking-widest hover:underline">Ver Todos</button>
                </div>
                
                <div class="flex flex-col gap-3">
                    <!-- Item 1 -->
                    <div class="bg-surface-container-low hover:bg-surface-container transition-colors rounded-xl p-4 flex items-center justify-between group cursor-pointer border border-transparent hover:border-outline-variant/20">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-surface-container-highest flex items-center justify-center font-titular-md text-on-surface-variant group-hover:text-primary transition-colors">DR</div>
                            <div>
                                <p class="font-body-md text-on-surface font-bold">Diana Rodríguez</p>
                                <p class="font-etiqueta-sm text-on-surface-variant">Abonó $15.00 el Lunes</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-titular-md text-error text-[18px]">-$15.00</p>
                            <button class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest bg-surface-container-highest px-3 py-1 rounded-md mt-1 hover:bg-primary/20 hover:text-primary transition-colors">Liquidar Ahora</button>
                        </div>
                    </div>
                    <!-- Item 2 -->
                    <div class="bg-surface-container-low hover:bg-surface-container transition-colors rounded-xl p-4 flex items-center justify-between group cursor-pointer border border-transparent hover:border-outline-variant/20">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-surface-container-highest flex items-center justify-center font-titular-md text-on-surface-variant group-hover:text-primary transition-colors">JV</div>
                            <div>
                                <p class="font-body-md text-on-surface font-bold">Juan Vallejo</p>
                                <p class="font-etiqueta-sm text-on-surface-variant">Plan Anual Incompleto</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-titular-md text-error text-[18px]">-$50.00</p>
                            <button class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest bg-surface-container-highest px-3 py-1 rounded-md mt-1 hover:bg-primary/20 hover:text-primary transition-colors">Liquidar Ahora</button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Right Column: BLOQUE 4 & BLOQUE 3 (Alertas) -->
        <div class="flex flex-col gap-6">
            
            <!-- BLOQUE 4: Accesos Directos a Tareas Frecuentes -->
            <div class="bg-primary/10 border border-primary/20 rounded-[24px] p-6 shadow-lg flex flex-col gap-4">
                <div class="flex items-center gap-2 text-primary tracking-widest font-etiqueta-sm uppercase mb-2">
                    <span class="material-symbols-outlined text-[16px]">bolt</span>
                    <span>Acción Rápida</span>
                </div>
                
                <a href="{{ route('clientes.create') }}" class="bg-surface-container hover:bg-surface-bright text-on-surface p-4 rounded-xl transition-all flex items-center gap-4 group border border-outline-variant/10 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-primary/20 text-primary flex items-center justify-center group-hover:scale-110 transition-transform"><span class="material-symbols-outlined">person_add</span></div>
                    <div class="flex-1">
                        <p class="font-titular-md text-[16px]">Nuevo Cliente</p>
                        <p class="font-etiqueta-sm text-on-surface-variant">Con 3 días adaptación</p>
                    </div>
                </a>
                
                <button class="bg-surface-container hover:bg-surface-bright text-on-surface p-4 rounded-xl transition-all flex items-center gap-4 group border border-outline-variant/10 shadow-sm text-left">
                    <div class="w-10 h-10 rounded-full bg-primary/20 text-primary flex items-center justify-center group-hover:scale-110 transition-transform"><span class="material-symbols-outlined">straighten</span></div>
                    <div class="flex-1">
                        <p class="font-titular-md text-[16px]">Medidas Antropométricas</p>
                        <p class="font-etiqueta-sm text-on-surface-variant">Registro mensual</p>
                    </div>
                </button>
                
                <button class="bg-surface-container hover:bg-surface-bright text-on-surface p-4 rounded-xl transition-all flex items-center gap-4 group border border-outline-variant/10 shadow-sm text-left">
                    <div class="w-10 h-10 rounded-full bg-primary/20 text-primary flex items-center justify-center group-hover:scale-110 transition-transform"><span class="material-symbols-outlined">restaurant_menu</span></div>
                    <div class="flex-1">
                        <p class="font-titular-md text-[16px]">Plan Nutricional</p>
                        <p class="font-etiqueta-sm text-on-surface-variant">Crear / Enviar PDF</p>
                    </div>
                </button>
                
                <button class="bg-surface-container hover:bg-surface-bright text-on-surface p-4 rounded-xl transition-all flex items-center gap-4 group border border-outline-variant/10 shadow-sm text-left">
                    <div class="w-10 h-10 rounded-full bg-[#4ade80]/20 text-[#4ade80] flex items-center justify-center group-hover:scale-110 transition-transform"><span class="material-symbols-outlined">point_of_sale</span></div>
                    <div class="flex-1">
                        <p class="font-titular-md text-[16px]">Registrar Pago</p>
                        <p class="font-etiqueta-sm text-on-surface-variant">Efectivo / Transferencia</p>
                    </div>
                </button>
            </div>

            <!-- BLOQUE 3 (Continuación): Alertas de Vencimiento -->
            <div class="glass-card rounded-[24px] p-6 shadow-lg border border-outline-variant/10 flex flex-col gap-4 flex-1">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="font-titular-md text-[20px] text-on-surface flex items-center gap-2" style="font-family: 'Montserrat', sans-serif;">
                        <span class="material-symbols-outlined text-[#facc15]">notification_important</span> Próximos Vencimientos
                    </h2>
                </div>
                
                <div class="flex flex-col gap-3">
                    <!-- Client 1 -->
                    <div class="bg-surface-container-low rounded-lg p-3 flex flex-col gap-3 border-l-2 border-[#facc15]">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-body-md text-on-surface font-bold">Carlos Martínez</p>
                                <p class="font-etiqueta-sm text-[#facc15]">Vence en 2 días</p>
                            </div>
                            <span class="font-titular-md text-[16px] text-on-surface-variant">$30</span>
                        </div>
                        <button class="w-full bg-[#25D366]/10 hover:bg-[#25D366]/20 text-[#25D366] border border-[#25D366]/30 rounded-md py-2 flex items-center justify-center gap-2 font-etiqueta-bold text-[12px] uppercase tracking-wider transition-colors">
                            <i class="fab fa-whatsapp text-[14px]"></i> Recordatorio WhatsApp
                        </button>
                    </div>
                    <!-- Client 2 -->
                    <div class="bg-surface-container-low rounded-lg p-3 flex flex-col gap-3 border-l-2 border-[#facc15]">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-body-md text-on-surface font-bold">Elena Rivas</p>
                                <p class="font-etiqueta-sm text-[#facc15]">Vence en 4 días</p>
                            </div>
                            <span class="font-titular-md text-[16px] text-on-surface-variant">$30</span>
                        </div>
                        <button class="w-full bg-[#25D366]/10 hover:bg-[#25D366]/20 text-[#25D366] border border-[#25D366]/30 rounded-md py-2 flex items-center justify-center gap-2 font-etiqueta-bold text-[12px] uppercase tracking-wider transition-colors">
                            <i class="fab fa-whatsapp text-[14px]"></i> Recordatorio WhatsApp
                        </button>
                    </div>
                    <!-- Client 3 -->
                    <div class="bg-surface-container-low rounded-lg p-3 flex flex-col gap-3 border-l-2 border-outline-variant/30 opacity-70 hover:opacity-100 transition-opacity">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-body-md text-on-surface font-bold">Fernando Silva</p>
                                <p class="font-etiqueta-sm text-on-surface-variant">Vence en 7 días</p>
                            </div>
                        </div>
                    </div>
                    <!-- Client 4 -->
                    <div class="bg-surface-container-low rounded-lg p-3 flex flex-col gap-3 border-l-2 border-outline-variant/30 opacity-70 hover:opacity-100 transition-opacity">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-body-md text-on-surface font-bold">Andrea Cruz</p>
                                <p class="font-etiqueta-sm text-on-surface-variant">Vence en 8 días</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Add FontAwesome for WhatsApp Icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    // --- LÓGICA DE CHECK-IN ---
    const checkinInput = document.getElementById('checkin-input');
    const dropdown = document.getElementById('checkin-dropdown');
    const alertBox = document.getElementById('checkin-alert');
    const successBox = document.getElementById('checkin-success');
    const alertMsg = document.getElementById('checkin-alert-msg');

    // Simular búsqueda al escribir
    checkinInput.addEventListener('input', (e) => {
        if(e.target.value.length > 2) {
            dropdown.classList.remove('hidden');
            dropdown.classList.add('flex');
        } else {
            dropdown.classList.add('hidden');
            dropdown.classList.remove('flex');
        }
    });

    // Cerrar dropdown si hace clic afuera
    document.addEventListener('click', (e) => {
        if(!checkinInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
            dropdown.classList.remove('flex');
        }
    });

    function processCheckin(status) {
        dropdown.classList.add('hidden');
        dropdown.classList.remove('flex');
        checkinInput.value = '';
        
        hideAlert();
        successBox.classList.add('hidden');

        if(status === 'expired') {
            alertMsg.innerHTML = 'El cliente <strong class="text-on-surface">Marcus Aurelius</strong> presenta una deuda pendiente de $30.00 (Membresía Vencida hace 2 días).';
            alertBox.classList.remove('hidden');
        } else {
            successBox.classList.remove('hidden');
            setTimeout(() => { successBox.classList.add('hidden'); }, 3000);
        }
    }

    function triggerCheckin() {
        const val = checkinInput.value.toLowerCase();
        if(val.includes('marcus')) {
            processCheckin('expired');
        } else if(val.length > 2) {
            processCheckin('valid');
        }
    }

    function hideAlert() {
        alertBox.classList.add('hidden');
    }

    // --- GRÁFICOS CHART.JS ---
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración común de Chart.js para Dark Mode
        Chart.defaults.color = '#b4b5b5'; // text-on-surface-variant
        Chart.defaults.font.family = 'Inter, sans-serif';

        // 1. Gráfico de Dona (Ratio Asistencia)
        const ctxDoughnut = document.getElementById('attendanceDoughnut').getContext('2d');
        new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: ['Asistieron', 'Faltan'],
                datasets: [{
                    data: [65, 35],
                    backgroundColor: [
                        '#e31b23', // primary
                        '#353535'  // surface-container-highest
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                cutout: '80%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f1f1f',
                        titleColor: '#e2e2e2',
                        bodyColor: '#e2e2e2',
                        borderColor: '#5d3f3c',
                        borderWidth: 1
                    }
                }
            }
        });

        // 2. Gráfico de Barras (Picos de Hora)
        const ctxBar = document.getElementById('peaksBarChart').getContext('2d');
        
        // Crear gradiente para barras
        const gradient = ctxBar.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, '#e31b23');
        gradient.addColorStop(1, 'rgba(227, 27, 35, 0.1)');

        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['06h', '08h', '10h', '12h', '16h', '18h', '20h'],
                datasets: [{
                    label: 'Asistentes',
                    data: [15, 30, 10, 5, 20, 45, 25],
                    backgroundColor: gradient,
                    borderRadius: 4,
                    borderSkipped: false,
                    barThickness: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        display: false,
                        beginAtZero: true
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endpush
