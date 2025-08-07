<?php

use App\Helpers\DataHelper;
?>
@extends('layouts.app')


@section('content')

<!-- Main Content Area -->
<div class="content-wraper-area">
    <div class="data-table-area">
        <div class="container-fluid">
            <div class="row mt-3">

                <div class="col-12">
                    <div class="card bg-white">

                        <form class="form theme-form" id="input-form" action="{{ url($information['route']) }}/store" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <h5>Refund Iuran</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <label class="form-label" for="select-input-student_id">Pilih Siswa Aktif</label>
                                        <select name="student_id" id="select-input-student_id" class="form-control" select2>

                                        </select>
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <label class="form-label" for="select-input-month">Pilih Bulan</label>
                                        <select name="month" id="select-input-month" class="form-control" select2>
                                            @for ($i = 0; $i < 12; $i++) <?php $val = str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?> <option value="{{ $val }}"><?= $val . " - " . DataHelper::get_month_name($i) ?></option>
                                                @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <label class="form-label" for="txt-input-year">Pilih Tahun</label>
                                        <input type="number" id="txt-input-year" min="2016" max="2099" step="1" value="{{ date('Y') }}" class="form-control" />
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <br>
                                        <button class="btn btn-primary mt-2" style="width: 100%;" type="button" onclick="get_invoice_payment()">Cari Pembayaran</button>
                                    </div>
                                </div>


                            </div>
                        </form>

                    </div>
                </div>

            </div>


            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card bg-white">
                        <div class="card-body">
                            <div class="col-md-12">
                                <table class="table">
                                    <tr>
                                        <th style="color: #000"><b>No</b></th>
                                        <th style="color: #000"><b>Nomor Bayar</b></th>
                                        <th style="color: #000"><b>Kode Iuran</b></th>
                                        <th style="color: #000"><b>Bulan</b></th>
                                        <th style="color: #000"><b>Tahun</b></th>
                                        <th style="color: #000; text-align: right;"><b>Nominal</b></th>
                                        <th style="color: #000; text-align: right;"></th>
                                    </tr>
                                    <tbody id="payment-container">

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td style="color: #000" colspan="5">Total Refund</td>
                                            <td style="color: #000" align="right" id="txt-display-grand_total">0</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td style="color: #000" colspan="4">
                                                Sumber Akun Pengeluaran
                                            </td>
                                            <td style="color: #000" colspan="4">
                                                <select name="finance_account_id" class="form-control" id="select-input-bank_id">
                                                    <option value="1" selected>Cash</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color: #000" colspan="4">
                                                Catatan
                                            </td>
                                            <td style="color: #000" colspan="4">
                                                <textarea name="note" class="form-control" id="txt-input-note"></textarea>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-light" onclick="history.back()" type="button">Tutup</button>
                            <button class="btn btn-primary" type="button" onclick="confirm_refund()">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>


@endsection


@section ('js_after')
<script>
    var can_input = true;
    var selected_student_id = -1;
    var selected_month = -1;
    var selected_year = -1;
    var refund_list = [];

    $(document).ready(function() {

        $("#select-input-student_id").select2({
            ajax: {
                url: "{{ url('/general/search-active-student') }}",
                dataType: 'json',
                delay: 300,
                data: function(params) {
                    return {
                        data: $.trim(params.term)
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            },
            placeholder: "Pilih Siswa"
        });

        $("#select-input-due_id").select2({
            ajax: {
                url: "{{ url('/general/search-due') }}",
                dataType: 'json',
                delay: 300,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            },
            placeholder: "Pilih Iuran"
        });

        $("#select-input-bank_id").select2({
            ajax: {
                dataType: 'json',
                type: "get",
                url: "{{ url('general/search-bank') }}",
                delay: 250,
                data: function(params) {
                    return {
                        data: $.trim(params.term)
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                }
            },
            width: '100%',
            placeholder: "Pilih Bank",
        });

    });




    function get_invoice_payment() {
        loading('show');
        $.ajax({
            type: "GET",
            url: "{{ url('/transaction/payment-refund/get-invoice-payment') }}",
            data: {
                "student_id": $("#select-input-student_id").val(),
                "month": $("#select-input-month").val(),
                "year": $("#txt-input-year").val(),
            },
            success: function(response) {
                selected_student_id = $("#select-input-student_id").val();
                selected_month = $("#select-input-month").val();
                selected_year = $("#txt-input-year").val();

                var invoice_detail_payments = response.invoice_detail_payments;

                $("#payment-container").empty();
                for (var i = 0; i < invoice_detail_payments.length; i++) {

                    var price = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(invoice_detail_payments[i]['price']);


                    $("#payment-container").append(`
                        <tr>
                            <td style="color: #000">${ i + 1 }</td>
                            <td style="color: #000">${ invoice_detail_payments[i]['payment_code'] }</td>
                            <td style="color: #000">${ invoice_detail_payments[i]['invoice_detail_code'] }</td>
                            <td style="color: #000">${ invoice_detail_payments[i]['payment_for_month'] }</td>
                            <td style="color: #000">${ invoice_detail_payments[i]['payment_for_year'] }</td>
                            <td style="color: #000; text-align: right;">${ price }</td>
                            <td style="color: #000">
                                <input type="checkbox" onchange="add_to_refund_list(${i}, '${ invoice_detail_payments[i]['payment_for_month'] }', '${ invoice_detail_payments[i]['payment_for_year'] }', '${ invoice_detail_payments[i]['price'] }', '${ invoice_detail_payments[i]['invoice_detail_payment_id'] }')">
                            </td>
                        </tr>
                    `);
                }
                loading('hide');
            },
            error: function(request, error) {
                selected_student_id = -1;
                selected_month = -1;
                selected_year = -1;

                loading('hide');
                swal.fire({
                    title: "Gagal!",
                    text: "Terjadi kesalahan dalam memproses, harap menghubungi Administrator",
                    icon: "error"
                });
            }
        });
    }

    function add_to_refund_list(id, month, year, price, invoice_detail_payment_id) {

        // Jika ada data maka langsung hapus
        for (var i = 0; i < refund_list.length; i++) {
            if (refund_list[i]['id'] == id) {
                refund_list.splice(i, 1);
                calculate_refund_grand_total();
                return;
            }
        }

        // Jika tidak ada datanya maka tambahkan
        refund_list.push({
            id: id,
            month: month,
            year: year,
            price: price,
            invoice_detail_payment_id: invoice_detail_payment_id
        });

        calculate_refund_grand_total();
    }

    function calculate_refund_grand_total() {
        var grand_total = 0;
        for (var i = 0; i < refund_list.length; i++) {
            grand_total = grand_total + parseFloat(refund_list[i]['price']);
        }

        var formatted_grand_total = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(grand_total);

        $("#txt-display-grand_total").text(formatted_grand_total);
    }


    function confirm_refund() {
        if (!can_input) return;
        swal.fire({
            title: 'Konfirmasi Refund Pembayaran',
            text: 'Refund pembayaran akan dikonfirmasi, lanjutkan?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Tidak",
            confirmButtonText: "Ya"
        }).then((result) => {
            if (result.isConfirmed) {
                loading('show');
                submit_refund();
            }
        });
    }

    function submit_refund() {
        can_input = false;

        var form_data = new FormData();
        form_data.append('_token', '{{ csrf_token() }}');
        form_data.append('student_id', selected_student_id);
        form_data.append('month', selected_month);
        form_data.append('year', selected_year);
        for (var i = 0; i < refund_list.length; i++) {
            form_data.append('refund_list[' + i + '][month]', refund_list[i]['month']);
            form_data.append('refund_list[' + i + '][year]', refund_list[i]['year']);
            form_data.append('refund_list[' + i + '][price]', refund_list[i]['price']);
            form_data.append('refund_list[' + i + '][invoice_detail_payment_id]', refund_list[i]['invoice_detail_payment_id']);
        }
        form_data.append('bank_id', $("#select-input-bank_id").val());
        form_data.append('note', $("#txt-input-note").val());

        $.ajax({
            type: "post",
            url: "{{ url($information['route']) }}/store",
            data: form_data,
            processData: false,
            contentType: false,
            // Jika request berhasil
            success: function (data) {
                loading("hide");
                var response = data;

                swal.fire({
                    title: response.title,
                    text: response.message,
                    icon: response.type,
                    showDenyButton: true,
                    confirmButtonText: "Input lagi",
                    denyButtonText: "Kembali ke halaman utama",
                }).then(function (result) {
                    if (result.isConfirmed) {
                        swal.close();
                        location.reload();
                    } else if (result.isDenied) {
                        location.href = param_redirect_url;
                    }
                });

                // resolve(response);
            },

            // Jika request gagal
            error: function (xhr, status, error) {
                loading("hide");
                var response = xhr.responseJSON;
                if (xhr.status == 406)
                    swal.fire(response.title, response.message, response.type);
                if (xhr.status == 404)
                    swal.fire("Proses Gagal!", "Halaman tidak ditemukan", "error");
                if (xhr.status == 422)
                    swal.fire("Proses gagal!", response.message, "error");
                if (xhr.status == 500)
                    swal.fire(
                        "Internal Servel Error 500",
                        "Hubungi admin untuk mendapatkan bantuan terkait masalah",
                        "error"
                    );

                can_input = true;
                // reject(response);
            },
        });
    }
</script>
@endsection
