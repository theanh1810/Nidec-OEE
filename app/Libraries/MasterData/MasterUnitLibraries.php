<?php

namespace App\Libraries\MasterData;

use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterUnit;
use App\Models\MasterData\History\UnitHistory;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use Auth;

class MasterUnitLibraries
{
	public function get_all_list_unit()
	{
		return MasterUnit::where('IsDelete', 0)
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

		$data 	 = MasterUnit::when($id, function ($query, $id) {
			return $query->where('ID', $id);
		})->when($name, function ($query, $name) {
			return $query->where('Name', $name);
		})->when($symbols, function ($query, $symbols) {
			return $query->where('Symbols', $symbols);
		})->where('IsDelete', 0)->get();

		return $data;
	}

	public function check_unit($request)
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
					Rule::unique('Master_Unit')->where(function ($q) use ($id) {
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
			$unit_check = MasterUnit::where('ID', $id)->first();
			$unit = MasterUnit::where('ID', $id)->update([
				'Name' 			=> $request->Name,
				'Symbols'		=> $request->Symbols,
				'Type'			=> $request->Type,
				'Note'			=> $request->Note,
				'User_Updated'	=> $user_updated
			]);
			UnitHistory::create([
				'Unit_ID'		=> $unit_check->ID,
				'Name' 			=> $unit_check->Name,
				'Symbols'		=> $unit_check->Symbols,
				'Type'			=> $unit_check->Type,
				'Note'			=> $unit_check->Note,
				'Status'		=> 1,
				'User_Created'	=> $user_created,
				'User_Updated'	=> $user_updated
			]);
			return (object)[
				'status' => __('Update') . ' ' . __('Success'),
				'data'	 => $unit
			];
		} else {
			if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) {
				abort(401);
			}

			$unit = MasterUnit::create([
				'Name' 			=> $request->Name,
				'Type'			=> $request->Type,
				'Symbols'		=> $request->Symbols,
				'Note'			=> $request->Note,
				'User_Created'	=> $user_created,
				'User_Updated'	=> $user_updated,
				'IsDelete'		=> 0
			]);

			return (object)[
				'status' => __('Create') . ' ' . __('Success'),
				'data'	 => $unit
			];
		}
	}

	public function destroy($request)
	{
		$user_created = Auth::user()->id;
		$user_updated = Auth::user()->id;
		$unit = MasterUnit::where('ID', $request->ID)->first();

		MasterUnit::where('ID', $request->ID)->update([
			'IsDelete' 		=> 1,
			'User_Updated'	=> Auth::user()->id
		]);

		return __('Delete') . ' ' . __('Success');
	}

	public function return($request)
	{
		$user_created = Auth::user()->id;
		$user_updated = Auth::user()->id;
		$unit_his = UnitHistory::where('ID', $request->ID)->first();
		UnitHistory::where('ID', $request->ID)->update([
			'Status' => 3,
			'User_Updated'	=> $user_updated,
		]);
		$unit = MasterUnit::where('ID', $unit_his->Unit_ID)->update([
			'Name' 			=> $unit_his->Name,
			'Type'			=> $unit_his->Type,
			'Symbols'		=> $unit_his->Symbols,
			'Note'			=> $unit_his->Note,
			'User_Updated'	=> $user_updated,
		]);
	}
}
