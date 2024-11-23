@extends('layouts-admin.app')
@section('title')
Dashboard
@endsection


@section('head')
<style>
    /* / */
</style>


@endsection

@section('content')

<div id="app" v-cloak>

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Web Setting</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Starter Page</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>



    <div class="content">
        <div class="container-fluid">

            <div class="alert alert-secondary" role="alert">
                NOTE : Klik tombol edit berwarna kuning (Icon Pencil) pada setiap settingan yang ingin diubah
            </div>

        </div>
    </div>



    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Halaman Pemilih</h5>
                            <div style="text-align: right;">
                                <button class="btn btn-warning" data-toggle="modal" data-target="#userPageModal"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row col-12">
                                <div class="col-4">
                                    <h6 class="card-title"><b>Judul Web </b></h6>
                                </div>
                                <div class="col-8">
                                    <h6 class="card-title">: &nbsp; @{{ webTitle }}</h6>
                                </div>
                            </div>
                            <div class="row col-12">
                                <div class="col-4">
                                    <h6 class="card-title"><b>Nama Sekolah </b></h6>
                                </div>
                                <div class="col-8">
                                    <h6 class="card-title">: &nbsp; @{{ schoolName }}</h6>
                                </div>
                            </div>
                            <div class="row col-12">
                                <div class="col-4">
                                    <h6 class="card-title"><b>Tahun Pemilihan </b></h6>
                                </div>
                                <div class="col-8">
                                    <h6 class="card-title">: &nbsp; @{{ yearPeriod }}</h6>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Atur Waktu Pemilihan
                                <span class="badge badge-success" v-if="status == 'dibuka'"> @{{ status }}</span>
                                <span class="badge badge-warning" v-if="status == 'ditutup'"> @{{ status }}</span>
                            </h5>
                            <div style="text-align: right;">
                                <button class="btn btn-warning" data-toggle="modal" data-target="#votingTimeModal"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row col-12">
                                <div class="col-4">
                                    <h6 class="card-title"><b>Tanggal Mulai</b></h6>
                                </div>
                                <div class="col-8">
                                    <h6 class="card-title">: &nbsp; @{{ startDate }}</h6>
                                </div>
                            </div>
                            <div class="row col-12">
                                <div class="col-4">
                                    <h6 class="card-title"><b>Jam Mulai</b></h6>
                                </div>
                                <div class="col-8">
                                    <h6 class="card-title">: &nbsp; @{{ startTime }}</h6>
                                </div>
                            </div>
                            <div class="row col-12">
                                <div class="col-4">
                                    <h6 class="card-title"><b>Jam Selesai</b></h6>
                                </div>
                                <div class="col-8">
                                    <h6 class="card-title">: &nbsp; @{{ endTime }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <!-- ============================================= START MODAL USER PAGE ========================================= -->
    <div class="modal fade" id="userPageModal" tabindex="-1" role="dialog" aria-labelledby="userPageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userPageModalLabel">Edit Halaman Pemilih </h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputCodeAccess">JUDUL WEB</label>
                        <input type="text" class="form-control" id="exampleInputCodeAccess" v-model="webTitle" aria-describedby="codeAccessHelp" placeholder="JUDUL WEB" autocomplete="off">
                        <small id="codeAccessHelp" class="form-text text-muted">Judul web akan tampil dihalaman depan pemilih dan halaman admin</span> </small>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputCodeAccess">NAMA SEKOLAH</label>
                        <input type="text" class="form-control" id="exampleInputCodeAccess" v-model="schoolName" aria-describedby="codeAccessHelp" placeholder="NAMA SEKOLAH" autocomplete="off">
                        <small id="codeAccessHelp" class="form-text text-muted">Nama Sekolah akan tampil dihalaman depan pemilih</span> </small>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputCodeAccess">TAHUN PEMILIHAN</label>
                        <input type="text" class="form-control" id="exampleInputCodeAccess" v-model="yearPeriod" aria-describedby="codeAccessHelp" placeholder="TAHUN PEMILIHAN" autocomplete="off">
                        <small id="codeAccessHelp" class="form-text text-muted">Tahun Pemilihan akan tampil dihalaman depan pemilih</span> </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">BATAL</button>
                    <button type="button" @click.prevent="submitFormFrontPageUser" class="btn btn-success">SIMPAN</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================= END MODAL USER PAGE  ========================================= -->


    <!-- ============================================= START MODAL VOTING TIME ========================================= -->
    <div class="modal fade" id="votingTimeModal" tabindex="-1" role="dialog" aria-labelledby="votingTimeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="votingTimeModalLabel">Edit Waktu Pemilihan </h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputStartDate">TANGGAL MULAI</label>
                        <input type="date" class="form-control" id="exampleInputStartDate" v-model="startDate" aria-describedby="startDateHelp" placeholder="TANGGAL MULAI" autocomplete="off" min="<?php echo date('Y-m-d'); ?>">
                        <small id="startDateHelp" class="form-text text-muted">Tanggal Mulai untuk mengatur hari dilaksanakannya pemilihan</span> </small>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputStartTime">JAM MULAI</label>
                        <input type="time" class="form-control" id="exampleInputStartTime" v-model="startTime" aria-describedby="startTimeHelp" placeholder="JAM MULAI" autocomplete="off">
                        <small id="startTimeHelp" class="form-text text-muted">Jam Mulai akan tampil untuk mengatur hitung mundur dimulainya Pemilihan</span> </small>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEndTime">JAM SELESAI</label>
                        <input type="time" class="form-control" id="exampleInputEndTime" v-model="endTime" aria-describedby="endTimeHelp" placeholder="JAM SELESAI" autocomplete="off">
                        <small id="endTimeHelp" class="form-text text-muted">Jam Selesai untuk mengatur berakhirnya Pemilihan</span> </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">BATAL</button>
                    <button type="button" @click.prevent="submitFormVotingTime" class="btn btn-success">SIMPAN</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================= END MODAL VOTING TIME  ========================================= -->

</div>

@endsection

@section('pagescript')

@section('pagescript')
<script>
    let app = new Vue({
        el: '#app',
        data: {
            id: '{{ $web_setting->id ?? 0 }}',
            webTitle: '{{ $web_setting->web_title ?? "-" }}',
            schoolName: '{{ $web_setting->school_name ?? "-" }}',
            yearPeriod: '{{ $web_setting->year_period ?? "-" }}',
            startDate: '{{ $web_setting->start_date ?? "-" }}',
            startTime: '{{ $web_setting->start_time ?? "-" }}',
            endTime: '{{ $web_setting->end_time ?? "-" }}',
            status: '{{ $web_setting->status ?? "-" }}',
        },
        methods: {
            submitFormFrontPageUser: function() {
                this.loading = true;
                this.sendDataFrontPageUser();
            },
            submitFormVotingTime: function() {
                this.loading = true;
                this.sendDataFrontVotingTime();
            },
            sendDataFrontPageUser: function() {
                let vm = this;
                let data = {
                    id: vm.id,
                    webTitle: vm.webTitle,
                    schoolName: vm.schoolName,
                    yearPeriod: vm.yearPeriod,
                }
                let formData = new FormData();
                for (var key in data) {
                    formData.append(key, data[key]);
                }

                axios.post('/admin/web-setting/front-user-page', formData)
                    .then(function(response) {
                        vm.loading = true;
                        Swal.fire({
                            title: 'Berhasil',
                            text: 'Data Berhasil Diupdate .',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/admin/web-setting';
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
            sendDataFrontVotingTime: function() {
                let vm = this;
                let data = {
                    id: vm.id,
                    startDate: vm.startDate,
                    startTime: vm.startTime,
                    endTime: vm.endTime,
                }
                let formData = new FormData();
                for (var key in data) {
                    formData.append(key, data[key]);
                }

                axios.post('/admin/web-setting/voting-time', formData)
                    .then(function(response) {
                        vm.loading = true;
                        Swal.fire({
                            title: 'Berhasil',
                            text: 'Data Berhasil Diupdate .',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/admin/web-setting';
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
    const today = new Date().toISOString().split('T')[0];
    document.getElementById("exampleInputStartDate").min = today;
</script>


<script>
    // Get the current time in 24-hour format
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const currentTime = `${hours}:${minutes}`;

    // Set the minimum attribute of the time input
    document.getElementById("exampleInputStartTime").min = currentTime;
</script>
@endsection