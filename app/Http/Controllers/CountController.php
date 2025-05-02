<?php

namespace App\Http\Controllers;

use App\Models\AddBankDeposit;
use App\Models\AddWallet;
use App\Models\Advance;
use App\Models\BankDeposit;
use App\Models\ChequeEntry;
use App\Models\CreditClient;
use App\Models\DayEnd;
use App\Models\DayStart;
use App\Models\Decantination;
use App\Models\Expense;
use App\Models\FuelSalesDetail;
use App\Models\Godown;
use App\Models\HandLoan;
use App\Models\OilInvoice;
use App\Models\PetrolCard;
use App\Models\PetrolInvoiceFeeding;
use App\Models\RetailSalesRecord;
use App\Models\Variation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CountController extends Controller
{
    public function checkToday(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());
        $exists = DayStart::whereDate('date', $date)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function checkTodayLoans(Request $request)
    {
        try {
            $date = $request->input('date', Carbon::now()->toDateString());

            $handLoanExists = HandLoan::whereDate('date', $date)->exists();
            $advanceExists = Advance::whereDate('date', $date)->exists();

            return response()->json([
                'handLoanExists' => $handLoanExists,
                'advanceExists' => $advanceExists
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while checking loans: ' . $e->getMessage()], 500);
        }
    }

    public function checkfule(Request $request)
    {
        try {
            $date = $request->input('date', Carbon::now()->toDateString());

            $invoicefeedingExists = PetrolInvoiceFeeding::whereDate('date', $date)->exists();
            $decantinationExists = Decantination::whereDate('date', $date)->exists();
            $fuelsalesdetailExists = FuelSalesDetail::whereDate('date', $date)->exists();
            $variationExists = Variation::whereDate('date', $date)->exists();

            return response()->json([
                'invoicefeedingExists' => $invoicefeedingExists,
                'decantinationExists' => $decantinationExists,
                'fuelsalesdetailExists' => $fuelsalesdetailExists,
                'variationExists' => $variationExists
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while checking fuel entries: ' . $e->getMessage()], 500);
        }
    }

    public function checkoil(Request $request)
    {
        try {
            $date = $request->input('date', Carbon::now()->toDateString());

            $purchaseExists = OilInvoice::whereDate('date', $date)->exists();
            $godownExists = Godown::whereDate('date', $date)->exists();
            $retailSaleExists = RetailSalesRecord::whereDate('date', $date)->exists();
            return response()->json([
                'purchaseExists' => $purchaseExists,
                'godownExists' => $godownExists,
                'retailSaleExists' => $retailSaleExists,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while checking oil entries: ' . $e->getMessage()], 500);
        }
    }

    public function checkcards(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());

        return response()->json([
            'petrolcardExists' => PetrolCard::whereDate('date', $date)->exists(),
            'walletExists' => AddWallet::whereDate('date', $date)->exists(),
        ]);
    }

    public function checkBankDeposits(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());

        return response()->json([
            'bankDepositExists' => BankDeposit::whereDate('date', $date)->exists(),
        ]);
    }

    public function checkCredit(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());

        $creditClientExists = CreditClient::whereDate('date', $date)->exists();
        $chequeEntryExists = ChequeEntry::whereDate('date', $date)->exists();

        return response()->json([
            'creditClientExists' => $creditClientExists,
            'chequeEntryExists' => $chequeEntryExists,
        ]);
    }
    // publci funcition checkexp
    public function checkexp(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());

        $expensesExist = Expense::whereDate('date', $date)->exists();

        return response()->json([
            'expensesExist' => $expensesExist,
        ]);
    }

    public function checkdayend(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());

        $dayendExist = DayEnd::whereDate('date', $date)->exists();

        return response()->json([
            'dayendExist' => $dayendExist,
        ]);
    }
}
