<?php

namespace Kanexy\Banking\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Helper;
use Kanexy\Cms\Livewire\DataTable;
use Kanexy\Cms\Setting\Models\Setting;
use Kanexy\Cms\Traits\InteractsWithOneTimePassword;
use Kanexy\Banking\Exports\TransactionExport;
use Kanexy\PartnerFoundation\Core\Models\Log;
use Kanexy\PartnerFoundation\Core\Traits\InteractsWithUrn;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use PDF;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;

class Transaction extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, InteractsWithUrn, InteractsWithOneTimePassword, Notifiable;

    protected $table_name = 'transactions';

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar');
    }

    public static function getUrnPrefix(): ?string
    {
        /**
         * The once method will ensure that we do not make call to database
         * everytime we're looking for transaction_prefix and we only look
         * for it in the database once for the request.
         */
        return once(fn () => Setting::getValue('transaction_prefix'));
    }

    public function getPhoneWithCountryCode(): string
    {
        return '+' . $this->initiator->country_code . Helper::normalizePhone($this->initiator->phone);
    }

    public function routeNotificationForTwilio()
    {
        return $this->getPhoneWithCountryCode();
    }

    public function scopeForCard($query, Card $card)
    {
        return $query->where('meta->card_id', $card->id);
    }

    public function scopeForWorkspace($query, Workspace $workspace)
    {
        return $query->where('workspace_id', $workspace->getKey());
    }

    public static function findOrFailByRef($refId)
    {
        return static::where('ref_id', $refId)->firstOrFail();
    }

    public function getLastProcessDateTime()
    {
        return $this->settled_at ?? $this->submitted_at ?? $this->created_at;
    }

    /**
     * Get the user that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Get the user that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->morphMany(Log::class, 'target')->latest();
    }

    public function applyfilter($workspace_id)
    {
        return $this->when($this->getAppliedFilterWithValue('workspace_id'), fn($query, $active) => $query->where('workspace_id',$workspace_id));
    }

    public static function setPagination()
    {
        return true;
    }

    public static function setBuilder($workspace_id,$type): Builder
    {
         if (!$workspace_id) {
            return Transaction::query()->where('payment_method','bank')->latest();
         }

         return Transaction::query()->where('payment_method','bank')->whereWorkspaceId($workspace_id)->latest();
    }

    public static function downloadExcel($records)
    {
        return Excel::download(new TransactionExport($records), 'transaction.xlsx');
    }

    public static function downloadCsv($records)
    {
        return Excel::download(new TransactionExport($records), 'transaction.csv');
    }

    public static function downloadPdf($records)
    {
        $transactions = collect();
        foreach ($records as $record) {
            $transactions->push(Transaction::find($record));
        }

        $account = auth()->user()->workspaces()->first()?->account()->first();
        $user = Auth::user();
        $view = PDF::loadView('partner-foundation::banking.transactionspdf', compact('transactions','account','user'))
            ->setPaper(array(0, 0, 1000, 800), 'landscape')
            ->output();

        return response()->streamDownload(fn () => print($view), "transactions.pdf");
    }

    public static function columns()
    {

        return [
            Column::make("Id", "id")->hideIf(true),
            Column::make("Workspace Id", "workspace_id") ->secondaryHeaderFilter('workspace_id')->hideIf(true),


            Column::make("Transaction Id", "urn")
                ->sortable()->format(function ($value, $model) {
                    return view('cms::livewire.datatable-link', ['user' => $value, 'overlay' => "Livewire.emit('showTransactionDetail', $model->id);Livewire.emit('showTransactionAttachment', $model->id );"]);
                })
                ->searchable()
                ->secondaryHeaderFilter('urn'),

            Column::make("Source", "payment_method")->format(function ($value) {
                return ucfirst($value);
            })
                ->sortable()
                ->searchable()
                ->secondaryHeaderFilter('payment_method'),

            Column::make("Sender name", "meta->sender_name")->format(function ($value) {
                return $value;
            })
                ->searchable()
                ->sortable()
                ->secondaryHeaderFilter('meta->sender_name'),

            Column::make("Receiver name", "meta->beneficiary_name")->format(function ($value) {
                return ucfirst($value);
            })
                ->searchable()
                ->sortable()
                ->secondaryHeaderFilter('meta->beneficiary_name'),

            Column::make("Reference", "meta->reference")->format(function ($value) {
                return ucfirst($value);
            })
                ->searchable()
                ->sortable()
                ->secondaryHeaderFilter('meta->reference'),

            Column::make("Type", "type")->hideIf(true)
                ->sortable()
                ->searchable()
                ->secondaryHeaderFilter('type'),

            Column::make("Amount", "amount")
                ->searchable()
                ->sortable()
                ->format(function ($amount, $model) {
                    if ($model->type == 'debit') {
                        return  '<span class="px-6 py-4 whitespace-nowrap text-sm text-right text-theme-6">' . \Kanexy\PartnerFoundation\Core\Helper::getFormatAmount($amount) . '</span>';
                    } else {
                        return '<span class="px-6 py-4 whitespace-nowrap text-sm text-right text-success">' . \Kanexy\PartnerFoundation\Core\Helper::getFormatAmount($amount) . '</span>';
                    }
                })
                ->secondaryHeaderFilter('amount')
                ->html(),
            Column::make("Status", "status")->format(function ($value) {
                return ucfirst($value);
            })
                ->searchable()
                ->secondaryHeaderFilter('status')
                ->sortable(),
            Column::make("Date & Time", "created_at")->format(function($value){
                return Carbon::parse($value)->format('d-m-Y  H:i');
            })
                ->secondaryHeaderFilter('created_at')
                ->sortable(),

        ];
    }

    public static function setFilters()
    {
        return [
            SelectFilter::make('Status')
                ->options([
                    '' => 'All',
                    'draft' => 'Draft',
                    'pending' => 'Pending',
                    'accepted' => 'Accepted',
                    'pending-confirmation' => 'Pending Confirmation',
                ])
                ->filter(function (Builder $builder, string $value) {

                    $builder->where('status', $value);
                }),

            DateFilter::make('Created at')->filter(function (Builder $builder, string $value) {
                $builder->whereDate('created_at', date('Y-m-d', strtotime($value)));
            }),

            TextFilter::make('urn')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                $builder->where('transactions.urn', 'like', '%' . $value . '%');
            }),
            TextFilter::make('payment_method')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                $builder->where('transactions.payment_method', 'like', '%' . $value . '%');
            }),
            TextFilter::make('meta->sender_name')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                $builder->where('transactions.meta->sender_name', 'like', '%' . $value . '%');
            }),
            TextFilter::make('meta->beneficiary_name')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                $builder->where('transactions.meta->beneficiary_name', 'like', '%' . $value . '%');
            }),
            TextFilter::make('meta->reference')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                $builder->where('transactions.meta->reference', 'like', '%' . $value . '%');
            }),
            TextFilter::make('amount')->hiddenFromAll()->config(['placeholder' => 'Search'])->filter(function (Builder $builder, string $value) {
                    $builder->where('transactions.amount', '=',floatval($value));
            }),

            TextFilter::make('workspace_id')->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                $builder->where('transactions.workspace_id', 'like', '%' . $value . '%');
            }),


        ];
    }
}
