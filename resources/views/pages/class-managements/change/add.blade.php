@extends('layouts.app')


@section('content')

<!-- Main Content Area -->
<div class="content-wraper-area">
    <div class="data-table-area">
        <div class="container-fluid">
            <div class="row mt-3">

                <div class="col-7">
                    <div class="card bg-white">
                        <div class="card-header">
                            <h5 class="txt-title">Input {{ $information['title'] }}</h5>
                        </div>

                        <form class="form theme-form" id="input-form" action="{{ url($information['route']) }}/store" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-student_id">Siswa<span class="text-danger">*</span></label>
                                            <select name="student_id" id="select-input-student_id" class="form-control select2">

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-school_year_id">Tahun Ajaran</label>
                                            <select name="school_year_id" class="form-control" id="select-input-school_year_id">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-classroom_id">Dipindahkan ke Kelas</label>
                                            <select name="classroom_id" class="form-control" id="select-input-classroom_id">

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

                <div class="col-5">
                    <div class="card bg-white">
                        <div class="card-header">
                            <h5 class="txt-title">Informasi Siswa</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label" for="txt-current_class">Kelas Sekarang</label>
                                        <p class="information" id="txt-current_class">&nbsp</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label" for="txt-student_name">Nama Siswa</label>
                                        <p class="information" id="txt-student_name">&nbsp</p>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label" for="txt-student_nis">NIS</label>
                                        <p class="information" id="txt-student_nis">&nbsp</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<style>
    .information {
        font-size: 1.2rem;
        color: #000;
    }
</style>


@endsection


@section ('js_after')
<script>
    var can_input = true;
    var student_id = null;

    $("#select-input-student_id").select2({
        ajax: {
            dataType: 'json',
            type: "get",
            url: "{{ url('general/search-active-student') }}",
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
        placeholder: "Pilih Siswa",
    });

    $("#select-input-student_id").on('change', function() {
        student_id = $(this).val();
        $.ajax({
            url: "{{ url('general/get-student-current-classroom') }}",
            type: "get",
            data: {
                student_id: student_id
            },
            success: function(response) {
                student_id = response.student.student_id;
                $("#select-input-classroom_id").val(null).trigger('change');

                $("#txt-current_class").text(response.classroom.name);
                $("#txt-student_name").text(response.student.student_name);
                $("#txt-student_nis").text(response.student.student_nis);
            }
        });
    });

    $("#select-input-classroom_id").select2({
        ajax: {
            dataType: 'json',
            type: "get",
            url: "{{ url('general/search-classroom') }}",
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
        placeholder: "Pilih Kelas",
    });

    $("#select-input-school_year_id").select2({
        ajax: {
            dataType: 'json',
            type: "get",
            url: "{{ url('general/search-school-year') }}",
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
        width: "100%",
        placeholder: "Pilih Tahun Ajaran",
    });

    $("#input-form").submit(function(e) {
        e.preventDefault();

        if (student_id == null) {
            swal.fire("Peringatan!", "Harap pilih siswa terlebih dahulu", "warning");
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
</script>
@endsection
