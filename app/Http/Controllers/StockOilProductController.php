<?php

namespace App\Http\Controllers;

use App\Models\OilProduct;
use App\Models\StockInPiece;
use Illuminate\Http\Request;

class StockOilProductController extends Controller
{
    public function index()
{
    $products = OilProduct::with('stockInPieces')->get();
    return response()->json(['data' => $products], 200);
}


}
