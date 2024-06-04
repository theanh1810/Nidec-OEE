<?php

namespace App\Exports\Maintance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;  
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\MasterData\MasterMold;
use App\Libraries\MasterData\MasterMoldLibraries;
use App\Models\Maintance\MaintanceDetail;
use App\Models\Maintance\CommandMaintance;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
/**
 * 
 */
class ListMoldExport
{
     public function export($data,$request)
	{
        $name = 'List Mold';
        $fileType = IOFactory::identify(public_path('template\excels\list_mold.xlsx'));
        //Load data
        $loadFile = IOFactory::createReader($fileType);
        $file = $loadFile->load(public_path('template\excels\list_mold.xlsx'));
        $loadFile->setIncludeCharts(TRUE);
        $active_sheet = $file->getActiveSheet();
        $count = 1; 
        $active_sheet->setCellValue('A' . $count, 'Tên Khuôn');
        $active_sheet->setCellValue('B' . $count, 'Mã Khuôn');
        $count = 22;
        $dem =0;
        $arr = [];
        $arr1 =[];
		foreach($data as $value)
		{	 
            $dem++;
            $active_sheet->setCellValue('A' . $count, $dem+10);
            $active_sheet->setCellValue('B' . $count, $value->Name);
            $active_sheet->setCellValue('C' . $count, $value->Symbols);
			$use = collect($value->detail)->sum('Quantity');
			$max = collect($value->group)->sum('Height_Use');
            $not = collect($value->group)->sum('Height_Not_Use');
            $active_sheet->setCellValue('F' . $count, $max - $use);
            $active_sheet->setCellValue('G' . $count, $not);
            $active_sheet->setCellValue('H' . $count, $max- $not);
            $active_sheet->setCellValue('I' . $count, '=H'.$count.'-J'.$count);
            $active_sheet->setCellValue('J' . $count, '=F'.$count.'-G'.$count);
            $active_sheet->setCellValue('L' . $count, $value->Height_Use);
            $active_sheet->setCellValue('M' . $count, '=J'.$count.'/L'.$count);
            $data1 =  CommandMaintance::where('IsDelete',0)->where('Mold_ID',$value->ID)
            ->where('Status',2)
            ->get();
            $row = '';
            $row1 = 'M';
            foreach($data1 as $value1)
            {
                if($row1 == 'Z')
                {
                    $row1 = 'A';
                    if($row == '')
                    {
                        $row = 'A';
                    }
                    else
                    {
                        $row++;
                    }
                }
                else
                {
                    $row1 ++;
                }
                $row2 = $row.''.$row1;
                $sum = collect($value1->detail)->sum('Quantity');
                $active_sheet->setCellValue($row2.'' . $count, $sum);
            }
            $arr1[count($data1)] = $row2;
           
            if($row1 == 'Z')
            {
                $row1 = 'A';
                if($row == '')
                {
                    $row = 'A';
                }
                else
                {
                    $row++;
                }
            }
            else
            {
                $row1 ++;
            }
            $row2 = $row.''.$row1;
            $arr[count($data1)] = $row2;
            $count = $count + 1;
		}
        $count1 = 22 ;
        $row4 = collect($arr1)->sortKeysDesc()->first();
        $active_sheet->setCellValue('N20', 'Mài định kỳ');
        $active_sheet->mergeCells("N20:".$row4.'21');
        $row3 = collect($arr)->sortKeysDesc()->first();
        $active_sheet->mergeCells($row3."20:".$row3.'21');
        $active_sheet->setCellValue($row3.'20', 'Tổng lượng mài');
        foreach($data as $value)
        {
            $active_sheet->setCellValue($row3.'' . $count1, '=sum(N'.$count1.':'.$row3.''.$count1.')');
            $count1++ ;
        }

        $dataSeriesLabels1 = [
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$F$22:$F$'.$count.'', null, 12), // Q1 to Q4
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$I$22:$I$'.$count.'', null, 12), // Q1 to Q4
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$J$22:$J$'.$count.'', null, 12), // Q1 to Q4
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1), // 2010
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$1', null, 1), // 2011
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$1', null, 1), // 2012
        ];
        $dataSeriesLabels2 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!I20', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!J20', null, 1),
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1), // 2010
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$1', null, 1), // 2011
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$1', null, 1), // 2012
        ];
        // Set the X-Axis Labels
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $xAxisTickValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$22:$B$'.($count-1).'', null,1), // Q1 to Q4
        ];
        // Set the Data values for each data series we want to plot
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $dataSeriesValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!I20', null, 4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!J20', null, 4),
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$D$2:$D$5', null, 4),
        ];
        
        // Build the dataseries
        $series1 = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            DataSeries::GROUPING_STACKED , // plotGrouping
            range(0, count($dataSeriesValues1) - 1), // plotOrder
            $dataSeriesLabels2, // plotLabel
            $xAxisTickValues1, // plotCategory
            $dataSeriesLabels1,// plotValues
        );
            
        // Set the series in the plot area
        $plotArea1 = new PlotArea(null, [$series1]);
        // Set the chart legend
        $legend1 = new Legend(Legend::POSITION_TOPRIGHT, null, false);
        $title1 = new Title('QUẢN LÝ TUỔI THỌ KHUÔN DẬP HÀNG N N枠　プレス金型寿命管理');
        $yAxisLabel1 = new Title('Micro met');
        // Create the chart
        $chart1 = new Chart(
            'chart1', // name
            $title1, // title
            $legend1, // legend
            $plotArea1, // plotArea
            true, // plotVisibleOnly
            DataSeries::EMPTY_AS_GAP, // displayBlanksAs
            null, // xAxisLabel
            $yAxisLabel1 // yAxisLabel
        );
        // Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('A1');
        $chart1->setBottomRightPosition('G18');
        // Add the chart to the worksheet
        $active_sheet->addChart($chart1);
        // dd(collect($arr)->sortKeysDesc()->first());
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file, 'Xlsx');
		$file_name = $name . '.' . strtolower('Xlsx');
        $writer->setIncludeCharts(true);
		$writer->save($file_name);
		header('Content-Type: application/x-www-form-urlencoded');
		header('Content-Transfer-Encoding: Binary');
		header("Content-disposition: attachment; filename=\"".$file_name."\"");
		readfile($file_name);
		unlink($file_name);
		exit;
	}
}