@extends('layouts.admin')

@section('title', 'Control de Asistencia - Ares Gym')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .glass-card {
        background: rgba(31, 31, 31, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    /* Scroll custom para el feed */
    .custom-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.1);
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(227, 27, 35, 0.5);
    }
</style>
@endpush

@section('content')
<div class="flex flex-col w-full animate-on-load gap-6 pb-12">
    
    <!-- HEADER -->
    <div class="flex justify-between items-end border-b border-outline-variant/10 pb-4">
        <div>
            <div class="flex items-center gap-2 text-primary tracking-widest font-etiqueta-sm uppercase mb-2">
                <span class="w-8 h-[1px] bg-primary"></span>
                <span>CONTROL DE ACCESO</span>
            </div>
            <h1 class="font-titular-xl text-[40px] text-on-surface uppercase tracking-tight leading-none" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">Asistencia Diaria</h1>
        </div>
        <div class="hidden md:flex flex-col items-end gap-1">
            <span class="font-etiqueta-bold text-etiqueta-sm text-on-surface-variant uppercase tracking-widest">Reloj Local (En Vivo)</span>
            <div class="bg-surface-container-high px-4 py-2 rounded-xl border border-outline-variant/20 flex items-center gap-3">
                <span class="material-symbols-outlined text-primary animate-pulse">schedule</span>
                <span id="live-clock" class="font-titular-md text-[24px] text-primary" style="font-family: 'JetBrains Mono', monospace;">18:14:28</span>
            </div>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 mt-2">
        
        <!-- COLUMNA IZQUIERDA (40%): Check-In Rápido -->
        <div class="xl:col-span-5 flex flex-col gap-6">
            
            <!-- Tarjeta de Registro -->
            <div class="glass-card p-6 rounded-[24px] shadow-xl border-t border-t-primary/30 flex flex-col gap-6 relative overflow-hidden">
                <div class="absolute right-0 top-0 w-full h-full bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary/10 via-transparent to-transparent pointer-events-none"></div>
                
                <h3 class="font-titular-md text-[20px] text-on-surface flex items-center gap-2 relative z-10" style="font-family: 'Montserrat', sans-serif;">
                    <span class="material-symbols-outlined text-primary">qr_code_scanner</span> Registro de Entrada
                </h3>

                <!-- Buscador -->
                <div class="relative group z-10">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors text-[24px]">search</span>
                    <input type="text" id="checkin-search" placeholder="Ingrese Cédula o Nombre..." class="w-full bg-surface-container-lowest text-on-surface text-[16px] pl-12 pr-4 py-4 rounded-xl border border-outline-variant/20 focus:border-primary focus:outline-none font-body-md shadow-inner transition-colors" autofocus autocomplete="off" />
                </div>

                <!-- Previsualización del Cliente (Oculto por defecto) -->
                <div id="client-preview" class="hidden bg-surface-container-lowest p-4 rounded-xl border flex items-center justify-between z-10 transition-all animate-fade-in">
                    <div class="flex items-center gap-4">
                        <div class="relative w-14 h-14">
                            <img src="https://ui-avatars.com/api/?name=Kathy&background=1f1f1f&color=e2e2e2&size=128" alt="Foto" class="w-full h-full rounded-full border-2 border-primary/50 shadow-sm">
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-[#4ade80] rounded-full border-2 border-surface flex items-center justify-center">
                                <span class="material-symbols-outlined text-background text-[12px]">done</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-titular-md text-on-surface text-[18px]">Kathy S.</h4>
                            <p class="font-etiqueta-sm text-on-surface-variant text-[11px] uppercase tracking-wider mt-0.5">Plan Mensual • <span class="text-[#4ade80] font-bold">Activo (12 días)</span></p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-[#4ade80] text-[32px]">check_circle</span>
                </div>

                <!-- Alerta de Deuda (Simulada) -->
                <div id="debt-alert" class="hidden bg-error/10 p-4 rounded-xl border border-error/30 flex items-center justify-between z-10 transition-all animate-fade-in">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-error-container/50 border-2 border-error flex items-center justify-center text-error font-bold text-xl shadow-sm">M</div>
                        <div>
                            <h4 class="font-titular-md text-on-surface text-[18px]">Marcus A.</h4>
                            <p class="font-etiqueta-sm text-error text-[11px] uppercase tracking-wider mt-0.5 font-bold">Abono Pendiente: $15.00</p>
                        </div>
                    </div>
                    <button class="bg-error hover:brightness-110 text-on-error px-3 py-2 rounded-lg font-etiqueta-bold text-[10px] uppercase tracking-wider transition-colors">Cobrar</button>
                </div>

                <!-- Botón de Confirmación -->
                <button id="btn-checkin" class="w-full bg-surface-container-highest text-on-surface-variant font-titular-md text-[16px] uppercase tracking-wider py-4 rounded-xl flex items-center justify-center gap-2 transition-all z-10 cursor-not-allowed border border-outline-variant/10" disabled>
                    <span class="material-symbols-outlined">how_to_reg</span> Marcar Asistencia
                </button>
            </div>

            <!-- Feed de Entradas Recientes -->
            <div class="glass-card rounded-[24px] p-6 shadow-xl border border-outline-variant/10 flex flex-col flex-1 max-h-[400px]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-titular-md text-[16px] text-on-surface flex items-center gap-2" style="font-family: 'Montserrat', sans-serif;">
                        <span class="material-symbols-outlined text-primary text-[18px]">history</span> Entradas Recientes
                    </h3>
                    <span class="bg-[#4ade80]/10 text-[#4ade80] font-etiqueta-bold text-[10px] uppercase px-2 py-1 rounded animate-pulse">En vivo</span>
                </div>
                
                <div class="flex flex-col gap-3 overflow-y-auto custom-scroll pr-2" id="feed-list">
                    <!-- Item 1 -->
                    <div class="bg-surface-container-lowest p-3 rounded-xl border border-outline-variant/10 flex justify-between items-center group hover:border-primary/30 transition-colors">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=Leo&background=1f1f1f&color=e2e2e2" class="w-8 h-8 rounded-full">
                            <div>
                                <p class="font-body-md text-on-surface font-bold text-sm">Leo Ares</p>
                                <p class="font-etiqueta-sm text-on-surface-variant text-[10px]">Plan Trimestral</p>
                            </div>
                        </div>
                        <span class="font-mono text-on-surface-variant text-[12px] group-hover:text-primary transition-colors">18:05</span>
                    </div>
                    <!-- Item 2 -->
                    <div class="bg-surface-container-lowest p-3 rounded-xl border border-outline-variant/10 flex justify-between items-center group hover:border-primary/30 transition-colors">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=Diana+R&background=1f1f1f&color=e2e2e2" class="w-8 h-8 rounded-full">
                            <div>
                                <p class="font-body-md text-on-surface font-bold text-sm">Diana Rodríguez</p>
                                <p class="font-etiqueta-sm text-on-surface-variant text-[10px]">Plan Mensual</p>
                            </div>
                        </div>
                        <span class="font-mono text-on-surface-variant text-[12px] group-hover:text-primary transition-colors">17:42</span>
                    </div>
                    <!-- Item 3 -->
                    <div class="bg-surface-container-lowest p-3 rounded-xl border border-outline-variant/10 flex justify-between items-center group hover:border-primary/30 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant text-[12px] font-bold">GE</div>
                            <div>
                                <p class="font-body-md text-on-surface font-bold text-sm">Grupo Espartanos</p>
                                <p class="font-etiqueta-sm text-on-surface-variant text-[10px]">Plan Grupal (3)</p>
                            </div>
                        </div>
                        <span class="font-mono text-on-surface-variant text-[12px] group-hover:text-primary transition-colors">17:15</span>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- COLUMNA DERECHA (60%): Panel Estadístico Analytics -->
        <div class="xl:col-span-7 flex flex-col gap-6">
            
            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-surface-container-low border border-outline-variant/10 rounded-xl p-4 flex flex-col relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-[#4ade80]/10 rounded-full blur-xl group-hover:bg-[#4ade80]/20 transition-colors"></div>
                    <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest mb-1 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">directions_run</span> Presentes Hoy</span>
                    <span class="font-titular-xl text-[32px] text-on-surface font-bold leading-none">136 <span class="font-body-md text-on-surface-variant text-[12px] font-normal">personas</span></span>
                </div>
                
                <div class="bg-surface-container-low border border-outline-variant/10 rounded-xl p-4 flex flex-col relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-[#facc15]/10 rounded-full blur-xl group-hover:bg-[#facc15]/20 transition-colors"></div>
                    <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest mb-1 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">bolt</span> Hora Pico Actual</span>
                    <span class="font-titular-xl text-[32px] text-on-surface font-bold leading-none" style="font-family: 'Montserrat', sans-serif;">18:00 <span class="font-body-md text-on-surface-variant text-[12px] font-normal uppercase">pm</span></span>
                </div>
                
                <div class="bg-surface-container-low border border-outline-variant/10 rounded-xl p-4 flex flex-col relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-primary/10 rounded-full blur-xl group-hover:bg-primary/20 transition-colors"></div>
                    <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest mb-1 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">trending_up</span> Promedio Semanal</span>
                    <span class="font-titular-xl text-[32px] text-on-surface font-bold leading-none">72<span class="font-body-md text-primary text-[16px]">%</span></span>
                </div>
            </div>

            <!-- Gráfico Principal: Donut Chart -->
            <div class="glass-card rounded-[24px] p-6 shadow-xl border border-outline-variant/10 grid grid-cols-1 md:grid-cols-2 gap-8 items-center h-full">
                <div class="flex flex-col gap-4">
                    <h2 class="font-titular-md text-[20px] text-on-surface leading-tight" style="font-family: 'Montserrat', sans-serif;">Porcentaje de Asistencia Diaria</h2>
                    <p class="font-body-md text-on-surface-variant text-sm">Proporción de socios activos que han marcado su ingreso el día de hoy.</p>
                    
                    <div class="flex flex-col gap-3 mt-4">
                        <div class="flex items-center justify-between bg-surface-container-lowest p-3 rounded-lg border-l-4 border-l-primary">
                            <span class="font-etiqueta-bold text-[12px] text-on-surface uppercase tracking-wider">Asistieron (Hoy)</span>
                            <span class="font-titular-md text-primary">136</span>
                        </div>
                        <div class="flex items-center justify-between bg-surface-container-lowest p-3 rounded-lg border-l-4 border-l-surface-container-high">
                            <span class="font-etiqueta-bold text-[12px] text-on-surface uppercase tracking-wider">Faltantes</span>
                            <span class="font-titular-md text-on-surface-variant">64</span>
                        </div>
                        <div class="flex items-center justify-between pt-2">
                            <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-wider">Base Activa Total</span>
                            <span class="font-body-md text-on-surface text-sm">200 socios</span>
                        </div>
                    </div>
                </div>
                
                <div class="relative w-full aspect-square max-w-[250px] mx-auto flex items-center justify-center">
                    <canvas id="attendanceDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="font-titular-xl text-[48px] text-on-surface leading-none" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">68%</span>
                        <span class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest mt-1">Presentes</span>
                    </div>
                </div>
            </div>

            <!-- Gráfico Secundario: Histograma Picos -->
            <div class="glass-card rounded-[24px] p-6 shadow-xl border border-outline-variant/10 flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-titular-md text-[18px] text-on-surface flex items-center gap-2" style="font-family: 'Montserrat', sans-serif;">
                        <span class="material-symbols-outlined text-primary text-[18px]">bar_chart</span> Picos de Horario (Afluencia)
                    </h2>
                </div>
                <div class="w-full h-[180px] relative">
                    <canvas id="peaksChart"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- RELOJ EN VIVO ---
        function updateClock() {
            const now = new Date();
            let h = now.getHours().toString().padStart(2, '0');
            let m = now.getMinutes().toString().padStart(2, '0');
            let s = now.getSeconds().toString().padStart(2, '0');
            document.getElementById('live-clock').innerText = `${h}:${m}:${s}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // --- LOGICA DE CHECK-IN SIMULADA ---
        const searchInput = document.getElementById('checkin-search');
        const previewValid = document.getElementById('client-preview');
        const previewDebt = document.getElementById('debt-alert');
        const btnCheckin = document.getElementById('btn-checkin');
        
        searchInput.addEventListener('input', function(e) {
            const val = e.target.value.toLowerCase();
            
            // Reset
            previewValid.classList.add('hidden');
            previewDebt.classList.add('hidden');
            btnCheckin.disabled = true;
            btnCheckin.className = "w-full bg-surface-container-highest text-on-surface-variant font-titular-md text-[16px] uppercase tracking-wider py-4 rounded-xl flex items-center justify-center gap-2 transition-all z-10 cursor-not-allowed border border-outline-variant/10";

            if (val.includes('kathy')) {
                previewValid.classList.remove('hidden');
                
                // Activar botón Verde/Rojo
                btnCheckin.disabled = false;
                btnCheckin.className = "w-full bg-primary hover:bg-primary/90 text-on-primary font-titular-md text-[16px] uppercase tracking-wider py-4 rounded-xl flex items-center justify-center gap-2 shadow-[0_0_15px_rgba(227,27,35,0.4)] hover:shadow-[0_0_25px_rgba(227,27,35,0.6)] transition-all z-10";
            
            } else if (val.includes('marcus')) {
                previewDebt.classList.remove('hidden');
            }
        });

        // Simular registro exitoso
        btnCheckin.addEventListener('click', function() {
            const feed = document.getElementById('feed-list');
            
            // Obtener hora actual
            const now = new Date();
            let h = now.getHours().toString().padStart(2, '0');
            let m = now.getMinutes().toString().padStart(2, '0');

            const newEntry = `
                <div class="bg-[#4ade80]/10 p-3 rounded-xl border border-[#4ade80]/30 flex justify-between items-center animate-fade-in">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=Kathy&background=1f1f1f&color=e2e2e2" class="w-8 h-8 rounded-full border border-[#4ade80]/50">
                        <div>
                            <p class="font-body-md text-[#4ade80] font-bold text-sm">Kathy S.</p>
                            <p class="font-etiqueta-sm text-on-surface-variant text-[10px]">Plan Mensual</p>
                        </div>
                    </div>
                    <span class="font-mono text-[#4ade80] font-bold text-[12px]">${h}:${m}</span>
                </div>
            `;
            
            // Insertar al inicio
            feed.insertAdjacentHTML('afterbegin', newEntry);
            
            // Reset form
            searchInput.value = '';
            previewValid.classList.add('hidden');
            btnCheckin.disabled = true;
            btnCheckin.className = "w-full bg-surface-container-highest text-on-surface-variant font-titular-md text-[16px] uppercase tracking-wider py-4 rounded-xl flex items-center justify-center gap-2 transition-all z-10 cursor-not-allowed border border-outline-variant/10";
            searchInput.focus();
        });


        // --- CHART.JS ---
        Chart.defaults.color = '#9ca3af';
        Chart.defaults.font.family = 'Inter, sans-serif';

        // 1. Donut Chart
        const ctxDonut = document.getElementById('attendanceDonutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Asistieron', 'Faltan'],
                datasets: [{
                    data: [136, 64],
                    backgroundColor: ['#e31b23', '#2a2a2a'],
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
                        borderColor: '#e31b23',
                        borderWidth: 1
                    }
                }
            }
        });

        // 2. Bar Chart (Picos)
        const ctxBar = document.getElementById('peaksChart').getContext('2d');
        const gradient = ctxBar.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, '#e31b23');
        gradient.addColorStop(1, 'rgba(227, 27, 35, 0.1)');

        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['06h', '07h', '08h', '10h', '12h', '16h', '18h', '19h', '20h'],
                datasets: [{
                    label: 'Afluencia',
                    data: [25, 40, 30, 15, 10, 35, 60, 55, 20],
                    backgroundColor: gradient,
                    borderRadius: 4,
                    borderSkipped: false,
                    barThickness: 16
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { display: false, beginAtZero: true },
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
