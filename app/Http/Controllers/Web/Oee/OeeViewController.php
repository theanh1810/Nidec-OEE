<?php

namespace App\Http\Controllers\Web\Oee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\MasterData\MasterMachineLibraries;

class OeeViewController extends Controller
{
    public function __construct(
        MasterMachineLibraries $MasterMachineLibraries,
    ) {
        $this->middleware('auth');
        $this->machine   = $MasterMachineLibraries;
    }

    public function visualization() {
        return view('oee.visualization');
    }

    public function detail($id = null) {
        if(!$id)
        {
            $machine = $this->machine->get_first();
            if($machine)
            {
                $id = $machine->ID;
            }
        }
        return view('oee.detail', [
            'id' => $id
        ]);
    }

    public function report() {
        return view('oee.report');
    }
}
