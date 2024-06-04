<?php

namespace App\Libraries\MasterData;

use App\Models\MasterData\History\MaterialHistory;
use App\Models\MasterData\MasterMachine;
use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterMaterials;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use Auth;

class MasterMaterialsLibraries
{
	public function get_all_list_materials()
	{
		return MasterMaterials::where('IsDelete', 0)
			->with([
				'user_created',
				'user_updated',
				'unit',
				'supplier'
			])
			->get();
	}

	public function filter_id($request)
	{
		$data = MasterMaterials::where('IsDelete', 0)
			->whereIn('Symbols', $request)
			->get();

		return $data;
	}

	public function filter_materials($request)
	{
		$id 	 = $request->ID;
		$name 	 = $request->Name;
		$symbols = $request->Symbols;
		$Spec = $request->Spec;
		$Wire_Type = $request->Wire_Type;


		$data 	 = MasterMaterials::when($id, function ($query, $id) {
			return $query->where('ID', $id);
		})
			->when($name, function ($query, $name) {
				return $query->where('Name', $name);
			})
			->when($symbols, function ($query, $symbols) {
				return $query->where('Symbols', $symbols);
			})
			->when($Spec, function ($query, $Spec) {
				return $query->where('Spec', $Spec);
			})
			->when($Wire_Type, function ($query, $Wire_Type) {
				return $query->where('Wire_Type', $Wire_Type);
			})
			->where('IsDelete', 0)
			->get();

		return $data;
	}

	public function check_materials($request)
	{
		$id      = $request->ID;
		$symbols = $request->Symbols;


		Validator::make(
			$request->all(),
			[
				'Name'       => 'required|max:255',
				'Symbols'    => 'required|max:255',
			]
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
			$material_find = MasterMaterials::where('IsDelete', 0)->where('ID', $id)->first();

			$materials = MasterMaterials::where('ID', $id)->update([
				'Name'             => $request->Name,
				'Symbols'          => $request->Symbols,
				'Unit_ID'          => $request->Unit_ID,
				'Packing_ID'       => $request->Packing_ID,
				'Supplier_ID'      => $request->Supplier_ID,
				'Model'            => $request->Model,
				'Norms'			   => $request->Norms,
				'Standard_Unit'    => $request->Standard_Unit,
				'Standard_Packing' => $request->Standard_Packing,
				'Part_ID'          => $request->Part_ID,
				'Difference'       => $request->Difference,
				'Spec'			   => $request->Spec,
				'Wire_Type'        => $request->Wire_Type,
				'Note'             => $request->Note,
				'User_Updated'     => $user_updated
			]);

			return (object)[
				'status' => __('Update') . ' ' . __('Success'),
				'data'	 => $materials
			];
		} else {
			if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) {
				abort(401);
			}

			$materials = MasterMaterials::create([
				'Name'             => $request->Name,
				'Symbols'          => $request->Symbols,
				'Unit_ID'          => $request->Unit_ID,
				'Packing_ID'       => $request->Packing_ID,
				'Supplier_ID'      => $request->Supplier_ID,
				'Model'            => $request->Model,
				'Standard_Unit'    => $request->Standard_Unit,
				'Standard_Packing' => $request->Standard_Packing,
				'Part_ID'          => $request->Part_ID,
				'Note'             => $request->Note,
				'Norms'			   => $request->Norms,
				'Difference'       => $request->Difference,
				'Spec'			   => $request->Spec,
				'Wire_Type'        => $request->Wire_Type,
				'User_Created'     => $user_created,
				'User_Updated'     => $user_updated,
				'IsDelete'         => 0
			]);

			return (object)[
				'status' => __('Create') . ' ' . __('Success'),
				'data'	 => $materials
			];
		}
	}

	public function destroy($request)
	{
		$user_created = Auth::user()->id;
		$user_updated = Auth::user()->id;
		$find = MasterMaterials::where('ID', $request->ID)->first();

		MasterMaterials::where('ID', $request->ID)->update([
			'IsDelete' 		=> 1,
			'User_Updated'	=> Auth::user()->id
		]);
		return __('Delete') . ' ' . __('Success');
	}

	public function return($request)
	{
		$user_updated = Auth::user()->id;
		$material_his = MaterialHistory::where('ID', $request->ID)->first();
		MaterialHistory::where('ID', $request->ID)->update([
			'Status' => 3,
			'User_Updated'	=> $user_updated,
		]);
		MasterMaterials::where('ID', $material_his->Material_ID)->update([
			'Name'             	=> $material_his->Name,
			'Symbols'          	=> $material_his->Symbols,
			'Unit_ID'          	=> $material_his->Unit_ID,
			'Packing_ID'       	=> $material_his->Packing_ID,
			'Supplier_ID'      	=> $material_his->Supplier_ID,
			'Model'            	=> $material_his->Model,
			'Norms'			   	=> $material_his->Norms,
			'Standard_Unit'    	=> $material_his->Standard_Unit,
			'Standard_Packing' 	=> $material_his->Standard_Packing,
			'Part_ID'          	=> $material_his->Part_ID,
			'Difference'       	=> $material_his->Difference,
			'Spec'			   	=> $material_his->Spec,
			'Wire_Type'        	=> $material_his->Wire_Type,
			'Note'             	=> $material_his->Note,
			'User_Updated'     	=> $user_updated,
		]);
	}
}
