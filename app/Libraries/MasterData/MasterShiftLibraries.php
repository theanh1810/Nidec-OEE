<?php

namespace App\Libraries\MasterData;

use App\Models\MasterData\History\MachineHistory;
use App\Models\MasterData\History\ShiftHistory;
use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterShift;
use Carbon\Carbon;
use Validator;
use ExportLibraries;
use GuzzleHttp\Promise\Create;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Support\Facades\Auth;

class MasterShiftLibraries
{
    public function get_all_list_shift()
    {
        return MasterShift::where('IsDelete', 0)
            ->with([
                'user_created',
                'user_updated',
            ])
            ->get();
    }

    public function filter($request)
    {
        $id      = $request->ID;
        $name    = $request->Name;
        $from    = $request->from;
        $to      = $request->to;

        $data      = MasterShift::when($id, function ($query, $id) {
            return $query->where('ID', $id);
        })->when($name, function ($query, $name) {
            return $query->where('Name', $name);
        })->get();

        return $data;
    }

    public function check_shift($request)
    {
        // dd($request);
        $id = $request->ID;
        $message = [
            'unique.Name'   => $request->Name . ' ' . __('Already Exists') . '!',
        ];
        Validator::make(
            $request->all(),
            [
                'Name' => [
                    'required', 'max:20',
                    Rule::unique('Master_Shift')->where(function ($q) use ($id) {
                        $q->where('ID', '!=', $id)->where('IsDelete', 0);
                    })
                ],
                'Shift' => ['required', 'numeric', 'min:0', 'digits_between:0,10'],
                // 'Start_Time' => ['required'],
                // 'End_Time' => ['required']
            ],
            $message
        )->validate();
    }


    public function add_or_update($request)
    {
        $from = $request->from;
        $to = $request->to;
        $id = $request->ID;
        $user_created = Auth::user()->id;
        $user_updated = Auth::user()->id;

        $shift = MasterShift::where('IsDelete', 0)->orderBy('ID', 'desc')->first();

        if ($shift) {
            if (!Auth::user()->checkRole('update_master') && Auth::user()->level != 9999) {
                abort(401);
            }
            $a =  Carbon::create($shift->End_Time)->diffInSeconds(Carbon::create($request->from), false);
            // dd($a);
            if ($a < 0) {
                return (object)[
                    'status' => __('Shift Time In Another Shift')
                ];
            } else {
                $shift = MasterShift::create([
                    'Name'            => $request->Name,
                    'Start_Time'      => $request->from,
                    'End_Time'        => $request->to,
                    'Note'            => $request->Note,
                    'Shift'           => $request->Shift,
                    'User_Created'    => $user_created,
                    'User_Updated'    => $user_updated,
                    'IsDelete'        => 0
                ]);

                return (object)[
                    'status' => __('Create') . ' ' . __('Success'),
                    'data'     => $shift
                ];
            }
        } else {
            if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) {
                abort(401);
            }
            $shift = MasterShift::create([
                'Name'            => $request->Name,
                'Start_Time'      => $request->from,
                'End_Time'        => $request->to,
                'Note'            => $request->Note,
                'Shift'           => $request->Shift,
                'User_Created'    => $user_created,
                'User_Updated'    => $user_updated,
                'IsDelete'        => 0
            ]);

            return (object)[
                'status' => __('Create') . ' ' . __('Success'),
                'data'     => $shift
            ];
        }
    }

    public function destroy($request)
    {
        $user_created = Auth::user()->id;
        $user_updated = Auth::user()->id;
        MasterShift::where('ID', $request->ID)->update([
            'IsDelete'         => 1,
            'User_Updated'    => Auth::user()->id
        ]);

        return __('Delete') . ' ' . __('Success');
    }

    public function return($request)
    {
        // dd($request);
        $user_updated = Auth::user()->id;
        $shift_his = ShiftHistory::where('ID', $request->ID)->first();
        ShiftHistory::where('ID', $request->ID)->update([
            'Status' => 3,
            'User_Updated'    => $user_updated,
        ]);


        MasterShift::where('ID', $shift_his->Machine_ID)->update([
            'Name'            => $shift_his->Name,
            'Start_Time'      => $shift_his->from,
            'End_Time'        => $shift_his->to,
            'Note'            => $shift_his->Note,
            'Shift'           => $shift_his->Shift,
            'User_Updated'    => $user_updated,
        ]);
    }
}
