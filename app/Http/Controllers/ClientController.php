<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ClientController extends Controller
{
    /**
     * Search clients via AJAX
     */
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (!$query) {
            return response()->json([]);
        }

        $clients = Client::where('id_card', 'like', "%{$query}%")
            ->orWhere('name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhereRaw("CONCAT(name, ' ', last_name) LIKE ?", ["%{$query}%"])
            ->limit(10)
            ->get(['id', 'id_card', 'name', 'last_name', 'phone']);

        return response()->json($clients);
    }

    /**
     * Authorize partial payment (Abono) with admin key / password.
     */
    public function authorizeAbono(Request $request)
    {
        $password = $request->input('password');

        if (empty($password)) {
            return response()->json([
                'success' => false,
                'message' => 'Debe ingresar la clave de autorización del administrador.'
            ], 422);
        }

        $isAuthorized = false;

        // 1. Check logged-in user
        if (auth()->check() && \Illuminate\Support\Facades\Hash::check($password, auth()->user()->password)) {
            $isAuthorized = true;
        }

        // 2. Check master admin (ID 1) if not logged in user
        if (!$isAuthorized) {
            $masterAdmin = \App\Models\User::find(1);
            if ($masterAdmin && \Illuminate\Support\Facades\Hash::check($password, $masterAdmin->password)) {
                $isAuthorized = true;
            }
        }

        // 3. Master supervisor keys
        $masterKeys = ['ares2026'];
        if (!$isAuthorized && in_array($password, $masterKeys)) {
            $isAuthorized = true;
        }

        if ($isAuthorized) {
            return response()->json([
                'success' => true,
                'message' => '¡Autorización concedida con éxito! Abono habilitado.',
                'token' => hash('sha256', 'abono_authorized_' . date('Y-m-d'))
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Clave de autorización incorrecta. Solo el administrador o supervisor puede autorizar abonos parciales.'
        ], 403);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::with([
            'user',
            'memberships' => function ($query) {
                $query->orderBy('end_date', 'desc');
            }
        ])->get();

        $now = \Carbon\Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();

        $totalActivos = 0;
        $pagosEnRiesgo = 0;
        $vencidos = 0;
        $nuevosMes = Client::where('created_at', '>=', $startOfMonth)->count();

        foreach ($clients as $client) {
            $latestMembership = $client->memberships->first();

            if ($latestMembership) {
                $endDate = \Carbon\Carbon::parse($latestMembership->end_date);
                $daysDiff = $now->diffInDays($endDate, false); // false means negative if in past

                if ($daysDiff >= 0) {
                    $totalActivos++;
                    if ($daysDiff <= 3) {
                        $pagosEnRiesgo++;
                    }
                } else {
                    $vencidos++;
                    if ($daysDiff >= -7) {
                        // Consider recently expired as at risk too, or separate them. Let's just count them in vencidos.
                    }
                }
            }
        }

        return view('clientes', compact('clients', 'totalActivos', 'pagosEnRiesgo', 'vencidos', 'nuevosMes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $plans = clone \App\Models\Plan::where('max_capacity', 1)->where('name', '!=', 'Valor Diario')->get();
        $groupName = $request->query('group');
        $groupMembershipId = $request->query('membership_id');

        $groupPlanId = null;
        if ($groupMembershipId) {
            $membership = \App\Models\Membership::find($groupMembershipId);
            if ($membership) {
                $groupPlanId = $membership->plan_id;
                // Ensure the group's plan is in the $plans collection so it can be rendered
                if (!$plans->contains('id', $groupPlanId)) {
                    $groupPlan = \App\Models\Plan::find($groupPlanId);
                    if ($groupPlan) {
                        $plans->push($groupPlan);
                    }
                }
            }
        }

        return view('clientes.create', compact('plans', 'groupName', 'groupMembershipId', 'groupPlanId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'gender' => 'required|in:M,F',
                'id_card' => ['required', 'string', 'size:10', 'unique:clients,id_card'],
                'phone' => 'required|string|max:15',
                'birth_date' => 'required|date|before_or_equal:today',
                'plan_id' => 'required|exists:plans,id',
                'payment_type' => 'nullable|in:completo,abono',
                'abono_amount' => 'nullable|numeric|min:0.01',
                'payment_method' => 'required|in:efectivo,transferencia',
                'voucher_number' => 'nullable|string|max:30',
                'billing_name' => 'nullable|string|max:100',
                'billing_id_card' => 'nullable|string|max:20',
                'billing_address' => 'nullable|string|max:255',
            ]);

            $email = $request->id_card . '@aresgym.local'; // Auto-generated email

            \Illuminate\Support\Facades\DB::beginTransaction();

            // Ensure role exists
            $role = Role::firstOrCreate(['type' => 'Cliente']);

            // Create User
            $user = User::create([
                'role_id' => $role->id,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $email,
                'password' => Hash::make($request->id_card),
                'status' => true,
            ]);

            // Create Client
            $client = Client::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'id_card' => $request->id_card,
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'entry_date' => Carbon::now()->toDateString(),
            ]);

            // Prevent single users from registering group plans UNLESS they are being added to an existing group
            $plan = \App\Models\Plan::findOrFail($request->plan_id);
            $isBeingAddedToGroup = $request->filled('group_name') && $request->filled('group_membership_id');

            if ($plan->min_capacity > 1 && !$isBeingAddedToGroup) {
                return response()->json([
                    'success' => false,
                    'errors' => ['plan_id' => ['Para registrar un plan grupal, debes usar la ventana de Nuevo Plan Grupal (Mínimo 2 personas).']],
                    'message' => 'Error de validación.'
                ], 422);
            }

            if ($isBeingAddedToGroup) {
                $groupMembership = \App\Models\Membership::find($request->group_membership_id);
                if ($groupMembership) {
                    $paymentMethodName = $request->payment_method == 'efectivo' ? 'Efectivo' : 'Transferencia';
                    $pm = \App\Models\PaymentMethod::firstOrCreate(['name' => $paymentMethodName]);

                    \App\Services\GroupManager::addClientToGroup($client, $groupMembership, $pm->id, $request->voucher_number);

                    \Illuminate\Support\Facades\DB::commit();
                    return response()->json([
                        'success' => true,
                        'message' => 'Atleta registrado y añadido al grupo ' . $request->group_name . ' exitosamente.',
                        'client_id' => $client->id
                    ]);
                }
            }

            // Create Membership
            $membership = \App\Models\Membership::create([
                'plan_id' => $plan->id,
                'status' => 'Activa',
                'start_date' => Carbon::now()->toDateString(),
                'end_date' => Carbon::now()->addDays($plan->validity_days)->toDateString(),
            ]);

            // Link Client and Membership
            $client->memberships()->attach($membership->id);

            // Get or Create Payment Method
            $paymentMethodName = $request->payment_method == 'efectivo' ? 'Efectivo' : 'Transferencia';
            $paymentMethod = \App\Models\PaymentMethod::firstOrCreate(['name' => $paymentMethodName]);

            // Determine Payment Amount & Pending Balance (Abono calculation)
            $planPrice = (float) $plan->price;
            $isAbono = $request->input('payment_type') === 'abono';

            if ($isAbono) {
                $amountPaid = (float) $request->input('abono_amount', 0);
                $minAbono = round($planPrice * 0.25, 2);
                $maxAbono = round($planPrice * 0.75, 2);

                if ($amountPaid <= 0) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['abono_amount' => ['El monto del abono debe ser mayor a $0.00. No se permiten valores negativos ni cero.']],
                        'message' => 'Error en el monto del abono.'
                    ], 422);
                }

                if ($amountPaid < $minAbono) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['abono_amount' => ['El abono parcial ($' . number_format($amountPaid, 2) . ') no puede ser inferior al 25% ($' . number_format($minAbono, 2) . ') del valor del plan ($' . number_format($planPrice, 2) . ').']],
                        'message' => 'El abono es inferior al mínimo permitido (25%).'
                    ], 422);
                }

                if ($amountPaid > $maxAbono) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['abono_amount' => ['El abono parcial ($' . number_format($amountPaid, 2) . ') no puede ser superior al 75% ($' . number_format($maxAbono, 2) . ') del valor del plan ($' . number_format($planPrice, 2) . '). Si cancela el total o desea abonar más, seleccione Pago Completo.']],
                        'message' => 'El abono supera el máximo permitido (75%).'
                    ], 422);
                }

                $pendingBalance = max(0, round($planPrice - $amountPaid, 2));
            } else {
                $amountPaid = $planPrice;
                $pendingBalance = 0;
            }

            // Create Payment with Client's own details for billing
            \App\Models\Payment::create([
                'membership_id' => $membership->id,
                'payment_method_id' => $paymentMethod->id,
                'date' => Carbon::now()->toDateString(),
                'amount' => $amountPaid,
                'pending_balance' => $pendingBalance,
                'voucher_number' => $request->payment_method == 'transferencia' ? $request->voucher_number : null,
                'billing_name' => $client->name . ' ' . $client->last_name,
                'billing_id_card' => $client->id_card,
                'billing_address' => null,
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cliente registrado exitosamente.',
                'client_id' => $client->id
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Error de validación.'
            ], 422);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado al procesar el registro.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new group plan and its members.
     */
    public function storeGroup(Request $request)
    {
        try {
            // Validation
            $request->validate([
                'group_name' => 'nullable|string|max:100',
                'payment_type' => 'nullable|in:completo,abono',
                'abono_amount' => 'nullable|numeric|min:0.01',
                'payment_method' => 'required|in:efectivo,transferencia',
                'voucher_number' => 'required_if:payment_method,transferencia|nullable|string|max:100',
                'members' => 'required|array|min:2',
                'members.*.existing_client_id' => 'nullable|exists:clients,id',
                'members.*.name' => 'required_without:members.*.existing_client_id|nullable|string|max:100',
                'members.*.last_name' => 'required_without:members.*.existing_client_id|nullable|string|max:100',
                'members.*.gender' => 'required_without:members.*.existing_client_id|nullable|in:M,F',
                'members.*.id_card' => ['required_without:members.*.existing_client_id', 'nullable', 'string', 'max:10', 'distinct'],
                'members.*.phone' => 'required_without:members.*.existing_client_id|nullable|string|max:15',
            ]);

            // Check uniqueness manually for all new id_cards in the members array against the DB
            $newMembersIdCards = collect($request->members)->filter(function ($m) {
                return empty($m['existing_client_id']) && !empty($m['id_card']);
            })->pluck('id_card')->toArray();

            if (count($newMembersIdCards) > 0) {
                $existingClients = \App\Models\Client::whereIn('id_card', $newMembersIdCards)->pluck('id_card')->toArray();
                if (count($existingClients) > 0) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['members' => ['Una o más cédulas ya están registradas en el sistema: ' . implode(', ', $existingClients)]],
                        'message' => 'Error de validación.'
                    ], 422);
                }
            }

            // Determine Plan ID and Price
            $memberCount = count($request->members);
            $planId = $memberCount >= 4 ? 3 : 2;
            $plan = \App\Models\Plan::findOrFail($planId);
            $totalAmount = $plan->price * $memberCount;

            \DB::beginTransaction();

            // Create Membership
            $membership = \App\Models\Membership::create([
                'plan_id' => $plan->id,
                'group_name' => $request->group_name,
                'status' => 'Activa',
                'start_date' => now(),
                'end_date' => now()->addDays($plan->validity_days),
            ]);

            // Create Clients and Attach
            $firstClientId = null;
            $firstClient = null;
            foreach ($request->members as $index => $memberData) {
                if (!empty($memberData['existing_client_id'])) {
                    $client = \App\Models\Client::findOrFail($memberData['existing_client_id']);
                } else {
                    $client = \App\Models\Client::create([
                        'user_id' => auth()->id() ?? 1,
                        'name' => $memberData['name'],
                        'last_name' => $memberData['last_name'],
                        'gender' => $memberData['gender'] ?? 'M',
                        'id_card' => $memberData['id_card'],
                        'phone' => $memberData['phone'],
                        'birth_date' => '2000-01-01', // Default date for group members since it's not collected
                        'entry_date' => now(),
                        'status' => 'Activo',
                    ]);
                }

                if ($index === 0) {
                    $firstClientId = $client->id;
                    $firstClient = $client;
                }

                $membership->clients()->attach($client->id);
            }

            // Determine Payment Amount & Pending Balance (Abono calculation)
            $isAbono = $request->input('payment_type') === 'abono';
            if ($isAbono) {
                $amountPaid = (float) $request->input('abono_amount', 0);
                $minAbono = round($totalAmount * 0.25, 2);
                $maxAbono = round($totalAmount * 0.75, 2);

                if ($amountPaid <= 0) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['abono_amount' => ['El monto del abono debe ser mayor a $0.00. No se permiten valores negativos ni cero.']],
                        'message' => 'Error en el monto del abono.'
                    ], 422);
                }

                if ($amountPaid < $minAbono) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['abono_amount' => ['El abono parcial ($' . number_format($amountPaid, 2) . ') no puede ser inferior al 25% ($' . number_format($minAbono, 2) . ') del valor total del plan grupal ($' . number_format($totalAmount, 2) . ').']],
                        'message' => 'El abono grupal es inferior al mínimo permitido (25%).'
                    ], 422);
                }

                if ($amountPaid > $maxAbono) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['abono_amount' => ['El abono parcial ($' . number_format($amountPaid, 2) . ') no puede ser superior al 75% ($' . number_format($maxAbono, 2) . ') del valor total del plan grupal ($' . number_format($totalAmount, 2) . '). Si cancela el total o desea abonar más, seleccione Pago Completo.']],
                        'message' => 'El abono grupal supera el máximo permitido (75%).'
                    ], 422);
                }

                $pendingBalance = max(0, round($totalAmount - $amountPaid, 2));
            } else {
                $amountPaid = $totalAmount;
                $pendingBalance = 0;
            }

            // Create Payment
            $paymentMethodId = $request->payment_method === 'efectivo' ? 1 : 2;

            \App\Models\Payment::create([
                'membership_id' => $membership->id,
                'payment_method_id' => $paymentMethodId,
                'date' => now(),
                'amount' => $amountPaid,
                'pending_balance' => $pendingBalance,
                'voucher_number' => $request->voucher_number,
                'billing_name' => $request->group_name ?: ($firstClient ? ($firstClient->name . ' ' . $firstClient->last_name) : 'Plan Grupal'),
                'billing_id_card' => $firstClient ? $firstClient->id_card : null,
                'billing_address' => null,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Plan grupal registrado exitosamente.',
                'membership_id' => $membership->id,
                'first_client_id' => $firstClientId
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Error de validación.'
            ], 422);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error en storeGroup: ' . $e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado al procesar el registro.',
                'error' => $e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine()
            ], 500);
        }
    }

    /**
     * Generate HTML receipt / invoice for the client's payment.
     */
    public function invoice(Request $request, Client $client)
    {
        $client->load(['memberships.plan', 'memberships.payments.paymentMethod']);

        $paymentId = $request->query('payment_id');
        if ($paymentId) {
            $payment = \App\Models\Payment::with(['membership.plan', 'paymentMethod'])->find($paymentId);
            $membership = $payment ? $payment->membership : $client->memberships->last();
        } else {
            $membership = $client->memberships->last();
            $payment = $membership ? $membership->payments->last() : null;
        }

        return view('clientes.invoice', [
            'client' => $client,
            'membership' => $membership,
            'payment' => $payment
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::with([
            'user',
            'memberships.plan',
            'memberships.payments.paymentMethod',
            'measurements' => function ($query) {
                $query->orderBy('date', 'asc');
            },
            'nutritionalPlans.meals.food'
        ])->findOrFail($id);

        $latestMeasurement = $client->measurements->last();
        $latestNutritionalPlan = $client->nutritionalPlans->last();

        // Chart Data
        $chartLabels = $client->measurements->map(function ($m) {
            return \Carbon\Carbon::parse($m->date)->format('M d');
        })->values()->toJson();

        $chartWeights = $client->measurements->pluck('weight')->values()->toJson();
        $chartFats = $client->measurements->pluck('fat')->values()->toJson();
        $chartMuscles = $client->measurements->pluck('muscle')->values()->toJson();

        // Payments Data
        $recentPayments = $client->memberships->flatMap(function ($membership) {
            return $membership->payments;
        })->sortByDesc('created_at')->take(5)->values();

        return view('clientes.show', compact('client', 'latestMeasurement', 'latestNutritionalPlan', 'chartLabels', 'chartWeights', 'chartFats', 'chartMuscles', 'recentPayments'));
    }

    /**
     * Toggle athlete's decision for measurement & nutritional plan protocol
     */
    public function toggleMeasurements(Request $request, string $id)
    {
        $client = Client::findOrFail($id);
        $accepts = $request->input('accepts', null);

        if ($accepts !== null) {
            $client->accepts_measurements = filter_var($accepts, FILTER_VALIDATE_BOOLEAN);
        } else {
            $client->accepts_measurements = !$client->accepts_measurements;
        }

        $client->save();

        $message = $client->accepts_measurements
            ? 'El atleta ha aceptado la toma de medidas corporales y el acceso al plan nutricional.'
            : 'El atleta ha optado por no realizar toma de medidas ni seguimiento nutricional.';

        return redirect()->route('clientes.show', $client->id)->with('success', $message);
    }

    /**
     * Toggle athlete's status (Active / Inactive / Deactivated)
     */
    public function toggleStatus(string $id)
    {
        $client = Client::findOrFail($id);
        if ($client->user) {
            $client->user->status = !$client->user->status;
            $client->user->save();

            $statusText = $client->user->status ? 'reactivado con éxito' : 'dado de baja (inactivo)';
            return redirect()->route('clientes.index')->with('success', "El atleta {$client->name} {$client->last_name} ha sido {$statusText}.");
        }

        return redirect()->route('clientes.index')->with('error', 'No se encontró el usuario vinculado al atleta.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = Client::findOrFail($id);
        return view('clientes.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $client = Client::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|in:M,F',
            'email' => 'required|email|unique:users,email,' . $client->user_id,
            'phone' => 'nullable|string|max:15',
            'entry_date' => 'nullable|date',
        ]);

        // Update user
        if ($client->user) {
            $client->user->update([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
            ]);
        }

        // Update client (id_card and birth_date are not editable)
        $client->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'entry_date' => $request->entry_date,
        ]);

        return redirect()->route('clientes.show', $client->id)->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);
        $client->user->delete();
        $client->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }

    /**
     * Remove a client from a group membership.
     */
    public function removeFromGroup(Request $request, string $clientId, string $membershipId)
    {
        $client = Client::findOrFail($clientId);
        $membership = \App\Models\Membership::findOrFail($membershipId);

        \App\Services\GroupManager::removeClientFromGroup($client, $membership);

        return redirect()->route('clientes.show', $client->id)->with('success', 'Cliente retirado del grupo exitosamente.');
    }

    /**
     * Add a client to an existing group membership.
     */
    public function addToGroup(Request $request, string $membershipId)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'payment_method' => 'required|in:efectivo,transferencia',
            'voucher_number' => 'nullable|string',
        ]);

        $client = Client::findOrFail($request->client_id);
        $membership = \App\Models\Membership::findOrFail($membershipId);

        // Verify membership is group eligible (monthly)
        if (!str_contains($membership->plan->name, 'Mensual')) {
            return redirect()->back()->with('error', 'Solo los planes mensuales pueden formar grupos.');
        }

        $paymentMethodName = $request->payment_method == 'efectivo' ? 'Efectivo' : 'Transferencia';
        $paymentMethod = \App\Models\PaymentMethod::firstOrCreate(['name' => $paymentMethodName]);

        \App\Services\GroupManager::addClientToGroup($client, $membership, $paymentMethod->id, $request->voucher_number);

        return redirect()->back()->with('success', 'Cliente agregado al grupo exitosamente.');
    }
}

