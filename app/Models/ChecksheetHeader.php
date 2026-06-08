<?php

namespace App\Models;

use App\Jobs\GenerateApprovalFlow;
use App\Traits\Blameable;
use App\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecksheetHeader extends Model
{
    use HasFactory, HasActivityLog, Blameable, SoftDeletes;

    protected $table = 'checksheet_headers';

    protected $fillable = [
        'code',
        'tanggal',
        'shift',
        'equipment_id',
        'leader_id',
        'remark',
        'is_active',
        'is_closed',
        'leader_approve',
        'leader_approve_by',
        'leader_approve_date',
        'spv_approve',
        'spv_approve_by',
        'spv_approve_date',
        'she_approve',
        'she_approve_by',
        'she_approve_date',
        'created_by',
        'updated_by'
    ];

    protected function casts()
    {
        return [
            'is_active' => 'boolean',
            'is_closed' => 'boolean',
            'leader_approve' => 'boolean',
            'spv_approve' => 'boolean',
            'she_approve' => 'boolean',
            'she_approve' => 'boolean',
            'leader_approve_date' => 'datetime',
            'spv_approve_date' => 'datetime',
            'she_approve_date' => 'datetime',
        ];
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function leaderApprove(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_approve_by');
    }

    public function spvApprove(): BelongsTo
    {
        return $this->belongsTo(User::class, 'spv_approve_by');
    }

    public function sheApprove(): BelongsTo
    {
        return $this->belongsTo(User::class, 'she_approve_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ChecksheetDetail::class, 'header_id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $prefix = 'CS-' . now()->format('Ymd') . '-';

            $latestRecord = static::where('code', 'like', $prefix . '%')
                ->withTrashed()
                ->orderBy('id', 'desc')
                ->first();

            if ($latestRecord) {
                $latestNumber = (int) substr($latestRecord->code, '-8');
                $nextNumber = $latestNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $model->code = $prefix . str_pad($nextNumber, 8, '0', STR_PAD_LEFT);
        });

        static::created(function ($model) {
            GenerateApprovalFlow::dispatch($model);
        });
    }
}
