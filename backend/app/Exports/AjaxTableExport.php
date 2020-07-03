<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;

class AjaxTableExport implements FromCollection, WithEvents, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize , WithTitle 
{
    private $models = "";
    private $totals = "";
    private $header = "";
    private $mapping = "";
    private $column_format = "";
    private $style = "";

    public function __construct($models, $header, $mapping, $column_format, $style = [],$totals)
    {
        $this->models = $models;
        $this->header = $header;
        $this->mapping = $mapping;
        $this->column_format = $column_format;
        $this->style = $style;
        $this->totals = $totals + 1;
    }

    public function title(): string
    {
        return 'Hasil Pemeriksaan';
    }

    public function headings(): array
    {
        return $this->header;
    }

    public function map($model): array
    {
        return ($this->mapping)($model);
    }

    public function array(): array
    {
        return $this->models;
    }

    public function collection()
    {
        return $this->models;
    }

    public function columnFormats(): array
    {
        return $this->column_format;
    }

    public function registerEvents(): array
    {
       return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ]
                ];
                $event->sheet->getDelegate()->getStyle("A1:W{$this->totals}")->applyFromArray($styleArray);
            },
        ];
        
    }
}
