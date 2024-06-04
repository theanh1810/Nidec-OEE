<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData\MasterStatus;

class MasterStatusController extends Controller
{
    public function index(Request $request) {
        $name 	 = $request->name;
		$symbols = $request->symbols;
        $masterStatus = MasterStatus::where('IsDelete',0)
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
        // dd($name,$symbols,$masterUnit);
        return response()->json([
            'recordsTotal' => $masterStatus->total(),
            'recordsFiltered' => $masterStatus->total(),
            'data' => $masterStatus->toArray()['data']
        ]);
    }
}
