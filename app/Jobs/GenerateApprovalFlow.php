<?php

namespace App\Jobs;

use App\Models\ChecksheetHeader;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class GenerateApprovalFlow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $header;
    /**
     * Create a new job instance.
     */
    public function __construct(ChecksheetHeader $header)
    {
        $this->header = $header;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $approvalData = [];

            if ($this->header->leader_id) {
                $approvalData[] = [
                    'document_id' => $this->header->id,
                    'order' => 1,
                    'approver' => $this->header->leader_id,
                    'last_approver' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $spv = User::where('user_type', 'spv')->where('is_active', true)->first();
            $she = User::where('user_type', 'she')->where('is_active', true)->first();

            if ($spv) {
                $approvalData[] = [
                    'document_id' => $this->header->id,
                    'order' => 2,
                    'approver' => $spv->id,
                    'last_approver' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if ($she) {
                $approvalData[] = [
                    'document_id' => $this->header->id,
                    'order' => 3,
                    'approver' => $she->id,
                    'last_approver' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (! empty($approvalData)) {
                DB::table('approvals')->insert($approvalData);
            }

            // --- LOG BERHASIL (SPATIE ACTIVITY LOG) ---
            activity()
                ->performedOn($this->header) // Log ini terikat ke model ChecksheetHeader terkait
                ->causedBy($this->header->created_by ?? null) // Aktor pemicunya (Operator yang buat checksheet)
                ->event('approval_flow_generated')
                ->withProperties([
                    'status' => 'success',
                    'approvals_count' => count($approvalData),
                ])
                ->log("Successfully generated approval flow for document: {$this->header->code}");
        } catch (Throwable $e) {
            // --- LOG ERROR/GAGAL (SPATIE ACTIVITY LOG) ---
            activity()
                ->performedOn($this->header)
                ->causedBy($this->header->created_by ?? null)
                ->event('approval_flow_failed')
                ->withProperties([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ])
                ->log("Failed to generate approval flow for document: {$this->header->code}");

            // WAJIB: Lempar ulang error-nya biar Laravel Queue tahu kalau job ini gagal
            throw $e;
        }
    }
}
