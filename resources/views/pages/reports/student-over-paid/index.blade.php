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
                                    Laporan Siswa Lebih Bayar

                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Laporan</a></li>
                                        <li class="breadcrumb-item active">Laporan Siswa Lebih Bayar</li>
                                    </ol>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <form class="form theme-form" id="input-form" action="{{ url('/report/student-over-paid/print') }}" method="get" enctype="multipart/form-data">
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
                                    {{-- check box group_by_student --}}
                                    {{-- <div class="col-6">
                                        <h5>Filter Laporan</h5>
                                        <div class="mb-1">
                                            <input type="checkbox" id="txt-input-group_by_student" name="group_by_student" value="1" />
                                            <label class="form-label" for="txt-input-group_by_student">Berdasarkan Siswa</label>
                                        </div>
                                    </div> --}}

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
</script>
@endsection
