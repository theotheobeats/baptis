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
                                        <li class="breadcrumb-item active">{{ $information['title'] }}</li>
                                    </ol>
                                </h4>
                                <div class="page-title-right">
                                    <a href="#" class="btn btn-primary" id="btn-export-excel" onclick="export_excel('<?= url($information['route']) ?>', '<?= csrf_token() ?>', 'Data <?= $information['title'] ?>')"><i class="fa fa-upload me-2"></i> Export Data</a>
                                    <a href="#" class="btn btn-primary" id="btn-import-excel" data-bs-toggle="modal" data-bs-target="#import-excel-modal"><i class="fa fa-download me-2"></i> Import Data</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-white">
                        <div class="card-header">
                            <div class="row mt-2">
                                <div class="col">
                                    <h5>Input {{ $information['title'] }}</h5>
                                </div>
                            </div>
                        </div>
                        <form class="form theme-form" id="input-form" action="{{ url($information['route']) }}/store" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-due_id">Iuran</label>
                                            <select name="due_id" id="select-input-due_id" class="form-control select2">

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-student_id">Siswa</label>
                                            <select name="student_id[]" id="select-input-student_id" class="form-control select2" multiple>

                                            </select>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-primary" type="submit">Simpan</button>
                            </div>
                        </form>

                    </div>
                </div>



                <div class="col-md-8">
                    <div class="card bg-white">
                        <div class="card-header">
                            <div class="row mt-2">
                                <div class="col">
                                    <h5>List Data {{ $information['title'] }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="text" class="form-control mb-3" id="input-table-search" placeholder="Cari" style="width: 100%;">
                            <table id="index-table" class="table table-bordered dt-responsive nowrap data-table-area">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Jenis Iuran</th>
                                        <th>Harga</th>
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

<div class="modal fade" id="due_price_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ubah Jumlah Tagihan</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="url" hidden>
                <div class="row">
                    <div class="col">
                        <label for="due_price">Jumlah Tagihan</label>
                        <input type="text" class="form-control" name="due_price" id="due_price">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="due_price_update()">Simpan Perubahan</button>
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

@endsection


@section ('js_after')
<script src="{{ asset('/js/exceljson/js/xlsx.core.min.js') }}"></script>
<script src="{{ asset('/js/exceljson/js/FileSaver.js') }}"></script>
<script src="{{ asset('/js/exceljson/js/jhxlsx.js') }}"></script>
<script>
    var can_input = true;
    var data_table_search_delay = null;
    var data_table = null;

    $(document).ready(function() {
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

        $("#select-input-student_id").select2({
            ajax: {
                dataType: 'json',
                type: "get",
                url: "{{ url('general/search-active-student') }}",
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
        });

        $("#due_price").on("input", function() {
            var value = $(this).val().replace(/[\s,.]/g, "");
            var formattedValue = addCommas(value);
            $(this).val(formattedValue);
        });

        function addCommas(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

    });

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
            order: [
                [3, 'desc']
            ],
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
                    name: "dues.name",
                    data: "due_name"
                },
                {
                    name: "student_dues.price",
                    data: "price"
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


    $("#input-form").submit(function(e) {
        e.preventDefault();

        if (!can_input) return;
        can_input = false;

        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#input-form")[0]);
        submit_form_data("{{ url($information['route']) }}/store", form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        }, {
            success: function () { can_input = true; },
            error: function () { can_input = true; }
        });
    });


    function unsubscribe_confirm(url) {
        swal.fire({
            title: 'Konfirmasi Berhenti Mengikuti Kursus',
            text: 'Siswa berikut akan berhenti mengikuti kursus, lanjutkan?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Batal",
            confirmButtonText: "Ya, Berhenti"
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

    function due_price_modal(url) {
        $("#url").val(url);
        $("#due_price_modal").modal('show');
    }

    function due_price_update() {
        var url = $("#url").val();
        var price = $("#due_price").val().replace(/,/g, "");

        let form_data = new FormData();
        form_data.append('price', price);
        form_data.append('_token', '{{ csrf_token() }}');

        submit_form_data(url, form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        })

    }
</script>
@endsection
