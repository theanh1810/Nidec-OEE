<?php

namespace App\Http\Controllers\Web\ControlAGV;

use App\Http\Controllers\Controller;
use App\Libraries\ControlAGV\TransLibraries;
use App\Libraries\MasterData\MasterAGVLibraries;
use App\Libraries\MasterData\MasterMachineLibraries;
use App\Libraries\MasterData\MasterMaterialsLibraries;
use App\Libraries\MasterData\MasterWarehouseDetailLibraries;
use App\Models\ControlAGV\MasterError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TransController extends Controller
{
    private $trans;

    public function __construct(
        TransLibraries $transLibraries,
        MasterMachineLibraries $masterMachineLibraries,
        MasterMaterialsLibraries $masterMaterialsLibraries,
        MasterWarehouseDetailLibraries $masterWarehouseDetailLibraries,
        MasterAGVLibraries $masterAGVLibraries
    ) {
        $this->middleware('auth');
        $this->trans = $transLibraries;
        $this->machines = $masterMachineLibraries;
        $this->materials = $masterMaterialsLibraries;
        $this->warehouse_detail = $masterWarehouseDetailLibraries;
        $this->agv = $masterAGVLibraries;
    }

    public function index(Request $request)
    {
        $warehouse_detail = $this->warehouse_detail->get_all_list_warehouse_detail();
        $machines = $this->machines->get_all_list_machine();
        $materials = $this->materials->get_all_list_materials();
        $agvs = $this->agv->get_all_list_agv();
        $tran     = $this->trans->getAllListTrans();
        $trans     = $tran;

        return view('control_agv.list_command.index', [
            'warehouse_detail' => $warehouse_detail,
            'machines' => $machines,
            'materials' => $materials,
            'agvs' => $agvs,
            'tran'  => $tran,
            'trans' => $trans,
            'request'    => $request
        ]);
    }

    public function destroyTrans(Request $request)
    {
        // dd($request);
        // DB::transaction(function () use ($request) {
        $data = $this->trans->destroyTrans($request);
        // });
        return redirect()->route('index.trans')->with('danger', $data);
    }

    public function successTrans(Request $request)
    {
        // dd($request);
        // DB::transaction(function () use ($request) {
        $data = $this->trans->succesTrans($request);
        // });
        return redirect()->route('index.trans')->with('success', $data);
    }

    public function efficienciesAGV()
    {
        $agvs = $this->agv->get_all_list_agv();

        return view('control_agv.efficiency.index', [
            'agvs'  => $agvs
        ]);
    }

    public function errorAgv(Request $request)
    {
        $arrAgv = $request->agv;
        $ag     = true;
        $from   = Carbon::create($request->from)->startOfDay();
        $to     = Carbon::create($request->to)->endOfDay();

        if (!$arrAgv) {
            $arrAgv = [];
        }

        // if (count($arrAgv) == 0) {
        //     $ag = 0;
        // }

        $agvs   = $this->agv->get_all_list_agv()
            ->when($ag, function ($q) use ($arrAgv) {
                return $q->whereIn('ID', $arrAgv);
            })
            ->pluck('ID')
            ->toArray();

        count($agvs) == '0' ? $agvs = [0] : '';

        $errors = MasterError::where('ERROR', 'not like', '%ok%')
            ->where('magnet', '>=', 0)
            ->whereNotIn('ID', [0, 3, 9])
            ->withCount([
                'log AS log_sum' => function ($query) use ($agvs, $from, $to) {
                    $query->select(DB::raw("SUM(PERIOD) as logsum"))
                        ->whereIn('AGV', $agvs)
                        ->where('TRANS_ID', '!=', 0)
                        ->where('CREATED_TIME', '>=', $from)
                        ->where('CREATED_TIME', '<=', $to);
                }
            ])->get();

        return response()->json([
            'status' => true,
            'data'   => $errors,
            // 'agv'    => $arrAgv
        ]);
    }
}
