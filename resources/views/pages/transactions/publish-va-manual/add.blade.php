<?php

use App\Helpers\DataHelper;
?>
@extends('layouts.app')


@section('content')

<!-- Main Content Area -->
<div class="content-wraper-area">
    <div class="data-table-area">
        <div class="container-fluid">
            <div class="card bg-white">

                <form class="form theme-form" id="input-form" action="{{ url($information['route']) }}/store" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <h5>Terbit VA Manual</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 mt-0">
                                <label class="form-label" for="txt-input-va_number">Nomor VA <span class="text-danger">*</span></label>
                                <input name="va_number" type="number" id="txt-input-va_number" class="form-control" placeholder="Masukkan Nomor VA" required>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="form-label" for="txt-input-name">Nama Siswa <span class="text-danger">*</span></label>
                                <select name="student_id" class="form-control" id="select-input-student_id" required></select>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="form-label" for="txt-input-va_number">Jumlah Tagihan <span class="text-danger">*</span></label>
                                <input name="amount" type="amount" id="txt-input-amount" class="form-control" placeholder="Jumlah Tagihan" required>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="form-label" for="txt-input-note">Catatan</label>
                                <textarea name="note" class="form-control" id="txt-input-note"></textarea>
                            </div>
                            <div class="col-md-8"></div>
                            <div class="col-md-2 mt-2">
                                <a href="{{ url('/transaction/publish-va-manual') }}" class="btn btn-secondary mt-2" style="width: 100%;" type="button">Kembali</a>
                            </div>
                            <div class="col-md-2 mt-2">
                                <button class="btn btn-primary mt-2" style="width: 100%;" type="submit">Terbit VA Manual</button>
                            </div>
                        </div>


                    </div>
                </form>

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

    $("#input-form").submit(function(e) {
        e.preventDefault();
        confirm_refund();
    });



    function confirm_refund() {
        if (!can_input) return;
        swal.fire({
            title: 'Konfirmasi Terbit Tagihan Manual',
            text: 'Konfirmasi terbit tagihan manual, pastikan data sudah terisi dengan benar, lanjutkan?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Tidak",
            confirmButtonText: "Ya"
        }).then((result) => {
            if (result.isConfirmed) {
                can_input = false;
                loading('show');

                let form_data = new FormData($("#input-form")[0]);
                submit_form_data("{{ url($information['route']) }}/store", form_data, {
                    reload: "Input Lagi",
                    close: "Tutup Halaman",
                    redirect_url: "{{ $information['route'] }}"
                }, {
                    success: function () { can_input = false; },
                    error: function () { can_input = true; }
                });
            }
        });
    }
</script>
@endsection
