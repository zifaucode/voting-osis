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
            padding: 15px;
            float: center;
        }

        .frame-image img {
            display: block;
            margin-bottom: 10px;
            width: 100%;
        }
    </style>


</head>



<body class="index-page">
    <div id="app">
        <header id="header" class="header d-flex align-items-center sticky-top">
            <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

                <a href="index.html" class="logo d-flex align-items-center">
                    <!-- Uncomment the line below if you also wish to use an image logo -->
                    <!-- <img src="assets/img/logo.png" alt=""> -->
                    <h1 class="sitename"><span>{{ $web_setting->web_title ?? 'JUDUL WEB' }}</span></h1>
                </a>

                <nav id="navmenu" class="navmenu">
                    <ul>
                        <li><button type="button" class="btn btn-success me-2" data-toggle="modal" data-target="#visiMisiModal">Visi Misi</button></li>
                        <!-- <li><button type="button" class="btn btn-secondary">Login</button></li> -->
                        <li><a href="/admin">Login</a></li>

                    </ul>
                    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
                </nav>

            </div>
        </header>



        <main class="main">
            <!-- ==================================== START CANDIDATE SECTION ============================== -->
            <section id="pricing" class="pricing section">









                <!-- ================================= PEMILIHAN  DIMULAI ========================================= -->
                <template v-if="status == 'buka'">

                    <div class="container section-title" data-aos="fade-up">
                        <div>
                            <span id="getCountdown">
                            </span>
                        </div>
                    </div>

                    <div class="container section-title" data-aos="fade-up">
                        <h2>{{ $web_setting->web_title ?? 'JUDUL WEB' }}</h2>
                        <div><span>{{ $web_setting->school_name ?? 'NAMA SEKOLAH' }}</span> <span class="description-title">{{ $web_setting->year_period ?? 'TAHUN PERIODE' }} / {{ $year_plus ?? 'TAHUN PERIODE' }}</span></div>
                    </div>

                    <div class="container">

                        <div class="row gy-4">

                            <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="100" v-for="candidate in osisCandidate">
                                <div class="pricing-tem" style="background-color: #ffffff; opacity: 1; background-image: radial-gradient(#4fb66b 0.8px, #ffffff 0.8px); background-size: 16px 16px;">
                                    <span class="featured">@{{ candidate.class ?? 'KELAS' }}</span>
                                    <h3 style="color: black; background-color: ffffff;"><b>@{{ candidate.name ?? 'NAMA KANDIDAT' }}</b></h3>

                                    <div class="frame-image">
                                        <figure>
                                            <img :src="`/file/image/` + candidate.image" width="220px" height="360px" alt="Example Photo">
                                        </figure>
                                    </div>
                                    <!-- <img :src="`/file/image/` + candidate.image" alt="" width="250px" height="300px" style="background-color: #20c997;"> -->

                                    <div class="icon">
                                        <i class="bi bi-box" style="color: #64cc61;"> &nbsp;<b>@{{ candidate.sequence_number ?? 'NOMOR URUT'}} </b></i>
                                    </div>

                                    <button type="button" @click="onSelcected(candidate.id)" class="btn-buy" data-toggle="modal" data-target="#chooseCandidateModal">PILIH KANDIDAT INI</button>

                                </div>
                            </div>

                        </div>
                    </div>
                </template>
                <!-- ================================= PEMILIHAN  DIMULAI ========================================= -->







                <!-- ================================= MULAI HITUNG MUNDUR ========================================= -->
                <!-- <template v-if="status == ''"> -->
                <div class="container section-title" data-aos="fade-up">
                    <div>
                        <span id="demo">
                        </span>
                    </div>
                </div>
                <!-- </template> -->
                <!-- ================================= SELESAI HITUNG MUNDUR ========================================= -->





                <!-- ================================= PEMILIHAN  SELESAI ========================================= -->
                <template v-if="status == 'tutup'">
                    <div class="container section-title" data-aos="fade-up">

                        <div>
                            <span id="demo">
                            </span>
                        </div>
                        <!-- <h2>PEMILIHAN TELAH SELESAI</h2>

                        <br>

                        <img src="/gambar/hero-img.png" alt=""> -->
                    </div>
                </template>
                <!-- ================================= PEMILIHAN  SELESAI ========================================= -->








            </section>
            <!-- ==================================== END CANDIDATE SECTION ============================== -->

        </main>


        <template v-if="status == 'buka'">
            <!-- ============================================= START MODAL CHOOSE CANDIDATE ========================================= -->
            <div class="modal fade" id="chooseCandidateModal" tabindex="-1" role="dialog" aria-labelledby="chooseCandidateModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #f1f1f1; opacity: 0.8; background: linear-gradient(135deg, #b7e0c255 25%, transparent 25%) -21px 0/ 42px 42px, linear-gradient(225deg, #b7e0c2 25%, transparent 25%) -21px 0/ 42px 42px, linear-gradient(315deg, #b7e0c255 25%, transparent 25%) 0px 0/ 42px 42px, linear-gradient(45deg, #b7e0c2 25%, #f1f1f1 25%) 0px 0/ 42px 42px;">
                            <h4 class="modal-title" id="chooseCandidateModalLabel" style="background-color: #ffffff;"> <b>Anda Memilih : @{{ candidateDetail.name ?? 'NAMA KANDIDAT'}} </b></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="exampleInputCodeAccess">Masukan Kode Akses</label>
                                <input type="text" class="form-control" id="exampleInputCodeAccess" v-model="code_access" aria-describedby="codeAccessHelp" placeholder="kode akses" autocomplete="off">
                                <br>
                                <small id="codeAccessHelp" class="form-text text-muted">Masukan kode akses yang valid, Untuk memilih : <span style="color:green;"> <b>@{{ candidateDetail.name ?? 'NAMA KANDIDAT'}} </b></span> </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">BATAL</button>
                            <button type="button" @click.prevent="submitForm" class="btn btn-success">PILIH</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================= END MODAL CHOOSE CANDIDATE ========================================= -->
        </template>





        <!-- ============================================= START MODAL VISI MISI ========================================= -->
        <div class="modal fade" id="visiMisiModal" tabindex="-1" role="dialog" aria-labelledby="visiMisiModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="visiMisiModalLabel">VISI - MISI</h5>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">

                            <div class="card mb-4" v-for="candidate in osisCandidate">
                                <div class="card-header bg-success text-dark" style="background-color: #f1f1f1; opacity: 0.8; background: linear-gradient(135deg, #b7e0c255 25%, transparent 25%) -21px 0/ 42px 42px, linear-gradient(225deg, #b7e0c2 25%, transparent 25%) -21px 0/ 42px 42px, linear-gradient(315deg, #b7e0c255 25%, transparent 25%) 0px 0/ 42px 42px, linear-gradient(45deg, #b7e0c2 25%, #f1f1f1 25%) 0px 0/ 42px 42px;">
                                    <h3><b> @{{ candidate.sequence_number ?? 'NOMOR URUT' }} - @{{ candidate.name ?? 'NAMA KANDIDAT'}} </b></h3>
                                </div>
                                <div class="card-body" style="background-color: #ffffff; opacity: 1; background-image: radial-gradient(#4fb66b 0.8px, #ffffff 0.8px); background-size: 16px 16px;">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item" style="background-color: #ffffff; opacity: 1; background-image: radial-gradient(#4fb66b 0.8px, #ffffff 0.8px); background-size: 16px 16px;"><b>VISI : </b>
                                            <br>
                                            @{{ candidate.visi ?? 'VISI'}}
                                            <br>
                                        </li>
                                        <li class="list-group-item" style="background-color: #ffffff; opacity: 1; background-image: radial-gradient(#4fb66b 0.8px, #ffffff 0.8px); background-size: 16px 16px;"><b>MISI :</b>
                                            <br>
                                            <br>
                                            @{{ candidate.misi ?? 'MISI' }}
                                            <br>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <hr>
                            <br>
                            <br>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================= END MODAL VISI MISI ========================================= -->
        <template v-if="status == 'buka'">
            <footer id="footer" class="footer light-background">
                <div class="container">
                    <div class="credits">
                        VOTING OSIS by <a href="https://github.com/zifaucode/voting-osis" target="_blank">zifaucode</a> Template By <a href="https://bootstrapmade.com/">BootstrapMade</a>
                    </div>
                </div>
            </footer>
        </template>

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


<script>
    var id_aktif;

    function on(clicked_id) {
        document.getElementById("overlay").style.display = "block";
        document.getElementById(clicked_id).style.display = "block";
        id_aktif = clicked_id;
    }

    function off() {
        document.getElementById("overlay").style.display = "none";
        document.getElementById(id_aktif).style.display = "none";
    }
</script>
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
            candidateDetail: '',
            code_access: '',
            status: '',
            loading: false,
        },
        methods: {
            onSelcected: function(id) {
                this.candidateDetail = this.osisCandidate.filter((item) => {
                    return item.id == id;
                })[0]

            },
            submitForm: function() {
                this.loading = true;
                this.sendData();
            },
            sendData: function() {
                let vm = this;
                let data = {
                    candidate_id: vm.candidateDetail.id,
                    code_access: vm.code_access,
                }
                let formData = new FormData();
                for (var key in data) {
                    formData.append(key, data[key]);
                }

                axios.post('/user-choose', formData)
                    .then(function(response) {
                        vm.loading = true;
                        Swal.fire({
                            title: 'Berhasil',
                            text: 'Terimakasih Anda telah memilih .',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/';
                            }
                        })
                    })
                    .catch(function(error) {
                        vm.loading = false;
                        console.log(error);
                        Swal.fire({
                            title: 'Kesalahan',
                            error: true,
                            icon: 'warning',
                            text: error.response.data.message,
                        })
                    });
            },
        }
    })
</script>

<script>
    var countDownDate = new Date("{!! $web_setting->start_date !!} {!! $web_setting->start_time !!}").getTime();
    var countDownDateEnd = new Date("{!! $web_setting->start_date !!} {!! $web_setting->end_time !!}").getTime();

    var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = countDownDate - now;
        var distance2 = now - countDownDateEnd;
        var distanceEnd = countDownDateEnd - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);


        // ====================== WAKTU AKHIR =======================
        var days2 = Math.floor(distanceEnd / (1000 * 60 * 60 * 24));
        var hours2 = Math.floor((distanceEnd % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes2 = Math.floor((distanceEnd % (1000 * 60 * 60)) / (1000 * 60));
        var seconds2 = Math.floor((distanceEnd % (1000 * 60)) / 1000);
        // ====================== SELESAI ====================

        if (distance > 0 && distance2 < 0) {
            document.getElementById("demo").innerHTML = "<br><h2>HITUNG MUNDUR PEMILIHAN : </h2><br>" + days + " Hari • " + hours + " Jam • " + minutes + " Menit • " + seconds + " Detik " + "<br> <img src='/gambar/hero-img.png'> <br>";
        }

        if (distance < 0 && distance2 < 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "<br><h2>SELESAI PADA : </h2><br>" + hours2 + " Jam • " + minutes2 + " Menit • " + seconds2 + " Detik ";
            app.$data.status = 'buka';
        }

        if (distance < 0 && distance2 > 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "<br><h2>PEMILIHAN TELAH SELESAI </h2> <br> <img src='/gambar/hero-img.png'> <br>";
            app.$data.status = 'tutup';
        }

        // console.log('DEMO :', distance);
        // console.log('DEMO 2 :', distance2);
    }, 1000);
</script>



<script>
    var countDownDateEnd = new Date("{!! $web_setting->start_date !!} {!! $web_setting->end_time !!}").getTime();

    var x = setInterval(function() {

        var nowTime = new Date().getTime();
        var distanceTime = countDownDateEnd - nowTime;

        var days = Math.floor(distanceTime / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distanceTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distanceTime % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distanceTime % (1000 * 60)) / 1000);


        if (distanceTime > 0) {
            document.getElementById("getCountdown").innerHTML = "<br><h2>PEMILIHAN BERAKHIR PADA : </h2><br>" + days + " Hari - " + hours + " Jam - " + minutes + " Menit - " + seconds + " Detik ";
        }

        if (distanceTime < 0) {
            clearInterval(x);
            document.getElementById("getCountdown").innerHTML = "PEMILIHAN SELESAI";
            app.$data.status = 'tutup';

            console.log('getCountdown :', distanceTime);

            // axios.post(`/update-status`, {
            //         status: "ditutup",
            //     })
            //     .then((res) => {
            //         setInterval(function() {
            //             window.location.reload();
            //         }, 4000);

            //     })
            //     .catch((err) => {
            //         console.log(`Error  : ${err}`);
            //     });
        }
    }, 1000);
</script>