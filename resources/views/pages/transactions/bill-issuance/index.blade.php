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
                                    {{ $information['title'] }}
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item active"><a href="javascript: void(0);">Tagihan</a></li>
                                    </ol>
                                </h4>
                                <div class="page-title-right">
                                    <a href="#" class="btn btn-primary" id="btn-export-excel" data-bs-toggle="modal" data-bs-target="#export-excel-modal"><i class="fa fa-upload me-2"></i> Export Data</a>
                                    {{-- <a href="#" class="btn btn-primary" id="btn-export-excel" onclick="export_excel('<?= url($information['route']) ?>', '<?= csrf_token() ?>', 'Data <?= $information['title'] ?>')"><i class="fa fa-upload me-2"></i> Export Data</a> --}}
                                    {{-- <a href="#" class="btn btn-primary" id="btn-import-excel" data-bs-toggle="modal" data-bs-target="#import-excel-modal"><i class="fa fa-download me-2"></i> Import Data</a> --}}
                                    <button type="button" class="btn btn-danger" data-bs-target="#publish-bill-date-modal" data-bs-toggle="modal">Terbit Tagihan Manual</button>
                                    <button type="button" class="btn btn-danger" data-bs-target="#publish-bill-date-per-student-modal" data-bs-toggle="modal">Terbit Tagihan Per Siswa</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card bg-white">
                        <div class="card-body">
                            <input type="text" class="form-control mb-3" id="input-table-search" placeholder="Cari" style="width: 100%;">
                            <table id="index-table" class="table table-bordered dt-responsive nowrap data-table-area">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Total Tagihan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="col-12">
                    {{-- <button type="button" class="btn btn-primary" style="width: 100%;" onclick="publish_invoice()">Terbit Tagihan</button> --}}

                    <br><br><br><br><br><br><br><br><br>
                    <br><br><br><br><br><br><br><br><br>
                    <br><br><br><br><br><br>
                    <!-- <h5 class="txt-title">Input {{ $information['title'] }}</h5>
                    <div class="card bg-white">


                        <form class="form theme-form" id="input-form" action="{{ url($information['route']) }}/store" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="col">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-input-student_id">Siswa</label>
                                        <select name="student_id" id="select-input-student_id" class="form-control select2" onchange="get_student_dues()">

                                        </select>
                                    </div>
                                </div>

                                <hr>

                                <h5>List Kegiatan yang Diambil Siswa</h5>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Kegiatan</th>
                                            <th>Nilai Tagihan</th>
                                            <th>Aksi</th>
                                        </tr>
                                        <tbody id="student-due-container">

                                        </tbody>
                                    </table>

                                </div>

                                <h5>Total Tagihan <span id="txt-display-grand_total">0</span></h5>

                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-light" onclick="history.back()" type="button">Tutup</button>
                                <button class="btn btn-primary" type="submit">Simpan</button>
                            </div>
                        </form>

                    </div>
                </div> -->

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="import-excel-modal" tabindex="-1" role="dialog" aria-labelledby="import-excel-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Import Data Excel</h3>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="input-import-excel">Pilih File Excel</label>
                    <input type="file" id="input-import-excel" class="form-control">
                    <small class="text-danger">*Harap gunakan format file excel yang sudah diexport</small>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Tutup</button>
                <button class="btn btn-secondary" type="button" onclick="import_excel('<?= url($information['route']) ?>', '<?= csrf_token() ?>')">Import Data</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="export-excel-modal" tabindex="-1" role="dialog" aria-labelledby="export-excel-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Export Data</h3>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="export-form">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="mb-2">
                            <label class="form-label" for="input-export-start_date">Pilih Tanggal Dari</label>
                            <input type="date" id="input-export-start_date" class="form-control">
                        </div>
                        <div>
                            <label class="form-label" for="input-export-end_date">Pilih Tanggal Sampai</label>
                            <input type="date" id="input-export-end_date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-secondary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="publish-bill-date-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Publish Tagihan</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="url" hidden>
                <div class="row">
                    <div class="col">
                        <label for="due_price">Tagihan Bulan</label>
                        <input type="date" class="form-control" name="biil_date" id="txt-input-bill_date">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="publish_invoice()">Terbitkan Tagihan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="publish-bill-date-per-student-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Publish Tagihan Per Siswa</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="url" hidden>
                <div class="row mb-3">
                    <div class="col">
                        <label for="due_price">Pilih Siswa</label>
                        <select style="width: 100%;" name="student_id" class="form-control" id="single-select-input-student_id"></select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="due_price">Tagihan Dari Tanggal</label>
                        <input type="date" class="form-control" name="biil_date" id="single-txt-input-date_from">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="due_price">Tagihan Sampai Tanggal</label>
                        <input type="date" class="form-control" name="biil_date" id="single-txt-input-date_to">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="publish_student_invoice()">Terbitkan Tagihan</button>
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

    $(function() {
        data_table = $("#index-table").DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            pageLength: 5,
            searchDelay: 2000,
            ajax: {
                url: "{{ url($information['route']) }}"
            },
            // order: [
            //     [3, 'desc']
            // ],
            columns: [{
                    data: 'DT_RowIndex',
                    sortable: false,
                    searchable: false
                },
                {
                    name: "students.nis",
                    data: "student_nis"
                },
                {
                    name: "students.name",
                    data: "student_name"
                },
                {
                    name: "bill_price",
                    data: "bill_price"
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

    });

    var grand_total = 0;
    var bill_list = [];

    $("#select-input-due_id").select2({
        placeholder: "Pilih Tagihan",
        width: "100%"
    });

    $("#single-select-input-student_id").select2({
        ajax: {
            dataType: 'json',
            type: "get",
            url: "{{ url('general/search-student') }}",
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
        placeholder: "Pilih Siswa",
        dropdownParent: $("#publish-bill-date-per-student-modal"),
    });

    $("#export-form").submit(function(e) {
        e.preventDefault();
        var start_date = $("#input-export-start_date").val();
        var end_date = $("#input-export-end_date").val();

        if (start_date == "" || end_date == "") {
            swal.fire("Peringatan!", "Harap pilih tanggal terlebih dahulu", "warning");
            return;
        }

        loading("show");
        $.ajax({
            type: "post",
            url: "{{ url($information['route']) }}" + "/export/excel",
            data: {
                _token: "{{ csrf_token() }}",
                start_date: start_date,
                end_date: end_date
            },
            success: function (data) {
                loading("hide");
                var response = data;
                // console.log(response);
                var tabular_data = [
                    {
                        sheetName: "Sheet1",
                        data: response,
                    },
                ];
                Jhxlsx.export(tabular_data, {
                    fileName: "Data Tagihan",
                });
            },
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
            },
        });
    });

    $("#input-form").submit(function(e) {
        e.preventDefault();


        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#input-form")[0]);
        submit_form_data("{{ url($information['route']) }}/store", form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        })
    });

    function get_student_dues() {
        loading('show');
        $.ajax({
            type: "GET",
            url: "{{ url('/transaction/bill-issuance/get-student-dues') }}",
            data: {
                "student_id": $("#select-input-student_id").val()
            },
            success: function(response) {
                var student_dues = response.student_dues;
                grand_total = 0;
                $("#student-due-container").empty();
                for (var i = 0; i < student_dues.length; i++) {
                    $("#student-due-container").append(`
                        <tr>
                            <td>${ i + 1 }</td>
                            <td>${ student_dues[i]['due_name'] }</td>
                            <td>${ student_dues[i]['due_price'] }</td>
                            <td>
                                <input type="hidden" name="" value="${ student_dues[i]['due_id'] }" id="txt-input-due_id-${ student_dues[i]['due_id'] }" />
                                <input type="hidden" name="" value="${ student_dues[i]['due_price'] }" id="txt-input-due_price-${ student_dues[i]['due_id'] }" />
                                <input type="checkbox" name="add_to_bill[]" id="cb-input-add_to_bill-${ student_dues[i]['due_id'] }" onchange="add_to_bill(${ student_dues[i]['due_id'] })">
                            </td>
                        </tr>
                    `);
                }
                loading('hide');
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

    function add_to_bill(id) {
        var selected_dues = $("#txt-input-due_price-" + id).val();
        if ($("#cb-input-add_to_bill-" + id).is(":checked")) {
            grand_total = grand_total + parseInt(selected_dues);
            $("#txt-input-due_id-" + id).attr("name", "due_id[]");
            $("#txt-input-due_price-" + id).attr("name", "due_price[]");
        } else {
            grand_total = grand_total - parseInt(selected_dues);
            $("#txt-input-due_id-" + id).attr("name", "");
            $("#txt-input-due_price-" + id).attr("name", "");
        }
        $("#txt-display-grand_total").text(grand_total);
    }

    function publish_invoice()
    {
        swal.fire({
            title: 'Konfirmasi Terbit tagihan',
            text: 'Apakah anda yakin ingin melakukan terbit tagihan?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Tidak",
            confirmButtonText: "Ya"
        }).then((result) => {
            if (result.isConfirmed) {
                loading('show');
                $.ajax({
                    type: "POST",
                    url: "{{ url('/invoice/publish-monthly-invoice') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "bill_date": $("#txt-input-bill_date").val()
                    },
                    success: function(data) {
                        loading('hide');
                        var response = data;
                        swal.fire({
                            title: response.title,
                            text: response.meesage,
                            icon: response.type
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
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
        });
    }

    function publish_student_invoice() {
        swal.fire({
            title: 'Konfirmasi Terbit tagihan',
            text: 'Apakah anda yakin ingin melakukan terbit tagihan per siswa?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Tidak",
            confirmButtonText: "Ya"
        }).then((result) => {
            if (result.isConfirmed) {
                loading('show');
                $.ajax({
                    type: "POST",
                    url: "{{ url('/invoice/publish-individual-invoice') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "student_id": $("#single-select-input-student_id").val(),
                        "date_from": $("#single-txt-input-date_from").val(),
                        "date_to": $("#single-txt-input-date_to").val()
                    },
                    success: function(data) {
                        loading('hide');
                        var response = data;
                        swal.fire({
                            title: response.title,
                            text: response.meesage,
                            icon: response.type
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
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
        });
    }

    function send_invoice_notification(invoice_id) {
        swal.fire({
            title: 'Kirim notifikasi tagihan',
            text: 'Apakah anda yakin ingin mengirimkan notifikasi tagihan?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Tidak",
            confirmButtonText: "Ya"
        }).then((result) => {
            if (result.isConfirmed) {
                loading('show');
                $.ajax({
                    type: "POST",
                    url: "{{ url('/transaction/bill-issuance/send-invoice-notification') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "invoice_id": invoice_id
                    },
                    success: function(data) {
                        loading('hide');
                        var response = data;
                        swal.fire({
                            title: response.title,
                            text: response.meesage,
                            icon: response.type
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
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
        });
    }
</script>
@endsection
