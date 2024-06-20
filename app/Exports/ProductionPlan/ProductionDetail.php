<?php

namespace App\Exports\ProductionPlan;

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
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use App\Models\History\HistoriesImportFile;
use App\Models\ProductionPlan\CommandProductionDetail;
/**
 *
 */
class ProductionDetail
{

      // private function data_export_plan($request){
      //       $id = $request->ID;
      //       return $data = CommandProductionDetail::leftJoin('Master_Mold', 'Command_Production_Detail.Mold_ID', 'Master_Mold.ID')
      //       ->leftJoin('Master_Product', 'Command_Production_Detail.Product_ID', 'Master_Product.ID')
      //       ->leftJoin('Master_Materials', 'Command_Production_Detail.Materials_ID', 'Master_Materials.ID')
      //       ->leftJoin('Master_Machine', 'Command_Production_Detail.Part_Action', 'Master_Machine.ID')
      //       ->select(
      //             'Master_Mold.Symbols as Mold_Symbols',
      //             'Master_Product.Symbols as Product_Symbols',
      //             'Command_Production_Detail.Version',
      //             'Command_Production_Detail.His',
      //             'Master_Materials.Symbols as Materials_Symbols',
      //             'Master_Materials.Symbols as Materials_Symbols',
      //             'Command_Production_Detail.Time_Start',
      //             'Command_Production_Detail.Time_End',
      //             'Command_Production_Detail.Quantity_Production',
      //       )
      //       where('Command_ID', $id)->get()->toArray();
      // }
     public function export($plan,$data,$request)
	{
            $name       = 'khsx-thang-'.$request->ID.'-'. Carbon::now()->format('YmdHis');
            $fileType   = IOFactory::identify(public_path('template/master_shift_template.xlsx'));
            $loadFile   = IOFactory::createReader($fileType);
            $file       = $loadFile->load(public_path('template/master_shift_template.xlsx'));
            $sheet1     = $file->getSheetByName('Sheet1');
            $styleA3 = $sheet1->getStyle('A3');
            if(!$request->template)
            {
                $data_detail  = $this->data_export_plan($request);
                $numRows = count($data_detail);
                $dataRange = 'A3:D' . ($numRows + 2);
                $sheet1->duplicateStyle($styleA3,$dataRange);
                $sheet1->fromArray(
                    $data_detail,
                    null,
                    'A3'
                );
            }
            $sheet1->setAutoFilter('A2:D2');
            $writer = IOFactory::createWriter($file, 'Xlsx');
            $file_name = $name . '.' . strtolower('Xlsx');
            $writer->save($file_name);        
            ob_end_clean();
            header('Content-Type: application/x-www-form-urlencoded');
            header('Content-Transfer-Encoding: Binary');
            header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
            readfile($file_name);
            unlink($file_name);
            exit;
	}
}
