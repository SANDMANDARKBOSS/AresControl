@extends('layouts.admin')

@section('title', 'Historial de Cierres de Caja')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-4 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Historial de Cierres de Caja</h1>
            
            <div class="flex space-x-2">
                @if(!Auth::check() || Auth::user()?->role?->type !== 'Recepcionista')
                    <a href="{{ route('cash-shifts.excel') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Exportar Excel
                    </a>
                @endif
                <a href="{{ route('cash-shifts.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Nuevo Cierre / Arqueo
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse($cashShifts as $shift)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-indigo-600 truncate">
                                    Turno #{{ $shift->id }} - {{ $shift->opening_time ? $shift->opening_time->format('d/m/Y') : '' }}
                                </p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $shift->cash_difference == 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $shift->cash_difference == 0 ? 'Cuadrado' : 'Descuadre ($' . number_format($shift->cash_difference, 2) . ')' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500">
                                        Responsable: {{ $shift->user ? ($shift->user->name . ' ' . $shift->user->last_name) : 'Administrador' }}
                                    </p>
                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                        Total Recaudado: ${{ number_format($shift->total_declared_cash + $shift->total_system_transfers, 2) }}
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm sm:mt-0">
                                    <a href="{{ route('cash-shifts.pdf', $shift->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-semibold">Ver PDF</a>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li>
                        <div class="px-4 py-4 sm:px-6 text-center text-gray-500">
                            No hay cierres de caja registrados.
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
        
        <div class="mt-4">
            {{ $cashShifts->links() }}
        </div>
    </div>
</div>
@endsection
