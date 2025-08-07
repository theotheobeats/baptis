@include('pages.reports.report-print-control')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if (Request::get('type'))
    <title>Export</title>
    @else
    <title>Laporan Arus Kas</title>
    @endif

    @stack('report-css')

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 0;
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
            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <center>
        <h2 style="margin-bottom: 0px">Laporan Arus Kas</h2>
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
                <th>Tanggal</th>
                <th>No. Bukti</th>
                <th>Keterangan</th>
                <th>Debit</th>
                <th>Kredit</th>
                <!-- <th>Saldo</th> -->
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1 ;
                $total_debit = 0;
                $total_credit = 0;
                $lastNote = "";
		$lastNumber = "";
            @endphp
            @foreach($cashflows as $cf)
            @php
                if($cf->note == $lastNote && $cf->transaction_number == $lastNumber) {
		            $cf->transaction_date = "";
                    $cf->transaction_number = "";
                    $cf->note = "";
		            $rownum = "";
                }
                else{
		            $lastNumber = $cf->transaction_number;
                    $lastNote = $cf->note;
 		            $rownum = $i++;
                }
                $date = $cf->transaction_date ? \Carbon\Carbon::parse($cf->transaction_date)->format('d F Y') : "";
            @endphp
            <tr>
                <td>{{ $rownum }}</td>
                <td>{{ $date }}</td>
                <td>{{ $cf->transaction_number }}</td>
                <td>{{ $cf->note }}</td>
                <td align="right">{{ number_format_custom($cf->debit) }}</td>
                <td align="right">{{ number_format_custom($cf->credit) }}</td>
                <!-- <td>-</td> -->
            </tr>
            @php
                $total_debit += $cf->debit;
                $total_credit += $cf->credit;
            @endphp
            @endforeach
            <tr>
                <td colspan="4" style="text-align: right"><b>Total</b></td>
                <td align="right"><b>{{ number_format_custom($total_debit) }}</b></td>
                <td align="right"><b>{{ number_format_custom($total_credit) }}</b></td>
            </tr>
        </tbody>
    </table>

    @stack('report-print_control')
    @stack('report-js')

</body>

</html>
