<?php

namespace App\Libraries\MasterData;

use App\Models\MasterData\History\LineHistory;
use App\Models\MasterData\MasterLine;
use Illuminate\Validation\Rule;
use Validator;
use Auth;

class MasterLineLibraries
{
    public function get_all_list_line()
    {
        return MasterLine::where('IsDelete', 0)
            ->get();
    }

    public function filter_id($request)
    {
        $data = MasterLine::where('IsDelete', 0)
            ->whereIn('Name', $request)
            ->get();

        return $data;
    }

    public function filter_line($request)
    {
        $id      = $request->ID;
        $name      = $request->Name;

        $data      = MasterLine::when($id, function ($query, $id) {
            return $query->where('ID', $id);
        })
            ->when($name, function ($query, $name) {
                return $query->where('Name', $name);
            })
            ->where('IsDelete', 0)
            ->get();

        return $data;
    }

    public function check_line($request)
    {
        $id = $request->ID;
        $message = [
            'unique.Name'   => $request->Name . ' ' . __('Already Exists') . '!',
        ];
        Validator::make(
            $request->all(),
            [
                'Name' => [
                    Rule::unique('Master_Line')->where(function ($q) use ($id) {
                        $q->where('ID', '!=', $id)->where('IsDelete', 0);
                    })
                ],
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
            $line_find = MasterLine::where('IsDelete', 0)->where('ID', $id)->first();
            if ($line_find->Name != $request->Name || $line_find->Note != $request->Note) {
                // dd('1');
                $machine = MasterLine::where('ID', $id)->update([
                    'Name'        => $request->Stock_Min,
                    'Note'             => $request->Note,
                    'User_Updated'     => $user_updated
                ]);

                return (object)[
                    'status' => __('Update') . ' ' . __('Success'),
                    'data'     => $machine
                ];
            } else {
                return (object)[
                    'status' => __('Update') . ' ' . __('Success'),
                ];
            }
        } else {
            if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) {
                abort(401);
            }

            $machine = MasterLine::create([
                'Name'             => $request->Name,
                'Note'             => $request->Note,
                'User_Created'     => $user_created,
                'User_Updated'     => $user_updated,
                'IsDelete'         => 0
            ]);

            return (object)[
                'status' => __('Create') . ' ' . __('Success'),
                'data'     => $machine
            ];
        }
    }

    public function destroy($request)
    {
        MasterLine::where('ID', $request->ID)->update([
            'IsDelete'         => 1,
            'User_Updated'    => Auth::user()->id
        ]);

        return __('Delete') . ' ' . __('Success');
    }

    public function return($request)
    {
        // dd($request);
        $user_updated = Auth::user()->id;
        $machine_his = LineHistory::where('ID', $request->ID)->first();
        LineHistory::where('ID', $request->ID)->update([
            'Status' => 3,
            'User_Updated'    => $user_updated,
        ]);


        MasterLine::where('ID', $machine_his->Line_ID)->update([
            'Name'          => $machine_his->Name,
            'Note'          => $machine_his->Note,
            'User_Updated'    => $user_updated,
        ]);
    }

    private function read_file($request)
    {
        try {
            $file     = request()->file('fileImport');
            $name     = $file->getClientOriginalName();
            $arr      = explode('.', $name);
            $fileName = strtolower(end($arr));
            // dd($file, $name, $arr, $fileName);
            if ($fileName != 'xlsx' && $fileName != 'xls') {
                return redirect()->back();
            } else if ($fileName == 'xls') {
                $reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else if ($fileName == 'xlsx') {
                $reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            try {
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
        // try {

        $data = $this->read_file($request);
        $im = [];
        $err = array();
        $user_created = Auth::user()->id;
        $user_updated = Auth::user()->id;

        foreach ($data as $key => $value) {
            $name = strval($value[2]);
            $note = strval($value[3]);
            // dd($stockmax);
            if ($key > 1) {
                if (!is_null($value[2]) && strlen($name) <= 25) {
                    $line = MasterLine::where('IsDelete', 0)->where('Name', $value[2])->first();
                    if ($line) {
                        MasterLine::where('IsDelete', 0)->where('ID', $line->ID)->update([
                            'Note'        => $value[3],
                        ]);
                    } else {
                        $line = MasterLine::create([
                            'Name'             => $value[2],
                            'Note'        => $value[3],
                            'User_Created'     => $user_created,
                            'User_Updated'     => $user_updated,
                            'IsDelete'         => 0
                        ]);
                    }
                } else {
                    array_push($err, __('Create') . ' ' . __('Fail') . ' ' . __('In') . ' ' . __('Position') . ' ' . ($key + 1));
                }
            } else {
                array_push($err, __('Name') . ' ' . __('Does Not Exist') . ' ' . __('In') . ' ' . __('Position') . ' ' . ($key + 1));
            }
        }
        // } catch (\Exception $e) {
        // 	array_push($err, $e);
        // }
        return $err;
    }
}
