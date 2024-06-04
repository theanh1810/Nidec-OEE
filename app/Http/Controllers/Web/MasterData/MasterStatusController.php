<?php

namespace App\Http\Controllers\Web\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\MasterData\MasterStatusLibraries;

class MasterStatusController extends Controller
{
    private $status;

	public function __construct(
		MasterStatusLibraries $masterStatusLibraries
	) {
		$this->middleware('auth');
		$this->status = $masterStatusLibraries;
	}

	public function index()
	{
		$status 	= $this->status->get_all_list_status();

		$status1 	= $status;
		return view(
			'master_data.status.index',
			[
				'status'  => $status,
				'status1' => $status1
			]
		);
	}

	public function filter(Request $request)
	{
		$id		= $request->ID;
		$name    = $request->Name;
		$symbols    = $request->Symbols;
		$status   = $this->status->get_all_list_status();

		$status1    = $status->when($id,function ($q,$id){
			return $q->where('ID',$id);
		})
		->when($name, function ($q, $name) {
			return $q->where('Name', $name);
		})
		->when($symbols, function ($q, $symbols) {
			return $q->where('Symbols', $symbols);
		})
		;

		return view(
			'master_data.status.index',
			[
				'status'  => $status,
				'status1' => $status1
			]
		);
	}


	public function show(Request $request)
	{
		// dd($request);
		$status = $this->status->filter($request);
		// dd($status);
		if (!$request->ID) {
			$status = collect([]);
			// dd('1');
		}
		// dd($status);

		return view(
			'master_data.status.add_or_update',
			[
				'status' => $status->first(),
				'show' => true
			]
		);
	}

	public function add_or_update(Request $request)
	{
		// dd($request);
		$check = $this->status->check_status($request);
		// dd($request);
		$data  = $this->status->add_or_update($request);

		return redirect()->route('masterData.status')->with('success', $data->status);
	}

	public function destroy(Request $request)
	{
		$data = $this->status->destroy($request);

		return redirect()->route('masterData.status')->with('danger', $data);
	}
}
