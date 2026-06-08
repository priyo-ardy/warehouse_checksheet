<?php

namespace App\Models;

use App\Traits\Blameable;
use App\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterChecksheetHeader extends Model
{
    use HasFactory, Blameable, HasActivityLog, SoftDeletes;

    protected $table = 'master_checksheet_headers';

    protected $fillable = [
        'equipment_category',
        'is_active',
        'remark',
        'created_by',
        'updated_by'
    ];

    protected function casts()
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(MasterChecksheetDetail::class, 'header_id');
    }
}
