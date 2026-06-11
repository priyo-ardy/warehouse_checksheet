<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body>
    @foreach($reports as $report)
    <table>
        <thead>
            <tr class="bg-slate-50 dark:bg-gray-900/50 font-bold">
                <td colspan="2" class="p-2 bg-white dark:bg-gray-950 align-middle text-center">
                    <img src="{{ asset('logo.png') }}" alt="Company Logo" class="h-12 w-auto mx-auto object-contain">
                </td>

                <td colspan="8" class="text-center text-lg font-black tracking-wider align-middle uppercase">
                    CHECKLIST HARIAN FORKLIFT<br> <span class="text-primary-600 dark:text-primary-400">{{ $report['equipment_name'] }}</span>
                </td>

                <td colspan="4" class="align-left-force p-3 font-mono text-[10px] leading-relaxed bg-slate-100 dark:bg-gray-900/30 align-middle">
                    <div class="font-semibold">Form no. : C-08-05-06-01</div>
                    <div class="border-t border-gray-300 dark:border-gray-700 pt-1 mt-1 font-semibold">Version no.: 1.0</div>
                </td>
            </tr>

            <tr>
                <th colspan="2" style="border: 1px solid #000000; background-color: #ffffff;"></th>
                <th colspan="2" style="font-weight: bold; text-align: center; background-color: #e2e8f0; border: 1px solid #000000;">SENIN</th>
                <th colspan="2" style="font-weight: bold; text-align: center; background-color: #e2e8f0; border: 1px solid #000000;">SELASA</th>
                <th colspan="2" style="font-weight: bold; text-align: center; background-color: #e2e8f0; border: 1px solid #000000;">RABU</th>
                <th colspan="2" style="font-weight: bold; text-align: center; background-color: #e2e8f0; border: 1px solid #000000;">KAMIS</th>
                <th colspan="2" style="font-weight: bold; text-align: center; background-color: #e2e8f0; border: 1px solid #000000;">JUMAT</th>
                <th colspan="2" style="font-weight: bold; text-align: center; background-color: #e2e8f0; border: 1px solid #000000;">SABTU</th>
            </tr>

            <tr>
                <th colspan="2" style="font-weight: bold; text-align: left; background-color: #f1f5f9; border: 1px solid #000000;">Tanggal :</th>
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                <th colspan="2" style="text-align: center; font-family: monospace; border: 1px solid #000000;">{{ $report['dates'][$day] }}</th>
                @endforeach
            </tr>

            <tr>
                <th colspan="2" style="font-weight: bold; text-align: left; background-color: #f1f5f9; border: 1px solid #000000;">PIC :</th>
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                <th colspan="2" style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{ $report['leaders'][$day] }}</th>
                @endforeach
            </tr>

            <tr>
                <th style="font-weight: bold; text-align: center; background-color: #f1f5f9; border: 1px solid #000000;">No.</th>
                <th style="font-weight: bold; text-align: left; background-color: #f1f5f9; border: 1px solid #000000;">Item yang dicheck</th>
                @for ($i = 0; $i < 6; $i++)
                    <th style="font-weight: bold; text-align: center; color: #16a34a; background-color: #f0fdf4; border: 1px solid #000000;">OK</th>
                    <th style="font-weight: bold; text-align: center; color: #dc2626; background-color: #fef2f2; border: 1px solid #000000;">Repair</th>
                    @endfor
            </tr>
        </thead>
        <tbody>
            @foreach($report['items'] as $index => $item)
            <tr>
                <td style="text-align: center; font-weight: bold; color: #64748b; background-color: #f8fafc; border: 1px solid #000000;">{{ $index + 1 }}</td>
                <td style="text-align: left; border: 1px solid #000000;">{{ $item['nama_item'] }}</td>
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                <td style="text-align: center; font-weight: bold; color: #16a34a; border: 1px solid #000000;">{{ $item['status_hari'][$day] === 'OK' ? '✓' : '' }}</td>
                <td style="text-align: center; font-weight: bold; color: #dc2626; border: 1px solid #000000;">{{ $item['status_hari'][$day] === 'NG' ? '✗' : '' }}</td>
                @endforeach
            </tr>
            @endforeach

            <tr>
                <td colspan="2" style="font-weight: bold; text-align: center; background-color: #f1f5f9; border: 1px solid #000000; height: 55px;">Sign Leader</td>
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                <td colspan="2" style="text-align: center; font-weight: bold; border: 1px solid #000000; font-size: 10px;">
                    @if($report['leaders'][$day] !== '-')
                    {{ $report['leaders'][$day] }}<br><span style="font-size: 8px; color: #64748b; font-weight: normal;">{{ $report['leaders_date'][$day] }}</span>
                    @endif
                </td>
                @endforeach
            </tr>

            <tr>
                <td colspan="2" style="font-weight: bold; text-align: center; background-color: #f1f5f9; border: 1px solid #000000; height: 55px;">Sign Supervisor</td>
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                <td colspan="2" style="text-align: center; font-weight: bold; border: 1px solid #000000; font-size: 10px;">
                    @if($report['spvs'][$day] !== '-')
                    {{ $report['spvs'][$day] }}<br><span style="font-size: 8px; color: #64748b; font-weight: normal;">{{ $report['spvs_date'][$day] }}</span>
                    @endif
                </td>
                @endforeach
            </tr>

            <tr>
                <td colspan="2" style="font-weight: bold; text-align: center; background-color: #f1f5f9; border: 1px solid #000000; height: 50px;">Sign SHE</td>
                <td colspan="12" style="text-align: center; font-weight: bold; border: 1px solid #000000; font-size: 10px;">
                    {{ $report['she'] !== '-' ? $report['she'] : '' }}
                </td>
            </tr>

            <tr>
                <td colspan="2" style="font-weight: bold; text-align: center; background-color: #f1f5f9; border: 1px solid #000000; height: 40px; vertical-align: top;">Keterangan</td>
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                <td colspan="2" style="text-align: left; vertical-align: top; border: 1px solid #000000; font-size: 9px; color: #475569;">
                    {{ $report['keterangan_hari'][$day] !== '-' ? $report['keterangan_hari'][$day] : '' }}
                </td>
                @endforeach
            </tr>
        </tbody>
    </table>
    @endforeach
</body>

</html>