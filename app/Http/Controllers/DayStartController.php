<?php

namespace App\Http\Controllers;

use App\Models\DayEnd;
use App\Models\DayStart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DayStartController extends Controller
{

    public function getDayStartRates(Request $request)
    {
        // Get selected date from request, default to today if not provided
        $date = $request->input('date', Carbon::today()->toDateString());

        // Fetch rates for the selected date
        $dayStart = DB::table('day_start')
            ->where('date', '=', $date)
            ->first();

        if (!$dayStart) {
            $dayStart = (object) [
                'ms_rate_day' => 0,
                'speed_rate_day' => 0,
                'hsd_rate_day' => 0,
                'ms_diff' => 0,
                'speed_diff' => 0,
                'hsd_diff' => 0
            ];
        }

        // Fetch previous day's rates
        $yesterday = Carbon::parse($date)->subDay()->toDateString();
        $lastDayStart = DB::table('day_start')
            ->where('date', '=', $yesterday)
            ->first();

        return response()->json([
            'today' => $dayStart,
            'yesterday' => $lastDayStart ?? (object)[
                'ms_rate_day' => 0,
                'speed_rate_day' => 0,
                'hsd_rate_day' => 0,
                'ms_diff' => 0,
                'speed_diff' => 0,
                'hsd_diff' => 0
            ]
        ]);
    }

    // public function getDayStartRates(Request $request)
    // {
    //     // Get selected date from request, default to today if not provided
    //     $date = $request->input('date', Carbon::today()->toDateString());

    //     // Fetch rates for the selected date
    //     $dayStart = DB::table('day_start')
    //         ->where('date', '=', $date)
    //         ->first();

    //     if (!$dayStart) {
    //         $dayStart = (object) [
    //             'ms_rate_day' => 0,
    //             'speed_rate_day' => 0,
    //             'hsd_rate_day' => 0,
    //         ];
    //     }

    //     // Fetch previous day's rates
    //     $yesterday = Carbon::parse($date)->subDay()->toDateString();
    //     $lastDayStart = DB::table('day_start')
    //         ->where('date', '=', $yesterday)
    //         ->first();

    //     return response()->json([
    //         'today' => $dayStart,
    //         'yesterday' => $lastDayStart ?? (object)[
    //             'ms_rate_day' => 0,
    //             'speed_rate_day' => 0,
    //             'hsd_rate_day' => 0,
    //         ]
    //     ]);
    // }

    // public function getDayStartRates()
    // {
    //     // Get today's date
    //     $today = Carbon::today()->toDateString();

    //     // Get the current day's rates if available, else return defaults
    //     $dayStart = DB::table('day_start')
    //         ->where('date', '=', $today)
    //         ->first();

    //     // If no record for today, set default values
    //     if (!$dayStart) {
    //         $dayStart = (object) [
    //             'ms_rate_day' => 0,
    //             'speed_rate_day' => 0,
    //             'hsd_rate_day' => 0,
    //             'ms_last_day' => 0,
    //             'speed_last_day' => 0,
    //             'hsd_last_day' => 0,
    //             'ms_diff' => 0,
    //             'speed_diff' => 0,
    //             'hsd_diff' => 0,
    //         ];
    //     }

    //     // Get the previous day's rates (yesterday)
    //     $yesterday = Carbon::yesterday()->toDateString();
    //     $lastDayStart = DB::table('day_start')
    //         ->where('date', '=', $yesterday)
    //         ->first();

    //     // Return the data to the frontend
    //     return response()->json([
    //         'today' => $dayStart,
    //         'yesterday' => $lastDayStart ?? (object)[
    //             'ms_rate_day' => 0,
    //             'speed_rate_day' => 0,
    //             'hsd_rate_day' => 0,
    //             'ms_last_day' => 0,
    //             'speed_last_day' => 0,
    //             'hsd_last_day' => 0,
    //             'ms_diff' => 0,
    //             'speed_diff' => 0,
    //             'hsd_diff' => 0,
    //         ]
    //     ]);
    // }



    public function getLastDayRates()
    {
        // Retrieve the last day's rate from the database
        $lastDayRate = DayStart::latest()->first();

        // If no previous record exists, return default values
        if (!$lastDayRate) {
            return response()->json([
                'ms_last_day' => 0,
                'speed_last_day' => 0,
                'hsd_last_day' => 0,
            ]);
        }

        // Return the previous day's rate
        return response()->json([
            'ms_last_day' => $lastDayRate->ms_rate_day,
            'speed_last_day' => $lastDayRate->speed_rate_day,
            'hsd_last_day' => $lastDayRate->hsd_rate_day,
        ]);
    }

    // Controller method to store the day start data
    public function storeRate(Request $request)
    {
        // Get today's date and previous day's rate
        $today = $request->date;

        // Check if this is a new day (e.g., new day entry for tomorrow)
        $lastDayRates = DayStart::where('date', '<', $today)->orderBy('date', 'desc')->first();

        $msLastDay = $lastDayRates ? $lastDayRates->ms_rate_day : 0;
        $speedLastDay = $lastDayRates ? $lastDayRates->speed_rate_day : 0;
        $hsdLastDay = $lastDayRates ? $lastDayRates->hsd_rate_day : 0;

        // Store today's rates
        $dayStart = new DayStart();
        $dayStart->ms_rate_day = $request->ms_rate_day;
        $dayStart->speed_rate_day = $request->speed_rate_day;
        $dayStart->hsd_rate_day = $request->hsd_rate_day;
        $dayStart->ms_last_day = $msLastDay; // Store yesterday's rate
        $dayStart->speed_last_day = $speedLastDay; // Store yesterday's rate
        $dayStart->hsd_last_day = $hsdLastDay; // Store yesterday's rate
        $dayStart->ms_diff = $request->ms_diff;
        $dayStart->speed_diff = $request->speed_diff;
        $dayStart->hsd_diff = $request->hsd_diff;
        $dayStart->date = $today;
        $dayStart->added_by = $request->added_by;
        $dayStart->save();

        return response()->json([
            'message' => 'Rates saved successfully!',
            'data' => $dayStart
        ]);
    }



    public function show(DayStart $dayStart)
    {
        return response()->json([
            'message' => 'Day Start record retrieved successfully.',
            'data' => $dayStart
        ]);
    }

    // public function getLastEntryDate()
    // {
    //     $lastEntry = DayStart::latest('date')->first(); // Fetch the latest entry

    //     return response()->json([
    //         'last_entry_date' => $lastEntry ? $lastEntry->date : null
    //     ]);
    // }

    public function getLastEntryDate()
{
    // Fetch the latest date where both DayStart and DayEnd exist
    $lastEntryDate = DayStart::whereIn('date', function ($query) {
        $query->select('date')->from('day_ends'); // Ensures date exists in both tables
    })->latest('date')->first();

    return response()->json([
        'last_entry_date' => $lastEntryDate ? $lastEntryDate->date : null
    ]);
}

    public function checkTodayEntry()
    {
        $today = Carbon::now()->toDateString();
        $exists = DayStart::whereDate('date', $today)->exists();

        return response()->json(['exists' => $exists]);
    }



    // public function getValidDates()
    // {
    //     // Fetch dates that exist in both tables
    //     $validDates = DayStart::whereIn('date', DayEnd::pluck('date'))->pluck('date');

    //     return response()->json($validDates);
    // }

    // public function getValidDates()
    // {
    //     $validDates = DB::table('day_start')
    //         ->join('day_ends', 'day_start.date', '=', 'day_ends.date')
    //         ->select('day_start.date')
    //         ->orderBy('day_start.date')
    //         ->get();

    //     return response()->json($validDates);
    // }
    // public function getValidDates()
    // {
    //     // Fetch only dates where BOTH day_start and day_end exist
    //     $completedDates = DB::table('day_start')
    //         ->join('day_ends', 'day_start.date', '=', 'day_ends.date')
    //         ->select('day_start.date')
    //         ->orderBy('day_start.date')
    //         ->get()
    //         ->pluck('date')
    //         ->toArray();

    //     $validDates = [];

    //     // Only allow selection of next day if previous day is completed
    //     for ($i = 0; $i < count($completedDates); $i++) {
    //         $validDates[] = $completedDates[$i];
    //         if (isset($completedDates[$i + 1])) {
    //             $expectedNextDate = date('Y-m-d', strtotime($completedDates[$i] . ' +1 day'));
    //             if ($completedDates[$i + 1] !== $expectedNextDate) {
    //                 break;
    //             }
    //         }
    //     }

    //     return response()->json($validDates);
    // }
    public function getValidDates()
    {
        // Fetch only dates where BOTH day_start and day_end exist
        $completedDates = DB::table('day_start')
            ->join('day_ends', 'day_start.date', '=', 'day_ends.date')
            ->select('day_start.date')
            ->orderBy('day_start.date')
            ->get()
            ->pluck('date')
            ->toArray();

        $selectableDates = $completedDates;

        // If we have at least one completed date, add the next day
        if (!empty($completedDates)) {
            $latestCompletedDate = end($completedDates);
            $nextDate = date('Y-m-d', strtotime($latestCompletedDate . ' +1 day'));
            $selectableDates[] = $nextDate;
        } else {
            // If no dates are completed yet, make first day selectable
            $selectableDates[] = "2025-01-01";
        }

        return response()->json($selectableDates);
    }

    public function getLatestDate()
    {
        $latestDate = DayStart::orderBy('date', 'desc')->value('date');

        if ($latestDate) {
            return response()->json(['date' => $latestDate], 200);
        }

        return response()->json(['message' => 'No records found'], 404);
    }
}
