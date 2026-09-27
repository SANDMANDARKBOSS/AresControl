<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function index()
    {
        // Get all unique group names and their stats including status and end_date
        $groups = \App\Models\Membership::whereNotNull('group_name')
            ->select(
                'group_name',
                \Illuminate\Support\Facades\DB::raw('MAX(status) as status'),
                \Illuminate\Support\Facades\DB::raw('MAX(end_date) as end_date'),
                \Illuminate\Support\Facades\DB::raw('COUNT(DISTINCT client_membership.client_id) as members_count')
            )
            ->leftJoin('client_membership', 'memberships.id', '=', 'client_membership.membership_id')
            ->groupBy('group_name')
            ->get();

        // Count total active clients across all groups (for the aforo indicator)
        $totalGroupAthletes = $groups->sum('members_count');
        $maxCapacity = 120;
        $capacityPercentage = min(100, round(($totalGroupAthletes / $maxCapacity) * 100));

        return view('grupos.index', compact('groups', 'totalGroupAthletes', 'capacityPercentage', 'maxCapacity'));
    }

    public function show($name)
    {
        // Get all memberships for this group
        $memberships = Membership::where('group_name', $name)->with('clients')->get();
        
        // Build an array of clients with their specific membership ID for this group
        $clients = collect();
        foreach ($memberships as $membership) {
            foreach ($membership->clients as $client) {
                // Check if client is already in collection to avoid duplicates
                if (!$clients->contains('id', $client->id)) {
                    $client->group_membership_id = $membership->id;
                    $clients->push($client);
                }
            }
        }

        // Get all clients NOT in this group to populate the dropdown
        $clientIdsInGroup = $clients->pluck('id')->toArray();
        $availableClients = Client::whereNotIn('id', $clientIdsInGroup)->orderBy('name')->get();

        return view('grupos.show', compact('name', 'clients', 'availableClients'));
    }

    public function edit($name)
    {
        return view('grupos.edit', compact('name'));
    }

    public function update(Request $request, $name)
    {
        $request->validate([
            'new_name' => 'required|string|max:100'
        ]);

        Membership::where('group_name', $name)->update([
            'group_name' => $request->new_name
        ]);

        return redirect()->route('grupos.index')->with('success', 'Nombre del grupo actualizado exitosamente.');
    }
}
