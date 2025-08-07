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

                        <form class="form theme-form" id="edit-form" action="{{ url($information['route']) }}/update/{{ Crypt::encrypt($school_year->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-name">Nama<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-name" name="name" type="text" placeholder="Nama" value="{{ $school_year->name }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-semester">Semester</label>
                                            <select name="semester" id="select-input-semester" class="form-control select2">
                                                <option value="" selected hidden>Pilih Semester</option>
                                                <option value="Ganjil" {{ $school_year->semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                                <option value="Genap" {{ $school_year->semester == 'Genap' ? 'selected' : '' }}>Genap</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="is_active" type="checkbox" role="switch" value="1" id="cb-input-is_active" {{ $school_year->is_active ? 'checked disabled' : '' }}>
                                                <label class="form-check-label" for="cb-input-is_active">Aktif</label>
                                            </div>
                                            @if ( $school_year->is_active == 1)
                                            <div style="opacity: 0.75;">
                                                <span class="text-danger">*</span>Untuk mengganti Tahun Ajaran aktif, ubah status aktif pada Tahun Ajaran tujuan
                                            </div>
                                            @endif
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
        submit_form_data("{{ url($information['route']) }}/update/{{ Crypt::encrypt($school_year->id) }}", form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        })
    });
</script>
@endsection
