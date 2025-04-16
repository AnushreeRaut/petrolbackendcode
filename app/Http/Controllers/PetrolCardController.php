<?php

namespace App\Http\Controllers;

use App\Models\AddCard;
use App\Models\PetrolCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PetrolCardController extends Controller
{
    public function getBatchNumbers(Request $request)
{
    $cardId = $request->input('card_id');

    // Get the last entry for the selected card
    $lastEntry = PetrolCard::where('card_id', $cardId)
        ->orderBy('id', 'desc')
        ->first();

    $lastBatch = $lastEntry ? (int) $lastEntry->current_batch_no : 0;

    return response()->json([
        'last_batch_no' => $lastBatch,
        'current_batch_no' => $lastBatch + 1,
    ]);
}

    // public function getBatchNumbers(Request $request)
    // {
    //     $cardId = $request->card_id;

    //     $lastCardEntry = PetrolCard::where('card_id', $cardId)
    //         ->orderBy('id', 'desc')
    //         ->first();

    //     if ($lastCardEntry) {
    //         $lastBatchNo = $lastCardEntry->current_batch_no;
    //         $currentBatchNo = $lastBatchNo + 1;

    //         return response()->json([
    //             'last_batch_no' => $lastBatchNo,
    //             'current_batch_no' => $currentBatchNo,
    //         ]);
    //     }

    //     // If no previous entries for the card
    //     return response()->json([
    //         'last_batch_no' => 0, // Default or adjust as needed
    //         'current_batch_no' => 1,
    //     ]);
    // }

    public function getPetrolCards()
    {
        // Fetch petrol card machine numbers and card numbers
        $petrolCards = AddCard::select('id', 'petrol_card_machine_no', 'petrol_card_no')->get();

        return response()->json($petrolCards);
    }


    public function index1()
    {
        $today = Carbon::today();
        $petrolCards = PetrolCard::with('addCard')
            ->whereDate('created_at', $today) // Filter records created today
            ->get();

        return response()->json($petrolCards);
    }
    public function index(Request $request)
{
    $date = $request->query('date');

    $query = PetrolCard::query();

    if ($date) {
        $query->whereDate('date', $date);
    }

    $cards = $query->get();

    return response()->json($cards);
}

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_id' => 'required|exists:add_cards,id',
            'amount' => 'required|numeric',
            'current_batch_no' => 'nullable|string',
            'last_batch_no' => 'nullable|string',
            'date' => 'required|date', // ✅ Validate date
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $petrolCard = PetrolCard::create([
            'card_id' => $request->card_id,
            'amount' => $request->amount,
            'current_batch_no' => $request->current_batch_no,
            'last_batch_no' => $request->last_batch_no,
            'added_by' => auth()->id(), // optional
            'date' => $request->date, // ✅ Save date
        ]);

        return response()->json([
            'message' => 'Petrol card created successfully!',
            'data' => $petrolCard,
        ]);
    }


    // public function store(Request $request)
    // {
    //     // Validate the incoming request
    //     $validated = $request->validate([
    //         'card_id' => 'required|exists:add_cards,id', // Ensure card_id exists in the add_cards table
    //         'amount' => 'required|numeric', // Ensure amount is a valid number
    //         'current_batch_no' => 'required|numeric',
    //         'last_batch_no' => 'required|numeric',
    //     ]);

    //     try {
    //         // Attempt to store the petrol card data
    //         $petrolCard = PetrolCard::create([
    //             'card_id' => $validated['card_id'],
    //             'amount' => $validated['amount'],
    //             'current_batch_no' => $validated['current_batch_no'],
    //             'last_batch_no' => $validated['last_batch_no'],
    //             'added_by' => auth()->id(), // Example for adding user info
    //         ]);

    //         // Mark the card as used in the add_cards table
    //         $addCard = AddCard::find($validated['card_id']);
    //         if ($addCard) {
    //             $addCard->card_used_status = true; // Set card_used_status to true
    //             $addCard->save(); // Save the updated status
    //         }

    //         // Return a success response if no errors occur
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Petrol card saved successfully!',
    //             'data' => $petrolCard,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         // Log the error for debugging
    //         Log::error('Error storing petrol card:', ['error' => $e->getMessage()]);

    //         // Return a response with an error message
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error saving petrol card. Please try again.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }


    // public function store(Request $request)
    // {
    //     // Validate the incoming request
    //     $validated = $request->validate([
    //         'card_id' => 'required|exists:add_cards,id', // Ensure card_id exists in the add_cards table
    //         'amount' => 'required|numeric', // Ensure amount is a valid number
    //         'current_batch_no' => 'required|numeric',
    //         'last_batch_no' => 'required|numeric',
    //     ]);

    //     try {
    //         // Attempt to store the petrol card data
    //         $petrolCard = PetrolCard::create([
    //             'card_id' => $validated['card_id'],
    //             'amount' => $validated['amount'],
    //             'current_batch_no' => $validated['current_batch_no'],
    //             'last_batch_no' => $validated['last_batch_no'],
    //             'added_by' => auth()->id(), // Example for adding user info
    //         ]);

    //         // Return a success response if no errors occur
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Petrol card saved successfully!',
    //             'data' => $petrolCard,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         // Log the error for debugging
    //         Log::error('Error storing petrol card:', ['error' => $e->getMessage()]);

    //         // Return a response with an error message
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error saving petrol card. Please try again.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }


    public function update(Request $request, PetrolCard $petrolCard)
    {
        // Step 1: Validate the incoming request data
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0', // Ensure amount is numeric and non-negative
            'card_id' => 'required|exists:add_cards,id', // Ensure the card_id exists in add_cards table
            'current_batch_no' => 'nullable|string',
            'last_batch_no' => 'nullable|string',
        ]);

        try {
            // Step 2: Update the petrol card details
            $petrolCard->update([
                'amount' => $validated['amount'],
                'card_id' => $validated['card_id'], // Update card_id if necessary
                'current_batch_no' => $validated['current_batch_no'],
                'last_batch_no' => $validated['last_batch_no'],
                'updated_by' => auth()->id(), // Assuming you want to store the user who updated the card
            ]);

            // Step 3: Return success response with updated data
            return response()->json([
                'success' => true,
                'message' => 'Petrol card updated successfully!',
                'data' => $petrolCard,
            ], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating petrol card:', ['error' => $e->getMessage()]);

            // Step 4: Return an error response if something goes wrong
            return response()->json([
                'success' => false,
                'message' => 'Error updating petrol card. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function destroy(PetrolCard $petrolCard)
    {
        $petrolCard->delete();
        return response()->json(['message' => 'Petrol card deleted successfully']);
    }
}
