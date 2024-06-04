<?php

namespace App\Http\Controllers\Api\ControlAGV;

use App\Http\Controllers\Controller;
use App\Models\ControlAGV\Trans;
use Illuminate\Http\Request;

class TransController extends Controller
{
    public function index(Request $request)
    {
        // dd($request);
        $agv      = $request->agv;
        // dd($agv);
        $machine   = $request->machine;
        $status = $request->status;
        if ($status === '0') {
            $trans = Trans::where('IsDelete', 0)
                ->where('StatusID', 0)
                ->when($agv, function ($query, $agv) {
                    return $query->where('AGV', $agv);
                })
                ->when($machine, function ($query, $machine) {
                    return $query->where('Line_ID', $machine);
                })
                ->with([
                    'agv',
                    'lines',
                    'fromPoint',
                    'user_created:id,username',
                    'user_updated:id,username'
                ])
                ->orderBy('Time_Created', 'desc')
                ->paginate($request->length);
        } else if ($agv === '0') {
            $trans = Trans::where('IsDelete', 0)
                ->where('AGV', 0)
                ->when($status, function ($query, $status) {
                    return $query->where('StatusID', $status);
                })
                ->when($machine, function ($query, $machine) {
                    return $query->where('Line_ID', $machine);
                })
                ->with([
                    'agv',
                    'lines',
                    'fromPoint',
                    'user_created:id,username',
                    'user_updated:id,username'
                ])
                ->orderBy('Time_Created', 'desc')
                ->paginate($request->length);
        } else {

            $trans = Trans::where('IsDelete', 0)
                ->when($agv, function ($query, $agv) {
                    return $query->where('AGV', $agv);
                })
                ->when($machine, function ($query, $machine) {
                    return $query->where('Line_ID', $machine);
                })
                ->when($status, function ($query, $status) {
                    return $query->where('StatusID', $status);
                })
                ->with([
                    'agv',
                    'lines',
                    'fromPoint',
                    'user_created:id,username',
                    'user_updated:id,username'
                ])
                ->orderBy('Time_Created', 'desc')
                ->paginate($request->length);
        }

        // dd('1');
        return response()->json([
            'recordsTotal' => $trans->total(),
            'recordsFiltered' => $trans->total(),
            'data' => $trans->toArray()['data']
        ]);
    }
}
