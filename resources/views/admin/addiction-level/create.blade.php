@extends('layouts-admin.app')
@section('title', 'Tambah Tingkat Kecanduan')




@section('head')
<style>
    /* / */
</style>

<link rel="stylesheet" href="{{ asset('admin-page/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-page/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">


@endsection

@section('content')

<div id="app" v-cloak>

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Tingkat Kecanduan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Tambah </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div style="text-align: right;">
                                <a type="button" class="btn text-center btn-primary me-5" v-on:click="addRow">
                                    Tambah <i class="fas fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <form class="form" @submit.prevent="submitForm">
                            <div class="card-body" v-for="(addiction, index) in addictionLevelList">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-outline card-info">
                                            <div class="card-header">

                                            </div>

                                            <div class="card-body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>

                                                            <th>tingkat kecanduan</th>
                                                            <th>Solusi</th>
                                                            <th>Dari Skor</th>
                                                            <th style="text-align: center;"></th>
                                                            <th>Sampai Skor</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>

                                                            <td>
                                                                <select class="form-control" v-model="addiction.addiction_level">
                                                                    <option value="">- PILIH TINGKAT KECANDUAN-</option>
                                                                    <option value="Tidak Kecanduan">Tidak Kecanduan</option>
                                                                    <option value="Kecanduan ringan">Kecanduan ringan</option>
                                                                    <option value="Kecanduan sedang">Kecanduan sedang</option>
                                                                    <option value="Kecanduan berat">Kecanduan berat</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <textarea type="text" class="form-control" v-model="addiction.solution"></textarea>
                                                            </td>


                                                            <td style="text-align: center; width: 100px;">
                                                                <input type="text" class="form-control" v-model="addiction.start_score">
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrows" viewBox="0 0 16 16">
                                                                    <path d="M1.146 8.354a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L2.707 7.5h10.586l-1.147-1.146a.5.5 0 0 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L13.293 8.5H2.707l1.147 1.146a.5.5 0 0 1-.708.708z" />
                                                                </svg>
                                                            </td>
                                                            <td style="text-align: center; width: 100px;">
                                                                <input type="text" class="form-control" v-model="addiction.end_score">
                                                            </td>

                                                            <td>
                                                                <a href="#" @click.prevent="deleteRow(index)" class="btn btn-danger btn-icon" title="Delete">
                                                                    <span class="svg-icon svg-icon-md">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                                        </svg>
                                                                    </span>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>




                                            </div>

                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="card-footer" style="text-align: right;">
                                <button type="submit" class="btn btn-primary" :class="loading && 'spinner spinner-white spinner-right'" :disabled="loading"> Simpan </button>
                            </div>
                        </form>

                    </div>



                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

</div>

@endsection


@section('pagescript')


<script src="{{ asset('admin-page/plugins/select2/js/select2.full.min.js') }}"></script>


<script>
    let app = new Vue({
        el: '#app',
        data: {
            addictionLevelList: [{
                'addiction_level': '',
                'solution': '',
                'start_score': '',
                'end_score': '',
            }],

            loading: false,

        },
        methods: {
            addRow: function(event) {
                const data = {
                    ...[]
                };
                data['addiction_level'] = '';
                data['solution'] = '';
                data['start_score'] = '';
                data['end_score'] = '';


                this.addictionLevelList.push(data);
            },
            deleteRow: function(index) {
                this.addictionLevelList.splice(index, 1);
            },
            submitForm: function() {
                this.sendData();
            },
            sendData: function() {
                let vm = this;
                vm.loading = true;

                const data = {
                    addictionLevelList: vm.addictionLevelList,
                }

                axios.post('/admin/addiction-level/', data)
                    .then(function(response) {
                        vm.loading = false;
                        Swal.fire({
                            title: 'Success',
                            text: 'Data has been saved',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/admin/addiction-level/';
                            }
                        })
                        // console.log(response);
                    })
                    .catch(function(error) {
                        vm.loading = false;
                        console.log(error);
                        Swal.fire(
                            'Oops!',
                            'Something wrong',
                            'error'
                        )
                    });
            },
        }
    })
</script>


<script>
    let select2Question = [];
    if (Array.isArray(getQuestion)) {
        select2Question = getQuestion.map(questinMaps => ({
            id: questinMaps.id || '',
            text: questinMaps.number || '',
        }));
    }

    $(function() {

        function formatState2(state) {
            if (!state.id) {
                return state.text;
            }

            var $state = $(
                `
                <div class="">
                    <div>
                        <span class="fw-bold">${state.text}</span>
                    </div>
                </div>
                `
            );
            return $state;
        };

        $('#select-question').select2({
            data: select2Question,
            templateResult: formatState2,
        });

        $('#select-question').on('change', function(e) {
            const values = $(this).val();
            app.$data.selectedQuestion = values;
        });
    })
</script>

<script>
    $(function() {
        // Summernote
        $('#summernote').summernote()


        // $('.select2').select2()

        // $('.select2bs4').select2({
        //     theme: 'bootstrap4'
        // })
    })
</script>
@endsection