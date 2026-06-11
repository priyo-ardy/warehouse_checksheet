<x-filament-panels::page>
    <style>
        .professional-sheet,
        .professional-sheet th,
        .professional-sheet td {
            border: 1px solid #1e293b !important;
            padding: 12px 10px !important;
            text-align: center !important;
        }

        .dark .professional-sheet,
        .dark .professional-sheet th,
        .dark .professional-sheet td {
            border: 1px solid #4b5563 !important;
        }

        .professional-sheet td.align-left-force,
        .professional-sheet th.align-left-force {
            text-align: left !important;
        }
    </style>

    <form wire:submit.prevent="generateReport" class="space-y-6 no-print">
        {{ $this->form }}
    </form>

    @if(!empty($reports))
    <div class="flex justify-end mb-4 no-print">
        <x-filament::button
            wire:click="exportToExcel"
            icon="heroicon-m-document-arrow-down"
            color="success"
            class="font-semibold shadow-sm">
            Export to Excel
        </x-filament::button>
    </div>
    <div class="space-y-8 mt-8">
        @foreach($reports as $report)
        <div class="p-6 bg-white dark:bg-gray-950 rounded-xl shadow-md border border-gray-200 dark:border-gray-800 text-gray-900 dark:text-gray-100">
            <div class="w-full overflow-x-auto">

                <table class="w-full table-fixed professional-sheet border-collapse border-2 border-slate-950 dark:border-gray-400 text-xs">
                    <colgroup>
                        <col style="width: 4%;">
                        <col style="width: 36%;">
                        @for($i = 0; $i < 12; $i++)
                            <col style="width: 5%;">
                            @endfor
                    </colgroup>

                    <thead>
                        <tr class="h-0 invisible select-none pointer-events-none">
                            <th class="p-0 border-0 h-0"></th>
                            <th class="p-0 border-0 h-0"></th>
                            @for($i = 0; $i < 12; $i++)
                                <th class="p-0 border-0 h-0">
                                </th>
                                @endfor
                        </tr>

                        <tr class="bg-slate-50 dark:bg-gray-900/50 font-bold">
                            <td colspan="10" class="text-center text-lg font-black tracking-wider align-middle uppercase">
                                CHECKLIST HARIAN FORKLIFT<br> <span class="text-primary-600 dark:text-primary-400">{{ $report['equipment_name'] }}</span>
                            </td>
                            <td colspan="4" class="align-left-force p-3 font-mono text-[10px] leading-relaxed bg-slate-100 dark:bg-gray-900/30 align-middle">
                                <div class="font-semibold">Form no. : C-08-05-06-01</div>
                                <div class="border-t border-gray-300 dark:border-gray-700 pt-1 mt-1 font-semibold">Version no.: 1.0</div>
                            </td>
                        </tr>

                        <tr class="bg-slate-100 dark:bg-gray-800 font-black text-xs tracking-wider text-center">
                            <td colspan="2" class="bg-white dark:bg-gray-950 border-b-2"></td>
                            <td colspan="2">SENIN</td>
                            <td colspan="2">SELASA</td>
                            <td colspan="2">RABU</td>
                            <td colspan="2">KAMIS</td>
                            <td colspan="2">JUMAT</td>
                            <td colspan="2">SABTU</td>
                        </tr>

                        <tr class="font-medium bg-white dark:bg-gray-950 text-center">
                            <td colspan="2" class="align-left-force pl-4 font-bold bg-slate-50 dark:bg-gray-900/60 text-[11px]">Tanggal :</td>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            <td colspan="2" class="font-mono text-gray-500 dark:text-gray-400 font-semibold bg-white dark:bg-gray-950">{{ $report['dates'][$day] }}</td>
                            @endforeach
                        </tr>

                        <tr class="font-medium bg-white dark:bg-gray-950 text-center">
                            <td colspan="2" class="align-left-force pl-4 font-bold bg-slate-50 dark:bg-gray-900/60 text-[11px]">PIC :</td>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            <td colspan="2" class="bg-white dark:bg-gray-950 font-bold text-[10px] text-slate-800 dark:text-slate-200 whitespace-normal leading-tight">
                                {{ $report['leaders'][$day] }}
                            </td>
                            @endforeach
                        </tr>

                        <tr class="bg-slate-50 dark:bg-gray-900 font-bold text-[11px] uppercase tracking-wider text-center">
                            <td class="text-center">No.</td>
                            <td class="align-left-force pl-4">Item yang dicheck</td>
                            @for ($i = 0; $i < 6; $i++)
                                <td class="text-emerald-600 dark:text-emerald-400 font-black bg-emerald-50/20 dark:bg-emerald-950/10">OK</td>
                                <td class="text-rose-600 dark:text-rose-400 font-black bg-rose-50/20 dark:bg-rose-950/10">Repair</td>
                                @endfor
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($report['items'] as $index => $item)
                        <tr class="bg-white dark:bg-gray-950 hover:bg-slate-50 dark:hover:bg-gray-900/40 transition-colors text-center">
                            <td class="font-bold text-slate-400 dark:text-gray-500 bg-slate-50/30 dark:bg-gray-900/10 text-center">
                                {{ $index + 1 }}
                            </td>
                            <td class="align-left-force font-medium text-slate-800 dark:text-gray-200 whitespace-normal break-words pl-4">
                                {{ $item['nama_item'] }}
                            </td>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            <td class="font-black text-sm text-emerald-600 dark:text-emerald-400">
                                {{ $item['status_hari'][$day] === 'OK' ? '✓' : '' }}
                            </td>
                            <td class="font-black text-sm text-rose-500 dark:text-rose-400">
                                {{ $item['status_hari'][$day] === 'NG' ? '✗' : '' }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach

                        <tr class="h-20 bg-white dark:bg-gray-950 text-center">
                            <td colspan="2" class="text-center font-bold bg-slate-50 dark:bg-gray-900/60 text-[11px]">Sign Leader</td>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            <td colspan="2" class="text-[10px] text-slate-700 dark:text-slate-300 align-bottom font-bold bg-white dark:bg-gray-950 whitespace-normal leading-tight">
                                {{ $report['leaders'][$day] !== '-' ? $report['leaders'][$day] : '' }} <br>
                                {{ $report['leaders_date'][$day] !== '-' ? $report['leaders_date'][$day] : ''}}
                            </td>
                            @endforeach
                        </tr>

                        <tr class="h-20 bg-white dark:bg-gray-950 text-center">
                            <td colspan="2" class="text-center font-bold bg-slate-50 dark:bg-gray-900/60 text-[11px]">Sign Supervisor</td>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            <td colspan="2" class="text-[10px] text-slate-700 dark:text-slate-300 align-bottom font-bold bg-white dark:bg-gray-950 whitespace-normal leading-tight">
                                {{ $report['spvs'][$day] !== '-' ? $report['spvs'][$day] : '' }} <br>
                                {{ $report['spvs_date'][$day] !== '-' ? $report['spvs_date'][$day] : ''}}
                            </td>
                            @endforeach
                        </tr>

                        <tr class="h-20 bg-white dark:bg-gray-950 text-center">
                            <td colspan="2" class="text-center font-bold bg-slate-50 dark:bg-gray-900/60 text-[11px]">Sign SHE</td>
                            <td colspan="12" class="text-[11px] text-slate-700 dark:text-slate-300 align-bottom font-bold bg-white dark:bg-gray-950 whitespace-normal leading-tight">
                                {{ $report['she'] !== '-' ? $report['she'] : '' }}
                            </td>
                        </tr>

                        <tr class="h-24 bg-white dark:bg-gray-950">
                            <td colspan="2" class="font-bold bg-slate-50 dark:bg-gray-900/60 align-middle text-[11px] text-center">
                                Keterangan
                            </td>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            <td colspan="2" class="p-2 border bg-white dark:bg-gray-950 text-left align-top font-medium text-[10px] text-gray-600 dark:text-gray-400 whitespace-normal leading-tight">
                                {{ $report['keterangan_hari'][$day] !== '-' ? $report['keterangan_hari'][$day] : '' }}
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
        @endforeach
    </div>
    @endif
</x-filament-panels::page>