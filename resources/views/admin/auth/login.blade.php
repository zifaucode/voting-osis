<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Masuk</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="{{ asset('admin-page/plugins/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('admin-page/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('admin-page/dist/css/adminlte.min.css?v=3.2.0') }}">

    <style>
        .bg-custom {
            background-color: #048b8e !important;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">

        <div class="card card-outline">
            <div class="card-header text-center bg-custom">
                <h1 style="color: white;"><b>Login Admin</b></h1>
            </div>
            <div class="card-body">

                <div style="text-align: center; padding: 15px;">
                    <img src="/gambar/logounpam (1).png" alt="Logo UNPAM">
                    <img src="/gambar/logorsu (1).png" alt="Logo RSU">

                </div>

                @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
                @endif

                @if (Session::has('error'))
                <div class="alert alert-danger mb-0" role="alert">
                    <span>{{ Session::get('error') }}</span>

                </div>
                @endif
                <br>

                <form class="form-main" action="{{ route('auth.admin') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="username" name="username" autocomplete="off" value="{{ old('username') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="py-2" style="text-align: right;">
                        <button type="submit" class="btn" style="background-color: #048b8e; color:white">Masuk</button>
                    </div>

                </form>

                <!-- <div class="social-auth-links text-center mt-2 mb-3">
                    <a href="#" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                    </a>
                </div> -->


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