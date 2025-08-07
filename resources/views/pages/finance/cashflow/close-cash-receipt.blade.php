<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} | @yield('app_title')</title>
</head>

<style>
    body {
        margin: 0;
        font-family: arial, verdana, sans-serif;
        /* font-family: 'Courier New', Courier, monospace; */
        font-size: 1.1rem;
        text-transform: uppercase;
    }

    .product_row {
        margin-bottom: 8px;
    }
</style>

<body>
    <table width="300px">
        <tr>
            <td>
                <center style="font-size: 1.3rem;">
                    <img src="{{ asset('/ghl_kios.jpeg') }}" width="100px">
                </center>
                <center style="font-size: 0.9rem;">
                    <span></span>
                </center>

            </td>
        </tr>
        <tr>
            <td>
                <hr style="border: dashed 1px black;" />
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td colspan="2" align="center"><span>{{ $cash_account->created_at }}</span></td>
                    </tr>
                    <tr>
                        <td style="font-size: 14px;">Ditutup oleh:</td>
                        <td style="font-size: 14px;" align="right">{{ $employee->name }}</td>
                    </tr>
            </td>
        </tr>
        <tr>
            <td style="font-size: 14px;"><span>{{ date('d-m-Y', strtotime(substr($cash_account->created_at, 0, 10))) }}</span></td>
            <td align="right" style="font-size: 14px;"><span>{{ substr($cash_account->created_at, 10,9) }}</span></td>
        </tr>
    </table>
    </td>
    </tr>
    <tr>
        <td>
            <hr style="border: dashed 1px black;" />
        </td>
    </tr>
    <tr>
        <td id="products">
            <table width="100%" class="product_row">
                <tr>
                    <td colspan="1">
                        <span>Saldo Akhir</span>
                    </td>
                </tr>
                <tr>
                    <td><span>{{ $cash_account->closing_balance }}</span></td>
                </tr>
                <tr>
                    <td colspan="1">
                        <span>Saldo Pengeluaran</span>
                    </td>
                </tr>
                <tr>
                    <td><span>{{ $cash_account->expense_balance }}</span></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <hr style="border: dashed 1px black;" />
        </td>
    </tr>
    <tr>
        <td id="total_n_friends">
            <table width="100%">
                <tr style="font-weight: bold; transform: scale(1, 1.25);">
                    <td><span>Saldo Awal</span></td>
                    <td></td>
                    <td align="right"><span>{{ number_format($cash_account->beginning_balance) }}</span></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <hr style="border: dashed 1px black;" />
        </td>
    </tr>
    <tr>
        <td>
            <center style="font-size: 1.3rem;">
                Terima Kasih
            </center>
        </td>
    </tr>
    <tr>
        <td>
            <hr style="border: dashed 1px black;" />
        </td>
    </tr>
    </table>


    <div style="height: 100px; width:100px; background-color:#fff;"></div>
    <span style="color: #eee;">.</span>

</body>
<script>
    window.print();
    setTimeout(function() {
        window.close();
    }, 1000);
</script>

</html>