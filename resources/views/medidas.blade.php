@extends('layouts.admin')

@section('title', 'Medidas y Progreso - Ares Gym')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .glass-card {
        background: rgba(31, 31, 31, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .input-medida {
        @apply w-full bg-surface-container-lowest text-on-surface font-body-md px-4 py-2 rounded-lg focus:outline-none focus:border-primary/50 border border-outline-variant/20 shadow-inner transition-colors;
    }
    .form-group label {
        @apply font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px] mb-1 block;
    }
    
    /* Scroll custom para el formulario */
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
    
    <!-- Page Header & Seleccion de Cliente -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-2">
        <div>
            <div class="flex items-center gap-2 text-primary tracking-widest font-etiqueta-sm uppercase mb-2">
                <span class="w-8 h-[1px] bg-primary"></span>
                <span>MÓDULO DEPORTIVO</span>
            </div>
            <h1 class="font-titular-xl text-[40px] text-on-surface uppercase tracking-tight leading-none" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">Medidas y Progreso</h1>
        </div>
        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto items-end">
            <div class="relative w-full md:w-64 group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary">person_search</span>
                <input type="text" class="w-full bg-surface-container-low text-on-surface font-body-md pl-12 pr-4 py-3 rounded-xl focus:outline-none border border-outline-variant/20 focus:border-primary/50" value="Kathy">
            </div>
            <button class="bg-primary text-on-primary font-titular-md text-[14px] uppercase tracking-wider px-6 py-3 rounded-xl shadow-[0_0_15px_rgba(227,27,35,0.3)] hover:scale-105 transition-all whitespace-nowrap flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span> Nueva Toma
            </button>
        </div>
    </div>

    <!-- CABECERA DE ESTADO (Ficha del Cliente) -->
    <div class="glass-card rounded-[24px] p-6 shadow-xl border border-outline-variant/10 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-full bg-gradient-to-l from-primary/10 to-transparent pointer-events-none"></div>
        
        <!-- Avatar y Datos -->
        <div class="flex items-center gap-6 z-10">
            <div class="relative w-20 h-20">
                <img src="https://ui-avatars.com/api/?name=Kathy&background=1f1f1f&color=e2e2e2&size=128" alt="Kathy" class="w-full h-full rounded-full border-2 border-primary/50">
            </div>
            <div>
                <h2 class="font-titular-md text-[24px] text-on-surface leading-tight" style="font-family: 'Montserrat', sans-serif;">Kathy S.</h2>
                <div class="flex items-center gap-3 mt-1">
                    <span class="font-body-md text-on-surface-variant text-sm">28 años</span>
                    <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                    <span class="font-etiqueta-bold text-[10px] text-primary uppercase tracking-wider border border-primary/20 bg-primary/10 px-2 py-0.5 rounded">Plan Mensual</span>
                </div>
            </div>
        </div>

        <div class="hidden md:block w-px h-16 bg-outline-variant/20 z-10"></div>

        <!-- Alertas y Estado -->
        <div class="flex flex-1 justify-between items-center z-10 gap-4">
            <!-- Alerta de Adaptación -->
            <div class="flex items-center gap-3 bg-[#4ade80]/10 border border-[#4ade80]/20 px-4 py-3 rounded-xl">
                <span class="material-symbols-outlined text-[#4ade80]">verified</span>
                <div>
                    <p class="font-etiqueta-bold text-[12px] text-[#4ade80] uppercase tracking-wider">Apta para Mediciones</p>
                    <p class="font-body-md text-on-surface-variant text-[11px]">Periodo de adaptación (3 días) superado.</p>
                </div>
            </div>
            
            <!-- Frecuencia -->
            <div class="flex flex-col items-end">
                <span class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px]">Mediciones este mes</span>
                <div class="flex items-baseline gap-1">
                    <span class="font-titular-xl text-[28px] text-on-surface" style="font-family: 'Montserrat', sans-serif;">1</span>
                    <span class="font-titular-md text-on-surface-variant text-[16px]">/ 2</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN GRID (40% - 60%) -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        
        <!-- COLUMNA IZQUIERDA (40%): Formulario -->
        <div class="xl:col-span-5 flex flex-col gap-6">
            <div class="glass-card rounded-[24px] p-6 shadow-xl border-t border-t-primary/30 flex flex-col flex-1 h-[750px]">
                
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-titular-md text-[20px] text-on-surface" style="font-family: 'Montserrat', sans-serif;">Registro de Datos</h2>
                    <span class="font-etiqueta-sm text-primary uppercase tracking-widest">{{ date('d M, Y') }}</span>
                </div>
                
                <!-- Scrollable Form Area -->
                <div class="overflow-y-auto custom-scroll pr-2 flex-1 flex flex-col gap-6">
                    
                    <!-- 1. Composición Corporal -->
                    <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant/10">
                        <h3 class="font-etiqueta-bold text-[12px] text-on-surface uppercase tracking-widest mb-4 flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[16px]">monitor_weight</span> Composición Corporal</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group relative">
                                <label>Peso (kg)</label>
                                <input type="number" class="input-medida" value="62.5">
                            </div>
                            <div class="form-group relative">
                                <label>% Grasa</label>
                                <input type="number" class="input-medida" value="24.0">
                            </div>
                            <div class="form-group relative">
                                <label>% Masa Muscular</label>
                                <input type="number" class="input-medida" value="38.5">
                            </div>
                            <div class="form-group relative">
                                <label>IMC (Auto)</label>
                                <input type="number" class="input-medida bg-surface-container-highest text-on-surface-variant" value="22.1" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Perímetros -->
                    <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant/10">
                        <h3 class="font-etiqueta-bold text-[12px] text-on-surface uppercase tracking-widest mb-4 flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[16px]">accessibility_new</span> Perímetros (cm)</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group"><label>Pecho / Busto</label><input type="number" class="input-medida" value="88"></div>
                            <div class="form-group"><label>Cintura</label><input type="number" class="input-medida" value="68"></div>
                            <div class="form-group"><label>Cadera</label><input type="number" class="input-medida" value="96"></div>
                            <div class="form-group"><label>Espalda</label><input type="number" class="input-medida" value="42"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t border-outline-variant/10">
                            <div class="form-group"><label>Bíceps Izq.</label><input type="number" class="input-medida" value="26"></div>
                            <div class="form-group"><label>Bíceps Der.</label><input type="number" class="input-medida" value="26.5"></div>
                            <div class="form-group"><label>Muslo Izq.</label><input type="number" class="input-medida" value="54"></div>
                            <div class="form-group"><label>Muslo Der.</label><input type="number" class="input-medida" value="54.5"></div>
                            <div class="form-group"><label>Pantorrilla Izq.</label><input type="number" class="input-medida" value="34"></div>
                            <div class="form-group"><label>Pantorrilla Der.</label><input type="number" class="input-medida" value="34"></div>
                        </div>
                    </div>

                    <!-- 3. Pliegues & Imagen de Referencia -->
                    <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant/10">
                        <h3 class="font-etiqueta-bold text-[12px] text-on-surface uppercase tracking-widest mb-4 flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[16px]">straighten</span> Pliegues Cutáneos (mm)</h3>
                        <div class="flex flex-col xl:flex-row gap-4 items-center">
                            <!-- Visual Reference -->
                            <div class="w-full xl:w-1/3 flex justify-center bg-[#131313] rounded-lg p-2 border border-outline-variant/20">
                                <img src="{{ asset('img/body_points.png') }}" alt="Puntos Anatómicos" class="h-32 object-contain opacity-80 hover:opacity-100 transition-opacity mix-blend-screen">
                            </div>
                            <!-- Inputs -->
                            <div class="w-full xl:w-2/3 grid grid-cols-2 gap-4">
                                <div class="form-group"><label>Tríceps</label><input type="number" class="input-medida" value="14"></div>
                                <div class="form-group"><label>Bíceps</label><input type="number" class="input-medida" value="8"></div>
                                <div class="form-group"><label>Abdominal</label><input type="number" class="input-medida" value="18"></div>
                                <div class="form-group"><label>Subescapular</label><input type="number" class="input-medida" value="12"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="w-full mt-6 bg-primary text-on-primary font-titular-md text-[14px] uppercase tracking-wider py-4 rounded-xl shadow-[0_0_15px_rgba(227,27,35,0.3)] hover:shadow-[0_0_25px_rgba(227,27,35,0.5)] transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">save</span> Guardar Medidas
                </button>
            </div>
        </div>

        <!-- COLUMNA DERECHA (60%): Historial, Gráficos y Nutrición -->
        <div class="xl:col-span-7 flex flex-col gap-6">
            
            <!-- GRAFICA DE EVOLUCION -->
            <div class="glass-card rounded-[24px] p-6 shadow-xl border border-outline-variant/10 flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-titular-md text-[20px] text-on-surface" style="font-family: 'Montserrat', sans-serif;">Curva de Progreso</h2>
                    <!-- Selector de Metricas para el Chart -->
                    <select class="bg-surface-container-low text-on-surface-variant border border-outline-variant/20 text-[10px] font-etiqueta-bold uppercase rounded-lg px-3 py-1 focus:outline-none">
                        <option>Peso vs % Grasa</option>
                        <option>Masa Muscular</option>
                        <option>Cintura y Abdomen</option>
                    </select>
                </div>
                <div class="w-full h-[220px] relative">
                    <canvas id="progresoChart"></canvas>
                </div>
            </div>

            <!-- TABLA COMPARATIVA LADO A LADO -->
            <div class="glass-card rounded-[24px] p-6 shadow-xl border border-outline-variant/10">
                <h2 class="font-titular-md text-[18px] text-on-surface mb-4" style="font-family: 'Montserrat', sans-serif;">Comparativa Inmediata</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-outline-variant/20 text-on-surface-variant font-etiqueta-bold text-[10px] uppercase tracking-widest">
                                <th class="py-2 px-2 font-medium">Métrica</th>
                                <th class="py-2 px-2 font-medium">Toma Anterior (15/07)</th>
                                <th class="py-2 px-2 font-medium">Hoy (11/08)</th>
                                <th class="py-2 px-2 font-medium text-right">Variación</th>
                            </tr>
                        </thead>
                        <tbody class="font-body-md text-on-surface text-sm">
                            <tr class="border-b border-outline-variant/10">
                                <td class="py-3 px-2 font-bold text-on-surface-variant">Peso</td>
                                <td class="py-3 px-2">64.0 kg</td>
                                <td class="py-3 px-2">62.5 kg</td>
                                <td class="py-3 px-2 text-right text-[#4ade80] font-etiqueta-bold text-[12px]">-1.5 kg ↓</td>
                            </tr>
                            <tr class="border-b border-outline-variant/10">
                                <td class="py-3 px-2 font-bold text-on-surface-variant">% Grasa</td>
                                <td class="py-3 px-2">26.5 %</td>
                                <td class="py-3 px-2">24.0 %</td>
                                <td class="py-3 px-2 text-right text-[#4ade80] font-etiqueta-bold text-[12px]">-2.5 % ↓</td>
                            </tr>
                            <tr class="border-b border-outline-variant/10">
                                <td class="py-3 px-2 font-bold text-on-surface-variant">Cintura</td>
                                <td class="py-3 px-2">72 cm</td>
                                <td class="py-3 px-2">68 cm</td>
                                <td class="py-3 px-2 text-right text-[#4ade80] font-etiqueta-bold text-[12px]">-4 cm ↓</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-2 font-bold text-on-surface-variant">Masa Muscular</td>
                                <td class="py-3 px-2">37.0 kg</td>
                                <td class="py-3 px-2">38.5 kg</td>
                                <td class="py-3 px-2 text-right text-primary font-etiqueta-bold text-[12px]">+1.5 kg ↑</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MODULO: PLAN NUTRICIONAL -->
            <div class="bg-surface-container rounded-[24px] p-6 shadow-xl border border-primary/20 flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden">
                <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-primary/10 rounded-full blur-2xl pointer-events-none"></div>
                
                <div class="flex-1 relative z-10">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary">restaurant_menu</span>
                        <h2 class="font-titular-md text-[20px] text-on-surface" style="font-family: 'Montserrat', sans-serif;">Plan Nutricional</h2>
                    </div>
                    <p class="font-body-md text-on-surface-variant text-sm">Basado en las medidas actuales, el objetivo es <strong class="text-on-surface">Déficit Calórico</strong> (aprox. 1500 kcal). ¿Deseas actualizar su dieta?</p>
                </div>
                
                <div class="flex flex-col gap-3 w-full md:w-auto relative z-10">
                    <button class="bg-primary/10 hover:bg-primary/20 text-primary border border-primary/30 px-6 py-2.5 rounded-lg font-etiqueta-bold text-[12px] uppercase tracking-wider transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">edit_document</span> Elaborar Dieta
                    </button>
                    
                    <div class="flex gap-2">
                        <button class="flex-1 bg-surface-container-highest hover:bg-surface-bright text-on-surface px-4 py-2 rounded-lg font-etiqueta-bold text-[10px] uppercase tracking-wider transition-colors flex items-center justify-center gap-1 border border-outline-variant/10">
                            <span class="material-symbols-outlined text-[14px]">picture_as_pdf</span> Exportar
                        </button>
                        <button class="flex-1 bg-[#25D366]/10 hover:bg-[#25D366]/20 text-[#25D366] px-4 py-2 rounded-lg font-etiqueta-bold text-[10px] uppercase tracking-wider transition-colors flex items-center justify-center gap-1 border border-[#25D366]/30">
                            <i class="fab fa-whatsapp"></i> Enviar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart.js Configuration
        Chart.defaults.color = '#9ca3af'; // text-on-surface-variant
        Chart.defaults.font.family = 'Inter, sans-serif';

        const ctx = document.getElementById('progresoChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['15 Abr', '15 May', '15 Jun', '15 Jul', 'Hoy'],
                datasets: [
                    {
                        label: 'Peso (kg)',
                        data: [67, 66.2, 65.5, 64.0, 62.5],
                        borderColor: '#e31b23', // primary red
                        backgroundColor: 'transparent',
                        borderWidth: 3,
                        pointBackgroundColor: '#131313',
                        pointBorderColor: '#e31b23',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        yAxisID: 'y'
                    },
                    {
                        label: '% Grasa',
                        data: [29, 28.5, 27.5, 26.5, 24.0],
                        borderColor: '#4ade80', // green
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5], // dashed line for body fat
                        pointBackgroundColor: '#131313',
                        pointBorderColor: '#4ade80',
                        pointRadius: 3,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            color: '#e2e2e2'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1f1f1f',
                        titleColor: '#e2e2e2',
                        bodyColor: '#e2e2e2',
                        borderColor: '#2a2a2a',
                        borderWidth: 1,
                        padding: 10
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Peso (kg)', color: '#e31b23', font: {size: 10} },
                        grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: '% Grasa', color: '#4ade80', font: {size: 10} },
                        grid: { drawOnChartArea: false }, // avoid overlapping grid lines
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush
