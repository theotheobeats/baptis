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
                                            <label class="form-label" for="txt-input-date">Tanggal<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-date" name="date" type="date" value="{{ now()->format('Y-m-d') }}" placeholder="Tanggal Transaksi" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-student_id">Pilih Siswa<span class="text-danger">*</span></label>
                                        <select style="width: 100%;" name="student_id" class="form-control" id="select-input-student_id"></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-amount">Jumlah<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-amount" name="amount" type="text" placeholder="Nominal" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-account_id_1">Akun 1<span class="text-danger">*</span></label>
                                            <select name="coa_1_id" id="select-input-account_id_1" class="form-control" select2>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-debit_1">Debit</label>
                                            <input class="form-control" id="txt-input-debit_1" name="coa_1_debit" type="text" placeholder="Debit">
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-credit_1">Kredit</label>
                                            <input class="form-control" id="txt-input-credit_1" name="coa_1_credit" type="text" placeholder="Kredit">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-account_id_2">Akun 2</label>
                                            <select name="coa_2_id" id="select-input-account_id_2" class="form-control" select2>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-debit_2">Debit</label>
                                            <input class="form-control" id="txt-input-debit_2" name="coa_2_debit" type="text" placeholder="Debit">
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-credit_2">Kredit</label>
                                            <input class="form-control" id="txt-input-credit_2" name="coa_2_credit" type="text" placeholder="Kredit">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-note">Keterangan </label>
                                            <textarea class="form-control" id="txt-input-note" name="note" placeholder="Keterangan"></textarea>
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
        $("#select-input-account_id_1").select2({
            ajax: {
                url: "{{ route('finance.coa.search') }}",
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term),
                    };
                },
                delay: 300,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            },
            placeholder: "Pilih COA 1"
        });

        $("#select-input-account_id_2").select2({
            ajax: {
                url: "{{ route('finance.coa.search') }}",
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term),
                    };
                },
                delay: 300,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            },
            placeholder: "Pilih COA 2"
        });

        $("#select-input-student_id").select2({
            ajax: {
                url: "{{ url('general/search-student') }}",
                dataType: 'json',
                data: function (params) {
                    return {
                        data: $.trim(params.term),
                        display_for_cashier: 1
                    };
                },
                delay: 300,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            },
            placeholder: "Pilih Siswa"
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

    $("#input-form").submit(function(e) {
        e.preventDefault();

        if ($("#select-input-student_id").val() == "" || $("#select-input-student_id").val() == null){
            Swal.fire("Peringatan", "Siswa harus diisi", "warning");
            return;
        }

        if ($("#select-input-account_id_1").val() == "" || $("#select-input-account_id_1").val() == null){
            Swal.fire("Peringatan", "Akun 1 harus diisi", "warning");
            return;
        }

        if ($("#txt-input-debit_1").val() == "" && $("#txt-input-credit_1").val() == ""){
            Swal.fire("Peringatan", "Debit atau Kredit Akun 1 harus diisi", "warning");
            return;
        }

        if ($("#select-input-account_id_2").val() != "" && $("#select-input-account_id_2").val() != null) {
            if ($("#txt-input-debit_2").val() == "" && $("#txt-input-credit_2").val() == "") {
                Swal.fire("Peringatan", "Debit atau Kredit Akun 2 harus diisi", "warning");
                return;
            }
        }

        if ($("#txt-input-debit_2").val() != "" || $("#txt-input-credit_2").val() != "") {
            if ($("#select-input-account_id_2").val() == "" || $("#select-input-account_id_2").val() == null) {
                Swal.fire("Peringatan", "Akun 2 harus diisi", "warning");
                return;
            }
        }

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
