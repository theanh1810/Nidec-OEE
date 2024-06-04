<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use App\Models\History\History;
use App\Models\MasterData\History\LineHistory;
use Illuminate\Http\Request;
use App\Models\MasterData\MasterLine;

class MasterLineController extends Controller
{
	public function index(Request $request)
	{
		$name = $request->name;
		$masterLine = MasterLine::where('IsDelete', 0)
			->when($name, function ($query, $name) {
				return $query->where('Name', $name);
			})
			->with([
				'user_created',
				'user_updated'
			])
			->orderBy('Time_Updated', 'desc')
			->paginate($request->length);

		return response()->json([
			'recordsTotal' => $masterLine->total(),
			'recordsFiltered' => $masterLine->total(),
			'data' => $masterLine->toArray()['data']
		]);
	}

	public function history(Request $request)
	{
		$lines = History::where('Table_Name', 'Master_Line')
			->orderBy('ID', 'desc')
			// ->get();
			->paginate($request->length);
		// dd($machines);
		return response()->json([
			'recordsTotal' => $lines->total(),
			'recordsFiltered' => $lines->total(),
			'data' => $lines->toArray()['data']
		]);
	}

	public function get(Request $request)
	{
		return response()->json(
			MasterLine::where('IsDelete', 0)->get()
		);
	}

	public function show($id)
	{
		return response()->json(
			MasterLine::where('IsDelete', 0)->find($id)
		);
	}
}
