<?php

namespace App\Http\Controllers\Web\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\MasterData\MasterDataImport;
use App\Libraries\MasterData\MasterLineLibraries;
use App\Libraries\MasterData\MasterMachineLibraries;
use App\Libraries\MasterData\MasterUnitLibraries;
use App\Exports\MasterData\MasterDataExport;

use App\Libraries\MasterData\MasterSupplierLibraries;
use App\Models\MasterData\MasterLine;

class MasterMachineController extends Controller
{
    private $export;

    public function __construct(
        MasterDataImport $masterDataImport,
        MasterMachineLibraries $MasterMachineLibraries,
        MasterUnitLibraries $masterUnitLibraries,
        MasterSupplierLibraries $masterSupplierLibraries,
        MasterLineLibraries $masterLineLibraries,
        MasterDataExport $masterDataExport
    ) {
        $this->middleware('auth');
        $this->import    = $masterDataImport;
        $this->machine   = $MasterMachineLibraries;
        $this->unit      = $masterUnitLibraries;
        $this->supplier  = $masterSupplierLibraries;
        $this->line  = $masterLineLibraries;
        $this->export = $masterDataExport;
    }

    public function index(Request $request)
    {
        // return 1;
        $machine  = $this->machine->get_all_list_machine();
        $machines = $machine;
        return view(
            'master_data.machine.index',
            [
                'machine'  => $machine,
                'machines' => $machines,
                'request'    => $request
            ]
        );
    }

    public function filter(Request $request)
    {
        $name       = $request->Name;
        $symbols    = $request->Symbols;
        $machines = $this->machine->get_all_list_machine();

        $machine  =  $this->machine->filter_machine($request);

        return view(
            'master_data.machine.index',
            [
                'machine'  => $machine,
                'machines' => $machines,
                'request'    => $request
            ]
        );
    }

    public function show(Request $request)
    {
        $units     = $this->unit->get_all_list_unit();
        $suppliers = $this->supplier->get_all_list_supplier();
        $machine = $this->machine->filter_machine($request);
        $lines = $this->line->get_all_list_line();
        if (!$request->ID) {
            $machine = collect([]);
        }

        return view(
            'master_data.machine.add_or_update',
            [
                'machine'   => $machine->first(),
                'show'      => true,
                'units'     => $units,
                'suppliers' => $suppliers,
                'lines' => $lines
            ]
        );
    }

    public function add_or_update(Request $request)
    {
        $check = $this->machine->check_machine($request);
        // dd($check);
        $data  = $this->machine->add_or_update($request);

        return redirect()->route('masterData.machine')->with('success', $data->status);
    }

    public function import_file_excel(Request $request)
    {
        // get data in file excel
        // dd($request);
        $data  = $this->machine->import_file($request);
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
        $data =  $this->export->product_export_file_excel($request);
    }

    public function destroy(Request $request)
    {
        $data = $this->machine->destroy($request);

        return redirect()->route('masterData.machine')->with('danger', $data);
    }

    public function return(Request $request)
    {
        $data  = $this->machine->return($request);

        return redirect()->route('masterData.machine')->with('success', __('Return') . '' . __('Success'));
    }
}
