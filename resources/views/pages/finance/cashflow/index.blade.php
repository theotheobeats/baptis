@extends('layouts.app')


@section('content')

<style>
    .select2-container--default .select2-selection--single {
        padding-right: 16px; /* Beri ruang tambahan buat clear button */
    }
</style>

<!-- Main Content Area -->
<div class="content-wraper-area">
    <div class="data-table-area">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body card-breadcrumb">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">
                                    {{ $information['title'] }}

                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                                        <li class="breadcrumb-item active">{{ $information['title'] }}</li>
                                    </ol>
                                </h4>
                                <div class="page-title-right">
                                    <a href="{{ url($information['route'] . '/create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Input Data</a>
                                    <a href="{{ url($information['route'] . '/create2') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Input Data (Baru)</a>
                                    <?php

                                    use App\Helpers\UserInfoHelper;
                                    use App\Models\CashAccount;

                                    $has_open_cash_account = CashAccount::where("employee_id", "=", UserInfoHelper::employee_id())->whereNull("close_time")->get();
                                    ?>
                                    @if (count($has_open_cash_account) > 0)
                                    <a href="#" class="btn btn-primary" id="btn-import-excel" data-bs-target="#close_sales_account_cash-modal" data-bs-toggle="modal"><i class="fa fa-crate me-2"></i>Tutup Akun Kas</a>
                                    @else
                                    <a href="{{ url('/finance/cashflow/cash-account/open') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Buka Akun Kas</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card bg-white">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="form-group col-sm-3 md-6">
                                    <label class="form-label" for="txt-input-name"><b>Dari Tanggal</b></label>
                                    <input class="form-control date_from" type="date" autocomplete="off" id="date_from" name="date_from" placeholder="DD/MM/YYYY">
                                </div>
                                <div class="form-group col-sm-3 md-6">
                                    <label class="form-label" for="txt-input-name"><b>Sampai Tanggal</b> </label>
                                    <input class="form-control date_to" type="date" autocomplete="off" id="date_to" name="date_to" placeholder="DD/MM/YYYY">
                                </div>
                                <div class="form-group col-sm-6 md-6">
                                    <label class="form-label" for="txt-input-name"><b>Akun Kas</b> </label>
                                    <select name="cash_account_id" style="width: 100%;" class="form-control select select2" id="select-filter-cash_account_id"></select>
                                </div>
                                <div class="form-group col-sm-6 mt-2">
                                    <label class="form-label" for="txt-filter-note"><b>Catatan</b> </label>
                                    <input class="form-control" type="text" autocomplete="off" id="txt-filter-note" name="note" placeholder="Catatan">
                                </div>
                                <div class="form-group col-sm-6 mt-3">
                                <label class="form-label"><b></b> </label>
                                    <button type="button" style="width:100%; height:40px;" class="btn btn-sm btn-primary" onclick="filter()">Filter Data </button>
                                </div>
                            </div>
                            <input type="text" class="form-control mb-3" id="input-table-search" placeholder="Cari" style="width: 100%;">
                            <div class="table-responsive">
                                <table id="index-table" class="table table-bordered dt-responsive nowrap data-table-area">
                                    <thead>
                                        <tr>
                                            <!-- <th>No</th> -->
                                            <th>Tanggal</th>
                                            <th>Kode Akun</th>
                                            <th>Akun Kas</th>
                                            <!-- <th>Kode</th> -->
                                            <th>Nomor Transaksi</th>
                                            <th>Debit</th>
                                            <th>Kredit</th>
                                            <th>Aksi</th>
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
    </div>
</div>

<div class="modal fade" id="close_sales_account_cash-modal" tabindex="-1" role="dialog" aria-labelledby="import-excel-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="close_cash_account-form" action="{{ url('/finance/cashflow/cash-account/do-close') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Tutup Akun Kas</h3>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="txt-input-cash_amount">Cash on Hand</label>
                        <input type="text" name="closing_balance" id="txt-input-cash_amount" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="txt-input-expense">Pengeluaran</label>
                        <input type="text" name="expense_balance" id="txt-input-expense" class="form-control" value="0" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-secondary" type="submit" id="close-cash-btn">Tutup Kas Penjualan</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection


@section ('js_after')
<script>
    var data_table_search_delay = null;
    var data_table = null;

    $(document).ready(function() {
        $("#select-filter-cash_account_id").select2({
            ajax: {
                url: "{{ route('finance.coa.search') }}",
                dataType: 'json',
                delay: 300,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            },
            placeholder: "Pilih Akun",
            allowClear: true
        });
        getDatatable();
        filter();
    })

    function filter() {
        getDatatable();
    }

    function getDatatable() {
        if (data_table !== null) {
            data_table.destroy();
        }

        data_table = $("#index-table").DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            pageLength: 5,
            searchDelay: 2000,
            ajax: {
                url: "{{ url($information['route']) }}",
                data: (d) => {
                    d.date_from = $("#date_from").val(),
                    d.date_to = $("#date_to").val()
                    d.cash_account_id = $("#select-filter-cash_account_id").val()
                    d.note = $("#txt-filter-note").val()
                },
            },
            order: [
                [0, 'desc']
            ],
            columns: [
                // {
                //     data: 'DT_RowIndex',
                //     sortable: false,
                //     searchable: false
                // },
                {
                    name: "finance_cash_flows.transaction_date",
                    data: "transaction_date"
                },
                {
                    name: "finance_accounts.code",
                    data: "account_code"
                },
                {
                    name: "finance_accounts.name",
                    data: "account_name"
                },
                // {
                //     name: "finance_cash_flows.code",
                //     data: "code"
                // },
                {
                    name: "finance_cash_flows.transaction_number",
                    data: "transaction_number"
                },
                {
                    name: "finance_cash_flows.debit",
                    data: "debit"
                },
                {
                    name: "finance_cash_flows.credit",
                    data: "credit"
                },
                {
                    data: "action",
                    searchable: false,
                    sortable: false
                },
            ],
        });

        $('#input-table-search').keyup(function() {
            clearTimeout(data_table_search_delay);
            data_table_search_delay = setTimeout(() => {
                data_table.search($(this).val()).draw();
            }, 350);
        })

    };


    function delete_confirm(url) {
        swal.fire({
            title: 'Konfirmasi Hapus Data',
            text: 'Apakah anda yakin ingin menghapus data berikut?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Batal",
            confirmButtonText: "Ya, Hapus"
        }).then((result) => {
            if (result.isConfirmed) {
                loading('show');
                $.ajax({
                    type: "post",
                    url: url,
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        loading('hide');
                        var response = data;
                        swal.fire(response.title, response.message, response.type).then((result) => {
                            data_table.ajax.reload(null, false);
                        });
                    },
                    error: function(xhr, status, error) {
                        loading('hide')
                        var response = xhr.responseJSON;
                        if (xhr.status == 406) {
                            swal.fire(response.title, response.message, response.type);
                        }
                        if (xhr.status == 404) {
                            swal.fire("Proses Gagal!", "Halaman tidak ditemukan", "error");
                        }
                        if (xhr.status == 500) {
                            swal.fire("Internal Servel Error 500", "Hubungi admin untuk mendapatkan bantuan terkait masalah", "error");
                        }
                    }
                });
            }
        });
    }

    function verify_confirm(url) {
        swal.fire({
            title: 'Verifikasi Arus Kas',
            text: 'Apakah anda yakin ingin memverifikasi data berikut?',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Batal",
            confirmButtonText: "Ya, Verifikasi"
        }).then((result) => {
            if (result.isConfirmed) {
                loading('show');
                $.ajax({
                    type: "post",
                    url: url,
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        loading('hide');
                        var response = data;
                        swal.fire(response.title, response.message, response.type).then((result) => {
                            data_table.ajax.reload(null, false);
                        });
                    },
                    error: function(xhr, status, error) {
                        loading('hide')
                        var response = xhr.responseJSON;
                        if (xhr.status == 406) {
                            swal.fire(response.title, response.message, response.type);
                        }
                        if (xhr.status == 404) {
                            swal.fire("Proses Gagal!", "Halaman tidak ditemukan", "error");
                        }
                        if (xhr.status == 500) {
                            swal.fire("Internal Servel Error 500", "Hubungi admin untuk mendapatkan bantuan terkait masalah", "error");
                        }
                    }
                });
            }
        });
    }

    $("#close_cash_account-form").submit(function(e) {
        e.preventDefault();

        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#close_cash_account-form")[0]);

        $.ajax({
            type: "post",
            url: "{{ url('/finance/cashflow/cash-account/do-close') }}",
            data: form_data,
            processData: false,
            contentType: false,
            // Jika request berhasil
            success: function(data) {
                loading("hide");
                var response = data;
                console.log(response)
                window.open("{{ url('/finance/cashflow/cash-account/close/print') }}" + "/" + data.id, '_blank');
                location.reload();
            },

            // Jika request gagal
            error: function(xhr, status, error) {
                loading("hide");
                var response = xhr.responseJSON;
                if (xhr.status == 406) swal(response.title, response.message, response.type);
                if (xhr.status == 404) swal("Proses Gagal!", "Halaman tidak ditemukan", "error");
                if (xhr.status == 422) swal("Proses gagal!", response.message, "error");
                if (xhr.status == 500) swal("Internal Servel Error 500", "Hubungi admin untuk mendapatkan bantuan terkait masalah", "error");
            },
        });
    });
</script>
@endsection