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
                                    Laporan Arus Kas

                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Laporan</a></li>
                                        <li class="breadcrumb-item active">Laporan Arus Kas</li>
                                    </ol>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                <form class="form theme-form" id="input-form" action="{{ url('/report/cashflow/print') }}" method="get" enctype="multipart/form-data">
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
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-account_id">Filter Jenis Akun Kas</label>
                                            <select name="account_id[]" id="select-input-account_id" class="form-control" multiple="multiple">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-4">
                                                <label class="form-label" for="select-input-employee_id">Dibuat Oleh</label>
                                                <select name="employee_id" id="select-input-employee_id" class="form-control">
                                                </select>
                                            </div>
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
            opens: 'right',
            locale: {
                format: 'DD/MM/YYYY'
            }
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    });

    $(document).ready(function() {
        $("#select-input-employee_id").select2({
            ajax: {
                dataType: 'json',
                type: "get",
                url: "{{ url('general/search-employee') }}",
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
            placeholder: "Pilih Pegawai",
        });

        $("#select-input-account_id").select2({
            ajax: {
                url: "{{ url('/general/search-finance-account') }}",
                data: function(q, page) {
                    return {
                        data: $.trim(q.term)
                    };
                },
                dataType: 'json',
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
            delay: 300,
            placeholder: "Pilih Jenis Akun Kas"
        });
    });

</script>
@endsection
