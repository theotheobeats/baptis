@extends('layouts.app')


@section('content')

<!-- Main Content Area -->
<div class="content-wraper-area">
    <div class="data-table-area">
        <div class="container-fluid">
            <div class="row mt-3">

                <div class="card text-white bg-primary mb-3 col-md-3 p-4">
                    <div class="card-body">

                        <h4 class="text-white">Siswa Aktif Saat Ini</h4>
                        <hr>
                        <h3 class="text-white">{{ $student_active_count }}</h3>

                    </div>
                </div>
                <div class="card text-white bg-secondary mb-3 col-md-3 p-4">
                    <div class="card-body">
                        <h4 class="text-white">Siswa yang Belum Bayar</h4>
                        <hr>
                        <h3 class="text-white">{{ $student_late_payment == null ? 0 : $student_late_payment->amount }}</h3>
                    </div>
                </div>
                <div class="card text-white bg-success mb-3 col-md-3 p-4">
                    <div class="card-body">
                        <h4 class="text-white">Pemasukkan Hari Ini</h4>
                        <hr>
                        <h3 class="text-white">Rp. {{ number_format($today_income) }}</h3>
                    </div>
                </div>
                <div class="card text-white bg-danger mb-3 col-md-3 p-4">
                    <div class="card-body">
                        <h4 class="text-white">Jumlah Pembayaran Virtual Account Hari Ini</h4>
                        <hr>
                        <h3 class="text-white">{{ $virtual_account }}</h3>
                    </div>
                </div>


                <div class="card mb-3 col-md-12 p-4">
                    <div class="card-body">
                        <h4>Informasi API Whatsapp</h4>
                        <hr>
                        <table class="table">
                            <tr>
                                <th>Nama</th>
                                <th>Jumlah</th>
                            </tr>
                            <tr>
                                <td>Status Bot</td>
                                {{-- <td>{{ number_format($whatsapp_bot['data'][0]['wa_bot_status']) ? 'Online' : 'Offline' }}</td> --}}
                            </tr>
                            <tr>
                                <td>Saldo</td>
                                {{-- <td>{{ number_format($whatsapp_bot['data'][0]['wa_bot_saldo']) }}</td> --}}
                            </tr>
                            <tr>
                                <td>Free Session</td>
                                {{-- <td>{{ number_format($whatsapp_bot['data'][0]['wa_bot_free_session']) }}</td> --}}
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card mb-3 col-md-12 p-2 bg-white">
                    <div class="card-body">
                        <h4>Riwayat Pembayaran Bank Maspion</h4>
                        <hr>

                        <input type="text" class="form-control mb-3" id="api-espay-payment-notifications-table-search" placeholder="Cari" style="width: 100%;">
                        <table id="api-espay-payment-notifications-table" class="table table-bordered dt-responsive nowrap data-table-area">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>SS JSON</th>
                                    <th>Jumlah</th>
                                    <th>Sukses (1/0)</th>
                                    <th>Pesan</th>
                                    <th>Rekonsiliasi ID</th>
                                    <th>Order ID</th>
                                    <th>Tanggal Rekonsiliasi</th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="info-modal" tabindex="-1" role="dialog" aria-labelledby="info-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="data">

                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection


@section ('js_after')

<script>
    var data_table_search_delay = null;
    var api_espay_payment_notifications_table = null;

    $(function() {

        api_espay_payment_notifications_table = $("#api-espay-payment-notifications-table").DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            pageLength: 5,
            searchDelay: 2000,
            ajax: {
                url: "{{ url('table/api-espay-payment-notifications-maspion') }}",
            },
            order: [
                [0, 'desc']
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    sortable: false,
                    searchable: false
                },
                {
                    name: "api_espay_payment_notifications.ss_json",
                    data: "ss_json"
                },
                {
                    name: "api_espay_payment_notifications.amount",
                    data: "amount"
                },
                {
                    name: "api_espay_payment_notifications.success_flag",
                    data: "success_flag"
                },
                {
                    name: "api_espay_payment_notifications.error_message",
                    data: "error_message"
                },
                {
                    name: "api_espay_payment_notifications.reconcile_id",
                    data: "reconcile_id"
                },
                {
                    name: "api_espay_payment_notifications.order_id",
                    data: "order_id"
                },
                {
                    name: "api_espay_payment_notifications.reconcile_datetime",
                    data: "reconcile_datetime"
                }
            ],
        });

        $('#api-espay-payment-notifications-table-search').keyup(function() {
            clearTimeout(data_table_search_delay);
            data_table_search_delay = setTimeout(() => {
                data_table.search($(this).val()).draw();
            }, 350);
        })

    });

    function show(string) {
        var data = JSON.parse(string);
        var data_str = JSON.stringify(data, null, 2);

        $('#info-modal .data').html('<pre>' + data_str + '</pre>');
        $('#info-modal').modal('show');
    }
</script>

@endsection
