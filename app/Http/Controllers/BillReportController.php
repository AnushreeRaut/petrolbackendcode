<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\CreditClient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillReportController extends Controller
{
    public function index()
    {
        $bills = Bill::with(['creditClient.vehicle', 'creditClient.tank','creditClient.clientCredit'])->get();
        return response()->json($bills);
    }

    // public function clientbill()
    // {
    //     $bills = Bill::with(['creditClient.clientCredit'])
    //     ->get();
    //     return response()->json($bills);
    // }

    public function clientbill(){
        $bills = CreditClient::with('clientCredit')
        ->get();
        return response()->json($bills);
    }
}
