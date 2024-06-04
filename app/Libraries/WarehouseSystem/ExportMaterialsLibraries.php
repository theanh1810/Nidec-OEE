<?php

namespace App\Libraries\WarehouseSystem;

use App\Models\ControlAGV\Trans;
use Illuminate\Validation\Rule;
use App\Models\WarehouseSystem\ExportMaterials;
use App\Models\WarehouseSystem\InventoryMachine;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use Auth;
use App\Models\MasterData\MasterMachine;
use App\Models\MasterData\MasterProduct;

class ExportMaterialsLibraries
{

    public function create_command_export($request)
    {
        // dd($request);
        $check = ExportMaterials::where('IsDelete', 0)->where('Materials_ID', $request->Materials_ID)->where('To', $request->Machine_ID)->where('Status', '<', 2)->first();
        if (!$check) {
            ExportMaterials::create([
                'Materials_ID' => $request->Materials_ID,
                'Quantity'    => $request->Quantity,
                'To'          => $request->Machine_ID,
                'Type' => 0,
                'Status' => 0,
                'User_Created' => Auth::user()->id,
                'User_Updated' => Auth::user()->id,
                'IsDelete' => 0
            ]);
        }
        return true;
    }

    public function export_with_normal($request)
    {
        // dd($request);
        if (!$request->ID) {
            return  __('Command Export') . ' ' . __('Does Not Exist');
        } else if (is_null($request->Machine)) {
            return  __('Machine') . ' ' . __('Does Not Exist');
        } else if (is_null($request->Quantity) || $request->Quantity <= 0) {
            return  __('Quantity') . ' ' . __('Does Not Exist');
        } else if (is_null($request->Materials)) {
            return  __('Product') . ' ' . __('Does Not Exist');
        }
        $machine = MasterMachine::where('IsDelete', 0)->where('Name', $request->Machine)->first();
        if ($request->Quantity > 0) {
            if ($machine) {
                $inven = InventoryMachine::where('Machine_ID', $machine->ID)->first();

                if ($inven) {
                    InventoryMachine::where('ID', $inven->ID)->update([
                        'Quantity' => $request->Quantity + $inven->Quantity,
                        'User_Updated' => Auth::user()->id,
                    ]);
                } else {
                    InventoryMachine::create([
                        'Machine_ID' => $machine->ID,
                        'Quantity' => $request->Quantity,
                        'User_Created' => Auth::user()->id,
                        'User_Updated' => Auth::user()->id,
                        'IsDelete' => 0
                    ]);
                }
                ExportMaterials::where('ID', $request->ID)->update([
                    'Quantity_Export' => $request->Quantity,
                    'Type' => 2,
                    'Status' => 3,
                    'User_Updated' => Auth::user()->id,
                ]);
                return __('Export') . ' ' . __('Success');
            } else {
                return __('Machine') . ' ' . __('Not Exit');
            }
        } else {
            return __('Quantity') . ' ' . __('Not Exit');
        }
    }

    public function export_with_agv($request)
    {
        // dd('run');
        if (!$request->ID) {
            return  __('Command Export') . ' ' . __('Does Not Exist');
        } else if (is_null($request->Machine)) {
            return  __('Machine') . ' ' . __('Does Not Exist');
        } else if (is_null($request->Quantity) || $request->Quantity <= 0) {
            return  __('Quantity') . ' ' . __('Does Not Exist');
        } else if (is_null($request->Materials)) {
            return  __('Product') . ' ' . __('Does Not Exist');
        }
        $command_export = ExportMaterials::where('IsDelete', 0)->where('ID', $request->ID)->first();
        $machine = MasterMachine::where('IsDelete', 0)->where('ID', $command_export->To)->first();
        $product = MasterProduct::where('IsDelete', 0)->where('ID', $command_export->Product_ID)->first();
        // dd($machine);
        if (!$machine) {
            return  __('Machine') . ' ' . __('Does Not Exist');
        }
        if (!$product) {
            return   __('Product') . ' ' . __('Does Not Exist');
        }
        if (!$command_export) {
            return   __('Command Export') . ' ' . __('Does Not Exist');
        }
        // Check exist command in line
        $check = Trans::where('IsDelete', 0)
            ->where('Return_Point', $machine->ID)
            ->whereIn('StatusID', [0, 1])
            ->count();
        if ($check != 0) {
            return  __('Machine') . ' ' . __('Already Exists') . ' ' . __('Command');
        }

        $arr = [
            'Line_ID'               => $machine->ID,
            'Return_Point'          => $machine->ID,
            'From_Point'            => $machine->ID, // vi tri lay hang can fix 1 diem kho cua nidec
            'StatusID'              => 0,
            'ProcessID'             => 0,
            'Type'                  => 1,
            'Materials_ID'          => $command_export->Materials_ID,
            'Quantity'              => intval($request->Quantity),
            'Export_Material_ID'    => $command_export->ID,
            'User_Created'          => Auth::user()->id,
            'Time_Created'          => now(),
            'User_Updated'          => Auth::user()->id,
            'Time_Updated'          => now(),
        ];
        $trans = Trans::insert($arr);

        ExportMaterials::where('ID', $request->ID)->update([
            'Type'                  => 1,
            'Status'                => 1,
            'User_Updated'          => Auth::user()->id,
        ]);
        return __('Export') . ' ' . __('Success');
    }

    public function cancel($request)
    {
        $data = ExportMaterials::where('IsDelete', 0)
            ->where('ID', $request->ID)
            ->update([
                'IsDelete'     => 1,
                'User_Updated' => Auth::id(),
            ]);
        return $data;
    }
}
