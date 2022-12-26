<?php

namespace Kanexy\Banking\Exports;

use Kanexy\PartnerFoundation\Core\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

class TransactionExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function styles(Worksheet $sheet)
    {
        return [
            '1' => ['font' => ['bold' => true]],
        ];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($records)
    {
        $this->records = $records;
    }
    public function collection()
    {
        $list = collect();
        foreach ($this->records as $record) {
            $transaction = Transaction::find($record);
            $list->push($transaction);
        }

        return $list;
    }
    public function map($list): array
    {
        return [
            $list->urn,
            $list->payment_method,
            @$list->meta['sender_name'],
            @$list->meta['beneficiary_name'],
            @$list->meta['reference'],
            $list->amount,
            $list->status,
            $list->created_at,
        ];
    }
    public function headings(): array
    {
        return [
            'TRANSACTION ID',
            'SOURCE',
            'SENDER NAME',
            'RECEIVER NAME',
            'REFERENCE',
            'AMOUNT',
            'STATUS',
            'DATE & TIME',
        ];
    }
}
