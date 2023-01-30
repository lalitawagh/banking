<?php

namespace Kanexy\Banking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Traits\InteractsWithOneTimePassword;
use Kanexy\PartnerFoundation\Core\Models\Transaction;
use Kanexy\PartnerFoundation\Core\Traits\InteractsWithUrn;
use Kanexy\PartnerFoundation\Exports\Export;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Spatie\MediaLibrary\InteractsWithMedia;
use PDF;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;

class Statement extends Model
{
    use HasFactory, InteractsWithMedia, InteractsWithUrn, InteractsWithOneTimePassword, Notifiable;

    protected $table = 'transactions';

    protected $fillable = [
        'urn',
        'ref_id',
        'ref_type',
        'amount',
        'status',
        'reasons',
        'initiator_id',
        'initiator_type',
        'meta',
        'scheduled_at',
        'is_overdraft',
        'transaction_fee',
        'submitted_at',
        'settled_at',
        'type',
        'note',
        'attachment',
        'instructed_currency',
        'instructed_amount',
        'settled_currency',
        'settled_amount',
        'settlement_date',
        'payment_method',
        'workspace_id',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'submitted_at' => 'datetime',
        'settled_at' => 'datetime',
        'settlement_date' => 'date',
        'meta' => 'array',
        'reasons' => 'array',
        'amount' => 'float',
        'is_overdraft' => 'bool',
        'transaction_fee' => 'float',
    ];

    /**
     * Get the user that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_number');
    }

    public static function setPagination()
    {
        return true;
    }

    public static function setBulkActions()
    {
        return true;
    }

    public static function setRecordsToDownload($records, $type)
    {
        $list = collect();
        $columnsValue = [];

        foreach ($records as $record) {
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

            $columnDetail = [
                @$transaction->urn,
                @$transaction['third_party'],
                $transaction->created_at,
                $transaction->workspace->account?->account_number,
                $transaction['debit'],
                $transaction['credit'],
                @$transaction->meta['reference'],
                $transaction->payment_method,
                $transaction->workspace->account?->balance,
            ];

            array_push($columnsValue, $columnDetail);
        }

        $columnsHeading = [
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

        return Excel::download(new Export($list, $columnsValue, $columnsHeading), 'statement.' . $type . '');
    }

    public static function setBuilder($workspace_id, $type): Builder
    {
        if (!$workspace_id) {
            return Statement::query()->latest();
        }
        return Statement::query()->whereWorkspaceId($workspace_id)->latest();
    }

    public static function downloadPdf($records)
    {
        ini_set("memory_limit", "256M");
        $transactions = collect();
        foreach ($records as $record) {
            $transactions->push(Transaction::with('workspace.account')->find($record));
        }
        $account = auth()->user()->workspaces()->first()?->accounts()->first();
        $user = Auth::user();
        $view = PDF::loadView('banking::banking.statementpdf', compact('transactions', 'account', 'user'))
            ->setPaper(array(0, 0, 1000, 800), 'landscape')
            ->output();

        return response()->streamDownload(fn () => print($view), "statement.pdf");
    }

    public static function columns()
    {
        return [
            Column::make("Id", "id")->hideIf(true),

            Column::make("Transaction Id", "urn")
                ->sortable()
                ->searchable()
                ->secondaryHeaderFilter('urn'),

            Column::make("Third Party", "meta")->format(function ($value, $model) {
                if ($model->type === 'debit') {
                    return ucfirst(@$model->meta['beneficiary_name']);
                } else {
                    return ucfirst(@$model->meta['sender_name']);
                }
            })
                ->sortable()
                ->searchable()
                ->secondaryHeaderFilter('meta'),

            Column::make("Date & Time", "created_at")
                ->sortable()
                ->searchable()
                ->secondaryHeaderFilter('created_at'),

            Column::make("Account No", "workspace.account.account_number")
                ->sortable()
                ->searchable(),

            Column::make("Type", "type")->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make("Debit", "amount")->format(function ($value, $model) {
                if ($model->type === 'debit') {
                    return  '<span class="px-6 py-4 whitespace-nowrap text-sm text-right text-theme-6">' . \Kanexy\PartnerFoundation\Core\Helper::getFormatAmount($value) . '</span>';
                } else {
                    return '<span class="px-6 py-4 whitespace-nowrap text-sm text-right">' . '-' . '</span>';
                }
            })
                ->sortable()
                ->searchable()
                ->secondaryHeaderFilter('debit')
                ->html(),

            Column::make("Credit", "amount")->format(function ($value, $model) {
                if ($model->type === 'credit') {
                    return  '<span class="px-6 py-4 whitespace-nowrap text-sm text-right text-success">' . \Kanexy\PartnerFoundation\Core\Helper::getFormatAmount($value) . '</span>';
                } else {
                    return '<span class="px-6 py-4 whitespace-nowrap text-sm text-right">' . '-' . '</span>';
                }
            })
                ->sortable()
                ->searchable()
                ->secondaryHeaderFilter('credit')
                ->html(),

            Column::make("Reference", "meta->reference")
                ->searchable()
                ->secondaryHeaderFilter('meta->reference')
                ->sortable(),
            Column::make("Payment method", "payment_method")
                ->searchable()
                ->secondaryHeaderFilter('payment_method')
                ->sortable()
                ->format(function ($value) {
                    return ucfirst($value);
                }),
            Column::make("Balance", "workspace.account.balance")->format(function ($value) {
                return \Kanexy\PartnerFoundation\Core\Helper::getFormatAmount($value);
            })
                ->searchable()
                ->sortable()
                ->secondaryHeaderFilter('workspace.account.balance'),

        ];
    }

    public static function setFilters()
    {
        return [
            DateFilter::make('Created at')->filter(function (Builder $builder, string $value) {
                $builder->whereDate('transactions.created_at', date('Y-m-d', strtotime($value)));
            }),

            SelectFilter::make('Payment method')
                ->options([
                    '' => 'All',
                    'wallet' => 'Wallet',
                    'manual_transfer' => 'Manual Transfer',
                    'bank' => 'Bank',
                    'stripe' => 'Stripe',
                ])
                ->filter(function (Builder $builder, string $value) {

                    $builder->where('payment_method', $value);
                }),

            SelectFilter::make('Month')
                ->options([
                    '' => 'All',
                    '01' => 'Jan',
                    '02' => 'Feb',
                    '03' => 'Mar',
                    '04' => 'Apr',
                    '05' => 'May',
                    '06' => 'Jun',
                    '07' => 'Jul',
                    '08' => 'Aug',
                    '09' => 'Sep',
                    '10' => 'Oct',
                    '11' => 'Nov',
                    '12' => 'Dec',

                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->whereMonth('transactions.created_at', $value);
                }),
            SelectFilter::make('Year')
                ->options([
                    '' => 'All',
                    '2022' => '2022',
                    '2023' => '2023',
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->whereYear('transactions.created_at', $value);
                }),

            TextFilter::make('urn')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                $builder->where('transactions.urn', 'like', '%' . $value . '%');
            }),
            TextFilter::make('meta')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                if (!is_null($builder->where('transactions.meta->sender_name', 'like', '%' . $value . '%')->where('transactions.type', '=', 'credit'))) {
                    $builder->where('transactions.meta->sender_name', 'like', '%' . $value . '%')->where('transactions.type', '=', 'credit');
                } elseif (!is_null($builder->where('transactions.meta->beneficiary_name', 'like', '%' . $value . '%')->where('transactions.type', '=', 'debit'))) {
                    $builder->where('transactions.meta->beneficiary_name', 'like', '%' . $value . '%')->where('transactions.type', '=', 'debit');
                }
            }),
            TextFilter::make('meta->reference')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                $builder->where('transactions.meta->reference', 'like', '%' . $value . '%');
            }),
        ];
    }
}
