<?php

use App\Helpers\UserInfoHelper;
?>
@extends('layouts.app')

@section('content')
<!-- Container-fluid starts-->
<div class="container-fluid">
    <div class="breadcrumb">
        <a class="breadcrumb-item" href="#">
            <span class="breadcrumb-icon">
                <i data-feather="home"></i>
            </span>
            <span class="breadcrumb-text">Home</span>
        </a>
        <a class="breadcrumb-item" href="#">
            <span class="breadcrumb-icon">
                <i data-feather="database"></i>
            </span>
            <span class="breadcrumb-text">Penjualan</span>
        </a>
        <a class="breadcrumb-item" href="#">
            <span class="breadcrumb-icon">
                <i data-feather="database"></i>
            </span>
            <span class="breadcrumb-text">Buka Akun Kas</span>
        </a>
    </div>
    <div class="row starter-main">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border">
                    <h3>Buka Akun Kas</h3>
                </div>

                <form class="form theme-form" id="input-form" action="/finance/cashflow/cash-account/do-open" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <input type="text" value="{{ $type ?? 'default' }}" name="type" id="type" hidden>
                        <div class="row">
                            <div class="col">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-name">Nama Karyawan <span class="text-danger">*</span></label>
                                    <input class="form-control" id="txt-input-name" name="name" type="text" placeholder="Nama" required value="<?= UserInfoHelper::employee()->name ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-beginning_balance">Saldo Awal <span class="text-danger">*</span></label>
                                    <input class="form-control" id="txt-input-beginning_balance" name="beginning_balance" type="text" placeholder="Saldo Awal" required>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-light" onclick="history.back()" type="button">Tutup</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- Container-fluid Ends-->
@endsection


@section('js_after')
<script>
    $(document).ready(function() {
        $("#txt-input-beginning_balance").on("input", function() {
            var value = $(this).val().replace(/,/g, "");
            var formatted_value = add_commas(value);
            $(this).val(formatted_value);
        });


        function add_commas(value) {
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    });


    $("#input-form").submit(function(e) {
        e.preventDefault();

        var type = $("#type").val();

        var beginning_balance = $("#txt-input-beginning_balance").val().replace(/,/g, "");
        $("#txt-input-beginning_balance").val(beginning_balance);


        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#input-form")[0]);

        $.ajax({
            type: "post",
            url: "{{ url('/finance/cashflow/cash-account/do-open') }}",
            data: form_data,
            processData: false,
            contentType: false,
            // Jika request berhasil
            success: function(data) {
                loading("hide");
                var response = data;
                console.log(response)
                window.location.href = "{{ url('/finance/cashflow/cash-account/open/print') }}" + "/" + data.id + "/" + type;
                // window.open("{{ url('/sales/sales-cash-account/open/print') }}" + "/" + data.id, '_blank');
                // Reload the current page
                // location.reload();
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
