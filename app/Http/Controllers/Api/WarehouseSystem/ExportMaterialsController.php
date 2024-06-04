<?php

namespace App\Http\Controllers\Api\WarehouseSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WarehouseSystem\ExportMaterials;

class ExportMaterialsController extends Controller
{
    public function index(Request $request) {
        $materials 	 = $request->materials;
		$machine = $request->machine;
        $status = $request->status;
        $type = $request->type;
        // dd($request);
        if($status === '0')
        {
            $masterExportMaterials = ExportMaterials::where('IsDelete',0)
            ->where('Status',0)
            ->when($materials, function($query, $materials)
            {
                return $query->where('Materials_ID', $materials);
            })
            ->when($machine, function($query, $machine)
            {
                return $query->where('To', $machine);
            })
            
            ->when($type, function($query, $type)
            {
                return $query->where('Type', $type);
            })
            ->with([
                'user_created',
                'user_updated',
                'materials',
                'machine',
                'product',
                'plan'
            ])
            ->orderBy('Time_Updated','desc')
            ->paginate($request->length);
        }
        else
        {
            $masterExportMaterials = ExportMaterials::where('IsDelete',0)
            ->when($materials, function($query, $materials)
            {
                return $query->where('Materials_ID', $materials);
            })
            ->when($machine, function($query, $machine)
            {
                return $query->where('To', $machine);
            })
            ->when($status, function($query, $status)
            {
                return $query->where('Status', $status);
            })
            ->when($type, function($query, $type)
            {
                return $query->where('Type', $type);
            })
            ->with([
                'user_created',
                'user_updated',
                'materials',
                'machine',
                'product',
                'plan'
            ])
            ->orderBy('Time_Updated','desc')
            ->paginate($request->length);
        }
        
        return response()->json([
            'recordsTotal' => $masterExportMaterials->total(),
            'recordsFiltered' => $masterExportMaterials->total(),
            'data' => $masterExportMaterials->toArray()['data']
        ]);
    }
}
