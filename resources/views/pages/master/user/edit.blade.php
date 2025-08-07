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

                        <form class="form theme-form" id="edit-form" action="{{ url($information['route']) }}/update/{{ Crypt::encrypt($user->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-email">Email<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-email" name="email" type="text" placeholder="Email" value="{{ $user->email }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-username">Username</label>
                                            <input class="form-control" id="txt-input-username" name="username" type="text" placeholder="Username" value="{{ $user->username }}">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-password">Password</label>
                                            <input class="form-control" id="txt-input-password" name="password" type="password" placeholder="Password">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-pin">PIN</label>
                                            <input class="form-control" id="txt-input-pin" name="pin" type="password" placeholder="PIN" minlength="6">
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-input-employee_id">Pemilik Akun</label>
                                        <select name="employee_id" id="select-input-employee_id" class="form-control select2">
                                            <option value="" selected hidden>Pilih Pegawai</option>
                                            @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ $user->employee_id == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="select-input-access">Aksesibilitas</label>
                                            <select name="accessibility_id" id="select-input-access" class="form-control input-air-primary select2">
                                                <option value="" selected hidden>Pilih Aksesibilitas</option>
                                                @foreach ($accessibilities as $accessibility)
                                                <option value="{{ $accessibility->id }}">{{ $accessibility->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <?php $edit = true; ?>

                                @include('pages.master.accessibility.table')

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

        $("#select-input-access").change(function() {
            var accessibility_id = $(this).val();
            $.ajax({
                url: "{{ url($information['route']) }}/access",
                type: 'GET',
                data: {
                    accessibility_id: accessibility_id
                },
                success: function(response) {
                    display_checked_access(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('AJAX request failed: ' + textStatus + ', ' + errorThrown);
                }
            });
            $("#access_table").show();
        });
    });


    $("#edit-form").submit(function(e) {
        e.preventDefault();

        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#edit-form")[0]);
        submit_form_data("{{ url($information['route']) }}/update/{{ Crypt::encrypt($user->id) }}", form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        })
    });


    function check_all(name) {
        if ($(".check-" + name + ":first").is(":checked")) {
            $(".check-" + name).prop("checked", true);
        } else {
            $(".check-" + name).prop("checked", false);
        }
    }

    function display_checked_access(obj) {
        var access = JSON.parse(obj);

        if (!accessibility_selected) {
            check_accessibility(access);
            var accessibility_selected = true;
        }

        if (accessibility_selected) {
            uncheck_all_accessibility(access);
            check_accessibility(access);
            var accessibility_selected = true;
        }
        console.log(accessibility_selected)
        $('#myCheckbox').prop('checked', false); // Unchecks it
    }

    function check_accessibility(access) {
        Object.entries(access).forEach(([key, value]) => {
            if (access.hasOwnProperty(key)) {
                for (var key in value) {
                    if (value.hasOwnProperty(key)) {
                        if (value[key] == 1) {
                            $("#check-" + key).prop("checked", true); // can be optimized using check_all()
                        }
                    }
                }
            }
        });
    }

    function uncheck_all_accessibility(access) {
        Object.entries(access).forEach(([key, value]) => {
            if (access.hasOwnProperty(key)) {
                for (var key in value) {
                    if (value.hasOwnProperty(key)) {
                        $("#check-" + key).prop("checked", false); // can be optimized using check_all()
                    }
                }
            }
        });
    }
</script>
@endsection
