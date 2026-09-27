@extends('layouts.admin')

@section('title', 'Editar Cliente - Ares Gym')

@section('content')
<div class="flex flex-col w-full max-w-4xl mx-auto animate-on-load gap-6 pb-12">
    
    <!-- Top Bar Navigation -->
    <div class="flex items-center justify-between mb-2">
        <a href="{{ route('clientes.show', $client->id) }}" class="font-etiqueta-bold text-etiqueta-sm text-on-surface-variant uppercase tracking-[0.2em] hover:text-primary transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span> Volver al Perfil
        </a>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col">
        <span class="font-label-sm text-label-sm text-primary uppercase tracking-[0.2em] mb-2">[ EDICIÓN DE PERFIL ]</span>
        <h1 class="font-titular-xl text-[40px] text-on-surface tracking-tighter uppercase leading-none" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">Actualizar<br/>Atleta</h1>
    </div>

    @if ($errors->any())
        <div class="bg-error/10 border border-error/30 text-error p-4 rounded-xl flex items-start gap-3 mt-4">
            <span class="material-symbols-outlined">error</span>
            <div class="flex flex-col">
                <span class="font-titular-md text-[14px]">Revisa los siguientes errores:</span>
                <ul class="list-disc ml-5 font-body-md text-sm mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('clientes.update', $client->id) }}" method="POST" class="w-full flex flex-col gap-6">
        @csrf
        @method('PUT')
        
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-[24px] p-8 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <h2 class="font-titular-md text-[20px] text-on-surface flex items-center gap-2 border-b border-outline-variant/10 pb-4 mb-6" style="font-family: 'Montserrat', sans-serif;">
                <span class="material-symbols-outlined text-primary">person</span> Datos Personales
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                <!-- Nombre -->
                <div class="flex flex-col gap-2">
                    <label for="name" class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Nombres <span class="text-error">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" required
                           class="bg-surface-container-highest border border-outline-variant/20 rounded-xl px-4 py-3 text-on-surface font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-all outline-none">
                </div>
                
                <!-- Apellidos -->
                <div class="flex flex-col gap-2">
                    <label for="last_name" class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Apellidos <span class="text-error">*</span></label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $client->last_name) }}" required
                           class="bg-surface-container-highest border border-outline-variant/20 rounded-xl px-4 py-3 text-on-surface font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-all outline-none">
                </div>

                <!-- Gnero -->
                <div class="flex flex-col gap-2 md:col-span-2">
                    <label class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Gnero (Avatar) <span class="text-error">*</span></label>
                    <div class="flex gap-4">
                        <label class="flex-1 cursor-pointer group">
                            <input type="radio" name="gender" value="M" class="peer sr-only" required {{ old('gender', $client->gender) == 'M' ? 'checked' : '' }}>
                            <div class="p-4 bg-surface-container-highest rounded-xl border border-outline-variant/20 peer-checked:border-primary peer-checked:bg-primary/5 transition-all flex flex-col items-center gap-2 hover:border-outline-variant/40">
                                <img src="{{ asset('images/avatarM.jpeg') }}" class="w-12 h-12 rounded-full object-cover border-2 border-transparent peer-checked:border-primary group-hover:scale-105 transition-transform">
                                <span class="font-etiqueta-bold text-on-surface text-[12px] uppercase">Masculino</span>
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer group">
                            <input type="radio" name="gender" value="F" class="peer sr-only" required {{ old('gender', $client->gender) == 'F' ? 'checked' : '' }}>
                            <div class="p-4 bg-surface-container-highest rounded-xl border border-outline-variant/20 peer-checked:border-primary peer-checked:bg-primary/5 transition-all flex flex-col items-center gap-2 hover:border-outline-variant/40">
                                <img src="{{ asset('images/avatarF.png') }}" class="w-12 h-12 rounded-full object-cover border-2 border-transparent peer-checked:border-primary group-hover:scale-105 transition-transform">
                                <span class="font-etiqueta-bold text-on-surface text-[12px] uppercase">Femenino</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Cédula -->
                <div class="flex flex-col gap-2">
                    <label for="id_card" class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest flex items-center justify-between">
                        <span>Cédula / Identificación</span>
                        <span class="text-[10px] text-outline lowercase font-normal italic tracking-normal">(no modificable)</span>
                    </label>
                    <input type="text" id="id_card" value="{{ old('id_card', $client->id_card) }}" 
                           class="bg-surface-container border border-outline-variant/10 rounded-xl px-4 py-3 text-on-surface-variant/70 font-body-md cursor-not-allowed opacity-75 select-none">
                </div>

                <!-- Fecha de Nacimiento -->
                <div class="flex flex-col gap-2">
                    <label for="birth_date" class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest flex items-center justify-between">
                        <span>Fecha de Nacimiento</span>
                        <span class="text-[10px] text-outline lowercase font-normal italic tracking-normal">(no modificable)</span>
                    </label>
                    <input type="date" id="birth_date" value="{{ old('birth_date', $client->birth_date ? \Carbon\Carbon::parse($client->birth_date)->format('Y-m-d') : '') }}" 
                           class="bg-surface-container border border-outline-variant/10 rounded-xl px-4 py-3 text-on-surface-variant/70 font-body-md cursor-not-allowed opacity-75 [color-scheme:dark] select-none">
                </div>
            </div>
        </div>

        <div class="bg-surface-container-low border border-outline-variant/10 rounded-[24px] p-8 shadow-xl relative overflow-hidden">
            <h2 class="font-titular-md text-[20px] text-on-surface flex items-center gap-2 border-b border-outline-variant/10 pb-4 mb-6" style="font-family: 'Montserrat', sans-serif;">
                <span class="material-symbols-outlined text-primary">contact_mail</span> Contacto y Sistema
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Teléfono -->
                <div class="flex flex-col gap-2">
                    <label for="phone" class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Teléfono Móvil</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-on-surface-variant font-etiqueta-bold">+593</span>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $client->phone) }}" maxlength="15"
                               class="bg-surface-container-highest border border-outline-variant/20 rounded-xl pl-14 pr-4 py-3 w-full text-on-surface font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-all outline-none">
                    </div>
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-2">
                    <label for="email" class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Correo Electrónico <span class="text-error">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $client->user ? $client->user->email : '') }}" required
                           class="bg-surface-container-highest border border-outline-variant/20 rounded-xl px-4 py-3 text-on-surface font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-all outline-none">
                </div>

                <!-- Fecha de Ingreso -->
                <div class="flex flex-col gap-2 md:col-span-2">
                    <label for="entry_date" class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Fecha de Registro Inicial</label>
                    <input type="date" name="entry_date" id="entry_date" value="{{ old('entry_date', $client->entry_date ? \Carbon\Carbon::parse($client->entry_date)->format('Y-m-d') : '') }}" readonly tabindex="-1"
                           class="bg-surface-container-highest border border-outline-variant/20 rounded-xl px-4 py-3 text-on-surface font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-all outline-none w-full md:w-1/2 [color-scheme:dark] opacity-60 pointer-events-none">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-4">
            <a href="{{ route('clientes.show', $client->id) }}" class="px-8 py-3 bg-surface-container border border-outline-variant/20 text-on-surface font-titular-md text-[14px] uppercase tracking-wide rounded-xl hover:bg-surface-container-high transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-8 py-3 bg-primary text-on-primary font-titular-md text-[14px] uppercase tracking-wide rounded-xl hover:bg-[#ff2a35] shadow-[0_0_15px_rgba(227,27,35,0.3)] transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">save</span> Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection


