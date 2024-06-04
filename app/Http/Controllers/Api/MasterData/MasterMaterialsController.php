<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\History\MaterialHistory;
use Illuminate\Http\Request;
use App\Models\MasterData\MasterMaterials;

class MasterMaterialsController extends Controller
{
    public function index(Request $request)
    {
        $name      = $request->name;
        $symbols = $request->symbols;
        $masterMaterials = MasterMaterials::where('IsDelete', 0)
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
        // dd($name,$symbols,$masterMaterials);
        return response()->json([
            'recordsTotal' => $masterMaterials->total(),
            'recordsFiltered' => $masterMaterials->total(),
            'data' => $masterMaterials->toArray()['data']
        ]);
    }

    public function history(Request $request)
    {
        $name      = $request->name;
        $symbols = $request->symbols;
        $masterProduct = MaterialHistory::where('IsDelete', 0)
            ->where('Material_ID', $request->materialid)
            ->when($name, function ($query, $name) {
                return $query->where('Name', $name);
            })->when($symbols, function ($query, $symbols) {
                return $query->where('Symbols', $symbols);
            })
            ->with([
                'user_created:id,username',
                'user_updated:id,username'
            ])
            ->orderBy('Time_Updated', 'desc')
            ->paginate($request->length);
        // dd($run);
        return response()->json([
            'recordsTotal' => $masterProduct->total(),
            'recordsFiltered' => $masterProduct->total(),
            'data' => $masterProduct->toArray()['data']
        ]);
    }
}
