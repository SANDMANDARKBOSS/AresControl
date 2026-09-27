@extends('layouts.admin')

@section('title', 'Nuevo Cierre de Caja')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-4 sm:px-0">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Nuevo Cierre / Arqueo Diario</h1>

        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <ul class="list-disc pl-5 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('cash-shifts.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Columna Izquierda: Efectivo -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">1. Conteo Físico (Efectivo)</h2>
                    
                    <div class="mb-4">
                        <label for="initial_base_cash" class="block text-sm font-medium text-gray-700">Fondo Base Inicial (Caja Chica)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="initial_base_cash" id="initial_base_cash" value="{{ old('initial_base_cash', 50.00) }}" step="0.01" min="0" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Este monto se restará del total contado para no inflar las ventas.</p>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-medium text-gray-700">Billetes de $100</label>
                            <input type="number" name="b100" class="cash-input w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('b100', 0) }}" min="0" data-val="100">
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-medium text-gray-700">Billetes de $50</label>
                            <input type="number" name="b50" class="cash-input w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('b50', 0) }}" min="0" data-val="50">
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-medium text-gray-700">Billetes de $20</label>
                            <input type="number" name="b20" class="cash-input w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('b20', 0) }}" min="0" data-val="20">
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-medium text-gray-700">Billetes de $10</label>
                            <input type="number" name="b10" class="cash-input w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('b10', 0) }}" min="0" data-val="10">
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-medium text-gray-700">Billetes de $5</label>
                            <input type="number" name="b5" class="cash-input w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('b5', 0) }}" min="0" data-val="5">
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-medium text-gray-700">Billetes de $1</label>
                            <input type="number" name="b1" class="cash-input w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('b1', 0) }}" min="0" data-val="1">
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-medium text-gray-700">Monedas (Total en $)</label>
                            <input type="number" name="coins" class="cash-input w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('coins', 0) }}" step="0.01" min="0" data-val="1">
                        </div>
                    </div>

                    <div class="mt-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                        <div class="flex justify-between items-center font-bold text-lg">
                            <span>Total Físico en Caja:</span>
                            <span id="total-fisico" class="text-indigo-600">$0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Transferencias y Observaciones -->
                <div class="space-y-6">
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">2. Verificación de Transferencias</h2>
                        
                        @if($transfers->isEmpty())
                            <p class="text-sm text-gray-500">No hay transferencias registradas pendientes de verificación en este turno.</p>
                        @else
                            <p class="text-xs text-gray-500 mb-3">Marca las transferencias que ya confirmaste en la app del banco.</p>
                            <div class="space-y-2 max-h-60 overflow-y-auto">
                                @foreach($transfers as $transf)
                                    <label class="flex items-start p-3 border rounded hover:bg-gray-50 cursor-pointer">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="verified_transfers[]" value="{{ $transf->id }}" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" checked>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <span class="font-medium text-gray-900">Ref: {{ $transf->voucher_number ?: 'Sin Ref' }}</span>
                                            <span class="text-gray-500 ml-2">${{ number_format($transf->amount, 2) }} - {{ $transf->billing_name ?: 'Cliente' }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">3. Observaciones</h2>
                        <textarea name="observations" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Anota cualquier novedad, descuadre o justificación aquí...">{{ old('observations') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Confirmar y Cerrar Turno
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.cash-input');
        const totalFisicoSpan = document.getElementById('total-fisico');

        function calculateTotal() {
            let total = 0;
            inputs.forEach(input => {
                const val = parseFloat(input.value) || 0;
                const multiplier = parseFloat(input.dataset.val);
                
                // Si es monedas (data-val="1" pero step 0.01), el valor ingresado ya es el total en $
                if(input.name === 'coins') {
                    total += val;
                } else {
                    total += (val * multiplier);
                }
            });
            totalFisicoSpan.textContent = '$' + total.toFixed(2);
        }

        inputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
        });

        calculateTotal();
    });
</script>
@endpush
@endsection
