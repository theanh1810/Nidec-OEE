<?php

namespace App\Http\Controllers\Api\ProductionPlan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionPlan\CommandProductionDetail;
use App\Models\History\History;
use Carbon\Carbon;

class ProductionPlanDetailController extends Controller
{
    public function index(Request $request)
    {
        $product      = $request->product;
        $machine = $request->machine;
        $status = $request->status;
        $from = $request->from;
        $to = $request->to;

        if ($status > 0) {
            $masterCommandProductionDetail = CommandProductionDetail::where('IsDelete', 0)
                ->whereIn('Status', $status)
                ->when($product, function ($query, $product) {
                    return $query->where('Product_ID', $product);
                })
                ->when($machine, function ($query, $machine) {
                    return $query->where('Part_Action', $machine);
                })
                ->when($from, function ($query, $from) {
                    return $query->where('Date', '>=', $from);
                })
                ->when($to, function ($query, $to) {
                    return $query->where('Date', '<=', $to);
                })
                ->where('Command_ID', $request->planId)
                ->with([
                    'command',
                    'product',
                    'machine',
                    'mold',
                    'user_created',
                    'user_updated'
                ])
                ->orderBy('Date')->orderBy('Part_Action')
                ->paginate($request->length);
        } else {
            $masterCommandProductionDetail = CommandProductionDetail::where('IsDelete', 0)
                ->where('Status', 4)
                ->when($product, function ($query, $product) {
                    return $query->where('Product_ID', $product);
                })
                ->when($machine, function ($query, $machine) {
                    return $query->where('Part_Action', $machine);
                })
                ->when($from, function ($query, $from) {
                    return $query->where('Date', '>=', $from);
                })
                ->when($to, function ($query, $to) {
                    return $query->where('Date', '<=', $to);
                })
                ->where('Command_ID', $request->planId)
                ->with([
                    'command',
                    'product',
                    'machine',
                    'mold',
                    'user_created',
                    'user_updated'
                ])
                ->orderBy('Date')->orderBy('Part_Action')
                ->paginate($request->length);
        }


        return response()->json([
            'recordsTotal' => $masterCommandProductionDetail->total(),
            'recordsFiltered' => $masterCommandProductionDetail->total(),
            'data' => $masterCommandProductionDetail->toArray()['data']
        ]);
    }

    public function history(Request $request)
    {
        $name      = $request->name;
        $symbols = $request->symbols;
        $masterProduct =  History::where('Table_Name', 'Command_Production_Detail')
            ->where('ID_Main', $request->idplan)
            ->orderBy('ID', 'desc')
            ->paginate($request->length);
        // dd($masterProduct);
        return response()->json([
            'recordsTotal' => $masterProduct->total(),
            'recordsFiltered' => $masterProduct->total(),
            'data' => $masterProduct->toArray()['data']
        ]);
    }

    public function visualization_data(Request $request)
    {
        $product      = $request->product;
        $machine = $request->machine;
        $from = $request->from;
        $to = $request->to;
        $data = CommandProductionDetail::where('IsDelete', 0)
            ->when($product, function ($query, $product) {
                return $query->where('Product_ID', $product);
            })
            ->when($machine, function ($query, $machine) {
                return $query->where('Part_Action', $machine);
            })
            ->when($from, function ($query, $from) {
                return $query->where('Date', '>=', $from);
            })
            ->when($to, function ($query, $to) {
                return $query->where('Date', '<=', $to);
            })
            // ->where('Command_ID',$request->plan_id)
            ->with([
                'command',
                'product',
                'machine',
                'mold',
                'user_created',
                'user_updated'
            ])
            ->orderBy('Status')->orderBy('Date')->orderBy('ID')
            ->get();
        // dd($data->GroupBy('Part_Action'));
        $stt = 0;
        $stt1 = 0;
        $stt2 = 0;
        $arr = [];
        foreach ($data->GroupBy('Part_Action') as $key => $value) {
            $stt = $stt1 + 1;
            $quantity = 0;
            $quantity1 = 0;


            foreach ($value->GroupBy('Product_ID') as $value1) {
                $quantity2 = 0;
                $quantity3 = 0;
                $stt1 = $stt2 + $stt + 1;
                foreach ($value1 as $value2) {

                    $stt2 = $stt2 + $stt1 + 1;

                    $obj2 = (object)([
                        'id' => $stt2,
                        'quan' => $value2->product ? $value2->product->Symbols : '',
                        'text' => floatval($value2->Quantity_Production ? $value2->Quantity_Production : 0) . '/' . floatval($value2->Quantity),
                        'start_date' => Carbon::create($value2->Date)->isoFormat('DD-MM-YYYY'),
                        'duration' => 1,
                        'parent' => $stt1,
                        'progress' => floatval($value2->Quantity_Production ? $value2->Quantity_Production / $value2->Quantity : 0)
                    ]);
                    array_push($arr, $obj2);
                    $quantity += $value2->Quantity;
                    $quantity1 += $value2->Quantity_Production;
                    $quantity2 += $value2->Quantity;
                    $quantity3 += $value2->Quantity_Production;
                }
                $obj1 = (object)([
                    'id' => $stt1,
                    'quan' => $value2->product ? $value2->product->Symbols : '',
                    'text' => floatval($quantity3 ? $quantity3 : 0) . '/' . floatval($quantity2),
                    'start_date' => Carbon::create($value2->Date)->isoFormat('DD-MM-YYYY'),
                    'duration' => 1,
                    'render' => 'split',
                    'parent' => $stt,
                    'progress' => floatval($value2->Quantity_Production ? $value2->Quantity_Production / $value2->Quantity : 0)
                ]);
                array_push($arr, $obj1);
            }
            $obj = (object)([
                'id' => $stt,
                'quan' => $value2->machine ? $value2->machine->Symbols : '',
                'text' => floatval($quantity1 ? $quantity1 : 0) . '/' . floatval($quantity),
                'progress' => floatval($quantity1 ? $quantity1 / $quantity : 0),
                'type' => "project",
                'open' => true,
            ]);
            array_push($arr, $obj);
            // dd($arr);
        }
        // dd($arr);
        return   response()->json(
            [
                'data' => $arr
            ]
        );
    }
}
