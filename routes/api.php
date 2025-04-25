<?php

use App\Http\Controllers\AddAdvanceClientController;
use App\Http\Controllers\AddBankDepositController;
use App\Http\Controllers\AddCardController;
use App\Http\Controllers\AddPetrolInvoiceController;
use App\Http\Controllers\AddWalletController;
use App\Http\Controllers\AdvanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalanceHandloanController;
use App\Http\Controllers\BalStockController;
use App\Http\Controllers\BankDepositAddCardController;
use App\Http\Controllers\BankDepositController;
use App\Http\Controllers\BillBookDetailController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BillReportController;
use App\Http\Controllers\ChequeEntryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientCreditController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CountController;
use App\Http\Controllers\CreditClientController;
use App\Http\Controllers\DayEndController;
use App\Http\Controllers\DayStartController;
use App\Http\Controllers\DecantationController;
use App\Http\Controllers\DifferenceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeIncentiveController;
use App\Http\Controllers\EmployeeProductController;
use App\Http\Controllers\EmployeeRecordController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpensesTopicController;
use App\Http\Controllers\FuelSalesDetailController;
use App\Http\Controllers\FuelStockController;
use App\Http\Controllers\GivenLoanController;
use App\Http\Controllers\GodownController;
use App\Http\Controllers\HandLoanController;
use App\Http\Controllers\IndexInvoiceController;
use App\Http\Controllers\InvoiceFeedingController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\MachineGroupingController;
use App\Http\Controllers\MachineWiseGroupingController;
use App\Http\Controllers\NozzleController;
use App\Http\Controllers\NozzleReadingController;
use App\Http\Controllers\OilInvoiceController;
use App\Http\Controllers\OilProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PetrolCardController;
use App\Http\Controllers\PetrolInvoiceController;
use App\Http\Controllers\PetrolInvoiceFeedingController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\ProductWiseGroupingController;
use App\Http\Controllers\PurchaseRecordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RetailSalesRecordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoughController;
use App\Http\Controllers\SalesRecordController;
use App\Http\Controllers\StockInLiterController;
use App\Http\Controllers\StockInPieceController;
use App\Http\Controllers\StockOilProductController;
use App\Http\Controllers\StockRecordController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\TankController;
use App\Http\Controllers\TankDecantinationController;
use App\Http\Controllers\TotalRecordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VariationController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WalletPaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/tank-products-data', [TankController::class, 'getAllTankProducts']);
Route::get('/tanks/tanksnumber', [TankController::class, 'getTanks']);
Route::put('/tanks/{id}/toggle-status', [TankController::class, 'toggleStatus']);

// Forgot password route
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

// Update password route
Route::post('/update-password', [AuthController::class, 'updatePassword']);

Route::middleware('auth:sanctum')->group(function () {

    // NEWAPIS


    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', UserController::class);
    Route::put('/tanks/{id}/opening-stock', [TankController::class, 'updateOpeningStock']);
    Route::put('/tanks/{id}', [TankController::class, 'update']);
    Route::apiResource('tanks', TankController::class);
    Route::post('machines-and-nozzles', [MachineController::class, 'storeMachineAndNozzle']);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('machines', MachineController::class);


    Route::get('/machines/todays/data', [MachineController::class, 'showtodays']);
    // Route::put('/machines/{id}', [MachineController::class, 'update']);


    Route::put('/machines/{id}/isactive', [MachineController::class, 'toggleStatus']);

    Route::get('/machines/details/nozzels', [MachineGroupingController::class, 'getAllMachineDetails']);

    Route::put('/nozzles/openingreading/{id}', [MachineGroupingController::class, 'update']);


    Route::apiResource('machineswisegrouping', MachineWiseGroupingController::class);
    Route::apiResource('productwisegroupings', ProductWiseGroupingController::class);

    Route::get('/nozzles-grouped', [NozzleController::class, 'getNozzlesGroupedByProduct']);

    Route::get('/nozzles-grouped-data-productwise', [NozzleController::class, 'getNozzlesGroupedByProductwise']);
    Route::get('/nozzles-detail-date', [NozzleController::class, 'getNozzles']);
    Route::put('/nozzles-change-value/{id}', [NozzleController::class, 'update']);

    Route::get('/day_start/getDayStartRates', [DayStartController::class, 'getDayStartRates']);
    Route::post('/day_start', [DayStartController::class, 'storeRate']);

    // client-credits
    Route::apiResource('client-credits-data', ClientCreditController::class);
    Route::put('/client-credits-data/{id}/status', [ClientCreditController::class, 'updateStatus']);

    Route::apiResource('clients-details', ClientController::class);
    Route::get('/get-vehicles/{clientId}', [CreditClientController::class, 'getVehiclesByClient']);
    Route::get('credit_clients_get_details', [CreditClientController::class, 'getDetails']); // Fetch client credits and tanks
    Route::get('get_day_start_rate', [CreditClientController::class, 'getDayStartRate']); // Fetch the rate for the selected product
    Route::post('credit_clients', [CreditClientController::class, 'store']);
    Route::put('/credit_clients_update_data/{id}', [RoughController::class, 'update']);
    Route::get('get-rate-by-tank', [CreditClientController::class, 'getRateByTank']);
    Route::get('/credit_clients_view_data', [CreditClientController::class, 'getdetailsdata']);
    Route::get('/credit_client_transactions', [CreditClientController::class, 'getClientTransactions']);

    Route::get('/credit_clients_display', [RoughController::class, 'getCreditClients']);
    Route::get('/credit_clients_all_records', [RoughController::class, 'getAllCreditClients']);

    // Route::apiResource('credit_clients', CreditClientController::class);
    Route::apiResource('handloans', HandLoanController::class);
    Route::apiResource('advances', AdvanceController::class);
    Route::apiResource('givenloan', GivenLoanController::class);
    Route::apiResource('invoice-feedings', InvoiceFeedingController::class);
    Route::apiResource('commissions', CommissionController::class);
    Route::apiResource('product-details', ProductDetailController::class);
    Route::apiResource('purchase-records', PurchaseRecordController::class);
    Route::apiResource('tank-decantations', TankDecantinationController::class);
    // Route::apiResource('/fuel-sales/store', FuelSalesDetailController::class);
    Route::post('/fuel-sales/store/all/data', [FuelSalesDetailController::class, 'store']);
    Route::post('/fuel-sales/update', [FuelSalesDetailController::class, 'update']);
    Route::get('/fuel-sales/rate', [FuelSalesDetailController::class, 'getRates']);
    Route::apiResource('variations', VariationController::class);
    Route::apiResource('index-invoices', IndexInvoiceController::class);
    Route::apiResource('oil-products', OilProductController::class);
    Route::put('/oil-products/{id}/update-status', [OilProductController::class, 'updateStatus']);
    Route::apiResource('stock-in-pieces', StockInPieceController::class);
    Route::apiResource('stock-in-liters', StockInLiterController::class);
    Route::apiResource('summaries', SummaryController::class);
    Route::apiResource('differences', DifferenceController::class);
    // Route::apiResource('godowns', GodownController::class);
    // godown-total-bal-stk
    Route::get('/godown-total-bal-stk', [GodownController::class, 'getTotalBalStk']);
    Route::get('/godown-stock', [GodownController::class, 'getTodaysStock']);
    Route::post('/godown-stock-store', [GodownController::class, 'store']);
    Route::get('/godown-stock-by-date', [GodownController::class, 'getStockByDate']);

    // Route::apiResource('retail-sales-records', RetailSalesRecordController::class);
    Route::apiResource('stock-records', StockRecordController::class);
    Route::apiResource('sales-records', SalesRecordController::class);
    Route::apiResource('bal-stocks', BalStockController::class);
    Route::apiResource('total-records', TotalRecordController::class);

    Route::apiResource('/cheque-entries', ChequeEntryController::class);
    Route::get('/clients', [ChequeEntryController::class, 'getClients']); // For the client dropdown
    Route::get('/clients-data-list', [ChequeEntryController::class, 'getClientsdata']); // For the client dropdown
    Route::patch('/add-cards/{id}/toggle-status', [AddCardController::class, 'toggleStatus']);
    Route::apiResource('add-cards', AddCardController::class);

    Route::get('/get-add-card-data', [AddCardController::class, 'getAddCards']);
    // add-cards-batch
    // routes/api.php
    // Route::get('/petrol-cards/latest/{cardId}', [PetrolCardController::class, 'getLatestBatchNo']);
    Route::get('/get-batch-numbers', [PetrolCardController::class, 'getBatchNumbers']);


    Route::get('/add-cards-batch', [AddCardController::class, 'batchshow']); // For the client dropdown

    Route::get('/petrol-cards-get', [PetrolCardController::class, 'getPetrolCards']);
    Route::get('/petrol-cards-show', [PetrolCardController::class, 'index']);
    Route::apiResource('petrol-cards', PetrolCardController::class);
    Route::apiResource('add-wallet', AddWalletController::class);
    Route::put('/wallet/{id}/status', [AddWalletController::class, 'updateStatus']);
    Route::get('/wallets', [WalletPaymentController::class, 'fetchWallets']);
    Route::apiResource('wallet-payments', WalletPaymentController::class);
    Route::apiResource('policies', PolicyController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('loans', LoanController::class);
    Route::apiResource('expenses-topics', ExpensesTopicController::class);
    Route::put('/expenses-topics/{id}/status', [ExpensesTopicController::class, 'updateStatus']);
    Route::apiResource('expenses', ExpenseController::class);
    Route::apiResource('add-bank-deposit', AddBankDepositController::class);
    Route::put('/add-bank-deposit/{id}/status-show', [AddBankDepositController::class, 'updateStatusAndShow']);

    // Route::apiResource('bank-deposits', BankDepositController::class);
    // Route::get('/bank-names', [BankDepositController::class, 'getBanks']);
    Route::get('/get-account-number/{bankId}', [BankDepositController::class, 'getAccountNumber']);

    Route::get('/fetch-banks', [BankDepositController::class, 'getBanks']);
    Route::get('/bank-deposits', [BankDepositController::class, 'index']);
    Route::post('/bank-deposits', [BankDepositController::class, 'store']);
    Route::put('/bank-deposits/{id}', [BankDepositController::class, 'update']);
    Route::delete('/bank-deposits/{id}', [BankDepositController::class, 'destroy']);
    Route::apiResource('add-advance-clients', AddAdvanceClientController::class);
    // Route::apiResource('advances-data', AdvanceController::class);
    Route::get('/add-advance-clients', [AdvanceController::class, 'getclient']);
    Route::get('/advances-data', [AdvanceController::class, 'index']);
    Route::post('/advances-data', [AdvanceController::class, 'store']);
    Route::put('/advances-data/{advance}', [AdvanceController::class, 'update']);
    Route::delete('/advances-data/{advance}', [AdvanceController::class, 'destroy']);

    Route::get('clients', [ClientController::class, 'index']);

    Route::apiResource('add-petrol-invoice', AddPetrolInvoiceController::class);
    Route::put('/add-petrol-invoice/{id}/toggle-status', [AddPetrolInvoiceController::class, 'toggleStatus']);

    Route::get('/tank-products', [AddPetrolInvoiceController::class, 'getTankProducts']);

    // Route::apiResource('petrol-invoice-feeding', PetrolInvoiceFeedingController::class);
    Route::get('/tank-invoices-data-product', [PetrolInvoiceFeedingController::class, 'getTankProducts']);
    Route::get('/fetch-invoice-data-details/{tank_id}', [PetrolInvoiceController::class, 'fetchInvoiceData']);
    // Route::get('/fetch-invoice-data', [PetrolInvoiceFeedingController::class, 'fetchAllInvoiceData']);
    Route::get('/get-grouped-invoices', [PetrolInvoiceFeedingController::class, 'getGroupedInvoices']);
    Route::get('/get-grouped-invoices-addinvoice', [PetrolInvoiceFeedingController::class, 'getGroupedInvoicesdata']);
    Route::post('petrol-invoice-feeding-store', [PetrolInvoiceFeedingController::class, 'store'])->name('petrol-invoice-feeding-store');
    Route::put('petrol-invoice-feeding-update/{id}', [PetrolInvoiceFeedingController::class, 'update'])->name('petrol-invoice-feeding-update');
    // /petrol-invoice-feeding-destroy/
    Route::delete('petrol-invoice-feeding-destroy/{id}', [PetrolInvoiceFeedingController::class, 'destroy'])->name('petrol-invoice-feeding-destroy');


    Route::get('/decant/invoices/data', [DecantationController::class, 'getInvoiceData']);
    Route::get('/decant/invoice/details/{invoice_no}', [DecantationController::class, 'getInvoiceDetails']);
    Route::get('/tanks/details/invoice/fetch', [DecantationController::class, 'getAllTanks']);
    Route::post('/decant/submit', [DecantationController::class, 'store']);
    Route::get('/decantations/view', [DecantationController::class, 'index']);
    // Route::get('/tank-details/{id}', [InvoiceFeedingController::class, 'getTankDetails']);

    // Route::get('/variations', [VariationController::class, 'getVariationData']);
    // Route::get('/get-variations', [VariationController::class, 'getVariations']);

    Route::post('/variation-data', [VariationController::class, 'fetchVariationData']);
    // Route::post('/variations/get-by-date', [VariationController::class, 'getVariationsByDate']);
    Route::post('/variations/get-by-date', [VariationController::class, 'getByDate']);

    Route::apiResource('bank_deposit_cards', BankDepositAddCardController::class);

    Route::get('fetch-bank-deposits', [BankDepositAddCardController::class, 'fetchBankDeposits']);
    Route::get('fetch-add-cards', [BankDepositAddCardController::class, 'fetchAddCards']);
    // /fuel-sales/today
    Route::put('/bank_deposit_cards/{id}/toggle-status', [BankDepositAddCardController::class, 'toggleStatus']);

    // Route::patch('/bank_deposit_cards/{id}/toggle-status', [BankDepositAddCardController::class, 'toggleStatus']);

    Route::get('/day-start/today', [CountController::class, 'checkToday']);

    // Route::get('checkoil', [CountController::class, 'checkoil']);
    Route::get('/check-today-loans', [CountController::class, 'checkTodayLoans']);
    Route::get('/checkfule', [CountController::class, 'checkfule']);
    Route::get('checkoil', [CountController::class, 'checkoil']);
    Route::get('/checkcards', [CountController::class, 'checkcards']);
    Route::get('/checkbankdeposits', [CountController::class, 'checkBankDeposits']);
    Route::get('/checkcredit', [CountController::class, 'checkCredit']);

    Route::get('/vehicles', [VehicleController::class, 'index']);

    // Route::apiResource('oil-stock-products', StockOilProductController::class);
    Route::apiResource('oil-invoices', OilInvoiceController::class);
    // Route::get('/oil-invoices/today', [OilInvoiceController::class, 'getTodaysInvoices']);
    Route::get('/oil-invoices/today/{productId}', [OilInvoiceController::class, 'getTodaysInvoices']);
    Route::get('/oil-invoices-by-date', [OilInvoiceController::class, 'getInvoicesByDate']);

    Route::get('/credit_clients-data-bills', [BillController::class, 'getClients']);
    Route::get('/bills', [BillController::class, 'index']);
    Route::post('/bills', [BillController::class, 'store']);
    Route::delete('/bills/{id}', [BillController::class, 'destroy']);

    // api report
    Route::get('/bills/report', [BillReportController::class, 'index']);
    Route::get('/bills/report/client', [BillReportController::class, 'clientbill']);
    Route::get('getOilProductsWithInvoices', [RetailSalesRecordController::class, 'getOilProductsWithInvoices']);

    // Route::get('/getOilProductsWithInvoices', [RetailSalesRecordController::class, 'getOilProductsWithInvoices']);
    Route::post('/retail-sales-records', [RetailSalesRecordController::class, 'store']);

    Route::get('/fuel-sales/get', [FuelSalesDetailController::class, 'getFuelSalesData']);


    Route::post('/day-ends', [DayEndController::class, 'store']);
    Route::get('/fetchAllData', [DayEndController::class, 'fetchprofitData']);
    Route::get('/fetchLossData', [DayEndController::class, 'fetchLossData']);
    Route::get('/banks-names', [DayEndController::class, 'getBankNames']);
    Route::post('/day-end-submit', [DayEndController::class, 'store']);
    Route::get('/day-end-data', [DayEndController::class, 'getStoredData']);



    Route::apiResource('balance-handloans', BalanceHandloanController::class);
    Route::get('/clients-with-handloans', [BalanceHandloanController::class, 'fetchhandloanclient']);
    Route::get('/client-balance/{clientId}', [BalanceHandloanController::class, 'getClientBalance']);

    Route::get('/report-data', [ReportController::class, 'getReportData']);

    Route::get('/report-data-variation', [ReportController::class, 'fetchVariationData']);


    Route::get('/report-data-creditclient', [ReportController::class, 'getcreditclient']);
    Route::get('/credit_clients_display', [ReportController::class, 'getcreditclientsum']);
    Route::get('/credit_clients_display_data', [ReportController::class, 'getCreditClientSumdata']);
    Route::get('/todays-invoice-summary', [ReportController::class, 'todaysInvoiceSummary']);
    // /todays-wallet-payments
    Route::get('/todays-wallet-payments', [ReportController::class, 'getTodaysWalletPayments']);
    // perolcardreport
    Route::get('/todays-petro-card', [ReportController::class, 'perolcardreport']);
    // Route::get('/fuel-stock', [FuelStockController::class, 'getStockData']);
    Route::get('/stock-details', [FuelStockController::class, 'getStockDetails']);
    Route::get('/getTodayTotalAmounts', [FuelStockController::class, 'getTodayTotalAmounts']);

    Route::apiResource('employees', EmployeeController::class);
    Route::put('/employees/{id}/toggle-active', [EmployeeController::class, 'toggleActive']);


    Route::apiResource('products_staff', ProductController::class);
    Route::put('/products_staff/{id}/toggle-active', [ProductController::class, 'toggleActive']);


    // Route::apiResource('/employee-products', EmployeeProductController::class);

    Route::get('/employee-products', [EmployeeProductController::class, 'index']);
    Route::post('/employee-products', [EmployeeProductController::class, 'store']);
    Route::put('/employee-products/{id}', [EmployeeProductController::class, 'update']);
    Route::delete('/employee-products/{id}', [EmployeeProductController::class, 'destroy']);

    // Newly Added Route
    Route::get('/employee-products/{employeeId}/{month}', [EmployeeProductController::class, 'show']);
    Route::get('/valid-dates', [DayStartController::class, 'getValidDates']);
    Route::get('/last-entry-date', [DayStartController::class, 'getLastEntryDate']);

    Route::get('/employee-products-this-month', [EmployeeRecordController::class, 'getThisMonthEmployeeProducts']);

    Route::apiResource('/employee-incentives', EmployeeIncentiveController::class);
    // routes/api.php

    Route::get('/total-sales', [EmployeeIncentiveController::class, 'getTotalSales']);
    Route::get('/total-sales-amount', [EmployeeIncentiveController::class, 'getTotalSalesAndAmount']);

    Route::post('/bill-book-details', [BillBookDetailController::class, 'store']);
    Route::get('/bill-book-details/display', [BillBookDetailController::class, 'index']);

});
