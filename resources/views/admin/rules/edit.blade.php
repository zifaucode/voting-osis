@extends('layouts-admin.app')
@section('title', 'Edit Aturan')




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
                    <h1 class="m-0">Edit setting aturan</h1>
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
                                                            <th>Pilih Gejala</th>
                                                            <!-- <th>Tingkat Kecanduan</th> -->
                                                            <th>Nama Gejala</th>
                                                            <th>Skor</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="select2-purple">
                                                                    <select id="select-question" multiple="multiple" data-placeholder="Pilih Kode Gejala" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                                                        <option value=""></option>

                                                                    </select>
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <input type="text" class="form-control" v-model="symptomName">

                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" v-model="score">

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
    const getQuestion = <?php echo Illuminate\Support\Js::from($question) ?>;
    const getAddictionLevel = <?php echo Illuminate\Support\Js::from($get_addiction_level) ?>;
    const selectedQuestion = <?php echo Illuminate\Support\Js::from(json_decode($rules->symptom_code)) ?>;
    let app = new Vue({
        el: '#app',
        data: {
            getAddictionLevel,
            addictionLevelId: '',
            symptomName: '{{ $rules->symptom_name }}',
            score: '{{ $rules->score }}',
            selectedQuestion,

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
                    addiction_level_id: vm.addictionLevelId,
                    symptom_name: vm.symptomName,
                    score: vm.score,
                    selectedQuestion: JSON.stringify(this.selectedQuestion),
                }

                axios.post('/admin/rules/{{ $rules->id }}', data)
                    .then(function(response) {
                        vm.loading = false;
                        Swal.fire({
                            title: 'Success',
                            text: 'Data has been saved',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/admin/rules/';
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