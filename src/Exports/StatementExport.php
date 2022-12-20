<?php

namespace Kanexy\Banking\Exports;

use Kanexy\Banking\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

class StatementExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
            $transaction = Transaction::with('workspace.account')->find($record);
            if ($transaction->type === 'debit') {
                $transaction['debit'] = \Kanexy\PartnerFoundation\Core\Helper::getFormatAmount($transaction->amount);
            } else {
                $transaction['debit'] =  '-';
            }
            if ($transaction->type === 'credit') {
                $transaction['credit'] = \Kanexy\PartnerFoundation\Core\Helper::getFormatAmount($transaction->amount);
            } else {
                $transaction['credit'] =  '-';
            }
            if ($transaction->type === 'debit') {
                $transaction['third_party'] = @$transaction->meta['beneficiary_name'];
            } else {
                $transaction['third_party'] =  @$transaction->meta['sender_name'];
            }
            $list->push($transaction);
        }

        return $list;
    }

    public function map($list): array
    {
        return [
            @$list->urn,
            @$list->third_party,
            $list->created_at,
            $list->workspace->account?->account_number,
            $list->debit,
            $list->credit,
            @$list->meta['reference'],
            $list->payment_method,
            $list->workspace->account?->balance,
        ];
    }

    public function headings(): array
    {
        return [
            'TRANSACTION ID',
            'THIRD PARTY',
            'DATE & TIME',
            'ACCOUNT NO',
            'DEBIT',
            'CREDIT',
            'REFERENCE',
            'PAYMENT METHOD',
            'BALANCE',
        ];
    }
}
