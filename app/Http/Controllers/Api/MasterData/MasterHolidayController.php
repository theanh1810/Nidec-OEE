<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData\MasterHoliday;

class MasterHolidayController extends Controller
{
    public function index(Request $request) {
        $name 	 = $request->name;
		$symbols = $request->symbols;
        $masterHoliday = MasterHoliday::where('IsDelete',0)
        ->when($name, function($query, $name)
		{
			return $query->where('Name', $name);
		})->when($symbols, function($query, $symbols)
		{
			return $query->where('Symbols', $symbols);
		})
        ->with([
            'user_created',
            'user_updated'
        ])
        ->orderBy('Time_Updated','desc')
        ->paginate($request->length);
        // dd($name,$symbols,$masterHoliday);
        return response()->json([
            'recordsTotal' => $masterHoliday->total(),
            'recordsFiltered' => $masterHoliday->total(),
            'data' => $masterHoliday->toArray()['data']
        ]);
    }
}
