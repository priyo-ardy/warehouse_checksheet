<?php

namespace App\Filament\Pages\Report;

use App\Exports\ChecksheetReportExport;
use App\Models\ChecksheetHeader;
use App\Models\Equipment;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use UnitEnum;

class ChecksheetReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;
    protected static ?string $navigationLabel = 'Cheksheet Report';
    protected static string|UnitEnum|null $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.report.checksheet-report';

    public ?array $data = [];

    public array $reports = [];

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        DatePicker::make('dari_tanggal')
                            ->label('From Date')
                            ->date()
                            ->required()
                            ->maxDate(fn($get) => $get('sampai_tanggal'))
                            ->columnSpan(4),
                        DatePicker::make('sampai_tanggal')
                            ->label('To Date')
                            ->required()
                            ->date()
                            ->minDate(fn($get) => $get('dari_tanggal'))
                            ->columnSpan(4),
                        Select::make('equipment_id')
                            ->label('Equipment Name')
                            ->required()
                            ->options(function () {
                                return Equipment::where('is_active', true)->orderBy('name', 'asc')->pluck('name', 'id');
                            })
                            ->searchable()
                            ->searchPrompt('Type equipment name here ...')
                            ->native(false)
                            ->preload()
                            ->columnSpan(4)
                    ])
                    ->footerActions([
                        Action::make('generate')
                            ->label('Generate Report')
                            ->color('primary')
                            ->icon(Heroicon::OutlinedCog8Tooth)
                            ->tooltip('Generate report')
                            ->action(fn() => $this->generateReport())
                    ])
                    ->columns(12)
                    ->columnSpanFull()
            ])
            ->statePath('data');
    }

    public function generateReport()
    {
        $state = $this->form->getState();
        $dari_tanggal = $state['dari_tanggal'];
        $sampai_tanggal = $state['sampai_tanggal'];
        $equipment_id = $state['equipment_id'];

        $headers = DB::table('checksheet_headers')
            ->where('doc_status', 'approved')
            ->where('equipment_id', $equipment_id)
            ->whereBetween('tanggal', [$dari_tanggal, $sampai_tanggal])
            ->get();

        if ($headers->isEmpty()) {
            $this->reports = [];
            \Filament\Notifications\Notification::make()
                ->title('Data tidak ditemukan atau belum approved pada tanggal tersebut.')
                ->warning()
                ->send();
            return;
        }

        $details = DB::table('checksheet_details')
            ->whereIn('header_id', $headers->pluck('id'))
            ->orderBy('order', 'asc')
            ->get();

        $equipmentName = DB::table('equipment')->where('id', $equipment_id)->value('name') ?? 'Equipment #' . $equipment_id;

        // --- PEMETAAN DATA APPROVAL, OPERATOR, & KETERANGAN PER HARI ---
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $datesOfWeek = [];
        $operatorsOfWeek = [];
        $leadersOfWeek = [];
        $leaderDateofWeeks = [];
        $spvsOfWeek = [];
        $spvDateofWeeks = [];
        $keteranganOfWeek = []; // Penampung baru untuk keterangan per hari

        foreach ($daysOfWeek as $index => $dayName) {
            $datesOfWeek[$dayName] = date('d/m/Y', strtotime($dari_tanggal . " +$index days"));

            // Cari header spesifik hari ini menggunakan Carbon agar akurat
            $headerForDay = $headers->first(function ($h) use ($dayName) {
                return \Illuminate\Support\Carbon::parse($h->tanggal)->format('l') === $dayName;
            });

            if ($headerForDay) {
                // 1. PIC / Operator (created_by)
                $operatorId = $headerForDay->created_by ?? null;
                $operator = $operatorId ? DB::table('users')->find($operatorId) : null;
                $operatorsOfWeek[$dayName] = $operator ? $operator->name : '-';

                // 2. Leader Approve (leader_approve_by)
                $leaderId = $headerForDay->leader_approve_by ?? null;
                $leader = $leaderId ? DB::table('users')->find($leaderId) : null;
                $leadersOfWeek[$dayName] = $leader ? $leader->name : '-';
                $leaderDateofWeeks[$dayName] = $headerForDay->leader_approve_date ? date('d/m/Y H:i:s', strtotime($headerForDay->leader_approve_date)) : '-';

                // 3. SPV Approve (spv_approve_by)
                $spvId = $headerForDay->spv_approve_by ?? null;
                $spv = $spvId ? DB::table('users')->find($spvId) : null;
                $spvsOfWeek[$dayName] = $spv ? $spv->name : '-';
                $spvDateofWeeks[$dayName] = $headerForDay->spv_approve_date ? date('d/m/Y H:i:s', strtotime($headerForDay->spv_approve_date)) : '-';

                // 4. Ambil data Keterangan (Sesuaikan 'keterangan' dengan nama kolom asli di tabel checksheet_headers kamu, misal: 'notes' atau 'remark')
                $keteranganOfWeek[$dayName] = $headerForDay->keterangan ?? $headerForDay->notes ?? $headerForDay->remark ?? '-';
            } else {
                $operatorsOfWeek[$dayName] = '-';
                $leadersOfWeek[$dayName] = '-';
                $leaderDateofWeeks[$dayName] = '-';
                $spvsOfWeek[$dayName] = '-';
                $spvDateofWeeks[$dayName] = '-';
                $keteranganOfWeek[$dayName] = '-';
            }
        }

        // 5. SHE Approve (she_approve_by)
        $sheName = '-';
        $headerWithShe = $headers->whereNotNull('she_approve_by')->first();
        if ($headerWithShe) {
            $sheUser = DB::table('users')->find($headerWithShe->she_approve_by);
            $sheName = $sheUser ? $sheUser->name : '-';
        }

        // --- MAP DATA PARAMETER CHECKLIST ---
        $groupedDetails = $details->groupBy('parameter_name_snapshot');
        $itemRows = [];
        foreach ($groupedDetails as $itemName => $records) {
            $daysStatus = [];
            foreach ($daysOfWeek as $dayName) {
                $daysStatus[$dayName] = null;
                $matchedRecord = $records->first(function ($detail) use ($headers, $dayName) {
                    $associatedHeader = $headers->firstWhere('id', $detail->header_id);
                    return $associatedHeader ? \Illuminate\Support\Carbon::parse($associatedHeader->tanggal)->format('l') === $dayName : false;
                });

                if ($matchedRecord) {
                    $daysStatus[$dayName] = $matchedRecord->status;
                }
            }

            $itemRows[] = [
                'nama_item' => $itemName,
                'status_hari' => $daysStatus
            ];
        }

        // Masukkan hasil pemisahan data ke array utama
        $this->reports = [
            [
                'equipment_name' => $equipmentName,
                'items'          => $itemRows,
                'dates'          => $datesOfWeek,
                'operators'      => $operatorsOfWeek,
                'leaders'        => $leadersOfWeek,
                'leaders_date'   => $leaderDateofWeeks,
                'spvs'           => $spvsOfWeek,
                'spvs_date'      => $spvDateofWeeks,
                'she'            => $sheName,
                'keterangan_hari' => $keteranganOfWeek, // Dikirim ke Blade
            ]
        ];

        \Filament\Notifications\Notification::make()
            ->title('Success')
            ->body('Report generated successfully')
            ->success()
            ->send();
    }

    public function exportToExcel()
    {
        // Pastikan data report sudah di-generate dan tidak kosong sebelum dieksport
        if (empty($this->reports)) {
            \Filament\Notifications\Notification::make()
                ->title('Tidak ada data yang bisa diexport. Silakan klik Generate Report terlebih dahulu.')
                ->danger()
                ->send();
            return;
        }

        $filename = 'Checksheet_Report_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ChecksheetReportExport($this->reports), $filename);
    }
}
