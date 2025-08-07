<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Title -->
    <title>{{ env('APP_NAME') }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('/assets/img/core-img/favicon.ico') }}">
    <!-- Plugins File -->
    <link rel="stylesheet" href="{{ asset('/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/animate.css') }}">

    <!-- Master Stylesheet [If you remove this CSS file, your file will be broken undoubtedly.] -->
    <link rel="stylesheet" href="{{ asset('/assets/style.css') }}">

</head>

<body class="login-area">

    <!-- Preloader -->
    <!-- <div id="preloader">
        <div class="preloader-book">
            <div class="inner">
                <div class="left"></div>
                <div class="middle"></div>
                <div class="right"></div>
            </div>
            <ul>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    </div> -->
    <!-- /Preloader -->

    <!-- ======================================
    ******* Page Wrapper Area Start **********
    ======================================= -->
    <div class="main-content- h-100vh">
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center">
                <div class="col-sm-10 col-md-7 col-lg-5">
                    <!-- Middle Box -->
                    <div class="middle-box">
                        <div class="card-body">
                            <div class="log-header-area card p-4 mb-4 text-center">
                                <center>
                                    <img src="{{ asset('logo-baptis.png') }}" alt="logo-baptis" width="100px">
                                </center>

                            </div>

                            <div class="card">
                                <div class="card-body p-4">
                                    <form action="{{ url('/auth/login') }}" id="login-form">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label class="text-muted" for="emailaddress">Username</label>
                                            <input class="form-control" type="text" name="username" id="username" placeholder="Masukkan ID/Username">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="text-muted" for="password">Password</label>
                                            <input class="form-control" type="password" id="password" name="password" placeholder="Masukkan password">
                                        </div>

                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary btn-lg w-100" type="submit">Login</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================
    ********* Page Wrapper Area End ***********
    ======================================= -->

    <!-- Must needed plugins to the run this Template -->
    <script src="{{ asset('/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/js/default-assets/setting.js') }}"></script>
    <script src="{{ asset('/assets/js/default-assets/scrool-bar.js') }}"></script>
    <script src="{{ asset('/assets/js/todo-list.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/assets/js/dataTables.responsive.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('/js/jquery.blockUI.js') }}"></script>
    <script src="{{ asset('/js/script.js') }}"></script>

    <!-- Active JS -->
    <script src="{{ asset('/assets/js/default-assets/active.js') }}"></script>
    <script>
        $("#login-form").submit(function(e) {
            e.preventDefault();
            
            loading("show");
            // Kirim data ke server
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response) {
                    var result = response.response;
                    loading('hide');
                    if (response.code > 0) {
                        swal.fire({
                            title: result.title,
                            text: result.message,
                            icon: result.type,
                            buttons: false,
                        });
                        window.location = '/';
                    } else {
                        swal.fire({
                            title: result.title,
                            text: result.message,
                            icon: result.type
                        });
                    }
                },
                error: function(request, error) {
                    loading("hide");
                    swal.fire({
                        title: "Gagal!",
                        text: "Terjadi kesalahan dalam memproses, harap menghubungi Administrator",
                        icon: "error"
                    });
                }
            });
        });
    </script>

</body>

</html>