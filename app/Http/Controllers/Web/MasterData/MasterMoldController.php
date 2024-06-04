<?php

namespace App\Http\Controllers\Web\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\MasterData\MasterMoldLibraries;
use App\Imports\MasterData\MasterDataImport;
use App\Exports\MasterData\MasterMoldExport;
use Carbon\Carbon;
use Auth;
class MasterMoldController extends Controller
{

	public function __construct(
		MasterMoldLibraries $MasterMoldLibraries
        ,MasterDataImport $masterDataImport
        ,MasterMoldExport  $MasterMoldExport

	){
		$this->middleware('auth');
		$this->mold = $MasterMoldLibraries;
        $this->import    = $masterDataImport;
        $this->export    = $MasterMoldExport;
	}
    
    public function index(Request $request)
    {	

    	$mold 	= $this->mold->get_all_list_mold();
    	$molds 	= $mold;
		$role =  Auth::user()->role()->pluck('role')->toBase()->toArray();
    	return view('master_data.mold.index', 
    	[
			'mold'  => $mold,
			'molds' => $molds,
            'request'    => $request,
			'role' => $role
    	]);
		
    }

    public function filter(Request $request)
    {
		$name    = $request->Name;
		$symbols = $request->Symbols;
		$from = $request->from;
		$to = $request->to;
		$molds   = $this->mold->get_all_list_mold();
		
		$mold    = $molds->when($name, function($q, $name) 
		{
			return $q->where('Name', $name);

		})->when($symbols, function($q, $symbols) 
		{
			return $q->where('Symbols', $symbols);
			
		})
		->when($from, function($query, $from )
		{
			return $query->where('Time_Created', '>=' , Carbon::create($from)->startOfDay()->toDateTimeString());
		})
        ->when($to, function($query, $to)
		{
			return $query->where('Time_Created', '<=', Carbon::create($to)->endOfDay()->toDateTimeString());
		});

    	return view('master_data.mold.index', 
    	[
			'mold'  => $mold,
			'molds' => $molds,
            'request' => $request
    	]);
    }

    public function show(Request $request)
    {
    	$mold = $this->mold->filter($request);
    	
    	if (!$request->ID) 
    	{
    		$mold = collect([]);
    		// dd('1');
    	}

    	return view('master_data.mold.add_or_update', 
    	[
    		'mold' => $mold->first(),
    		'show' => true
    	]);
    }

    
    public function add_or_update(Request $request)
    {
		$check = $this->mold->check_mold($request);
		$data  = $this->mold->add_or_update($request);
    	
    	return redirect()->route('masterData.mold')->with('success',$data->status);
    }

    public function destroy(Request $request)
    {
    	$data = $this->mold->destroy($request);

    	return redirect()->route('masterData.mold')->with('danger',$data);
    }

    public function import_file_excel(Request $request)
    {
        // get data in file excel
        // dd($request);
        $data  = $this->mold->import_file($request);
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
        $data = $this->mold->filter($request);
        $this->export->export($data,$request); 
        
    }
}
