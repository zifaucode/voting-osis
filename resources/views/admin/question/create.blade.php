@extends('layouts-admin.app')
@section('title', 'Tambah Gejala')




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
                        <li class="breadcrumb-item active">Tambah Data</li>
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
                            <div class="card-body" v-for="(questions, index) in questionList">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-outline card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    Pertanyaan
                                                </h3>
                                            </div>

                                            <div class="card-body">
                                                <textarea class="form-control" v-model="questions.question" rows="3"> </textarea>
                                            </div>
                                            <div class="card-footer">

                                                <div class="form-group" v-for="(options, index2) in questions.option">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Pilihan</th>
                                                                <th style="text-align: center;">CF</th>
                                                                <th style="text-align: center;">MB</th>
                                                                <th style="text-align: center;">MD</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td> <input type="text" class="form-control" v-model="options.opt" id="question"></td>
                                                                <td style="text-align:center; width: 100px;"> <input type="text" class="form-control" v-model="options.cf" id="cf"></td>
                                                                <td style="text-align:center; width: 100px;"> <input type="text" class="form-control" v-model="options.mb" id="cf"></td>
                                                                <td style="text-align:center; width: 100px;"> <input type="text" class="form-control" v-model="options.md" id="cf"></td>
                                                                <td style="text-align:center; width: 100px;">
                                                                    <a href="#" @click.prevent="deleteOption(index,index2)" class="btn btn-danger btn-icon" title="Delete">
                                                                        <span class="svg-icon svg-icon-md">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
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
                                                <a type="button" class="btn text-center btn-primary me-5" @click.prevent="addOption(index)">
                                                    <i class="fas fa fa-plus"></i>
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <a href="#" @click.prevent="deleteRow(index)" class="btn btn-danger btn-icon" title="Delete">
                                        <span class="svg-icon svg-icon-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                            </svg>
                                        </span>
                                    </a>
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

@section('pagescript')

<script>
    let app = new Vue({
        el: '#app',
        data: {
            questionList: [{
                'question': '',
                'option': [{
                    'opt': '',
                    'cf': '',
                    'mb': '',
                    'md': '',
                }],
            }],
            loading: false,

        },
        methods: {
            addRow: function(event) {
                const data = {
                    ...[]
                };
                data['question'] = '';
                data['option'] = [{
                    'opt': '',
                    'cf': '',
                    'mb': '',
                    'md': '',
                }];

                this.questionList.push(data);
            },
            deleteRow: function(index) {
                this.questionList.splice(index, 1);
            },
            addOption: function(index) {
                data = {
                    'opt': '',
                    'cf': '',
                    'mb': '',
                    'md': '',
                };
                this.questionList[index]['option'].push(data);
            },
            deleteOption: function(deletedIndex, deletedOptionIndex) {
                this.questionList[deletedIndex]['option'].splice(deletedOptionIndex, 1);
            },
            submitForm: function() {
                this.sendData();
            },
            sendData: function() {
                let vm = this;
                vm.loading = true;

                const data = {
                    questionList: vm.questionList,
                }

                axios.post('/admin/question/', data)
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