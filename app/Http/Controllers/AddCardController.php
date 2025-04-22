<?php

namespace App\Http\Controllers;

use App\Models\BankDepositAddCard;
use Illuminate\Http\Request;
use App\Models\AddCard;
use Illuminate\Support\Facades\DB;

class AddCardController extends Controller
{
    public function index()
    {
        $cards = AddCard::with('bankDeposits')->get(); // Adjusted relation name
        return response()->json($cards);
    }

    public function getAddCards()
    {
        $addCards = AddCard::with(['bankDeposits.bankDeposit'])->get();

        return response()->json($addCards);
    }



    // public function index()
    // {
    //     $cards = AddCard::all();
    //     return response()->json($cards);
    // }
    public function batchshow()
    {
        $cards = AddCard::with('bankDeposits.bankDeposit')->get(); // Adjusted relation name
        $lastCard = AddCard::latest()->first(); // Fetch the last added record

        return response()->json([
            'cards' => $cards,
            'lastBatchNo' => $lastCard ? $lastCard->current_batch_no : null,
        ]);
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'petrol_card_machine_no' => 'required|string|max:255',
                'petrol_card_no' => 'required|string|max:255',
                'batch_no' => 'required|string',
                'add_bank_deposit_id' => 'required|exists:add_bank_deposits,id',
                'tid_no' => 'required|array', // Ensure tid_no is an array
                'tid_no.*' => 'required|integer', // Validate each TID number
                'narration' => 'required|string|max:255',
            ]);

            DB::beginTransaction();

            // Create a new card
            $card = AddCard::create([
                'petrol_card_machine_no' => $validated['petrol_card_machine_no'],
                'petrol_card_no' => $validated['petrol_card_no'],
                'batch_no' => $validated['batch_no'],
                'added_by' => auth()->id(),
            ]);

            // Store each TID number separately
            $bankDepositAddCards = [];
            foreach ($validated['tid_no'] as $tid_no) {
                $bankDepositAddCards[] = BankDepositAddCard::create([
                    'add_bank_deposit_id' => $validated['add_bank_deposit_id'],
                    'add_card_id' => $card->id,
                    'tid_no' => $tid_no, // Store each TID
                    'narration' => $validated['narration'],
                ]);
            }

            DB::commit();

            return response()->json([
                'card' => $card,
                'bank_deposit_add_cards' => $bankDepositAddCards,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to save data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    //     public function store(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'petrol_card_machine_no' => 'required|string|max:255', // Adjust as per your requirements
    //             'petrol_card_no' => 'required|string|max:255', // Assuming 'add_cards' is a table
    //             'batch_no' => 'required|string',
    //             'add_bank_deposit_id' => 'required|exists:add_bank_deposits,id', // Assuming 'add_bank_deposits' is a table
    //             'tid_no' => 'required|integer',
    //             'narration' => 'required|string|max:255',
    //         ]);

    //         DB::beginTransaction();

    //         // Create a new card
    //         $card = AddCard::create([
    //             'petrol_card_machine_no' => $validated['petrol_card_machine_no'],
    //             'petrol_card_no' => $validated['petrol_card_no'],
    //             'batch_no' => $validated['batch_no'],
    //             'added_by' => auth()->id(),
    //         ]);

    //         // Create a bank deposit add card entry
    //         $bankDepositAddCard = BankDepositAddCard::create([
    //             'add_bank_deposit_id' => $validated['add_bank_deposit_id'],
    //             'add_card_id' => $card->id,
    //             'tid_no' => $validated['tid_no'],
    //             'narration' => $validated['narration'],
    //         ]);

    //         DB::commit();

    //         return response()->json([
    //             'card' => $card,
    //             'bank_deposit_add_card' => $bankDepositAddCard,
    //         ], 201);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'message' => 'Validation Error',
    //             'errors' => $e->errors(),
    //         ], 422);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => 'Failed to save data',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }



    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'petrol_card_machine_no' => 'required|unique:add_cards',
    //         'petrol_card_no' => 'required|unique:add_cards',
    //         'batch_no' => 'nullable|string',
    //     ]);

    //     $card = AddCard::create(array_merge(
    //         $validated,
    //         ['added_by' => auth()->id()]
    //     ));

    //     return response()->json($card, 201);
    // }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'petrol_card_machine_no' => 'required|string|max:255',
                'petrol_card_no' => 'required|string|max:255,' . $id,
                'batch_no' => 'required|string',
                'add_bank_deposit_id' => 'required|exists:add_bank_deposits,id',
                'tid_no' => 'required|array',
                'tid_no.*' => 'required|integer',
                'narration' => 'required|string|max:255',
            ]);

            DB::beginTransaction();

            // Find the card
            $card = AddCard::findOrFail($id);
            $card->update([
                'petrol_card_machine_no' => $validated['petrol_card_machine_no'],
                'petrol_card_no' => $validated['petrol_card_no'],
                'batch_no' => $validated['batch_no'],
            ]);

            // Update each TID number
            // First, delete existing bank deposit add cards
            BankDepositAddCard::where('add_card_id', $id)->delete();

            // Add new bank deposit add cards
            $bankDepositAddCards = [];
            foreach ($validated['tid_no'] as $tid_no) {
                $bankDepositAddCards[] = BankDepositAddCard::create([
                    'add_bank_deposit_id' => $validated['add_bank_deposit_id'],
                    'add_card_id' => $card->id,
                    'tid_no' => $tid_no,
                    'narration' => $validated['narration'],
                ]);
            }

            DB::commit();

            return response()->json([
                'card' => $card,
                'bank_deposit_add_cards' => $bankDepositAddCards,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to save data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // public function update(Request $request, $id)
    // {
    //     $card = AddCard::findOrFail($id);

    //     $validated = $request->validate([
    //         'petrol_card_machine_no' => 'required|unique:add_cards,petrol_card_machine_no,' . $id,
    //         'petrol_card_no' => 'required|string|max:255,' . $id,
    //         'batch_no' => 'nullable|string',
    //     ]);

    //     $card->update(array_merge(
    //         $validated,
    //         ['updated_by' => auth()->id()]
    //     ));

    //     return response()->json($card);
    // }

    public function destroy($id)
    {
        $card = AddCard::findOrFail($id);
        $card->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $card = AddCard::findOrFail($id);
        $card->open_closed = !$card->open_closed; // Toggle the status
        $card->save(); // Save the status change

        return response()->json(['message' => 'Status updated successfully', 'card' => $card]);
    }
}
