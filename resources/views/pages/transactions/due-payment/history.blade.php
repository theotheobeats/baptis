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
                                    Riwayat {{ $information['title'] }}

                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Transaksi</a></li>
                                        <li class="breadcrumb-item active">{{ $information['title'] }}</li>
                                    </ol>
                                </h4>
                            
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
                                        <th>Nama Siswa</th>
                                        <th>Bank</th>
                                        <th>Nominal</th>
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

    $(function() {

        data_table = $("#index-table").DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            pageLength: 5,
            searchDelay: 2000,
            ajax: {
                url: "{{ url($information['route']) }}/history"
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
                    name: "invoices.student_name",
                    data: "student_name"
                },
                {
                    name: "invoices.bank_name",
                    data: "bank_name"
                },
                {
                    name: "invoices.price",
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
</script>
@endsection