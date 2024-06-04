<?php

namespace App\Libraries\ControlAGV;

use App\Models\ControlAGV\Trans;
use App\Models\MasterData\MasterMachine;
use App\Models\MasterData\MasterProduct;
use App\Models\WarehouseSystem\ExportMaterials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransLibraries
{
    public function getAllListTrans()
    {
        return Trans::where('IsDelete', 0)->get();
    }

    public function destroyTrans($request)
    {
        $find_trans = Trans::where('IsDelete', 0)->where('ID', $request->ID)->first();
        // dd($find_trans);
        $data = Trans::where('IsDelete', 0)
            ->where('ID', $request->ID)
            ->update([
                'StatusID'     => 5,
                'User_Updated' => Auth::id(),
            ]);

        ExportMaterials::where('IsDelete', 0)->where('ID', $find_trans->Export_Material_ID)->update([
            'Type'      => 0,
            'Status'    => 0,
            'Note'      => 'Command AGV Was Destroy'
        ]);

        return $data;
    }

    public function succesTrans($request)
    {
        $find_trans = Trans::where('IsDelete', 0)->where('ID', $request->ID)->first();
        // dd($find_trans);
        $data = Trans::where('IsDelete', 0)
            ->where('ID', $request->ID)
            ->update([
                'StatusID'     => 2,
                'User_Updated' => Auth::id(),
            ]);

        // ExportMaterials::where('IsDelete', 0)->where('ID', $find_trans->Export_Material_ID)->update([
        //     'Type'      => 0,
        //     'Status'    => 3
        // ]);

        return $data;
    }
}
