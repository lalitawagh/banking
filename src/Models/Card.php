<?php

namespace Kanexy\Banking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kanexy\PartnerFoundation\Core\Models\Address;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Card extends Model
{
    use HasFactory, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Cards')->logOnly(['*'])->logOnlyDirty();
        // Chain fluent methods for configuration options
    }
}
