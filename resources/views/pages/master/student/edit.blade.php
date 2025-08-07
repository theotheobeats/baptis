@extends('layouts.app')


@section('content')

<!-- Main Content Area -->
<div class="content-wraper-area">
    <div class="data-table-area">
        <form class="form theme-form" id="edit-form" action="{{ url($information['route']) }}/update/{{ Crypt::encrypt($student->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row mt-3">

                <div class="col-md-7 mb-4">
                    <div class="card bg-white">
                        <div class="card-header">
                            <h5 class="txt-title">Edit {{ $information['title'] }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-name">Nama<span class="text-danger">*</span></label>
                                        <input class="form-control" id="txt-input-name" name="name" type="text" placeholder="Nama" value="{{ $student->name }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-nis">NIS<span class="text-danger">*</span></label>
                                        <input class="form-control" id="txt-input-nis" name="nis" type="text" placeholder="NIS" value="{{ $student->nis }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-nisn">NISN<span class="text-danger">*</span></label>
                                        <input class="form-control" id="txt-input-nisn" name="nisn" type="text" placeholder="NISN" value="{{ $student->nisn }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-input-gender">Jenis Kelamin</label>
                                        <select name="gender" id="select-input-gender" class="form-control select2">
                                            <option value="" selected hidden>Pilih Jenis Kelamin</option>
                                            <option value="Male" {{ $student->gender == "Male" ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Female" {{ $student->gender == "Female"? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-birth_date">Tanggal Lahir</label>
                                        <input class="form-control" id="txt-input-birth_date" name="birth_date" type="date" placeholder="Tanggal Lahir" value="{{ $student->birth_date }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-birth_place">Tempat Lahir</label>
                                        <input class="form-control" id="txt-input-birth_place" name="birth_place" type="text" placeholder="Tempat Lahir" value="{{ $student->birth_place }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-input-religion">Agama</label>
                                        <select name="religion" id="select-input-religion" class="form-control select2">
                                            <option value="" selected hidden>Pilih Agama</option>
                                            <option value="Kristen" {{ $student->religion == "Kristen" ? 'selected' : '' }}>Kristen</option>
                                            <option value="Katolik" {{ $student->religion == "Katolik" ? 'selected' : '' }}>Katolik</option>
                                            <option value="Islam" {{ $student->religion == "Islam" ? 'selected' : '' }}>Islam</option>
                                            <option value="Buddha" {{ $student->religion == "Buddha" ? 'selected' : '' }}>Buddha</option>
                                            <option value="Hindu" {{ $student->religion == "Hindu" ? 'selected' : '' }}>Hindu</option>
                                            <option value="Kong Hu Cu" {{ $student->religion == "Kong Hu Cu" ? 'selected' : '' }}>Kong Hu Cu</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-address">Alamat</label>
                                        <input class="form-control" id="txt-input-address" name="address" type="text" placeholder="Alamat" value="{{ $student->address }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-RT">RT</label>
                                        <input class="form-control" id="txt-input-RT" name="rt" type="text" placeholder="RT" value="{{ $student->rt }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-RW">RW</label>
                                        <input class="form-control" id="txt-input-RW" name="rw" type="text" placeholder="RW" value="{{ $student->rw }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-input-village">Kelurahan</label>
                                        <select name="village_id" id="select-input-village" class="form-control select2">
                                            <option value="" selected hidden>Pilih Kelurahan</option>
                                            @foreach ($villages as $village)
                                            <option value="{{ $village->id }}" {{ $student->village_id == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-input-district">Kelurahan</label>
                                        <select name="district_id" id="select-input-district" class="form-control select2">
                                            <option value="" selected hidden>Pilih Kelurahan</option>
                                            @foreach ($districts as $district)
                                            <option value="{{ $district->id }}" {{ $student->district_id == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-postal_code">Kode Pos</label>
                                        <input class="form-control" id="txt-input-postal_code" name="postal_code" type="text" placeholder="Kode Pos" value="{{ $student->postal_code }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-parent_contact">Kontak Orang Tua<span class="text-danger">*</span></label>
                                        <input class="form-control" id="txt-input-parent_contact" name="parent_contact" type="text" placeholder="Kontak Orang Tua" value="{{ $student->parent_contact }}" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-backtrack_student_whatsapp_number">Nomor Whatsapp (Nomor diawali dengan 62)<span class="text-danger">*</span></label>
                                        <input class="form-control" id="txt-input-backtrack_student_whatsapp_number" name="backtrack_student_whatsapp_number" type="text" placeholder="Nomor Whatsapp" value="{{ $student->backtrack_student_whatsapp_number }}" required>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-father_name">Nama Ayah</label>
                                        <input class="form-control" id="txt-input-father_name" name="father_name" type="text" placeholder="Nama Ayah" value="{{ $student->father_name }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-father_phone">Kontak Ayah</label>
                                        <input class="form-control" id="txt-input-father_phone" name="father_phone" type="text" placeholder="Kontak Ayah" value="{{ $student->father_contact }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-father_address">Alamat Ayah</label>
                                        <input class="form-control" id="txt-input-father_address" name="father_address" type="text" placeholder="Alamat Ayah" value="{{ $student->father_name }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-input-father_religion">Agama Ayah</label>
                                        <select name="father_religion" id="select-input-father_religion" class="form-control select2">
                                            <option value="" selected hidden>Pilih Agama</option>
                                            <option {{ $student->father_religion == "Kristen" ? "selected" : "" }} value="Kristen">Kristen</option>
                                            <option {{ $student->father_religion == "Katolik" ? "selected" : "" }} value="Katolik">Katolik</option>
                                            <option {{ $student->father_religion == "Islam" ? "selected" : "" }} value="Islam">Islam</option>
                                            <option {{ $student->father_religion == "Buddha" ? "selected" : "" }} value="Buddha">Buddha</option>
                                            <option {{ $student->father_religion == "Hindu" ? "selected" : "" }} value="Hindu">Hindu</option>
                                            <option {{ $student->father_religion == "Kong Hu Cu" ? "selected" : "" }} value="Kong Hu Cu">Kong Hu Cu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-father_job">Pekerjaan Ayah</label>
                                        <input class="form-control" id="txt-input-father_job" name="father_job" type="text" placeholder="Pekerjaan Ayah" value="{{ $student->father_job }}">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-mother_name">Nama Ibu</label>
                                        <input class="form-control" id="txt-input-mother_name" name="mother_name" type="text" placeholder="Nama Ibu" value="{{ $student->mother_name }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-mother_phone">Kontak Ibu</label>
                                        <input class="form-control" id="txt-input-mother_phone" name="mother_phone" type="text" placeholder="Kontak Ibu" value="{{ $student->mother_contact }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-mother_address">Alamat Ibu</label>
                                        <input class="form-control" id="txt-input-mother_address" name="mother_address" type="text" placeholder="Alamat Ibu" value="{{ $student->mother_address }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-input-mother_religion">Agama Ibu</label>
                                        <select name="mother_religion" id="select-input-mother_religion" class="form-control select2">
                                            <option value="" selected hidden>Pilih Agama</option>
                                            <option {{ $student->mother_religion == "Kristen" ? "selected" : "" }} value="Kristen">Kristen</option>
                                            <option {{ $student->mother_religion == "Katolik" ? "selected" : "" }} value="Katolik">Katolik</option>
                                            <option {{ $student->mother_religion == "Islam" ? "selected" : "" }} value="Islam">Islam</option>
                                            <option {{ $student->mother_religion == "Buddha" ? "selected" : "" }} value="Buddha">Buddha</option>
                                            <option {{ $student->mother_religion == "Hindu" ? "selected" : "" }} value="Hindu">Hindu</option>
                                            <option {{ $student->mother_religion == "Kong Hu Cu" ? "selected" : "" }} value="Kong Hu Cu">Kong Hu Cu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="txt-input-mother_job">Pekerjaan Ibu</label>
                                        <input class="form-control" id="txt-input-mother_job" name="mother_job" type="text" placeholder="Pekerjaan Ibu" value="{{ $student->mother_job }}">
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
                        <div class="col-md-12">
                            <div class="card bg-white">
                                <div class="card-header">
                                    <h5 class="txt-title">Informasi Kelas</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-4">
                                                {{-- <label class="form-label" for="txt-input-parent_contact">Kelas</label>
                                                    <select name="classroom_id" class="form-control" id="select-input-classroom_id">
                                                        @if ($classroom != null)
                                                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                                @endif
                                                </select> --}}
                                                <label class="form-label" for="txt-current-class">Kelas</label>
                                                <input type="text" id="txt-current-class" class="form-control" style="color: #000" value="{{ $classroom->name }}" disabled />
                                                <br>
                                                <label class="form-label" for="txt-current-school_year">Tahun Ajaran</label>
                                                <input type="text" id="txt-current-class" class="form-control" style="color: #000" value="{{ $school_year->semester }} {{ $school_year->name }}" disabled />
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
                                    <h5>Informasi Nomor VA dan Iuran</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <nav>
                                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                    <button class="nav-link active position-relative bg-transparent" id="student-va-accounts-tab" data-bs-toggle="tab" data-bs-target="#student-va-accounts" type="button" role="tab" aria-controls="student-va-accounts" aria-selected="false">Nomor VA Siswa
                                                    </button>
                                                    <button class="nav-link position-relative bg-transparent" id="student-due-tab" data-bs-toggle="tab" data-bs-target="#student-due" type="button" role="tab" aria-controls="student-due" aria-selected="false">Iuran Siswa
                                                    </button>
                                                </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">

                                                <div class="tab-pane fade" id="student-due" role="tabpanel" aria-labelledby="student-due-tab" tabindex="0">
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
                                                                @foreach ($student_dues as $student_due)
                                                                <tr>
                                                                    <td>{{ $i }}</td>
                                                                    <td>{{ $student_due->due_name }}</td>
                                                                    <td>{{ $student_due->price }}</td>
                                                                </tr>
                                                                @php $i++ @endphp
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>


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
                                                                    <td>
                                                                        @php
                                                                        $student_va_current = $student_va->where('bank_id', $bank->id)->first();
                                                                        @endphp
                                                                        @if($student_va_current)
                                                                        <input class="form-control" type="number" name="va_number[]" value="{{ $student_va_current->va_number }}">
                                                                        @else
                                                                        <input class="form-control" type="number" name="va_number[]">
                                                                        @endif
                                                                    </td>
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


    $("#edit-form").submit(function(e) {
        e.preventDefault();

        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#edit-form")[0]);

        $("#va_table tbody tr").each(function() {
            var va_number = $(this).find("input[name='va_number[]']").val();

            if (va_number !== null && va_number !== '') {
                var bank_id = $(this).find("input[name='bank_id[]']:hidden").attr('value');

                form_data.append('bank_id_list[]', bank_id);
                form_data.append('va_number_list[]', va_number);
            }
        });


        // $("#due_table tbody tr").each(function() {
        //     var is_due_checked = $(this).find("input[name='is_due_checked[]']").prop("checked");

        //     if (is_due_checked) {
        //         var due_id = $(this).find("input[name='due_id[]']:hidden").val();
        //         var due_price = $(this).find("input[name='due_price[]']").val();

        //         form_data.append('due_id_list[]', due_id);
        //         form_data.append('due_price_list[]', due_price);
        //     }
        // });


        submit_form_data("{{ url($information['route']) }}/update/{{ Crypt::encrypt($student->id) }}", form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        })
    });

    function student_due_bind_submit() {
        let form_data = new FormData();

        let due_id = $("#select-input-due_id").val();

        form_data.append("_token", "{{ csrf_token() }}");
        form_data.append("due_id", due_id);

        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            data: form_data,
            url: "{{ url($information['route']) }}/due-bind/{{ Crypt::encrypt($student->id) }}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(data) {
                loading("hide");
                var response = data;
                swal.fire(response.title, response.message, response.type).then((isConfirm) => {
                    location.reload();
                });
            },
            error: function(xhr, status, error) {
                loading("hide");
                var response = xhr.responseJSON;
                if (xhr.status == 406) {
                    swal.fire(response.title, response.message, response.type);
                }
                if (xhr.status == 404) {
                    swal.fire("Proses Gagal!", "Halaman tidak ditemukan", "error");
                }
                if (xhr.status == 500) {
                    swal.fire("Internal Server Error 500", "Hubungi admin untuk mendapatkan bantuan terkait masalah", "error");
                }
            }
        });
    }
</script>
@endsection
