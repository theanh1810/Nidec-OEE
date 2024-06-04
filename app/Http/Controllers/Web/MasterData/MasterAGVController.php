<?php

namespace App\Http\Controllers\Web\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\MasterData\MasterAGVLibraries;
use App\Libraries\UserLibraries;
use App\Exports\MasterData\MasterAGVExport;
use Auth;
use Carbon\Carbon;
use App\Models\ControlAGV\AgvType;
use App\Models\ControlAGV\AgvScope;


class MasterAGVController extends Controller
{
    private $agv;
    private $export;
    private $user;
    private $warehouse;

    public function __construct(
        MasterAGVLibraries $masterAGVLibraries,
        UserLibraries $userLibraries
    ) {
        $this->middleware('auth');
        $this->agv       = $masterAGVLibraries;
        $this->user      = $userLibraries;
    }

    public function index(Request $request)
    {
        $agv   = $this->agv->get_all_list_agv();
        $agvs  = $agv;

        return view(
            'master_data.agv.index',
            [
                'agv'   => $agv,
                'agvs'  => $agvs,
                'request' => $request
            ]
        );
    }

    public function filter(Request $request)
    {
        $name       = $request->Name;
        $symbols    = $request->IP;
        $mac        = $request->MAC;
        $agvs = $this->agv->get_all_list_agv();

        $agv  =  $this->agv->filter_agv($request);

        return view(
            'master_data.agv.index',
            [
                'agv'  => $agv,
                'agvs' => $agvs,
                'request'    => $request
            ]
        );
    }

    public function show(Request $request)
    {
        $users     = $this->user->get_all_list_user();
        $agv       = $this->agv->filter_agv($request);
        if (!$request->ID) {
            $agv = collect([]);
        }

        return view(
            'master_data.agv.add_or_update',
            [
                'agv'   => $agv->first(),
                'show'      => true,
                'users'     => $users
            ]
        );
    }


    public function add_or_update(Request $request)
    {
        $check = $this->agv->check_agv($request);
        // dd($check);
        $data  = $this->agv->add_or_update($request);

        return redirect()->route('masterData.agv')->with('success', $data->status);
    }

    public function destroy(Request $request)
    {
        $data = $this->agv->destroy($request);

        return redirect()->route('masterData.agv')->with('danger', $data);
    }

    public function efficiencyAgv(Request $request)
    {
        $data   = array();
        $arrAgv = $request->agv;
        $from   = Carbon::create($request->from)->startOfDay();
        $to     = Carbon::create($request->to)->endOfDay();
        $agvs   = $this->agv->get_all_list_agv();

        !$arrAgv ? $arrAgv = array() : '';

        for ($i = 0; $i < count($arrAgv); $i++) {
            $find = $this->agv->getEfficiency((object) [
                'agv'  => $request->agv[$i],
                'from' => $from,
                'to'   => $to
            ]);

            $find[0]->Name = $agvs->where('ID', $request->agv[$i])->first()->Name;

            array_push($data, $find[0]);
        }

        return response()->json([
            'data'  => $data
        ]);
    }
}
