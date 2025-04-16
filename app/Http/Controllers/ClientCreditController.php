<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientCredit;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class ClientCreditController extends Controller
{
    // Retrieve all clients
       public function index()
    {
        $clients = ClientCredit::with('vehicles')->get(); // Eager-load vehicles
        return response()->json($clients);
    }



    public function updateStatus(Request $request, $id)
    {
        $client = ClientCredit::findOrFail($id);
        $client->status = $request->status;
        $client->save();

        return response()->json(['success' => true, 'status' => $client->status]);
    }




    // Store a new client// Store a new client
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'client_name' => 'required|string|max:255',
                'mobile_number' => 'required|string|max:15',
                'address' => 'nullable|string',
                'firm_name' => 'nullable|string|max:255',
                'bank_name' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:20',
                'ifsc_code' => 'nullable|string|max:15',
                'branch_name' => 'nullable|string|max:255',
                'account_type' => 'nullable|string|max:50',
                'has_vehicle' => 'nullable|boolean',
                'vehicles' => 'nullable|array',
                'vehicles.*.vehicle_no' => 'nullable|string|max:255|distinct',
                'vehicles.*.company_name' => 'nullable|string|max:255',
                'vehicles.*.description' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $validatedData['added_by'] = auth()->id();
            $validatedData['updated_by'] = auth()->id();

            $client = ClientCredit::create($validatedData);

            if (!empty($validatedData['vehicles'])) {
                foreach ($validatedData['vehicles'] as $vehicleData) {
                    $vehicle = new Vehicle($vehicleData);
                    $client->vehicles()->save($vehicle);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Client added successfully!', 'client' => $client->load('vehicles')], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to add client.', 'error' => $e->getMessage()], 500);
        }
    }

    // Update an existing client
    public function update(Request $request, $id)
    {
        $client = ClientCredit::findOrFail($id);

        // Validate the incoming data
        $validatedData = $request->validate([
            'client_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:15',
            'address' => 'nullable|string',
            'firm_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:20',
            'ifsc_code' => 'nullable|string|max:15',
            'branch_name' => 'nullable|string|max:255',
            'account_type' => 'nullable|string|max:50',
            'has_vehicle' => 'boolean', // Validate has_vehicle
            'vehicles' => 'nullable|array', // Validate vehicles array
            'vehicles.*.vehicle_no' => 'required_with:vehicles|string|max:255',//|distinct', //unique:vehicles,vehicle_no
            'vehicles.*.company_name' => 'nullable|string|max:255',
            'vehicles.*.description' => 'nullable|string',
        ]);
        $validatedData['updated_by'] = auth()->id();
        DB::beginTransaction();
        try {
            // Update the client's data
            $client->update($validatedData);

            // Delete existing vehicles
            $client->vehicles()->delete();

            // Create the vehicles
            if (isset($validatedData['vehicles'])) {
                foreach ($validatedData['vehicles'] as $vehicleData) {
                    $vehicle = new Vehicle($vehicleData);
                    $client->vehicles()->save($vehicle);
                }
            }
            DB::commit();
            return response()->json(['message' => 'Client updated successfully!', 'client' => $client->load('vehicles')], 200);
        } catch (\Exception $e) {
            // If any error occurs, rollback the transaction
            DB::rollback();
            return response()->json(['message' => 'Failed to update client.', 'error' => $e->getMessage()], 500);
        }
    }
    // Delete a client
    public function destroy($id)
    {
        $client = ClientCredit::findOrFail($id);
        $client->delete();

        return response()->json(['message' => 'Client deleted successfully!']);
    }

}
