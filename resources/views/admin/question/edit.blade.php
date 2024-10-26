@extends('layouts-admin.app')
@section('title')
Edit Gejala
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
                    <h1 class="m-0">Data Gejala</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Data</li>
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
                            <h5 class="m-0">Edit Gejala - <b>@{{ questionList.number }}</b></h5>

                        </div>
                        <form class="form" @submit.prevent="submitForm">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-outline card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    <!-- Pertanyaan -->
                                                </h3>
                                            </div>

                                            <div class="card-body">
                                                <textarea class="form-control" v-model="questionList.question_content" rows="3"> </textarea>
                                            </div>
                                            <div class="card-footer">

                                                <div class="form-group" v-for="opt in questionList.options">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Pilihan</th>
                                                                <th style="text-align: center;">CF</th>
                                                                <th style="text-align: center;">MB</th>
                                                                <th style="text-align: center;">MD</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td> <input type="text" class="form-control" v-model="opt.option" id="question"></td>
                                                                <td style="text-align:center; width: 100px;"> <input type="text" class="form-control" v-model="opt.cf_value" id="question"></td>
                                                                <td style="text-align:center; width: 100px;"> <input type="text" class="form-control" v-model="opt.mb_value" id="question"></td>
                                                                <td style="text-align:center; width: 100px;"> <input type="text" class="form-control" v-model="opt.md_value" id="question"></td>

                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <label for="question">Pilihan </label>



                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="card-footer" style="text-align: right;">
                                <a href="/admin/question" type="submit" class="btn btn-secondary"> Kembali </a>
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

@section('pagescript')

<script>
    const questionList = <?php echo Illuminate\Support\Js::from($question) ?>;
    let app = new Vue({
        el: '#app',
        data: {
            questionList,
            id: '{{ $question->id }}',
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
                    questionList: vm.questionList,
                }

                axios.post('/admin/question/{{ $question->id }}', data)
                    .then(function(response) {
                        vm.loading = false;
                        Swal.fire({
                            title: 'Success',
                            text: 'Data has been saved',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/admin/question/';
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
    $(function() {
        // Summernote
        $('#summernote').summernote()

        // CodeMirror
        CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
            mode: "htmlmixed",
            theme: "monokai"
        });
    })
</script>
@endsection