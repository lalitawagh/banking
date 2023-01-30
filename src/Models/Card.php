<?php

namespace Kanexy\Banking\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Kanexy\PartnerFoundation\Core\Models\Address;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Kanexy\PartnerFoundation\Core\Enums\Permission;
use Kanexy\PartnerFoundation\Core\Exports\Export;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'type',
        'name',
        'mode',
        'status',
        'workspace_id',
        'billing_address_id',
        'delivery_address_id',
        'holder_type',
        'holder_id',
        'expiry_date',
        'design',
        'delivery_name',
        'delivery_method',
        'ref_type',
        'ref_id',
    ];

    public static function findOrFailByRef($refId)
    {
        return static::where('ref_id', $refId)->firstOrFail();
    }

    public function scopeForWorkspace($query, Workspace $workspace)
    {
        return $query->where('workspace_id', $workspace->getKey());
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class);
    }

    public function deliveryAddress()
    {
        return $this->belongsTo(Address::class);
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
            $cardlist = Card::find($record);
            $list->push($cardlist);

            $columnDetail = [
                $cardlist->name,
                $cardlist->mode,
                $cardlist->type,
                $cardlist->expiry_date,
                $cardlist->status,
            ];

            array_push($columnsValue, $columnDetail);
        }

        $columnsHeading = [
            'NAME',
            'MODE',
            'TYPE',
            'EXPIRY_DATE',
            'STATUS',
        ];

        return Excel::download(new Export($list, $columnsValue, $columnsHeading), 'card.' . $type . '');
    }
   
    public static function downloadPdf($records)
    {
        $cardslist = collect();
        foreach ($records as $record) {
            $cardslist->push(Card::find($record));
        }

        $account = auth()->user()->workspaces()->first()?->account()->first();
        $user = Auth::user();
        $view = PDF::loadView('banking::cards.cardpdf', compact('cardslist','account','user'))
            ->setPaper(array(0, 0, 700, 600), 'landscape')
            ->output();

        return response()->streamDownload(fn () => print($view), "cards.pdf");
    }

    public static function setBuilder($workspace_id,$type): Builder
    {
         if (!$workspace_id) {
            return Card::query()->latest();
         }

         return Card::query()->whereWorkspaceId($workspace_id)->latest();
    }

    public static function columns()
    {

        return [
            Column::make("Id", "id")->hideIf(true),

            Column::make("Name", "name")->format(function ($value) {
                return ucfirst($value);
            })
                ->sortable()
                ->searchable()
                ->secondaryHeaderFilter('name'),

            Column::make("Created At", "created_at")->hideIf(true),
            Column::make("Bank Code", "workspace.account.bank_code")->hideIf(true),
            Column::make("Bank Account", "workspace.account.account_number")->format(function ($value, $model) {
                return '<span>'.$model['workspace.account.bank_code'].'&nbsp;'. '/'.'&nbsp;' .$value.'</span>';
            })
                ->sortable()
                ->searchable()
                ->html()
                ->secondaryHeaderFilter('id'),

            Column::make("Mode", "mode")->format(function ($value) {
                return ucfirst($value);
            })
                ->searchable()
                ->sortable()
                ->secondaryHeaderFilter('mode'),

            Column::make("Type", "type")->format(function ($value) {
                return ucfirst($value);
            })
                ->searchable()
                ->sortable()
                ->secondaryHeaderFilter('type'),

            Column::make("Expiry Date", "expiry_date")->format(function ($value) {
                return ucfirst($value);
            })
                ->searchable()
                ->sortable()
                ->secondaryHeaderFilter('expiry_date'),

            Column::make("Type", "type")->hideIf(true)
                ->sortable()
                ->searchable()
                ->secondaryHeaderFilter('type'),

            Column::make("Status", "status")->format(function ($value) {
                return ucfirst($value);
            })
                ->searchable()
                ->secondaryHeaderFilter('status')
                ->sortable(),

            Column::make('Actions','id')->format(function($value, $model, $row) {
                $actions = [];
                if (\Illuminate\Support\Facades\Auth::user()->hasPermissionTo(Permission::CARD_APPROVE)){

                    $actions[] = ['icon' => '<i data-lucide="check" class="w-4 h-4 mr-2"></i>','isOverlay' => '0','route' => route('dashboard.cards.approve', $value),'action' => 'Approve'];
                }
                if (\Illuminate\Support\Facades\Auth::user()->hasPermissionTo(Permission::CARD_ACTIVATE)){

                    $actions[] = ['icon' => '<i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>','isOverlay' => '0','route' => route('dashboard.cards.activate', $value),'action' => 'Activate'];
                }
                if (\Illuminate\Support\Facades\Auth::user()->hasPermissionTo(Permission::CARD_CLOSE)){

                    $actions[] = ['icon' => '<i data-lucide="x" class="w-4 h-4 mr-2"></i>','isOverlay' => 'true','route' => "Livewire.emit('cardClose', $value)",'action' => 'Close'];
                }
                $actions[] = ['icon' => '<i data-lucide="eye" class="w-4 h-4 mr-2"></i>','isOverlay' => '0','route' => route('dashboard.cards.show', $value),'action' => 'Show'];

                return view('cms::livewire.datatable-actions', ['actions' => $actions])->withUser($row);
            })
            ->html(),

        ];
    }

    public static function setFilters()
    {
        return [
            SelectFilter::make('Status')
                ->options([
                    '' => 'All',
                    'active' => 'Active',
                    'requested' => 'Requested',
                ])
                ->filter(function (Builder $builder, string $value) {

                    $builder->where('status', $value);
                }),

            // DateFilter::make('Created at')->filter(function (Builder $builder, string $value) {
            //     $builder->whereDate('created_at', date('Y-m-d', strtotime($value)));
            // }),

            TextFilter::make('name')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                $builder->where('cards.name', 'like', '%' . $value . '%');
            }),
            TextFilter::make('type')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                $builder->where('cards.type', 'like', '%' . $value . '%');
            }),
            TextFilter::make('mode')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                $builder->where('cards.mode', 'like', '%' . $value . '%');
            }),
            // TextFilter::make('meta->beneficiary_name')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
            //     $builder->where('transactions.meta->beneficiary_name', 'like', '%' . $value . '%');
            // }),
            // TextFilter::make('meta->reference')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
            //     $builder->where('transactions.meta->reference', 'like', '%' . $value . '%');
            // }),
            // TextFilter::make('amount')->hiddenFromAll()->config(['placeholder' => 'Search'])->filter(function (Builder $builder, string $value) {
            //         $builder->where('transactions.amount', '=',floatval($value));
            // }),

            // TextFilter::make('workspace_id')->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
            //     $builder->where('transactions.workspace_id', 'like', '%' . $value . '%');
            // }),


        ];
    }

}
