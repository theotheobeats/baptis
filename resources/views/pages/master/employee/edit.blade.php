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
                        <form class="form theme-form" id="edit-form" action="{{ url($information['route']) }}/update/{{ Crypt::encrypt($employee->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="col">
                                                <img src="{{ asset($employee->photo) }}" class="img-thumbnail mb-3" style="display: block;" width="100%" alt="{{ $employee->name }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-4">
                                                    <label class="form-label" for="txt-input-name">Nama Karyawan <span class="text-danger">*</span></label>
                                                    <input class="form-control" id="txt-input-name" name="name" type="text" placeholder="Nama" value="{{ $employee->name }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-4">
                                                    <label class="form-label" for="txt-input-phone_number">Telepon</label>
                                                    <input class="form-control" id="txt-input-phone_number" name="phone_number" type="text" value="{{ $employee->phone_number }}" placeholder="Nomor Telepon">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-4">
                                                    <label class="form-label" for="select-input-position_id">Jabatan</label>
                                                    <select name="position_id" id="select-input-position_id" class="form-control select2" required>
                                                        <option value="" selected hidden>Pilih Jabatan</option>
                                                        @foreach ($positions as $position)
                                                        <option value="{{ $position->id }}" {{ $employee->position_id == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-4">
                                                    <label class="form-label" for="input-photo">Foto Pegawai (<i class="text-secondary">Tidak wajib diisi jika foto tidak ingin diubah</i>)</label>
                                                    <input class="form-control" name="photo" id="input-photo" type="file">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-4">
                                                    <label class="form-label" for="txt-input-address">Alamat Tempat Tinggal</label>
                                                    <input class="form-control" id="txt-input-address" name="address" type="text" value="{{ $employee->address }}" placeholder="Alamat Tempat Tinggal">
                                                </div>
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
    });


    $("#edit-form").submit(function(e) {
        e.preventDefault();

        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#edit-form")[0]);
        submit_form_data("{{ url($information['route']) }}/update/{{ Crypt::encrypt($employee->id) }}", form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        })
    });
</script>
@endsection