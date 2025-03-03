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

    public static function setArchived()
    {
        return false;
    }

    public static function setUnArchived()
    {
        return false;
    }

    public static function setRecordsToDownload($records, $type)
    {
        $list = collect();

        foreach ($records as $record) {
            $cardlist = Card::find($record);

            $columnDetail = [
                $cardlist->name,
                $cardlist->mode,
                $cardlist->type,
                $cardlist->expiry_date,
                $cardlist->status,
            ];

            $list->push($columnDetail);
        }

        $columnsHeading = [
            'NAME',
            'MODE',
            'TYPE',
            'EXPIRY_DATE',
            'STATUS',
        ];

        return Excel::download(new Export($list, $columnsHeading), 'card.' . $type . '');
    }

    public static function downloadPdf($records)
    {
        $cardslist = collect();
        foreach ($records as $record) {
            $cardslist->push(Card::find($record));
        }

        $account = auth()->user()->workspaces()->first()?->account()->first();
        $user = Auth::user();
        $view = PDF::loadView('banking::cards.cardpdf', compact('cardslist', 'account', 'user'))
            ->setPaper(array(0, 0, 700, 600), 'landscape')
            ->output();

        return response()->streamDownload(fn () => print($view), "cards.pdf");
    }

    public static function setBuilder($workspace_id, $type): Builder
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
                return '<span>' . $model['workspace.account.bank_code'] . '&nbsp;' . '/' . '&nbsp;' . $value . '</span>';
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

            Column::make('Actions', 'id')->format(function ($value, $model, $row) {
                $actions = [];
                if (\Illuminate\Support\Facades\Auth::user()->hasPermissionTo(Permission::CARD_APPROVE)) {

                    $actions[] = ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check" data-lucide="check" class="lucide lucide-check w-4 h-4 mr-2"><polyline points="20 6 9 17 4 12"></polyline></svg>', 'isOverlay' => '0', 'method' => 'GET', 'route' => route('dashboard.cards.approve', $value), 'action' => 'Approve'];
                }
                if (\Illuminate\Support\Facades\Auth::user()->hasPermissionTo(Permission::CARD_ACTIVATE)) {

                    $actions[] = ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-circle" data-lucide="check-circle" class="lucide lucide-check-circle w-4 h-4 mr-2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>', 'isOverlay' => '0', 'method' => 'GET', 'route' => route('dashboard.cards.activate', $value), 'action' => 'Activate'];
                }
                if (\Illuminate\Support\Facades\Auth::user()->hasPermissionTo(Permission::CARD_CLOSE)) {

                    $actions[] = ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" data-lucide="x" class="lucide lucide-x w-4 h-4 mr-2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>', 'isOverlay' => 'true', 'method' => 'GET', 'route' => "Livewire.emit('cardClose', $value)", 'action' => 'Close'];
                }
                $actions[] = ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="eye" data-lucide="eye" class="lucide lucide-eye w-4 h-4 mr-2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>', 'isOverlay' => '0', 'method' => 'GET', 'route' => route('dashboard.cards.show', $value), 'action' => 'Show'];
                if (\Illuminate\Support\Facades\Auth::user()->isSubscriber()) {
                    $actions[] = ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" data-lucide="x" class="lucide lucide-x w-4 h-4 mr-2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>', 'isOverlay' => '0', 'method' => 'GET', 'route' => route('dashboard.cards.request-close', $value), 'action' => 'Request Close'];
                }
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

                    $builder->where('cards.status', $value);
                }),


            TextFilter::make('name')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                $builder->where('cards.name', 'like', '%' . $value . '%');
            }),
            TextFilter::make('type')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                $builder->where('cards.type', 'like', '%' . $value . '%');
            }),
            TextFilter::make('mode')->hiddenFromAll()->config(['placeholder' => 'Search', 'maxlength' => '25',])->filter(function (Builder $builder, string $value) {
                $builder->where('cards.mode', 'like', '%' . $value . '%');
            }),



        ];
    }
}
