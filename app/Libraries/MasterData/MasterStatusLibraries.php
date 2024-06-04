<?php

namespace App\Libraries\MasterData;

use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterStatus;
use Validator;
use Illuminate\Support\Facades\Auth;
use DB;

class MasterStatusLibraries
{
	public function get_all_list_status()
	{
		return MasterStatus::where('IsDelete', 0)
			->with([
				'user_created',
				'user_updated',
			])
			->get();
	}

	public function filter($request)
	{
		// dd($request);
		$id 	 = $request->ID;
		$name 	 = $request->Name;


		$data 	 = MasterStatus::when($id, function ($query, $id) {
			return $query->where('ID', $id);
		})->where('IsDelete', 0)->get();

		return $data;
	}

	public function check_status($request)
	{
		// dd($request);
		$id = $request->Old_ID;

		// dd($request->all());	
		$message = [
			'unique.ID'   => $request->ID . ' ' . __('Already Exists') . '!',
			'unique.Name'   => $request->Name . ' ' . __('Already Exists') . '!',
		];

		Validator::make(
			$request->all(),
			[
				'Name' => [
					'required', 'max:255',
					Rule::unique('Master_Status')->where(function ($q) use ($id) {
						// dd('1');
						$q->where('ID', '!=', $id)->where('IsDelete', 0);
					}),
				],
				'ID' => [
					'required',
					Rule::unique('Master_Status')->where(function ($q) use ($id) {
						$q->where('ID', '!=', $id)->where('IsDelete', 0);
					}),
				],

			],
			$message
		)->validate();
		// dd($message);

	}

	public function add_or_update($request)
	{
		$id = $request->Old_ID;
		$user_created = Auth::user()->id;
		$user_updated = Auth::user()->id;
		if (isset($id) && $id != '') {
			if (!Auth::user()->checkRole('update_master') && Auth::user()->level != 9999) {
				abort(401);
			}

			if ($request->Old_ID != $request->ID) {
				$status = MasterStatus::where('ID', $id)->update([
					'ID' 			=> $request->ID,
					// 'Old_ID' 		=> $request->ID,
					'Name' 			=> $request->Name,
					'Type' 			=> $request->Type,
					'Note' 			=> $request->Note,
					'User_Updated' 	=> $user_updated
				]);
			} else {
				$status = MasterStatus::where('ID', $id)->update([
					'Name' 			=> $request->Name,
					'Type' 		=> $request->Type,
					'Note' 			=> $request->Note,
					'User_Updated' 	=> $user_updated
				]);
			}


			return (object)[
				'status' => __('Update') . ' ' . __('Success'),
				'data'     => $status
			];
		} else {
			if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) {
				abort(401);
			}

			$status = MasterStatus::create([
				'ID'				=> $request->ID,
				// 'Old_ID'			=> $request->ID,
				'Name'				=> $request->Name,
				'Type'				=> $request->Type,
				'Note' 				=> $request->Note,
				'User_Created'    	=> $user_created,
				'User_Updated'    	=> $user_updated,
				'IsDelete'        	=> 0
			]);

			return (object)[
				'status' => __('Create') . ' ' . __('Success'),
				'data'     => $status
			];
		}
	}

	public function destroy($request)
	{
		MasterStatus::where('ID', $request->ID)->update([
			'IsDelete' 		=> 1,
		]);

		return __('Delete') . ' ' . __('Success');
	}
}
