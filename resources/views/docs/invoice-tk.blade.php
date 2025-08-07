<!DOCTYPE html>
<html lang="en">
<?php
use App\Helpers\DataHelper;
use Carbon\Carbon;
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tunggakan SD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .container {
            margin: 0 1cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 0px solid black;
            padding: 5px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #eee;
        }

        table.border,
        table.border th,
        table.border td {
            border: 1px solid black;
        }


        p {
            /* line-height: 0.2; */
            margin-bottom: 3px;
            margin-top: 0;
        }

        .grand-total {
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <table style="width: 100%; border: 0">
            <tr>
                <td style="width: 20%;">
                    <img src="data:image/jpeg;base64,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" width="80px" alt="">
                </td>
                <td style="width: 75%;">
                    <center>
                        <h1 style="margin-top: 0px; margin-bottom: 5px">PG - TK BAPTIS PALEMBANG</h1>
                        <p>Jl. Jenderal Sudirman No.490 Km. 2,5 Telp. (0711) 311238 Palembang 30126</p>
                        <p>Notaris No. 85 Terdaf. Depkeh No.11 Thn.1991</p>
                    </center>
                </td>
                <td style="width: 5%;"></td>
            </tr>
        </table>
    </div>

    <center>
        <hr>
    </center>

    <div class="container">
        <table style="width: 100%; border: 0">
            <tr>
                <td style="width: 50%; vertical-align: top; padding: 0;">
                    <table>
                        <!-- <tr>
                            <td style="width: 35%; padding: 2px;">No</td>
                            <td style="width: 55%; padding: 2px;">: </td>
                        </tr> -->
                        <tr>
                            <td style="width: 35%; padding: 2px;">Hal</td>
                            <td style="width: 65%; padding: 2px;">: Pemberitahuan</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top; padding: 0;">
                    <table>
                        <tr>
                            <td style="width: 65%; padding: 2px; text-align: right">
                                Palembang, &nbsp; {{ Carbon::now()->format('d F Y') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <br>

        <div>
            <p>Kepada Yth,</p>
            <p>Bapak/Ibu Orang tua/Wali Murid</p>
            <p>Dari {{ $student->name }}</p>
            <p>di -</p>
            <p>Tempat</p>
        </div>

        <br>

        <div>
            <p>Dengan hormat,</p>
            <p style="text-align: justify; text-indent: 5%">Melalui surat ini kami memberitahukan kepada orang tua murid/wali murid dari {{ $student->name }}, sehubungan dengan laporan bendahara Sekolah Baptis mengenai daftar nama anak-anak yang masih ada kewajiban sekolah yang belum dilunasi. Oleh sebab itu kami memberitahukan kepada orang tua berikut kewajiban sekolah dari {{ $student->name }} :</p>
        </div>

        <table class="border">
            <tr>
                <th style="text-align: center;">Tunggakan</th>
                <th style="text-align: center;">Yang harus Dilunasi</th>
            </tr>
            @php
                $i = 1;
                $grand_total = 0;
            @endphp
            @foreach ($invoice_details as $invoice_detail)
                <tr>
                    <td style="text-align: center;">{{ $invoice_detail->due_name }} - {{ DataHelper::get_month_name(intval($invoice_detail->payment_for_month) - 1) }} {{ $invoice_detail->payment_for_year }}</td>
                    <td style="text-align: right;">{{ number_format($invoice_detail->price) }}</td>
                </tr>
            @php
                $grand_total += $invoice_detail->price;
            @endphp
            @endforeach
            <tr>
                <td class="grand-total" style="text-align: center;">Total</td>
                <td class="grand-total" style="text-align: right;">{{ number_format($grand_total) }}</td>
            </tr>
        </table>

        <br>

        <div>
            <p style="text-align: justify; text-indent: 5%">Kami mohon kerjasama orang tua untuk melunasi kewajiban sekolah tersebut tepat waktu dan untuk PMB kami mohon kerjasama orang tua untuk melunasi sampai bulan Desember demi kelancaran administrasi sekolah Baptis Palembang.</p>
        </div>

        <br>

        <div>
            <p style="text-align: justify; text-indent: 5%">Demikianlah surat ini kami sampaikan, atas kerjasama dan perhatiannya kami ucapkan terima kasih.</p>
        </div>

        <br>

        <table style="width: 100%; border: 0">
            <tr>
                <td style="width: 60%;">
                </td>
                <td style="width: 30%;">
                    <p>Mengetahui,</p>
                    <p>Kepala TK Baptis,</p>
                    <br><br><br><br>
                    <p>Lia Christiani, S.Pd.K</p>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
