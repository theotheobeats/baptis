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
                                            <label class="form-label" for="txt-input-transaction_date">Tanggal Transaksi<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-transaction_date" name="transaction_date" type="date" value="{{ now()->format('Y-m-d') }}" placeholder="Tanggal Transaksi" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-transaction_type">Tipe Transaksi<span class="text-danger">*</span></label>
                                            <select name="transaction_type" class="form-control" id="select-input-transaction_type" onchange="on_transaction_type_change()">
                                                <option value="Pembayaran Lainnya">Pembayaran Lainnya</option>
                                                <option value="Pembayaran Iuran">Pembayaran Iuran</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="select-student-container" style="display: none;">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-student_id">Pilih Siswa<span class="text-danger">*</span></label>
                                            <select style="width: 100%;" name="student_id" class="form-control" id="select-input-student_id"></select>
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
                                            <label class="form-label" for="select-input-bank_id">Sumber Pembayaran<span class="text-danger">*</span></label>
                                            <select name="bank_id" id="select-input-bank_id" class="form-control" select2>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-account_id">Jenis Transaksi<span class="text-danger">*</span></label>
                                            <select name="account_id" id="select-input-account_id" class="form-control" select2>

                                            </select>
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
        $("#select-input-account_id").select2({
            ajax: {
                url: "{{ route('finance.coa.search') }}",
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term),
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
            placeholder: "Pilih Jenis Transaksi"
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

        $("#select-input-bank_id").select2({
            ajax: {
                dataType: 'json',
                type: "get",
                url: "{{ url('general/search-bank') }}",
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
            width: '100%',
            placeholder: "Pilih Sumber Pembayaran",
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

        if ($("#select-input-bank_id").val() == "" || $("#select-input-bank_id").val() == null) {
            Swal.fire("Peringatan", "Sumber Pembayaran harus diisi", "warning");
            return;
        }

        if ($("#select-input-account_id").val() == "" || $("#select-input-account_id").val() == null){
            Swal.fire("Peringatan", "Jenis Transaksi harus diisi", "warning");
            return;
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

    function on_transaction_type_change() {
        let transaction_type = $("#select-input-transaction_type").val();
        if (transaction_type == "Pembayaran Iuran") {
            $("#select-student-container").show();
        } else {
            $("#select-student-container").hide();
        }
    }
</script>
@endsection
