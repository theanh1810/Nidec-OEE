<?php

namespace App\Http\Controllers\Web\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\MasterData\MasterUnitLibraries;

class MasterUnitController extends Controller
{
    private $unit;

	public function __construct(
		MasterUnitLibraries $masterUnitLibraries
	){
		$this->middleware('auth');
		$this->unit = $masterUnitLibraries;
	}
    
    public function index(Request $request)
    {
    	$unit 	= $this->unit->get_all_list_unit();
    	$units 	= $unit;
		// dd($unit);
    	return view('master_data.unit.index', 
    	[
			'unit'  => $unit,
			'units' => $units,
            'request'    => $request
    	]);
    }

    public function filter(Request $request)
    {
		$name    = $request->Name;
		$symbols = $request->Symbols;
		$units   = $this->unit->get_all_list_unit();
		
		$unit    = $units->when($name, function($q, $name) 
		{
			return $q->where('Name', $name);

		})->when($symbols, function($q, $symbols) 
		{
			return $q->where('Symbols', $symbols);
			
		});

    	return view('master_data.unit.index', 
    	[
			'unit'  => $unit,
			'units' => $units,
            'request' => $request
    	]);
    }

    public function show(Request $request)
    {
    	$unit = $this->unit->filter($request);
    	
    	if (!$request->ID) 
    	{
    		$unit = collect([]);
    		// dd('1');
    	}

    	return view('master_data.unit.add_or_update', 
    	[
    		'unit' => $unit->first(),
    		'show' => true
    	]);
    }

    public function add_or_update(Request $request)
    {
		$check = $this->unit->check_unit($request);
		$data  = $this->unit->add_or_update($request);
    	
    	return redirect()->route('masterData.unit')->with('success',$data->status);
    }

    public function destroy(Request $request)
    {
    	$data = $this->unit->destroy($request);

    	return redirect()->route('masterData.unit')->with('danger',$data);
    }

    public function import_file_excel(Request $request)
    {
        // get data in file excel
        // dd($request);
        $data  = $this->import->master_unit($request);
        // dd($data);
        if(count($data) > 1)
        {
            return redirect()->back()
            ->with('danger', $data);
        } 
        else
        {
            return redirect()->back()
            ->with('success', __('Success'));
        }
    }

    public function export_file(Request $request)
    {
        $data = $this->unit->filter($request);
        $this->export->export($data,$request); 
        
    }

	public function return(Request $request)
	{
		$data  = $this->unit->return($request);
    	
    	return redirect()->route('masterData.unit')->with('success',__('Return') .''.__('Success'));
	}
}
