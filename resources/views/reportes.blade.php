@extends('layouts.admin')

@section('title', 'Centro de Reportes - Ares Gym')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .glass-card {
        background: rgba(31, 31, 31, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .tab-btn.active {
        background-color: #e31b23;
        color: #ffffff;
        border-color: #e31b23;
    }
    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease-out forwards;
    }
    .tab-content.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="flex flex-col w-full animate-on-load gap-6 pb-12">
    
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-outline-variant/10 pb-4">
        <div>
            <div class="flex items-center gap-2 text-primary tracking-widest font-etiqueta-sm uppercase mb-2">
                <span class="w-8 h-[1px] bg-primary"></span>
                <span>CENTRO DE REPORTES</span>
            </div>
            <h1 class="font-titular-xl text-[36px] text-on-surface uppercase tracking-tight leading-none" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">Analítica & IA</h1>
        </div>
        <button class="bg-primary hover:bg-primary/90 text-on-primary px-6 py-3 rounded-xl font-etiqueta-bold text-[12px] uppercase tracking-wider transition-all flex items-center gap-2 shadow-[0_0_15px_rgba(227,27,35,0.3)]">
            <span class="material-symbols-outlined text-[18px]">add_chart</span> Generar Personalizado
        </button>
    </div>

    <!-- PESTAÑAS (TABS) -->
    <div class="flex flex-nowrap overflow-x-auto gap-3 pb-2 w-full custom-scroll">
        <button onclick="switchTab('tab-operativos')" class="tab-btn active bg-surface-container-high border border-outline-variant/20 hover:bg-surface-bright text-on-surface-variant font-etiqueta-bold text-[12px] uppercase tracking-wider px-6 py-3 rounded-xl transition-colors whitespace-nowrap flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">insert_chart</span> Reportes Operativos
        </button>
        <button onclick="switchTab('tab-ia')" class="tab-btn bg-surface-container-high border border-outline-variant/20 hover:bg-surface-bright text-on-surface-variant font-etiqueta-bold text-[12px] uppercase tracking-wider px-6 py-3 rounded-xl transition-colors whitespace-nowrap flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">auto_awesome</span> Ficha Mensual IA
        </button>
        <button onclick="switchTab('tab-facturacion')" class="tab-btn bg-surface-container-high border border-outline-variant/20 hover:bg-surface-bright text-on-surface-variant font-etiqueta-bold text-[12px] uppercase tracking-wider px-6 py-3 rounded-xl transition-colors whitespace-nowrap flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">receipt_long</span> Facturación
        </button>
    </div>

    <!-- CONTENIDO TABS -->
    <div class="mt-2">
        
        <!-- PESTAÑA 1: REPORTES OPERATIVOS -->
        <div id="tab-operativos" class="tab-content active">
            <div class="flex justify-end mb-4">
                <div class="flex items-center gap-2 bg-surface-container p-1 rounded-lg border border-outline-variant/10">
                    <button class="px-4 py-1.5 bg-surface-container-highest text-on-surface font-etiqueta-sm text-[11px] uppercase rounded flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">calendar_month</span> Este Mes</button>
                    <button class="px-4 py-1.5 text-on-surface-variant hover:text-on-surface font-etiqueta-sm text-[11px] uppercase rounded transition-colors">Mes Anterior</button>
                    <button class="px-4 py-1.5 text-on-surface-variant hover:text-on-surface font-etiqueta-sm text-[11px] uppercase rounded transition-colors">Anual</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Tarjeta 1: Progreso -->
                <div class="glass-card p-6 rounded-2xl hover:border-primary/50 transition-colors cursor-pointer group flex flex-col justify-between h-48">
                    <div>
                        <span class="material-symbols-outlined text-primary text-[28px] mb-3 group-hover:scale-110 transition-transform">monitor_weight</span>
                        <h3 class="font-titular-md text-[16px] text-on-surface mb-1">Progreso de Medidas</h3>
                        <p class="font-body-md text-on-surface-variant text-[12px]">Evolución gráfica por cliente.</p>
                    </div>
                    <div class="flex justify-end gap-2 text-on-surface-variant">
                        <span class="material-symbols-outlined hover:text-primary transition-colors text-[18px]">picture_as_pdf</span>
                    </div>
                </div>

                <!-- Tarjeta 2: Nutrición -->
                <div class="glass-card p-6 rounded-2xl hover:border-primary/50 transition-colors cursor-pointer group flex flex-col justify-between h-48">
                    <div>
                        <span class="material-symbols-outlined text-primary text-[28px] mb-3 group-hover:scale-110 transition-transform">restaurant_menu</span>
                        <h3 class="font-titular-md text-[16px] text-on-surface mb-1">Plan Nutricional</h3>
                        <p class="font-body-md text-on-surface-variant text-[12px]">Dietas y macros asignados.</p>
                    </div>
                    <div class="flex justify-end gap-2 text-on-surface-variant">
                        <span class="material-symbols-outlined hover:text-primary transition-colors text-[18px]">picture_as_pdf</span>
                    </div>
                </div>

                <!-- Tarjeta 3: Ingresos -->
                <div class="glass-card p-6 rounded-2xl hover:border-[#4ade80]/50 transition-colors cursor-pointer group flex flex-col justify-between h-48">
                    <div>
                        <span class="material-symbols-outlined text-[#4ade80] text-[28px] mb-3 group-hover:scale-110 transition-transform">payments</span>
                        <h3 class="font-titular-md text-[16px] text-on-surface mb-1">Ingresos por Periodo</h3>
                        <p class="font-body-md text-on-surface-variant text-[12px]">Arqueo de caja y transferencias.</p>
                    </div>
                    <div class="flex justify-end gap-2 text-on-surface-variant">
                        <span class="material-symbols-outlined hover:text-[#4ade80] transition-colors text-[18px] mr-1">table_view</span>
                        <span class="material-symbols-outlined hover:text-primary transition-colors text-[18px]">picture_as_pdf</span>
                    </div>
                </div>

                <!-- Tarjeta 4: Vencimientos -->
                <div class="glass-card p-6 rounded-2xl hover:border-[#facc15]/50 transition-colors cursor-pointer group flex flex-col justify-between h-48">
                    <div>
                        <span class="material-symbols-outlined text-[#facc15] text-[28px] mb-3 group-hover:scale-110 transition-transform">event_busy</span>
                        <h3 class="font-titular-md text-[16px] text-on-surface mb-1">Vencimiento de Planes</h3>
                        <p class="font-body-md text-on-surface-variant text-[12px]">Próximos 7 a 15 días.</p>
                    </div>
                    <div class="flex justify-end gap-2 text-on-surface-variant">
                        <span class="material-symbols-outlined hover:text-primary transition-colors text-[18px]">picture_as_pdf</span>
                    </div>
                </div>

                <!-- Tarjeta 5: Cartera -->
                <div class="glass-card p-6 rounded-2xl hover:border-outline-variant/50 transition-colors cursor-pointer group flex flex-col justify-between h-48">
                    <div>
                        <span class="material-symbols-outlined text-on-surface text-[28px] mb-3 group-hover:scale-110 transition-transform">groups</span>
                        <h3 class="font-titular-md text-[16px] text-on-surface mb-1">Cartera de Clientes</h3>
                        <p class="font-body-md text-on-surface-variant text-[12px]">Padrón total y estado activo/inactivo.</p>
                    </div>
                    <div class="flex justify-end gap-2 text-on-surface-variant">
                        <span class="material-symbols-outlined hover:text-[#4ade80] transition-colors text-[18px] mr-1">table_view</span>
                    </div>
                </div>

                <!-- Tarjeta 6: Pagos Pendientes -->
                <div class="glass-card p-6 rounded-2xl hover:border-error/50 transition-colors cursor-pointer group flex flex-col justify-between h-48">
                    <div>
                        <span class="material-symbols-outlined text-error text-[28px] mb-3 group-hover:scale-110 transition-transform">money_off</span>
                        <h3 class="font-titular-md text-[16px] text-on-surface mb-1">Pagos Pendientes</h3>
                        <p class="font-body-md text-on-surface-variant text-[12px]">Listado de deudores y abonos.</p>
                    </div>
                    <div class="flex justify-end gap-2 text-on-surface-variant">
                        <span class="material-symbols-outlined hover:text-primary transition-colors text-[18px]">picture_as_pdf</span>
                    </div>
                </div>

                <!-- Tarjeta 7: Distribución Planes -->
                <div class="glass-card p-6 rounded-2xl hover:border-outline-variant/50 transition-colors cursor-pointer group flex flex-col justify-between h-48">
                    <div>
                        <span class="material-symbols-outlined text-on-surface text-[28px] mb-3 group-hover:scale-110 transition-transform">pie_chart</span>
                        <h3 class="font-titular-md text-[16px] text-on-surface mb-1">Distribución de Planes</h3>
                        <p class="font-body-md text-on-surface-variant text-[12px]">Porcentaje de ventas por plan.</p>
                    </div>
                    <div class="flex justify-end gap-2 text-on-surface-variant">
                        <span class="material-symbols-outlined hover:text-primary transition-colors text-[18px]">picture_as_pdf</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- PESTAÑA 2: FICHA DE FIN DE MES CON IA -->
        <div id="tab-ia" class="tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Columna Datos y Gráficas -->
                <div class="lg:col-span-7 flex flex-col gap-6">
                    <!-- Buscador de Socio -->
                    <div class="relative w-full">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                        <input type="text" placeholder="Buscar socio para Ficha IA (ej. Kathy)..." class="w-full bg-surface-container-high text-on-surface font-body-md pl-12 pr-4 py-3 rounded-xl border border-outline-variant/20 focus:border-primary focus:outline-none shadow-sm" value="Kathy S." />
                    </div>

                    <!-- Ficha Encabezado -->
                    <div class="glass-card rounded-[24px] p-6 border-t border-t-primary/30 flex justify-between items-center relative overflow-hidden shadow-xl">
                        <div class="absolute right-0 top-0 w-32 h-full bg-gradient-to-l from-primary/10 to-transparent pointer-events-none"></div>
                        <div class="flex items-center gap-4 z-10">
                            <img src="https://ui-avatars.com/api/?name=Kathy+S&background=1f1f1f&color=e2e2e2&size=128" alt="Kathy" class="w-16 h-16 rounded-full border-2 border-primary/50">
                            <div>
                                <h2 class="font-titular-md text-[20px] text-on-surface leading-tight" style="font-family: 'Montserrat', sans-serif;">Reporte Mensual: Kathy S.</h2>
                                <p class="font-etiqueta-sm text-on-surface-variant text-[11px] uppercase tracking-wider mt-1">Período: <span class="text-on-surface">15 Jul - 15 Ago</span></p>
                            </div>
                        </div>
                        <div class="text-right z-10">
                            <span class="block font-titular-xl text-[28px] text-[#4ade80] leading-none">85%</span>
                            <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest">Asistencia</span>
                        </div>
                    </div>

                    <!-- Comparativa Visual -->
                    <div class="glass-card rounded-[24px] p-6 shadow-xl border border-outline-variant/10">
                        <h3 class="font-titular-md text-[18px] text-on-surface mb-4" style="font-family: 'Montserrat', sans-serif;">Comparativa de Medidas</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <!-- Peso -->
                            <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/10 text-center">
                                <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest block mb-2">Peso</span>
                                <div class="flex items-center justify-center gap-2 mb-1">
                                    <span class="text-on-surface-variant text-[14px] line-through">78.2</span>
                                    <span class="material-symbols-outlined text-[14px] text-on-surface-variant">arrow_forward</span>
                                    <span class="font-bold text-on-surface text-[16px]">76.6</span>
                                </div>
                                <span class="font-etiqueta-sm text-[#4ade80] text-[10px] bg-[#4ade80]/10 px-2 py-0.5 rounded">-1.6 kg ↓</span>
                            </div>
                            <!-- Grasa -->
                            <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/10 text-center">
                                <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest block mb-2">% Grasa</span>
                                <div class="flex items-center justify-center gap-2 mb-1">
                                    <span class="text-on-surface-variant text-[14px] line-through">23%</span>
                                    <span class="material-symbols-outlined text-[14px] text-on-surface-variant">arrow_forward</span>
                                    <span class="font-bold text-on-surface text-[16px]">21%</span>
                                </div>
                                <span class="font-etiqueta-sm text-[#4ade80] text-[10px] bg-[#4ade80]/10 px-2 py-0.5 rounded">-2% ↓</span>
                            </div>
                            <!-- Músculo -->
                            <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/10 text-center">
                                <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest block mb-2">Músculo</span>
                                <div class="flex items-center justify-center gap-2 mb-1">
                                    <span class="text-on-surface-variant text-[14px] line-through">51%</span>
                                    <span class="material-symbols-outlined text-[14px] text-on-surface-variant">arrow_forward</span>
                                    <span class="font-bold text-on-surface text-[16px]">53%</span>
                                </div>
                                <span class="font-etiqueta-sm text-primary text-[10px] bg-primary/10 px-2 py-0.5 rounded">+2% ↑</span>
                            </div>
                            <!-- Cintura -->
                            <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/10 text-center">
                                <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest block mb-2">Cintura</span>
                                <div class="flex items-center justify-center gap-2 mb-1">
                                    <span class="text-on-surface-variant text-[14px] line-through">88</span>
                                    <span class="material-symbols-outlined text-[14px] text-on-surface-variant">arrow_forward</span>
                                    <span class="font-bold text-on-surface text-[16px]">85</span>
                                </div>
                                <span class="font-etiqueta-sm text-[#4ade80] text-[10px] bg-[#4ade80]/10 px-2 py-0.5 rounded">-3 cm ↓</span>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico Asistencia vs Progreso -->
                    <div class="glass-card rounded-[24px] p-6 shadow-xl border border-outline-variant/10">
                        <h3 class="font-titular-md text-[18px] text-on-surface mb-4" style="font-family: 'Montserrat', sans-serif;">Impacto de Asistencia vs Grasa</h3>
                        <div class="w-full h-[180px] relative">
                            <canvas id="iaChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Columna IA y Acciones -->
                <div class="lg:col-span-5 flex flex-col gap-6">
                    
                    <!-- Bloque de Diagnóstico IA para Reporte Mensual (Proporcionado) -->
                    <div class="bg-surface-container p-6 rounded-2xl border border-primary/30 shadow-xl space-y-5 relative overflow-hidden h-full">
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>
                        
                        <div class="flex items-center gap-3 relative z-10">
                            <div class="w-12 h-12 rounded-xl bg-[linear-gradient(45deg,#e31b23,#ffb4ac)] flex items-center justify-center text-[#131313] shadow-[0_0_15px_rgba(227,27,35,0.4)]">
                                <span class="material-symbols-outlined">auto_awesome</span>
                            </div>
                            <div>
                                <h3 class="font-titular-md text-on-surface text-[18px]" style="font-family: 'Montserrat', sans-serif;">Análisis y Recomendaciones IA</h3>
                                <p class="text-etiqueta-sm text-on-surface-variant text-[10px] uppercase tracking-wider mt-0.5">Diagnóstico Automático</p>
                            </div>
                        </div>

                        <!-- Resumen IA -->
                        <p class="font-body-md text-on-surface text-[13px] leading-relaxed relative z-10 italic border-l-2 border-primary/50 pl-3">
                            "Kathy ha mostrado un excelente rendimiento este mes con una reducción del 2% de grasa y un aumento notable en masa muscular, correlacionado directamente con su 85% de asistencia."
                        </p>

                        <div class="grid grid-cols-1 gap-4 pt-2 relative z-10">
                            <!-- Ajuste Entreno -->
                            <div class="bg-surface-container-highest p-4 rounded-xl border border-outline-variant/10 space-y-2 hover:border-primary/30 transition-colors">
                                <span class="text-etiqueta-bold font-bold text-primary flex items-center gap-2 text-[12px] uppercase tracking-wider">
                                    <span class="material-symbols-outlined text-[16px]">fitness_center</span> Ajuste Entrenamiento
                                </span>
                                <p class="font-body-md text-on-surface-variant text-[13px]">
                                    Dado que el pliegue del tríceps descendió pero el bíceps se mantuvo, se recomienda aumentar intensidad en ejercicios de empuje (tríceps/hombro) a 10-12 repeticiones.
                                </p>
                            </div>

                            <!-- Ajuste Dieta -->
                            <div class="bg-surface-container-highest p-4 rounded-xl border border-outline-variant/10 space-y-2 hover:border-[#4ade80]/30 transition-colors">
                                <span class="text-etiqueta-bold font-bold text-[#4ade80] flex items-center gap-2 text-[12px] uppercase tracking-wider">
                                    <span class="material-symbols-outlined text-[16px]">restaurant</span> Ajuste Nutrición
                                </span>
                                <p class="font-body-md text-on-surface-variant text-[13px]">
                                    Para evitar estancamiento sin comprometer músculo, reducir 100 kcal en carbohidratos durante la cena e incrementar consumo de agua a 3L diarios.
                                </p>
                            </div>
                        </div>

                        <!-- Acciones Finales -->
                        <div class="pt-4 mt-auto border-t border-outline-variant/10 flex gap-3 relative z-10">
                            <button class="flex-1 bg-surface-container-highest hover:bg-surface-bright text-on-surface font-etiqueta-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2 text-[11px] uppercase tracking-wider border border-outline-variant/20">
                                <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span> Exportar PDF
                            </button>
                            <button class="flex-1 bg-[#25D366]/10 hover:bg-[#25D366]/20 text-[#25D366] font-etiqueta-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2 text-[11px] uppercase tracking-wider border border-[#25D366]/30">
                                <i class="fab fa-whatsapp text-[16px]"></i> Enviar Socio
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- PESTAÑA 3: FACTURACIÓN -->
        <div id="tab-facturacion" class="tab-content">
            <div class="glass-card rounded-[24px] p-6 shadow-xl border border-outline-variant/10">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-titular-md text-[20px] text-on-surface" style="font-family: 'Montserrat', sans-serif;">Recibos Emitidos</h2>
                    <div class="relative w-64">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                        <input type="text" placeholder="Buscar por N° Recibo..." class="w-full bg-surface-container text-on-surface font-body-md text-sm pl-10 pr-4 py-2.5 rounded-lg border border-outline-variant/20 focus:border-primary focus:outline-none" />
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-outline-variant/20 text-on-surface-variant font-etiqueta-bold text-[10px] uppercase tracking-widest bg-surface-container-highest">
                                <th class="py-3 px-4 font-medium rounded-tl-lg">N° Transacción</th>
                                <th class="py-3 px-4 font-medium">Cliente</th>
                                <th class="py-3 px-4 font-medium">Concepto</th>
                                <th class="py-3 px-4 font-medium">Método</th>
                                <th class="py-3 px-4 font-medium">Monto</th>
                                <th class="py-3 px-4 font-medium">Fecha</th>
                                <th class="py-3 px-4 font-medium text-right rounded-tr-lg">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="font-body-md text-on-surface text-[13px]">
                            <tr class="border-b border-outline-variant/10 hover:bg-surface-container/50 transition-colors group">
                                <td class="py-4 px-4 font-mono text-primary">#REC-10024</td>
                                <td class="py-4 px-4 font-bold">Kathy S.</td>
                                <td class="py-4 px-4 text-on-surface-variant">Renovación Mensual</td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center gap-1 text-[#60a5fa] bg-[#60a5fa]/10 px-2 py-1 rounded font-etiqueta-bold text-[10px] uppercase tracking-wider">Transf. #98231</span>
                                </td>
                                <td class="py-4 px-4 font-bold">$30.00</td>
                                <td class="py-4 px-4 text-on-surface-variant">11/08/2026</td>
                                <td class="py-4 px-4 text-right flex justify-end gap-2">
                                    <button class="bg-surface-container-highest hover:text-on-surface text-on-surface-variant p-2 rounded-lg transition-colors tooltip" title="Imprimir"><span class="material-symbols-outlined text-[16px]">print</span></button>
                                    <button class="bg-surface-container-highest hover:text-primary text-on-surface-variant p-2 rounded-lg transition-colors tooltip" title="PDF"><span class="material-symbols-outlined text-[16px]">picture_as_pdf</span></button>
                                    <button class="bg-surface-container-highest hover:text-[#25D366] text-on-surface-variant p-2 rounded-lg transition-colors tooltip" title="WhatsApp"><i class="fab fa-whatsapp text-[16px]"></i></button>
                                </td>
                            </tr>
                            <tr class="border-b border-outline-variant/10 hover:bg-surface-container/50 transition-colors group">
                                <td class="py-4 px-4 font-mono text-primary">#REC-10023</td>
                                <td class="py-4 px-4 font-bold">Marcus A.</td>
                                <td class="py-4 px-4 text-on-surface-variant">Abono Parcial</td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center gap-1 text-[#4ade80] bg-[#4ade80]/10 px-2 py-1 rounded font-etiqueta-bold text-[10px] uppercase tracking-wider">Efectivo</span>
                                </td>
                                <td class="py-4 px-4 font-bold">$15.00</td>
                                <td class="py-4 px-4 text-on-surface-variant">10/08/2026</td>
                                <td class="py-4 px-4 text-right flex justify-end gap-2">
                                    <button class="bg-surface-container-highest hover:text-on-surface text-on-surface-variant p-2 rounded-lg transition-colors tooltip" title="Imprimir"><span class="material-symbols-outlined text-[16px]">print</span></button>
                                    <button class="bg-surface-container-highest hover:text-primary text-on-surface-variant p-2 rounded-lg transition-colors tooltip" title="PDF"><span class="material-symbols-outlined text-[16px]">picture_as_pdf</span></button>
                                    <button class="bg-surface-container-highest hover:text-[#25D366] text-on-surface-variant p-2 rounded-lg transition-colors tooltip" title="WhatsApp"><i class="fab fa-whatsapp text-[16px]"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tab Switcher Logic
    function switchTab(tabId) {
        // Ocultar todos los tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        // Quitar estado activo de botones
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('active');
            el.classList.replace('bg-primary', 'bg-surface-container-high');
            el.classList.remove('text-on-primary', 'border-primary');
            el.classList.add('text-on-surface-variant');
        });

        // Mostrar tab objetivo
        document.getElementById(tabId).classList.add('active');
        
        // Boton clickeado a activo
        const btn = event.currentTarget;
        btn.classList.add('active');
        btn.classList.replace('text-on-surface-variant', 'text-on-primary');
        btn.style.backgroundColor = '#e31b23';
        btn.style.borderColor = '#e31b23';
        
        // Re-render charts si el tab contiene un chart (por bugs de tamaño con display none)
        if(tabId === 'tab-ia' && window.iaChartInstance) {
            window.iaChartInstance.resize();
        }
    }

    // Fix manual de colores base para los tabs inactivos
    document.querySelectorAll('.tab-btn:not(.active)').forEach(el => {
        el.style.backgroundColor = '';
        el.style.borderColor = '';
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Chart para Tab IA
        Chart.defaults.color = '#9ca3af';
        Chart.defaults.font.family = 'Inter, sans-serif';

        const ctx = document.getElementById('iaChart');
        if(ctx) {
            window.iaChartInstance = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
                    datasets: [
                        {
                            type: 'line',
                            label: '% Grasa',
                            data: [23, 22.5, 21.8, 21.0],
                            borderColor: '#e31b23',
                            borderWidth: 3,
                            pointBackgroundColor: '#131313',
                            pointBorderColor: '#e31b23',
                            yAxisID: 'y'
                        },
                        {
                            type: 'bar',
                            label: 'Días Asistidos',
                            data: [5, 4, 6, 5],
                            backgroundColor: 'rgba(255, 255, 255, 0.1)',
                            borderRadius: 4,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 10 } }
                    },
                    scales: {
                        y: { display: false, position: 'left' },
                        y1: { display: false, position: 'right', beginAtZero: true }
                    }
                }
            });
        }
    });
</script>
@endpush
