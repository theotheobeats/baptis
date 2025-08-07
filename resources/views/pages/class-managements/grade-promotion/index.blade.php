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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Manajemen Kelas</a></li>
                                        <li class="breadcrumb-item active">{{ $information['title'] }}</li>
                                    </ol>
                                </h4>
                                <div class="page-title-right">
                                    <a href="#" class="btn btn-primary" id="btn-export-excel" data-bs-toggle="modal" data-bs-target="#export-excel-modal"><i class="fa fa-upload me-2"></i> Export Data</a>
                                    <a href="#" class="btn btn-primary" id="btn-import-excel" data-bs-toggle="modal" data-bs-target="#import-excel-modal"><i class="fa fa-download me-2"></i> Import Data</a>
                                </div>
                            </div>
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


<div class="modal fade" id="export-excel-modal" tabindex="-1"  role="dialog" aria-labelledby="export-excel-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Export Data</h3>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="export-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="current_school_year">Tahun Ajaran Saat Ini</label>
                        <input type="text" class="form-control" id="txt-input-current_school_year" value="{{ $school_year->semester }} {{ $school_year->name }}" readonly disabled>
                    </div>
                    <div class="form-group mt-2">
                        <label class="form-label" for="select-input-school_year_id">Tahun Ajaran Kenaikan Kelas</label>
                        <br>
                        <select name="school_year_id" id="select-input-school_year_id" class="form-control w-full">

                        </select>
                    </div>
                    <input type="text" id="url" hidden>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-secondary" type="submit">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection


@section ('js_after')
<script src="{{ asset('/js/exceljson/js/xlsx.core.min.js') }}"></script>
<script src="{{ asset('/js/exceljson/js/FileSaver.js') }}"></script>
<script src="{{ asset('/js/exceljson/js/jhxlsx.js') }}"></script>
<script>
    $("#select-input-school_year_id").select2({
        dropdownParent: $("#export-excel-modal"),
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
        width: "100%",
        placeholder: "Pilih Tahun Ajaran",
    });

    $("#export-form").submit(function(e) {
        e.preventDefault();
        var school_year_id = $("#select-input-school_year_id").val();
        console.log(school_year_id);

        if (school_year_id == "" || school_year_id == null) {
            swal.fire("Peringatan!", "Harap pilih tahun ajaran terlebih dahulu", "warning");
            return;
        }

        loading("show");
        $.ajax({
            type: "post",
            url: "{{ url($information['route']) }}" + "/export/excel",
            data: {
                _token: "{{ csrf_token() }}",
                current_school_year_id: "{{ $school_year->id }}",
                school_year_id: school_year_id,
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
                    fileName: "Data Export Kenaikan Kelas",
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

</script>
@endsection
