<?php

use App\Helpers\DataHelper;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        body {
            /* font-family: Arial, sans-serif; */
            margin: 20px;
            padding: 0;
            background-color: #f8f9fa;
            text-align: center;
        }

        h2,
        h4,
        h5 {
            margin: 5px 0;
        }

        table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tfoot tr {
            background-color: #6c757d;
            color: white;
        }

        .negative {
            color: red;
        }

        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .signature div {
            width: 30%;
        }

        .signature .name {
            margin-top: 30px;
            display: inline-block;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <h2>YAYASAN PERGURUAN BAPTIS PALEMBANG</h2>
    <h5>Periode: <?= DataHelper::format_indonesia_date($date) ?></h5>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Deskripsi</th>
                <th>BCA</th>
                <th>Maspion</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="background-color: #00000000; color: #000;">I</th>
                <th style="background-color: #00000000; color: #000; text-align: left;" colspan="3">PENERIMAAN DARI DPS, LES, EKSKUL, DLL</th>
                <th style="background-color: #00000000; color: #000;"></th>
            </tr>
            <?php

use App\Models\FinanceCashFlow;

            $i = 1;
            $pg_results = $results["pg"];
            $tk_results = $results["tk"];
            $sd_results = $results["sd"];
            $smp_results = $results["smp"];

            $over_paid_results = $results["over_paid"];
            $under_paid_results = $results["under_paid"];

            $bca_total = 0;
            $maspion_total = 0;
            ?>

            <!-- PG -->
            @foreach ($pg_results as $pg_result)
            @if ($pg_result['bca'] != 0 || $pg_result['maspion'] != 0)
            <tr>
                <td></td>
                <td align="left">{{ $pg_result['due_name'] }} - PG</td>
                <td align="right">{{ number_format($pg_result['bca']) }}</td>
                <td align="right">{{ number_format($pg_result['maspion']) }}</td>
                <td></td>
            </tr>
            <?php
            $bca_total += $pg_result['bca'];
            $maspion_total += $pg_result['maspion'];
            ?>
            @endif
            @endforeach

            <!-- TK -->
            @foreach ($tk_results as $tk_result)
            @if ($tk_result['bca'] != 0 || $tk_result['maspion'] != 0)
            <tr>
                <td></td>
                <td align="left">{{ $tk_result['due_name'] }} - TK</td>
                <td align="right">{{ number_format($tk_result['bca']) }}</td>
                <td align="right">{{ number_format($tk_result['maspion']) }}</td>
                <td></td>
            </tr>
            <?php
            $bca_total += $tk_result['bca'];
            $maspion_total += $tk_result['maspion'];
            ?>
            @endif
            @endforeach

            <!-- SD -->
            @foreach ($sd_results as $sd_result)
            @if ($sd_result['bca'] != 0 || $sd_result['maspion'] != 0)
            <tr>
                <td></td>
                <td align="left">{{ $sd_result['due_name'] }} - SD</td>
                <td align="right">{{ number_format($sd_result['bca']) }}</td>
                <td align="right">{{ number_format($sd_result['maspion']) }}</td>
                <td></td>
            </tr>
            <?php
            $bca_total += $sd_result['bca'];
            $maspion_total += $sd_result['maspion'];
            ?>
            @endif
            @endforeach

            <!-- SMP -->
            @foreach ($smp_results as $smp_result)
            @if ($smp_result['bca'] != 0 || $smp_result['maspion'] != 0)
            <tr>
                <td></td>
                <td align="left">{{ $smp_result['due_name'] }} - SMP</td>
                <td align="right">{{ number_format($smp_result['bca']) }}</td>
                <td align="right">{{ number_format($smp_result['maspion']) }}</td>
                <td></td>
            </tr>
            <?php
            $bca_total += $smp_result['bca'];
            $maspion_total += $smp_result['maspion'];
            ?>
            @endif
            @endforeach




            
            @foreach ($over_paid_results as $over_paid_result)
            <?php 
            $amount = $over_paid_result["debit"]; 
            $bca_amount = strtolower($over_paid_result['coa_sub_detail_name']) == FinanceCashFlow::COA_SUB_DETAIL_BCA ? $amount : 0;
            $maspion_amount = strtolower($over_paid_result['coa_sub_detail_name']) == FinanceCashFlow::COA_SUB_DETAIL_MASPION ? $amount : 0;
            ?>
            <tr>
                <td></td>
                <td align="left">{{ $over_paid_result['note'] }}</td>
                <td align="right">{{ number_format($bca_amount) }}</td>
                <td align="right">{{ number_format($maspion_amount) }}</td>
                <td></td>
            </tr>
            <?php
            $bca_total += $bca_amount;
            $maspion_total += $maspion_amount;
            ?>
            @endforeach

            
            @foreach ($under_paid_results as $under_paid_result)
            <?php
            $amount = $under_paid_result["debit"];
            $bca_amount = strtolower($under_paid_result['coa_sub_detail_name']) == FinanceCashFlow::COA_SUB_DETAIL_BCA ? $amount : 0;
            $maspion_amount = strtolower($under_paid_result['coa_sub_detail_name']) == FinanceCashFlow::COA_SUB_DETAIL_MASPION ? $amount : 0;
            ?>
            <tr>
                <td></td>
                <td align="left">{{ $under_paid_result['note'] }}</td>
                <td align="right">{{ number_format($bca_amount) }}</td>
                <td align="right">{{ number_format($maspion_amount) }}</td>
                <td></td>
            </tr>
            <?php
            $bca_total += $bca_amount;
            $maspion_total += $maspion_amount;
            ?>
            @endforeach



            <tr>
                <th style="background-color: #00000000; color: #000;">II</th>
                <th style="background-color: #00000000; color: #000; text-align: left;">Saldo Bank Tgl: <?= DataHelper::format_indonesia_date($date) ?></th>
                <th style="background-color: #00000000; color: #000; text-align: right;">{{ number_format($bca_total) }}</th>
                <th style="background-color: #00000000; color: #000; text-align: right;">{{ number_format($maspion_total) }}</th>
                <th style="background-color: #00000000; color: #000;"></th>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <div>
            <p>Kasir YPBP,</p>
            <p class="name"><b><u>Nia</u></b></p>
        </div>
        <div>
            <p>Accounting,</p>
            <p class="name"><b><u>Nomie</u></b></p>
        </div>
    </div>
</body>

</html>