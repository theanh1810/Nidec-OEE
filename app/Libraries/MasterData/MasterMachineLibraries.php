<?php

namespace App\Libraries\MasterData;

use App\Models\MasterData\History\MachineHistory;
use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterMachine;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use Auth;

class MasterMachineLibraries
{
    public function get_all_list_machine()
    {
        return MasterMachine::where('IsDelete', 0)
            ->get();
    }

    public function get_first()
    {
        return MasterMachine::where('IsDelete', 0)
            ->first();
    }

    public function filter_id($request)
    {
        $data = MasterMachine::where('IsDelete', 0)
            ->whereIn('Symbols', $request)
            ->get();

        return $data;
    }

    public function filter_machine($request)
    {
        $id      = $request->ID;
        $name      = $request->Name;
        $symbols = $request->Symbols;
        $Spec = $request->Spec;
        $Wire_Type = $request->Wire_Type;
        $Line_ID = $request->Line_ID;


        $data      = MasterMachine::when($id, function ($query, $id) {
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
            ->when($Line_ID, function ($query, $Line_ID) {
                return $query->where('Line_ID', $Line_ID);
            })
            ->where('IsDelete', 0)
            ->get();

        return $data;
    }

    public function check_machine($request)
    {
        $id = $request->ID;
        $message = [
            'unique.Symbols'   => $request->Symbols . ' ' . __('Already Exists') . '!',
            // 'unique.MAC'   => $request->MAC . ' ' . __('Already Exists') . '!',
        ];
        Validator::make(
            $request->all(),
            [
                'Stock_Min' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
                'Line_ID' => ['required'],
                'Symbols' => [
                    'required', 'max:25',
                    Rule::unique('Master_Machine')->where(function ($q) use ($id) {
                        $q->where('ID', '!=', $id)->where('IsDelete', 0);
                    })
                ],
                // 'MAC' => [
                //     'required', 'max:20',
                //     Rule::unique('Master_Machine')->where(function ($q) use ($id) {
                //         $q->where('ID', '!=', $id)->where('IsDelete', 0);
                //     })
                // ]
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
            $machine_find = MasterMachine::where('IsDelete', 0)->where('ID', $id)->first();
            if (
                $machine_find->Symbols != $request->Symbols || $machine_find->Stock_Min != $request->Stock_Min
                // || $machine_find->MAC != $request->MAC
                || $machine_find->Note != $request->Note
                || $machine_find->Line_ID != $request->Line_ID
            ) {
                $machine = MasterMachine::where('ID', $id)->update([
                    'Stock_Min'        => $request->Stock_Min,
                    'Symbols'          => $request->Symbols,
                    // 'MAC'              => $request->MAC,
                    'Note'             => $request->Note,
                    'Line_ID'          => $request->Line_ID,
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

            $machine = MasterMachine::create([
                'Name'             => $request->Symbols,
                'Symbols'          => $request->Symbols,
                // 'MAC'              => $request->MAC,
                'Stock_Min'        => $request->Stock_Min,
                'Line_ID'        => $request->Line_ID,
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
        MasterMachine::where('ID', $request->ID)->update([
            'IsDelete'         => 1,
            'User_Updated'    => Auth::user()->id
        ]);

        return __('Delete') . ' ' . __('Success');
    }

    public function return($request)
    {
        // dd($request);
        $user_updated = Auth::user()->id;
        $machine_his = MachineHistory::where('ID', $request->ID)->first();
        MachineHistory::where('ID', $request->ID)->update([
            'Status' => 3,
            'User_Updated'    => $user_updated,
        ]);


        MasterMachine::where('ID', $machine_his->Machine_ID)->update([
            'Name'          => $machine_his->Name,
            'Stock_Min'     => $machine_his->Stock_Min,
            'Stock_Max'     => $machine_his->Stock_Max,
            'Symbols'       => $machine_his->Symbols,
            'Line_ID'       => $machine_his->Line_ID,
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
            $stockmin = $value[4];
            $stockmax = $value[3];
            $name = strval($value[2]);
            $symbols = strval($value[2]);
            // dd($stockmax);
            if ($key > 1) {
                if (!is_null($value[2]) && strlen($name) <= 25) {
                        if (is_numeric($stockmin) && $stockmin > 0) {
                            $machine = MasterMachine::where('IsDelete', 0)->where('Name', $value[2])->first();
                            if ($machine) {
                                if ($machine->Stock_Min != $value[4] || $machine->Stock_Max != $value[3]) {
                                    MasterMachine::where('IsDelete', 0)->where('ID', $machine->ID)->update([
                                        'Stock_Min'        => $value[4],
                                        'Stock_Max'        => $value[3],
                                    ]);
                                }
                            } else {
                                    $machine = MasterMachine::create([
                                        'Name'             => $value[2],
                                        'Symbols'          => $value[2],
                                        'Stock_Min'        => $value[4],
                                        'Stock_Max'        => $value[3],
                                        'User_Created'     => $user_created,
                                        'User_Updated'     => $user_updated,
                                        'IsDelete'         => 0
                                    ]);
                            }
                        } else {
                            array_push($err, __('Create') . ' ' . __('Fail') . ' ' . __('In') . ' ' . __('Position') . ' ' . ($key + 1));
                        }

                } else {
                    array_push($err, __('Symbols') . ' ' . __('Does Not Exist') . ' ' . __('In') . ' ' . __('Position') . ' ' . ($key + 1));
                }
            }
        }
        // } catch (\Exception $e) {
        // 	array_push($err, $e);
        // }
        return $err;
    }
}
