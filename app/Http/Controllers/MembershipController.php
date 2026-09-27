<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Plan;
use App\Models\Client;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MembershipController extends Controller
{
    /**
     * Display memberships dashboard with real analytics, catalog, and active portfolio.
     */
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $in3Days = Carbon::today()->addDays(3)->toDateString();
        $in4Days = Carbon::today()->addDays(4)->toDateString();
        $in7Days = Carbon::today()->addDays(7)->toDateString();
        $in30Days = Carbon::today()->addDays(30)->toDateString();

        $selectedPlanId = $request->get('plan_id');

        // 1. Total Visitas / Pases Diarios Reales (Pases express registrados)
        $dailyPassesCount = \App\Models\DailyPass::count() + 
            Membership::whereHas('plan', function ($q) {
                $q->where('name', 'like', '%diario%')->orWhere('validity_days', 1);
            })->count();

        // 2. Catálogo Completo de Planes (para la sección de catálogo)
        $plans = Plan::withCount(['memberships as active_memberships_count' => function ($q) use ($today) {
            $q->where('status', 'Activa')->whereDate('end_date', '>=', $today);
        }, 'memberships as total_memberships_count'])->get();

        // Planes de suscripción recurrente (Excluyendo Valor Diario y pases de 1 día)
        $subscriptionPlans = Plan::where('validity_days', '>', 1)
            ->where('name', '!=', 'Valor Diario')
            ->orderBy('price', 'asc')
            ->get();

        // Todos los clientes registrados para el combobox de asignación
        $allClients = Client::orderBy('name', 'asc')->orderBy('last_name', 'asc')->get();

        // 3. Query Base para la Cartera de Suscripciones (Excluyendo Pases Diarios de 1 día)
        $baseTabQuery = Membership::whereHas('plan', function ($pq) {
            $pq->where('validity_days', '>', 1)->where('name', '!=', 'Valor Diario');
        });

        // 4. KPIs Globales Reales (Panel Superior de Suscripciones Recurrentes)
        $globalTotalMemberships = (clone $baseTabQuery)->count();

        // Total membresías de suscripción actualmente vigentes (end_date >= hoy)
        $totalActiveMemberships = (clone $baseTabQuery)
            ->where('status', 'Activa')
            ->whereDate('end_date', '>=', $today)
            ->count();

        // Total atletas únicos con membresía activa vigente
        $activeClientsCount = Client::whereHas('memberships', function ($q) use ($today) {
            $q->where('status', 'Activa')
              ->whereDate('end_date', '>=', $today)
              ->whereHas('plan', function ($pq) {
                  $pq->where('validity_days', '>', 1)->where('name', '!=', 'Valor Diario');
              });
        })->count();

        // Plan Más Popular / Más Vendido (con datos reales)
        $topPlanData = Plan::where('validity_days', '>', 1)
            ->where('name', '!=', 'Valor Diario')
            ->withCount('memberships')
            ->orderBy('memberships_count', 'desc')
            ->first();

        $topPlanName = $topPlanData ? $topPlanData->name : 'Plan Mensual Individual';
        $topPlanPercentage = ($topPlanData && $globalTotalMemberships > 0)
            ? round(($topPlanData->memberships_count / $globalTotalMemberships) * 100, 1)
            : 0;

        // Membresías activas con saldo pendiente o deuda (Requieren gestión de cobro)
        $pendingDebtCountGlobal = (clone $baseTabQuery)
            ->where(function ($q) {
                $q->whereHas('payments', function ($pq) {
                    $pq->where('pending_balance', '>', 0);
                })->orDoesntHave('payments');
            })
            ->count();

        $expiringCountGlobal = (clone $baseTabQuery)
            ->where('status', 'Activa')
            ->whereDate('end_date', '>=', $today)
            ->whereDate('end_date', '<=', $in30Days)
            ->count();

        $expiringMemberships = (clone $baseTabQuery)
            ->with(['plan', 'clients', 'payments.paymentMethod'])
            ->where('status', 'Activa')
            ->whereDate('end_date', '>=', $today)
            ->whereDate('end_date', '<=', $in30Days)
            ->orderBy('end_date', 'asc')
            ->get();

        // 5. Gráfico de Distribución de Planes (datos reales)
        $plansDistribution = Plan::where('validity_days', '>', 1)
            ->where('name', '!=', 'Valor Diario')
            ->withCount('memberships')
            ->get()
            ->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'count' => $plan->memberships_count,
                    'price' => (float)$plan->price,
                ];
            });

        // 6. Filtro por Plan específico en Cartera
        if (!empty($selectedPlanId)) {
            $baseTabQuery->where('plan_id', $selectedPlanId);
        }

        // Filtro por búsqueda de texto (Nombre, Cédula o Grupo)
        if ($request->filled('q')) {
            $search = trim($request->get('q'));
            $baseTabQuery->where(function ($q) use ($search) {
                $q->where('group_name', 'like', "%{$search}%")
                  ->orWhereHas('clients', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('id_card', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('plan', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Conteos Dinámicos de Pestañas según el filtro actual
        $tabTotalCount = (clone $baseTabQuery)->count();

        $tabActiveCount = (clone $baseTabQuery)
            ->where('status', 'Activa')
            ->whereDate('end_date', '>=', $today)
            ->count();

        // Alerta amarilla: 4 a 7 días (aviso a partir de 5 o 7 días)
        $tabWarningCount = (clone $baseTabQuery)
            ->where('status', 'Activa')
            ->whereDate('end_date', '>=', $in4Days)
            ->whereDate('end_date', '<=', $in7Days)
            ->count();

        // Crítico rojo: 0 a 3 días (por caducar en 1 a 3 días o vence hoy)
        $tabCriticalCount = (clone $baseTabQuery)
            ->where('status', 'Activa')
            ->whereDate('end_date', '>=', $today)
            ->whereDate('end_date', '<=', $in3Days)
            ->count();

        // Pendientes de Cobrar: todos los clientes que deban dinero (saldo pendiente / abonos incompletos)
        $tabPendingPaymentCount = (clone $baseTabQuery)
            ->where(function ($q) {
                $q->whereHas('payments', function ($pq) {
                    $pq->where('pending_balance', '>', 0);
                })->orDoesntHave('payments');
            })
            ->count();

        $tabExpiringCount = $tabPendingPaymentCount;

        $tabExpiredCount = (clone $baseTabQuery)
            ->where(function ($q) use ($today) {
                $q->where('status', 'Vencida')
                  ->orWhere('status', 'Inactiva')
                  ->orWhereDate('end_date', '<', $today);
            })
            ->count();

        $tabGroupsCount = (clone $baseTabQuery)
            ->where(function ($q) {
                $q->whereNotNull('group_name')
                  ->orWhereHas('clients', null, '>', 1);
            })
            ->count();

        // 7. Aplicar Filtro de Estado / Pestaña a la Consulta Final
        $query = (clone $baseTabQuery)->with(['plan', 'clients', 'payments.paymentMethod']);

        $filter = $request->get('filter', 'all');
        if ($filter === 'active') {
            $query->where('status', 'Activa')
                  ->whereDate('end_date', '>=', $today);
        } elseif ($filter === 'warning') {
            $query->where('status', 'Activa')
                  ->whereDate('end_date', '>=', $in4Days)
                  ->whereDate('end_date', '<=', $in7Days);
        } elseif ($filter === 'critical') {
            $query->where('status', 'Activa')
                  ->whereDate('end_date', '>=', $today)
                  ->whereDate('end_date', '<=', $in3Days);
        } elseif ($filter === 'pending_payment' || $filter === 'expiring' || $filter === 'debt') {
            $query->where(function ($q) {
                $q->whereHas('payments', function ($pq) {
                    $pq->where('pending_balance', '>', 0);
                })->orDoesntHave('payments');
            });
        } elseif ($filter === 'groups') {
            $query->where(function ($q) {
                $q->whereNotNull('group_name')
                  ->orWhereHas('clients', null, '>', 1);
            });
        } elseif ($filter === 'expired') {
            $query->where(function ($q) use ($today) {
                $q->where('status', 'Vencida')
                  ->orWhere('status', 'Inactiva')
                  ->orWhereDate('end_date', '<', $today);
            });
        }

        // Ordenamiento: las más próximas a vencer o vencidas primero
        $query->orderBy('end_date', 'asc');

        $memberships = $query->paginate(25)->withQueryString();

        $selectedPlan = !empty($selectedPlanId) ? $subscriptionPlans->firstWhere('id', $selectedPlanId) : null;

        return view('memberships', compact(
            'globalTotalMemberships',
            'totalActiveMemberships',
            'activeClientsCount',
            'topPlanName',
            'topPlanPercentage',
            'pendingDebtCountGlobal',
            'expiringCountGlobal',
            'expiringMemberships',
            'dailyPassesCount',
            'tabTotalCount',
            'tabActiveCount',
            'tabWarningCount',
            'tabCriticalCount',
            'tabPendingPaymentCount',
            'tabExpiringCount',
            'tabExpiredCount',
            'tabGroupsCount',
            'plansDistribution',
            'plans',
            'subscriptionPlans',
            'allClients',
            'memberships',
            'filter',
            'selectedPlanId',
            'selectedPlan'
        ));
    }

    /**
     * Store a new membership assignment (Individual o Grupal).
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'client_ids' => 'nullable|array',
            'client_ids.*' => 'exists:clients,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'payment_method' => 'required|in:efectivo,transferencia',
            'payment_type' => 'required|in:completo,abono',
            'abono_amount' => 'nullable|numeric|min:0',
            'voucher_number' => 'required_if:payment_method,transferencia|nullable|string|max:100',
            'group_name' => 'nullable|string|max:150',
        ], [
            'plan_id.required' => 'Debe seleccionar un plan de suscripción.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'end_date.required' => 'La fecha de corte es obligatoria.',
            'end_date.after_or_equal' => 'La fecha de corte debe ser posterior o igual a la fecha de inicio.',
            'payment_method.required' => 'Seleccione el método de pago.',
            'payment_type.required' => 'Seleccione la modalidad de pago.',
            'voucher_number.required_if' => 'El número de comprobante o referencia es obligatorio para pagos por transferencia.',
        ]);

        // Support both single client_id and client_ids array
        $clientIds = [];
        if ($request->filled('client_id')) {
            $clientIds[] = (int)$request->client_id;
        } elseif ($request->filled('client_ids') && is_array($request->client_ids)) {
            $clientIds = array_map('intval', $request->client_ids);
        }

        if (empty($clientIds)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor seleccione un atleta para registrar la asignación.'
                ], 422);
            }
            return back()->with('error', 'Debe seleccionar un atleta.');
        }

        if ($request->payment_method === 'transferencia' && empty(trim($request->voucher_number ?? ''))) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El número de comprobante o referencia es obligatorio para pagos por transferencia.'
                ], 422);
            }
            return back()->with('error', 'El comprobante es obligatorio para transferencias.');
        }

        $plan = Plan::findOrFail($request->plan_id);
        $clientCount = count($clientIds);

        // Calculate total amount based on capacity and plan
        $isGroup = $clientCount > 1 || !empty($request->group_name) || $plan->max_capacity > 1;
        $totalPrice = $isGroup ? ($plan->price * $clientCount) : $plan->price;

        // Date calculations (user-defined or calculated)
        $startDate = Carbon::parse($request->start_date);
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->end_date) 
            : $startDate->copy()->addDays($plan->validity_days);

        // Validation for partial abono
        $paymentType = $request->payment_type;
        $amountPaid = $totalPrice;
        $pendingBalance = 0;

        if ($paymentType === 'abono') {
            $minAbono = round($totalPrice * 0.25, 2);
            $maxAbono = round($totalPrice * 0.75, 2);
            $abonoInput = (float)$request->abono_amount;

            if ($abonoInput < $minAbono || $abonoInput > $maxAbono) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "El abono debe estar entre el 25% (\${$minAbono}) y el 75% (\${$maxAbono}) del total.",
                        'errors' => ['abono_amount' => ["Monto fuera del rango permitido (25% a 75%)."]]
                    ], 422);
                }
                return back()->withErrors(['abono_amount' => "El abono debe estar entre el 25% y el 75%."])->withInput();
            }

            $amountPaid = $abonoInput;
            $pendingBalance = max(0, $totalPrice - $amountPaid);
        }

        DB::beginTransaction();
        try {
            $membership = Membership::create([
                'plan_id' => $plan->id,
                'status' => 'Activa',
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'group_name' => $isGroup ? ($request->group_name ?: 'Grupo ' . $plan->name) : null,
            ]);

            $membership->clients()->sync($clientIds);

            // Payment method mapping
            $paymentMethodModel = PaymentMethod::firstOrCreate(
                ['name' => $request->payment_method === 'efectivo' ? 'Efectivo' : 'Transferencia']
            );

            Payment::create([
                'membership_id' => $membership->id,
                'payment_method_id' => $paymentMethodModel->id,
                'date' => Carbon::today()->toDateString(),
                'amount' => $amountPaid,
                'pending_balance' => $pendingBalance,
                'voucher_number' => $request->payment_method === 'transferencia' ? $request->voucher_number : null,
            ]);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Membresía asignada exitosamente!',
                    'membership_id' => $membership->id,
                ]);
            }

            return redirect()->route('memberships')->with('success', '¡Membresía asignada exitosamente!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al asignar la membresía: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error al guardar la asignación: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Renew an existing membership or liquidate outstanding debt.
     */
    public function renew(Request $request, Membership $membership)
    {
        $actionMode = $request->input('action_mode', 'renew'); // 'renew' or 'only_debt'

        // Check if there is an outstanding debt from previous payments
        $latestPayment = $membership->payments()->latest('id')->first();
        $pendingDebt = $latestPayment ? (float)$latestPayment->pending_balance : 0;

        // Case 1: User chose only to liquidate the debt without renewing
        if ($actionMode === 'only_debt') {
            $request->merge(['membership_id' => $membership->id]);
            return app(PaymentController::class)->liquidateDebt($request);
        }

        $request->validate([
            'payment_method' => 'required|in:efectivo,transferencia',
            'payment_type' => 'required|in:completo,abono',
            'abono_amount' => 'nullable|numeric|min:0',
            'voucher_number' => 'required_if:payment_method,transferencia|nullable|string|max:100',
        ], [
            'voucher_number.required_if' => 'El comprobante es obligatorio para transferencias.',
        ]);

        if ($request->payment_method === 'transferencia' && empty(trim($request->voucher_number ?? ''))) {
            return response()->json([
                'success' => false,
                'message' => 'El comprobante es obligatorio para transferencias.'
            ], 422);
        }

        $plan = $membership->plan;
        $clientCount = max(1, $membership->clients->count());
        $isGroup = $clientCount > 1 || !empty($membership->group_name);
        $planPrice = $isGroup ? ($plan->price * $clientCount) : (float)$plan->price;

        // Total price includes previous pending debt if exists
        $totalPrice = round($planPrice + $pendingDebt, 2);

        // Calculate new start and end dates
        $currentEnd = Carbon::parse($membership->end_date);
        $today = Carbon::today();
        
        // If expired, renew starts today; if still active, renew extends from current end date
        $newStart = $currentEnd->isPast() ? $today : $currentEnd->copy()->addDay();
        $newEnd = $newStart->copy()->addDays($plan->validity_days);

        $amountPaid = $totalPrice;
        $pendingBalance = 0;

        if ($request->payment_type === 'abono') {
            $minAbono = round($totalPrice * 0.25, 2);
            $maxAbono = round($totalPrice * 0.75, 2);
            $abonoInput = (float)$request->abono_amount;

            if ($abonoInput < $minAbono || $abonoInput > $maxAbono) {
                return response()->json([
                    'success' => false,
                    'message' => "El abono debe estar entre \${$minAbono} (25%) y \${$maxAbono} (75%) del total a pagar (\${$totalPrice})."
                ], 422);
            }
            $amountPaid = $abonoInput;
            $pendingBalance = max(0, round($totalPrice - $amountPaid, 2));
        }

        DB::beginTransaction();
        try {
            // Liquidar el saldo de los pagos anteriores
            $membership->payments()->where('pending_balance', '>', 0)->update(['pending_balance' => 0]);

            $membership->update([
                'status' => 'Activa',
                'start_date' => $newStart->toDateString(),
                'end_date' => $newEnd->toDateString(),
            ]);

            $paymentMethodModel = PaymentMethod::firstOrCreate(
                ['name' => $request->payment_method === 'efectivo' ? 'Efectivo' : 'Transferencia']
            );

            $firstClient = $membership->clients->first();
            Payment::create([
                'membership_id' => $membership->id,
                'payment_method_id' => $paymentMethodModel->id,
                'date' => Carbon::today()->toDateString(),
                'amount' => $amountPaid,
                'pending_balance' => $pendingBalance,
                'voucher_number' => $request->payment_method === 'transferencia' ? $request->voucher_number : null,
                'billing_name' => $firstClient ? ($firstClient->name . ' ' . $firstClient->last_name) : $membership->group_name,
                'billing_id_card' => $firstClient ? $firstClient->id_card : null,
            ]);

            DB::commit();

            $msg = $pendingDebt > 0 
                ? "¡Deuda anterior de \${$pendingDebt} saldada y membresía renovada hasta el {$newEnd->format('d/m/Y')} exitosamente!"
                : "¡Membresía renovada hasta el {$newEnd->format('d/m/Y')} exitosamente!";

            return response()->json([
                'success' => true,
                'message' => $msg,
                'new_end_date' => $newEnd->format('d/m/Y'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al renovar la membresía: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new plan in the catalog.
     */
    public function storePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0.50',
            'validity_days' => 'required|integer|min:1',
            'min_capacity' => 'nullable|integer|min:1',
            'max_capacity' => 'nullable|integer|min:1',
        ], [
            'name.required' => 'El nombre del plan es obligatorio.',
            'price.required' => 'El precio del plan es obligatorio.',
            'price.min' => 'El precio mínimo es de $0.50.',
            'validity_days.required' => 'La vigencia en días es obligatoria.',
            'validity_days.min' => 'La vigencia debe ser de al menos 1 día.',
        ]);

        try {
            $minCap = $request->filled('min_capacity') ? (int)$request->min_capacity : 1;
            $maxCap = $request->filled('max_capacity') ? (int)$request->max_capacity : $minCap;
            if ($maxCap < $minCap) {
                $maxCap = $minCap;
            }

            $plan = Plan::create([
                'name' => trim($request->name),
                'price' => (float)$request->price,
                'validity_days' => (int)$request->validity_days,
                'min_capacity' => $minCap,
                'max_capacity' => $maxCap,
            ]);

            return response()->json([
                'success' => true,
                'message' => "¡Plan '{$plan->name}' creado exitosamente en el catálogo!",
                'plan' => $plan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update plan details from catalog modal.
     */
    public function updatePlan(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0.50',
            'validity_days' => 'required|integer|min:1',
            'min_capacity' => 'nullable|integer|min:1',
            'max_capacity' => 'nullable|integer|min:1',
        ]);

        $plan->update([
            'name' => $request->name,
            'price' => $request->price,
            'validity_days' => $request->validity_days,
            'min_capacity' => $request->min_capacity ?: $plan->min_capacity,
            'max_capacity' => $request->max_capacity ?: $plan->max_capacity,
        ]);

        return response()->json([
            'success' => true,
            'message' => "¡Plan '{$plan->name}' actualizado correctamente!",
            'plan' => $plan
        ]);
    }

    /**
     * Delete a plan from the catalog.
     */
    public function destroyPlan(Plan $plan)
    {
        $membershipsCount = $plan->memberships()->count();
        if ($membershipsCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "No se puede eliminar el plan '{$plan->name}' porque tiene {$membershipsCount} membresía(s) asociadas en el sistema. Puedes modificar su precio o vigencia."
            ], 422);
        }

        try {
            $planName = $plan->name;
            $plan->delete();

            return response()->json([
                'success' => true,
                'message' => "¡Plan '{$planName}' eliminado del catálogo correctamente!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search clients for live modal autocomplete.
     */
    public function searchClients(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $clients = Client::where('name', 'like', "%{$q}%")
            ->orWhere('last_name', 'like', "%{$q}%")
            ->orWhere('id_card', 'like', "%{$q}%")
            ->orWhere('phone', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id', 'name', 'last_name', 'id_card', 'phone']);

        return response()->json($clients);
    }

    /**
     * Delete / Cancel membership.
     */
    public function destroy(Membership $membership)
    {
        try {
            $membership->clients()->detach();
            $membership->payments()->delete();
            $membership->delete();

            return response()->json([
                'success' => true,
                'message' => 'Membresía eliminada correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar membresía: ' . $e->getMessage()
            ], 500);
        }
    }
}
