<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Diagnosis - Kecanduan Game Online</title>

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @yield('head')
    <style>
        [v-cloak]>* {
            display: none;
        }

        [v-cloak]::before {
            content: "loading...";
        }

        table tr td {
            padding-top: 10px;
            padding-bottom: 10px;
        }
    </style>

    @yield('header')
</head>



<body>

    <body id="top">

        @include('layouts.header')

        <div class="content-wrapper">
            @yield('content')

        </div>
        @include('layouts.footer')

        <a href="#top" class="go-top" data-go-top>
            <ion-icon name="chevron-up-outline"></ion-icon>
        </a>

        <script src="{{ asset('frontend/assets/js/script.js') }}"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
        @yield('pagescript')
    </body>

</html>