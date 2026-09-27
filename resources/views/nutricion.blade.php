@extends('layouts.admin')

@section('title', 'Plan Nutricional - Ares Gym')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .glass-card {
        background: rgba(31, 31, 31, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .custom-range {
        -webkit-appearance: none;
        appearance: none;
        background: transparent;
        cursor: pointer;
    }
    .custom-range::-webkit-slider-runnable-track {
        height: 8px;
        border-radius: 4px;
    }
    .custom-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        height: 16px;
        width: 16px;
        border-radius: 50%;
        background: #ffffff;
        margin-top: -4px;
        border: 2px solid #131313;
        box-shadow: 0 0 5px rgba(0,0,0,0.5);
    }
    .range-primary::-webkit-slider-runnable-track { background: #e31b23; }
    .range-secondary::-webkit-slider-runnable-track { background: #e2e2e2; }
    .range-tertiary::-webkit-slider-runnable-track { background: #facc15; }
    
    .food-row:hover .drag-handle { color: #e31b23; }
</style>
@endpush

@section('content')
<div class="flex flex-col w-full animate-on-load gap-6 pb-12">
    
    <!-- HEADER: Contexto del Socio y Alertas -->
    <div class="glass-card rounded-[24px] p-6 shadow-xl border border-outline-variant/10 flex flex-col xl:flex-row justify-between items-center gap-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-full bg-gradient-to-l from-primary/10 to-transparent pointer-events-none"></div>
        
        <div class="flex items-center gap-6 z-10 w-full xl:w-auto">
            <div class="relative w-16 h-16">
                <img src="https://ui-avatars.com/api/?name=Kathy&background=1f1f1f&color=e2e2e2&size=128" alt="Kathy" class="w-full h-full rounded-full border-2 border-primary/50">
            </div>
            <div>
                <h2 class="font-titular-md text-[24px] text-on-surface leading-tight" style="font-family: 'Montserrat', sans-serif;">Kathy S.</h2>
                <div class="flex items-center gap-4 mt-1 font-body-md text-on-surface-variant text-sm">
                    <span><strong class="text-on-surface">76.65</strong> kg</span>
                    <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                    <span><strong class="text-on-surface">21%</strong> Grasa</span>
                    <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                    <span class="font-etiqueta-sm text-[10px] text-on-surface bg-surface-container px-2 py-1 rounded">Última toma: 11/08</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 z-10 w-full xl:w-auto overflow-x-auto pb-2 xl:pb-0">
            <!-- Botón Inteligencia Artificial -->
            <button class="bg-[linear-gradient(45deg,#e31b23,#ffb4ac)] text-[#131313] px-6 py-3 rounded-xl font-etiqueta-bold text-[12px] uppercase tracking-wider shadow-[0_0_15px_rgba(227,27,35,0.4)] hover:scale-105 transition-transform flex items-center gap-2 whitespace-nowrap">
                <span class="material-symbols-outlined text-[16px]">auto_awesome</span> Auto-Fill IA
            </button>
            
            <div class="w-px h-8 bg-outline-variant/20"></div>
            
            <!-- Acciones -->
            <button class="bg-surface-container-highest hover:bg-surface-bright text-on-surface px-5 py-3 rounded-xl font-etiqueta-bold text-[12px] uppercase tracking-wider transition-colors flex items-center gap-2 whitespace-nowrap">
                <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span> Exportar PDF
            </button>
            <button class="bg-[#25D366]/10 hover:bg-[#25D366]/20 text-[#25D366] border border-[#25D366]/30 px-5 py-3 rounded-xl font-etiqueta-bold text-[12px] uppercase tracking-wider transition-colors flex items-center gap-2 whitespace-nowrap">
                <i class="fab fa-whatsapp text-[16px]"></i> Enviar a Kathy
            </button>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        
        <!-- COLUMNA IZQUIERDA (35%): BLOQUE 1 - Calculadora de Macros -->
        <div class="xl:col-span-4 flex flex-col gap-6">
            <div class="glass-card rounded-[24px] p-6 lg:p-8 flex flex-col gap-8 shadow-xl border-t border-t-primary/30 h-full">
                
                <div>
                    <h2 class="font-titular-md text-[20px] text-on-surface mb-1" style="font-family: 'Montserrat', sans-serif;">Objetivo Nutricional</h2>
                    <p class="font-body-md text-on-surface-variant text-sm mb-6">Calculadora de calorías y distribución.</p>
                    
                    <!-- Objetivo Calórico Editable -->
                    <div class="flex flex-col gap-2 mb-8">
                        <label class="font-etiqueta-sm text-on-surface-variant uppercase tracking-widest text-[10px]">Calorías Totales (Kcal/día)</label>
                        <div class="flex items-center gap-3">
                            <input type="number" value="2450" class="font-titular-xl text-[36px] text-on-surface bg-surface-container-lowest px-4 py-2 rounded-xl w-full border border-outline-variant/20 focus:border-primary focus:outline-none text-center shadow-inner" style="font-family: 'Montserrat', sans-serif; font-weight: 700;" />
                            <div class="flex flex-col gap-1">
                                <button class="bg-surface-container-high hover:bg-surface-bright text-on-surface-variant w-8 h-8 rounded-lg flex items-center justify-center transition-colors"><span class="material-symbols-outlined text-[18px]">add</span></button>
                                <button class="bg-surface-container-high hover:bg-surface-bright text-on-surface-variant w-8 h-8 rounded-lg flex items-center justify-center transition-colors"><span class="material-symbols-outlined text-[18px]">remove</span></button>
                            </div>
                        </div>
                    </div>

                    <!-- Sliders Dinámicos -->
                    <div class="flex flex-col gap-6">
                        <!-- Proteína -->
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between font-etiqueta-bold text-[12px] uppercase">
                                <span class="text-primary tracking-wider">Proteína (40%)</span>
                                <span class="text-on-surface font-mono">245g <span class="text-on-surface-variant text-[10px] lowercase font-sans">(3.1g/kg)</span></span>
                            </div>
                            <input type="range" min="10" max="60" value="40" class="w-full custom-range range-primary" />
                        </div>
                        
                        <!-- Carbohidratos -->
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between font-etiqueta-bold text-[12px] uppercase">
                                <span class="text-on-surface tracking-wider">Carbohidratos (40%)</span>
                                <span class="text-on-surface font-mono">245g</span>
                            </div>
                            <input type="range" min="10" max="60" value="40" class="w-full custom-range range-secondary" />
                        </div>

                        <!-- Grasas -->
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between font-etiqueta-bold text-[12px] uppercase">
                                <span class="text-[#facc15] tracking-wider">Grasas (20%)</span>
                                <span class="text-on-surface font-mono">54g</span>
                            </div>
                            <input type="range" min="10" max="40" value="20" class="w-full custom-range range-tertiary" />
                        </div>
                    </div>
                </div>

                <!-- Resumen Status Bar -->
                <div class="mt-auto bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-xl p-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-[#4ade80]">check_circle</span>
                    <div>
                        <p class="font-etiqueta-bold text-[12px] text-[#4ade80] uppercase tracking-wider">Distribución Correcta</p>
                        <p class="font-body-md text-on-surface-variant text-[11px]">Los macros suman 100% (2450 kcal).</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA (65%): BLOQUE 3 - Constructor de Comidas -->
        <div class="xl:col-span-8 flex flex-col gap-6">
            
            <div class="glass-card rounded-[24px] p-6 lg:p-8 flex flex-col gap-6 shadow-xl border border-outline-variant/10">
                <div class="flex justify-between items-center border-b border-outline-variant/10 pb-4">
                    <h2 class="font-titular-md text-[20px] text-on-surface" style="font-family: 'Montserrat', sans-serif;">Planificador Diario (Menú)</h2>
                    <button class="text-primary font-etiqueta-bold text-[12px] uppercase tracking-wider hover:underline flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">add_circle</span> Añadir Comida</button>
                </div>

                <!-- COMIDA 1: DESAYUNO -->
                <div class="bg-surface-container-low border border-outline-variant/20 rounded-2xl overflow-hidden">
                    <!-- Header Acordeon -->
                    <div class="bg-surface-container p-4 flex justify-between items-center cursor-pointer hover:bg-surface-container-high transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-on-surface-variant cursor-grab active:cursor-grabbing">drag_handle</span>
                            <div>
                                <h3 class="font-titular-md text-[18px] text-on-surface leading-tight">Desayuno</h3>
                                <p class="font-etiqueta-sm text-on-surface-variant text-[10px] uppercase tracking-widest mt-1">08:00 AM</p>
                            </div>
                        </div>
                        <div class="hidden md:flex gap-4 text-label-sm font-mono text-[12px]">
                            <span class="text-primary bg-primary/10 px-2 py-1 rounded">660 kcal</span>
                            <span class="text-on-surface px-2 py-1">P: 58g</span>
                            <span class="text-on-surface px-2 py-1">C: 51g</span>
                            <span class="text-[#facc15] px-2 py-1">G: 25g</span>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant">expand_less</span>
                    </div>
                    
                    <!-- Lista de Alimentos -->
                    <div class="p-4 flex flex-col gap-3">
                        
                        <!-- Item 1 -->
                        <div class="food-row bg-surface-container-lowest p-3 rounded-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-3 border border-transparent hover:border-primary/40 transition-colors">
                            <div class="flex items-center gap-3 w-full md:w-auto flex-1">
                                <span class="material-symbols-outlined text-on-surface-variant cursor-grab drag-handle transition-colors">drag_indicator</span>
                                <input type="text" value="Huevos enteros (Hervidos)" class="bg-transparent text-on-surface font-body-md w-full focus:outline-none focus:border-b border-primary" />
                            </div>
                            <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end mt-2 md:mt-0">
                                <div class="flex items-center gap-2">
                                    <input type="number" value="3" class="w-16 bg-surface-container-high text-center text-on-surface font-mono text-[14px] py-1 rounded border border-outline-variant/20 focus:border-primary focus:outline-none" />
                                    <span class="text-etiqueta-sm text-on-surface-variant uppercase tracking-wider text-[10px]">Unid.</span>
                                </div>
                                <div class="flex gap-3 text-label-sm text-on-surface-variant font-mono text-[11px]">
                                    <span class="text-primary font-bold">234 kcal</span>
                                    <span>18g P</span>
                                    <span>2g C</span>
                                    <span>15g G</span>
                                </div>
                                <button class="text-on-surface-variant hover:text-error transition-colors p-1"><span class="material-symbols-outlined text-[18px]">close</span></button>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="food-row bg-surface-container-lowest p-3 rounded-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-3 border border-transparent hover:border-primary/40 transition-colors">
                            <div class="flex items-center gap-3 w-full md:w-auto flex-1">
                                <span class="material-symbols-outlined text-on-surface-variant cursor-grab drag-handle transition-colors">drag_indicator</span>
                                <input type="text" value="Avena en hojuelas" class="bg-transparent text-on-surface font-body-md w-full focus:outline-none focus:border-b border-primary" />
                            </div>
                            <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end mt-2 md:mt-0">
                                <div class="flex items-center gap-2">
                                    <input type="number" value="60" class="w-16 bg-surface-container-high text-center text-on-surface font-mono text-[14px] py-1 rounded border border-outline-variant/20 focus:border-primary focus:outline-none" />
                                    <span class="text-etiqueta-sm text-on-surface-variant uppercase tracking-wider text-[10px]">g</span>
                                </div>
                                <div class="flex gap-3 text-label-sm text-on-surface-variant font-mono text-[11px]">
                                    <span class="text-primary font-bold">227 kcal</span>
                                    <span>10g P</span>
                                    <span>40g C</span>
                                    <span>4g G</span>
                                </div>
                                <button class="text-on-surface-variant hover:text-error transition-colors p-1"><span class="material-symbols-outlined text-[18px]">close</span></button>
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div class="food-row bg-surface-container-lowest p-3 rounded-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-3 border border-transparent hover:border-primary/40 transition-colors">
                            <div class="flex items-center gap-3 w-full md:w-auto flex-1">
                                <span class="material-symbols-outlined text-on-surface-variant cursor-grab drag-handle transition-colors">drag_indicator</span>
                                <input type="text" value="Proteína Whey Isolate" class="bg-transparent text-on-surface font-body-md w-full focus:outline-none focus:border-b border-primary" />
                            </div>
                            <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end mt-2 md:mt-0">
                                <div class="flex items-center gap-2">
                                    <input type="number" value="30" class="w-16 bg-surface-container-high text-center text-on-surface font-mono text-[14px] py-1 rounded border border-outline-variant/20 focus:border-primary focus:outline-none" />
                                    <span class="text-etiqueta-sm text-on-surface-variant uppercase tracking-wider text-[10px]">g</span>
                                </div>
                                <div class="flex gap-3 text-label-sm text-on-surface-variant font-mono text-[11px]">
                                    <span class="text-primary font-bold">110 kcal</span>
                                    <span>25g P</span>
                                    <span>1g C</span>
                                    <span>0g G</span>
                                </div>
                                <button class="text-on-surface-variant hover:text-error transition-colors p-1"><span class="material-symbols-outlined text-[18px]">close</span></button>
                            </div>
                        </div>

                        <!-- Add new ingredient -->
                        <div class="mt-2 relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                            <input type="text" class="w-full bg-surface-container-high border border-outline-variant/20 text-on-surface font-body-md text-sm pl-10 pr-4 py-3 rounded-lg focus:outline-none focus:border-primary/50" placeholder="Buscar alimento (ej. Pechuga de Pollo)...">
                        </div>
                    </div>
                </div>

                <!-- COMIDA 2: ALMUERZO -->
                <div class="bg-surface-container-low border border-outline-variant/20 rounded-2xl overflow-hidden opacity-70 hover:opacity-100 transition-opacity">
                    <!-- Header Acordeon -->
                    <div class="bg-surface-container p-4 flex justify-between items-center cursor-pointer hover:bg-surface-container-high transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-on-surface-variant">drag_handle</span>
                            <div>
                                <h3 class="font-titular-md text-[18px] text-on-surface leading-tight">Almuerzo</h3>
                                <p class="font-etiqueta-sm text-on-surface-variant text-[10px] uppercase tracking-widest mt-1">13:30 PM</p>
                            </div>
                        </div>
                        <div class="hidden md:flex gap-4 text-label-sm font-mono text-[12px]">
                            <span class="text-primary bg-primary/10 px-2 py-1 rounded">820 kcal</span>
                            <span class="text-on-surface px-2 py-1">P: 75g</span>
                            <span class="text-on-surface px-2 py-1">C: 90g</span>
                            <span class="text-[#facc15] px-2 py-1">G: 18g</span>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant">expand_more</span>
                    </div>
                </div>

                <!-- COMIDA 3: CENA -->
                <div class="bg-surface-container-low border border-outline-variant/20 rounded-2xl overflow-hidden opacity-70 hover:opacity-100 transition-opacity">
                    <!-- Header Acordeon -->
                    <div class="bg-surface-container p-4 flex justify-between items-center cursor-pointer hover:bg-surface-container-high transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-on-surface-variant">drag_handle</span>
                            <div>
                                <h3 class="font-titular-md text-[18px] text-on-surface leading-tight">Cena</h3>
                                <p class="font-etiqueta-sm text-on-surface-variant text-[10px] uppercase tracking-widest mt-1">19:30 PM</p>
                            </div>
                        </div>
                        <div class="hidden md:flex gap-4 text-label-sm font-mono text-[12px]">
                            <span class="text-outline-variant bg-surface-container-highest px-2 py-1 rounded">0 kcal</span>
                            <span class="text-on-surface-variant px-2 py-1">P: 0g</span>
                            <span class="text-on-surface-variant px-2 py-1">C: 0g</span>
                            <span class="text-on-surface-variant px-2 py-1">G: 0g</span>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant">expand_more</span>
                    </div>
                </div>
            </div>

            <!-- BLOQUE 4: Panel de Reemplazos y Notas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Sustituciones -->
                <div class="glass-card rounded-[24px] p-6 shadow-xl border border-outline-variant/10">
                    <h3 class="font-titular-md text-[16px] text-on-surface mb-4 flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[18px]">swap_horiz</span> Reglas de Sustitución</h3>
                    <div class="flex flex-col gap-3">
                        <div class="bg-surface-container-lowest p-3 rounded-lg border border-outline-variant/10 flex flex-col gap-1">
                            <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest">Proteínas</span>
                            <p class="font-body-md text-on-surface text-sm">100g Pollo = 120g Tilapia = 100g Lomo Magro</p>
                        </div>
                        <div class="bg-surface-container-lowest p-3 rounded-lg border border-outline-variant/10 flex flex-col gap-1">
                            <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest">Carbohidratos</span>
                            <p class="font-body-md text-on-surface text-sm">100g Arroz = 120g Papa = 80g Avena</p>
                        </div>
                        <button class="text-primary font-etiqueta-bold text-[10px] uppercase tracking-wider text-left mt-1 hover:underline">+ Añadir regla</button>
                    </div>
                </div>

                <!-- Suplementación -->
                <div class="glass-card rounded-[24px] p-6 shadow-xl border border-outline-variant/10">
                    <h3 class="font-titular-md text-[16px] text-on-surface mb-4 flex items-center gap-2"><span class="material-symbols-outlined text-[#facc15] text-[18px]">medication</span> Suplementación</h3>
                    <div class="flex flex-col gap-3">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" checked class="w-5 h-5 rounded border-outline-variant/20 bg-surface-container-lowest text-primary focus:ring-primary focus:ring-offset-surface-container mt-0.5">
                            <div class="flex flex-col">
                                <span class="font-body-md text-on-surface font-bold group-hover:text-primary transition-colors">Creatina Monohidratada</span>
                                <span class="font-etiqueta-sm text-on-surface-variant text-[11px]">5g diarios (Post-entreno o desayuno)</span>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" checked class="w-5 h-5 rounded border-outline-variant/20 bg-surface-container-lowest text-primary focus:ring-primary focus:ring-offset-surface-container mt-0.5">
                            <div class="flex flex-col">
                                <span class="font-body-md text-on-surface font-bold group-hover:text-primary transition-colors">Whey Protein Isolate</span>
                                <span class="font-etiqueta-sm text-on-surface-variant text-[11px]">1 Scoop (30g) según necesidad de macros</span>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-5 h-5 rounded border-outline-variant/20 bg-surface-container-lowest text-primary focus:ring-primary focus:ring-offset-surface-container mt-0.5">
                            <div class="flex flex-col">
                                <span class="font-body-md text-on-surface font-bold group-hover:text-primary transition-colors">Multivitamínico</span>
                                <span class="font-etiqueta-sm text-on-surface-variant text-[11px]">1 Tableta con la primera comida</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
