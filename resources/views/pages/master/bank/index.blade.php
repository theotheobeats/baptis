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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                                        <li class="breadcrumb-item active">{{ $information['title'] }}</li>
                                    </ol>
                                </h4>
                                <div class="page-title-right">
                                    <a href="#" class="btn btn-primary" id="btn-export-excel" onclick="export_excel('<?= url($information['route']) ?>', '<?= csrf_token() ?>', 'Data <?= $information['title'] ?>')"><i class="fa fa-upload me-2"></i> Export Data</a>
                                    <a href="#" class="btn btn-primary" id="btn-import-excel" data-bs-toggle="modal" data-bs-target="#import-excel-modal"><i class="fa fa-download me-2"></i> Import Data</a>
                                    <a href="{{ url($information['route'] . '/create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Input Data</a>
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
                                        <th>Nama Bank</th>
                                        <th>Akun Kas</th>
                                        <th>PIC</th>
                                        <th>Telfon PIC</th>
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
            order: [
                [3, 'desc']
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    sortable: false,
                    searchable: false
                },
                {
                    name: "banks.name",
                    data: "name"
                },
                {
                    name: "finance_accounts.name",
                    data: "finance_account_name"
                },
                {
                    name: "banks.pic_name",
                    data: "pic_name"
                },
                {
                    name: "banks.pic_phone",
                    data: "pic_phone"
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
</script>
@endsection
