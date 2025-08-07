@extends('layouts.app')


@section('content')

<!-- Main Content Area -->
<div class="content-wraper-area">
    <div class="data-table-area">
        <div class="container-fluid">
            <div class="row g-4">

                <div class="col-12">
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
                                            <select name="due_id" id="select-input-due_id" class="form-control select2" multiple>

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-student_id">Siswa</label>
                                            <select name="student_id" id="select-input-student_id" class="form-control select2" multiple>

                                            </select>
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



                <div class="col-12">
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
                                        <th>Nama Siswa</th>
                                        <th>Jenis Iuran</th>
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



@endsection


@section ('js_after')
<script src="{{ asset('/js/exceljson/js/xlsx.core.min.js') }}"></script>
<script src="{{ asset('/js/exceljson/js/FileSaver.js') }}"></script>
<script src="{{ asset('/js/exceljson/js/jhxlsx.js') }}"></script>
<script>
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
        });

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
                    name: "due_unsubscriptions.due_name",
                    data: "due_name"
                },
                {
                    name: "due_unsubscriptions.student_name",
                    data: "student_name"
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
</script>
@endsection