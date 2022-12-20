<?php

namespace Kanexy\Banking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountMeta extends Model
{
    use HasFactory;

    protected $table = 'account_meta';

    public $primaryKey="key";

    protected $fillable = [
        'key',
        'value',
        'account_id',
    ];
}
