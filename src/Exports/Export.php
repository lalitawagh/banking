<?php

namespace Kanexy\Banking\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

class Export implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public $list;

    public $columnsValue;

    public $columnsHeading;

    public function styles(Worksheet $sheet)
    {
        return [
            '1' => ['font' => ['bold' => true]],
        ];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($list,$columnsValue,$columnsHeading)
    {
        $this->list = $list;
        $this->columnsValue = $columnsValue;
        $this->columnsHeading = $columnsHeading;
    }
    public function collection()
    {
        return  $this->list;
    }

    public function map($map): array
    {
        return $this->columnsValue;
    }

    public function headings(): array
    {
        return  $this->columnsHeading;
    }
}
