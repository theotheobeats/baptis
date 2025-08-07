@extends('layouts.app')


@section('content')

<!-- Main Content Area -->
<div class="content-wraper-area">
    <form class="form theme-form" id="input-form" action="{{ url($information['route']) }}/store" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row mt-3">

            <div class="col-md-7 mb-4">
                <div class="card bg-white">
                    <div class="card-header">
                        <h5 class="txt-title">Input {{ $information['title'] }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-name">Nama<span class="text-danger">*</span></label>
                                    <input class="form-control" id="txt-input-name" name="name" type="text" placeholder="Nama" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-nis">NIS<span class="text-danger">*</span></label>
                                    <input class="form-control" id="txt-input-nis" name="nis" type="text" placeholder="NIS" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-nisn">NISN<span class="text-danger">*</span></label>
                                    <input class="form-control" id="txt-input-nisn" name="nisn" type="text" placeholder="NISN" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-4">
                                    <label class="form-label" for="select-input-gender">Jenis Kelamin</label>
                                    <select name="gender" id="select-input-gender" class="form-control select2">
                                        <option value="" selected hidden>Pilih Jenis Kelamin</option>
                                        <option value="Male">Laki-laki</option>
                                        <option value="Female">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-birth_date">Tanggal Lahir</label>
                                    <input class="form-control" id="txt-input-birth_date" name="birth_date" type="date" placeholder="Tanggal Lahir">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-birth_place">Tempat Lahir</label>
                                    <input class="form-control" id="txt-input-birth_place" name="birth_place" type="text" placeholder="Tempat Lahir">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-4">
                                    <label class="form-label" for="select-input-religion">Agama</label>
                                    <select name="religion" id="select-input-religion" class="form-control select2">
                                        <option value="" selected hidden>Pilih Agama</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Kong Hu Cu">Kong Hu Cu</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-address">Alamat</label>
                                    <input class="form-control" id="txt-input-address" name="address" type="text" placeholder="Alamat">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-RT">RT</label>
                                    <input class="form-control" id="txt-input-RT" name="rt" type="text" placeholder="RT">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-RW">RW</label>
                                    <input class="form-control" id="txt-input-RW" name="rw" type="text" placeholder="RW">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="mb-4">
                                    <label class="form-label" for="aselect-input-district">Kecamatan</label>
                                    <select name="district_id" id="select-input-district" class="form-control">

                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-4">
                                    <label class="form-label" for="aselect-input-village">Kelurahan</label>
                                    <select name="village_id" id="select-input-village" class="form-control">

                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-postal_code">Kode Pos<span class="text-danger">*</span></label>
                                    <input class="form-control" id="txt-input-postal_code" name="postal_code" type="text" placeholder="Kode Pos" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-parent_contact">Kontak Orang Tua<span class="text-danger">*</span></label>
                                    <input class="form-control" id="txt-input-parent_contact" name="parent_contact" type="text" placeholder="Kontak Orang Tua" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-backtrack_student_whatsapp_number">Nomor Whatsapp (Nomor diawali dengan 62)<span class="text-danger">*</span></label>
                                    <input class="form-control" id="txt-input-backtrack_student_whatsapp_number" name="backtrack_student_whatsapp_number" type="text" placeholder="Nomor Whatsapp" required>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-father_name">Nama Ayah</label>
                                    <input class="form-control" id="txt-input-father_name" name="father_name" type="text" placeholder="Nama Ayah">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-father_phone">Kontak Ayah</label>
                                    <input class="form-control" id="txt-input-father_phone" name="father_phone" type="text" placeholder="Kontak Ayah">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-father_address">Alamat Ayah</label>
                                    <input class="form-control" id="txt-input-father_address" name="father_address" type="text" placeholder="Alamat Ayah">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label" for="select-input-father_religion">Agama Ayah</label>
                                    <select name="father_religion" id="select-input-father_religion" class="form-control select2">
                                        <option value="" selected hidden>Pilih Agama</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Kong Hu Cu">Kong Hu Cu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-father_job">Pekerjaan Ayah</label>
                                    <input class="form-control" id="txt-input-father_job" name="father_job" type="text" placeholder="Pekerjaan Ayah">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-mother_name">Nama Ibu</label>
                                    <input class="form-control" id="txt-input-mother_name" name="mother_name" type="text" placeholder="Nama Ibu">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-mother_phone">Kontak Ibu</label>
                                    <input class="form-control" id="txt-input-mother_phone" name="mother_phone" type="text" placeholder="Kontak Ibu">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-mother_address">Alamat Ibu</label>
                                    <input class="form-control" id="txt-input-mother_address" name="mother_address" type="text" placeholder="Alamat Ibu">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label" for="select-input-mother_religion">Agama Ibu</label>
                                    <select name="mother_religion" id="select-input-mother_religion" class="form-control select2">
                                        <option value="" selected hidden>Pilih Agama</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Kong Hu Cu">Kong Hu Cu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label" for="txt-input-mother_job">Pekerjaan Ibu</label>
                                    <input class="form-control" id="txt-input-mother_job" name="mother_job" type="text" placeholder="Pekerjaan Ibu">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-light" onclick="history.back()" type="button">Tutup</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>

                </div>
            </div>


            <div class="col-md-5">
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-white">
                            <div class="card-header">
                                <h5 class="txt-title">Informasi Kelas</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-classroom_id">Kelas</label>
                                            <select name="classroom_id" class="form-control" id="select-input-classroom_id">

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-school_year_id">Tahun Ajaran</label>
                                            <select name="school_year_id" class="form-control" id="select-input-school_year_id">
                                                @foreach ($school_years as $school_year)
                                                @if ($school_year->is_active == '1')
                                                <option value="{{ $school_year->id }}" selected>{{ $school_year->semester }} {{ $school_year->name }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                    </div>

                    <div class="col-md-12">
                        <div class="card bg-white">
                            <div class="card-header">
                                <h5 class="txt-title">Informasi Nomor VA</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <nav>
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                {{-- <button class="nav-link active position-relative bg-transparent" id="student-due-tab" data-bs-toggle="tab" data-bs-target="#student-due" type="button" role="tab" aria-controls="student-due" aria-selected="false">Iuran Siswa
                                                </button> --}}
                                                <button class="nav-link position-relative bg-transparent" id="student-va-accounts-tab" data-bs-toggle="tab" data-bs-target="#student-va-accounts" type="button" role="tab" aria-controls="student-va-accounts" aria-selected="false">Nomor VA Siswa
                                                </button>
                                            </div>
                                        </nav>
                                        <div class="tab-content" id="nav-tabContent">
                                            {{-- <div class="tab-pane fade show active" id="student-due" role="tabpanel" aria-labelledby="student-due-tab" tabindex="0">
                                                <br>
                                                <span class="text-secondary"><i><span class="text-danger">*</span> Kosongkan Iuran Jika Tidak Diambil</i></span>
                                                <br>
                                                <div class="table-responsive">
                                                    <table class="table" id="due_table">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Iuran</th>
                                                                <th>Jumlah Iuran</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $i = 1 @endphp
                                                            @foreach ($dues as $due)
                                                            <tr>
                                                                <td hidden><input type="text" value="{{ $due->id }}" name="due_id[]" hidden></td>
                                                                <td>{{ $i }}</td>
                                                                <td>{{ $due->name }}</td>
                                                                <td><input class="form-control" type="number" name="due_price[]"></td>
                                                            </tr>
                                                            @php $i++ @endphp
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div> --}}

                                            <div class="tab-pane fade show active" id="student-va-accounts" role="tabpanel" aria-labelledby="student-va-accounts-tab" tabindex="0">
                                                <br>
                                                <div class="table-responsive">
                                                    <table class="table" id="va_table">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Bank</th>
                                                                <th>Nomor Virtual Account</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $i = 1 @endphp
                                                            @foreach ($banks as $bank)
                                                            <tr>
                                                                <td hidden><input type="text" value="{{ $bank->id }}" name="bank_id[]" hidden></td>
                                                                <td>{{ $i }}</td>
                                                                <td>{{ $bank->name }}</td>
                                                                <td><input type="text" class="form-control" name="va_number[]"></td>
                                                            </tr>
                                                            @php $i++ @endphp
                                                            @endforeach
                                                        </tbody>
                                                    </table>
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

        </div>
    </form>
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

    $("#select-input-village").select2({
        ajax: {
            dataType: 'json',
            type: "get",
            url: "{{ url('general/search-village') }}",
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
        placeholder: "Pilih Kelurahan",
    });

    $("#select-input-district").select2({
        ajax: {
            dataType: 'json',
            type: "get",
            url: "{{ url('general/search-district') }}",
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
        placeholder: "Pilih Kecamatan",
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
        cache: true,
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
        placeholder: "Pilih Tahun Ajaran",
    });

    $("#input-form").submit(function(e) {
        e.preventDefault();

        if (!can_input) return;
        can_input = false;

        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#input-form")[0]);

        $("#va_table tbody tr").each(function() {
            var va_number = $(this).find("input[name='va_number[]']").val();

            if (va_number !== null && va_number !== '') {
                var bank_id = $(this).find("input[name='bank_id[]']:hidden").attr('value');

                form_data.append('bank_id_list[]', bank_id);
                form_data.append('va_number_list[]', va_number);
            }
        });


        // $("#due_table tbody tr").each(function() {
        //     // var is_due_checked = $(this).find("input[name='is_due_checked[]']").prop("checked");
        //     var due_price = $(this).find("input[name='due_price[]']").val();

        //     if (due_price !== null && due_price !== '') {
        //         var due_id = $(this).find("input[name='due_id[]']:hidden").val();
        //         var due_price = $(this).find("input[name='due_price[]']").val();

        //         form_data.append('due_id_list[]', due_id);
        //         form_data.append('due_price_list[]', due_price);
        //     }
        // });

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
