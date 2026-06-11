<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ChecksheetReportExport implements FromView, WithTitle, ShouldAutoSize
{
    protected array $reports;

    public function __construct(array $reports)
    {
        $this->reports = $reports;
    }

    public function view(): View
    {
        return view('exports.checksheet-report-excel', ['reports' => $this->reports]);
    }

    public function title(): string
    {
        return 'Daily Checksheet Reports';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 1. GLOBAL WRAP TEXT & VERTICAL ALIGN CENTER
                $sheet->getStyle('A1:N150')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:N150')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // DEFAULT: Semua kolom rata tengah (Center)
                $sheet->getStyle('A1:N150')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // 2. KECUALI KOLOM ITEM YANG DICHECK (Rata Kiri / Left)
                $sheet->getStyle('B6:B100')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                // 3. FIX LEBAR KOLOM (MUTLAK ENTERPRISE STANDAR)
                $sheet->getColumnDimension('A')->setWidth(5);   // Kolom No
                $sheet->getColumnDimension('B')->setWidth(45);  // Kolom Item Pengecekan (Lebar & Wrap)

                // Kolom C sampai N (OK & Repair Senin-Sabtu) dikunci TIPIS dan SAMA RATA (Fix 5 unit)
                foreach (range('C', 'N') as $column) {
                    $sheet->getColumnDimension($column)->setWidth(5);
                }

                // 4. SET TINGGI BARIS TANDA TANGAN (Biar kotak sgn pas buat nampung merge text nama+tgl)
                // Baris ttd biasanya dimulai setelah loop item selesai, kita set tinggi baris global atau dinamis
                $sheet->getDefaultRowDimension()->setRowHeight(-1); // Auto-row height jika text wrap melar
            },
        ];
    }
}
