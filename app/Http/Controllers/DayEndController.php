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
    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'total_incoming' => 'required|numeric',
                'total_outgoing' => 'required|numeric',
                'total_balance_cash' => 'required|numeric',
                'to_bank_on_date_cash' => 'required|numeric',
                'add_bank_deposit_id' => 'required|exists:add_bank_deposits,id',
                'short' => 'required|numeric',
                't_short' => 'required|numeric',
            ]);

            // Check if the record already exists for the given date
            $existingRecord = DayEnd::where('date', now()->toDateString())->first();

            if ($existingRecord) {
                // Update existing record
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
                // If no existing record, create a new one
                $dayEnd = DayEnd::create([
                    'total_incoming' => $validated['total_incoming'],
                    'total_outgoing' => $validated['total_outgoing'],
                    'total_balance_cash' => $validated['total_balance_cash'],
                    'to_bank_on_date_cash' => $validated['to_bank_on_date_cash'],
                    'add_bank_deposit_id' => $validated['add_bank_deposit_id'],
                    'short' => $validated['short'] ?? null,
                    't_short' => $validated['t_short'] ?? null,
                    'date' => now()->toDateString(),
                ]);

                return response()->json(['message' => 'Data stored successfully', 'data' => $dayEnd], 200);
            }
        } catch (\Exception $e) {
            Log::error('Error storing day end data: ' . $e->getMessage());
            return response()->json(['message' => 'Error storing data', 'error' => $e->getMessage()], 500);
        }
    }
    public function fetchProfitData()
    {
        $today = now()->toDateString(); // Get today's date

        // Fetch today's day start rates
        $dayStartRates = DayStart::whereDate('date', $today)->first();

        // Ensure we have a valid object for day rates to avoid errors
        $msRate = $dayStartRates->ms_rate_day ?? 0;
        $speedRate = $dayStartRates->speed_rate_day ?? 0;
        $hsdRate = $dayStartRates->hsd_rate_day ?? 0;

        // Fetch today's tank fuel sales and group by product
        $tankFuelSales = TankFuleSale::whereDate('created_at', $today)
            ->with('tank') // Ensure the relationship is loaded
            ->get()
            ->filter(fn($sale) => $sale->tank !== null) // Exclude records with no related tank
            ->groupBy(fn($sale) => $sale->tank->product ?? 'Unknown')
            ->map(function ($group, $product) use ($msRate, $speedRate, $hsdRate) {
                $rate = match ($product) {
                    'MS' => $msRate,
                    'MS(speed)' => $speedRate,
                    'HSD' => $hsdRate,
                    default => 0,
                };

                return [
                    'product' => $product,
                    'amount' => $group->sum('total_amount'),
                    'rate' => $rate,
                ];
            })
            ->values(); // Reset array indexes

        // Fetch Hand Loans with Credit-In Voucher Type
        $handLoansTotal = HandLoan::whereDate('created_at', $today)
            ->where('voucher_type', 'Credit-In')
            ->sum('amount');

        $handLoans = [
            [
                'product' => 'HandLoan (Credit-in)',
                'amount' => $handLoansTotal,
            ]
        ];

        // Fetch and sum Credit Client Amount
        // $creditClientSum = CreditClient::whereDate('created_at', $today)->sum('amount');
        // $creditClientData = [
        //     [
        //         'product' => 'Credit Client Total',
        //         'amount' => $creditClientSum,
        //     ]
        // ];

        // Fetch all advances with client names
        $advancesData = Advance::whereDate('created_at', $today)
            ->where('voucher_type', 'Receipt From') // Filter by voucher_type
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


        // Combine All Data
        $allData = collect()
            ->merge($tankFuelSales)
            ->merge($handLoans)
            // ->merge($creditClientData)
            ->merge($advances);

        return response()->json(['data' => $allData]);
    }



    // public function fetchprofitData()
    // {
    //     $today = now()->toDateString(); // Get today's date

    //     // Fetch Tank Fuel Sales and group by Product from Tanks table
    //     $tankFuelSales = TankFuleSale::whereDate('created_at', $today)
    //         ->with('tank')
    //         ->get()
    //         ->groupBy(function ($sale) {
    //             return $sale->tank->product ?? 'Unknown';
    //         })
    //         ->map(function ($group, $product) {
    //             return [
    //                 'product' => $product,
    //                 'amount' => $group->sum('total_amount'),
    //             ];
    //         })->values();

    //     // Fetch Hand Loans with Credit-In Voucher Type
    //     $handLoansTotal = HandLoan::whereDate('created_at', $today)
    //         ->where('voucher_type', 'Credit-In')
    //         ->sum('amount'); // Sum up the amount column

    //     $handLoans = [
    //         [
    //             'product' => 'Hand Loan', // Representing the total sum
    //             'amount' => $handLoansTotal,

    //         ]
    //     ];


    //     // Fetch and sum Credit Client Amount
    //     $creditClientSum = CreditClient::whereDate('created_at', $today)->sum('amount');
    //     $creditClientData = [
    //         [

    //             'product' => 'Credit Client Total',
    //             'amount' => $creditClientSum,

    //         ]
    //     ];

    //     // Fetch all advances with client names
    //     $advancesData = Advance::whereDate('created_at', $today)
    //         ->with('client')
    //         ->get()
    //         ->map(function ($advance) {
    //             return [
    //                 'client' => $advance->client->client_name ?? 'Unknown Client',
    //                 'amount' => $advance->amount,
    //             ];
    //         });

    //     $advances = [
    //         [
    //             'product' => 'Advances',
    //             'details' => $advancesData->toArray(), // Store clients with amounts
    //         ]
    //     ];

    //     // Combine All Data
    //     $allData = $tankFuelSales->merge($handLoans)->merge($creditClientData)->merge($advances);

    //     // Combine All Data
    //     // $allData = $tankFuelSales->merge($handLoans)->merge($creditClientData);

    //     return response()->json(['data' => $allData]);
    // }
    public function fetchLossDatatest()
{
    $expenses = Expense::all(); // Fetch all expenses
    $handloan = Handloan::where('type', 'debit-out')->get(); // Fetch handloan debit-out transactions

    $lossData = [
        [
            'title' => 'Other Expenses',
            'total_amount' => $expenses->sum('amount'),
            'expenses' => $expenses->map(function ($expense) {
                return [
                    'name' => $expense->name,
                    'amount' => $expense->amount,
                ];
            }),
        ],
        [
            'title' => 'Handloan Debit-Out',
            'total_amount' => $handloan->sum('amount'),
            'expenses' => $handloan->map(function ($loan) {
                return [
                    'name' => 'Handloan to ' . $loan->receiver,
                    'amount' => $loan->amount,
                ];
            }),
        ],
    ];

    return response()->json(['data' => $lossData]);
}


    public function fetchLossData()
    {
        $today = now()->toDateString(); // Get today's date

        // Fetch all bank deposits made today
        $bankDeposits = BankDeposit::whereDate('created_at', $today)
            ->get(['amount']);
        // Calculate the total sum of the bank deposit amounts
        $totalBankDepositAmount = $bankDeposits->sum('amount');

        // Fetch all wallet payments made today
        $walletPayments = WalletPayment::whereDate('created_at', $today)
            ->get(['amount']);
        // Calculate the total sum of the wallet payment amounts
        $totalWalletAmount = $walletPayments->sum('amount');

        // Fetch all petrol card payments made today
        $petrolCards = PetrolCard::whereDate('created_at', $today)
            ->get(['amount']);
        // Calculate the total sum of the petrol card amounts
        $totalPetrolCardAmount = $petrolCards->sum('amount');

        // Fetch all credit client payments made today
        $creditClients = CreditClient::whereDate('created_at', $today)
            ->get(['amount']);
        // Calculate the total sum of the credit client amounts
        $totalCreditClientAmount = $creditClients->sum('amount');

        // Fetch all expenses made today along with the related topic names
        $expenses = Expense::whereDate('created_at', $today)
            ->with('topic') // Eager load the related topic
            ->get(['amount', 'expenses_id']);

        // Format the expenses data with their topic name and amount
        $formattedExpenses = $expenses->map(function ($expense) {
            return [
                'name' => $expense->topic->name, // Access the topic name
                'amount' => $expense->amount, // Access the expense amount
            ];
        });

         // Fetch Hand Loans with Credit-In Voucher Type
         $handLoansTotal = HandLoan::whereDate('created_at', $today)
         ->where('voucher_type', 'Debit-In')
         ->sum('amount');

     $handLoans = [
         [
             'product' => 'HandLoan (Credit-in)',
             'amount' => $handLoansTotal,
         ]
     ];

        // Return the data with the total sums and expenses
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
                    'expenses' => $formattedExpenses, // Add the formatted expenses data
                ],
            ]
        ]);
    }
    public function getBankNames()
    {
        $banks = AddBankDeposit::select('id', 'bank_name', 'account_number', 'bank_branch')->where('show', true)->get();
        return response()->json(['banks' => $banks]);
    }
    // public function getStoredData()
    // {
    //     // Assuming you have a 'day_end' table to store the day's data
    //     $dayEndData = DayEnd::latest()->first(); // Get the latest day's data
    //     return response()->json($dayEndData);  // Return the data as JSON
    // }

    public function getStoredData()
    {
        // Get today's date
        $today = Carbon::today();

        // Fetch the latest record for today
        $dayEndData = DayEnd::whereDate('created_at', $today)
            ->latest()
            ->first();

        return response()->json($dayEndData);
    }
}
