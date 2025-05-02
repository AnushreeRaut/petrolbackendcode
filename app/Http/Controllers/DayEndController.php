<?php

namespace App\Http\Controllers;

use App\Models\AddBankDeposit;
use App\Models\CreditClient;
use App\Models\DayEnd;
use App\Models\DayStart;
use App\Models\TankFuleSale;
use App\Models\Advance;
use App\Models\BankDeposit;
use App\Models\PetrolCard;
use App\Models\HandLoan;
use App\Models\Expense;
use App\Models\Tank;
use App\Models\WalletPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DayEndController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'total_incoming' => 'required|numeric',
                'total_outgoing' => 'required|numeric',
                'total_balance_cash' => 'required|numeric',
                'to_bank_on_date_cash' => 'required|numeric',
                'add_bank_deposit_id' => 'required|exists:add_bank_deposits,id',
                'short' => 'required|numeric',
                't_short' => 'required|numeric',
                'date' => 'required|date', // ✅ validate the incoming date
            ]);

            $date = $validated['date']; // ✅ use the date from request

            $existingRecord = DayEnd::where('date', $date)->first();

            if ($existingRecord) {
                $existingRecord->update([
                    'total_incoming' => $validated['total_incoming'],
                    'total_outgoing' => $validated['total_outgoing'],
                    'total_balance_cash' => $validated['total_balance_cash'],
                    'to_bank_on_date_cash' => $validated['to_bank_on_date_cash'],
                    'add_bank_deposit_id' => $validated['add_bank_deposit_id'],
                    'short' => $validated['short'] ?? null,
                    't_short' => $validated['t_short'] ?? null,
                ]);

                return response()->json(['message' => 'Data updated successfully', 'data' => $existingRecord], 200);
            } else {
                $dayEnd = DayEnd::create([
                    'total_incoming' => $validated['total_incoming'],
                    'total_outgoing' => $validated['total_outgoing'],
                    'total_balance_cash' => $validated['total_balance_cash'],
                    'to_bank_on_date_cash' => $validated['to_bank_on_date_cash'],
                    'add_bank_deposit_id' => $validated['add_bank_deposit_id'],
                    'short' => $validated['short'] ?? null,
                    't_short' => $validated['t_short'] ?? null,
                    'date' => $date, // ✅ save the correct date
                ]);

                return response()->json(['message' => 'Data stored successfully', 'data' => $dayEnd], 200);
            }
        } catch (\Exception $e) {
            Log::error('Error storing day end data: ' . $e->getMessage());
            return response()->json(['message' => 'Error storing data', 'error' => $e->getMessage()], 500);
        }
    }

    // public function store(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'total_incoming' => 'required|numeric',
    //             'total_outgoing' => 'required|numeric',
    //             'total_balance_cash' => 'required|numeric',
    //             'to_bank_on_date_cash' => 'required|numeric',
    //             'add_bank_deposit_id' => 'required|exists:add_bank_deposits,id',
    //             'short' => 'required|numeric',
    //             't_short' => 'required|numeric',
    //         ]);

    //         $date = now()->toDateString();

    //         $existingRecord = DayEnd::where('date', $date)->first();

    //         if ($existingRecord) {
    //             $existingRecord->update([
    //                 'total_incoming' => $validated['total_incoming'],
    //                 'total_outgoing' => $validated['total_outgoing'],
    //                 'total_balance_cash' => $validated['total_balance_cash'],
    //                 'to_bank_on_date_cash' => $validated['to_bank_on_date_cash'],
    //                 'add_bank_deposit_id' => $validated['add_bank_deposit_id'],
    //                 'short' => $validated['short'] ?? null,
    //                 't_short' => $validated['t_short'] ?? null,
    //             ]);

    //             return response()->json(['message' => 'Data updated successfully', 'data' => $existingRecord], 200);
    //         } else {
    //             $dayEnd = DayEnd::create([
    //                 'total_incoming' => $validated['total_incoming'],
    //                 'total_outgoing' => $validated['total_outgoing'],
    //                 'total_balance_cash' => $validated['total_balance_cash'],
    //                 'to_bank_on_date_cash' => $validated['to_bank_on_date_cash'],
    //                 'add_bank_deposit_id' => $validated['add_bank_deposit_id'],
    //                 'short' => $validated['short'] ?? null,
    //                 't_short' => $validated['t_short'] ?? null,
    //                 'date' => $date,
    //             ]);

    //             return response()->json(['message' => 'Data stored successfully', 'data' => $dayEnd], 200);
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Error storing day end data: ' . $e->getMessage());
    //         return response()->json(['message' => 'Error storing data', 'error' => $e->getMessage()], 500);
    //     }
    // }

    public function fetchProfitData(Request $request)
    {
        $date = $request->query('date');
        $today = Carbon::parse($date)->toDateString();

        $dayStartRates = DayStart::whereDate('date', $today)->first();

        $msRate = $dayStartRates->ms_rate_day ?? 0;
        $speedRate = $dayStartRates->speed_rate_day ?? 0;
        $hsdRate = $dayStartRates->hsd_rate_day ?? 0;

        $tankFuelSales = TankFuleSale::whereDate('date', $today)
            ->with('tank')
            ->get()
            ->filter(fn($sale) => $sale->tank !== null)
            ->groupBy(fn($sale) => $sale->tank->product ?? 'Unknown')
            ->map(function ($group, $product) use ($msRate, $speedRate, $hsdRate) {
                $rate = match ($product) {
                    'MS' => $msRate,
                    'SPEED' => $speedRate,
                    'HSD' => $hsdRate,
                    default => 0,
                };

                return [
                    'product' => $product,
                    'amount' => $group->sum('total_amount'),
                    'rate' => $rate,
                ];
            })
            ->values();

        $handLoansTotal = HandLoan::whereDate('date', $today)
            ->where('voucher_type', 'Credit-In')
            ->sum('amount');

        $handLoans = [
            [
                'product' => 'HandLoan (Credit-in)',
                'amount' => $handLoansTotal,
            ]
        ];

        $advancesData = Advance::whereDate('date', $today)
            ->where('voucher_type', 'Receipt From')
            ->with('client')
            ->get()
            ->map(fn($advance) => [
                'client' => $advance->client->client_name ?? 'Unknown Client',
                'amount' => $advance->amount,
            ]);

        $advances = [
            [
                'product' => 'Advances (Receipt From)',
                'details' => $advancesData->toArray(),
            ]
        ];

        $allData = collect()
            ->merge($tankFuelSales)
            ->merge($handLoans)
            ->merge($advances);

        return response()->json(['data' => $allData]);
    }

    public function fetchLossData(Request $request)
    {
        $date = $request->query('date');
        $today = Carbon::parse($date)->toDateString();

        $bankDeposits = BankDeposit::whereDate('date', $today)
            ->get(['amount']);
        $totalBankDepositAmount = $bankDeposits->sum('amount');

        $walletPayments = WalletPayment::whereDate('date', $today)
            ->get(['amount']);
        $totalWalletAmount = $walletPayments->sum('amount');

        $petrolCards = PetrolCard::whereDate('date', $today)
            ->get(['amount']);
        $totalPetrolCardAmount = $petrolCards->sum('amount');

        $creditClients = CreditClient::whereDate('date', $today)
            ->get(['amount']);
        $totalCreditClientAmount = $creditClients->sum('amount');

        $expenses = Expense::whereDate('date', $today)
            ->with('topic')
            ->get(['amount', 'expenses_id']);

        $formattedExpenses = $expenses->map(function ($expense) {
            return [
                'name' => $expense->topic->name,
                'amount' => $expense->amount,
            ];
        });

        $handLoansTotal = HandLoan::whereDate('date', $today)
            ->where('voucher_type', 'Debit-In')
            ->sum('amount');

        $handLoans = [
            [
                'product' => 'HandLoan (Credit-in)',
                'amount' => $handLoansTotal,
            ]
        ];

        return response()->json([
            'data' => [
                [
                    'title' => 'Bank Deposits',
                    'total_amount' => $totalBankDepositAmount,
                ],
                [
                    'title' => 'POS Card (Wallet Payments)',
                    'total_amount' => $totalWalletAmount,
                ],
                [
                    'title' => 'Petrol Card',
                    'total_amount' => $totalPetrolCardAmount,
                ],
                [
                    'title' => 'Credit Client',
                    'total_amount' => $totalCreditClientAmount,
                ],
                [
                    'title' => 'Other Expenses',
                    'expenses' => $formattedExpenses,
                ],
            ]
        ]);
    }

    public function getBankNames()
    {
        $banks = AddBankDeposit::select('id', 'bank_name', 'account_number', 'bank_branch')->where('show', true)->get();
        return response()->json(['banks' => $banks]);
    }

    public function getStoredData(Request $request)
    {
        $date = $request->query('date');
        $today = Carbon::parse($date)->toDateString();

        $dayEndData = DayEnd::whereDate('date', $today)
            ->latest()
            ->first();

        if (!$dayEndData) {
            return response()->json([
                'total_incoming' => 0,
                'total_outgoing' => 0,
                'total_balance_cash' => 0,
                'to_bank_on_date_cash' => 0,
                'add_bank_deposit_id' => null,
                'short' => 0,
                't_short' => 0,
            ]);
        }

        return response()->json($dayEndData);
    }
}
