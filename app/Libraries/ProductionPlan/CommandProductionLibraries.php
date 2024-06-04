<?php

namespace App\Libraries\ProductionPlan;

use Illuminate\Validation\Rule;
use App\Models\ProductionPlan\CommandProduction;
use Validator;
use Auth;

/**
 * 
 */
class CommandProductionLibraries
{
	public function get_all_list_plan()
	{
		$data = CommandProduction::where('IsDelete', 0)->with([
			'user_created',
			'user_updated'
		])->get();

		return $data;
	}

	public function filter($request)
	{
		$id      = $request->ID;
		$name    = $request->Name;
		$symbols = $request->Symbols;

		$data = CommandProduction::where('IsDelete', 0)
		->when($id, function($q, $id)
		{
			return $q->where('ID', $id);
		})
		->when($name, function($q, $name)
		{
			return $q->where('Name', $name);
		})
		->when($symbols, function($q, $symbols)
		{
			return $q->where('Symbols', $symbols);
		})
		->with([
			'detail'
		])
		->get();

		return $data;
	}

	public function check_plan($request)
	{
       
		$id = $request->ID;
		$message = [
			'unique'   => $request->Symbols.' '.__('Already Exists').'!',
		];

		$validation = Validator::make($request->all(), [
			'Symbols'	=> [
				'max:255',
				Rule::unique('Command_Production')->where(function($plan) use ($id) {
	        		$plan->where('ID', '!=', $id)->where('IsDelete',0);
				})
			],
		], $message)->validate();
      
		return $validation;
	}

	public function check_plan_auto_name($request)
	{
		$id = $request->ID;
		$message = [
			'unique'   => $request->Symbols.' '.__('Already Exists').'!',
		];

		$validation = Validator::make($request->all(), [
			'Name'	=> 'required|max:255'
		], $message)->validate();

		return $validation;
	}

	public function add_or_update($request)
	{
		$find = CommandProduction::where('IsDelete', 0)->where('ID', $request->ID)->first();

		if ($find) 
		{
			$find->Name         = $request->Name;
			$find->Symbols      = $request->Symbols;
			$find->Note         = $request->Note;
            $find->Month        = $request->Month;
            $find->Year         = $request->year;
			$find->User_Updated = Auth::user()->id;
			$find->save();

			$status = __('Update').' '.__('Success');
 		} else
		{
			$find = CommandProduction::create([
				'Name'         => $request->Name,
				'Symbols'      => $request->Symbols,
				'Note'         => $request->Note,
                'Month'        => $request->Month,
                'Year'         => $request->Year,
				'User_Created' => Auth::user()->id,
				'User_Updated' => Auth::user()->id
			]);

			$status = __('Create').' '.__('Success');
		}

		return (object)[
			'data'   => $find,
			'status' => $status
		];
	}

	public function add_or_update_auto_name($request)
	{
		$find = CommandProduction::where('IsDelete', 0)->where('ID', $request->ID)->first();
		$last = CommandProduction::where('Symbols', 'like', 'KH%')->orderBy('ID', 'desc')->first();
		

		if($request->Month && is_numeric($request->Month) && is_numeric($request->Year) && $request->Month <= 12 && $request->Month > 0 && $request->Year  && $request->Year >= 2022 && $request->Year < 10000)
		{
			
			$check = CommandProduction::where('IsDelete', 0)->where('Month', $request->Month)->where('Year', $request->Year)->first();
			if(!$check)
			{
				$check_name = true;
				if($request->Name)
				{
					if(strlen($request->Name) > 50)
					{
						$check_name = false;
					}
					else
					{
						$check_na = CommandProduction::where('IsDelete', 0)->where('Name', $request->Name)->first();
						if($check_na)
						{
							$check_name =  false;
						}
					}
				}
				// dd($check_name);
				if($check_name)
				{
					if ($last) 
					{
						$symbols = $last->Symbols;
						$count   = intval(explode('KH', $symbols)[1]) + 1;
						$string  = str_pad(strval($count), 7, '0', STR_PAD_LEFT);
						$symbols = 'KH'.$string;
					} else
					{
						$count = str_pad(1, 7, '0', STR_PAD_LEFT);;
						$symbols = 'KH'.$count;
					}
			
					if ($find) 
					{
						$find->Name         = $request->Name;
						$find->Symbols      = $symbols;
						$find->Month        = intval($request->Month);
						$find->Year         = intval($request->year);
						$find->Note         = $request->Note;
						$find->User_Updated = Auth::user()->id;
						$find->save();
			
						$status = __('Update').' '.__('Success');
					} else
					{
						$find = CommandProduction::create([
							'Name'         => $request->Name ? $request->Name : 'Kế Hoạch Tháng '.$request->Month.' Năm '.$request->Year ,
							'Symbols'      => $symbols,
							'Note'         => $request->Note,
							'Month'        => intval($request->Month),
							'Year'         => intval($request->Year),
							'User_Created' => Auth::user()->id,
							'User_Updated' => Auth::user()->id
						]);
			
						$status = __('Create').' '.__('Success');
					}
				}
				else
				{
					$status = __('Create').' '.__('Fail');
				}
			}
			else
			{
				$status = __('Create').' '.__('Fail');
			}
			
			
		}
		else
		{
			$status = __('Create').' '.__('Fail');
		}
		

		return (object)[
			'data'   => $find,
			'status' => $status
		];
	}

	public function destroy($request)
	{
		$find = CommandProduction::where('IsDelete', 0)->where('ID', $request->ID)->first();
		// $find->detail()->update([
		// 	'User_Updated' => Auth::user()->id,
		// 	'IsDelete'     => 1
		// ]);
		$find->update([
			'User_Updated' => Auth::user()->id,
			'IsDelete'     => 1
		]);

		return __('Delete').' '.__('Success');
	}
}