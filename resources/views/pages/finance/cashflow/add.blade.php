@extends('layouts.app')


@section('content')

<!-- Main Content Area -->
<div class="content-wraper-area">
    <div class="data-table-area">
        <div class="container-fluid">
            <div class="row mt-3">

                <div class="col-12">
                    <h5 class="txt-title">Input {{ $information['title'] }}</h5>
                    <div class="card bg-white">

                        <form class="form theme-form" id="input-form" action="{{ url($information['route']) }}/store" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-transaction_number">Nomor Transaksi<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-transaction_number" name="transaction_number" type="text" placeholder="Nomor Transaksi" required>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-transaction_date">Tanggal Transaksi<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-transaction_date" name="transaction_date" type="date" value="{{ now()->format('Y-m-d') }}" placeholder="Tanggal Transaksi" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-note">Keterangan<span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="txt-input-note" name="note" placeholder="Keterangan" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-amount">Nominal<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-amount" name="amount" type="text" placeholder="Nominal" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-debit_account_id">Akun Debit<span class="text-danger">*</span></label>
                                            <select name="debit_account_id" id="select-input-debit_account_id" class="form-control" select2>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-credit_account_id">Akun Kredit<span class="text-danger">*</span></label>
                                            <select name="credit_account_id" id="select-input-credit_account_id" class="form-control" select2>

                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-debit">Debit<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-debit" name="debit" type="text" placeholder="Debit" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-credit">Kredit<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-credit" name="credit" type="text" placeholder="Kredit" required>
                                        </div>
                                    </div> --}}
                                </div>
                                <div id="container-file_handover">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="mb-4">
                                                <label class="form-label" for="file-input-file_handover">Upload Bukti Serah Terima</label>
                                                <input class="form-control" id="file-input-file_handover" name="file_handover[]" type="file" accept="image/jpeg,image/png,application/pdf" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-3 mt-auto">
                                            <div class="mb-4">
                                                <button class="btn btn-primary mt-4" type="button" onclick="add_row_file_handover()">+ Tambah Bukti Serah Terima</button>
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
    var can_input = true;

    $(document).ready(function() {
        $("#select-input-employee_id").select2({
            placeholder: "Pilih Pegawai",
            width: "100%"
        });

        $("#select-input-debit_account_id").select2({
            ajax: {
                url: "{{ route('finance.coa.search') }}",
                dataType: 'json',
                delay: 300,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            },
            placeholder: "Pilih Akun"
        });

        $("#select-input-credit_account_id").select2({
            ajax: {
                url: "{{ route('finance.coa.search') }}",
                dataType: 'json',
                delay: 300,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            },
            placeholder: "Pilih Akun"
        });

        $("#txt-input-amount").on("input", function() {
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

    $("#input-form").submit(function(e) {
        e.preventDefault();

        var amount = $("#txt-input-amount").val().replace(/,/g, "");
        $("#txt-input-amount").val(amount);

        if (!can_input) return;
        can_input = false;

        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#input-form")[0]);
        submit_form_data("{{ url($information['route']) }}/store", form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        }, {
            success: function () { can_input = false; },
            error: function () { can_input = true; }
        });
    });
</script>
@endsection
