<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use App\Models\History\History;
use App\Models\MasterData\MasterAGV;
use Illuminate\Http\Request;
use App\Models\MasterData\MasterMachine;

class MasterAGVController extends Controller
{
    public function index(Request $request)
    {
        // dd('1');
        $name = $request->name;
        $ip = $request->ip;
        $mac = $request->mac;
        $masterAGV = MasterAGV::where('IsDelete', 0)
            ->when($name, function ($query, $name) {
                return $query->where('Name', $name);
            })->when($ip, function ($query, $ip) {
                return $query->where('IP', $ip);
            })->when($mac, function ($query, $mac) {
                return $query->where('MAC', $mac);
            })
            ->with([
                'user_created',
                'user_updated',
                'manager'
            ])
            ->orderBy('Time_Updated', 'desc')
            ->paginate($request->length);
        // dd($name,$symbols,$masterMachine);
        return response()->json([
            'recordsTotal' => $masterAGV->total(),
            'recordsFiltered' => $masterAGV->total(),
            'data' => $masterAGV->toArray()['data']
        ]);
    }

    public function history(Request $request)
    {


        $agv = History::where('Table_Name', 'Master_AGV')
            ->orderBy('ID', 'desc')
            // ->get();
            ->paginate($request->length);
        // dd($agv);
        return response()->json([
            'recordsTotal' => $agv->total(),
            'recordsFiltered' => $agv->total(),
            'data' => $agv->toArray()['data']
        ]);
    }
}
