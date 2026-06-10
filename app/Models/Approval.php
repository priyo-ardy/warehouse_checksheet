<?php

namespace App\Models;

use App\Traits\Blameable;
use App\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Approval extends Model
{
    use HasFactory, HasActivityLog;
    protected $table = 'approvals';

    protected $fillable = [
        'document_id',
        'order',
        'status',
        'approver',
        'approved_date',
        'last_approver'
    ];

    protected function casts()
    {
        return parent::casts();
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(ChecksheetHeader::class, 'document_id');
    }

    public function approverUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver');
    }

    public function processApproval()
    {
        DB::transaction(function () {
            $user = $this->approverUser;
            $document = $this->document;

            if ($user && $document && in_array($user->user_type, ['leader', 'spv', 'she'])) {
                $type = $user->user_type;

                $documentData = [
                    "{$type}_approve" => true,
                    "{$type}_approve_by" => $user->id,
                    "{$type}_approve_date" => now(),
                ];

                if ($this->last_approver) {
                    $documentData['doc_status'] = 'approved';
                    $documentData['is_closed'] = true;
                }

                $document->update($documentData);
            }

            $this->update([
                'status' => true,
                'approved_date' => now(),
            ]);
        });
    }

    public function processReject(string $reason)
    {
        DB::transaction(function () use ($reason) {
            $user = $this->approverUser;
            $document = $this->document;

            if ($user && $document && in_array($user->user_type, ['leader', 'spv', 'she'])) {
                $type = $user->user_type;

                $document->update([
                    "doc_status" => 'rejected',
                    "rejected_by " => $user->id,
                    "rejected_date" => now(),
                    'rejected_reason' => $reason,
                ]);
            }

            $this->update([
                'status' => false,
                'approved_date' => now(),
            ]);

            self::where('document_id', $this->document_id)
                ->where('order', '>', $this->order)
                ->update([
                    'status' => false,
                    'approved_date' => now(),
                ]);
        });
    }
}
