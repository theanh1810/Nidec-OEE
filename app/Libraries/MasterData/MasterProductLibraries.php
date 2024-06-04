<?php

namespace App\Libraries\MasterData;

use App\Models\MasterData\History\ProductHistory;
use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterProduct;
use App\Models\MasterData\MasterMaterials;
use App\Models\MasterData\MasterUnit;
use App\Models\MasterData\MasterBOM;
use App\Models\MasterData\MasterMold;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Support\Facades\Auth;
use App\Models\History\HistoriesImportFile;
use Carbon\Carbon;

class MasterProductLibraries
{
	public function get_all_list_product()
	{
		return MasterProduct::where('IsDelete', 0)
			->with([
				'user_created',
				'user_updated',
				'unit',
				'materials'
			])
			->get();
	}

	public function filter($request)
	{

		$id 	 = $request->ID;
		$name 	 = $request->Name;
		$symbols = $request->Symbols;

		$data 	 = MasterProduct::when($id, function ($query, $id) {
			return $query->where('ID', $id);
		})->when($name, function ($query, $name) {
			return $query->where('Name', $name);
		})->when($symbols, function ($query, $symbols) {
			return $query->where('Symbols', $symbols);
		})->where('IsDelete', 0)->get();

		return $data;
	}
	public function filter_one($request)
	{

		$id 	 = $request->ID;
		$name 	 = $request->Name;
		$symbols = $request->Symbols;

		$data 	 = MasterProduct::where('ID', $request->ID)->where('IsDelete', 0)->first();

		return $data;
	}
	public function check_product($request)
	{
		$id = $request->ID;
		$message = [
			'unique'   => $request->Symbols . ' ' . __('Already Exists') . '!',
		];
		Validator::make(
			$request->all(),
			[
				'Symbols' => [
					'required', 'max:20',
					Rule::unique('Master_Product')->where(function ($q) use ($id) {
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
			$product_find = MasterProduct::where('IsDelete', 0)->where('ID', $id)->first();
			$product = MasterProduct::where('ID', $id)->update([
				'Name'         => $request->Name,
				'Symbols'      => $request->Symbols,
				'Unit_ID'      => $request->Unit_ID,
				'Cycle_Time'   => $request->Cycle_Time,
				'CAV'		   => $request->CAV,
				'Materials_ID' => $request->Materials_ID,
				'Note'		   => $request->Note,
				'Quantity'	   => $request->Quantity,
				'User_Updated' => $user_updated
			]);

			return (object)[
				'status' => __('Update') . ' ' . __('Success'),
				'data'	 => $product
			];
		} else {
			if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) {
				abort(401);
			}

			$product = MasterProduct::create([
				'Name' 			=> $request->Name,
				'Symbols'		=> $request->Symbols,
				'Unit_ID'		=> $request->Unit_ID,
				'Materials_ID'  => $request->Materials_ID,
				'Cycle_Time'    => $request->Cycle_Time,
				'CAV'		    => $request->CAV,
				'Note'			=> $request->Note,
				'Quantity'	   	=> $request->Quantity,
				'User_Created'	=> $user_created,
				'User_Updated'	=> $user_updated,
				'IsDelete'		=> 0
			]);

			return (object)[
				'status' => __('Create') . ' ' . __('Success'),
				'data'	 => $product
			];
		}
	}

	public function destroy($request)
	{
		$user_created = Auth::user()->id;
		$user_updated = Auth::user()->id;
		$find = MasterProduct::where('ID', $request->ID)->first();
		$find->update([
			'IsDelete' 		=> 1,
			'User_Updated'	=> Auth::user()->id
		]);

		return __('Delete') . ' ' . __('Success');
	}

	public function return($request)
	{
		// dd($request);
		$user_updated = Auth::user()->id;
		$product_his = ProductHistory::where('ID', $request->ID)->first();
		ProductHistory::where('ID', $request->ID)->update([
			'Status' => 3,
			'User_Updated'	=> $user_updated,
		]);
		$product = MasterProduct::where('ID', $product_his->Product_ID)->update([
			'Name' 			=> $product_his->Name,
			'Symbols'		=> $product_his->Symbols,
			'Unit_ID'		=> $product_his->Unit_ID,
			'Materials_ID'  => $product_his->Materials_ID,
			'Cycle_Time'    => $product_his->Cycle_Time,
			'CAV'		    => $product_his->CAV,
			'Note'			=> $product_his->Note,
			'Quantity'	   	=> $product_his->Quantity,
			'User_Updated'	=> $user_updated,
		]);
	}

	private function read_file($request)
	{
		try {
			$user_created = Auth::user()->id;
			$user_updated = Auth::user()->id;
			$cvt     = Carbon::now()->isoFormat('YYMMDDhhmmss');
			$file     = request()->file('fileImport');
			$name     = $file->getClientOriginalName();
			$arr      = explode('.', $name);
			$fileName = strtolower(end($arr));

			if ($fileName != 'xlsx' && $fileName != 'xls') {
				return redirect()->back();
			} else if ($fileName == 'xls') {
				$reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			} else if ($fileName == 'xlsx') {
				$reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}
			try {
                $reader->setReadDataOnly(true);
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
		$cvt     = Carbon::now()->isoFormat('YYMMDDhhmmss');
		$file     = request()->file('fileImport');
		$name     = $file->getClientOriginalName();
		$arr      = explode('.', $name);
		$fileName = strtolower(end($arr));
		$thu_muc_histories  = 'uploads\Histories';
		$ten_file_histories = 'BOM-' . $cvt . '.' . $fileName;
		// $file->move($thu_muc_histories,$ten_file_histories);
		HistoriesImportFile::create([
			'Table_Name'    => 'Master_BOM',
			'Folder'        => $thu_muc_histories,
			'File'          => $ten_file_histories,
			'User_Created'	=> $user_created,
			'User_Updated'	=> $user_updated,
			'IsDelete'		=> 0
		]);
        $listMold = MasterMold::where('IsDelete', 0)->get();
        $listMaterials = MasterMaterials::where('IsDelete', 0)->get();
        $listProduct = MasterProduct::where('IsDelete', 0)->get();
        $listBOM = MasterBOM::where('IsDelete', 0)->get();
		foreach ($data as $key => $value) {
			if ($key > 2) {
				if ($value[2] != '' && strlen($value[2]) <= 20 && $value[1] != '' && strlen($value[1]) <= 20
                    && is_numeric($value[5]) && is_numeric($value[6]) && is_numeric($value[7]) && $value[5] > 0
                    && $value[6] > 0 && $value[7] > 0 && $value[3] != '' && strlen($value[3]) <= 20) {
					$find_mold = $listMold->where('Symbols', $value[1])->first();
					$find_product = $listProduct->where('Symbols', $value[2])->first();
					$find_materials = $listMaterials->where('Name', $value[4])->where('Symbols', $value[3])->first();

                    if (!$find_materials) {
						$find_materials = MasterMaterials::create([
							'Name' 			=> $value[4],
							'Symbols'		=> $value[3],
							'User_Created'	=> $user_created,
							'User_Updated'	=> $user_updated,
							'IsDelete'		=> 0
						]);
                        $listMaterials->push($find_materials);
					}

					if (!$find_product) {
						$find_product = MasterProduct::create([
							'Name' 			=> $value[2],
							'Symbols'		=> $value[2],
                            'Materials_ID'  => $find_materials->ID,
							'Cycle_Time'    => $value[5],
							'CAV'		    => $value[7],
							'User_Created'	=> $user_created,
							'User_Updated'	=> $user_updated,
							'IsDelete'		=> 0
						]);
                        $listProduct->push($find_product);
					}

					if (!$find_mold) {
						$find_mold = MasterMold::create([
							'Name' 			=> $value[1],
							'Symbols'		=> $value[1],
							'User_Created'	=> $user_created,
							'User_Updated'	=> $user_updated,
							'IsDelete'		=> 0
						]);

                        $listMold->push($find_mold);
					}

                    $findBOM = $listBOM->where('Product_BOM_ID', $find_product->ID)
                            ->where('Materials_ID', $find_materials->ID)->where('Mold_ID', $find_mold->ID)->first();
					if (!$findBOM) {
						MasterBOM::create([
							'Product_BOM_ID' 	=> $find_product->ID,
							'Materials_ID' 		=> $find_materials->ID,
							'Quantity_Material' => $value[6],
							'Mold_ID' 			=> $find_mold->ID,
							'Cavity' 			=> $value[7],
							'Cycle_Time'		=> $value[5],
							'User_Created'		=> $user_created,
							'User_Updated'		=> $user_updated,
							'IsDelete'			=> 0
						]);
                        $listBOM->push($findBOM);
					} else {
						if ($findBOM->Quantity_Material != $value[6] || $find_mold->Cavity != $value[7] || $find_mold->Cycle_Time != $value[5]) {
							MasterBOM::where('ID', $findBOM->ID)->update([
								'Quantity_Material' => $value[6],
								'Cavity' 			=> $value[7],
								'Cycle_Time'		=> $value[5],
								'User_Updated'	=> $user_updated,
							]);
						}
					}
				} else {
					if ($value[2] != '') {
						$er = __('Create') . " " . __('Product') . " : " . $value[2] . ' ' . __('False');
						array_push($err, $er);
					}
				}
			}
		}

		return $err;
	}
	public function get_id_bom_and_materials($request)
	{
		return MasterBOM::where('IsDelete', 0)->where('Product_BOM_ID', $request->ID)->with('materials', 'mold', 'user_created', 'user_updated',)->get();
	}
	public function add_product_and_materials_to_bom($request)
	{

		$user_created = Auth::user()->id;
		$user_updated = Auth::user()->id;
		$product = $request->product;
		$materials = $request->materials;
		$mold = $request->mold;
		$Quantity_product = $request->Quantity_product;
		$Quantity_materials = $request->Quantity_materials;
		$Cavity = $request->Cavity;
		$Cycle_Time = $request->Cycle_Time;
		$pro_his = null;
		if (!$request->Product_BOM_ID) {
			if (strlen($request->Symbols) <= 20) {
				$find_product = MasterProduct::where('IsDelete', 0)->where('Symbols', $request->Symbols)->first();
				if (!$find_product) {
					$product = MasterProduct::create([
						'Name' 			=> $request->Symbols,
						'Symbols'		=> $request->Symbols,
						'Note'			=> $request->Note,
						'User_Created'	=> $user_created,
						'User_Updated'	=> $user_updated,
						'IsDelete'		=> 0
					]);
					$request->Product_BOM_ID = $product->ID;
				} else {
					return (object)[
						'status' => __('Create') . ' ' . __('False'),
					];
				}
			} else {
				return (object)[
					'status' => __('Create') . ' ' . __('False'),
				];
			}
		} else {
			$product_update = MasterProduct::where('ID', $request->Product_BOM_ID)->update([
				'Name' 			=> $request->Symbols,
				'Symbols'		=> $request->Symbols,
				'Note'			=> $request->Note,
				'User_Updated'	=> $user_updated,
			]);
		}
		// if($product)
		// {
		// 	$find = MasterBOM::where('IsDelete',0)->where('Product_BOM_ID',$request->Product_BOM_ID)->where('Product_ID','>',0)->update([
		// 		'User_Updated'	=> $user_updated,
		// 		'IsDelete'		=> 1
		// 	]);
		// 	foreach($product as $key => $pro)
		// 	{
		// 		$find = MasterBOM::where('IsDelete',0)->where('Product_BOM_ID',$request->Product_BOM_ID)->where('Product_ID',$pro)->first();
		// 		if($find)
		// 		{
		// 			if($find->Quantity_Product != $Quantity_product[$key])
		// 			{
		// 				MasterBOM::where('ID',$find->ID)
		// 				->update([
		// 					'Quantity_Product' => $Quantity_product[$key],
		// 				]);
		// 			}
		// 		}
		// 		else
		// 		{
		// 			MasterBOM::create([
		// 				'Product_BOM_ID'=>$request->Product_BOM_ID,
		// 				'Product_ID'=>$pro,
		// 				'Quantity_Product'=>$Quantity_product[$key],
		// 				'User_Created'	=> $user_created,
		// 				'User_Updated'	=> $user_updated,
		// 				'IsDelete'		=> 0
		// 			]);
		// 		}
		// 	}
		// }
		// else
		// {
		// 	$find = MasterBOM::where('IsDelete',0)->where('Product_BOM_ID',$request->Product_BOM_ID)->where('Product_ID','>',0)->update([
		// 		'User_Updated'	=> $user_updated,
		// 		'IsDelete'		=> 1
		// 	]);
		// }
		if ($materials) {
			$arr_mater = [];
			foreach ($materials as $key => $mater) {
				if (strlen($mater) <= 20 && is_numeric($Quantity_materials[$key]) && $Quantity_materials[$key] > 0) {
					$find_mater = MasterMaterials::where('IsDelete', 0)->where('Symbols', $mater)->first();
					if (!$find_mater) {
						$find_mater = MasterMaterials::create([
							'Name'             => $mater,
							'Symbols'          => $mater,
							'User_Created'     => $user_created,
							'User_Updated'     => $user_updated,
							'IsDelete'         => 0
						]);
						$find_mater = MasterMaterials::where('IsDelete', 0)->where('Symbols', $mater)->first();
					}
					$find_bom_mater = MasterBOM::where('IsDelete', 0)->where('Product_BOM_ID', $request->Product_BOM_ID)->where('Materials_ID', $find_mater->ID)->first();
					if ($find_bom_mater) {
						if ($find_bom_mater->Quantity_Material != $Quantity_materials[$key]) {
							MasterBOM::where('ID', $find_bom_mater->ID)->update([
								'Quantity_Material' => $Quantity_materials[$key],
								'User_Updated'	=> $user_updated,
							]);
						}
					} else {

						MasterBOM::create([
							'Product_BOM_ID' => $request->Product_BOM_ID,
							'Materials_ID'  => $find_mater->ID,
							'Quantity_Material' => $Quantity_materials[$key],
							'User_Created'	=> $user_created,
							'User_Updated'	=> $user_updated,
							'IsDelete'		=> 0
						]);
					}
					array_push($arr_mater, $find_mater->ID);
				} else {
					return (object)[
						'status' => __('Create') . ' ' . __('Materials') . ' : ' . $mater . ' ' . __('False'),
					];
				}
			}
			MasterBOM::where('IsDelete', 0)->where('Product_BOM_ID', $request->Product_BOM_ID)->where('Materials_ID', '>', 0)->whereNotIn('Materials_ID', $arr_mater)->update([
				'User_Updated'	=> $user_updated,
				'IsDelete'		=> 1
			]);
		} else {
			$find = MasterBOM::where('IsDelete', 0)->where('Product_BOM_ID', $request->Product_BOM_ID)->where('Product_ID', '>', 0)->update([
				'User_Updated'	=> $user_updated,
				'IsDelete'		=> 1
			]);
		}
		if ($mold) {
			$arr_mold = [];
			foreach ($mold as $key => $mol) {
				if (strlen($mol) <= 20 && is_numeric($Cavity[$key]) && is_numeric($Cycle_Time[$key]) && $Cavity[$key] > 0 && $Cycle_Time[$key] > 0) {
					$find_mold = MasterMold::where('IsDelete', 0)->where('Symbols', $mol)->first();

					if (!$find_mold) {
						$mold_cre = MasterMold::create([
							'Name' 			=> $mol,
							'Symbols'		=> $mol,
							'User_Created'	=> $user_created,
							'User_Updated'	=> $user_updated,
							'IsDelete'		=> 0
						]);
						$find_mold = MasterMold::where('IsDelete', 0)->where('Symbols', $mol)->first();
					}

					$find_bom_mold = MasterBOM::where('IsDelete', 0)->where('Product_BOM_ID', $request->Product_BOM_ID)->where('Mold_ID', $find_mold->ID)->first();
					if ($find_bom_mold) {
						if ($find_bom_mold->Cavity != $Cavity[$key] || $find_bom_mold->Cycle_Time != $Cycle_Time[$key]) {
							MasterBOM::where('ID', $find_bom_mold->ID)->update([
								'Cavity' 		=> $Cavity[$key],
								'Cycle_Time'	=> $Cycle_Time[$key],
								'User_Updated'	=> $user_updated,
							]);
						}
					} else {

						MasterBOM::create([
							'Product_BOM_ID' => $request->Product_BOM_ID,
							'Mold_ID'		=> $find_mold->ID,
							'Cavity' 		=> $Cavity[$key],
							'Cycle_Time'	=> $Cycle_Time[$key],
							'User_Created'	=> $user_created,
							'User_Updated'	=> $user_updated,
							'IsDelete'		=> 0
						]);
					}
					array_push($arr_mold, $find_mold->ID);
				} else {
					return (object)[
						'status' => __('Create') . ' ' . __('Mold') . ' : ' . $mol . ' ' . __('False'),
					];
				}
			}
			MasterBOM::where('IsDelete', 0)->where('Product_BOM_ID', $request->Product_BOM_ID)->where('Mold_ID', '>', 0)->whereNotIn('Mold_ID', $arr_mold)->update([
				'User_Updated'	=> $user_updated,
				'IsDelete'		=> 1
			]);
		} else {
			$find = MasterBOM::where('IsDelete', 0)->where('Product_BOM_ID', $request->Product_BOM_ID)->where('Mold_ID', '>', 0)->update([
				'User_Updated'	=> $user_updated,
				'IsDelete'		=> 1
			]);
		}
		return (object)[
			'status' => __('Create') . ' ' . __('Success'),
		];
	}

	public function get_list_materials_and_quantity($request)
	{
		$boms = 	MasterBOM::where('IsDelete', 0)->where('Product_BOM_ID', $request->Product_ID)->get();
		$array = [];
		foreach ($boms as $bom) {
			if ($bom->Product_ID) {
				$request->Product_ID = $bom->Product_ID;
				$data = $this->get_list_materials_and_quantity_next($request);
				$array = array_merge($array, $data->array);
				for ($i = 1; $i <= 1000; $i++) {
					if ($data->succ) {
						$request->Product_ID = $data->product;
						$data = $this->get_list_materials_and_quantity_next($request);
						$array = array_merge($array, $data->array);
					} else {
						$i = 1000;
					}
				}
			} else {
				$arr = (object)[
					'Materials' => $bom->Materials_ID,
					'Quantity'  => $request->Quantity * $bom->Quantity_Material,
				];
				array_push($array, $arr);
			}
		}
		return $array;
	}
	public function get_list_materials_and_quantity_next($request)
	{
		$boms = 	MasterBOM::where('IsDelete', 0)->where('Product_BOM_ID', $request->Product_ID)->get();
		$array = [];
		$succ = false;
		$product = null;
		foreach ($boms as $bom) {
			if ($bom->Product_ID) {
				$succ = true;
				$product = $bom->Product_ID;
			} else {
				$arr = (object)[
					'Materials' => $bom->Materials_ID,
					'Quantity'  => $request->Quantity * $bom->Quantity_Material,
				];
				array_push($array, $arr);
			}
		}
		return  (object)[
			'succ'  => $succ,
			'product' => $product,
			'array' => $array
		];
	}
}
