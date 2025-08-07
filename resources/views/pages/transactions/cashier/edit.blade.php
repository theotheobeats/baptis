@extends('layouts.app')


@section('content')
<?php

use App\Models\Student;
?>
<!-- Main Content Area -->
<div class="content-wraper-area">
    <div class="data-table-area">
        <div class="container-fluid">
            <div class="row mt-3">

                <div class="col-12">
                    <h5 class="txt-title">Edit {{ $information['title'] }}</h5>
                    <div class="card bg-white">

                        <form class="form theme-form" id="edit-form" action="{{ url($information['route']) }}/update/{{ Crypt::encrypt($cashier_transaction->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-transaction_date">Tanggal Transaksi<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-transaction_date" name="transaction_date" type="date" value="{{ $cashier_transaction->transaction_date }}" placeholder="Tanggal Transaksi" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-transaction_type">Tipe Transaksi<span class="text-danger">*</span></label>
                                            <select name="transaction_type" class="form-control" id="select-input-transaction_type" disabled>
                                                <option {{ $cashier_transaction->transaction_type == "Pembayaran Lainnya" ? "selected" : "" }} value="Pembayaran Lainnya">Pembayaran Lainnya</option>
                                                <option {{ $cashier_transaction->transaction_type == "Pembayaran Iuran" ? "selected" : "" }} value="Pembayaran Iuran">Pembayaran Iuran</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @if ($cashier_transaction->student_id != null)
                                <?php $student = Student::withTrashed()->find($cashier_transaction->student_id); ?>
                                <div class="row" id="select-student-container">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-student_id">Pilih Siswa<span class="text-danger">*</span></label>
                                            <select style="width: 100%;" name="student_id" class="form-control" id="select-input-student_id" disabled>
                                                @if ($student != null)
                                                <option selected>{{ $student->name }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-amount">Nominal<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-amount" name="amount" type="text" placeholder="Nominal" required value="{{ number_format($cashier_transaction->amount) }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-bank_id">Sumber Pembayaran<span class="text-danger">*</span></label>
                                            <select name="bank_id" id="select-input-bank_id" class="form-control" select2>
                                                @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}" {{ $cashier_transaction->bank_id == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-account_id">Jenis Transaksi<span class="text-danger">*</span></label>
                                            <select name="account_id" id="select-input-account_id" class="form-control" select2 disabled>
                                                @foreach ($finance_accounts as $finance_account)
                                                <option value="{{ $finance_account->id }}" {{ $cashier_transaction->account_id == $finance_account->id ? 'selected' : '' }}>{{ $finance_account->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-note">Keterangan </label>
                                            <textarea class="form-control" id="txt-input-note" name="note" placeholder="Keterangan">{{ $cashier_transaction->note }}</textarea>
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

        $("#txt-input-amount").on("input", function() {
            var value = $(this).val().replace(/[\s,.]/g, "");
            var formattedValue = addCommas(value);
            $(this).val(formattedValue);
        });

        function addCommas(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        $("#select-input-bank_id").select2({
            placeholder: "Pilih Sumber Pembayaran"
        });

        $("#select-input-account_id").select2({
            placeholder: "Pilih Jenis Transaksi"
        });

    });

    $("#edit-form").submit(function(e) {
        e.preventDefault();

        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#edit-form")[0]);
        submit_form_data("{{ url($information['route']) }}/update/{{ Crypt::encrypt($cashier_transaction->id) }}", form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        })
    });
</script>
@endsection
