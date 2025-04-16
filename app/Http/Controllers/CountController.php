<?php

namespace App\Http\Controllers;

use App\Models\AddBankDeposit;
use App\Models\AddWallet;
use App\Models\Advance;
use App\Models\BankDeposit;
use App\Models\ChequeEntry;
use App\Models\CreditClient;
use App\Models\DayStart;
use App\Models\Decantination;
use App\Models\FuelSalesDetail;
use App\Models\Godown;
use App\Models\HandLoan;
use App\Models\InvoiceFeeding;
use App\Models\OilInvoice;
use App\Models\PetrolCard;
use App\Models\PetrolInvoiceFeeding;
use App\Models\PurchaseRecord;
use App\Models\RetailSalesRecord;
use App\Models\Variation;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CountController extends Controller
{
    public function checkToday()
    {
        $today = Carbon::now()->toDateString();
        $exists = DayStart::whereDate('date', $today)->exists();

        return response()->json(['exists' => $exists]);
    }
    public function checkTodayLoans()
    {
        try {
            $today = Carbon::now()->toDateString(); // Get today's date

            // Check if any HandLoan or Advance exists today
            $handLoanExists = HandLoan::whereDate('created_at', $today)->exists();
            $advanceExists = Advance::whereDate('created_at', $today)->exists();

            // Return the status of both HandLoan and Advance entries
            return response()->json([
                'handLoanExists' => $handLoanExists,
                'advanceExists' => $advanceExists
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while checking loans: ' . $e->getMessage()], 500);
        }
    }


    public function checkfule()
    {
        try {
            $today = Carbon::now()->toDateString(); // Get today's date

            $invoicefeedingExists = PetrolInvoiceFeeding::whereDate('created_at', $today)->exists();
            $decantinationExists = Decantination::whereDate('created_at', $today)->exists();
            $fuelsalesdetailExists = FuelSalesDetail::whereDate('created_at', $today)->exists();
            $variationExists = Variation::whereDate('created_at',$today)->exists();
            return response()->json([
                'invoicefeedingExists' => $invoicefeedingExists,
                'decantinationExists' => $decantinationExists,
                'fuelsalesdetailExists' => $fuelsalesdetailExists,
                'variationExists' => $variationExists
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while checking loans: ' . $e->getMessage()], 500);
        }
    }


    public function checkoil()
    {
        try {
            $today = Carbon::now()->toDateString(); // Get today's date

            $purchaseExists = OilInvoice::whereDate('created_at', $today)->exists();
            $godownExists = Godown::whereDate('created_at', $today)->exists();
            // $retailsaleExists = RetailSalesRecord::whereDate('created_at', $today)->exists();
            // $pouchesExists = Pouch::whereDate('created_at', $today)->exists();

            return response()->json([
                'purchaseExists' => $purchaseExists,
                'godownExists' => $godownExists,
                // 'retailsaleExists' => $retailsaleExists,
                // 'pouchesExists' => $pouchesExists,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while checking oil entries: ' . $e->getMessage()], 500);
        }
    }

    public function checkcards() {
        $today = Carbon::now()->toDateString();

        return response()->json([
            'petrolcardExists' => PetrolCard::whereDate('created_at', $today)->exists(),
            'walletExists' => AddWallet::whereDate('created_at', $today)->exists(),
        ]);
    }
    public function checkBankDeposits() {
        $today = Carbon::now()->toDateString();

        return response()->json([
            'bankDepositExists' => BankDeposit::whereDate('created_at', $today)->exists(),
        ]);
    }


    public function checkCredit() {
        $today = Carbon::now()->toDateString();

        $creditClientExists = CreditClient::whereDate('created_at', $today)->exists();
        // $generateBillExists = GenerateBill::whereDate('crea/ted_at', $today)->exists();
        $chequeEntryExists = ChequeEntry::whereDate('created_at', $today)->exists();

        return response()->json([
            'creditClientExists' => $creditClientExists,
            // 'generateBillExists' => $generateBillExists,
            'chequeEntryExists' => $chequeEntryExists,
        ]);
    }

}
