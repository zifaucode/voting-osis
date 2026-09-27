<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link href="{{ asset('gambar/logo-osis.png') }}" rel="icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('admin-page/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin-page/dist/css/adminlte.min.css') }}">

    <link rel="stylesheet" href="{{ asset('admin-page/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-page/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-page/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-page/plugins/summernote/summernote-bs4.min.css') }}">

    <!-- Tailwind & Custom Design System -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('head')
    <style>
        [v-cloak]>* {
            display: none;
        }

        [v-cloak]::before {
            content: "loading...";
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important;
            background-color: #f8fafc !important;
            color: #0b1e2c;
        }

        table tr td {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        /* Fix: Override AdminLTE Layout.js min-height to prevent footer mid-page */
        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8fafc !important;
        }
        .content-wrapper {
            min-height: auto !important;
            flex: 1 1 auto;
            background-color: #f8fafc !important;
        }
        .main-footer {
            flex-shrink: 0;
            margin-top: auto;
        }
    </style>

    @yield('header')
</head>



<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('layouts-admin.header')

        @include('layouts-admin.sidebar')
        <div class="content-wrapper pb-3">
            @yield('content')
        </div>

        @include('layouts-admin.footer')
    </div>



        <script src="{{ asset('admin-page/plugins/jquery/jquery.min.js') }}"></script>

        <script src="{{ asset('admin-page/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('admin-page/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('admin-page/dist/js/adminlte.min.js') }}"></script>
        <script src="{{ asset('admin-page/dist/js/adminlte.js') }}"></script>
        <script src="{{ asset('admin-page/dist/js/adminlte.js?v=3.2.0') }}"></script>



        <script src="{{ asset('admin-page/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin-page/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('admin-page/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('admin-page/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('admin-page/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('admin-page/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('admin-page/plugins/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('admin-page/plugins/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('admin-page/plugins/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('admin-page/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('admin-page/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('admin-page/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

        <script src="{{ asset('admin-page/plugins/chart.js/Chart.min.js') }}"></script>


        <script src="{{ asset('admin-page/plugins/summernote/summernote-bs4.min.js') }}"></script>





        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(function() {
                $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                $('#example2').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "searching": false,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });
            });
        </script>


        @yield('pagescript')
    </body>

</html>
