@extends('layouts-admin.app')
@section('title', 'Edit Tingkat Kecanduan')




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
                    <h1 class="m-0">Edit Tingkat Kecanduan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit </li>
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

                        </div>
                        <form class="form" @submit.prevent="submitForm">
                            <div class="card-body">
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

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>

                                                            <td>
                                                                <select class="form-control" v-model="addiction_level">
                                                                    <option value="">- PILIH TINGKAT KECANDUAN-</option>
                                                                    <option value="Tidak Kecanduan">Tidak Kecanduan</option>
                                                                    <option value="Kecanduan ringan">Kecanduan ringan</option>
                                                                    <option value="Kecanduan sedang">Kecanduan sedang</option>
                                                                    <option value="Kecanduan berat">Kecanduan berat</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <textarea type="text" class="form-control" v-model="solution"></textarea>
                                                            </td>


                                                            <td style="text-align: center; width: 100px;">
                                                                <input type="text" class="form-control" v-model="start_score">
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrows" viewBox="0 0 16 16">
                                                                    <path d="M1.146 8.354a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L2.707 7.5h10.586l-1.147-1.146a.5.5 0 0 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L13.293 8.5H2.707l1.147 1.146a.5.5 0 0 1-.708.708z" />
                                                                </svg>
                                                            </td>
                                                            <td style="text-align: center; width: 100px;">
                                                                <input type="text" class="form-control" v-model="end_score">
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

            addiction_level: '{{ $get_addiction_level->level }}',
            solution: '{{ $get_addiction_level->solution }}',
            start_score: '{{ $get_addiction_level->start_score }}',
            end_score: '{{ $get_addiction_level->end_score }}',

            loading: false,

        },
        methods: {
            submitForm: function() {
                this.sendData();
            },
            sendData: function() {
                let vm = this;
                vm.loading = true;

                const data = {
                    addiction_level: vm.addiction_level,
                    solution: vm.solution,
                    start_score: vm.start_score,
                    end_score: vm.end_score,
                }

                axios.post('/admin/addiction-level/{{ $get_addiction_level->id }}', data)
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