<?php

namespace App\Http\Controllers;

use App\Models\AddPetrolInvoice;
use Illuminate\Http\Request;

class PetrolInvoiceController extends Controller
{
    // public function fetchInvoiceData($tank_id)
    // {
    //     $invoiceData = AddPetrolInvoice::where('tank_id', $tank_id)->first();

    //     if ($invoiceData) {
    //         return response()->json($invoiceData);
    //     }

    //     return response()->json(['message' => 'No data found for the selected tank.'], 404);
    // }

    public function fetchInvoiceData($tank_id)
{
    // Get the first active invoice for the given tank
    $activeInvoice = AddPetrolInvoice::where('tank_id', $tank_id)
        ->where('is_active', true)
        ->first();

    if ($activeInvoice) {
        return response()->json($activeInvoice);
    }

    return response()->json(['message' => 'No active invoice found for the selected tank.'], 404);
}


    // public function fetchInvoiceData($tank_id)
    // {
    //     // Get all active invoices for the given tank
    //     $activeInvoices = AddPetrolInvoice::where('tank_id', $tank_id)
    //         ->where('is_active', true)
    //         ->get();

    //     if ($activeInvoices->isNotEmpty()) {
    //         return response()->json($activeInvoices);
    //     }

    //     return response()->json(['message' => 'No active invoices found for the selected tank.'], 404);
    // }
}
