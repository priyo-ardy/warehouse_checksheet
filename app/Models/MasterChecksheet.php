<?php

namespace App\Models;

use App\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterChecksheet extends Model
{
    use HasFactory, HasActivityLog, SoftDeletes;

    protected $table = 'm_checksheet';

    protected $fillable = [
        'name',
        'category',
        'is_active',
        'remark',
        'created_by',
        'updated_by'
    ];

    protected function casts()
    {
        return [
            'category' => 'integer',
            'is_active' => 'boolean'
        ];
    }
}
