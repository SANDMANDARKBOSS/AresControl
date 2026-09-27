@extends('layouts.auth')

@section('title', 'Acceso - Ares Gym')

@section('content')
    <main class="relative bg-surface min-h-screen px-margin-lg py-margin-md flex items-center justify-center">
        <div class="flex flex-col w-full items-center justify-center">
            <!-- Ambient Background Lighting -->
            <div class="fixed inset-0 pointer-events-none overflow-hidden flex justify-center items-center">
                <div
                    class="w-[800px] h-[800px] bg-primary-container/10 rounded-full blur-[120px] mix-blend-screen animate-pulse">
                </div>
            </div>
            <!-- Login Container -->
            <div class="relative w-full max-w-md z-10 flex flex-col items-center">
                <!-- Logo -->
                <div class="mb-margin-md relative group">
                    <div
                        class="absolute inset-0 bg-primary-container/20 rounded-full blur-xl group-hover:bg-primary-container/30 transition-all duration-500">
                    </div>
                    <img alt="Ares Gym Logo"
                        class="w-40 h-40 object-contain relative z-10 drop-shadow-[0_0_15px_rgba(227,27,35,0.3)] transition-transform duration-500 group-hover:scale-105"
                        src="{{ asset('images/logo_recort.png') }}" />
                </div>
                <!-- Typography Header -->
                <div class="text-center mb-margin-lg space-y-2">
                    <h1
                        class="font-headline-xl text-headline-xl text-on-surface uppercase tracking-tight animate-title-reveal">
                        Acceso</h1>
                    <p
                        class="font-body-md text-body-md text-primary font-medium tracking-widest uppercase animate-letter-space">
                        Protocolo de Entrada</p>
                </div>

                @if ($errors->any())
                    <div
                        class="w-full max-w-md mb-4 bg-error-container/20 border border-error text-error rounded-xl p-4 text-sm font-label-md">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Login Card -->
                <div
                    class="w-full bg-surface-container/40 backdrop-blur-md rounded-2xl p-margin-md shadow-2xl relative overflow-hidden group">
                    <!-- Subtle Inner Glow -->
                    <div
                        class="absolute inset-0 shadow-[inset_0_0_20px_rgba(227,27,35,0.05)] rounded-2xl pointer-events-none">
                    </div>
                    <!-- Dynamic Border Effect -->
                    <div
                        class="absolute inset-0 rounded-2xl border border-outline-variant/30 group-hover:border-primary-container/50 transition-colors duration-500 pointer-events-none">
                    </div>

                    <form method="POST" action="{{ route('login.post') }}" class="space-y-6 relative z-10">
                        @csrf
                        <!-- Input: Username/Email -->
                        <div class="space-y-2 group/input">
                            <label
                                class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider block transition-colors group-focus-within/input:text-primary"
                                for="username">Nombre de Usuario o Correo</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within/input:text-primary transition-colors">person</span>
                                <input name="email" value="{{ old('email') }}"
                                    class="w-full bg-surface-container-lowest text-on-surface font-label-md rounded-xl py-4 pl-12 pr-4 outline-none border border-outline-variant/30 focus:border-primary-container focus:shadow-[0_0_15px_rgba(227,27,35,0.2)] transition-all placeholder:text-on-surface-variant/50"
                                    id="username" placeholder="atleta@aresgym.com" type="text" required />
                            </div>
                        </div>
                        <!-- Input: Password -->
                        <div class="space-y-2 group/input">
                            <label
                                class="flex justify-between items-center font-label-md text-label-md text-on-surface-variant uppercase tracking-wider transition-colors group-focus-within/input:text-primary"
                                for="password">
                                <span>Contraseña</span>
                                <button
                                    class="text-label-sm text-primary hover:text-primary-fixed transition-colors normal-case tracking-normal"
                                    style="top: 40%" onclick="showSupportModal(event)" type="button">¿Olvidaste
                                    tu contraseña?</button>
                            </label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within/input:text-primary transition-colors">lock</span>
                                <input name="password"
                                    class="w-full bg-surface-container-lowest text-on-surface font-label-md rounded-xl py-4 pl-12 pr-12 outline-none border border-outline-variant/30 focus:border-primary-container focus:shadow-[0_0_15px_rgba(227,27,35,0.2)] transition-all placeholder:text-on-surface-variant/50"
                                    id="password" placeholder="••••••••" type="password" required />
                                <button
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors focus:outline-none"
                                    id="toggle-password" type="button">
                                    <span class="material-symbols-outlined">visibility_off</span>
                                </button>
                            </div>
                        </div>
                        <!-- Action Button -->
                        <button
                            class="w-full bg-gradient-to-b from-primary-container to-[#B3151B] text-on-primary-container font-headline-md text-body-lg py-4 rounded-xl uppercase tracking-widest shadow-[0_4px_20px_rgba(227,27,35,0.3)] hover:shadow-[0_4px_30px_rgba(227,27,35,0.5)] hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 relative overflow-hidden group/btn mt-8"
                            type="submit">
                            <span class="relative z-10">Iniciar Sesión</span>
                            <span
                                class="material-symbols-outlined relative z-10 group-hover/btn:translate-x-1 transition-transform">arrow_forward</span>
                            <!-- Button Glare -->
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover/btn:translate-x-full duration-1000 transition-transform">
                            </div>
                        </button>
                    </form>
                </div>
                <!-- Footer Links & Lang -->
                <div class="mt-margin-md flex flex-col items-center gap-6 w-full">
                    <button
                        class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors flex items-center gap-2 group"
                        onclick="showSupportModal(event)" type="button">
                        <span class="material-symbols-outlined group-hover:scale-110 transition-transform">person_add</span>
                        <span>Registrar nuevo usuario</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Support Modal -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300"
            id="support-modal">
            <div
                class="bg-surface-container rounded-2xl p-margin-md max-w-sm w-full mx-4 shadow-2xl border border-outline-variant/20 transform scale-95 transition-transform duration-300">
                <div class="text-center space-y-4">
                    <div
                        class="w-16 h-16 bg-primary-container/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-primary text-3xl">support_agent</span>
                    </div>
                    <h3 class="font-headline-md text-headline-md text-on-surface">Aviso del Sistema</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant">Por favor, póngase en contacto con el
                        equipo desarrollador.</p>
                    <button
                        class="w-full bg-surface-container-high hover:bg-surface-variant text-on-surface font-label-md py-3 rounded-xl transition-colors mt-6"
                        onclick="closeSupportModal()" type="button">Cerrar</button>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        // Password visibility toggle
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('toggle-password');
            const passInput = document.getElementById('password');
            const icon = toggleBtn.querySelector('span');

            toggleBtn.addEventListener('click', () => {
                if (passInput.type === 'password') {
                    passInput.type = 'text';
                    icon.textContent = 'visibility';
                } else {
                    passInput.type = 'password';
                    icon.textContent = 'visibility_off';
                }
            });
        });

        // Support Modal logic
        const modal = document.getElementById('support-modal');
        const modalContent = modal.querySelector('div > div');

        function showSupportModal(e) {
            e.preventDefault();
            modal.classList.remove('pointer-events-none');
            // Small delay to allow display:block to apply before animating opacity
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
            }, 10);
        }

        function closeSupportModal() {
            modal.classList.add('opacity-0');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('pointer-events-none');
            }, 300); // match duration-300
        }

        // Close on click outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeSupportModal();
            }
        });
    </script>
@endpush