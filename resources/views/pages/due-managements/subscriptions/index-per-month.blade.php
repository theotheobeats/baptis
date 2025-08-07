@extends('layouts.app')


@section('content')

<!-- Main Content Area -->
<div class="content-wraper-area">
    <div class="data-table-area">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-12">
                    <div class="card">
                        {{-- <div class="card-body card-breadcrumb">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">
                                    Iuran Siswa per Bulan

                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item active">Iuran Siswa per Bulan</li>
                                    </ol>
                                </h4>
                                <div class="page-title-right">
                                    <a href="#" class="btn btn-primary" id="btn-export-excel" onclick="export_excel('<?= url($information['route']) ?>', '<?= csrf_token() ?>', 'Data <?= $information['title'] ?>')"><i class="fa fa-upload me-2"></i> Export Data</a>
                                    <a href="#" class="btn btn-primary" id="btn-import-excel" data-bs-toggle="modal" data-bs-target="#import-excel-modal"><i class="fa fa-download me-2"></i> Import Data</a>
                                </div>
                            </div>
                        </div> --}}
                        <div class="card-body">
                            <form action="{{ url('/due-management/subscription-per-month-list') }}" method="get">
                                <div class="row">
                                    <input type="hidden" name="filter" value="1">
                                    <div class="col-md-6">
                                        <div class="form-group row mb-3">
                                            <label for="select-input-classroom_id" class="col-sm-4 col-form-label">Kelas</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" name="classroom_id" id="select-input-classroom_id">
                                                    @if (isset($filter_params['selected_classroom']))
                                                        <option value="{{ $filter_params['selected_classroom']->id }}">{{ $filter_params['selected_classroom']->name }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row mb-3">
                                            <label for="select-input-month" class="col-sm-4 col-form-label">Bulan</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" name="payment_for_month" id="select-input-month">
                                                    <option value="" hidden>Pilih Bulan</option>
                                                    <option {{ $filter_params['payment_for_month'] == '01' ? 'selected' : '' }} value="01">Januari</option>
                                                    <option {{ $filter_params['payment_for_month'] == '02' ? 'selected' : '' }} value="02">Februari</option>
                                                    <option {{ $filter_params['payment_for_month'] == '03' ? 'selected' : '' }} value="03">Maret</option>
                                                    <option {{ $filter_params['payment_for_month'] == '04' ? 'selected' : '' }} value="04">April</option>
                                                    <option {{ $filter_params['payment_for_month'] == '05' ? 'selected' : '' }} value="05">Mei</option>
                                                    <option {{ $filter_params['payment_for_month'] == '06' ? 'selected' : '' }} value="06">Juni</option>
                                                    <option {{ $filter_params['payment_for_month'] == '07' ? 'selected' : '' }} value="07">Juli</option>
                                                    <option {{ $filter_params['payment_for_month'] == '08' ? 'selected' : '' }} value="08">Agustus</option>
                                                    <option {{ $filter_params['payment_for_month'] == '09' ? 'selected' : '' }} value="09">September</option>
                                                    <option {{ $filter_params['payment_for_month'] == '10' ? 'selected' : '' }} value="10">Oktober</option>
                                                    <option {{ $filter_params['payment_for_month'] == '11' ? 'selected' : '' }} value="11">November</option>
                                                    <option {{ $filter_params['payment_for_month'] == '12' ? 'selected' : '' }} value="12">Desember</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row mb-3">
                                            <label for="select-input-year" class="col-sm-4 col-form-label">Tahun</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" name="payment_for_year" id="select-input-year">
                                                    <option value="" hidden>Pilih Tahun</option>
                                                    <option {{ $filter_params['payment_for_year'] == '2023' ? 'selected' : '' }} value="2023">2023</option>
                                                    <option {{ $filter_params['payment_for_year'] == '2024' ? 'selected' : '' }} value="2024">2024</option>
                                                    <option {{ $filter_params['payment_for_year'] == '2025' ? 'selected' : '' }} value="2025">2025</option>
                                                    <option {{ $filter_params['payment_for_year'] == '2026' ? 'selected' : '' }} value="2026">2026</option>
                                                    <option {{ $filter_params['payment_for_year'] == '2027' ? 'selected' : '' }} value="2027">2027</option>
                                                    <option {{ $filter_params['payment_for_year'] == '2028' ? 'selected' : '' }} value="2028">2028</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row mb-3">
                                            <label for="select-input-school_year_id" class="col-sm-4 col-form-label">Tahun Ajaran</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" name="school_year_id" id="select-input-school_year_id">
                                                    @if (isset($filter_params['selected_school_year']))
                                                        <option value="{{ $filter_params['selected_school_year']->id }}">{{ $filter_params['selected_school_year']->semester }} {{ $filter_params['selected_school_year']->name }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 justify-content-end align-items-center gap-2" style="display: none" id="btn-reset-filter">
                                        <a href="{{ url('/due-management/subscription-per-month-list') }}" class="btn btn-outline-danger">Reset Filter</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card bg-white">
                        <div class="card-header">
                            <div class="row mt-2">
                                <div class="col">
                                    <h5>List Iuran Siswa per Bulan</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="text" class="form-control mb-3" id="input-table-search" placeholder="Cari" style="width: 100%;">
                            <table id="index-table" class="table table-bordered dt-responsive nowrap data-table-area">
                                <thead>
                                    <tr>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Tagihan</th>
                                        <th>Jumlah Bayar</th>
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

<div class="modal fade" id="invoice_detail_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Iuran</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th><b>No</b></th>
                            <th><b>Nama Tagihan</b></th>
                            <th><b>Bulan</b></th>
                            <th><b>Tahun</b></th>
                            <th style="text-align: right;"><b>Tagihan</b></th>
                            <th style="text-align: right;"><b>Dibayar</b></th>
                            <th style="text-align: right;"><b>Sisa Bayar</b></th>
                        </tr>
                        <tbody id="invoice-detail-container">

                        </tbody>
                    </table>

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
<script src="{{ asset('/js/exceljson/js/xlsx.core.min.js') }}"></script>
<script src="{{ asset('/js/exceljson/js/FileSaver.js') }}"></script>
<script src="{{ asset('/js/exceljson/js/jhxlsx.js') }}"></script>
<script>
    var data_table_search_delay = null;
    var data_table = null;

    var month_list = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    $(document).ready(function() {
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

        $("#select-input-school_year_id").select2({
            ajax: {
                dataType: 'json',
                type: "get",
                url: "{{ url('general/search-school-year') }}",
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
            placeholder: "Pilih Tahun Ajaran",
        });

        $("#select-input-classroom_id").on('change', function() {
            $("#btn-reset-filter").css("display", "flex");
            filter();
        });

        $("#select-input-school_year_id").on('change', function() {
            $("#btn-reset-filter").css("display", "flex");
            filter();
        });

        $("#select-input-month").on('change', function() {
            $("#btn-reset-filter").css("display", "flex");
            filter();
        });

        $("#select-input-year").on('change', function() {
            $("#btn-reset-filter").css("display", "flex");
            filter();
        });

        filter();
    });

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
                url: "{{ url('/due-management/subscription-per-month-list') }}",
                data: (d) => {
                    d.classroom_id = $("#select-input-classroom_id").val();
                    d.school_year_id = $("#select-input-school_year_id").val();
                    d.payment_for_month = $("#select-input-month").val();
                    d.payment_for_year = $("#select-input-year").val();
                },
            },
            order: [
                [0, 'desc']
            ],
            columns: [
                {
                    name: "students.nis",
                    data: "student_nis"
                },
                {
                    name: "students.name",
                    data: "student_name"
                },
                {
                    name: "classrooms.name",
                    data: "classroom_name"
                },
                {
                    name: "school_years.name",
                    data: "school_year_name"
                },
                {
                    name: "invoice_details.payment_for_month",
                    data: "payment_for_month"
                },
                {
                    name: "invoice_details.payment_for_year",
                    data: "payment_for_year"
                },
                {
                    name: "total_price",
                    data: "total_price",
                },
                {
                    name: "total_payed_amount",
                    data: "total_payed_amount"
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

    function view_paid_invoice_detail(invoice_id, payment_for_month, payment_for_year) {
        loading('show');
        $.ajax({
            type: "GET",
            url: "{{ url('/transaction/due-payment/get-student-paid-due-detail') }}",
            data: {
                "invoice_id": invoice_id,
                "payment_for_month": payment_for_month,
                "payment_for_year": payment_for_year
            },
            success: function(response) {
                var student_dues = response.student_dues;
                var price_total = 0;
                var payed_total = 0;
                var remaining_total = 0;

                $("#invoice-detail-container").empty();
                for (var i = 0; i < student_dues.length; i++) {
                    var due_id = student_dues[i]['id']
                    var price_raw = student_dues[i]['price']

                    var price = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(student_dues[i]['price']);

                    var payed_amount = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(student_dues[i]['payed_amount']);

                    var remaining_amount = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(student_dues[i]['price'] - student_dues[i]['payed_amount']);

                    $("#invoice-detail-container").append(`
                    <tr>
                        <td style="color: #000">${ i + 1 }</td>
                        <td style="color: #000">${ student_dues[i]['due_name'] }</td>
                        <td style="color: #000">${ month_list[parseInt(student_dues[i]['payment_for_month']) - 1] }</td>
                        <td style="color: #000">${ student_dues[i]['payment_for_year'] }</td>
                        <td style="color: #000" align="right">${ price }</td>
                        <td style="color: #000" align="right">${ payed_amount }</td>
                        <td style="color: #000" align="right">${ remaining_amount }</td>
                    </tr>
                `);
                    price_total += student_dues[i]['price'];
                    payed_total += student_dues[i]['payed_amount'];
                    remaining_total += student_dues[i]['price'] - student_dues[i]['payed_amount'];
                }

                var formatted_price_total = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(price_total);

                var formatted_payed_total = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(payed_total);

                var formatted_remaining_total = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(remaining_total);


                $("#invoice-detail-container").append(`
                    <tr>
                        <td style="color: #000" colspan="4">Total</td>
                        <td style="color: #000" align="right">${ formatted_price_total }</td>
                        <td style="color: #000" align="right">${ formatted_payed_total }</td>
                        <td style="color: #000" align="right">${ formatted_remaining_total }</td>
                    </tr>
                `);

                loading('hide');
                $("#invoice_detail_modal").modal('show');
            },
            error: function(request, error) {
                loading('hide');
                swal.fire({
                    title: "Gagal!",
                    text: "Terjadi kesalahan dalam memproses, harap menghubungi Administrator",
                    icon: "error"
                });
            }
        });
    }

</script>
@endsection
