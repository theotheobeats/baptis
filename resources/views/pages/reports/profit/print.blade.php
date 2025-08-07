@include('pages.reports.report-print-control')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if (Request::get('type'))
    <title>Export</title>
    @else
    <title>Laporan Laba Rugi</title>
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
            body {-webkit-print-color-adjust: exact;}
        }
    </style>
</head>

<body>
    <div>
        <h2 style="margin-bottom: 0px">Profit &amp; Loss (Standart)</h2>
        <p style="margin-top: 4px; margin-bottom: 4px">
            Periode: {{ \Carbon\Carbon::parse($date_from)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($date_to)->format('d F Y') }}
        </p>
        @if (isset($employee))
            <p style="margin-top: 4px">
                Dibuat Oleh: {{ $employee->name }}
            </p>
        @endif
    </div>
    <div>

    <table style="width: 100%;">
        <tr>
            <th width="800px">Description</th>
            <th width="200px">Nominal</th>
        </tr>
        <tr>
            <td><b>OPERATING REVENUE</b></td>
            <td></td>
        </tr>
        <?php
        $r1_total = 0;
        ?>
        @foreach ($report_1_accounts as $r1a)
            <tr>
                <td style="padding-left: 30px;">{{ $r1a->code }} - {{ $r1a->name }}</td>
                <?php $has_data = false; ?>
                @foreach ($report_1 as $r1)
                    @if ($r1->account_id == $r1a->id)
                        <?php
                            $r1_amount = $r1->debit_total - $r1->credit_total;
                        ?>
                        <td align="right">{{ number_format_custom(abs($r1_amount)) }}</td>
                        <?php
                            $has_data = true;
                            $r1_total += $r1_amount;
                        ?>
                    @endif
                @endforeach
                @if (!$has_data)
                    <td align="right">0</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td><b>Total OPERATING REVENUE</b></td>
            <td align="right"><b>{{ number_format_custom(abs($r1_total)) }}</b></td>
        </tr>




        <tr>
            <td><b>OPERATING EXPENSES</b></td>
            <td></td>
        </tr>
        <?php
        $r2_total = 0;
        ?>
        <tr>
            <td><b>Biaya Langsung / Operasional</b></td>
            <td></td>
        </tr>
        @foreach ($report_2_accounts as $r2a)
            <tr>
                <td style="padding-left: 30px;">{{ $r2a->code }} - {{ $r2a->name }}</td>
                <?php $has_data = false; ?>
                @foreach ($report_2 as $r2)
                    @if ($r2->account_id == $r2a->id)
                        <?php
                            $r2_amount = $r2->credit_total - $r2->debit_total;
                        ?>
                        <td align="right">{{ number_format_custom(abs($r2_amount)) }}</td>
                        <?php
                            $has_data = true;
                            $r2_total += $r2_amount;
                        ?>
                    @endif
                @endforeach
                @if (!$has_data)
                    <td align="right">0</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td><b>Total Biaya Langsung / Operasional</b></td>
            <td align="right"><b>{{ number_format_custom(abs($r2_total)) }}</b></td>
        </tr>



        <?php
        $r3_total = 0;
        ?>
        <tr>
            <td><b>Biaya Tak Langsung</b></td>
            <td></td>
        </tr>
        @foreach ($report_3_accounts as $r3a)
            <tr>
                <td style="padding-left: 30px;">{{ $r3a->code }} - {{ $r3a->name }}</td>
                <?php $has_data = false; ?>
                @foreach ($report_3 as $r3)
                    @if ($r3->account_id == $r3a->id)
                        <?php
                            $r3_amount = $r3->credit_total - $r3->debit_total;
                        ?>
                        <td align="right">{{ number_format_custom(abs($r3_amount)) }}</td>
                        <?php
                            $has_data = true;
                            $r3_total += $r3_amount;
                        ?>
                    @endif
                @endforeach
                @if (!$has_data)
                    <td align="right">0</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td><b>Total Biaya Tak Langsung</b></td>
            <td align="right"><b>{{ number_format_custom(abs($r3_total)) }}</b></td>
        </tr>


        <?php
        $r4_total = 0;
        ?>
        <tr>
            <td><b>Biaya Administrasi</b></td>
            <td></td>
        </tr>
        @foreach ($report_4_accounts as $r4a)
            <tr>
                <td style="padding-left: 30px;">{{ $r4a->code }} - {{ $r4a->name }}</td>
                <?php $has_data = false; ?>
                @foreach ($report_4 as $r4)
                    @if ($r4->account_id == $r4a->id)
                        <?php
                            $r4_amount = $r4->credit_total - $r4->debit_total;
                        ?>
                        <td align="right">{{ number_format_custom(abs($r4_amount)) }}</td>
                        <?php
                            $has_data = true;
                            $r4_total += $r4->debit_total;
                        ?>
                    @endif
                @endforeach
                @if (!$has_data)
                    <td align="right">0</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td><b>Biaya Administrasi</b></td>
            <td align="right"><b>{{ number_format_custom(abs($r4_total)) }}</b></td>
        </tr>

        <tr>
            <td><b>Total OPERATING EXPENSES</b></td>
            <td align="right"><b>{{ number_format_custom(abs($r2_total + $r3_total + $r4_total)) }}</b></td>
        </tr>



        <tr>
            <td><b>OTHER INCOME AND EXPENSES</b></td>
            <td></td>
        </tr>
        <?php
        $r5_total = 0;
        ?>
        <tr>
            <td><b>Pendapatan Lain - lain</b></td>
            <td></td>
        </tr>
        @foreach ($report_5_accounts as $r5a)
            <tr>
                <td style="padding-left: 30px;">{{ $r5a->code }} - {{ $r5a->name }}</td>
                <?php $has_data = false; ?>
                @foreach ($report_5 as $r5)
                    @if ($r5->account_id == $r5a->id)
                        <?php
                            $r5_amount = $r5->debit_total - $r5->credit_total;
                        ?>
                        <td align="right">{{ number_format_custom(abs($r5_amount)) }}</td>
                        <?php
                            $has_data = true;
                            $r5_total += $r5_amount;
                        ?>
                    @endif
                @endforeach
                @if (!$has_data)
                    <td align="right">0</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td><b>Total Pendapatan Lain - lain</b></td>
            <td align="right"><b>{{ number_format_custom(abs($r5_total)) }}</b></td>
        </tr>


        <?php
        $r6_total = 0;
        ?>
        <tr>
            <td><b>Pengeluaran Lain - lain</b></td>
            <td></td>
        </tr>
        @foreach ($report_6_accounts as $r6a)
            <tr>
                <td style="padding-left: 30px;">{{ $r6a->code }} - {{ $r6a->name }}</td>
                <?php $has_data = false; ?>
                @foreach ($report_6 as $r6)
                    @if ($r6->account_id == $r6a->id)
                        <?php
                            $r6_amount = $r6->credit_total - $r6->debit_total;
                        ?>
                        <td align="right">{{ number_format_custom(abs($r6_amount)) }}</td>
                        <?php
                            $has_data = true;
                            $r6_total += $r6_amount;
                        ?>
                    @endif
                @endforeach
                @if (!$has_data)
                    <td align="right">0</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td><b>Total Pengeluaran Lain - lain</b></td>
            <td align="right"><b>{{ number_format_custom(abs($r6_total)) }}</b></td>
        </tr>
        <tr>
            <td><b>Total OTHER INCOME AND EXPENSES</b></td>
            <td align="right"><b>{{ number_format_custom(abs($r5_total - $r6_total)) }}</b></td>
        </tr>




        <tr>
            <td><b>NET PROFIT / LOSS</b></td>
            <td align="right"><b>{{ number_format_custom($r1_total - $r2_total - $r3_total - $r4_total + ($r5_total - $r6_total)) }}</b></td>
        </tr>


    </table>

    @stack('report-print_control')
    @stack('report-js')

</body>

</html>
