<?php

namespace App\Services;

use App\Models\Membership;
use App\Models\Plan;
use App\Models\Client;
use App\Models\Payment;
use Carbon\Carbon;

class GroupManager
{
    /**
     * Recalculates and updates the plan of a membership based on the number of clients attached to it.
     */
    public static function reevaluatePlan(Membership $membership)
    {
        $count = $membership->clients()->count();

        if ($count == 0) {
            // No clients left, archive the membership
            $membership->update(['status' => 'Inactiva']);
            return;
        }

        // Determine the correct plan based on count
        $newPlanName = '';
        if ($count == 1) {
            $newPlanName = 'Plan Mensual Individual';
        } elseif ($count >= 2 && $count <= 3) {
            $newPlanName = 'Plan Mensual Grupal 2-3';
        } elseif ($count >= 4) {
            $newPlanName = 'Plan Mensual Grupal 4+';
        }

        // We assume we are dealing with 'Mensual' durations. 
        // If the original plan was Trimestral/Semestral/Anual, we shouldn't change it 
        // to a group plan since group plans are only Monthly as per requirements.
        $currentPlan = $membership->plan;
        if (!str_contains($currentPlan->name, 'Mensual')) {
            // Wait, if it's not monthly, we don't apply group dynamics? 
            // The requirements specifically mentioned group plans changing.
            return; 
        }

        $newPlan = Plan::where('name', $newPlanName)->first();
        if ($newPlan && $membership->plan_id != $newPlan->id) {
            $membership->update(['plan_id' => $newPlan->id]);
        }
        
        // If it was inactive but now has clients, reactivate if dates are valid
        if ($membership->status === 'Inactiva' && Carbon::now()->startOfDay()->lte(Carbon::parse($membership->end_date)->startOfDay())) {
            $membership->update(['status' => 'Activa']);
        }
    }

    /**
     * Adds a client to an existing membership group.
     */
    public static function addClientToGroup(Client $client, Membership $membership, $paymentMethodId, $voucherNumber = null)
    {
        // 1. Attach client to the shared membership
        if (!$client->memberships()->where('membership_id', $membership->id)->exists()) {
            $client->memberships()->attach($membership->id);
        }

        // 2. Re-evaluate the group's plan
        self::reevaluatePlan($membership);

        // 3. Register payment for the newly added client
        // The user stated: "se le cobra la tarifa grupal completa".
        // The group's plan was just re-evaluated. So we use the NEW plan price.
        $membership->refresh();
        $planPrice = $membership->plan->price;

        Payment::create([
            'membership_id' => $membership->id,
            'payment_method_id' => $paymentMethodId,
            'date' => Carbon::now()->toDateString(),
            'amount' => $planPrice,
            'pending_balance' => 0,
            'voucher_number' => $voucherNumber,
        ]);

        return true;
    }

    /**
     * Removes a client from a membership group.
     */
    public static function removeClientFromGroup(Client $client, Membership $membership)
    {
        // 1. Detach client
        $client->memberships()->detach($membership->id);

        // 2. Re-evaluate the group's plan
        self::reevaluatePlan($membership);
        
        return true;
    }
}
