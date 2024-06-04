<?php

namespace App\Http\Controllers\Web\ProductionPlan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Imports\MasterData\MasterDataImport;
use App\Libraries\ProductionPlan\CommandProductionLibraries;
use App\Libraries\ProductionPlan\CommandProductionDetailLibraries;
use App\Libraries\MasterData\MasterProductLibraries;
use App\Libraries\MasterData\MasterMachineLibraries;
use App\Libraries\MasterData\MasterMoldLibraries;
use Carbon\Carbon;
use App\Exports\ProductionPlan\ProductionDetail;
use App\Models\History\HistoriesImportFile;

class ProductionPlanController extends Controller
{

    public function __construct(
        CommandProductionLibraries $CommandProductionLibraries,
        MasterProductLibraries $MasterProductLibraries,
        MasterMachineLibraries $MasterMachineLibraries,
        CommandProductionDetailLibraries $CommandProductionDetailLibraries,
        MasterMoldLibraries   $MasterMoldLibraries,
        ProductionDetail     $ProductionDetail
    ) {
        $this->middleware('auth');
        $this->plan = $CommandProductionLibraries;
        $this->product = $MasterProductLibraries;
        $this->machine = $MasterMachineLibraries;
        $this->plan_detail = $CommandProductionDetailLibraries;
        $this->mold = $MasterMoldLibraries;
        $this->export = $ProductionDetail;
    }
    public function index()
    {
        $plan     = $this->plan->get_all_list_plan();
        $plans     = $plan;
        return view(
            'productionplan.command.index',
            [
                'plan'  => $plan,
                'plans' => $plans
            ]
        );
    }
    public function filter(Request $request)
    {
        $name    = $request->Name;
        $symbols = $request->Symbols;
        $plans   = $this->plan->get_all_list_plan();

        $plan    = $plans->when($name, function ($q, $name) {
            return $q->where('Name', $name);
        })->when($symbols, function ($q, $symbols) {
            return $q->where('Symbols', $symbols);
        });

        return view(
            'productionplan.command.index',
            [
                'plan'  => $plan,
                'plans' => $plans
            ]
        );
    }
    public function add_or_update(Request $request)
    {
        $check = $this->plan->check_plan($request);
        $data  = $this->plan->add_or_update_auto_name($request);
        return redirect()->route('productionplan')->with('success', $data->status);
    }

    public function destroy(Request $request)
    {
        $data = $this->plan->destroy($request);

        return redirect()->route('productionplan')->with('danger', $data);
    }

    public function detail(Request $request)
    {
        $file_up = HistoriesImportFile::where('ID_Main', $request->ID)->orderBy('Time_Created', 'desc')->first();

        $product = $this->product->get_all_list_product();
        $machine  = $this->machine->get_all_list_machine();
        $plans   = $this->plan->get_all_list_plan();
        $mold    = $this->mold->get_all_list_mold();
        $plan = $plans->where('ID', $request->ID)->first();

        $data = $this->plan_detail->get_list_with_command($request);
        // dd($file_up);
        return view(
            'productionplan.detail.index',
            [
                'product' => $product,
                'machine' => $machine,
                'plan'  => $plan,
                'mold'  => $mold,
                'data' => $data,
                'request' => $request,
                'file_up' => $file_up
            ]
        );
    }
    public function detail_filter(Request $request)
    {
        $product = $this->product->get_all_list_product();
        $machine  = $this->machine->get_all_list_machine();
        $plans   = $this->plan->get_all_list_plan();
        $plan = $plans->where('ID', $request->ID)->first();

        $data = $this->plan_detail->get_list_with_command_filter($request);
        return view(
            'productionplan.detail.index',
            [
                'product' => $product,
                'machine' => $machine,
                'plan'  => $plan,
                'data' => $data,
                'request' => $request
            ]
        );
    }
    public function detail_add_or_update(Request $request)
    {
        $status = $this->plan_detail->add_or_update($request);
        if ($status == 'true') {
            return redirect()->back()->with('success', __('Create') . ' ' . __('Success'));
        } else {
            return redirect()->back()->with('danger', $status);
        }
    }

    public function detail_destroy(Request $request)
    {
        $data = $this->plan_detail->destroy($request);

        return redirect()->back()->with('danger', $data);
    }
    public function detail_visualation(Request $request)
    {


        return view(
            'productionplan.detail.visualation'
        );
    }

    public function detail_export_materials(Request $request)
    {
        $data = $this->plan_detail->detail_export_materials($request);

        return redirect()->back()->with('success', $data);
    }

    public function import_file_excel(Request $request)
    {
        $data = $this->plan_detail->import_file($request);
        // dd($data);
        if (count($data) > 0) {
            return redirect()->back()->with('danger_array', $data);
        } else {
            return redirect()->back()->with('success', __('Import By File Excel') . ' ' . __('Success'));
        }
    }
    public function visualization(Request $request)
    {
        $products = $this->product->get_all_list_product();
        $machines = $this->machine->get_all_list_machine();
        if (!$request->from) {
            $request->from =  date("Y-m-d", strtotime(Carbon::now()->toDateString()));
        }
        if (!$request->to) {
            $request->to =  date("Y-m-d", strtotime($request->to . " +5 day"));
        }
        return view(
            'productionplan.detail.visualization',
            [
                'ID' => $request->ID,
                'products' => $products,
                'machines' => $machines,
                'pro' => $request->product,
                'mac' => $request->machine,
                'to' => $request->to,
                'from' => $request->from
            ]
        );
    }

    public function export(Request $request)
    {
        $plan = [];
        $data = [];
        $this->export->export($plan, $data, $request);
    }
    public function cancel(Request $request)
    {
        $data = $this->plan_detail->cancel($request);

        return redirect()->back()->with('success', $data);
    }
}
