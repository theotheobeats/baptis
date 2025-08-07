@extends('layouts.app')


@section('content')

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
                                    Laporan Detail Pembayaran Siswa

                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Laporan</a></li>
                                        <li class="breadcrumb-item active">Laporan Detail Pembayaran Siswa</li>
                                    </ol>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                <form class="form theme-form" id="input-form" action="{{ url('/report/student-paid-detail/print') }}" method="get" enctype="multipart/form-data">
                    <div class="col-12">
                        <div class="card bg-white">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-name">Pilih Tanggal Dari<span class="text-danger">*</span></label>
                                            <input class="form-control" type="date" name="date_from" value="<?php echo date('Y-m-d'); ?>" />
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-name">Pilih Tanggal Sampai<span class="text-danger">*</span></label>
                                            <input class="form-control" type="date" name="date_to" value="<?php echo date('Y-m-d'); ?>" />
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-due_id">Pilih Iuran</label>
                                            <select name="due_id" id="select-input-due_id" class="form-control">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-classroom_id">Pilih Kelas</label>
                                            <select name="classroom_id" id="select-input-classroom_id" class="form-control">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-bank_id">Pilih Metode Bayar / Bank</label>
                                            <select name="bank_id" id="select-input-bank_id" class="form-control">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    <button class="btn btn-primary" type="submit">Buat Laporan</button>
                                </div>
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
    $(function() {
        $('input[name="daterange"]').daterangepicker({
            opens: 'right'
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    });


    $(document).ready(function() {
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
            placeholder: "Pilih Metode Bayar / Bank",
        });

        $("#select-input-due_id").select2({
            ajax: {
                dataType: 'json',
                type: "get",
                url: "{{ url('general/search-due') }}",
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
            cache: true,
            placeholder: "Pilih Iuran",
        });

        $("#select-input-classroom_id").select2({
            ajax: {
                dataType: 'json',
                type: "get",
                url: "{{ url('general/search-classroom') }}",
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
            cache: true,
            placeholder: "Pilih Kelas",
        });

    });
</script>
@endsection
