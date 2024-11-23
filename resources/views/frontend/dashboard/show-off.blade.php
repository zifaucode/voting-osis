<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>VOTING OSIS</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('gambar/logo-osis.png') }}" rel="icon">
    <link href="{{ asset('gambar/logo-osis.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->

    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/aos/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/glightbox/css/glightbox.min.css') }}">

    <!-- Main CSS File -->
    <link rel="stylesheet" href="{{ asset('user-page/assets/css/main.css') }}">



    <style>
        .frame-image {
            border: 10px solid #64cc61;
            padding: 10px;
            float: center;
        }

        .frame-image img {
            display: block;
            margin-bottom: 10px;
            width: 100%;
        }



        .col_fourth {
            width: 20%;
        }

        .col_fourth {
            position: relative;
            display: inline;
            display: inline-block;
            float: left;
            margin-right: 2%;
            margin-bottom: 5px;
        }

        .counter {
            background-color: #0088cc;
            padding: 20px 0;
            border-radius: 5px;
        }

        .count-title {
            font-size: 40px;
            font-weight: normal;
            margin-top: 10px;
            margin-bottom: 0;
            text-align: center;
        }

        .count-text {
            font-size: 13px;
            font-weight: normal;
            margin-top: 10px;
            margin-bottom: 0;
            text-align: center;
        }

        .fa-2x {
            margin: 0 auto;
            float: none;
            display: table;
            color: #4ad1e5;
        }
    </style>


</head>



<body class="index-page">
    <div id="app">
        <header id="header" class="header d-flex align-items-center sticky-top">
            <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">


                <nav id="navmenu" class="navmenu">

                    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
                </nav>

            </div>
        </header>



        <main class="main">
            <!-- ==================================== START CANDIDATE SECTION ============================== -->
            <section id="pricing" class="pricing section">

                <!-- ================================= PEMILIHAN  DIMULAI ========================================= -->

                <div class="container section-title" data-aos="fade-up">
                    <h2>HASIL PEROLEHAN SUARA</h2>
                    <br>
                    <br>


                    <div id="animation_counter"></div>

                    <button type="button" class="btn-buy" onClick="window.location.reload();">HITUNG</button>
                </div>

                <div class="container">
                    <div class="row gy-4">
                        <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="100" v-for="(candidate, index) in osisCandidate">
                            <div class="pricing-tem" style="background-color: #ffffff; opacity: 1; background-image: radial-gradient(#4fb66b 0.8px, #ffffff 0.8px); background-size: 14px 14px;">
                                <!-- <span class="featured">@{{ candidate.class ?? 'KELAS' }}</span> -->
                                <h3 style="color: black; background-color: #ffffff;"><b>@{{ candidate.name ?? 'NAMA KANDIDAT' }}</b></h3>
                                <div class="icon">
                                    <h2 style="color: #64cc61; background-color: #ffffff;"> <b>@{{ candidate.sequence_number ?? 'NOMOR URUT'}} </b></h2>
                                </div>
                                <div class="frame-image">
                                    <figure>
                                        <img :src="`/file/image/` + candidate.image" width="150px" height="360px" alt="Example Photo">
                                    </figure>
                                </div>
                                <!-- <img :src="`/file/image/` + candidate.image" alt="" width="250px" height="300px" style="background-color: #20c997;"> -->



                                <div class="icon">
                                    <p style="color: #64cc61; font-size: 60px;" :id="`value` + (index +1)"> 80 </p>
                                </div>



                            </div>
                        </div>

                    </div>
                </div>
                <!-- ================================= PEMILIHAN  DIMULAI ========================================= -->

            </section>
            <!-- ==================================== END CANDIDATE SECTION ============================== -->

        </main>

    </div>



    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('user-page/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('user-page/assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('user-page/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('user-page/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('user-page/assets/js/main.js') }}"></script>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


</body>



<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const osisCandidate = <?php echo Illuminate\Support\Js::from($osis_candidate) ?>;
    let app = new Vue({
        el: '#app',
        data: {
            osisCandidate,

            loading: false,
        },
        methods: {
            // 
        }
    })
</script>

<script>
    const valueElement = document.getElementById("value1");
    const targetValue = parseInt(valueElement.textContent);

    function animateValue(duration) {
        let currentValue = 0;
        const interval = setInterval(() => {
            currentValue++;
            valueElement.textContent = currentValue;

            if (currentValue >= targetValue) {
                clearInterval(interval);
            }
        }, duration / targetValue);
    }

    animateValue(8000);
</script>