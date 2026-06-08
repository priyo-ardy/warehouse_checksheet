<?php

namespace App\Models;

use App\Traits\Blameable;
use App\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecksheetDetail extends Model
{
    use HasFactory, Blameable, HasActivityLog;

    protected $fillable = [
        'header_id',
        'order',
        'item_id',
        'parameter_name_snapshot',
        'status',
        'actual_value',
        'remark',
        'created_by',
        'updated_by'
    ];
}
