@extends('layouts.admin')

@section('title', 'Planes Grupales - Ares Gym')

@section('content')
<div class="flex flex-col w-full animate-on-load gap-6 pb-12 text-on-surface">

    <!-- Top Navigation & Aforo -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-2">
        <div class="flex flex-col gap-2">
            <!-- Volver Button -->
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-white bg-[#1E1E24]/50 hover:bg-[#1E1E24] px-4 py-2 rounded-lg transition-colors w-max text-sm font-titular-md uppercase tracking-wide">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                &larr; Volver a Dashboard
            </a>
            <h1 class="font-titular-xl text-[44px] text-white tracking-tighter uppercase leading-none" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">
                Planes <span class="text-[#FF2B2B]">Grupales</span>
            </h1>
        </div>

        <!-- Aforo Indicator -->
        <div class="flex items-center gap-4 bg-[#0B0B0E] border border-[#1E1E24] px-5 py-3 rounded-2xl shadow-lg">
            <div class="relative flex items-center justify-center w-12 h-12">
                <!-- Circular Progress Ring -->
                <svg class="w-12 h-12 transform -rotate-90">
                    <circle cx="24" cy="24" r="20" stroke="#1E1E24" stroke-width="4" fill="none" />
                    <circle cx="24" cy="24" r="20" stroke="#FF2B2B" stroke-width="4" fill="none" stroke-dasharray="125.6" stroke-dashoffset="{{ 125.6 - (125.6 * $capacityPercentage / 100) }}" class="transition-all duration-1000 ease-out" />
                </svg>
                <span class="absolute text-[11px] font-bold text-white">{{ $capacityPercentage }}%</span>
            </div>
            <div class="flex flex-col">
                <span class="text-[10px] text-on-surface-variant uppercase tracking-widest font-bold">Aforo Grupal</span>
                <span class="text-white font-titular-md text-[14px]">{{ $totalGroupAthletes }} / {{ $maxCapacity }} <span class="text-on-surface-variant text-[12px]">Personas</span></span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 text-green-500 p-4 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span class="font-body-md text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Toolbar: Search, Filters, New Button -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-[#0B0B0E] p-4 rounded-2xl border border-[#1E1E24]">
        <div class="flex w-full sm:w-auto items-center gap-3">
            <div class="relative w-full sm:w-64">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input type="text" id="searchInput" placeholder="Buscar grupo... (Ctrl+K)" class="w-full bg-[#1E1E24]/50 border border-[#1E1E24] text-white text-sm rounded-lg pl-10 pr-4 py-2.5 focus:outline-none focus:border-[#FF2B2B] transition-colors font-body-md placeholder:text-on-surface-variant/50">
            </div>
            <div class="relative">
                <button id="filterBtn" class="bg-[#1E1E24]/50 hover:bg-[#1E1E24] border border-[#1E1E24] text-white px-4 py-2.5 rounded-lg transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    <span class="text-sm font-titular-md uppercase tracking-wider">Filtros</span>
                </button>
                <!-- Filter Dropdown -->
                <div id="filterDropdown" class="absolute top-full left-0 mt-2 w-48 bg-[#0B0B0E] border border-[#1E1E24] rounded-xl shadow-2xl overflow-hidden z-50 hidden opacity-0 transition-opacity">
                    <div class="p-2 flex flex-col gap-1">
                        <button class="filter-option w-full text-left px-4 py-2 text-sm text-white hover:bg-[#1E1E24] rounded-lg transition-colors flex items-center justify-between" data-status="all">
                            <span>Todos</span>
                            <span class="material-symbols-outlined text-[16px] text-[#FF2B2B] check-icon">check</span>
                        </button>
                        <button class="filter-option w-full text-left px-4 py-2 text-sm text-on-surface-variant hover:text-white hover:bg-[#1E1E24] rounded-lg transition-colors flex items-center justify-between" data-status="activo">
                            <span>Activos</span>
                            <span class="material-symbols-outlined text-[16px] text-[#FF2B2B] check-icon hidden">check</span>
                        </button>
                        <button class="filter-option w-full text-left px-4 py-2 text-sm text-on-surface-variant hover:text-white hover:bg-[#1E1E24] rounded-lg transition-colors flex items-center justify-between" data-status="suspendido">
                            <span>Suspendidos</span>
                            <span class="material-symbols-outlined text-[16px] text-[#FF2B2B] check-icon hidden">check</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <a href="{{ route('clientes.create.group') }}" class="w-full sm:w-auto px-6 py-2.5 bg-[#FF2B2B] text-white font-titular-md text-[14px] uppercase tracking-wide rounded-lg hover:bg-[#e61b1b] transition-all flex items-center justify-center gap-2 shadow-[0_4px_15px_rgba(255,43,43,0.3)]">
            <span class="material-symbols-outlined text-[20px]">group_add</span> Nuevo Plan Grupal
        </a>
    </div>

    <!-- Data Table -->
    <div class="bg-[#0B0B0E] border border-[#1E1E24] rounded-2xl overflow-hidden shadow-2xl relative">
        <div class="overflow-x-auto relative z-10">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="border-b border-[#1E1E24] bg-[#1E1E24]/30">
                        <th class="py-4 px-6 font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Nombre del Grupo</th>
                        <th class="py-4 px-6 font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Estado del Plan</th>
                        <th class="py-4 px-6 font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest text-center">Miembros Activos</th>
                        <th class="py-4 px-6 font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest">Prx. Renovacin</th>
                        <th class="py-4 px-6 font-etiqueta-bold text-[11px] text-on-surface-variant uppercase tracking-widest text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="font-body-md divide-y divide-[#1E1E24]">
                    @forelse($groups as $group)
                        <tr class="hover:bg-[#1E1E24]/40 transition-colors group group-row" data-name="{{ strtolower($group->group_name) }}" data-status="{{ strtolower($group->status) }}">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-[#FF2B2B]/10 border border-[#FF2B2B]/20 flex items-center justify-center text-[#FF2B2B]">
                                        <span class="material-symbols-outlined text-[20px]">diversity_3</span>
                                    </div>
                                    <span class="font-titular-md text-[15px] text-white font-bold">{{ $group->group_name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if(strtolower($group->status) === 'activa')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-500/10 border border-green-500/20 text-green-500 text-[11px] font-bold uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#1E1E24] border border-outline-variant/20 text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-on-surface-variant"></span> Suspendido
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-center gap-2 bg-[#1E1E24]/50 px-3 py-1 rounded-lg border border-[#1E1E24]">
                                    <span class="font-titular-md text-white text-[15px]">{{ $group->members_count }}</span>
                                    <span class="font-etiqueta-bold text-[10px] text-on-surface-variant uppercase tracking-widest">Atletas</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                                    <span class="text-[14px] font-medium">{{ \Carbon\Carbon::parse($group->end_date)->translatedFormat('d M, Y') }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2 ">
                                    <!-- Ver Button -->
                                    <a href="{{ route('grupos.show', $group->group_name) }}" class="flex items-center gap-1.5 px-4 py-1.5 rounded-lg border border-[#FF2B2B] text-[#FF2B2B] hover:bg-[#FF2B2B] hover:text-white transition-all text-xs font-bold uppercase tracking-wider">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span> Ver
                                    </a>
                                    

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-state-row">
                            <td colspan="5" class="py-16 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center justify-center gap-4 opacity-60">
                                    <div class="w-20 h-20 bg-[#1E1E24] rounded-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[40px] text-on-surface-variant">group_off</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="font-titular-md text-white text-lg">No hay planes grupales</span>
                                        <span class="font-body-md text-sm">No se encontraron registros de grupos en el sistema.</span>
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
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const rows = document.querySelectorAll('.group-row');
        const filterBtn = document.getElementById('filterBtn');
        const filterDropdown = document.getElementById('filterDropdown');
        const filterOptions = document.querySelectorAll('.filter-option');
        let currentStatusFilter = 'all';

        // Toggle dropdown
        filterBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (filterDropdown.classList.contains('hidden')) {
                filterDropdown.classList.remove('hidden');
                setTimeout(() => filterDropdown.classList.remove('opacity-0'), 10);
            } else {
                filterDropdown.classList.add('opacity-0');
                setTimeout(() => filterDropdown.classList.add('hidden'), 300);
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            if (!filterDropdown.classList.contains('hidden')) {
                filterDropdown.classList.add('opacity-0');
                setTimeout(() => filterDropdown.classList.add('hidden'), 300);
            }
        });

        filterDropdown.addEventListener('click', (e) => e.stopPropagation());

        // Select filter option
        filterOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Update UI of options
                filterOptions.forEach(opt => {
                    opt.classList.remove('text-white');
                    opt.classList.add('text-on-surface-variant');
                    opt.querySelector('.check-icon').classList.add('hidden');
                });
                option.classList.remove('text-on-surface-variant');
                option.classList.add('text-white');
                option.querySelector('.check-icon').classList.remove('hidden');

                // Update state and filter
                currentStatusFilter = option.getAttribute('data-status');
                filterTable();
            });
        });

        // Search input logic
        searchInput.addEventListener('input', filterTable);

        // Ctrl+K shortcut
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                searchInput.focus();
            }
        });

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            let visibleCount = 0;
            
            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const status = row.getAttribute('data-status');
                
                const matchesSearch = name.includes(searchTerm);
                const matchesStatus = currentStatusFilter === 'all' || 
                                      (currentStatusFilter === 'activo' && status === 'activa') || 
                                      (currentStatusFilter === 'suspendido' && status !== 'activa');
                
                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle empty state
            const emptyRow = document.querySelector('.empty-state-row');
            if (emptyRow) {
                emptyRow.style.display = visibleCount === 0 ? '' : 'none';
            }
        }
    });
</script>
@endpush
