<?php

namespace App\Http\Controllers\Api\ProductionPlan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionPlan\CommandProduction;

class ProductionPlanController extends Controller
{
    public function index(Request $request) {
        $name 	 = $request->name;
		$symbols = $request->symbols;
        $month = $request->month;
        $year = $request->year;
        $masterCommandProduction = CommandProduction::where('IsDelete',0)
        ->when($name, function($query, $name)
		{
			return $query->where('Name', $name);
		})->when($symbols, function($query, $symbols)
		{
			return $query->where('Symbols', $symbols);
		})
        ->when($month, function($query, $month)
		{
			return $query->where('Month', $month);
		})
        ->when($year, function($query, $year)
		{
			return $query->where('Year', $year);
		})
        ->with([
            'user_created',
            'user_updated',
            'running'
        ])
        ->orderBy('Time_Updated','desc')
        ->paginate($request->length);
        // dd($masterCommandProduction);
        return response()->json([
            'recordsTotal' => $masterCommandProduction->total(),
            'recordsFiltered' => $masterCommandProduction->total(),
            'data' => $masterCommandProduction->toArray()['data']
        ]);
    }
}
