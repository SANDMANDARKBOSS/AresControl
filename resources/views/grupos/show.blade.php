@extends('layouts.admin')

@section('title', 'Detalle de Grupo - Ares Gym')

@section('content')
<div class="flex flex-col w-full animate-on-load gap-6 pb-12 text-on-surface">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4">
        <div class="flex flex-col gap-2">
            <!-- Volver Button -->
            <a href="{{ route('grupos.index') }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-white bg-[#1E1E24]/50 hover:bg-[#1E1E24] px-4 py-2 rounded-lg transition-colors w-max text-sm font-titular-md uppercase tracking-wide">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                &larr; Volver a Grupos
            </a>
            <h1 class="font-titular-xl text-[44px] text-white tracking-tighter uppercase leading-none" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">
                Grupo <span class="text-[#FF2B2B]">{{ $name }}</span>
            </h1>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <button onclick="document.getElementById('add-member-modal').classList.remove('hidden')" class="px-6 py-2.5 bg-[#FF2B2B] text-white font-titular-md text-[14px] uppercase tracking-wide rounded-lg shadow-[0_4px_15px_rgba(255,43,43,0.3)] hover:bg-[#e61b1b] transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[20px]">person_add</span> A&ntilde;adir Integrante
            </button>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="bg-[#0B0B0E] border border-[#1E1E24] rounded-2xl overflow-hidden shadow-2xl relative mt-2">
        <div class="p-6 border-b border-[#1E1E24] flex items-center justify-between bg-[#1E1E24]/30">
            <h2 class="font-titular-md text-[20px] text-white flex items-center gap-3" style="font-family: 'Montserrat', sans-serif;">
                <span class="material-symbols-outlined text-[#FF2B2B]">groups</span> Miembros Actuales
            </h2>
            <span class="bg-[#1E1E24] text-white px-4 py-1.5 rounded-lg border border-[#1E1E24] font-etiqueta-bold text-[12px] uppercase tracking-wider">
                {{ count($clients) }} Atletas
            </span>
        </div>
        
        <div class="overflow-x-auto relative z-10">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="border-b border-[#1E1E24] bg-[#0B0B0E]">
                        <th class="py-4 px-6 font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Atleta</th>
                        <th class="py-4 px-6 font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Identificaci&oacute;n</th>
                        <th class="py-4 px-6 font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest text-right">Ver Perfil</th>
                    </tr>
                </thead>
                <tbody class="font-body-md divide-y divide-[#1E1E24]">
                    @forelse($clients as $client)
                        <tr class="hover:bg-[#1E1E24]/40 transition-colors group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-[#1E1E24] border border-[#1E1E24] flex items-center justify-center overflow-hidden">
                                          <img src="{{ $client->gender === 'F' ? asset('images/avatarF.png') : asset('images/avatarM.jpeg') }}" class="w-full h-full object-cover">
                                      </div>
                                    <span class="font-titular-md text-[15px] text-white font-bold">{{ $client->name }} {{ $client->last_name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-etiqueta-sm tracking-widest text-on-surface-variant">{{ $client->id_card }}</span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <!-- Siempre visibles: sin opacity-0 -->
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('clientes.show', $client->id) }}" class="p-2 text-on-surface-variant hover:text-white hover:bg-[#1E1E24] rounded-lg transition-colors tooltip-trigger" title="Ver Perfil">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                    <form action="{{ route('clientes.group.remove', ['client' => $client->id, 'membership' => $client->group_membership_id]) }}" method="POST" onsubmit="return confirm('Ests seguro de que deseas retirar a este atleta del grupo?');" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-on-surface-variant hover:text-[#FF2B2B] hover:bg-[#FF2B2B]/10 rounded-lg transition-colors tooltip-trigger" title="Retirar del Grupo">
                                            <span class="material-symbols-outlined text-[20px]">person_remove</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-16 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center justify-center gap-4 opacity-60">
                                    <div class="w-20 h-20 bg-[#1E1E24] rounded-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[40px] text-on-surface-variant">group_off</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="font-titular-md text-white text-lg">No hay atletas registrados</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Member -->
<div id="add-member-modal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-[#0B0B0E] rounded-2xl border border-[#1E1E24] p-8 shadow-2xl w-full max-w-lg relative animate-on-load">
        <button onclick="document.getElementById('add-member-modal').classList.add('hidden')" class="absolute top-4 right-4 text-on-surface-variant hover:text-[#FF2B2B] transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
        <h2 class="font-titular-md text-[24px] text-white mb-6 border-b border-[#1E1E24] pb-4">A&ntilde;adir Integrante</h2>
        
        <form action="{{ route('clientes.group.add', ['membership' => $clients->first()->group_membership_id ?? 1]) }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <div class="flex flex-col gap-2">
                <label class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Seleccionar Atleta Existente <span class="text-[#FF2B2B]">*</span></label>
                <select name="client_id" required class="bg-[#1E1E24]/50 border border-[#1E1E24] rounded-lg px-4 py-3 text-white outline-none focus:border-[#FF2B2B]">
                    <option value="" disabled selected>-- Elige un atleta --</option>
                    @foreach($availableClients as $ac)
                        <option value="{{ $ac->id }}">{{ $ac->name }} {{ $ac->last_name }} ({{ $ac->id_card }})</option>
                    @endforeach
                </select>
                <div class="flex items-center gap-1 mt-1">
                    <span class="text-[11px] text-on-surface-variant">&iquest;No est&aacute; en la lista?</span>
                    <a href="{{ route('clientes.create', ['group' => $name, 'membership_id' => $clients->first()->group_membership_id ?? 1]) }}" class="text-[11px] text-[#FF2B2B] hover:underline font-bold">Registrar nuevo atleta</a>
                </div>
            </div>
            
            <div class="flex flex-col gap-2">
                <label class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">M&eacute;todo de Pago</label>
                <select name="payment_method" required class="bg-[#1E1E24]/50 border border-[#1E1E24] rounded-lg px-4 py-3 text-white outline-none focus:border-[#FF2B2B]">
                    <option value="efectivo">Efectivo</option>
                    <option value="transferencia">Transferencia</option>
                </select>
            </div>
            
            <div class="flex flex-col gap-2">
                <label class="font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">N&uacute;mero de Comprobante (Opcional)</label>
                <input type="text" name="voucher_number" class="bg-[#1E1E24]/50 border border-[#1E1E24] rounded-lg px-4 py-3 text-white outline-none focus:border-[#FF2B2B]">
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="document.getElementById('add-member-modal').classList.add('hidden')" class="px-6 py-2 bg-[#1E1E24]/50 rounded-lg text-white font-etiqueta-bold uppercase text-[12px] hover:bg-[#1E1E24]">Cancelar</button>
                <button type="submit" class="px-6 py-2 bg-[#FF2B2B] text-white rounded-lg font-etiqueta-bold uppercase text-[12px] hover:bg-[#e61b1b]">A&ntilde;adir Atleta</button>
            </div>
        </form>
    </div>
</div>
@endsection

