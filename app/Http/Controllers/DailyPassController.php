<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyPass;
use App\Models\Plan;

class DailyPassController extends Controller
{
    public function store(Request $request)
    {
        $dailyPlan = Plan::where('validity_days', 1)
            ->orWhere('name', 'like', '%diario%')
            ->orWhere('name', 'like', '%express%')
            ->orWhere('name', 'like', '%pase%')
            ->orderBy('id', 'desc')
            ->first();

        $amount = $dailyPlan ? (float)$dailyPlan->price : (float)($request->amount ?: 3.00);

        $request->validate([
            'guest_name' => 'required|string|min:3|max:100',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ], [
            'guest_name.required' => 'El nombre del visitante es obligatorio para el Pase Express.',
            'guest_name.min' => 'El nombre debe tener al menos 3 caracteres.',
        ]);

        DailyPass::create([
            'guest_name' => trim($request->guest_name),
            'amount' => $amount,
            'payment_method_id' => $request->payment_method_id,
        ]);

        return back()->with('success', '¡Pase Express registrado con éxito por $' . number_format($amount, 2) . ' para ' . $request->guest_name . '!');
    }
}

