<?php

namespace App\Http\Controllers\Api\Statistic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Oee\OeeDay;
use Carbon\Carbon;

use App\Models\Oee\ProductDefectiveLog;
use App\Models\Oee\RuntimeHistory;
use App\Business\Statistic;

class OeeReportController extends Controller
{
    public function __construct(Statistic $statistic) {
        $this->statistic = $statistic;
    }

    public function oeeReportByDay(Request $request) {
        return response()->json($this->statistic->oeeReportByDay($request));
    }

    public function oeeReportByMachine(Request $request) {
        return response()->json($this->statistic->oeeReportByMachine($request));
    }

    public function productDefectiveReport(Request $request) {
        return response()->json($this->statistic->productDefectiveReport($request));
    }

    public function productDefectiveReportByLine(Request $request) {
        return response()->json($this->statistic->productDefectiveReportByLine($request));
    }


    public function errorAndNotError(Request $request) {
        return response()->json($this->statistic->errorAndNotError($request));
    }

    public function machineError(Request $request) {
        return response()->json($this->statistic->machineError($request));
    }

    public function stopNotError(Request $request) {
        return response()->json($this->statistic->stopNotError($request));
    }

    public function stopQuality(Request $request) {
        return response()->json($this->statistic->stopQuality($request));
    }

    public function stopReport(Request $request) {
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $this->statistic->stopReport($request)
        ]);
    }

}
