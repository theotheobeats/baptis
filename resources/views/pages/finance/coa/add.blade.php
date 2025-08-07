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
                                            <label class="form-label" for="txt-input-code">Kode<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-code" name="code" type="text" placeholder="Kode" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-name">Nama<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-name" name="name" type="text" placeholder="Nama" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-sub_detail">Sub Detail</label>
                                            <input class="form-control" id="txt-input-sub_detail" name="sub_detail" type="text" placeholder="Sub Detail">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-description">Deskripsi</label>
                                            <textarea class="form-control" id="txt-input-description" name="description" placeholder="Deskripsi"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-1">
                                            <label class="form-label" for="cb-input-display_for_cashier"><input type="checkbox" name="display_for_cashier" id="cb-input-display_for_cashier" value="yes"> Tampilkan Ke Kasir</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-2">
                                            <label class="form-label" for="cb-input-hide_coa"><input type="checkbox" name="hide_coa" id="cb-input-hide_coa" value="yes"> Sembunyikan Akun Kas</label>
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

    });

    $("#input-form").submit(function(e) {
        e.preventDefault();

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
