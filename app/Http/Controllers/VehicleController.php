<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VehicleController extends Controller
{
//   // In CreditClientController.php
//   public function getDayStartRate(Request $request)
//   {
//       try {
//           // Validate the incoming request data
//           $validated = $request->validate([
//               'product' => 'required|string|in:ms,speed,hsd', // Ensure the product is one of the allowed types
//           ]);

//           // Get the latest 'day_start' record
//           $dayStart = DB::table('day_start')
//               ->whereDate('date', today()) // Filter by today's date
//               ->first();

//           // If no record found for today, fetch the most recent one
//           if (!$dayStart) {
//               $dayStart = DB::table('day_start')
//                   ->orderByDesc('date')
//                   ->first();
//           }

//           if (!$dayStart) {
//               return response()->json(['message' => 'No day start data found.'], 404);
//           }

//           // Get the rate based on the selected product
//           $rateColumn = $validated['product'] . '_rate_day'; // ms_rate_day, speed_rate_day, or hsd_rate_day
//           $rate = $dayStart->$rateColumn;

//           return response()->json([
//               'rate' => $rate,
//           ]);
//       } catch (\Exception $e) {
//           return response()->json([
//               'message' => 'Error fetching day start rate',
//               'error' => $e->getMessage(),
//           ], 500);
//       }
//   }

//   public function getRateByTank(Request $request)
//   {
//       $tankId = $request->input('tank_id');

//       if (!$tankId) {
//           return response()->json(['error' => 'Tank ID is required'], 400);
//       }

//       // Fetch the tank details
//       $tank = Tank::find($tankId);

//       if (!$tank) {
//           return response()->json(['error' => 'Tank not found'], 404);
//       }

//       // Get today's date
//       $today = Carbon::now()->toDateString(); // Ensure timezone consistency

//       // Fetch the rate for today's date
//       $dayStart = DayStart::whereDate('date', $today)->first();

//       if (!$dayStart) {
//           return response()->json(['error' => 'Rate for today not found'], 404);
//       }

//       // Determine the rate field based on the tank's product
//       $rateField = match (strtolower($tank->product)) {
//           'ms' => 'ms_rate_day',
//           'ms(speed)' => 'speed_rate_day',
//           'hsd' => 'hsd_rate_day',
//           default => null,
//       };

//       if (!$rateField || !isset($dayStart->$rateField)) {
//           return response()->json(['error' => 'Rate not found for the selected product'], 404);
//       }

//       return response()->json(['rate' => $dayStart->$rateField]);
//   }

//   // public function getRateByTank(Request $request)
//   // {
//   //     $tankId = $request->input('tank_id');

//   //     if (!$tankId) {
//   //         return response()->json(['error' => 'Tank ID is required'], 400);
//   //     }

//   //     // Fetch the tank details
//   //     $tank = Tank::find($tankId);

//   //     if (!$tank) {
//   //         return response()->json(['error' => 'Tank not found'], 404);
//   //     }

//   //     // Get today's date
//   //     $today = Carbon::today()->toDateString();

//   //     // Fetch the rate for today's date
//   //     $dayStart = DayStart::whereDate('date', $today)->first();

//   //     if (!$dayStart) {
//   //         return response()->json(['error' => 'Rate for today not found'], 404);
//   //     }

//   //     // Determine the rate field based on the tank's product
//   //     $rateField = match (strtolower($tank->product)) {
//   //         'MS' => 'ms_rate_day',
//   //         'MS(speed)' => 'speed_rate_day',
//   //         'HSD' => 'hsd_rate_day',
//   //         default => null,
//   //     };

//   //     if (!$rateField || !isset($dayStart->$rateField)) {
//   //         return response()->json(['error' => 'Rate not found for the selected product'], 404);
//   //     }

//   //     return response()->json(['rate' => $dayStart->$rateField]);
//   // }
//   // public function getRateByTank(Request $request)
//   // {
//   //     $tankId = $request->input('tank_id');

//   //     if (!$tankId) {
//   //         return response()->json(['error' => 'Tank ID is required'], 400);
//   //     }

//   //     $tank = Tank::find($tankId);

//   //     if (!$tank) {
//   //         return response()->json(['error' => 'Tank not found'], 404);
//   //     }

//   //     $today = Carbon::today()->toDateString();
//   //     $dayStart = DayStart::where('date', $today)->first();

//   //     if (!$dayStart) {
//   //         return response()->json(['error' => 'Rate for today not found'], 404);
//   //     }

//   //     // Determine the rate based on the tank's product
//   //     $rateField = match (strtolower($tank->product)) {
//   //         'ms' => 'ms_rate_day',
//   //         'speed' => 'speed_rate_day',
//   //         'hsd' => 'hsd_rate_day',
//   //         default => null,
//   //     };

//   //     if (!$rateField || !isset($dayStart->$rateField)) {
//   //         return response()->json(['error' => 'Rate not found for the selected product'], 404);
//   //     }

//   //     return response()->json(['rate' => $dayStart->$rateField]);
//   // }
//   // Method to get client credits and tanks
//   public function getDetails()
//   {
//       try {
//           // Fetch client credits and tanks
//           $clientCredits = ClientCredit::select('id', 'client_name', 'has_vehicle')->get();
//           $tanks = Tank::select('id', 'product')->get();

//           return response()->json([
//               'clientCredits' => $clientCredits,
//               'tanks' => $tanks,
//           ]);
//       } catch (\Exception $e) {
//           return response()->json([
//               'message' => 'Error fetching data',
//               'error' => $e->getMessage(),
//           ], 500);
//       }
//   }

//   public function getdetailsdata()
//   {
//       $creditClients = CreditClient::with(['clientCredit', 'tank'])->get(); // Eager load relationships
//       return response()->json($creditClients);
//   }
//   public function create()
//   {
//       $clientCredits = ClientCredit::all(['id', 'client_name']);
//       $tanks = Tank::all(['id', 'tank_number']);
//       return response()->json(compact('clientCredits', 'tanks'));
//   }

//   // In CreditClientController.php
//   public function store(Request $request)
//   {
//       $validated = $request->validate([
//           'add_client_credit_id' => 'required|exists:client_credits,id',
//           'tank_id' => 'required|exists:tanks,id',
//           'bill_no' => 'required|string|max:255',
//           'amount' => 'required|numeric',
//           'quantity_in_liter' => 'required|numeric',
//           'amt_wrds' => 'required|string',
//           'vehicle_no' => 'nullable|string',
//       ]);

//       // Get the tank product (ms, speed, hsd)
//       $tank = Tank::find($validated['tank_id']);
//       $product = strtolower($tank->product); // Assuming `product` is MS, Speed, HSD

//       // Fetch the rate for the selected product
//       $rateResponse = $this->getDayStartRate(new Request(['product' => $product]));
//       $rate = $rateResponse->getData()->rate;

//       $validated['rate'] = $rate; // Automatically set the rate

//       $validated['added_by'] = auth()->id();
//       CreditClient::create($validated);

//       return response()->json(['message' => 'Credit Client created successfully!']);
//   }

//   public function edit(CreditClient $creditClient)
//   {
//       $clientCredits = ClientCredit::all(['id', 'client_name']);
//       $tanks = Tank::all(['id', 'tank_number']);
//       return response()->json(compact('creditClient', 'clientCredits', 'tanks'));
//   }
//   public function update(Request $request, CreditClient $creditClient)
//   {
//       $validated = $request->validate([
//           'add_client_credit_id' => 'required|exists:client_credits,id',
//           'tank_id' => 'required|exists:tanks,id',
//           'bill_no' => 'required|string|max:255',
//           'amount' => 'required|numeric',
//           'quantity_in_liter' => 'required|numeric',
//           'amt_wrds' => 'required|string',
//           'vehicle_no' => 'nullable|string',
//       ]);

//       // Get the tank product (ms, speed, hsd)
//       $tank = Tank::find($validated['tank_id']);
//       if (!$tank) {
//           return response()->json(['error' => 'Tank not found'], 404);
//       }

//       $product = strtolower($tank->product); // Assuming `product` is MS, Speed, HSD

//       // Fetch the rate for the selected product
//       $rateResponse = $this->getDayStartRate(new Request(['product' => $product]));
//       $rate = $rateResponse->getData()->rate;

//       // Add the rate to the validated data
//       $validated['rate'] = $rate;

//       // Update the credit client with the new data
//       $validated['updated_by'] = auth()->id();
//       $creditClient->update($validated);

//       return response()->json(['message' => 'Credit Client updated successfully!']);
//   }

//   // public function update(Request $request, CreditClient $creditClient)
//   // {
//   //     $validated = $request->validate([
//   //         'add_client_credit_id' => 'required|exists:client_credits,id',
//   //         'tank_id' => 'required|exists:tanks,id',
//   //         'bill_no' => 'required|string|max:255',
//   //         'amount' => 'required|numeric',
//   //         'quantity_in_liter' => 'required|numeric',
//   //         'amt_wrds' => 'required|string',
//   //         'vehicle_no' => 'nullable|string',
//   //     ]);


//   //     $validated['updated_by'] = auth()->id();
//   //     $creditClient->update($validated);
//   //     return response()->json(['message' => 'Credit Client updated successfully!']);
//   // }

//   public function destroy(CreditClient $creditClient)
//   {
//       $creditClient->delete();
//       return response()->json(['message' => 'Credit Client deleted successfully!']);
//   }


//   // Fetch stored client credit transactions
//   // Controller method to fetch transactions along with clientCredit and tank relationships
//   public function getClientTransactions()
//   {
//       // Eager load clientCredit and tank relationships
//       $transactions = CreditClient::with('clientCredit', 'tank')->get();

//       // Return transactions as JSON
//       return response()->json($transactions);
//   }

//   public function getVehiclesByClient($clientId)
//   {
//       try {
//           $vehicles = Vehicle::where('add_client_credit_id', $clientId)->pluck('vehicle_no', 'id');

//           return response()->json([
//               'vehicles' => $vehicles
//           ]);
//       } catch (\Exception $e) {
//           return response()->json([
//               'message' => 'Error fetching vehicles',
//               'error' => $e->getMessage(),
//           ], 500);
//       }
//   }
}
