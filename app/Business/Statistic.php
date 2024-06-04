<?php

namespace App\Business;

use App\Models\Oee\ProductDefectiveLog;
use App\Models\Oee\RuntimeHistory;
use App\Models\Oee\OeeDay;
use App\Models\MasterData\MasterMachine;
use Carbon\Carbon;

class Statistic
{
    public function oeeReportByDay($request) {
        $oeeDay = new OeeDay();
        $start = new Carbon($request->startDate);
        $end = new Carbon($request->endDate);

        if($request->machineId != 0) {
            $oeeDay = $oeeDay->where('Master_Machine_ID', $request->machineId);
        }

        $oeeDay = $oeeDay->where('Time_Created', '>=', $start->add(8, 'hours'))
                         ->where('Time_Updated', '<=', $end->add(1, 'day')->add(8, 'hours')->sub(1, 'second'));

        $oeeDay = $oeeDay->get()->groupBy(function($item) {
            return substr($item->Time_Created, 0, 10);
        });

        $oeeDay = $oeeDay->map(function($item, $key) {
            return [
                'Oee' => round($item->avg('Oee'), 2),
                'A' => round($item->avg('A'), 2),
                'P' => round($item->avg('P'), 2),
                'Q' => round($item->avg('Q'), 2),
                'Date' => $key
            ];
        });

        return $oeeDay;
    }

    public function oeeReportByMachine($request) {
        $oeeDay = new OeeDay();
        $start = new Carbon($request->startDate);
        $end = new Carbon($request->endDate);

        if($request->machineId[0] != 0) {
            $oeeDay = $oeeDay->whereIn('Master_Machine_ID', $request->machineId);
        }

        $oeeDay = $oeeDay->where('Oee_Day.Time_Created', '>=', $start->add(8, 'hours'))
                         ->where('Oee_Day.Time_Updated', '<=', $end->add(1, 'day')->add(8, 'hours')->sub(1, 'second'));

        $oeeDay = $oeeDay->join('Master_Machine', 'Oee_Day.Master_Machine_ID', '=', 'Master_Machine.ID')
                         ->select('Oee_Day.Oee', 'Oee_Day.A', 'Oee_Day.P', 'Oee_Day.Q', 'Master_Machine.Name as Machine_Name')
                         ->get()
                         ->groupBy('Machine_Name');

        $oeeDay = $oeeDay->map(function($item, $key) {
            return [
                'Oee' => round($item->avg('Oee'), 2),
                'A' => round($item->avg('A'), 2),
                'P' => round($item->avg('P'), 2),
                'Q' => round($item->avg('Q'), 2),
                'Machine_Name' => $key
            ];
        });

        return $oeeDay;
    }

    public function productDefectiveReport($request) {
        $start = new Carbon($request->startDate);
        $end = new Carbon($request->endDate);
        $productDefective = new ProductDefectiveLog();

        if($request->machineId[0] != 0) {
            $productDefective = $productDefective->whereIn('Product_Defective_Log.Master_Machine_ID', $request->machineId);
        }

        $productDefective = $productDefective->where('Product_Defective_Log.Time_Created', '>=', $start->add(8, 'hours'))
                                             ->where('Product_Defective_Log.Time_Updated', '<=', $end->add(1, 'day')->add(8, 'hours')->sub(1, 'second'));

        $productDefective = $productDefective->join('Master_Machine', 'Product_Defective_Log.Master_Machine_ID', '=', 'Master_Machine.ID')
                                             ->select('Product_Defective_Log.Quantity', 'Product_Defective_Log.Time_Created', 'Master_Machine.Name as Machine_Name')
                                             ->get();

        $processedProductDefective = $productDefective->groupBy('Machine_Name')->map(function($item, $key) {
            return $item->sum('Quantity');
        });

        return [
            'productDefective' => $productDefective,
            'processedProductDefective' => $processedProductDefective
        ];
    }

    public function productDefectiveReportByLine($request) {
        $start = new Carbon($request->startDate);
        $end = new Carbon($request->endDate);
        $lineId = $request->lineId;
        $machineId = $request->machineId;
        $productDefective = new ProductDefectiveLog();

        $productDefective = $productDefective->where('Product_Defective_Log.Time_Created', '>=', $start->add(8, 'hours'))
                                             ->where('Product_Defective_Log.Time_Updated', '<=', $end->add(1, 'day')->add(8, 'hours')->sub(1, 'second'));

        $productDefective = $productDefective->join('Master_Machine', 'Product_Defective_Log.Master_Machine_ID', '=', 'Master_Machine.ID')
                                             ->join('Production_Log', 'Product_Defective_Log.Production_Log_ID', '=', 'Production_Log.ID')
                                             ->join('Master_Status', 'Production_Log.Master_Status_ID', '=', 'Master_Status.ID')
                                             ->where('Master_Machine.Line_ID', '=', $lineId)
                                             ->select('Product_Defective_Log.Quantity', 'Product_Defective_Log.Time_Created', 'Master_Machine.Name as Machine_Name',
                                                      'Master_Status.Name as Error_Name', 'Master_Status.ID as Error_ID');
        if($machineId)
        {
            $machineIdArray = explode (",", $machineId);
            $productDefective = $productDefective
                ->whereIn('Master_Machine.ID', $machineIdArray);
        }

        $productDefective = $productDefective -> get();
        $processedProductDefective = $productDefective->groupBy(['Machine_Name'])->map(function($item, $key) {
            return $item->sum('Quantity');
        })->sortDesc();

        $category = $processedProductDefective->keys();
        $changeMold = array();
        $burr = array();
        $dim = array();
        $shape = array();
        $IBUTSU = array();
        $others = array();

        foreach($category as $machineName) {
            $items = $productDefective->where('Machine_Name','=', $machineName)->groupBy(['Error_Name'])->map(function($item, $key) {
                return $item->sum('Quantity');
            });

            $changeMold[] = $items->has('SHORT MOLD') ? $items['SHORT MOLD'] : 0;
            $burr[] = $items->has('BURR') ? $items['BURR'] : 0;
            $dim[] = $items->has('DIM') ? $items['DIM'] : 0;
            $shape[] = $items->has('SHAPE CHANGE') ? $items['SHAPE CHANGE'] : 0;
            $IBUTSU[] = $items->has('IBUTSU') ? $items['IBUTSU'] : 0;
            $others[] = $items->has('Others') ? $items['Others'] : 0;
        }
        return [
            'chartBarData' => [
               'category' => $category,
               'mold' => $changeMold,
               'burr' => $burr,
               'dim' => $dim,
               'shape' => $shape,
               'IBUTSU' => $IBUTSU,
               'others' => $others
            ],
            'processedProductDefective' => $processedProductDefective
        ];
    }

    public function errorAndNotError($request) {
        $start = new Carbon($request->startDate);
        $end = new Carbon($request->endDate);
        $runtimeHistory = RuntimeHistory::where('IsRunning', 0)
                        ->where('Master_Status_ID', ">=", 1)->where('Master_Status_ID', "<=", 10);

        if($request->machineId[0] != 0) {
            $runtimeHistory = $runtimeHistory->whereIn('Master_Machine_ID', $request->machineId);
        }

        $runtimeHistory = $runtimeHistory->where('Time_Created', '>=', $start->add(8, 'hours'))
                                         ->where('Time_Updated', '<=', $end->add(1, 'day')->add(8, 'hours')->sub(1, 'second'));

        $runtimeHistory = $runtimeHistory->get()->groupBy(function($item) {
            $machineStatus = $item->Master_Status_ID;

            if($machineStatus >= 7 && $machineStatus <= 10) {
                return 'stopNotError';
            } elseif( $machineStatus >= 1 && $machineStatus <= 6 ) {
                return 'stopError';
            }
        });

        $runtimeHistory = $runtimeHistory->map(function($item, $key) {
            return $item->sum('Duration');
        });

        return $runtimeHistory;
    }

    public function machineError($request) {
        $start = new Carbon($request->startDate);
        $end = new Carbon($request->endDate);
        $runtimeHistory = RuntimeHistory::join('Master_Status', 'Runtime_History.Master_Status_ID', '=', 'Master_Status.ID')
                                        ->select('Runtime_History.*', 'Master_Status.Name as Master_Status')
                                        ->where('Master_Status_ID', ">=", 1)->where('Master_Status_ID', "<=", 6);

        if($request->machineId[0] != 0) {
            $runtimeHistory = $runtimeHistory->whereIn('Runtime_History.Master_Machine_ID', $request->machineId);
        }

        $runtimeHistory = $runtimeHistory->where('Runtime_History.Time_Created', '>=', $start->add(8, 'hours'))
                                         ->where('Runtime_History.Time_Updated', '<=', $end->add(1, 'day')->add(8, 'hours')->sub(1, 'second'));

        $runtimeHistory = $runtimeHistory->get()->groupBy('Master_Status')->map(function($item) {
            return $item->sum('Duration');
        });

        return $runtimeHistory;
    }

    public function stopNotError($request) {
        $start = new Carbon($request->startDate);
        $end = new Carbon($request->endDate);
        $runtimeHistory = RuntimeHistory::join('Master_Status', 'Runtime_History.Master_Status_ID', '=', 'Master_Status.ID')
                                        ->select('Runtime_History.*', 'Master_Status.Name as Master_Status')
                                        ->where('Master_Status_ID', ">=", 7)->where('Master_Status_ID', "<=", 10);

        if($request->machineId[0] != 0) {
            $runtimeHistory = $runtimeHistory->whereIn('Runtime_History.Master_Machine_ID', $request->machineId);
        }

        $runtimeHistory = $runtimeHistory->where('Runtime_History.Time_Created', '>=', $start->add(8, 'hours'))
                                         ->where('Runtime_History.Time_Updated', '<=', $end->add(1, 'day')->add(8, 'hours')->sub(1, 'second'));


        $runtimeHistory = $runtimeHistory->get()->groupBy(function($item) {
            return $item->Master_Status;
        });

        $runtimeHistory = $runtimeHistory->map(function($item) {
            return $item->sum('Duration');
        });

        return $runtimeHistory;
    }

    public function stopQuality($request) {
        $start = new Carbon($request->startDate);
        $end = new Carbon($request->endDate);
        $runtimeHistory = RuntimeHistory::join('Master_Status', 'Runtime_History.Master_Status_ID', '=', 'Master_Status.ID')
                                        ->select('Runtime_History.*', 'Master_Status.Name as Master_Status', 'Master_Status.Master_Status_Type_ID')
                                        ->where('IsRunning', 0)
                                        ->where('Master_Status_Type_ID', 3);

        if($request->machineId[0] != 0) {
            $runtimeHistory = $runtimeHistory->whereIn('Runtime_History.Master_Machine_ID', $request->machineId);
        }

        $runtimeHistory = $runtimeHistory->where('Runtime_History.Time_Created', '>=', $start->add(8, 'hours'))
                                         ->where('Runtime_History.Time_Updated', '<=', $end->add(1, 'day')->add(8, 'hours')->sub(1, 'second'));

        $runtimeHistory = $runtimeHistory->get()->groupBy('Master_Status')->map(function($item) {
            return $item->sum('Duration');
        });


        return $runtimeHistory;
    }

    public function stopReport($request) {
        $start = new Carbon($request->startDate);
        $end = new Carbon($request->endDate);
        $runtimeHistory = RuntimeHistory::join('Master_Machine', 'Runtime_History.Master_Machine_ID', '=', 'Master_Machine.ID')
                                        ->join('Master_Status', 'Runtime_History.Master_Status_ID', '=', 'Master_Status.ID')
                                        ->join('Master_Status_Type', 'Master_Status.Master_Status_Type_ID', '=', 'Master_Status_Type.ID')
                                        ->join('Master_Shift', 'Runtime_History.Master_Shift_ID', '=', 'Master_Shift.ID')
                                        ->select(
                                            'Runtime_History.*',
                                            'Master_Machine.Name as Machine_Name',
                                            'Master_Status.Name as Status_Name',
                                            'Master_Status_Type.Name as Status_Type',
                                            'Master_Shift.Name as Shift_Name',
                                            'Master_Shift.Start_Time as Shift_Start',
                                            'Master_Shift.End_Time as Shift_End',
                                        )
                                        ->where('Runtime_History.Master_Status_ID', ">=", 1)->where('Runtime_History.Master_Status_ID', "<=", 10);


        if($request->machineId[0] != 0) {
            $runtimeHistory = $runtimeHistory->whereIn('Runtime_History.Master_Machine_ID', $request->machineId);
        }

        $runtimeHistory = $runtimeHistory->where('Runtime_History.Time_Created', '>=', $start->add(8, 'hours'))
                                         ->where('Runtime_History.Time_Updated', '<=', $end->add(1, 'day')->add(8, 'hours')->sub(1, 'second'));

        $runtimeHistory = $runtimeHistory->get();

        $statisticData = $runtimeHistory->groupBy('Machine_Name')->map(function($item, $key) {
            return $item->sum('Duration');
        });

        return [
            'runtimeHistory' => $runtimeHistory,
            'statisticData' => $statisticData,
        ];
    }
}
