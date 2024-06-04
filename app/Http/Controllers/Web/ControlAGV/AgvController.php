<?php

namespace App\Http\Controllers\Web\ControlAGV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

use App\Models\MasterData\MasterAGV;
use App\Models\ControlAGV\Layout;
use App\Models\ControlAGV\Point;
use App\Models\ControlAGV\Map;
use App\Models\ControlAGV\Views\ViewMap;
use App\Models\ControlAGV\Views\ViewPoint;


class AgvController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $layouts  = Layout::where('IsDelete', 0)->get(['ID', 'Name']);

        $data    = MasterAGV::orderBy('ID', 'asc')->get([
            'ID as AGV_ID', 'Name as NAME', 'IsDelete as Actived'
        ])
            ->map(function (MasterAGV $mas) {
                $mas->Color = '000';
                $mas->Type  = 1;

                return $mas;
            });

        return view('control_agv.agv_control.index', [
            'data'          => $data,
            'partition'     => collect([]),
            'area'          => collect([]),
            'masterAgvType' => collect([]),
            'layouts'       => $layouts,
            'maps'          => collect([]),
            'sel'           => $id,
            'operation'     => collect([]),
            'point'         => collect([]),
            // 'conf'          => $this->authConfigMap()
        ]);
    }

    public function layoutAgv(Request $request)
    {
        $id       = $request->layout ?? 0;

        // $data     = array();
        $data = Layout::where('IsDelete', 0)
            ->when($id, function ($q, $id) {
                return $q->where('ID', $id);
            })
            ->toBase()
            ->get([
                'ID',
                'Name',
                'X as X_offset',
                'Y as Y_offset',
                'W',
                'H',
                'scale as Ratio',
                'IMAGE as Image'
            ]);

        // foreach($dataFind as $dat)
        // {
        //     array_push($data, [
        //         'ID'       => $dat->ID,
        //         'Name'     => $dat->Name,
        //         'X_offset' => $dat->X,
        //         'Y_offset' => $dat->Y,
        //         'W'        => $dat->W,
        //         'H'        => $dat->H,
        //         'Ratio'    => $dat->scale,
        //         'Image'    => $dat->IMAGE,
        //     ]);
        // }

        return response()->json(['data' => $data]);
    }

    public function pointList(Request $request)
    {
        $data = ViewPoint::where('Layout', $request->layout)->get();

        return response()->json(['data' => $data]);
    }

    public function map(Request $request)
    {
        $data = ViewMap::where('Layout', $request->layout)->get();

        return response()->json(['data' => $data]);
    }

    public function ratio(Request $request)
    {
        $data = array();

        return response()->json(['data' => $data]);
    }

    public function createMap(Request $request)
    {
        try {

            $num = Map::orderBy('Name', 'desc')->first();

            if ($num == null) {
                $number = 0;
            } else {
                $number = $num->Name;
            }

            $size = Map::orderBy('Time_Created', 'desc')->first();

            if ($size == null) {
                $mySize = 5;
            } else {
                $mySize = $size->W;
            }

            $data = Map::create([
                'Name'         => intval($number) + 1,
                'X'            => $request->x,
                'Y'            => $request->y,
                'W'            => $mySize,
                'Layout'       => $request->layout,
                'User_Created' => Auth::id(),
                'User_Updated' => Auth::id(),
            ]);

            return response()->json([
                'status' => true,
                'data'   => [
                    'NAME'   => $data->Name,
                    'X'      => $data->X,
                    'Y'      => $data->Y,
                    'Z'      => $data->W,
                    'X_LIDA' => 0,
                    'Y_LIDA' => 0,
                    'X_NAV'  => 0,
                    'Y_NAV'  => 0,
                    'Layout' => $request->layout,
                ]
            ]);
        } catch (Excpetion $e) {
            return response()->json([
                'status' => false,
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function updateMap(Request $request)
    {
        try {
            $data = Map::where('Name', $request->name)->first();

            if ($data) {
                $data->update(
                    [
                        'X'            => $request->x,
                        'Y'            => $request->y,
                        'W'            => $request->z,
                        'CODE'         => $request->code,
                        // 'CODE2'     => $request->code2,
                        'N1'           => $request->N1,
                        'N2'           => $request->N2,
                        'N3'           => $request->N3,
                        'N4'           => $request->N4,
                        // 'N5'        => $request->N5,
                        // 'N0'        => $request->N0
                        // 'X_LIDA'    => $request->x_lida,
                        // 'Y_LIDA'    => $request->y_lida,
                        // 'X_NAV'     => $request->x_nav,
                        // 'Y_NAV'     => $request->y_nav,
                        'Layout'       => $request->layout,
                        'User_Updated' => Auth::id(),
                    ]
                );
            } else {
                return response()->json([
                    'status' => false,
                    'data'   => 'Không tìm thấy điểm'
                ]);
            }

            return response()->json([
                'status' => true,
                'data'   => $data
            ]);
        } catch (Excpetion $e) {
            return response()->json([
                'status' => false,
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function deleteMap(Request $request)
    {
        $data = Map::where('Name', $request->name)->update([
            'IsDelete'     => 1,
            'User_Updated' => Auth::id(),
        ]);

        return response()->json(['data' => $data]);
    }

    public function deletePoint(Request $request)
    {
        $data = Point::where('IsDelete', 0)
            ->where('ID', $request->id)
            ->update([
                'User_Updated' => Auth::id(),
                'IsDelete'     => 1,
            ]);

        return response()->json([
            'status' => true,
            'data'   => $data
        ]);
    }

    public function updateLine(Request $request)
    {
        try {
            if ($request->name != null) {
                if ($request->N3 != null) {
                    $data = Map::where('Name', $request->name)->update(['N3' => $request->N3]);
                } else if ($request->N4 != null) {
                    $data = Map::where('Name', $request->name)->update(['N4' => $request->N4]);
                } else if ($request->N1 != null) {
                    $data = Map::where('Name', $request->name)->update(['N1' => $request->N1]);
                } else if ($request->N2 != null) {
                    $data = Map::where('Name', $request->name)->update(['N2' => $request->N2]);
                }
            }

            return response()->json([
                'status' => true,
                'data'   => $data
            ]);
        } catch (Excpetion $e) {
            return response()->json([
                'status' => false,
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function updateLayoutPoint(Request $request)
    {
        $data = array();

        return response()->json(['data' => $data]);
    }

    public function updateAllLayoutPoint(Request $request)
    {
        try {
            if ($request->id == '0') {
                $data = Point::insert([
                    'Name'         => $request->name,
                    'MapName'      => $request->mapName,
                    'Direction'    => $request->angle,
                    'Offset'       => $request->offset,
                    'StatusID'     => 0,
                    'Code'         => 1,
                    'X'            => $request->x,
                    'Y'            => $request->y,
                    'Layout'       => $request->layout,
                    'User_Created' => Auth::id(),
                    'User_Updated' => Auth::id(),

                    // 'H'         => $request->h,
                    // 'W'         => $request->w,
                    // 'REV'       => $request->rev,
                    // 'AREA'      => $request->area,
                    // 'PARTITION' => $request->partition,
                    // 'AGV_TYPE'  => $request->type,
                    // 'CODE'      => $request->code,
                ]);
            } else {
                $data = Point::where('ID', $request->id)->update([
                    'Name'      => $request->name,
                    'MapName'   => $request->mapName,
                    'Direction' => $request->angle,
                    'Offset'    => $request->offset,
                    'StatusID'  => 0,
                    'Code'      => 1,
                    'X'         => $request->x,
                    'Y'         => $request->y,
                    'Layout'    => $request->layout,
                    'User_Updated' => Auth::id(),
                    // 'H'         => $request->h,
                    // 'W'         => $request->w,
                    // 'REV'       => $request->rev,
                    // 'AREA'      => $request->area,
                    // 'PARTITION' => $request->partition,
                    // 'AGV_TYPE'  => $request->type,
                    // 'CODE'      => $request->code,
                ]);
            }

            return response()->json([
                'status' => true,
                'data'   => $data
            ]);
        } catch (Excpetion $e) {
            return response()->json([
                'status' => false,
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function updateLinePoint(Request $request)
    {
        if ($request->angle == 'N1') {
            $ang = 1;
        } else if ($request->angle == 'N2') {
            $ang = 2;
        } else if ($request->angle == 'N3') {
            $ang = 3;
        } else if ($request->angle == 'N4') {
            $ang = 4;
        }

        $data = Point::where('NAME', $request->name)->update(
            [
                'MapName'   => $request->mapName,
                'Direction' => $ang
            ]
        );


        return response()->json([
            'status' => true,
            'data'   => $data
        ]);
    }
}
