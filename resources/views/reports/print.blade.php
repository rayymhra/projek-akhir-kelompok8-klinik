<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Sistem Klinik</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h2, h4 {
            text-align: center;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
        }
        th {
            background: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>LAPORAN SISTEM KLINIK</h2>
<h4>Periode {{ $filter['start_date'] }} s/d {{ $filter['end_date'] }}</h4>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Data</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports['data'] as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->created_at->format('d/m/Y') }}</td>
            <td>
                @if($reports['type'] === 'visits')
                    {{ $item->patient->nama }} - {{ ucfirst($item->status) }}
                @elseif($reports['type'] === 'transactions')
                    Rp {{ number_format($item->total_biaya,0,',','.') }}
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<p style="margin-top:40px; text-align:right;">
    Dicetak pada: {{ now()->format('d/m/Y H:i') }}
</p>

</body>
</html>
