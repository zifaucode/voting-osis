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
            border: 10px solid #20c997;
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

                <div class="container section-title" data-aos="fade-up">
                    <h2>{{ $web_setting->web_title ?? 'JUDUL WEB' }}</h2>
                    <div><span>{{ $web_setting->school_name ?? 'NAMA SEKOLAH' }}</span> <span class="description-title">{{ $web_setting->year_period ?? 'TAHUN PERIODE' }} / {{ $year_plus ?? 'TAHUN PERIODE' }}</span></div>
                </div>

                <div class="container">

                    <div class="row gy-4">

                        <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="100" v-for="candidate in osisCandidate">
                            <div class="pricing-tem">
                                <span class="featured">@{{ candidate.class ?? 'KELAS' }}</span>
                                <h3 style="color: #20c997;">@{{ candidate.name ?? 'NAMA KANDIDAT' }}</h3>

                                <div class="frame-image">
                                    <figure>
                                        <img :src="`/file/image/` + candidate.image" width="220px" height="360px" alt="Example Photo">
                                    </figure>
                                </div>
                                <!-- <img :src="`/file/image/` + candidate.image" alt="" width="250px" height="300px" style="background-color: #20c997;"> -->

                                <div class="icon">
                                    <i class="bi bi-box" style="color: #20c997;">@{{ candidate.sequence_number ?? 'NOMOR URUT'}}</i>
                                </div>

                                <button type="button" @click="onSelcected(candidate.id)" class="btn-buy" data-toggle="modal" data-target="#chooseCandidateModal">PILIH KANDIDAT INI</button>

                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <!-- ==================================== END CANDIDATE SECTION ============================== -->

        </main>


        <!-- ============================================= START MODAL CHOOSE CANDIDATE ========================================= -->
        <div class="modal fade" id="chooseCandidateModal" tabindex="-1" role="dialog" aria-labelledby="chooseCandidateModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="chooseCandidateModalLabel">Anda Memilih : @{{ candidateDetail.name ?? 'NAMA KANDIDAT'}}</h5>
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
                                <div class="card-header bg-success text-white">
                                    @{{ candidate.sequence_number ?? 'NOMOR URUT' }} - @{{ candidate.name ?? 'NAMA KANDIDAT'}}
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><b>VISI : </b>
                                        <br>
                                        @{{ candidate.visi ?? 'VISI'}}
                                        <br>
                                    </li>
                                    <li class="list-group-item"><b>MISI :</b>
                                        <br>
                                        <br>
                                        @{{ candidate.misi ?? 'MISI' }}
                                        <br>
                                    </li>
                                </ul>
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


    </div>

    <footer id="footer" class="footer light-background">

        <div class="container">

            <div class="credits">
                VOTING OSIS by <a href="https://github.com/zifaucode/voting-osis" target="_blank">zifaucode</a> Template By <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>

    </footer>

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