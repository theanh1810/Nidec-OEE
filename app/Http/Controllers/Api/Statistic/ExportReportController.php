<?php

namespace App\Http\Controllers\Api\Statistic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\Oee\OeeReport;
use App\Business\Statistic;
use Carbon\Carbon;

class ExportReportController extends Controller
{
    public function __construct(OeeReport $exportReport, Statistic $statistic) {
        $this->exportReport = $exportReport;
        $this->statistic = $statistic;
    }

    public function oeeReportByDay(Request $request) {
        $start = new Carbon($request->startDate);
        $end = new Carbon($request->endDate);
        $name = "Oee_Report_By_Day({$start->format('d_m_Y')}-{$end->format('d_m_Y')}).xlsx";
        $path = "/export/oee-by-day";

        $datasheet = $this->statistic->oeeReportByDay($request);

        $this->exportReport->setName($name);
        $this->exportReport->setPath($path);
        $this->exportReport->setTitle([ 'Date', 'A', 'P', 'Q', 'OEE' ]);
        $this->exportReport->setDatasheet($datasheet);
        $this->exportReport->export(function($data) {
            return [
                $data["Date"],
                $data["A"],
                $data["P"],
                $data["Q"],
                $data["Oee"],
            ];
        });

        return response()->download($this->exportReport->getPath(), $name, ['file-name' => $name]);
    }

    public function oeeReportByMachine(Request $request) {
        $start = new Carbon($request->startDate);
        $end = new Carbon($request->endDate);
        $name = "Oee_Report_By_Machine({$start->format('d_m_Y')}-{$end->format('d_m_Y')}).xlsx";
        $path = "/export/oee-by-machine";
        $datasheet = $this->statistic->oeeReportByMachine($request);

        $this->exportReport->setName($name);
        $this->exportReport->setPath($path);
        $this->exportReport->setTitle([ 'Machine', 'A', 'P', 'Q', 'OEE' ]);
        $this->exportReport->setDatasheet($datasheet);
        $this->exportReport->export(function($data) {
            return [
                $data["Machine_Name"],
                $data["A"],
                $data["P"],
                $data["Q"],
                $data["Oee"],
            ];
        });

        return response()->download($this->exportReport->getPath(), $name, ['file-name' => $name]);
    }

    public function stopReport(Request $request) {
        $start = new Carbon($request->startDate);
        $end = new Carbon($request->endDate);
        $name = "Machine_Stop_Report({$start->format('d_m_Y')}-{$end->format('d_m_Y')}).xlsx";
        $path = "/export/machine-stop";
        $datasheet = $this->statistic->stopReport($request);

        $this->exportReport->setName($name);
        $this->exportReport->setPath($path);
        $this->exportReport->setTitle([ 'Date', 'Machine', 'Stop Time', 'Error Code', 'Error Type' ]);
        $this->exportReport->setDatasheet($datasheet['runtimeHistory']);
        $this->exportReport->export(function($data) {
            return [
                $data["Time_Created"],
                $data["Machine_Name"],
                $data["Duration"],
                $data["Status_Name"],
                $data["Status_Type"]
            ];
        });

        return response()->download($this->exportReport->getPath(), $name, ['file-name' => $name]);
    }

    public function productDefectiveReport(Request $request) {
        $start = new Carbon($request->startDate);
        $end = new Carbon($request->endDate);
        $name = "Product_Defective_Report({$start->format('d_m_Y')}-{$end->format('d_m_Y')}).xlsx";
        $path = "/export/product-defective";
        $datasheet = $this->statistic->productDefectiveReport($request);

        $this->exportReport->setName($name);
        $this->exportReport->setPath($path);
        $this->exportReport->setTitle([ 'Date', 'Machine', 'Quantity Error' ]);
        $this->exportReport->setDatasheet($datasheet['productDefective']);
        $this->exportReport->export(function($data) {
            return [
                $data["Time_Created"],
                $data["Machine_Name"],
                $data["Quantity"]
            ];
        });

        return response()->download($this->exportReport->getPath(), $name, ['file-name' => $name]);
    }
}
