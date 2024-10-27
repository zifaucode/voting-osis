@extends('layouts-admin.app')
@section('title')
List User
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
                    <h1 class="m-0">Data User</h1>
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
            <div class="row">


                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="callout callout-success">
                                <h5>Silahkan Upload File Excel dengan format yang sudah disediakan dibawah ini</h5>

                                <p>download file template dengan format yang sudah di siapkan pada file disini.
                                    <a href="/file/format/EXEL-IMPORT.xlsx" class="btn btn-sm btn-success" style="text-decoration: none; color:white;">Download Format Excel</a>
                                </p>
                                <br>
                                <p>Upload File yang sudah di isi dari template yang sudah di siapkan. <br>
                                    <input type="file" ref="file" class="form-control" accept=".xls, .xlsx, .csv" v-on:change="handleFileUpload">
                                    <button type="button" class="btn btn-sm btn-primary" @click.prevent="submitForm"> Upload</button>

                                </p>
                            </div>
                            <div class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 30px;">No </th>
                                            <th>Nama </th>
                                            <th>Email </th>
                                            <th>Status </th>
                                            <th class="text-center" style="width: 200px;">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(user, index) in users">
                                            <td class="text-center">@{{ index+1 }}</td>
                                            <td>@{{ user.name ?? ''}}</td>
                                            <td>@{{ user.email ?? ''}}</td>
                                            <td>
                                                <span class="badge badge-success" v-if="user.answer_status == 1">Sudah Mengisi</span>
                                                <span class="badge badge-warning" v-else>Belum Mengisi</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <button class="btn btn-sm btn-warning mr-2" @click.prevent="resetQuestion(user.id)">Reset Jawaban</button>
                                                <button class="btn btn-sm btn-danger" @click.prevent="deleteRecord(user.id)">Hapus</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>


        </div>
        <!-- /.row -->
    </div>
</div>

</div>

@endsection


@section('pagescript')
<script>
    const users = <?php echo Illuminate\Support\Js::from($user) ?>;
    const userNotAddictionList = <?php echo Illuminate\Support\Js::from($user_not_addiction_list) ?>;
    const userAddictionLowList = <?php echo Illuminate\Support\Js::from($user_low_addiction_list) ?>;
    const userAddictionMediumList = <?php echo Illuminate\Support\Js::from($user_medium_addiction_list) ?>;
    const userAddictionHighList = <?php echo Illuminate\Support\Js::from($user_high_addiction_list) ?>;

    let app = new Vue({
        el: '#app',
        data: {
            users,
            userNotAddictionList,
            userAddictionLowList,
            userAddictionMediumList,
            userAddictionHighList,
            file: '',
        },
        methods: {
            handleFileUpload() {
                this.file = this.$refs.file.files[0];
                if (!this.file) {
                    return;
                }
                if (!this.isValidFileType(this.file)) {
                    return;
                }
            },
            isValidFileType(file) {
                const allowedExtensions = ['.xls', '.xlsx', '.csv'];
                return allowedExtensions.includes(file.name.split('.').pop().toLowerCase());
            },
            submitForm: function() {
                this.sendData();
            },
            sendData: function() {
                let vm = this;
                let data = {
                    file: vm.file,
                }
                let formData = new FormData();
                for (var key in data) {
                    formData.append(key, data[key]);
                }

                axios.post('/admin/osis-candidate/import_excel', formData)
                    .then(function(response) {
                        vm.loading = false;
                        Swal.fire({
                            title: 'Success',
                            text: 'Data Kandidat Osis berhasil disimpan.',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/admin/osis-candidate';
                            }
                        })
                    })
                    .catch(function(error) {
                        vm.loading = false;
                        console.log(error);
                        Swal.fire({
                            title: 'Internal Error',
                            error: true,
                            icon: 'error',
                            text: error.response.data.message,
                        })
                    });
            },
            resetQuestion: function(id) {
                Swal.fire({
                    title: 'Apakah yakin ingin mereset jawaban?',
                    text: "data jawaban akan terhapus, dan user bisa menjawab kembali pertanyaan",
                    icon: 'warning',
                    reverseButtons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Reset',
                    cancelButtonText: 'Cancel',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios.post('/admin/user/reset/' + id)
                            .then(function(response) {
                                console.log(response.data);
                            })
                            .catch(function(error) {
                                console.log(error.data);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops',
                                    text: 'Something wrong',
                                })
                            });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Data has been Reset',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                    }
                })
            },
            deleteRecord: function(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "The data will be deleted",
                    icon: 'warning',
                    reverseButtons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios.delete('/admin/user/' + id)
                            .then(function(response) {
                                console.log(response.data);
                            })
                            .catch(function(error) {
                                console.log(error.data);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops',
                                    text: 'Something wrong',
                                })
                            });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Data has been deleted',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                    }
                })
            }
        }
    })
</script>
@endsection