<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User | Daftar</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="{{ asset('admin-page/plugins/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('admin-page/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('admin-page/dist/css/adminlte.min.css?v=3.2.0') }}">
</head>

<body class="hold-transition login-page">
    <div class="login-box">

        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <b>Pendaftaran User</b>
            </div>
            <div class="card-body">

                <div style="text-align: center; padding: 15px;">
                    <img src="/gambar/logorsu (1).png" alt="Logo RSU">
                </div>

                @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
                @endif
                <form method="post" action="{{ route('new.register') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Nama Lengkap" name="name">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="number" class="form-control" placeholder="Umur" name="age">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa fa-bookmark"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="No Telepon" name="phone">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa fa-phone"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <!-- <input type="text" class="form-control" placeholder="Alamat" name="address"> -->
                        <textarea name="address" class="form-control" placeholder="Alamat"></textarea>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-map-marker-alt"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <!-- <input type="text" class="form-control" placeholder="Gender" name="gender"> -->
                        <select name="gender" class="form-control">
                            <option value=""> - PILIH GENDER -</option>
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                            <option value="Tidak Menyebutkan">Tidak Menyebutkan</option>
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa fa-tasks"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="py-2" style="text-align: right;">
                        <button type="submit" class="btn btn-primary">Daftar</button>
                    </div>

                </form>

                <!-- <div class="social-auth-links text-center mt-2 mb-3">
                    <a href="#" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                    </a>
                </div> -->


                <p class="mb-0">
                    sudah punya akun? <a href="/user/login" class="text-center"> masuk</a>
                </p>
            </div>

        </div>

    </div>


    <script src="{{ asset('admin-page/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin-page/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-page/dist/js/adminlte.min.js?v=3.2.0') }}"></script>

    <script type="text/javascript">
        window.setTimeout(function() {
            $(".alert").fadeTo(300, 0).slideUp(300, function() {
                $(this).remove();
            });

        }, 3000);
    </script>
</body>

</html>