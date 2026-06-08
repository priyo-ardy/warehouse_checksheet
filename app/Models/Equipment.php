<?php

namespace App\Models;

use App\Traits\Blameable;
use App\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Equipment extends Model
{
    use HasFactory, HasActivityLog, Blameable, SoftDeletes;

    protected $fillable = [
        'equipment_category',
        'image',
        'name',
        'brand',
        'serial_no',
        'pic_id',
        'is_active',
        'remark',
        'created_by',
        'updated_by'
    ];

    protected function casts()
    {
        return [
            'is_active' => 'boolean'
        ];
    }

    public function leaders(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    protected static function booted()
    {
        static::forceDeleted(function ($model) {
            if ($model->image) {
                Storage::disk('public')->delete($model->image);
            }
        });
    }
}
