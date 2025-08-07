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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Transaksi</a></li>
                                        <li class="breadcrumb-item active">{{ $information['title'] }}</li>
                                    </ol>
                                </h4>
                                <div class="page-title-right">
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
                                        <th>Nomor VA</th>
                                        <th>Nama Siswa</th>
                                        <th>Jumlah</th>
                                        <th>Catatan</th>
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

<div class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Refund</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th><b>No</b></th>
                            <th><b>Nomor Bayar</b></th>
                            <th><b>Kode Iuran</b></th>
                            <th><b>Bulan</b></th>
                            <th><b>Tahun</b></th>
                            <th style="text-align: right;"><b>Nominal</b></th>
                        </tr>
                        <tbody id="refund-detail-container">

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
                [1, 'desc']
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    sortable: false,
                    searchable: false
                },
                {
                    name: "publish_va_manuals.va_number",
                    data: "va_number"
                },
                {
                    name: "students.name",
                    data: "student_name"
                },
                {
                    name: "publish_va_manuals.amount",
                    data: "amount"
                },
                {
                    name: "publish_va_manuals.note",
                    data: "note"
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

    function detail_modal(payment_refund_id) {
        loading('show');
        $.ajax({
            type: "GET",
            url: "{{ url('/transaction/payment-refund/get-payment-refund-detail') }}",
            data: {
                "payment_refund_id": payment_refund_id
            },
            success: function(response) {
                $("#refund-detail-container").empty();

                var payment_refund_details = response.payment_refund_details;
                var payment_refund = response.payment_refund;
                for (var i = 0; i < payment_refund_details.length; i++) {
                    var amount = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(payment_refund_details[i]['amount']);

                    $("#refund-detail-container").append(`
                    <tr>
                        <td style="color: #000">${ i + 1 }</td>
                        <td style="color: #000">${ payment_refund_details[i].payment_code }</td>
                        <td style="color: #000">${ payment_refund_details[i].invoice_detail_code }</td>
                        <td style="color: #000">${ payment_refund_details[i].payment_for_month }</td>
                        <td style="color: #000">${ payment_refund_details[i].payment_for_year }</td>
                        <td style="color: #000" align="right">${ amount }</td>
                    </tr>
                    `);
                }

                var total = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(payment_refund['total']);

                $("#refund-detail-container").append(`
                    <tr>
                        <td style="color: #000" colspan="5">Total Refund</td>
                        <td style="color: #000" align="right"><b>${ total }</b></td>
                    </tr>
                    <tr>
                        <td style="color: #000" colspan="2">Catatan</td>
                        <td style="color: #000" colspan="4">${ payment_refund.note }</td>
                    </tr>
                `);

                loading('hide');
                $("#detail_modal").modal('show');
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
