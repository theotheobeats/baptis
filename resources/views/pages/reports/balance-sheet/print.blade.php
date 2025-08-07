@include('pages.reports.report-print-control')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if (Request::get('type'))
    <title>Export</title>
    @else
    <title>Laporan Balance Sheet</title>
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
        <h2 style="margin-bottom: 0px">Balance Sheet (Standart)</h2>
        <p style="margin-top: 4px; margin-bottom: 4px">
            Periode: {{ \Carbon\Carbon::parse($date_from)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($date_to)->format('d F Y') }}
        </p>
        @if (isset($employee))
            <p style="margin-top: 4px">
                Dibuat Oleh: {{ $employee->name }}
            </p>
        @endif
    </div>

    <table style="width: 100%;">
        <tr>
            <th width="800px">Description</th>
            <th width="200px">Debit</th>
            <th width="200px">Kredit</th>
        </tr>

        <tr>
            <td><b>CURRENT ASSETS</b></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left: 30px"><b>Kas &amp; Bank</b></td>
            <td></td>
        </tr>
        <?php
        $r1_debit_total = 0;
        $r1_credit_total = 0;
        ?>
        @foreach ($report_1_accounts as $r1a)
            <tr>
                <td style="padding-left: 60px;">{{ $r1a->code }} - {{ $r1a->name }}</td>
                <?php $has_data = false; ?>
                @foreach ($report_1 as $r1)
                    @if ($r1->account_id == $r1a->id)
                        <td align="right">{{ number_format_custom($r1->debit_total) }}</td>
                        <td align="right">{{ number_format_custom($r1->credit_total) }}</td>
                        <?php
                            $has_data = true;
                            $r1_debit_total += $r1->debit_total;
                            $r1_credit_total += $r1->credit_total;
                        ?>
                    @endif
                @endforeach
                @if (!$has_data)
                    <td align="right">0</td>
                    <td align="right">0</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td style="padding-left: 30px"><b>Total Kas &amp; Bank</b></td>
            <td align="right"><b>{{ number_format_custom($r1_debit_total) }}</b></td>
            <td align="right"><b>{{ number_format_custom($r1_credit_total) }}</b></td>
        </tr>

        <tr>
            <td style="padding-left: 30px"><b>Piutang</b></td>
            <td></td>
        </tr>
        <?php
        $r2_debit_total = 0;
        $r2_credit_total = 0;
        ?>
        @foreach ($report_2_accounts as $r2a)
            <tr>
                <td style="padding-left: 60px;">{{ $r2a->code }} - {{ $r2a->name }}</td>
                <?php $has_data = false; ?>
                @foreach ($report_2 as $r2)
                    @if ($r2->account_id == $r2a->id)
                        <td align="right">{{ number_format_custom($r2->debit_total) }}</td>
                        <td align="right">{{ number_format_custom($r2->credit_total) }}</td>
                        <?php
                            $has_data = true;
                            $r2_debit_total += $r2->debit_total;
                            $r2_credit_total += $r2->credit_total;
                        ?>
                    @endif
                @endforeach
                @if (!$has_data)
                    <td align="right">0</td>
                    <td align="right">0</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td style="padding-left: 30px"><b>Total Piutang</b></td>
            <td align="right"><b>{{ number_format_custom($r2_debit_total) }}</b></td>
            <td align="right"><b>{{ number_format_custom($r2_credit_total) }}</b></td>
        </tr>

        @php
            $current_assets_debit_total = $r1_debit_total + $r2_debit_total;
            $current_assets_credit_total = $r1_credit_total + $r2_credit_total;
        @endphp
        <tr>
            <td><b>Total CURRENT ASSETS</b></td>
            <td align="right"><b>{{ number_format_custom($current_assets_debit_total) }}</b></td>
            <td align="right"><b>{{ number_format_custom($current_assets_credit_total) }}</b></td>
        </tr>




        <tr>
            <td><b>FIXED ASSETS</b></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left: 30px"><b>Aktiva Tetap</b></td>
            <td></td>
        </tr>
        <?php
        $r3_debit_total = 0;
        $r3_credit_total = 0;
        ?>
        @foreach ($report_3_accounts as $r3a)
            <tr>
                <td style="padding-left: 60px;">{{ $r3a->code }} - {{ $r3a->name }}</td>
                <?php $has_data = false; ?>
                @foreach ($report_3 as $r3)
                    @if ($r3->account_id == $r3a->id)
                        <td align="right">{{ number_format_custom($r3->debit_total) }}</td>
                        <td align="right">{{ number_format_custom($r3->credit_total) }}</td>
                        <?php
                            $has_data = true;
                            $r3_debit_total += $r3->debit_total;
                            $r3_credit_total += $r3->credit_total;
                        ?>
                    @endif
                @endforeach
                @if (!$has_data)
                    <td align="right">0</td>
                    <td align="right">0</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td style="padding-left: 30px"><b>Total Aktiva Tetap</b></td>
            <td align="right"><b>{{ number_format_custom($r3_debit_total) }}</b></td>
            <td align="right"><b>{{ number_format_custom($r3_credit_total) }}</b></td>
        </tr>

        <tr>
            <td style="padding-left: 30px"><b>Akumulasi Penyusutan</b></td>
            <td></td>
        </tr>
        <?php
        $r4_debit_total = 0;
        $r4_credit_total = 0;
        ?>
        @foreach ($report_4_accounts as $r4a)
            <tr>
                <td style="padding-left: 60px;">{{ $r4a->code }} - {{ $r4a->name }}</td>
                <?php $has_data = false; ?>
                @foreach ($report_4 as $r4)
                    @if ($r4->account_id == $r4a->id)
                        <td align="right">{{ number_format_custom($r4->debit_total) }}</td>
                        <td align="right">{{ number_format_custom($r4->credit_total) }}</td>
                        <?php
                            $has_data = true;
                            $r4_debit_total += $r4->debit_total;
                            $r4_credit_total += $r4->credit_total;
                        ?>
                    @endif
                @endforeach
                @if (!$has_data)
                    <td align="right">0</td>
                    <td align="right">0</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td style="padding-left: 30px"><b>Total Akumulasi Penyusutan</b></td>
            <td align="right"><b>{{ number_format_custom($r4_debit_total) }}</b></td>
            <td align="right"><b>{{ number_format_custom($r4_credit_total) }}</b></td>
        </tr>

        @php
        $fixed_assets_debit_total = $r3_debit_total + $r4_debit_total;
        $fixed_assets_credit_total = $r3_credit_total + $r4_credit_total;
        @endphp
        <tr>
            <td><b>Total FIXED ASSETS</b></td>
            <td align="right"><b>{{ number_format_custom($fixed_assets_debit_total) }}</b></td>
            <td align="right"><b>{{ number_format_custom($fixed_assets_credit_total) }}</b></td>
        </tr>




        <tr>
            <td><b>LIABILITIES and EQUITIES</b></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left: 30px"><b>Liabilitas</b></td>
            <td></td>
        </tr>
        <?php
        $r5_debit_total = 0;
        $r5_credit_total = 0;
        ?>
        @foreach ($report_5_accounts as $r5a)
            <tr>
                <td style="padding-left: 60px;">{{ $r5a->code }} - {{ $r5a->name }}</td>
                <?php $has_data = false; ?>
                @foreach ($report_5 as $r5)
                    @if ($r5->account_id == $r5a->id)
                        <td align="right">{{ number_format_custom($r5->debit_total) }}</td>
                        <td align="right">{{ number_format_custom($r5->credit_total) }}</td>
                        <?php
                            $has_data = true;
                            $r5_debit_total += $r5->debit_total;
                            $r5_credit_total += $r5->credit_total;
                        ?>
                    @endif
                @endforeach
                @if (!$has_data)
                    <td align="right">0</td>
                    <td align="right">0</td>
                @endif
            </tr>
        @endforeach

        <?php
        $r6_debit_total = 0;
        $r6_credit_total = 0;
        ?>
        @foreach ($report_6_accounts as $r6a)
            <tr>
                <td style="padding-left: 60px;">{{ $r6a->code }} - {{ $r6a->name }}</td>
                <?php $has_data = false; ?>
                @foreach ($report_6 as $r6)
                    @if ($r6->account_id == $r6a->id)
                        <td align="right">{{ number_format_custom($r6->debit_total) }}</td>
                        <td align="right">{{ number_format_custom($r6->credit_total) }}</td>
                        <?php
                            $has_data = true;
                            $r6_debit_total += $r6->debit_total;
                            $r6_credit_total += $r6->credit_total;
                        ?>
                    @endif
                @endforeach
                @if (!$has_data)
                    <td align="right">0</td>
                    <td align="right">0</td>
                @endif
            </tr>
        @endforeach

        <?php
        $r7_debit_total = 0;
        $r7_credit_total = 0;
        ?>
        @foreach ($report_7_accounts as $r7a)
            <tr>
                <td style="padding-left: 60px;">{{ $r7a->code }} - {{ $r7a->name }}</td>
                <?php $has_data = false; ?>
                @foreach ($report_7 as $r7)
                    @if ($r7->account_id == $r7a->id)
                        <td align="right">{{ number_format_custom($r7->debit_total) }}</td>
                        <td align="right">{{ number_format_custom($r7->credit_total) }}</td>
                        <?php
                            $has_data = true;
                            $r7_debit_total += $r7->debit_total;
                            $r7_credit_total += $r7->credit_total;
                        ?>
                    @endif
                @endforeach
                @if (!$has_data)
                    <td align="right">0</td>
                    <td align="right">0</td>
                @endif
            </tr>
        @endforeach

        <tr>
            <td style="padding-left: 30px"><b>Total Liabilitas</b></td>
            <td align="right"><b>{{ number_format_custom($r5_debit_total + $r6_debit_total + $r7_debit_total) }}</b></td>
            <td align="right"><b>{{ number_format_custom($r5_credit_total + $r6_credit_total + $r7_credit_total) }}</b></td>
        </tr>



        <tr>
            <td style="padding-left: 30px"><b>Ekuitas</b></td>
            <td></td>
        </tr>
        <?php
        $r8_debit_total = 0;
        $r8_credit_total = 0;
        ?>
        @foreach ($report_8_accounts as $r8a)
            <tr>
                <td style="padding-left: 60px;">{{ $r8a->code }} - {{ $r8a->name }}</td>
                <?php $has_data = false; ?>
                @foreach ($report_8 as $r8)
                    @if ($r8->account_id == $r8a->id)
                        <td align="right">{{ number_format_custom($r8->debit_total) }}</td>
                        <td align="right">{{ number_format_custom($r8->credit_total) }}</td>
                        <?php
                            $has_data = true;
                            $r8_debit_total += $r8->debit_total;
                            $r8_credit_total += $r8->credit_total;
                        ?>
                    @endif
                @endforeach
                @if (!$has_data)
                    <td align="right">0</td>
                    <td align="right">0</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td style="padding-left: 30px"><b>Total Ekuitas</b></td>
            <td align="right"><b>{{ number_format_custom($r8_debit_total) }}</b></td>
            <td align="right"><b>{{ number_format_custom($r8_credit_total) }}</b></td>
        </tr>

        @php
            $liabilities_equities_debit_total = $r5_debit_total + $r6_debit_total + $r7_debit_total + $r8_debit_total;
            $liabilities_equities_credit_total = $r5_credit_total + $r6_credit_total + $r7_credit_total + $r8_credit_total;
        @endphp
        <tr>
            <td><b>Total Liabilities and Equities</b></td>
            <td align="right"><b>{{ number_format_custom($liabilities_equities_debit_total) }}</b></td>
            <td align="right"><b>{{ number_format_custom($liabilities_equities_credit_total) }}</b></td>
        </tr>


    </table>

    @stack('report-print_control')
    @stack('report-js')

</body>

</html>
