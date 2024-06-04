<?php

namespace App\Http\Controllers\Web\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\MasterData\MasterShiftLibraries;
use Carbon\Carbon;

class MasterShiftController extends Controller
{
    private $shift;

    public function __construct(
        MasterShiftLibraries $masterShiftLibraries
    ) {
        $this->middleware('auth');
        $this->shift = $masterShiftLibraries;
    }

    public function index()
    {

        $shift     = $this->shift->get_all_list_shift();
        $shifts = $shift;

        return view(
            'master_data.shift.index',
            [
                'shift'  => $shift,
                'shifts' => $shifts,
            ]
        );
    }

    public function filter(Request $request)
    {
        $name    = $request->Name;
        $from    = $request->from;
        $to    = $request->to;
        $shifts   = $this->shift->get_all_list_shift();
        // dd($shifts);

        $shift    = $shifts->when($name, function ($q, $name) {
            return $q->where('Name', $name);
        })->when($from, function ($q, $from) {
            return $q->where('Start_Time', '>=', $from);
        })
            ->when($to, function ($q, $to) {
                return $q->where('End_Time', '<=', $to);
            });

        return view(
            'master_data.shift.index',
            [
                'shift'  => $shift,
                'shifts' => $shifts,
            ]
        );
    }

    public function show(Request $request)
    {
        $shift = $this->shift->filter($request);

        if (!$request->ID) {
            $shift = collect([]);
            // dd('1');
        }

        return view(
            'master_data.shift.add_or_update',
            [
                'shift' => $shift->first(),
                'show' => true
            ]
        );
    }

    public function add_or_update(Request $request)
    {
        // dd($request);
        $check = $this->shift->check_shift($request);
        $data  = $this->shift->add_or_update($request);

        return redirect()->route('masterData.shift')->with('success', $data->status);
    }

    public function destroy(Request $request)
    {
        $data = $this->shift->destroy($request);

        return redirect()->route('masterData.shift')->with('danger', $data);
    }

    public function return(Request $request)
    {
        $data  = $this->shift->return($request);

        return redirect()->route('masterData.shift')->with('success', __('Return') . '' . __('Success'));
    }
}
