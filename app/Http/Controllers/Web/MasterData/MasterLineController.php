<?php

namespace App\Http\Controllers\Web\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\MasterData\MasterDataImport;
use App\Libraries\MasterData\MasterLineLibraries;

class MasterLineController extends Controller
{
    public function __construct(
        MasterDataImport $masterDataImport,
        MasterLineLibraries $MasterLineLibraries,
    ) {
        $this->middleware('auth');
        $this->import    = $masterDataImport;
        $this->line   = $MasterLineLibraries;
    }

    public function index(Request $request)
    {
        // return 1;
        $line  = $this->line->get_all_list_line();
        $lines = $line;
        return view(
            'master_data.line.index',
            [
                'line'  => $line,
                'lines' => $lines,
                'request'    => $request
            ]
        );
    }

    public function filter(Request $request)
    {
        $lines = $this->line->get_all_list_line();

        $line  =  $this->line->filter_line($request);

        return view(
            'master_data.machine.index',
            [
                'line'  => $line,
                'lines' => $lines,
                'request'    => $request
            ]
        );
    }

    public function show(Request $request)
    {
        $line = $this->line->filter_line($request);

        if (!$request->ID) {
            $line = collect([]);
        }

        return view(
            'master_data.line.add_or_update',
            [
                'line'   => $line->first(),
                'show'      => true
            ]
        );
    }

    public function add_or_update(Request $request)
    {
        $check = $this->line->check_line($request);
        // dd($check);
        $data  = $this->line->add_or_update($request);

        return redirect()->route('masterData.line')->with('success', $data->status);
    }

    public function import_file_excel(Request $request)
    {
        // get data in file excel
        // dd($request);
        $data  = $this->line->import_file($request);
        // dd($data[0]);
        if (count($data) >= 1) {
            return redirect()->back()
                ->with('danger_array', $data);
        } else {
            return redirect()->back()
                ->with('success', __('Success'));
        }
    }

    public function export_file(Request $request)
    {
        $data = $this->line->filter_line($request);
        // dd($data);
        $this->export->export($data, $request);
    }

    public function destroy(Request $request)
    {
        $data = $this->line->destroy($request);

        return redirect()->route('masterData.line')->with('danger', $data);
    }

    public function return(Request $request)
    {
        $data  = $this->line->return($request);

        return redirect()->route('masterData.line')->with('success', __('Return') . '' . __('Success'));
    }
}
