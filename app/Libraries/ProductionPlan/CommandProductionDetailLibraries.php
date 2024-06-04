<?php

namespace App\Libraries\ProductionPlan;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ProductionPlan\CommandProductionDetail;
use App\Models\ProductionPlan\ProductionRuntime;
use App\Models\MasterData\MasterMachine;
use App\Models\MasterData\MasterProduct;
use App\Models\MasterData\MasterMold;
use App\Models\MasterData\MasterBOM;
use Validator;
use Auth;
use Exception;
use App\Libraries\MasterData\MasterProductLibraries;
use App\Libraries\WarehouseSystem\ExportMaterialsLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use App\Models\History\HistoriesImportFile;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class MyReadFilter implements IReadFilter
{
    public function readCell($columnAddress, $row, $worksheetName = '')
    {
        // Read rows 9 to 15 and columns A to E only
        if ($row <= 1000) {
                return true;
        }

        return false;
    }
}
/**
 *
 */
class CommandProductionDetailLibraries
{

    public function __construct(
        MasterProductLibraries $masterProductLibraries,
        ExportMaterialsLibraries $ExportMaterialsLibraries

    ) {
        $this->product = $masterProductLibraries;
        $this->export = $ExportMaterialsLibraries;
    }
    public function get_list_with_command($request)
    {
        $data = CommandProductionDetail::where('IsDelete', 0)->where('Command_ID', $request->ID)
            ->orderBy('Time_Start')->orderBy('Time_End')
            ->with([
                'user_created',
                'user_updated'
            ])->paginate(20);

        return $data;
    }
    public function get_all_list_with_command($request)
    {
        $data = CommandProductionDetail::where('IsDelete', 0)->where('Command_ID', $request->ID)
            ->orderBy('Time_Start')->orderBy('Time_End')
            ->with([
                'user_created',
                'user_updated'
            ])->get();

        return $data;
    }
    public function get_list_with_command_paginate_date($request)
    {
        if (!$request->page || $request->page == 1) {
            $data = CommandProductionDetail::where('IsDelete', 0)->where('Command_ID', $request->ID)
                ->orderBy('Time_Start')->orderBy('Time_End')
                ->with([
                    'user_created',
                    'user_updated'
                ])->paginate(10);
        }
        return $data;
    }
    public function get_list_with_command_filter($request)
    {
        $from = $request->From;
        $to = $request->To;
        $product = $request->Product;
        $machine = $request->Machine;
        $status = $request->Status;
        if ($status === 0) {
            $data = CommandProductionDetail::where('IsDelete', 0)
                ->where('Command_ID', $request->ID)
                ->where('Status', 0)
                ->when($machine, function ($query, $machine) {
                    return $query->where('Part_Action', $machine);
                })->when($product, function ($query, $product) {
                    return $query->where('Product_ID', $product);
                })
                ->when($from, function ($query, $from) {
                    return $query->where('Time_Start', '>=', Carbon::create($from));
                })
                ->when($to, function ($query, $to) {
                    return $query->where('Time_End', Carbon::create($to));
                })
                ->orderBy('Time_Start')->orderBy('Time_End')
                ->with([
                    'user_created',
                    'user_updated'
                ])->paginate(10);
        } else {
            $data = CommandProductionDetail::where('IsDelete', 0)
                ->where('Command_ID', $request->ID)
                ->when($status, function ($query, $status) {
                    return $query->where('Status', $status);
                })
                ->when($machine, function ($query, $machine) {
                    return $query->where('Part_Action', $machine);
                })->when($product, function ($query, $product) {
                    return $query->where('Product_ID', $product);
                })
                ->when($from, function ($query, $from) {
                    return $query->where('Time_Start', '>=', Carbon::create($from));
                })
                ->when($to, function ($query, $to) {
                    return $query->where('Time_End', Carbon::create($to));
                })
                ->orderBy('Time_Start')->orderBy('Time_End')
                ->with([
                    'user_created',
                    'user_updated'
                ])->paginate(10);
        }
        return $data;
    }

    public function add_or_update($request)
    {
        // dd($request);
        if (!$request->Machine) return __('Machine') . ' ' . __('Are') . ' ' . __('Wrong');
        if (!$request->Product) return __('Product') . ' ' . __('Are') . ' ' . __('Wrong');
        if (!$request->Mold_ID) return __('Mold') . ' ' . __('Are') . ' ' . __('Wrong');
        if (!$request->Date) return __('Date') . ' ' . __('Are') . ' ' . __('Wrong');
        if (!$request->Quantity) return  __('Quantity') . ' ' . __('Product') . ' ' . __('Are') . ' ' . __('Wrong');
        $product = MasterProduct::where('ID', $request->Product)->first();
        $machine = MasterMachine::where('ID', $request->Machine)->first();
        $mold    = MasterMold::where('ID', $request->Mold_ID)->first();
        if (!$product) {
            return  __('Product') . "  " . __('Not Exit');
        }

        if (!$machine) {
            return  __('Machine') . "  " . __('Not Exit');
        }
        $check_bom = MasterBOM::where('IsDelete', 0)->where('Product_BOM_ID', $request->Product)->where('Mold_ID', $request->Mold_ID)->first();
        // dd($check_bom)
        if (!$check_bom) {
            return  __('Mold') . "  " . __('Not In') . "  " . __('BOM');
        }
        if (!$mold) {
            return  __('Mold') . "  " . __('Not Exit');
        }
        if (!is_numeric($request->Quantity) || $request->Quantity <= 0) {
            return  __('Quantity') . "  " . __('Not Exit');
        }
        if ($request->Quantity > 99999999999) {
            return  __('Quantity') . "  " . __('Very') . "  " . __('Big');
        }
        $cvdate          = Carbon::create($request->Date)->isoFormat('YYMMDD');
        $stt_in_month    = CommandProductionDetail::where('IsDelete', 0)->where('Part_Action', $machine->ID)->where('Date', '=', $request->Date)->GroupBy('Symbols');
        $stt             = $stt_in_month->count();
        $sym             = $machine->Symbols . '--' . $cvdate . '--' . ($stt + 1);
        try {
            $start  = $request->Date . ' 00:00';
            $end    = $request->Date . ' 23:59:59';
            if (!$request->idDetail) {
                $commandDetail = CommandProductionDetail::create([
                    'Symbols'      => $sym,
                    'Command_ID'   => $request->ID,
                    'Mold_ID'      => $request->Mold_ID,
                    'Product_ID'   => $request->Product,
                    'Part_Action'  => $request->Machine,
                    'Quantity'     => $request->Quantity,
                    'Date'         => $request->Date,
                    'Time_Start'   => $start,
                    'Time_End'     => $end,
                    'Status'       => 0,
                    'Type'         => 1,
                    'Version'      => $request->Version,
                    'His'          => $request->His,
                    'Note'          => __('Create') . ' ' . __('With WebSite'),
                    'User_Created' => Auth::user()->id,
                    'User_Updated' => Auth::user()->id
                ]);
            } else {
                $commandDetail = CommandProductionDetail::where('ID', $request->idDetail)->update([
                    'Symbols'      => $sym,
                    'Quantity'     => $request->Quantity,
                    'Date'         => $request->Date,
                    'Time_Start'   => $start,
                    'Time_End'     => $end,
                    'Status'       => 0,
                    'Type'         => 1,
                    'Version'      => $request->Version,
                    'His'          => $request->His,
                    'Note'          => __('Update') . ' ' . __('With WebSite'),
                    'User_Updated' => Auth::user()->id
                ]);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return true;
    }

    public function destroy($request)
    {
        $find = CommandProductionDetail::where('IsDelete', 0)->where('ID', $request->ID)->first();

        $find->update([
            'User_Updated' => Auth::user()->id,
            'IsDelete'     => 1
        ]);

        return __('Delete') . ' ' . __('Success');
    }
    public function cancel($request)
    {
        $find = CommandProductionDetail::where('IsDelete', 0)->where('ID', $request->ID)->first();

        $find->update([
            'User_Updated' => Auth::user()->id,
            'Status'     => 2
        ]);

        return __('End') . ' ' . __('Success');
    }
    public function detail_export_materials($request)
    {
        $planDetail = CommandProductionDetail::where('IsDelete', 0)->where('ID', $request->ID)->first();
        if ($planDetail) {
            $data = $this->product->get_list_materials_and_quantity((object)[
                'Product_ID' => $planDetail->Product_ID,
                'Quantity' => $planDetail->Quantity,
            ]);
            // dd($data,$planDetail);
            foreach ($data as $value) {
                $export = $this->export->create_command_export((object)[
                    'Materials_ID' => $value->Materials,
                    'Quantity' => $value->Quantity,
                    'Machine_ID' => $planDetail->Part_Action
                ]);
            }
        }

        return __('Create') . ' ' . __('Success');
    }

    // private function read_file($request)
    // {
    //     try {
    //         $file     = request()->file('fileImport');
    //         $name     = $file->getClientOriginalName();
    //         $arr      = explode('.', $name);
    //         $fileName = strtolower(end($arr));
    //         if ($fileName != 'xlsx' && $fileName != 'xls') {
    //             return redirect()->back();
    //         } else if ($fileName == 'xls') {
    //             $reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
    //         } else if ($fileName == 'xlsx') {

    //             $reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    //         }
    //         try {

    //             $filterSubset = new MyReadFilter();
    //             $reader->setReadFilter($filterSubset);
    //             $spreadsheet = $reader->load($file);
    //             $spreadsheet->setActiveSheetIndex(1);
    //             $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    //             dd($sheetData);
    //             return $spreadsheetActive;
    //         } catch (\Exception $e) {
    //             return ['danger' => __('Select The Standard ".xlsx" Or ".xls" File')];
    //         }
    //     } catch (\Exception $e) {
    //         return ['danger' => __('Error Something')];
    //     }
    // }
    private function read_file($request)
    {
        try {
            $file     = request()->file('fileImport');
            $name     = $file->getClientOriginalName();
            $arr      = explode('.', $name);
            $fileName = strtolower(end($arr));
            if ($fileName != 'xlsx' && $fileName != 'xls') {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            return ['danger' => __('Error Something')];
        }
    }
    public function import_file($request)
    {
        $user_created = Auth::user()->id;
        $user_updated = Auth::user()->id;
        $this->read_file($request);
        $user_created = Auth::user()->id;
        $user_updated = Auth::user()->id;
        $cvt      = Carbon::now()->isoFormat('YYMMDDhhmmss');
        $file     = request()->file('fileImport');
        $name     = $file->getClientOriginalName();
        $arr      = explode('.', $name);
        $fileName = strtolower(end($arr));
        $thu_muc_histories  = 'uploads\Histories';
        $ten_file_histories = 'KHSX-' . $cvt . '.' . $fileName;

        $im  = [];
        $err = [];
        $product = null;
        $machine = null;
        $mold    = null;

        $visit = 0;
        $key = 1;
        $date   = $request->Year . '-' . sprintf("%02d", $request->Month).'%';

        $listMold = MasterMold::where('IsDelete', 0)->get();
        $listMachine = MasterMachine::where('IsDelete', 0)->get();
        $listProduct = MasterProduct::where('IsDelete', 0)->get();
        $listBOM = MasterBOM::where('IsDelete', 0)->get();
        $listDetail = CommandProductionDetail::where('IsDelete', 0)->where('Date','LIKE', $date)->get();
        $sheetIndex = 1;
        try {
            $reader = ReaderEntityFactory::createXLSXReader();
            $reader->open($file);
            foreach ($reader->getSheetIterator() as $sheet) {
                if($sheetIndex == 2){
                    foreach ($sheet->getRowIterator() as $row) {
                        // do stuff with the row
                        $cells = $row->getCells();
                        $value = [];
                        foreach($cells as $cell)
                        {
                            $value[] = $cell->getValue();
                        }
                        if (!$value[2])
                        {
                            $key=$key+1;
                            continue;
                        }
                        $date  = "1-" . $request->Month . '-' . $request->Year;
                        $month = Carbon::create($date)->format('M');
                        $text_month = '-' . $month;
                        $check = false;
                        if ($key == 4) {
                            for ($i = 22; $i < count($value); $i++) {
                                $monthInFile = Carbon::create($value[$i])->format('yy-M-d');
                                if (strlen(strstr($monthInFile, $text_month)) > 0) {
                                    $visit = $i;
                                    break;
                                }
                            }

                            if ($visit == 0) {
                                $er = __('Month') . " : " . $request->Month . __(' ') . __('Not Exit');
                                array_push($err, $er);
                            }
                        }
                        if ($key > 5 && $visit > 0) {
                            if ($value[2]) {
                                $mold    = $listMold->where('Name', $value[2])->first();
                                if (!$mold) {
                                    $er = __('Mold') . " : " . $value[2] . ' ' . __('Not Exit');
                                    array_push($err, $er);
                                }
                            } else {
                                $er = __('Mold') . " : " . __('Location') . ' ' . ($key + 1) . ' ' . __('Not Exit');
                                array_push($err, $er);
                            }
                            if ($value[12]) {
                                $machine    = $listMachine->where('Name', $value[12])->first();
                                if (!$machine) {
                                    $er = __('Machine') . " : " . $value[12] . ' ' . __('Not Exit');
                                    array_push($err, $er);
                                }
                            } else {
                                $er = __('Machine') . " : " . __('Location') . ' ' . ($key + 1) . ' ' . __('Not Exit');
                                array_push($err, $er);
                            }
                            if ($value[3]) {
                                $product = $listProduct->where('Symbols', $value[3])->first();
                                if ($product) {
                                    if ($product && $machine && $mold) {
                                        $check_bom = $listBOM->where('Product_BOM_ID', $product->ID)->where('Mold_ID', $mold->ID)->first();
                                        if (!$check_bom) {
                                            return  __('Mold') . ' ' . $value[2] . "  " . __('Not In') . "  " . __('BOM') . ' ' . $value[3];
                                        } else {
                                            $check = true;
                                        }
                                    }
                                } else {
                                    $er = __('Product') . " : " . $value[3] . ' ' . __('Not Exit');
                                    array_push($err, $er);
                                }
                            } else {
                                $er = __('Product') . " : " . __('Location') . ' ' . ($key + 1) . ' ' . __('Not Exit');
                                array_push($err, $er);
                            }
                            if ($check) {
                                $date  = "1-" . $request->Month . '-' . $request->Year;
                                $day = Carbon::create($date)->endOfMonth()->day;
                                $day_start  = $request->Year . '-' . $request->Month . '-1';
                                $day_end = $request->Year . '-' . $request->Month . '-' . $day;
                                for ($i = 1; $i <= $day; $i++) {
                                    if ($value[$i + ($visit - 1)] != '' && is_numeric($value[$i + ($visit - 1)]) && $value[$i + ($visit - 1)] > 0  && $value[$i + ($visit - 1)] <= 9999999999999) {
                                        // dd($value[$i+($visit-1)],$i);
                                        $start  = $request->Year . '-' . $request->Month . '-' . $i . ' 00:00';
                                        $end    = $request->Year . '-' . $request->Month . '-' . $i . ' 23:59:59';
                                        $date   = $request->Year . '-' . $request->Month . '-' . $i;
                                        $dateFilter   = $request->Year . '-' . sprintf("%02d", $request->Month) . '-' . sprintf("%02d", $i);
                                        $cvdate     = Carbon::create($date)->isoFormat('YYMMDD');
                                        $check_plan = $listDetail->where('Part_Action', $machine->ID)->where('Mold_ID', $mold->ID)->where('Product_ID', $product->ID)->where('Date', '=', $dateFilter)->first();
                                        if ($check_plan) {
                                            if ($check_plan->Status < 2) {
                                                if ($check_plan->Quantity != $value[$i + ($visit - 1)] || $check_plan->Version != $value[6] || $check_plan->His !=  $value[7]) {
                                                    $dataSave = ([
                                                        'Quantity'      => $value[$i + ($visit - 1)],
                                                        'Version'       => $value[6],
                                                        'His'           => $value[7],
                                                        'MPMT'           => $value[9],
                                                        'MaterialCode'           => $value[10],
                                                        'Note'          => __('Update') . ' ' . __('With Excel'),
                                                        'User_Updated'    => $user_updated,
                                                    ]);
                                                    CommandProductionDetail::where('ID', $check_plan->ID)->update($dataSave);
                                                }
                                            }
                                        } else {
                                            if (!$value[12]) {
                                                $stt_in_month  =  $listDetail->where('Part_Action', $machine->ID)->where('Date', '=', $date)->GroupBy('Symbols');
                                                $stt = $stt_in_month->count();
                                                $sym = $machine->Symbols . '--' . $cvdate . '--' . ($stt + 1);
                                            } else {
                                                $stt_in_month  =  $listDetail->where('Part_Action', $machine->ID)
                                                                            ->where('Date', '=', $date)->where('Group', '=', $value[12])->sortByDesc('Time_Created')->first();
                                                if ($stt_in_month && $value[12] != ' ') {
                                                    $sym =  $stt_in_month->Symbols;
                                                } else {
                                                    $stt_in_month  =  $listDetail->where('Part_Action', $machine->ID)->where('Date', '=', $date)->groupBy('Symbols');
                                                    $stt = $stt_in_month->count();
                                                    $sym = $machine->Symbols . '--' . $cvdate . '--' . ($stt + 1);
                                                }
                                            }
                                            $bom = $listBOM->where('Product_BOM_ID', $product->ID)->where('Mold_ID', $mold->ID)->first();
                                            $dataSave = ([
                                                'Symbols'       => $sym,
                                                'Command_ID'    => $request->Plan_ID,
                                                'Mold_ID'       => $mold->ID,
                                                'Product_ID'    => $product->ID,
                                                'Part_Action'   => $machine->ID,
                                                'Quantity'      => $value[$i + ($visit - 1)],
                                                'Date'          => $date,
                                                'Time_Start'    => $start,
                                                'Time_End'      => $end,
                                                'Status'        => 0,
                                                'Type'          => 1,
                                                'Version'       => $value[6],
                                                'His'           => $value[7],
                                                'Note'          => __('Create') . ' ' . __('With Excel'),
                                                'User_Created'    => $user_created,
                                                'User_Updated'    => $user_updated,
                                                'IsDelete'        => 0,
                                                'MPMT'           => $value[9],
                                                'MaterialCode'           => $value[10],
                                                'Cavity_Real' => $bom->Cavity
                                            ]);
                                            CommandProductionDetail::create($dataSave);
                                        }
                                    }
                                    // else {
                                    //     if (is_numeric($value[$i + ($visit - 1)]) ||  $value[$i + ($visit - 1)] < 0 || $value[$i + ($visit - 1)] > 9999999999999) {
                                    //         $er = __('Error') . " : " . $request->Year . '-' . $request->Month . '-' . $i . ' không có giá trị ' . __('Location') . ' ' . ($key) . ' ';
                                    //         array_push($err, $er);
                                    //     }
                                    // }
                                }
                            }
                        }

                        $key=$key+1;
                    }

                    break;
                }

                $sheetIndex = $sheetIndex + 1;
            }

            $file->move($thu_muc_histories, $ten_file_histories);
            HistoriesImportFile::create([
                'Table_Name'    => 'Command_Production_Detail',
                'Folder'        => $thu_muc_histories,
                'ID_Main'       => $request->Plan_ID,
                'File'          => $ten_file_histories,
                'User_Created'    => $user_created,
                'User_Updated'    => $user_updated,
                'IsDelete'        => 0
            ]);
        } catch (\Exception $e) {
            dd($e);
            return ['danger' => __('Error') . ' ' . __('Plan') . ' ' . __('Production')];
        }
        return $err;
    }
}


