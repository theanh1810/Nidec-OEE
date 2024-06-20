<?php

namespace App\Exports\MasterData;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;
use App\Models\MasterData\MasterMachine;
use App\Models\MasterData\MasterBOM;
use App\Models\MasterData\MasterProduct;
use DB;
class MasterDataExport
{
    public function data_master_machine($request)
    {        
        $fill_symbols   = $request->symbols;
        return MasterMachine::select('Master_Machine.ID', 'Symbols', 'Stock_Min', 'Master_Line.Name' , 'Master_Machine.Note')
        ->leftJoin('Master_Line', 'Master_Machine.Line_ID', 'Master_Line.ID')
        ->when($fill_symbols, function ($query) use ($fill_symbols) {
                return $query->where('symbols', $fill_symbols);
            })
        ->where('Master_Machine.IsDelete', 0)           
        ->get()->toArray();
    }

    public function machine_export_file_excel($request)
    {
        $name       = 'master-machine-' . Carbon::now()->format('YmdHis');
        $fileType   = IOFactory::identify(public_path('template/excels/master-machine-template.xlsx'));
        $loadFile   = IOFactory::createReader($fileType);
        $file       = $loadFile->load(public_path('template/excels/master-machine-template.xlsx'));
        $sheet1     = $file->getSheetByName('Sheet1');
        $styleA3 = $sheet1->getStyle('A3');
        if(!$request->template)
        {
            $data_detail  = $this->data_master_machine($request);
            $numRows = count($data_detail);
            $dataRange = 'A3:E' . ($numRows + 2);
            $sheet1->duplicateStyle($styleA3,$dataRange);
            $sheet1->fromArray(
                $data_detail,
                null,
                'A3'
            );
        }
        $sheet1->setAutoFilter('A2:E2');
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

    public function data_master_product($request)
    {        
        $fill_symbols   = $request->symbols;
        return MasterProduct::leftJoin('Master_BOM', 'Master_Product.ID', 'Master_BOM.Product_BOM_ID')
        ->leftJoin('Master_Materials', 'Master_BOM.Materials_ID', 'Master_Materials.ID')
        ->leftJoin('Master_Mold', 'Master_BOM.Mold_ID', 'Master_Mold.ID')
        ->select(
            'Master_Product.ID', 
            'Master_Product.Symbols as Pro_Symbols', 
            'Master_Materials.Symbols as Materials_Symbols', 
            'Quantity_Material', 
            'Master_Mold.Symbols as Mold_Symbols', 
            'Cavity', 
            'Cycle_Time', 
            'Master_Product.Note')
        ->when($fill_symbols, function ($query) use ($fill_symbols) {
                return $query->where('Master_Product.Symbols', $fill_symbols);
            })
        ->where('Master_Product.IsDelete', 0)           
        ->get()->toArray();
    }

    public function product_export_file_excel($request)
    {
        $name       = 'master-product-' . Carbon::now()->format('YmdHis');
        $fileType   = IOFactory::identify(public_path('template/excels/master-product-template.xlsx'));
        $loadFile   = IOFactory::createReader($fileType);
        $file       = $loadFile->load(public_path('template/excels/master-product-template.xlsx'));
        $sheet1     = $file->getSheetByName('Sheet1');
        $styleA3 = $sheet1->getStyle('A3');
        if(!$request->template)
        {   
            $data_detail  = $this->data_master_product($request);
            $numRows = count($data_detail);
            $dataRange = 'A3:H' . ($numRows + 2);
            $sheet1->duplicateStyle($styleA3,$dataRange);
            $sheet1->fromArray(
                $data_detail,
                null,
                'A3'
            );
        }
        $sheet1->setAutoFilter('A2:H2');
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

