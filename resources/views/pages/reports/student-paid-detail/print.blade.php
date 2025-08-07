@include('pages.reports.report-print-control')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if (Request::get('type'))
    <title>Export</title>
    @else
    <title>Laporan Pembayaran Siswa</title>
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
    <center>
        <h2 style="margin-bottom: 0px">Laporan Pembayaran Siswa</h2>
        <p style="margin: 4px 0; font-size: 1.3em">Sekolah Baptis Palembang</p>
        <p style="margin-top: 5px;">
            Periode: {{ \Carbon\Carbon::parse($date_from)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($date_to)->format('d F Y') }}
        </p>
    </center>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Nama Iuran</th>
                <th>Tagihan</th>
                <th>Metode Bayar</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                $total_payed = 0;
            @endphp
            @foreach ($invoices_payments as $invoices_payment)

            @php
            // $student_va = \App\Models\StudentVaAccount::where('student_id', $invoice->student_id)->first();
            // $bank = \App\Models\Bank::where('id', $student_va->bank_id)->first();
            @endphp

            <?php
                $bill_price = 0;
                $total_bill_payed = 0;
                foreach ($invoice_detail_payments as $invoice_detail_payment) {
                    if ($invoice_detail_payment->id == $invoices_payment->invoice_detail_payment_id) {
                        $bill_price += $invoice_detail_payment->price;
                        $total_bill_payed += $invoice_detail_payment->price;
                    }
                }
            ?>
            <tr>
                <td>{{ $i }}</td>
                <td>{{ \Carbon\Carbon::parse($invoices_payment->date)->format('d-m-Y') }}</td>
                <td>{{ $invoices_payment->student_nis }}</td>
                <td>{{ $invoices_payment->student_name }}</td>
                <td>{{ $invoices_payment->due_name }}</td>
                <td align="right">{{ number_format_custom($invoices_payment->price, "Rp") }}</td>
                <td>{{ $invoices_payment->bank_name }}</td>
                <td align="right">{{ number_format_custom($total_bill_payed, "Rp") }}</td>
            </tr>
            @php
            $i++;
            $total_payed += $total_bill_payed;
            @endphp
            @endforeach
            <tr>
                <td colspan="6" align="right">Total Diterima</td>
                <td align="right">{{ number_format_custom($total_payed, "Rp") }}</td>
            </tr>
        </tbody>
    </table>

    @stack('report-print_control')
    @stack('report-js')

</body>

</html>
