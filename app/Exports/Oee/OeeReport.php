<?php

namespace App\Exports\Oee;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class OeeReport
{
    private $datasheet = [];
    private $dataExport = [];
    private $col  = ['start' => 'A', 'end' => 'A'];
    private $row  = ['start' => 1, 'end' => 1];
    private $name = 'default.xlsx';
    private $title = [];
    private $path = '/export';

    private function defaultStyle() {
        return [
            'font' => [
                'name' => 'Arial',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setTitle($title) {
        $this->title = $title;
        array_push($this->dataExport, $title);
    }

    public function setDatasheet($datasheet) {
        $this->datasheet = $datasheet;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function export($getData) {
        foreach($this->datasheet as $data) {
            array_push($this->dataExport, $getData($data));
            $this->row["end"]++;
        }

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $sheet->fromArray($this->dataExport, NULL, $this->col["start"].$this->row["start"]);

        for($i = 1; $i < count($this->title); $i++) {
            $sheet->getColumnDimension($this->col["end"])
                  ->setAutoSize(true);
            $this->col["end"]++;
        }

        $sheet->getStyle($this->col["start"].$this->row["start"].':'.$this->col["end"].$this->row["end"])
              ->applyFromArray($this->defaultStyle());

        $sheet->getStyle($this->col["start"].$this->row["start"].':'.$this->col["end"].$this->row["start"])
              ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4caf50');
        
        $sheet->getStyle($this->col["start"].$this->row["start"].':'.$this->col["end"].$this->row["start"])
              ->getFont()->getColor()->setARGB('ffffff');

        $sheet->getStyle($this->col["start"].$this->row["start"].':'.$this->col["end"].$this->row["start"])
              ->getFont()->setBold(true);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save(public_path("{$this->path}/{$this->name}"));
    }

    public function getPath() {
        return public_path("{$this->path}/{$this->name}");
    }
}