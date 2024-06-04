<?php

namespace App\Http\Controllers\Web\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\MasterData\MasterHolidayLibraries;

class MasterHolidayController extends Controller
{
    private $Holiday;

	public function __construct(
		MasterHolidayLibraries $masterHolidayLibraries
	){
		$this->middleware('auth');
		$this->Holiday = $masterHolidayLibraries;
	}
    
    public function index(Request $request)
    {
    	$Holiday 	= $this->Holiday->get_all_list_Holiday();
    	$Holidays 	= $Holiday;
    	return view('master_data.holiday.index', 
    	[
			'Holiday'  => $Holiday,
			'Holidays' => $Holidays,
            'request'    => $request
    	]);
    }

    public function filter(Request $request)
    {
		$name    = $request->Name;
		$symbols = $request->Symbols;
		$Holidays = $this->Holiday->get_all_list_Holiday();
		
		$Holiday    = $Holidays->when($name, function($q, $name) 
		{
			return $q->where('Name', $name);

		})->when($symbols, function($q, $symbols) 
		{
			return $q->where('Symbols', $symbols);
			
		});

    	return view('master_data.holiday.index', 
    	[
			'Holiday'  => $Holiday,
			'Holidays' => $Holidays,
            'request' => $request
    	]);
    }

    public function show(Request $request)
    {
    	$Holiday = $this->Holiday->filter($request);
    	
    	if (!$request->ID) 
    	{
    		$Holiday = collect([]);
    		// dd('1');
    	}

    	return view('master_data.holiday.add_or_update', 
    	[
    		'Holiday' => $Holiday->first(),
    		'show' => true
    	]);
    }

    public function add_or_update(Request $request)
    {
		// $check = $this->Holiday->check_Holiday($request);
		$data  = $this->Holiday->add_or_update($request);
    	
    	return redirect()->route('masterData.holiday')->with('success',$data->status);
    }

    public function destroy(Request $request)
    {
    	$data = $this->Holiday->destroy($request);

    	return redirect()->route('masterData.holiday')->with('danger',$data);
    }

    public function import_file_excel(Request $request)
    {
        // get data in file excel
        // dd($request);
        $data  = $this->import->master_Holiday($request);
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
        $data = $this->Holiday->filter($request);
        $this->export->export($data,$request); 
        
    }
}
