<?php

namespace App\Http\Controllers;

use App\Models\CreditClient;
use App\Models\DayStart;
use App\Models\FuelSalesDetail;
use App\Models\PetrolCard;
use App\Models\PetrolInvoiceFeeding;
use App\Models\Tank;
use App\Models\Variation;
use App\Models\WalletPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ReportController extends Controller
{

    public function getReportData(Request $request)
    {
        $date = $request->query('date', Carbon::today()); // 👈 Get selected date

        // Fetch latest day_start record for today
        $dayStart = DayStart::whereDate('date', $date)->latest()->first();

        // Fetch fuel sales details for today
        $fuelSales = FuelSalesDetail::with(['tank', 'tankFuelSale'])
            ->whereDate('date', $date)->latest()->get()
            ->map(function ($sale) {
                $sale->tank_fuel_sale = $sale->tank_fuel_sale ?? (object) []; // Ensures it's never null
                return $sale;
            });
        return response()->json([
            'day_start' => $dayStart,
            'fuel_sales' => $fuelSales,

        ]);
    }

    public function fetchVariationData(Request $request)
    {
        $date = $request->query('date', Carbon::today()); // 👈 Get selected date

        $variations = Variation::with(['tank', 'petrolInvoiceFeeding'])
            ->whereDate('date', $date)
            ->get();

        Log::info("Fetched variations: ", $variations->toArray());

        return response()->json([
            'variations' => $variations,
        ]);
    }

    public function getcreditclient(Request $request)
    {
        $date = $request->query('date', Carbon::today()); // 👈 Get selected date

        $creditClients = CreditClient::with(['clientCredit', 'tank','vehicle'])
            ->whereDate('date', $date)
            ->get();

        return response()->json([
            'credit_clients' => $creditClients,
        ]);
    }

    public function getCreditClientSumdata(Request $request)
    {
        // Get the selected date from the query parameter
        $selectedDate = $request->query('date');

        if (!$selectedDate) {
            return response()->json(['error' => 'Missing date'], 400);
        }

        // Sum of all credit amounts before the selected date
        $opCredit = CreditClient::whereDate('date', '<', $selectedDate)->sum('amount');

        // Sum of all credit amounts on the selected date
        $onDateCredit = CreditClient::whereDate('date', $selectedDate)->sum('amount');

        // Total credit (Op. Credit + Today's Credit)
        $totalCredit = $opCredit + $onDateCredit;

        // Fetch credit clients for the selected date
        $creditClients = CreditClient::with(['clientCredit', 'tank', 'vehicle'])
            ->whereDate('date', $selectedDate)  // Use the 'date' column
            ->get();

        return response()->json([
            'credit_clients' => $creditClients,
            'op_credit' => $opCredit,
            'on_date_credit' => $onDateCredit,
            'total_credit' => $totalCredit,
        ]);
    }

    public function getcreditclientsum(Request $request)
    {
        $date = $request->query('date', Carbon::today()); // 👈 Get selected date

        // Sum of all credit amounts before today
        $opCredit = CreditClient::whereDate('date', '<', $date)->sum('amount');

        // Sum of all credit amounts for today
        $onDateCredit = CreditClient::whereDate('date', $date)->sum('amount');

        // Total credit (Op. Credit + Today's Credit)
        $totalCredit = $opCredit + $onDateCredit;

        // Fetch only today's credit clients
        $creditClients = CreditClient::with(['clientCredit', 'tank', 'vehicle'])
            ->whereDate('date', $date) // ✅ Fetch only today's records
            ->get();

        return response()->json([
            'credit_clients' => $creditClients, // ✅ Now only today's data
            'op_credit' => $opCredit,
            'on_date_credit' => $onDateCredit,
            'total_credit' => $totalCredit,
        ]);
    }

    public function todaysInvoiceSummary(Request $request)
    {
        $date = $request->query('date', Carbon::today()); // 👈 Get selected date

        $data = PetrolInvoiceFeeding::select(
                'tank_id',
                DB::raw('SUM(kl_qty) as total_kl_qty')
            )
            ->whereDate('date', $date)
            ->groupBy('tank_id')
            ->with('tank') // Assuming 'tank' relation exists
            ->get();

        return response()->json($data);
    }

    public function getTodaysWalletPayments(Request $request)
{
    $date = $request->query('date', Carbon::today()); // 👈 Get selected date
    $todaysPayments = WalletPayment::with(['wallet'])->whereDate('date', $date)->get();
    return response()->json($todaysPayments);
}

// public function perolcardreport() {
//     $petrolCards = PetrolCard::with('addCard.bankDeposits')->whereDate('created_at', Carbon::today())->get();
//     return response()->json(['petrol_cards' => $petrolCards]);
// }
public function perolcardreport(Request $request) {
    $date = $request->query('date', Carbon::today()); // 👈 Get selected date
    $petrolCards = PetrolCard::with('addCard.bankDeposits')
        ->whereDate('date', $date)
        ->get()
        ->map(function ($card) {
            return [
                'amount' => $card->amount,
                'current_batch_no' => $card->current_batch_no,
                'last_batch_no' => $card->last_batch_no,
                'petrol_card_machine_no' => $card->addCard->petrol_card_machine_no ?? null,
                'petrol_card_no' => $card->addCard->petrol_card_no ?? null,
                'bank_deposits' => $card->addCard->bankDeposits->map(function ($deposit) {
                    return ['tid_no' => $deposit->tid_no];
                }),
            ];
        });

    return response()->json(['petrol_cards' => $petrolCards]);
}


}
