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
                    <div class="card card-info card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Semua User</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Tidak Kecanduan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Kecanduan Rendah</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Kecanduan Sedang</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-more-tab" data-toggle="pill" href="#custom-tabs-one-more" role="tab" aria-controls="custom-tabs-one-more" aria-selected="false">Kecanduan Tinggi</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">

                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
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



                                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
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
                                                <tr v-for="(user, index) in userNotAddictionList">
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


                                <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
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
                                                <tr v-for="(user, index) in userAddictionLowList">
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



                                <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
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
                                                <tr v-for="(user, index) in userAddictionMediumList">
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


                                <div class="tab-pane fade" id="custom-tabs-one-more" role="tabpanel" aria-labelledby="custom-tabs-one-more-tab">
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
                                                <tr v-for="(user, index) in userAddictionHighList">
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


            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
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
        },
        methods: {
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