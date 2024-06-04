<?php

namespace App\Http\Controllers\Web\WarehouseSystem;

use App\Http\Controllers\Controller;
use App\Libraries\ControlAGV\TransLibraries;
use Illuminate\Http\Request;
use Session;
use App\Libraries\WarehouseSystem\ExportMaterialsLibraries;
use App\Libraries\MasterData\MasterMaterialsLibraries;
use App\Libraries\MasterData\MasterMachineLibraries;
use App\Models\ControlAGV\Trans;
use App\Models\MasterData\MasterMachine;
use App\Models\ProductionPlan\CommandProductionDetail;
use App\Models\WarehouseSystem\ExportMaterials;
use Illuminate\Support\Facades\Auth;

class ExportMaterialsController extends Controller
{

    public function __construct(
        ExportMaterialsLibraries $ExportMaterialsLibraries,
        MasterMaterialsLibraries $MasterMaterialsLibraries,
        MasterMachineLibraries $MasterMachineLibraries,
        TransLibraries $transLibraries
    ) {
        $this->middleware('auth');
        $this->export = $ExportMaterialsLibraries;
        $this->materials = $MasterMaterialsLibraries;
        $this->machine = $MasterMachineLibraries;
        $this->trans = $transLibraries;
    }
    public function index()
    {
        $materials  = $this->materials->get_all_list_materials();
        $machine  = $this->machine->get_all_list_machine();
        return view(
            'warehouse-system.export.index',
            [
                'materials'  => $materials,
                'machine' => $machine
            ]
        );
    }

    public function export(Request $request)
    {
        if ($request->Type == 1) {
            $data = $this->export->export_with_agv($request);
        } else {
            $data =  $this->export->export_with_normal($request);
        }
        return redirect()->back()->with('success', $data);


        // if ($request->Type == 1) {
        //     // Tạo lệnh AGV và cập nhật lệnh xuất
        //     if (!$request->ID) {
        //         return (object)[
        //             'status'  => false,
        //             'message' => __('Command Export') . ' ' . __('Does Not Exist'),
        //         ];
        //     } else if (is_null($request->Machine)) {
        //         return (object)[
        //             'status'  => false,
        //             'message' => __('Machine') . ' ' . __('Does Not Exist'),
        //         ];
        //     } else if (is_null($request->Quantity) || $request->Quantity <= 0) {
        //         return (object)[
        //             'status'  => false,
        //             'message' => __('Quantity') . ' ' . __('Does Not Exist'),
        //         ];
        //     }
        //     $command_export = ExportMaterials::where('IsDelete', 0)->where('ID', $request->ID)->first();
        //     $machine = MasterMachine::where('IsDelete', 0)->where('ID', $command_export->To)->first();
        //     // dd($machine);
        //     if (!$machine) {
        //         return (object)[
        //             'status'  => false,
        //             'message' => __('Machine') . ' ' . __('Does Not Exist'),
        //         ];
        //     }
        //     if (!$command_export) {
        //         return (object)[
        //             'status'  => false,
        //             'message' => __('Command Export') . ' ' . __('Does Not Exist'),
        //         ];
        //     }
        //     $plan_detail = CommandProductionDetail::where('IsDelete', 0)->where('ID', $command_export->Plan_ID)->first();
        //     if($plan_detail)
        //     {
        //         return (object)[
        //             'status'  => false,
        //             'message' => __('Machine') . ' ' . __('Already Exists') . ' ' . __('Command'),
        //         ];  
        //     }
        //     // Check exist command in line
        //     $check = Trans::where('IsDelete', 0)
        //         ->where('Return_Point', $machine->ID)
        //         ->whereIn('StatusID', [0, 1])
        //         ->count();
        //     if ($check != 0) {
        //         return (object)[
        //             'status'  => false,
        //             'message' => __('Machine') . ' ' . __('Already Exists') . ' ' . __('Command'),
        //         ];
        //     }

        //     $arr = [
        //         'Line_ID'           => $machine->ID,
        //         'Return_Point'      => $machine->ID,
        //         'From_Point'        => $machine->ID, // vi tri lay hang can fix 1 diem kho cua nidec
        //         'StatusID'          => 0, // can hoi lai a Tung cac status id tuong ung du lieu gi
        //         'ProcessID'         => 0,
        //         'Type'              => 10, // type nay can hoi a Tung
        //         'Materials_ID'      => $command_export->Materials_ID,
        //         'Quantity'          => intval($request->Quantity),
        //         'Plan_Detail_ID'    => $plan_detail->ID,
        //         'User_Created'      => Auth::user()->id,
        //         'Time_Created'      => now(),
        //         'User_Updated'      => Auth::user()->id,
        //         'Time_Updated'      => now(),
        //     ];
        //     $trans = Trans::insert($arr);

        //     return (object)[
        //         'status' => true,
        //         'data'   => (object)['Quantity' => $request->Quantity],
        //     ];
        // } else {
        //     // XUất bằng tay
        // }
    }

    public function cancel(Request $request)
    {
        // dd($request);
        $data = $this->export->cancel($request);

        return redirect()->back()->with('success', __('Success'));
    }
}
