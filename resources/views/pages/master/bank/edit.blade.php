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

                        <form class="form theme-form" id="edit-form" action="{{ url($information['route']) }}/update/{{ Crypt::encrypt($bank->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-code">Kode<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-code" name="code" type="text" placeholder="Kode" value="{{ $bank->code }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-name">Nama<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-name" name="name" type="text" placeholder="Nama" value="{{ $bank->name }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-pic_name">Nama PIC<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-pic_name" name="pic_name" type="text" placeholder="Deskripsi" value="{{ $bank->pic_name }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-pic_phone">Telfon PIC<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-pic_phone" name="pic_phone" type="text" placeholder="Deskripsi" value="{{ $bank->pic_phone }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-finance_account_id">Akun Kas<span class="text-danger">*</span></label>
                                            <select class="form-control" id="select-input-finance_account_id" name="finance_account_id" required>
                                                @if ($finance_account != null)
                                                <option value="{{ $finance_account->id }}">{{ $finance_account->name }}</option>
                                                @endif
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

            </div>
        </div>
    </div>
</div>


@endsection


@section ('js_after')
<script>
    $("#select-input-finance_account_id").select2({
        ajax: {
            dataType: 'json',
            type: "get",
            url: "{{ url('general/search-finance-account') }}",
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
        placeholder: "Pilih Akun Kas",
    });

    $("#edit-form").submit(function(e) {
        e.preventDefault();

        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#edit-form")[0]);
        submit_form_data("{{ url($information['route']) }}/update/{{ Crypt::encrypt($bank->id) }}", form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        })
    });
</script>
@endsection
