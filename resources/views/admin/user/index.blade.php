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
                    <h1 class="m-0">Data User (Pemilih)</h1>
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
                            <div class="callout callout-success mb-4">
                                <h5>Silahkan Upload File Excel dengan format yang sudah disediakan dibawah ini</h5>

                                <p>download file template dengan format yang sudah di siapkan pada file disini.
                                    <a href="/file/format/EXEL-IMPORT.xlsx" class="btn btn-sm btn-success" style="text-decoration: none; color:white;">Download Format Excel</a>
                                </p>
                                <br>
                                <p>Upload File yang sudah di isi dari template yang sudah di siapkan</p>
                                <div class="row col-9">
                                    <div class="col-6">

                                        <input type="file" ref="file" class="form-control" accept=".xls, .xlsx, .csv" v-on:change="handleFileUpload">
                                    </div>
                                    <div class="col-3">
                                        <button type="button" class="btn btn-primary" @click.prevent="submitForm" :disabled="loading"> Upload
                                            <span :class="loading && 'spinner-grow'" role="status"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <br>

                            <div class="table-responsive mb-4">
                                <div style="text-align: right;">
                                    <button class="btn btn-danger" @click.prevent="deleteSelectedRecord" v-if="userIds.length > 0">
                                        Hapus yang diceklis
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                        </svg>
                                    </button>
                                </div>

                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="text-center" style="width: 30px;"><input class="form-control" type="checkbox" @click="selectAll" v-model="allSelected" width="50%"></td>
                                            <th class="text-center" style="width: 30px;">No </th>
                                            <th>Nama </th>
                                            <th>Kelas </th>
                                            <th>Kode Akses </th>
                                            <th style="width: 70px;">Status </th>
                                            <th class="text-center" style="width: 70px;">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(user, index) in users">
                                            <td class="text-center">
                                                <input class="form-control" type="checkbox" v-model="userIds" :value="user.id" width="50%">
                                            </td>
                                            <td class="text-center">@{{ index+1 }}</td>
                                            <td>@{{ user.name ?? ''}}</td>
                                            <td>@{{ user.class ?? ''}}</td>
                                            <td>@{{ user.code_access ?? ''}}</td>
                                            <td>
                                                <span class="badge badge-success" v-if="user.vote_status == 1">Sudah Memilih</span>
                                                <span class="badge badge-warning" v-else>Belum Memilih</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <button class="btn btn-sm btn-danger" @click.prevent="deleteRecord(user.id)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                    </svg>

                                                </button>
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
    let app = new Vue({
        el: '#app',
        data: {
            users,
            file: '',
            allSelected: false,
            userIds: [],
            loading: false,
        },
        computed: {
            selectedOne() {
                const userId = this.userIds;
                return this.users.filter(getUser => userId.includes(getUser.id))
            },
        },
        methods: {
            selectAll: function() {
                this.userIds = [];
                if (!this.allSelected) {
                    for (user in this.users) {
                        this.userIds.push(users[user].id);
                    }
                }
            },
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
                this.loading = true;
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

                axios.post('/admin/user/import-excel', formData)
                    .then(function(response) {
                        vm.loading = true;
                        Swal.fire({
                            title: 'Success',
                            text: 'Data Pemilih berhasil disimpan.',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/admin/user';
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
            deleteRecord: function(id) {
                Swal.fire({
                    title: 'Apakah Kamu Yakin?',
                    text: "Data akan dihapus dari sistem",
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
                            title: 'Berhasil',
                            text: 'Data berhasil dihapus',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                    }
                })
            },
            deleteSelectedRecord: function() {
                Swal.fire({
                    title: 'Apakah Kamu Yakin?',
                    text: "Data yang anda ceklis akan dihapus dari sistem",
                    icon: 'warning',
                    reverseButtons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        let vm = this;
                        let data = {
                            userIds: vm.userIds,
                        }
                        return axios.post('/admin/user/selected-delete', data)
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
                            title: 'Berhasil',
                            text: 'Data berhasil dihapus',
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