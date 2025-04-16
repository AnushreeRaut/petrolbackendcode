<?php

namespace App\Http\Controllers;

use App\Models\CreditClient;
use App\Models\DayEnd;
use Illuminate\Http\Request;
use App\Models\Variation;
use App\Models\DayStart;
use App\Models\OilInvoice;
use App\Models\PetrolCard;
use App\Models\Tank;
use App\Models\WalletPayment;
use Carbon\Carbon;

class FuelStockController extends Controller
{
    public function getStockDetails()
    {
        // Get today's date
        $today = Carbon::today()->toDateString();

        // Fetch today's fuel rates
        $rates = DayStart::whereDate('date', $today)->first();

        // If no rates found for today, return an error response
        if (!$rates) {
            return response()->json(['error' => 'Rates for today not found'], 404);
        }

        // Define product-wise rate mapping
        $rateMapping = [
            'MS' => $rates->ms_rate_day,
            'SPEED' => $rates->speed_rate_day,
            'HSD' => $rates->hsd_rate_day,
        ];

        // Fetch tank details with variations
        $tanks = Tank::with(['variations' => function ($query) {
            $query->latest();
        }])->get();

        // Prepare response data
        $data = [];

        foreach ($tanks as $tank) {
            $product = strtoupper($tank->product); // Get product name (MS, SPEED, HSD)
            $stock = $tank->variations->first()->actual_bal_stk ?? 0; // Get latest actual_bal_stk
            $dip = 1100; // Default dip (editable in frontend)
            $a_stock = $stock - $dip; // Calculate A.Stock
            $rate = $rateMapping[$product] ?? 0; // Get today's rate
            $amount = $a_stock * $rate; // Calculate Amount

            $data[] = [
                'product' => $product,
                'stock' => $stock,
                'dip' => $dip,
                'a_stock' => $a_stock,
                'rate' => $rate,
                'amount' => $amount
            ];
        }

        return response()->json($data);
    }


    public function getTodayTotalAmounts()
    {
        // Get total sum of invoice_amt for today's invoices
        $todayInvoiceTotal = OilInvoice::whereDate('created_at', Carbon::today())
            ->sum('invoice_amt');

        // Get total sum of amount for today's PetrolCard records
        $todayPetrolCardTotal = PetrolCard::whereDate('created_at', Carbon::today())
            ->sum('amount');

        // Get total sum of amount for today's Credit client records
        $todayCreditClientTotal = CreditClient::whereDate('created_at', Carbon::today())
            ->sum('amount');

        // Get total sum of amount for today's Wallet Payments records
        $todayWalletPaymentTotal = WalletPayment::whereDate('created_at', Carbon::today())
            ->sum('amount');

        // Get today's records for the column `to_bank_on_date_cash`
        $todayDayend = DayEnd::whereDate('created_at', Carbon::today())
            ->pluck('to_bank_on_date_cash');


        return response()->json([
            'total_invoice_amount' => $todayInvoiceTotal,
            'total_petrol_card_amount' => $todayPetrolCardTotal,
            'total_credit_client_amount' => $todayCreditClientTotal,
            'total_wallet_amount' => $todayWalletPaymentTotal,
            'total_cash_bal_amount' => $todayDayend,
        ]);
    }
}
