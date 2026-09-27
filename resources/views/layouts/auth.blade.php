<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Ares Gym')</title>
    <style>
        @layer base{
            html,body{margin:0;padding:0;}
            body{overscroll-behavior:none;}
            main>:first-child{margin-top:0!important;}
            main>:last-child{margin-bottom:0!important;}
        }
        ::-webkit-scrollbar{display:none;}
    </style>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config={darkMode:"class",theme:{extend:{"colors":{"tertiary":"#c8c6c5","surface-tint":"#ffb4ac","error-container":"#93000a","on-error":"#690005","on-primary-container":"#fff9f8","primary-container":"#e31b23","on-secondary-fixed":"#1a1c1c","on-primary-fixed-variant":"#93000d","surface-container-low":"#080808","inverse-surface":"#e2e2e2","on-tertiary":"#313030","surface-container-high":"#151515","error":"#ffb4ab","on-secondary-fixed-variant":"#454747","on-surface":"#e2e2e2","surface-container-lowest":"#000000","secondary-fixed":"#e2e2e2","surface":"#000000","surface-bright":"#1a1a1a","outline":"#ae8883","on-secondary-container":"#b4b5b5","surface-container":"#0a0a0a","surface-variant":"#353535","on-background":"#e2e2e2","tertiary-fixed":"#e5e2e1","outline-variant":"#5d3f3c","on-tertiary-fixed":"#1c1b1b","tertiary-container":"#747373","on-primary-fixed":"#410002","primary":"#ffb4ac","secondary-container":"#454747","on-tertiary-fixed-variant":"#474746","on-secondary":"#2f3131","secondary":"#c6c6c7","inverse-primary":"#c00015","surface-container-highest":"#353535","on-error-container":"#ffdad6","on-primary":"#690006","background":"#000000","on-surface-variant":"#e7bdb8","primary-fixed":"#ffdad6","surface-dim":"#000000","inverse-on-surface":"#303030","on-tertiary-container":"#fdfaf9","tertiary-fixed-dim":"#c8c6c5","secondary-fixed-dim":"#c6c6c7","primary-fixed-dim":"#ffb4ac"},"borderRadius":{"DEFAULT":"0.125rem","lg":"0.25rem","xl":"0.5rem","full":"0.75rem"},"spacing":{"unit":"8px","margin-sm":"16px","margin-lg":"64px","gutter":"16px","container-padding":"24px","margin-md":"32px"},"fontFamily":{"headline-xl":["Montserrat"],"body-lg":["Inter"],"headline-lg-mobile":["Montserrat"],"headline-lg":["Montserrat"],"label-md":["Inter"],"label-sm":["Inter"],"body-md":["Inter"],"headline-md":["Montserrat"]},"fontSize":{"headline-xl":["48px",{"lineHeight":"56px","letterSpacing":"-0.02em","fontWeight":"800"}],"body-lg":["18px",{"lineHeight":"28px","fontWeight":"400"}],"headline-lg-mobile":["28px",{"lineHeight":"36px","fontWeight":"700"}],"headline-lg":["32px",{"lineHeight":"40px","letterSpacing":"-0.01em","fontWeight":"700"}],"label-md":["14px",{"lineHeight":"20px","letterSpacing":"0.05em","fontWeight":"500"}],"label-sm":["12px",{"lineHeight":"16px","letterSpacing":"0.08em","fontWeight":"500"}],"body-md":["16px",{"lineHeight":"24px","fontWeight":"400"}],"headline-md":["24px",{"lineHeight":"32px","letterSpacing":"-0.01em","fontWeight":"700"}]},"keyframes":{"titleReveal":{"0%":{"opacity":"0","transform":"translateY(20px) scale(0.95)"},"100%":{"opacity":"1","transform":"translateY(0) scale(1)"}},"letterSpace":{"0%":{"letterSpacing":"-0.05em","opacity":"0"},"100%":{"letterSpacing":"0.05em","opacity":"1"}}},"animation":{"title-reveal":"titleReveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards","letter-space":"letterSpace 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards"}}}}
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    @stack('styles')
</head>
<body class="bg-surface font-body-md text-on-surface">
    @yield('content')
    
    @stack('scripts')
</body>
</html>
