@include('pages.reports.report-print-control')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if (Request::get('type'))
    <title>Export</title>
    @else
    <title>Laporan Pembayaran Iuran</title>
    @endif

    @stack('report-css')

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            border: 1px solid #1e1e1e;
            padding: 7px;
            text-align: left;
            background-color: #ddd
        }

        tr {
            border: 1px solid #1e1e1e;
        }

        td {
            border: 1px solid #1e1e1e;
            padding: 7px;
        }

        @media print {
            body {-webkit-print-color-adjust: exact;}
        }
    </style>
</head>

<body>
    <center>
        <h2 style="margin-bottom: 0px">Laporan Pembayaran Iuran</h2>
        <p style="margin: 4px 0; font-size: 1.3em">Sekolah Baptis Palembang</p>
        <p style="margin-top: 4px; margin-bottom: 4px">
            Periode: {{ \Carbon\Carbon::parse($date_from)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($date_to)->format('d F Y') }}
        </p>
        @if (isset($employee))
            <p style="margin-top: 4px">
                Dibuat Oleh: {{ $employee->name }}
            </p>
        @endif
    </center>
    <table>
        <thead>

            <tr>
                <th>No.</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                @foreach ($dues as $due)
                <th>{{ $due->name }}</th>
                @endforeach
                <th>Total Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1 @endphp
            @foreach ($invoice_payments as $invoice_payment)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $invoice_payment->student_nis }}</td>
                <td>{{ $invoice_payment->student_name }}</td>
                @foreach ($dues as $due)
                    @php $found = false @endphp
                    @foreach ($invoice_detail_payments as $invoice_detail_payment)
                        @if ($invoice_detail_payment->invoice_payment_id == $invoice_payment->id && $invoice_detail_payment->due_id == $due->id)
                            @php $found = true @endphp
                            <td>{{ number_format_custom($invoice_detail_payment->price) }}</td>
                        @endif
                    @endforeach
                    @if (!$found)
                        <td>0</td>
                    @endif
                @endforeach
                <td>{{ number_format_custom($invoice_payment->price) }}</td>
            </tr>
            @php $i++ @endphp
            @endforeach
        </tbody>
    </table>

    @stack('report-print_control')
    @stack('report-js')

</body>

</html>
