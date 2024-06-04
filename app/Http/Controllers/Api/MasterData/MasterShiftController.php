<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use App\Models\History\History;
use App\Models\MasterData\History\ShiftHistory;
use Illuminate\Http\Request;
use App\Models\MasterData\MasterShift;

class MasterShiftController extends Controller
{
    public function index(Request $request)
    {
        $name      = $request->name;
        $symbols = $request->symbols;
        $masterShift = MasterShift::where('IsDelete', 0)
            ->when($name, function ($query, $name) {
                return $query->where('Name', $name);
            })->when($symbols, function ($query, $symbols) {
                return $query->where('Symbols', $symbols);
            })
            ->with([
                'user_created',
                'user_updated'
            ])
            ->orderBy('Time_Updated', 'desc')
            ->paginate($request->length);
        // dd($name,$symbols,$masterUnit);
        return response()->json([
            'recordsTotal' => $masterShift->total(),
            'recordsFiltered' => $masterShift->total(),
            'data' => $masterShift->toArray()['data']
        ]);
    }

    public function history(Request $request)
    {
        $shifts = History::where('Table_Name', 'Master_Shift')
            ->orderBy('ID', 'desc')
            // ->get();
            ->paginate($request->length);
        // dd($shifts);
        return response()->json([
            'recordsTotal' => $shifts->total(),
            'recordsFiltered' => $shifts->total(),
            'data' => $shifts->toArray()['data']
        ]);
    }
}
