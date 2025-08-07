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

                        <form class="form theme-form" id="edit-form" action="{{ url($information['route']) }}/update/{{ Crypt::encrypt($cashier_payment->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-date">Tanggal<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-date" name="date" type="date" value="{{ $cashier_payment->date }}" placeholder="Tanggal Transaksi" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-student_id">Pilih Siswa<span class="text-danger">*</span></label>
                                        <select style="width: 100%;" name="student_id" class="form-control" id="select-input-student_id" disabled>
                                            @if ($cashier_payment->student_id != null)
                                                <?php $student = Student::withTrashed()->find($cashier_payment->student_id); ?>
                                                @if ($student != null)
                                                <option selected>{{ $student->nis }} - {{ $student->name }}</option>
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-amount">Jumlah<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-amount" name="amount" type="text" placeholder="Nominal" required value="{{ number_format($cashier_payment->amount) }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-account_id_1">Akun 1<span class="text-danger">*</span></label>
                                            <select name="coa_1_id" id="select-input-account_id_1" class="form-control" select2 disabled>
                                                @foreach ($finance_accounts as $finance_account)
                                                <option value="{{ $finance_account->id }}" {{ $cashier_payment->coa_1_id == $finance_account->id ? 'selected' : '' }}>{{ $finance_account->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-debit_1">Debit</label>
                                            <input class="form-control" id="txt-input-debit_1" name="coa_1_debit" type="text" placeholder="Debit" value="{{ $cashier_payment->coa_1_debit }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-credit_1">Kredit</label>
                                            <input class="form-control" id="txt-input-credit_1" name="coa_1_credit" type="text" placeholder="Kredit" value="{{ $cashier_payment->coa_1_credit }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-account_id_2">Akun 2</label>
                                            <select name="coa_2_id" id="select-input-account_id_2" class="form-control" select2 disabled>
                                                @foreach ($finance_accounts as $finance_account)
                                                <option value="{{ $finance_account->id }}" {{ $cashier_payment->coa_2_id == $finance_account->id ? 'selected' : '' }}>{{ $finance_account->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-debit_2">Debit</label>
                                            <input class="form-control" id="txt-input-debit_2" name="coa_2_debit" type="text" placeholder="Debit" value="{{ $cashier_payment->coa_2_debit }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-credit_2">Kredit</label>
                                            <input class="form-control" id="txt-input-credit_2" name="coa_2_credit" type="text" placeholder="Kredit" value="{{ $cashier_payment->coa_2_credit }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-note">Keterangan </label>
                                            <textarea class="form-control" id="txt-input-note" name="note" placeholder="Keterangan">{{ $cashier_payment->note }}</textarea>
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
        submit_form_data("{{ url($information['route']) }}/update/{{ Crypt::encrypt($cashier_payment->id) }}", form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        })
    });
</script>
@endsection
