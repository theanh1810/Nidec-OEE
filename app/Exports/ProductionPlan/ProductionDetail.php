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
/**
 *
 */
class ProductionDetail
{
     public function export($plan,$data,$request)
	{
            $file_up = HistoriesImportFile::where('ID_Main',$request->ID)->orderBy('Time_Created','desc')->first();
            if($file_up)
            {
                  $name = 'KHSX';
                  $file_name_up = $file_up->Folder.'\\'.$file_up->File;
                  $filePath = public_path($file_name_up);
                  $file_name = $name . '.' . strtolower('Xlsx');
                //   $fileType = IOFactory::identify(public_path($file_name_up));
                //   $loadFile = IOFactory::createReader($fileType);
                //   $file = $loadFile->load(public_path($file_name_up));

                //   $loadFile->setIncludeCharts(TRUE);
                //   $active_sheet = $file->getActiveSheet();
                //   $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file, 'Xlsx');
                //   $file_name = $name . '.' . strtolower('Xlsx');
                //   $writer->setIncludeCharts(true);
                //   $writer->save($file_name);
                  header('Content-Type: application/x-www-form-urlencoded');
                  header('Content-Transfer-Encoding: Binary');
                  header("Content-disposition: attachment; filename=\"".$file_name."\"");
                  readfile($filePath);
                  unlink($filePath);
            }

		exit;
	}
}
