<?php

namespace App\Http\Controllers\Web\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\MasterData\MasterProductLibraries;
use App\Libraries\MasterData\MasterUnitLibraries;
use App\Libraries\MasterData\MasterMaterialsLibraries;
use App\Exports\MasterData\MasterProductExport;

use App\Models\MasterData\MasterProduct;
use App\Models\MasterData\MasterMaterials;
use App\Models\MasterData\MasterBOM;
use App\Models\MasterData\MasterMold;

class MasterProductController extends Controller
{
    private $product;
    private $unit;

    public function __construct(
        MasterProductLibraries $masterProductLibraries,
        MasterUnitLibraries $masterUnitLibraries,
        MasterProductExport  $masterProductExport,
        MasterMaterialsLibraries $MasterMaterialsLibraries
    ) {
        $this->middleware('auth');
        $this->product = $masterProductLibraries;
        $this->unit    = $masterUnitLibraries;
        $this->materials = $MasterMaterialsLibraries;
        $this->export = $masterProductExport;
    }

    public function index(Request $request)
    {
        $product     = $this->product->get_all_list_product();
        $products     = $product;
        return view(
            'master_data.product.index',
            [
                'product'  => $product,
                'products' => $products,
                'request'  => $request
            ]
        );
    }

    public function get_id_bom_and_materials(Request $request)
    {

        $data     = $this->product->get_id_bom_and_materials($request);

        $product     = $this->product->get_all_list_product();
        $materials  = $this->materials->get_all_list_materials();
        $pro = $product->where('ID', $request->ID)->first();
        return view(
            'master_data.product.bom',
            [
                'data'  => $data,
                'request'  => $request,
                'materials' => $materials,
                'product' => $product,
                'pro'    => $pro
            ]
        );
    }
    public function add_product_and_materials_to_bom(Request $request)
    {
        $data     = $this->product->add_product_and_materials_to_bom($request);

        return redirect()->route('masterData.product')->with('success', $data->status);
    }

    public function filter(Request $request)
    {
        $name     = $request->Name;
        $symbols  = $request->Symbols;
        $products = $this->product->get_all_list_product();

        $product  = $products->when($name, function ($q, $name) {
            return $q->where('Name', $name);
        })->when($symbols, function ($q, $symbols) {
            return $q->where('Symbols', $symbols);
        });

        return view(
            'master_data.product.index',
            [
                'product'  => $product,
                'products' => $products,
                'request'  => $request
            ]
        );
    }
    public function show(Request $request)
    {

        $product = $this->product->filter($request);
        $units   = $this->unit->get_all_list_unit();
        $materials = $this->materials->get_all_list_materials();
        if (!$request->ID) {
            $product = collect([]);
            // dd('1');
        }

        return view(
            'master_data.product.add_or_update',
            [
                'product' => $product->first(),
                'show'    => true,
                'units'   => $units,
                'materials' => $materials
            ]
        );
    }
    public function add_or_update(Request $request)
    {
        $check = $this->product->check_product($request);
        $data  = $this->product->add_or_update($request);

        return redirect()->route('masterData.product')->with('success', $data->status);
    }
    public function destroy(Request $request)
    {
        $data    = $this->product->destroy($request);
        $arr = [];
        array_push($arr, $data);
        return redirect()->route('masterData.product')->with('danger', __('Delete').''.__('Success'));
    }
    public function import_file(Request $request)
    {
        $data  = $this->product->import_file($request);

        if (count($data)  == 0) {
            return redirect()->route('masterData.product')->with('success', __('Success'));
        } else {
            return redirect()->route('masterData.product')->with('danger_array', $data);
        }

    }
    public function export_file(Request $request)
    {
        $data = $this->product->filter($request);
        $this->export->export($data,$request);
    }
}
