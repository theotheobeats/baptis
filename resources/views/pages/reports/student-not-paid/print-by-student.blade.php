@include('pages.reports.report-print-control')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if (Request::get('type'))
    <title>Export</title>
    @else
    <title>Laporan Siswa Belum Bayar</title>
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
        <h2 style="margin-bottom: 0px">Laporan Siswa Belum Bayar</h2>
        <p style="margin-top: 5px; font-size: 1.3em">Sekolah Baptis Palembang</p>
    </center>
    <table>
        <thead>

            <tr>
                <th>No.</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Total Tagihan</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1 @endphp
            @foreach ($invoices as $invoice)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $invoice->student_nis }}</td>
                <td>{{ $invoice->student_name }}</td>
                <td align="right">{{ number_format_custom($invoice->not_paid_amount, "Rp") }}</td>
            </tr>
            @php $i++ @endphp
            @endforeach
        </tbody>
    </table>

    @stack('report-print_control')
    @stack('report-js')

</body>

</html>
