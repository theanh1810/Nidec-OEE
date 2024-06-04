<?php

namespace App\Libraries\MasterData;

use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterAccessories;
use App\Models\MasterData\MasterAGV;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use Auth;
use DB;

class MasterAGVLibraries
{
    public function get_all_list_agv()
    {
        return MasterAGV::where('IsDelete', 0)
            ->get();
    }

    public function filter_id($request)
    {
        $data = MasterAGV::where('IsDelete', 0)
            ->whereIn('Name', $request)
            ->get();

        return $data;
    }

    public function filter_agv($request)
    {
        $id      = $request->ID;
        $name    = $request->Name;
        $ip      = $request->IP;
        $mac     = $request->MAC;

        $data      = MasterAGV::when($id, function ($query, $id) {
            return $query->where('ID', $id);
        })
            ->when($name, function ($query, $name) {
                return $query->where('Name', $name);
            })
            ->when($ip, function ($query, $ip) {
                return $query->where('IP', $ip);
            })
            ->when($mac, function ($query, $mac) {
                return $query->where('MAC', $mac);
            })
            ->where('IsDelete', 0)
            ->get();

        return $data;
    }

    public function check_agv($request)
    {
        $id = $request->ID;
        $message = [
            'unique'   => $request->Symbols . ' ' . __('Already Exists') . '!',
        ];
        Validator::make(
            $request->all(),
            [
                'MAC' => [
                    'required', 'max:20',
                    Rule::unique('App\Models\MasterData\MasterAGV')->where(function ($q) use ($id) {
                        $q->where('ID', '!=', $id)->where('IsDelete', 0);
                    })
                ],
                'Name' => [
                    'required', 'max:20',
                    Rule::unique('App\Models\MasterData\MasterAGV')->where(function ($q) use ($id) {
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
            $find_agv = MasterAGV::where('ID', $id)->first();
            if ($find_agv->Name != $request->Name || $find_agv->MAC != $request->MAC || $find_agv->Type != $request->Type || $find_agv->Active != $request->Active || $find_agv->Note != $request->Note) {
                $agv = MasterAGV::where('ID', $id)->update([
                    'Name'              => $request->Name,
                    'MAC'               => $request->MAC,
                    'Type'              => $request->Type,
                    'Active'            => $request->Active,
                    'Note'              => $request->Note,
                    'User_Updated'      => $user_updated
                ]);

                return (object)[
                    'status' => __('Update') . ' ' . __('Success'),
                    'data'     => $agv
                ];
            } else {
                return (object)[
                    'status' => __('Update') . ' ' . __('Success')
                ];
            }
        } else {
            if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) {
                abort(401);
            }

            $agv = MasterAGV::create([
                'Name'             => $request->Name,
                'MAC'              => $request->MAC,
                'Type'             => $request->Type,
                'Active'           => $request->Active,
                'Note'             => $request->Note,
                'User_Created'     => $user_created,
                'User_Updated'     => $user_updated,
                'IsDelete'         => 0,
            ]);


            return (object)[
                'status' => __('Create') . ' ' . __('Success'),
                'data'     => $agv
            ];
        }
    }

    public function destroy($request)
    {
        MasterAGV::where('ID', $request->ID)->update([
            'IsDelete'         => 1,
            'User_Updated'    => Auth::user()->id
        ]);

        return __('Delete') . ' ' . __('Success');
    }

    public function getEfficiency($request)
    {
        $data = DB::connection('sqlsrv2')
            ->select("exec AGV_PERFORMANCE " . $request->agv . ",'" . $request->from . "', '" . $request->to . "'");

        return $data;
    }
}
