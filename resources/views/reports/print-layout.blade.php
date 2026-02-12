<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan' }}</title>
    <style>
        /* Base Styles */
        @page {
            margin: 50px 30px;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Helvetica', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 15px;
        }
        
        .clinic-name {
            font-size: 20px;
            font-weight: 700;
            color: #4F46E5;
            margin-bottom: 3px;
        }
        
        .clinic-address {
            font-size: 11px;
            color: #666;
            margin-bottom: 2px;
        }
        
        .report-title {
            font-size: 16px;
            font-weight: 600;
            margin: 15px 0 5px 0;
            color: #333;
        }
        
        .report-period {
            font-size: 12px;
            color: #666;
            margin-bottom: 15px;
        }
        
        /* Stats Cards */
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .stat-card {
            flex: 1;
            min-width: 120px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 18px;
            font-weight: 700;
            color: #4F46E5;
            margin: 5px 0;
        }
        
        .stat-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10px;
        }
        
        .table th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-align: left;
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
        }
        
        .table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
        }
        
        .table tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Badges */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 500;
            text-align: center;
        }
        
        .badge-primary { background: #e0e7ff; color: #4F46E5; }
        .badge-success { background: #d1fae5; color: #059669; }
        .badge-warning { background: #fef3c7; color: #d97706; }
        .badge-info { background: #dbeafe; color: #2563eb; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        
        /* Summary Section */
        .summary-section {
            margin: 25px 0;
            padding: 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        
        .summary-title {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 10px;
        }
        
        /* Footer */
        .footer {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        
        .page-number:after {
            content: counter(page);
        }
        
        /* Utility Classes */
        .mb-3 { margin-bottom: 15px; }
        .mt-3 { margin-top: 15px; }
        .pb-3 { padding-bottom: 15px; }
        .pt-3 { padding-top: 15px; }
        
        .currency {
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }
        
        /* Page Break */
        .page-break {
            page-break-before: always;
        }
        
        /* No Data */
        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="clinic-name">{{ $clinic_info['name'] }}</div>
        <div class="clinic-address">{{ $clinic_info['address'] }}</div>
        <div class="clinic-address">Telp: {{ $clinic_info['phone'] }} | Email: {{ $clinic_info['email'] }}</div>
        
        <div class="report-title">{{ $title ?? 'Laporan' }}</div>
        <div class="report-period">
            Periode: {{ date('d/m/Y', strtotime($filter['start_date'])) }} s/d {{ date('d/m/Y', strtotime($filter['end_date'])) }}
        </div>
    </div>
    
    <!-- Content -->
    @yield('content')
    
    <!-- Footer -->
    <div class="footer">
        Dicetak pada: {{ $print_date }} | Halaman <span class="page-number"></span>
    </div>
</body>
</html>