<?php

namespace Kanexy\Banking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kanexy\PartnerFoundation\Membership\Models\ConfigurationSetting;
use Kanexy\PartnerFoundation\Workspace\Models\LedgerVerification;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Account extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'holder_id',
        'holder_type',
        'account_number',
        'balance',
        'iban_number',
        'iban_status',
        'bic_swift',
        'partner_product',
        'type',
        'status',
        'ref_id',
        'ref_type',
        'ledger_id',
        'partner_type',
        'partner_id',
        'bank_code',
        'bank_code_type',
        'country_code',
    ];

    protected $casts = [
        'balance' => 'float',
    ];

    public static function findOrFailByRef($refId)
    {
        return self::where('ref_id', $refId)->firstOrFail();
    }

    public function holder()
    {
        return $this->morphTo();
    }

    public function scopeForHolder($query, Model $model)
    {
        return $query->where(['holder_id' => $model->getKey(), 'holder_type' => $model->getMorphClass()]);
    }

    public function creditAmount(Transaction $transaction)
    {
        $this->update(['balance' => $this->balance + $transaction->amount]);
    }

    public function debitAmount(Transaction $transaction)
    {
        $this->update(['balance' => $this->balance - $transaction->amount]);
    }

    public function updateBalance(Transaction $transaction)
    {
        if ($transaction->type === 'debit') {
            $this->debitAmount($transaction);
        } elseif ($transaction->type === 'credit') {
            $this->creditAmount($transaction);
        }
    }

    public function verifications()
    {
        return $this->hasMany(LedgerVerification::class, 'ledger_id', "id");
    }

    public function meta()
    {
        return $this->belongsToMany(Account::class, 'account_meta');
    }

    public function deactivate()
    {
        $this->update(['status' => 'inactive']);
    }

    public function cards()
    {
        return $this->hasMany(Card::class, "account_id");
    }

    public function workspaces()
    {
        return $this->belongsTo(Workspace::class, "holder_id")->with('admin');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Beneficiary')->logOnly(['*'])->logOnlyDirty();
        // Chain fluent methods for configuration options
    }
}
