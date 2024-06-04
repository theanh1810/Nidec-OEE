<?php

namespace App\Libraries\MasterData;

use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterMold;
use App\Models\MasterData\MasterMoldAccessories;
use App\Models\MasterData\History\MoldHistory;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use Auth;

class MasterMoldLibraries
{
	public function get_all_list_mold()
	{
		return MasterMold::where('IsDelete', 0)
			->with([
				'user_created',
				'user_updated'
			])
			->get();
	}

	public function filter($request)
	{
		$id 	 = $request->ID;
		$name 	 = $request->Name;
		$symbols = $request->Symbols;

		$data 	 = MasterMold::when($id, function ($query, $id) {
			return $query->where('ID', $id);
		})->when($name, function ($query, $name) {
			return $query->where('Name', $name);
		})->when($symbols, function ($query, $symbols) {
			return $query->where('Symbols', $symbols);
		})->where('IsDelete', 0)->get();

		return $data;
	}

	public function check_mold($request)
	{
		$id = $request->ID;
		$message = [
			'unique'   => $request->Symbols . ' ' . __('Already Exists') . '!',
		];

		Validator::make(
			$request->all(),
			[
				'Symbols' => [
					'required', 'max:255',
					Rule::unique('Master_Mold')->where(function ($q) use ($id) {
						$q->where('ID', '!=', $id)->where('IsDelete', 0);
					})
				]
			],
			$message
		)->validate();
	}

	public function add_or_update($request)
	{
		$id = $request->ID;
		$user_created = Auth::user()->id;
		$user_updated = Auth::user()->id;
		if (isset($id) && $id != '') {
			if (!Auth::user()->checkRole('update_master') && Auth::user()->level != 9999) {
				abort(401);
			}
			$mold_check = MasterMold::where('ID', $id)->first();
			$mold = MasterMold::where('ID', $id)->update([
				'Name' 			=> $request->Name,
				'Symbols'		=> $request->Symbols,
				'CAV_Max'			=> $request->CAV_Max,
				'User_Updated'	=> $user_updated
			]);
			return (object)[
				'status' => __('Update') . ' ' . __('Success'),
				'data'	 => $mold
			];
		} else {
			if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) {
				abort(401);
			}

			$mold = MasterMold::create([
				'Name' 			=> $request->Name,
				'Symbols'		=> $request->Symbols,
				'CAV_Max'	    => $request->CAV_Max,
				'User_Created'	=> $user_created,
				'User_Updated'	=> $user_updated,
				'IsDelete'		=> 0
			]);

			return (object)[
				'status' => __('Create') . ' ' . __('Success'),
				'data'	 => $mold
			];
		}
	}

	public function destroy($request)
	{
		$user_created = Auth::user()->id;
		$user_updated = Auth::user()->id;
		$mold_check = MasterMold::where('ID', $request->ID)->first();
		MasterMold::where('ID', $request->ID)->update([
			'IsDelete' 		=> 1,
			'User_Updated'	=> Auth::user()->id
		]);

		return __('Delete') . ' ' . __('Success');
	}

	public function extend($request)
	{
		MasterMold::where('ID', $request->ID)->update([
			'Status' 		=> 1,
			'User_Updated'	=> Auth::user()->id
		]);

		return __('Delete') . ' ' . __('Success');
	}
	public function add_accessories($request)
	{
		// dd($request);
		MasterMoldAccessories::where('Mold_ID', $request->Mold_ID)
			->update(['IsDelete' => 1]);
		$arr1 = [];
		foreach ($request->Accessories_ID as $key => $value) {
			$arr = [
				'Mold_ID' => $request->Mold_ID,
				'Accessories_ID' => $value,
				'Quantity' => $request->Quantity[$key]
			];
			// array_push($arr1,$arr);
			MasterMoldAccessories::create($arr);
		}
		// dd($arr1);
		return __('Update') . ' ' . __('Success');
	}

	public function list_accessories($request)
	{
		return MasterMoldAccessories::where('Mold_ID', $request->ID)
			->where('IsDelete', 0)
			->get();
	}

	public function get_list_with_id($request)
	{

		return MasterMold::where('ID', $request->ID)
			->where('IsDelete', 0)
			->first();
	}

	public function get_all_list_accesory_in_mold($request)
	{
		return MasterMoldAccessories::where('Mold_ID', $request->Mold_ID)
			->where('IsDelete', 0)
			->get();
	}

	public function get_list_accessories_in_mold($request)
	{
		return MasterMoldAccessories::where('Mold_ID', $request->Mold)
			->where('IsDelete', 0)
			->with([
				'accessories',
			])
			->get();
	}


	private function read_file($request)
	{
		try {
			$file     = request()->file('fileImport');
			$name     = $file->getClientOriginalName();
			$arr      = explode('.', $name);
			$fileName = strtolower(end($arr));
			// dd($file, $name, $arr, $fileName);
			if ($fileName != 'xlsx' && $fileName != 'xls') {
				return redirect()->back();
			} else if ($fileName == 'xls') {
				$reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			} else if ($fileName == 'xlsx') {
				$reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}
			try {
				$spreadsheet = $reader->load($file);
				$data        = $spreadsheet->getActiveSheet()->toArray();

				return $data;
			} catch (\Exception $e) {
				return ['danger' => __('Select The Standard ".xlsx" Or ".xls" File')];
			}
		} catch (\Exception $e) {
			return ['danger' => __('Error Something')];
		}
	}

	public function import_file($request)
	{
		$data = $this->read_file($request);
		$im = [];
		$err = [];
		$user_created = Auth::user()->id;
		$user_updated = Auth::user()->id;
		// dd($data);
		foreach ($data as $key => $value) {
			if ($key > 1) {
				if ($value[2] != '') {
					$find = MasterMold::where('IsDelete', 0)->where('Name', $value[2])->first();
					if ($find) {
						$mold = MasterMold::where('ID', $find->ID)->update([
							'Name' 			=> $value[2],
							'Symbols'		=> $value[2],
							'CAV_Max'	    => $value[3],
							'User_Updated'	=> $user_updated
						]);
					} else {
						$mold = MasterMold::create([
							'Name' 			=> $value[2],
							'Symbols'		=> $value[2],
							'CAV_Max'	    => $value[3],
							'User_Created'	=> $user_created,
							'User_Updated'	=> $user_updated,
							'IsDelete'		=> 0
						]);
					}
				}
			}
		}
		return $err;
	}
}
