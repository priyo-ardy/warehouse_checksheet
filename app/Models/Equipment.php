<?php

namespace App\Models;

use App\Traits\HasActivityLog;
use App\Traits\HasBlamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory, HasActivityLog, HasBlamable;

    protected $fillable = [
        'name',
        'brand',
        'serial_no',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected function casts()
    {
        return [
            'is_active' => 'boolean'
        ];
    }
}
