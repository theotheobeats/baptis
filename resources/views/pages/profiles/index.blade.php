@extends('layouts.app')


@section('content')

<!-- Main Content Area -->



<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Informasi Akun</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row mb-2">
                        <div class="profile-title">
                            <div class="d-flex">
                                <center>
                                    <div class="flex-grow-1">
                                        <a href="{{ url('/profile') }}">
                                            <h5 class="mb-1 f-20 txt-primary">{{ $employee->name }}</h5>
                                        </a>
                                    </div>
                                </center>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input class="form-control" placeholder="" value="{{ $user->username }}" disabled>
                    </div>
                    <div class="form-footer">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#cPassButton">Ganti Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <form class="card" id="edit-employee-form" action="/profile/update-employee/{{ Crypt::encrypt($employee->id) }}" method="post">
            @csrf
            <div class="card-header pb-0">
                <h5>Biodata</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input class="form-control" type="text" name="name" placeholder="Nama" value="{{ $employee->name }}" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <input class="form-control" type="text" name="address" placeholder="Alamat" value="{{ $employee->address }}" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input class="form-control" type="text" name="phone_number" placeholder="Nomor Telepon" value="{{ $employee->phone_number }}" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button class="btn btn-primary" type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>



<div class="modal fade" id="cPassButton" tabindex="-1" role="dialog" aria-labelledby="cPassButton" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="edit-password-form" action="/profile/update-password/{{ Crypt::encrypt($user->id) }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Ganti Password</h3>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Password Lama</label>
                                <input class="form-control" type="password" placeholder="Password Lama" name="old_password" value="" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input class="form-control" type="password" placeholder="Password Baru" name="new_password" value="" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input class="form-control" type="password" placeholder="Konfirmasi Password Baru" name="confirm_new_password" value="" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection


@section ('js_after')
<script>
    $("#edit-password-form").submit(function(e) {
        e.preventDefault();

        // Method dibawah disimpan di script.js
        submit_data($(this), {
            reload: "Tetap Di Halaman Ini",
            close: "Kembali Ke Halaman Utama",
            redirect_url: "{{ url('/') }}"
        })
    });

    $("#edit-employee-form").submit(function(e) {
        e.preventDefault();

        // Method dibawah disimpan di script.js
        submit_data($(this), {
            reload: "Tetap Di Halaman Ini",
            close: "Kembali Ke Halaman Utama",
            redirect_url: "{{ url('/') }}"
        })
    });
</script>
@endsection