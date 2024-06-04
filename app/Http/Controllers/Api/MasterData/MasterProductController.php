<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\History\ProductHistory;
use Illuminate\Http\Request;
use App\Models\MasterData\MasterProduct;
use App\Models\History\History;
class MasterProductController extends Controller
{
    public function index(Request $request)
    {
        $name      = $request->name;
        $symbols = $request->symbols;
        $masterProduct = MasterProduct::where('IsDelete', 0)
            ->when($name, function ($query, $name) {
                return $query->where('Name', $name);
            })->when($symbols, function ($query, $symbols) {
                return $query->where('Symbols', $symbols);
            })
            ->with([
                'user_created',
                'user_updated',
                'unit',
                'bom',
                'bom.materials',
                'bom.mold',
                'running'
            ])
            ->orderBy('Time_Updated', 'desc')
            ->paginate($request->length);
        // dd($name,$symbols,$masterUnit);
        // dd($masterProduct);
        return response()->json([
            'recordsTotal' => $masterProduct->total(),
            'recordsFiltered' => $masterProduct->total(),
            'data' => $masterProduct->toArray()['data']
        ]);
    }

    public function history(Request $request)
    {
        $name      = $request->name;
        $symbols = $request->symbols;
        $masterProduct =  History::where('Table_Name', 'Master_BOM')
        ->orderBy('ID', 'desc')
        ->paginate($request->length);
       
        return response()->json([
            'recordsTotal' => $masterProduct->total(),
            'recordsFiltered' => $masterProduct->total(),
            'data' => $masterProduct->toArray()['data']
        ]);
    }
}
