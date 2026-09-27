<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Panel - Ares Gym')</title>
    <style>
        @layer base{
            html,body{margin:0;padding:0;}
            body{overscroll-behavior:none;}
            main>:first-child{margin-top:0!important;}
            main>:last-child{margin-bottom:0!important;}
        }
        ::-webkit-scrollbar{display:none;}
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            display: block;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(227, 27, 35, 0.5); /* primary color */
        }
        
        /* Entrance Animations base classes */
        .animate-on-load {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config={darkMode:"class",theme:{extend:{"colors":{"tertiary":"#c8c6c5","surface-tint":"#ffb4ac","error-container":"#93000a","on-error":"#690005","on-primary-container":"#fff9f8","primary-container":"#e31b23","on-secondary-fixed":"#1a1c1c","on-primary-fixed-variant":"#93000d","surface-container-low":"#080808","inverse-surface":"#e2e2e2","on-tertiary":"#313030","surface-container-high":"#151515","error":"#ffb4ab","on-secondary-fixed-variant":"#454747","on-surface":"#e2e2e2","surface-container-lowest":"#000000","secondary-fixed":"#e2e2e2","surface":"#000000","surface-bright":"#1a1a1a","outline":"#ae8883","on-secondary-container":"#b4b5b5","surface-container":"#0a0a0a","surface-variant":"#353535","on-background":"#e2e2e2","tertiary-fixed":"#e5e2e1","outline-variant":"#5d3f3c","on-tertiary-fixed":"#1c1b1b","tertiary-container":"#747373","on-primary-fixed":"#410002","primary":"#ffb4ac","secondary-container":"#454747","on-tertiary-fixed-variant":"#474746","on-secondary":"#2f3131","secondary":"#c6c6c7","inverse-primary":"#c00015","surface-container-highest":"#1a1a1a","on-error-container":"#ffdad6","on-primary":"#690006","background":"#000000","on-surface-variant":"#e7bdb8","primary-fixed":"#ffdad6","surface-dim":"#000000","inverse-on-surface":"#303030","on-tertiary-container":"#fdfaf9","tertiary-fixed-dim":"#c8c6c5","secondary-fixed-dim":"#c6c6c7","primary-fixed-dim":"#ffb4ac"},"borderRadius":{"DEFAULT":"0.125rem","lg":"0.25rem","xl":"0.5rem","full":"0.75rem"},"spacing":{"unit":"8px","margin-sm":"16px","margin-lg":"64px","gutter":"16px","container-padding":"24px","margin-md":"32px"},"fontFamily":{"headline-xl":["Montserrat"],"body-lg":["Inter"],"headline-lg-mobile":["Montserrat"],"headline-lg":["Montserrat"],"label-md":["Inter"],"label-sm":["Inter"],"body-md":["Inter"],"headline-md":["Montserrat"],"titular-xl":["Montserrat"],"titular-md":["Montserrat"],"etiqueta-bold":["Inter"],"etiqueta-sm":["Inter"],"cuerpo-md":["Inter"]},"fontSize":{"headline-xl":["48px",{"lineHeight":"56px","letterSpacing":"-0.02em","fontWeight":"800"}],"body-lg":["18px",{"lineHeight":"28px","fontWeight":"400"}],"headline-lg-mobile":["28px",{"lineHeight":"36px","fontWeight":"700"}],"headline-lg":["32px",{"lineHeight":"40px","letterSpacing":"-0.01em","fontWeight":"700"}],"label-md":["14px",{"lineHeight":"20px","letterSpacing":"0.05em","fontWeight":"500"}],"label-sm":["12px",{"lineHeight":"16px","letterSpacing":"0.08em","fontWeight":"500"}],"body-md":["16px",{"lineHeight":"24px","fontWeight":"400"}],"headline-md":["24px",{"lineHeight":"32px","letterSpacing":"-0.01em","fontWeight":"700"}],"titular-xl":["36px",{"lineHeight":"44px","fontWeight":"800"}],"titular-md":["20px",{"lineHeight":"28px","fontWeight":"700"}],"etiqueta-bold":["14px",{"lineHeight":"20px","fontWeight":"700"}],"etiqueta-sm":["12px",{"lineHeight":"16px","fontWeight":"500"}],"cuerpo-md":["14px",{"lineHeight":"20px","fontWeight":"400"}]}}}}
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>
<body class="bg-surface text-on-surface font-body-md antialiased overflow-x-hidden selection:bg-primary/30 selection:text-primary">
    
    <!-- Global Page Loader -->
    <div id="page-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-[#131313]/90 backdrop-blur-lg transition-opacity duration-500" style="opacity: 1;">
        <div class="bg-surface-container p-10 rounded-3xl shadow-2xl border border-primary/20 flex flex-col items-center gap-6 relative overflow-hidden">
            <!-- Background faint text -->
            <div class="absolute inset-0 opacity-[0.03] flex items-center justify-center pointer-events-none">
                <span class="font-titular-xl text-[120px] font-bold tracking-tighter">ARES</span>
            </div>
            
            <h2 class="font-titular-xl text-[36px] text-on-surface uppercase tracking-widest relative z-10" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">
                ARES <span class="text-primary">GYM</span>
            </h2>
            
            <div class="flex items-center gap-3 relative z-10">
                <span class="font-etiqueta-bold text-[14px] text-on-surface-variant uppercase tracking-widest">Cargando sistema</span>
                <div class="flex gap-1.5 mt-1">
                    <span class="w-2 h-2 bg-primary rounded-full animate-bounce" style="animation-delay: 0s;"></span>
                    <span class="w-2 h-2 bg-primary rounded-full animate-bounce" style="animation-delay: 0.15s;"></span>
                    <span class="w-2 h-2 bg-primary rounded-full animate-bounce" style="animation-delay: 0.3s;"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Navegación -->
    
      <!-- Overlay para móviles -->
      <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-30 hidden xl:hidden backdrop-blur-sm transition-opacity opacity-0" onclick="toggleSidebar()"></div>
      
      <!-- Sidebar Navegación -->
      <aside id="sidebar" class="fixed inset-y-0 left-0 w-72 bg-surface-container border-r border-outline-variant/10 h-full flex flex-col shadow-2xl z-40 transition-transform duration-300 -translate-x-full xl:translate-x-0">
          <!-- Close button on mobile -->
          <button onclick="toggleSidebar()" class="absolute top-4 right-4 xl:hidden w-10 h-10 flex items-center justify-center bg-surface-container-high rounded-full border border-outline-variant/10 text-on-surface-variant hover:text-primary transition-colors">
              <span class="material-symbols-outlined">close</span>
          </button>

        <div class="p-margin-lg flex items-center justify-center border-b border-outline-variant/10 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent opacity-50"></div>
            <h1 class="font-titular-xl text-[32px] text-primary tracking-tighter uppercase relative z-10" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">
                Ares<span class="text-on-surface">Gym</span>
            </h1>
        </div>
        <nav class="flex-1 overflow-y-auto py-margin-md px-margin-sm space-y-1 custom-scroll">
            <a href="{{ route('dashboard') }}" aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}" class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('dashboard') ? 'bg-primary-container text-on-primary-container shadow-[0_0_15px_rgba(227,27,35,0.3)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined mr-4">dashboard</span>
                <span class="font-etiqueta-bold text-[14px] uppercase tracking-wider">Dashboard</span>
            </a>
            <div class="pt-4 pb-2 px-4">
                <p class="font-etiqueta-sm text-[10px] uppercase tracking-widest text-outline-variant">Gestión</p>
            </div>
            <a href="{{ route('clientes.index') }}" aria-current="{{ request()->routeIs('clientes.*') ? 'page' : 'false' }}" class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('clientes.*') ? 'bg-primary-container text-on-primary-container shadow-[0_0_15px_rgba(227,27,35,0.3)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined mr-4">group</span>
                <span class="font-etiqueta-bold text-[14px] uppercase tracking-wider">Clientes</span>
            </a>
            <a href="{{ route('memberships') }}" aria-current="{{ request()->routeIs('memberships') ? 'page' : 'false' }}" class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('memberships') ? 'bg-primary-container text-on-primary-container shadow-[0_0_15px_rgba(227,27,35,0.3)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined mr-4">card_membership</span>
                <span class="font-etiqueta-bold text-[14px] uppercase tracking-wider">Membresías</span>
            </a>
            @php
                $isPagosActive = request()->routeIs('pagos*') || request()->routeIs('cash-shifts*');
                $isCierresActive = (request()->routeIs('pagos') && request('tab') === 'cierres') || request()->routeIs('cash-shifts.*');
                $isTerminalActive = request()->routeIs('pagos') && request('tab') !== 'cierres';
            @endphp
            <!-- Menú Acordeón: Pagos & Subsecciones -->
            <div class="space-y-1">
                <button type="button" 
                        onclick="togglePagosAccordion()" 
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all group {{ $isPagosActive ? 'bg-primary-container text-on-primary-container shadow-[0_0_15px_rgba(227,27,35,0.3)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}"
                        aria-expanded="{{ $isPagosActive ? 'true' : 'false' }}">
                    <div class="flex items-center">
                        <span class="material-symbols-outlined mr-4">payments</span>
                        <span class="font-etiqueta-bold text-[14px] uppercase tracking-wider">Pagos</span>
                    </div>
                    <span id="pagos-chevron" class="material-symbols-outlined text-[18px] transition-transform duration-300 {{ $isPagosActive ? 'rotate-180 text-on-primary-container' : 'text-on-surface-variant group-hover:text-on-surface' }}">
                        expand_more
                    </span>
                </button>
                
                <div id="pagos-submenu" class="pl-11 pr-2 py-1 space-y-1 transition-all duration-300 {{ $isPagosActive ? 'block' : 'hidden' }}">
                    <a href="{{ route('pagos') }}" 
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-etiqueta-bold uppercase tracking-wider transition-colors {{ $isTerminalActive ? 'text-primary font-bold bg-primary/10' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high/50' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $isTerminalActive ? 'bg-primary' : 'bg-outline-variant' }}"></span>
                        <span>Terminal de Cobros</span>
                    </a>
                    <a href="{{ route('pagos', ['tab' => 'cierres']) }}" 
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-etiqueta-bold uppercase tracking-wider transition-colors {{ $isCierresActive ? 'text-primary font-bold bg-primary/10' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high/50' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $isCierresActive ? 'bg-primary' : 'bg-outline-variant' }}"></span>
                        <span>Cierres de Caja</span>
                    </a>
                </div>
            </div>
            <a href="{{ route('medidas') }}" aria-current="{{ request()->routeIs('medidas') ? 'page' : 'false' }}" class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('medidas') ? 'bg-primary-container text-on-primary-container shadow-[0_0_15px_rgba(227,27,35,0.3)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined mr-4">straighten</span>
                <span class="font-etiqueta-bold text-[14px] uppercase tracking-wider">Medidas</span>
            </a>
            <a href="{{ route('nutricion') }}" aria-current="{{ request()->routeIs('nutricion') ? 'page' : 'false' }}" class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('nutricion') ? 'bg-primary-container text-on-primary-container shadow-[0_0_15px_rgba(227,27,35,0.3)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined mr-4">restaurant</span>
                <span class="font-etiqueta-bold text-[14px] uppercase tracking-wider">Nutrición</span>
            </a>
            <a href="{{ route('asistencia') }}" aria-current="{{ request()->routeIs('asistencia') ? 'page' : 'false' }}" class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('asistencia') ? 'bg-primary-container text-on-primary-container shadow-[0_0_15px_rgba(227,27,35,0.3)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined mr-4">calendar_today</span>
                <span class="font-etiqueta-bold text-[14px] uppercase tracking-wider">Asistencia</span>
            </a>
            <a href="{{ route('reportes') }}" aria-current="{{ request()->routeIs('reportes') ? 'page' : 'false' }}" class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('reportes') ? 'bg-primary-container text-on-primary-container shadow-[0_0_15px_rgba(227,27,35,0.3)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined mr-4">analytics</span>
                <span class="font-etiqueta-bold text-[14px] uppercase tracking-wider">Reportes</span>
            </a>
        </nav>
        <div class="p-margin-sm border-t border-outline-variant/10">
            <a class="flex items-center px-4 py-3 rounded-xl text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors" href="#">
                <span class="material-symbols-outlined mr-4">settings</span>
                <span class="font-etiqueta-bold text-[14px] uppercase tracking-wider">Ajustes</span>
            </a>
        </div>
    </aside>

    <div class="xl:pl-72 flex flex-col min-h-screen relative">
        <!-- Topbar (Estilo Minimalista Premium) -->
        <header class="h-24 border-b border-outline-variant/5 flex items-center justify-between px-4 xl:px-10 sticky top-0 bg-surface/70 backdrop-blur-2xl z-20">
            
            <!-- Izquierda: Hamburger & Status -->
            <div class="flex items-center gap-4 w-auto xl:w-1/3" id="network-status-container">
                <!-- Hamburger Button (Mobile only) -->
                <button onclick="toggleSidebar()" class="xl:hidden w-12 h-12 flex items-center justify-center bg-surface-container border border-outline-variant/10 rounded-xl text-primary shadow-lg z-50">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                
                <div class="hidden md:flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant/10 flex items-center justify-center relative">
                        <div id="network-ping" class="absolute inset-0 rounded-full border border-green-500/30 animate-ping opacity-20"></div>
                        <span id="network-icon" class="material-symbols-outlined text-green-500 text-[20px]">monitor_heart</span>
                    </div>
                    <div class="flex flex-col">
                        <span id="network-label" class="font-etiqueta-bold text-[9px] tracking-[0.2em] uppercase text-green-500 mb-0.5 transition-colors">Aforo Actual</span>
                        <span id="network-text" class="font-titular-md text-[14px] uppercase text-on-surface tracking-wider transition-colors">Sistema Activo</span>
                    </div>
                </div>
            </div>
            
            <!-- Centro: Fecha y Hora (Hidden on mobile) -->
            <div class="hidden md:flex items-center justify-center w-auto xl:w-1/3">
                <div class="flex items-center gap-8 text-on-surface-variant bg-surface-container-lowest/30 px-8 py-2.5 rounded-full border border-outline-variant/5">
                    <div class="flex flex-col items-center">
                        <span class="font-etiqueta-bold text-[8px] uppercase tracking-[0.2em] text-outline-variant mb-1">Fecha</span>
                        <span class="font-titular-md text-[13px] uppercase text-on-surface tracking-wider">{{ \Carbon\Carbon::now()->translatedFormat('d M, Y') }}</span>
                    </div>
                    <div class="w-px h-8 bg-gradient-to-b from-transparent via-outline-variant/20 to-transparent"></div>
                    <div class="flex flex-col items-center">
                        <span class="font-etiqueta-bold text-[8px] uppercase tracking-[0.2em] text-primary mb-1">Hora Local</span>
                        <span id="global-live-clock" class="font-mono text-[15px] text-primary font-bold tracking-widest">{{ \Carbon\Carbon::now()->format('H:i:s') }}</span>
                    </div>
                </div>
            </div>

            <!-- Derecha: Notificaciones y Check In -->
            <div class="flex items-center justify-end gap-3 xl:gap-6 w-auto xl:w-1/3">
                <button class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant hover:text-primary hover:bg-surface-bright transition-all relative group" title="{{ $expiringCount ?? 0 }} por vencer, {{ $expiredCount ?? 0 }} pendientes">
                    <span class="material-symbols-outlined text-[20px]">notifications</span>
                    @if(isset($notificationsCount) && $notificationsCount > 0)
                        <span class="absolute top-0 right-0 w-4 h-4 bg-primary text-on-primary text-[9px] font-bold rounded-full border-2 border-surface flex items-center justify-center group-hover:scale-110 transition-transform">
                            {{ $notificationsCount > 9 ? '9+' : $notificationsCount }}
                        </span>
                    @endif
                </button>
                
                <button onclick="document.getElementById('quick-sale-modal').classList.remove('hidden', 'opacity-0'); document.getElementById('quick-sale-content').classList.remove('scale-95'); document.getElementById('quick-sale-content').classList.add('scale-100');" class="bg-surface-container-high hover:bg-surface-container-highest text-on-surface px-4 xl:px-6 py-3 rounded-full font-etiqueta-bold text-[11px] uppercase tracking-widest shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2 border border-outline-variant/20">
                    <span class="text-[16px]">🎟️</span> <span class="hidden sm:inline">Vender Pase</span>
                </button>
                     <button class="bg-primary hover:bg-[#c00015] text-on-primary px-4 xl:px-7 py-3 rounded-full font-etiqueta-bold text-[11px] uppercase tracking-widest shadow-[0_4px_20px_rgba(227,27,35,0.4)] hover:shadow-[0_4px_25px_rgba(227,27,35,0.6)] hover:-translate-y-0.5 transition-all flex items-center gap-2 border border-white/10">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span> <span class="hidden sm:inline">Check In</span>
                </button>
            </div>
        </header>

        <main class="flex-1 pt-24 px-margin-lg py-margin-md">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar || !overlay) return;
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        function updateClock() {
            const now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();
            let seconds = now.getSeconds();
            
            hours = hours < 10 ? '0' + hours : hours;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            
            const liveClock = document.getElementById('live-clock');
            if (liveClock) liveClock.textContent = `${hours}:${minutes}:${seconds}`;

            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('es-ES', options);
            const currentDate = document.getElementById('current-date');
            if (currentDate) currentDate.textContent = dateStr;
        }

        setInterval(updateClock, 1000);
        updateClock();

        function togglePagosAccordion() {
            const submenu = document.getElementById('pagos-submenu');
            const chevron = document.getElementById('pagos-chevron');
            if (!submenu) return;

            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                submenu.classList.add('block');
                if (chevron) chevron.classList.add('rotate-180');
            } else {
                submenu.classList.add('hidden');
                submenu.classList.remove('block');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        }

        function updateNetworkStatus() {
            const ping = document.getElementById('network-ping');
            const icon = document.getElementById('network-icon');
            const label = document.getElementById('network-label');
            const text = document.getElementById('network-text');
            
            if (navigator.onLine) {
                // Online state: Green
                if (ping) ping.className = 'absolute inset-0 rounded-full border border-green-500/30 animate-ping opacity-20';
                if (icon) {
                    icon.className = 'material-symbols-outlined text-green-500 text-[20px]';
                    icon.textContent = 'monitor_heart';
                }
                if (label) label.className = 'font-etiqueta-bold text-[9px] tracking-[0.2em] uppercase text-green-500 mb-0.5 transition-colors';
                if (text) {
                    text.textContent = 'Sistema Activo';
                    text.classList.remove('text-[#ff2a2a]');
                    text.classList.add('text-on-surface');
                }
            } else {
                // Offline state: Intense Vibrant Red
                if (ping) ping.className = 'absolute inset-0 rounded-full border border-[#ff2a2a] animate-ping opacity-50';
                if (icon) {
                    icon.className = 'material-symbols-outlined text-[#ff2a2a] text-[20px]';
                    icon.textContent = 'wifi_off';
                }
                if (label) label.className = 'font-etiqueta-bold text-[9px] tracking-[0.2em] uppercase text-[#ff2a2a] mb-0.5 transition-colors';
                if (text) {
                    text.textContent = 'Sin Conexión';
                    text.classList.remove('text-on-surface');
                    text.classList.add('text-[#ff2a2a]');
                }
            }
        }

        window.addEventListener('online', updateNetworkStatus);
        window.addEventListener('offline', updateNetworkStatus);
        updateNetworkStatus();

        // Lógica del Loader de Página
        window.addEventListener('load', function() {
            const loader = document.getElementById('page-loader');
            if (loader) {
                loader.style.opacity = '0';
                setTimeout(() => loader.style.display = 'none', 500);
            }
        });

        document.querySelectorAll('aside a[href]').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('aria-current') !== 'page' && this.getAttribute('href') !== '#') {
                    const loader = document.getElementById('page-loader');
                    if (loader) {
                        loader.style.display = 'flex';
                        loader.offsetHeight; 
                        loader.style.opacity = '1';
                    }
                }
            });
        });
    </script>
    @stack('scripts')

    <script>
        function closeQuickSaleModal() {
            const modal = document.getElementById('quick-sale-modal');
            const content = document.getElementById('quick-sale-content');
            if (!modal || !content) return;
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Global Toast Notification Helper
        function showToast(type = 'success', message = '', title = '') {
            const iconColors = {
                success: '#4ade80',
                error: '#ef4444',
                warning: '#f59e0b',
                info: '#60a5fa'
            };

            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4500,
                    timerProgressBar: true,
                    background: '#151515',
                    color: '#e2e2e2',
                    iconColor: iconColors[type] || '#4ade80',
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: type,
                    title: title ? `<strong>${title}</strong><br><span style="font-size: 12px; font-weight: normal; opacity: 0.9;">${message}</span>` : message
                });
            } else {
                alert((title ? title + ': ' : '') + message);
            }
        }

        // Reloj Global en vivo
        function updateGlobalClock() {
            const clock = document.getElementById('global-live-clock');
            if (clock) {
                const now = new Date();
                let h = now.getHours().toString().padStart(2, '0');
                let m = now.getMinutes().toString().padStart(2, '0');
                let s = now.getSeconds().toString().padStart(2, '0');
                clock.innerText = `${h}:${m}:${s}`;
            }
        }
        setInterval(updateGlobalClock, 1000);
        updateGlobalClock();
    </script>
    <!-- Quick Sale Modal (Pase Diario / Express Dinámico) -->
    <div id="quick-sale-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeQuickSaleModal()"></div>
        <div id="quick-sale-content" class="bg-surface border border-outline-variant/20 p-8 rounded-[2rem] shadow-2xl relative z-10 max-w-md w-full mx-4 transform scale-95 transition-transform duration-300">
            <button onclick="closeQuickSaleModal()" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container-high text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
            <div class="flex flex-col items-center mb-8">
                <div class="w-16 h-16 bg-[#ff2b2b]/10 text-[#ff2b2b] rounded-full flex items-center justify-center mb-5 border border-[#ff2b2b]/20">
                    <span class="text-[32px] drop-shadow-md">🎟️</span>
                </div>
                <h3 class="font-titular-lg text-[22px] text-on-surface tracking-wide uppercase text-center drop-shadow-sm">Pase Express (Diario)</h3>
                <p class="font-body-md text-on-surface-variant text-center mt-2 opacity-80">Acceso de un solo día para visitantes.</p>
            </div>
            
            <form action="{{ route('daily-passes.store') }}" method="POST" class="flex flex-col gap-6">
                @csrf
                <div class="flex flex-col gap-2.5">
                    <label class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-[0.15em]">Nombre del Visitante <span class="text-[#ff2b2b]">*</span></label>
                    <input type="text" name="guest_name" required minlength="3" placeholder="Ej. Juan Pérez" class="bg-surface-container-highest border border-outline-variant/20 rounded-xl px-4 py-3.5 text-on-surface font-body-md focus:border-[#ff2b2b] focus:ring-1 focus:ring-[#ff2b2b] transition-all outline-none w-full shadow-inner [color-scheme:dark]">
                </div>
                
                <div class="grid grid-cols-2 gap-5">
                    <div class="flex flex-col gap-2.5">
                        <div class="flex justify-between items-center">
                            <label class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-[0.15em]">Tarifa del Plan</label>
                            <span class="text-[9px] bg-[#ff2b2b]/10 text-[#ff2b2b] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">Catálogo</span>
                        </div>
                        <input type="number" step="0.01" name="amount" value="{{ number_format($globalDailyPassPrice ?? 3.00, 2, '.', '') }}" readonly class="bg-surface-container-high border border-outline-variant/20 rounded-xl px-4 py-3.5 text-primary font-titular-md font-bold text-[20px] text-center outline-none cursor-not-allowed [color-scheme:dark] select-none shadow-inner">
                    </div>
                    <div class="flex flex-col gap-2.5">
                        <label class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-[0.15em]">Método de Pago</label>
                        <select name="payment_method_id" required class="bg-surface-container-highest border border-outline-variant/20 rounded-xl px-4 py-3.5 text-on-surface font-body-md focus:border-[#ff2b2b] focus:ring-1 focus:ring-[#ff2b2b] transition-all outline-none w-full [color-scheme:dark] shadow-inner cursor-pointer">
                            <option value="1">Efectivo 💵</option>
                            <option value="2">Transferencia 🏦</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#ff2b2b] hover:bg-[#d11a1a] text-white py-4 rounded-xl font-etiqueta-bold text-[13px] uppercase tracking-[0.2em] shadow-[0_4px_20px_rgba(255,43,43,0.4)] hover:shadow-[0_4px_25px_rgba(255,43,43,0.6)] hover:-translate-y-0.5 transition-all mt-4 border border-white/10 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">payments</span> Cobrar e Ingresar (${{ number_format($globalDailyPassPrice ?? 3.00, 2) }})
                </button>
            </form>
        </div>
    </div>
</body>
</html>
