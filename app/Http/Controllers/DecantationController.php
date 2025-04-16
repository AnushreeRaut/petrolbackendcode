<?php

namespace App\Http\Controllers;

use App\Models\Decantination;
use App\Models\PetrolInvoiceFeeding;
use App\Models\Tank;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DecantationController extends Controller
{


    public function index()
    {
        // Get today's date
        $today = Carbon::today();

        // Fetch decantination records for today
        $decantations = Decantination::whereDate('created_at', $today)->get();

        return response()->json([
            'success' => true,
            'data' => $decantations,
        ]);
    }

    public function getInvoiceData()
    {
        // Get today's date in YYYY-MM-DD format
        $today = Carbon::today();

        // Fetch only today's invoices
        $invoices = PetrolInvoiceFeeding::select('id', 'invoice_no', 'created_at')
            ->whereDate('created_at', $today) // Filter invoices by today's date
            ->get();

        return response()->json($invoices);
    }


    // public function getInvoiceData()
    // {
    //     // Fetch invoice_no and other required fields
    //     $invoices = PetrolInvoiceFeeding::select('id', 'invoice_no')->get();

    //     return response()->json($invoices);
    // }

    public function getInvoiceDetails($invoiceNo)
    {
        $tankDetails = PetrolInvoiceFeeding::where('invoice_no', $invoiceNo)
            ->with('tank')  // Eager load the tank relationship
            ->get();

        return response()->json($tankDetails);
    }

    public function getAllTanks()
    {
        try {
            // Fetch all tanks from the tanks table
            $tanks = Tank::all();

            // Return the tanks as a JSON response
            return response()->json($tanks, 200);
        } catch (\Exception $e) {
            // Handle any exceptions that might occur
            return response()->json(['error' => 'Failed to fetch tanks'], 500);
        }
    }
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'invoice_number' => 'required',
            'tank_1_ms' => 'required|numeric',
            'tank_2_speed' => 'required|numeric',
            'tank_3_hsd' => 'required|numeric',
            'total_kl' => 'required|numeric',
        ]);

        try {
            // Save the data
            Decantination::create([
                'invoice_number' => $validated['invoice_number'],
                'tank_1_ms' => $validated['tank_1_ms'],
                'tank_2_speed' => $validated['tank_2_speed'],
                'tank_3_hsd' => $validated['tank_3_hsd'],
                'total_kl' => $validated['total_kl'],
                'added_by' => Auth::id(), // Use logged-in user ID
            ]);

            return response()->json(['message' => 'Data saved successfully.'], 200);
        } catch (\Exception $e) {
            // Return detailed error message
            return response()->json([
                'message' => 'Failed to save data.',
                'error' => $e->getMessage() // Include exception message for debugging
            ], 500);
        }
    }

    // public function getInvoiceDetails($invoice_no)
    // {
    //     $data = PetrolInvoiceFeeding::where('invoice_no', $invoice_no)
    //         ->with('tank') // Assuming a relationship with the tanks table
    //         ->get(['tank_id', 'kl_qty']); // Include required fields

    //     return response()->json($data);
    // }
}
