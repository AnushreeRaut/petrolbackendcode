<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::all();
        return response()->json($policies);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'policy_holder_name' => 'required|string',
            'policy_name' => 'required|string',
            'policy_no' => 'required|string',
            'policy_start_date' => 'required|date',
            'policy_end_date' => 'required|date',
            'paying_term' => 'required|string',
            'date' => 'required|date',
            'policy_amt' => 'required|numeric',
            'agent_name' => 'required|string',
            'contact_number' => 'required|string',
            'payment_mode' => 'required|string',
            'payment_date' => 'required|date',
            'payment_amt' => 'required|numeric',
        ]);

        $policy = Policy::create($validated);

        return response()->json($policy, 201);
    }

    public function show($id)
    {
        $policy = Policy::findOrFail($id);
        return response()->json($policy);
    }

    public function update(Request $request, $id)
    {
        $policy = Policy::findOrFail($id);

        $validated = $request->validate([
            'policy_holder_name' => 'string',
            'policy_name' => 'string',
            'policy_no' => 'string',
            'policy_start_date' => 'date',
            'policy_end_date' => 'date',
            'paying_term' => 'string',
            'date' => 'date',
            'policy_amt' => 'numeric',
            'agent_name' => 'string',
            'contact_number' => 'string',
            'payment_mode' => 'string',
            'payment_date' => 'date',
            'payment_amt' => 'numeric',
        ]);

        $policy->update($validated);

        return response()->json($policy);
    }

    public function destroy($id)
    {
        $policy = Policy::findOrFail($id);
        $policy->delete();

        return response()->json(['message' => 'Policy record deleted successfully.']);
    }
}
