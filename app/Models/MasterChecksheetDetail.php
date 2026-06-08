<?php

namespace App\Models;

use App\Traits\Blameable;
use App\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterChecksheetDetail extends Model
{
    use HasFactory, HasActivityLog, Blameable;

    protected $table = 'master_checksheet_details';

    protected $fillable = [
        'header_id',
        'order',
        'name',
        'created_by',
        'updated_by'
    ];

    public function header(): BelongsTo
    {
        return $this->belongsTo(MasterChecksheetHeader::class, 'header_id');
    }
}
