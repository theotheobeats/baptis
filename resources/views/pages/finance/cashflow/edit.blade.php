@extends('layouts.app')


@section('content')

<!-- Main Content Area -->
<div class="content-wraper-area">
    <div class="data-table-area">
        <div class="container-fluid">
            <div class="row mt-3">

                <div class="col-12">
                    <h5 class="txt-title">Edit {{ $information['title'] }}</h5>
                    <div class="card bg-white">

                        <form class="form theme-form" id="edit-form" action="{{ url($information['route']) }}/update/{{ Crypt::encrypt($finance_cash_flow->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="col">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-input-account_id">Akun Kas</label>
                                        <select name="account_id" id="select-input-account_id" class="form-control select2">
                                            <option value="" selected hidden>Pilih Akun Kas</option>
                                            @foreach ($finance_accounts as $finance_account)
                                            <option value="{{ $finance_account->id }}" {{ $finance_cash_flow->account_id == $finance_account->id ? 'selected' : '' }}>{{ $finance_account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-transaction_number">Kode<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-transaction_number" name="transaction_number" type="text" placeholder="Kode" value="{{ $finance_cash_flow->code }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-transaction_number">Nomor Transaksi<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-transaction_number" name="transaction_number" type="text" placeholder="Nomor Transaksi" value="{{ $finance_cash_flow->transaction_number }}" required>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-transaction_date">Tanggal Transaksi<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-transaction_date" name="transaction_date" type="date" value="{{ $finance_cash_flow->transaction_date }}" placeholder="Tanggal Transaksi" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-note">Keterangan<span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="txt-input-note" name="note" placeholder="Keterangan" required>{{ $finance_cash_flow->note }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-debit">Debit<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-debit" name="debit" type="text" placeholder="Debit" value="{{ $finance_cash_flow->debit }}" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-credit">Kredit<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-credit" name="credit" type="text" placeholder="Kredit" value="{{ $finance_cash_flow->credit }}" required>
                                        </div>
                                    </div>
                                </div>
                                @if (count($finance_cash_flow_files) > 0)
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file-input-file_handover">Bukti Serah Terima</label>
                                        @foreach ($finance_cash_flow_files as $finance_cash_flow_file)
                                        <div class="mb-2">
                                            <a href="{{ asset($finance_cash_flow_file->file_handover) }}" target="_blank"><span><small>{{ basename($finance_cash_flow_file->file_handover) }}</small></a></span>
                                            <button class="btn btn-outline-danger btn-sm" type="button" onclick="delete_file_handover('{{ Crypt::encrypt($finance_cash_flow_file->id) }}')"><i class='fa fa-trash'></i></button>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                <div id="container-file_handover">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="mb-4">
                                                <label class="form-label" for="file-input-file_handover">Tambah Bukti Serah Terima</label>
                                                <input class="form-control" id="file-input-file_handover" name="file_handover[]" type="file" accept="image/jpeg,image/png,application/pdf" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-auto">
                                            <div class="mb-4">
                                                <button class="btn btn-primary" type="button" onclick="add_row_file_handover()">+ Tambah Bukti Serah Terima</button>
                                            </div>
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

            </div>
        </div>
    </div>
</div>


@endsection


@section ('js_after')
<script>
    $(document).ready(function() {
        $("#select-input-employee_id").select2({
            placeholder: "Pilih Pegawai",
            width: "100%"
        });

        $("#txt-input-credit").on("input", function() {
            var value = $(this).val().replace(/[\s,.]/g, "");
            var formattedValue = addCommas(value);
            $(this).val(formattedValue);
        });

        $("#txt-input-debit").on("input", function() {
            var value = $(this).val().replace(/[\s,.]/g, "");
            var formattedValue = addCommas(value);
            $(this).val(formattedValue);
        });

        function addCommas(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

    });

    function add_row_file_handover() {
         $("#container-file_handover").append(`
            <div class="row">
                <div class="col-9">
                    <div class="mb-4">
                        <input class="form-control" name="file_handover[]" accept="image/jpeg,image/png,application/pdf" type="file">
                    </div>
                </div>
                <div class="col-3 mt-auto">
                    <div class="mb-4">
                        <button class="btn btn-danger" id='delete-row'><i class='fa fa-trash'></i></a></button>
                    </div>
                </div>
            </div>
        `);

        $(document).on('click', '#delete-row', function() {
            $(this).closest('.row').remove();
        });
    }

    function delete_file_handover(finance_cash_flow_file_id) {
        swal.fire({
            title: 'Hapus File',
            text: 'Apakah Anda yakin ingin menghapus file ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('finance/cashflow/delete-file-handover') }}" + "/" + finance_cash_flow_file_id,
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    type: 'POST',
                    success: function (data) {
                        var response = data;

                        if (response.type == "success") {
                            swal.fire("Berhasil", response.message, "success");
                            location.reload();
                        } else {
                            swal.fire("Gagal", response.message, "error");
                        }
                    },

                    // Jika request gagal
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
            }
        })

    }


    $("#edit-form").submit(function(e) {
        e.preventDefault();

        var debit = $("#txt-input-debit").val().replace(/,/g, "");
        $("#txt-input-debit").val(debit);

        var credit = $("#txt-input-credit").val().replace(/,/g, "");
        $("#txt-input-credit").val(credit);

        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#edit-form")[0]);
        submit_form_data("{{ url($information['route']) }}/update/{{ Crypt::encrypt($finance_cash_flow->id) }}", form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        })
    });
</script>
@endsection
