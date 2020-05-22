<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;

class AjaxTableExport implements FromCollection, WithEvents, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    private $models = "";
    private $header = "";
    private $mapping = "";
    private $column_format = "";
    private $style = "";

    public function __construct($models, $header, $mapping, $column_format, $style = [])
    {
        $this->models = $models;
        $this->header = $header;
        $this->mapping = $mapping;
        $this->column_format = $column_format;
        $this->style = $style;
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
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });
        Sheet::macro('freezePane', function (Sheet $sheet, $pane) {
        $sheet->getDelegate()->getActiveSheet()->freezePane($pane);
        });

        if($this->style!='' && count($this->style)>0){
            return [
                AfterSheet::class => function(AfterSheet $event) {
                    $event->sheet->styleCells(
                        $this->style['cell'], $this->style['center']);
                    $event->sheet->freezePane($this->style['freeze']);
                },
            ];
        }
        else {
            return [];
        }
        
    }
}
