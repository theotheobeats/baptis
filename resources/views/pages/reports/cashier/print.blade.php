@include('pages.reports.report-print-control')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if (Request::get('type'))
    <title>Export</title>
    @else
    <title>Laporan Harian Kasir</title>
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
        <h2 style="margin-bottom: 0px">Laporan Harian Kasir</h2>
        <p style="margin: 4px 0; font-size: 1.3em">Sekolah Baptis Palembang</p>
        <p style="margin-top: 4px; margin-bottom: 4px">
            Periode: {{ \Carbon\Carbon::parse($date_from)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($date_to)->format('d F Y') }}
        </p>
    </center>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Uraian</th>
                <th>Pemasukan</th>
                <th>Pengeluaran</th>
                <th>Saldo Akhir</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                $cashier_balance = 0;
                $current_date = "";
            @endphp
            @foreach ($dates as $date)
                @php
                    $i = 1;
                    $current_date = $date;
                    $total_credit_date = 0;
                    $total_debit_date = 0;
                @endphp
                <tr>
                    <td></td>
                    <td><b>Penerimaan per {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach ($cashier_credits as $cashier_credit)
                    @if ($cashier_credit->transaction_date == $date)
                        @php
                            $total_credit_date += $cashier_credit->credit;
                        @endphp
                        <tr>
                            <td align="center">{{ $i++ }}</td>
                            <td>{{ $cashier_credit->note }} {{ $cashier_credit->account_name }}</td>
                            <td align="right">{{ $cashier_credit->credit == 0 ? '' : number_format_custom($cashier_credit->credit) }}</td>
                            <td align="right">{{ $cashier_credit->debit == 0 ? '' : number_format_custom($cashier_credit->debit) }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td></td>
                    <td><b>Total Penerimaan per {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</b></td>
                    <td></td>
                    <td></td>
                    <td align="right"><b>{{ number_format_custom($total_credit_date) }}</b></td>
                </tr>

                <tr>
                    <td></td>
                    <td style="color: white">[]</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td><b>Pengeluaran per {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach ($cashier_debits as $cashier_debit)
                    @if ($cashier_debit->transaction_date == $date)
                        @php
                            $total_debit_date += $cashier_debit->debit;
                        @endphp
                        <tr>
                            <td align="center">{{ $i++ }}</td>
                            <td>{{ $cashier_debit->note }} {{ $cashier_debit->account_name }}</td>
                            <td align="right">{{ $cashier_debit->credit == 0 ? '' : number_format_custom($cashier_debit->credit) }}</td>
                            <td align="right">{{ $cashier_debit->debit == 0 ? '' : number_format_custom($cashier_debit->debit) }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td></td>
                    <td><b>Total Pengeluaran per {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</b></td>
                    <td></td>
                    <td></td>
                    <td align="right"><b>{{ number_format_custom($total_debit_date) }}</b></td>
                </tr>
                @php
                    $cashier_balance = $cashier_balance + $total_credit_date - $total_debit_date;
                @endphp
                <tr>
                    <td colspan="2" align="right" style="background-color: #ddd"><b>Saldo per Tanggal {{ \Carbon\Carbon::parse($current_date)->format('d F Y') }}</b></td>
                    <td></td>
                    <td></td>
                    <td align="right"><b>{{ number_format_custom($cashier_balance) }}</b></td>
                </tr>
            @endforeach

        </tbody>
    </table>

    @stack('report-print_control')
    @stack('report-js')

</body>

</html>
