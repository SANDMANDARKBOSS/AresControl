<?php

namespace App\Http\Controllers;

use App\Models\CashShift;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class CashShiftController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('pagos', array_merge(['tab' => 'cierres'], $request->all()));
    }

    public function create()
    {
        $today = Carbon::today();
        
        // Obtener transferencias no verificadas
        $transfers = Payment::with('membership.clients')
            ->whereNull('cash_shift_id')
            ->whereHas('paymentMethod', function ($q) {
                $q->where('name', 'Transferencia');
            })->get();
            
        $cashPayments = Payment::with('membership.clients')
            ->whereNull('cash_shift_id')
            ->whereHas('paymentMethod', function ($q) {
                $q->where('name', 'Efectivo');
            })->get();
            
        $totalSystemCash = $cashPayments->sum('amount');
        $totalSystemTransfers = $transfers->sum('amount');

        return view('cash_shifts.create', compact('transfers', 'cashPayments', 'totalSystemCash', 'totalSystemTransfers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'initial_base_cash' => 'required|numeric|min:0',
            'b100' => 'required|integer|min:0',
            'b50' => 'required|integer|min:0',
            'b20' => 'required|integer|min:0',
            'b10' => 'required|integer|min:0',
            'b5' => 'required|integer|min:0',
            'b1' => 'required|integer|min:0',
            'coins' => 'required|numeric|min:0',
            'verified_transfers' => 'nullable|array',
            'verified_transfers.*' => 'exists:payments,id',
            'observations' => 'nullable|string'
        ]);

        $b100 = $request->b100;
        $b50 = $request->b50;
        $b20 = $request->b20;
        $b10 = $request->b10;
        $b5 = $request->b5;
        $b1 = $request->b1;
        $coins = (float) $request->coins;

        $totalPhysicalCash = ($b100 * 100) + ($b50 * 50) + ($b20 * 20) + ($b10 * 10) + ($b5 * 5) + ($b1 * 1) + $coins;
        $initialBaseCash = (float) $request->initial_base_cash;
        $netPhysicalCash = max(0, $totalPhysicalCash - $initialBaseCash);

        // Obtener pagos
        $cashPayments = Payment::whereNull('cash_shift_id')
            ->whereHas('paymentMethod', function($q) {
                $q->where('name', 'Efectivo');
            })->get();

        $systemCash = $cashPayments->sum('amount');
        
        // Transferencias validadas
        $verifiedTransfersIds = $request->input('verified_transfers', []);
        $transfersAmount = Payment::whereIn('id', $verifiedTransfersIds)->sum('amount');

        $difference = $netPhysicalCash - $systemCash;

        $breakdown = [
            'b100' => $b100,
            'b50' => $b50,
            'b20' => $b20,
            'b10' => $b10,
            'b5' => $b5,
            'b1' => $b1,
            'coins' => $coins,
        ];

        $shift = CashShift::create([
            'user_id' => Auth::id() ?: 1,
            'opening_time' => Carbon::today(),
            'closing_time' => Carbon::now(),
            'initial_base_cash' => $initialBaseCash,
            'total_system_cash' => $systemCash,
            'total_declared_cash' => $netPhysicalCash,
            'cash_difference' => $difference,
            'total_system_transfers' => $transfersAmount,
            'status' => 'cerrado',
            'observations' => $request->observations,
            'cash_breakdown' => $breakdown
        ]);

        $cashPaymentsIds = $cashPayments->pluck('id')->toArray();
        $allPaymentsToLink = array_merge($cashPaymentsIds, $verifiedTransfersIds);

        if (!empty($allPaymentsToLink)) {
            Payment::whereIn('id', $allPaymentsToLink)->update([
                'cash_shift_id' => $shift->id,
                'is_verified' => true
            ]);
        }

        return redirect()->route('pagos', ['tab' => 'cierres'])->with('success', 'Turno cerrado y guardado correctamente en el historial.');
    }

    public function pdf(CashShift $cashShift)
    {
        $userRole = Auth::user()?->role?->type;
        if ($userRole === 'Recepcionista' && $cashShift->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este cierre.');
        }

        $transfers = $cashShift->payments()->with('membership.clients')->whereHas('paymentMethod', function ($q) {
            $q->where('name', 'Transferencia');
        })->get();

        $breakdown = $cashShift->cash_breakdown ?? [
            'b100' => 0, 'b50' => 0, 'b20' => 0, 'b10' => 0, 'b5' => 0, 'b1' => 0, 'coins' => 0
        ];

        $totalEfectivoFisico = ($breakdown['b100'] * 100) + ($breakdown['b50'] * 50) + ($breakdown['b20'] * 20) + ($breakdown['b10'] * 10) + ($breakdown['b5'] * 5) + ($breakdown['b1'] * 1) + $breakdown['coins'];

        $difTransferencia = $cashShift->total_system_transfers - $transfers->sum('amount');
        
        $totalGeneralSistema = $cashShift->total_system_cash + $cashShift->total_system_transfers + $cashShift->initial_base_cash;
        $totalGeneralReal = $cashShift->total_declared_cash + $cashShift->total_system_transfers + $cashShift->initial_base_cash;

        $estadoEfectivo = $cashShift->cash_difference == 0 ? 'CUADRADO' : ($cashShift->cash_difference > 0 ? 'SOBRANTE' : 'FALTANTE');
        $estadoTransf = $difTransferencia == 0 ? 'CUADRADO' : 'ERROR';
        $estadoGeneral = ($cashShift->cash_difference == 0 && $difTransferencia == 0) ? 'CUADRADO' : 'DESCUADRE';

        $data = [
            'folio' => $cashShift->id . '-' . $cashShift->created_at->format('Ymd'),
            'fecha' => $cashShift->created_at->format('d/m/Y'),
            'administrador' => $cashShift->user ? ($cashShift->user->name . ' ' . $cashShift->user->last_name) : 'Administrador Ares Gym',
            'horaApertura' => $cashShift->opening_time ? $cashShift->opening_time->format('H:i') : '05:00',
            'horaCierre' => $cashShift->closing_time ? $cashShift->closing_time->format('H:i') : '-',
            'estado' => $cashShift->status === 'cerrado' ? 'CERRADA' : 'ABIERTA',
            
            // Billetes
            'b100' => $breakdown['b100'], 'sb100' => number_format($breakdown['b100'] * 100, 2),
            'b50' => $breakdown['b50'], 'sb50' => number_format($breakdown['b50'] * 50, 2),
            'b20' => $breakdown['b20'], 'sb20' => number_format($breakdown['b20'] * 20, 2),
            'b10' => $breakdown['b10'], 'sb10' => number_format($breakdown['b10'] * 10, 2),
            'b5' => $breakdown['b5'], 'sb5' => number_format($breakdown['b5'] * 5, 2),
            'b1' => $breakdown['b1'], 'sb1' => number_format($breakdown['b1'] * 1, 2),
            'cantMonedas' => '-', 'totalMonedas' => number_format($breakdown['coins'], 2),
            
            'totalEfectivoFisico' => number_format($totalEfectivoFisico, 2),
            'fondoInicial' => number_format($cashShift->initial_base_cash, 2),
            'efectivoVentas' => number_format($cashShift->total_declared_cash, 2),
            
            'transfers' => $transfers,
            'cantTransf' => $transfers->count(),
            'totalTransferencias' => number_format($cashShift->total_system_transfers, 2),

            'efectivoSistema' => number_format($cashShift->total_system_cash, 2),
            'difEfectivo' => number_format($cashShift->cash_difference, 2),
            'estadoEfectivo' => $estadoEfectivo,

            'transfSistema' => number_format($transfers->sum('amount'), 2), 
            'difTransferencia' => number_format($difTransferencia, 2),
            'estadoTransf' => $estadoTransf,

            'totalVentasSistema' => number_format($cashShift->total_system_cash + $cashShift->total_system_transfers, 2),
            'totalVentasReal' => number_format($cashShift->total_declared_cash + $cashShift->total_system_transfers, 2),
            'difTotal' => number_format($cashShift->cash_difference + $difTransferencia, 2),
            'estadoGeneral' => $estadoGeneral,

            'totalGeneralSistema' => number_format($totalGeneralSistema, 2),
            'totalGeneralReal' => number_format($totalGeneralReal, 2),
            'difFinal' => number_format($cashShift->cash_difference, 2),

            'observaciones' => $cashShift->observations ?: 'Sin observaciones.',
        ];

        $pdf = Pdf::loadView('cash_shifts.pdf', $data);
        return $pdf->stream('cierre_caja_' . $data['folio'] . '.pdf');
    }

    public function excel(Request $request)
    {
        $userRole = Auth::user()?->role?->type;
        if ($userRole === 'Recepcionista') {
            abort(403, 'Acceso denegado.');
        }

        $fileName = 'historial_cierres.csv';
        $cashShifts = CashShift::with('user')->orderBy('opening_time', 'desc')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['ID Turno', 'Fecha Apertura', 'Fecha Cierre', 'Responsable', 'Fondo Inicial', 'Ventas Efectivo (Sistema)', 'Efectivo Declarado (Físico)', 'Transferencias (Sistema)', 'Diferencia (Sobrante/Faltante)', 'Estado'];

        $callback = function() use($cashShifts, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($cashShifts as $shift) {
                $row['ID Turno']  = $shift->id;
                $row['Fecha Apertura']    = $shift->opening_time;
                $row['Fecha Cierre']  = $shift->closing_time;
                $row['Responsable'] = $shift->user ? ($shift->user->name . ' ' . $shift->user->last_name) : 'Administrador Ares Gym';
                $row['Fondo Inicial']  = $shift->initial_base_cash;
                $row['Ventas Efectivo (Sistema)']  = $shift->total_system_cash;
                $row['Efectivo Declarado (Físico)']  = $shift->total_declared_cash;
                $row['Transferencias (Sistema)']  = $shift->total_system_transfers;
                $row['Diferencia']  = $shift->cash_difference;
                $row['Estado']  = $shift->status;

                fputcsv($file, array_values($row));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
