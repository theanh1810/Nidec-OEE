<?php
namespace App\Libraries\MasterData;

use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterAccessories;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use Auth;

class MasterAccessoriesLibraries
{
	public function get_all_list_accessories()
	{
		return MasterAccessories::where('IsDelete', 0)
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

		$data 	 = MasterAccessories::when($id, function($query, $id)
		{
			return $query->where('ID', $id);
		})->when($name, function($query, $name)
		{
			return $query->where('Name', $name);
		})->when($symbols, function($query, $symbols)
		{
			return $query->where('Symbols', $symbols);
		})->where('IsDelete', 0)->get();

		return $data;
	}

	public function check_accessories($request)
	{
		$id = $request->ID;
		$message = [
			'unique'   => $request->Symbols.' '.__('Already Exists').'!',
		];

		Validator::make($request->all(), 
		[
	        'Symbols' => ['required','max:255',
	        Rule::unique('Master_Accessories')->where(function($q) use ($id) 
	        {
	        	$q->where('ID', '!=', $id)->where('IsDelete',0);
	        })]
	    ], $message)->validate();
	}

	public function add_or_update($request)
	{
		$id = $request->ID;
		$user_created = Auth::user()->id;
		$user_updated = Auth::user()->id;
		$file         = $request->file('Image_Url');
		$file_old     = $request->image;
		if($file == null)
		{
			 $ten_file = $file_old ;
		}
		else
		{
			$thu_muc  = 'uploads\image\Accessories';
			$ten_file = $file->getClientOriginalName();
			$file->move($thu_muc,$ten_file);

			if(file_exists($thu_muc.'/'.$ten_file))
			{
				$url_file = $thu_muc.'/'.$ten_file;
				$dataView['url_file'] = $url_file;
			}else
			{
				echo 'Lỗi không di chuyển được file';
			}
		}
		// dd($request);
		if (isset($id) && $id != '') 
		{
			if (!Auth::user()->checkRole('update_master') && Auth::user()->level != 9999) 
			{
				abort(401);	
			}

			$accessories = MasterAccessories::where('ID', $id)->update([
				'Name' 			=> $request->Name,
				'Symbols'		=> $request->Symbols,
				'Symbols_Input'			=> $request->Symbols_Input,
				'Height_Use'			=> $request->Height_Use,
				'Height_Not_Use'=>$request->Height_Not_Use,
				'Image'=>$ten_file,
				'Note'			=> $request->Note,
				'User_Updated'	=> $user_updated
			]);

			return (object)[
				'status' => __('Update').' '.__('Success'),
				'data'	 => $accessories
			];
		} else
		{
			if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) 
			{
				abort(401);	
			}

			$accessories = MasterAccessories::create([
				'Name' 			=> $request->Name,
				'Symbols_Input'	=> $request->Symbols_Input,
				'Height_Use'	=> $request->Height_Use,
				'Symbols'		=> $request->Symbols,
				'Note'			=> $request->Note,
				'Height_Not_Use'=>$request->Height_Not_Use,
				'Image'=>$ten_file,
				'User_Created'	=> $user_created,
				'User_Updated'	=> $user_updated,
				'IsDelete'		=> 0
			]);

			return (object)[
				'status' => __('Create').' '.__('Success'),
				'data'	 => $accessories
			];
		}
	}

	public function destroy($request)
	{
		MasterAccessories::where('ID', $request->ID)->update([
			'IsDelete' 		=> 1,
			'User_Updated'	=> Auth::user()->id
		]);

		return __('Delete').' '.__('Success');
	}
	
}