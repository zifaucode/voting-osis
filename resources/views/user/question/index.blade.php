@extends('layouts-admin.app')
@section('title')
Survey Gejala
@endsection


@section('head')
<link rel="stylesheet" href="{{ asset('admin-page/plugins/bs-stepper/css/bs-stepper.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-page/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">


@endsection

@section('content')

<div id="app" v-cloak>

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Survey Kecanduan Game Online</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Survey</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>



    <section class="content">
        <div class="container-fluid">

            @auth
            @if(auth()->user()->answer_status != 1)
            <div class="row">

                <div class="card-body">
                    <div class="callout callout-info">
                        <div class="bs-stepper">
                            <div class="bs-stepper-header" role="tablist">
                                <template v-for="(questions, index) in questionGet">

                                    <div class="step" :data-target="`#page`+index">
                                        <button type="button" class="step-trigger" role="tab" :aria-controls="`page`+index" :id="`page-trigger`+index">
                                            <!-- <span class="bs-stepper-circle">@{{ index+1 }}/@{{ questionGet.length }}</span> -->
                                        </button>
                                    </div>
                                    <!-- <div>---</div> -->
                                </template>

                            </div>
                            <div class="bs-stepper-content">



                                <!-- =============================================================================================================== -->

                                <template v-for="(questions, index) in question">
                                    <div :id="`page`+index" class="content" role="tabpanel" :aria-labelledby="`page-trigger`+index">

                                        <h5><b> @{{ index+1 }} / @{{ question.length }} </b></h5>

                                        <div class="mb-4 py-4">
                                            <div class="mb-4">

                                                <h1 class="card-title"> @{{ questions.question_content }}</h1>
                                                <!-- <h1 class="card-title"> @{{ questions.value }}</h1> -->

                                            </div>
                                            <br>
                                            <br>
                                            <template v-for="(opt, indexOpt) in questions.options">
                                                <div class="icheck-primary">
                                                    <input type="radio" :id="`radioPrimary`+ index + indexOpt" :name="`name`+ index" v-model="questions.value" :value="opt.id">
                                                    <label :for="`radioPrimary`+ index +  indexOpt">
                                                        <h2 class="card-title"> @{{ opt.option }}</h2>

                                                    </label>
                                                </div>
                                            </template>

                                        </div>



                                        <button class="btn btn-secondary" onclick="stepper.previous()" v-if="index+1 > 1"><i class="nav-icon fa fa-arrow-circle-left"></i> Sebelumnya</button>
                                        <button class="btn btn-info" onclick="stepper.next()" v-if="index+1 < question.length" :disabled="questions.value ==0">Selanjutnya <i class="nav-icon fa fa-arrow-circle-right"></i></button>
                                        <button class="btn btn-success" @click.prevent="submitForm()" v-if="index+1 == question.length">
                                            Simpan Jawaban
                                            &nbsp;
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy-fill" viewBox="0 0 16 16">
                                                <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5z" />
                                                <path d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5zM9 1h2v4H9z" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @endif

            @if(auth()->user()->answer_status == 1)
            <div class="col-sm-12">
                <div class="position-relative p-3 bg-info" style="height: 650px">
                    <div class="ribbon-wrapper ribbon-xl">
                        <div class="ribbon bg-primary">
                            {{ $user_result->answer_result ?? ''}}
                        </div>
                    </div>
                    <div class="text-center text-dark">

                        <h1>
                            <blockquote>
                                <img src="/gambar/kecanduan-preview.png" alt="" width="200px">

                                <h2 class="text-dark"> " {{ $user_result->answer_result ?? ''}} "</h2>
                                <a href="/user/result" class="btn btn-primary">
                                    Lihat Hasil Lengkap &nbsp;
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-square-fill" viewBox="0 0 16 16">
                                        <path d="M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1" />
                                    </svg>
                                </a>

                                <p style="text-align: left; font-size:16px;">keterang diagnosis :</p>
                                @foreach ( $symptom_name as $analisis)

                                <h5 class="text-dark" style="text-align: left; font-size:13px;"> - {{ $analisis }}</h5>
                                @endforeach




                            </blockquote>

                        </h1>
                    </div>
                </div>
            </div>
            @endif
            @endauth

        </div>
    </section>

</div>

@endsection

@section('pagescript')

@section('pagescript')
<script>
    let question = <?php echo Illuminate\Support\Js::from($question) ?>;
    question = question.map(items => ({
        id: items.id,
        options: items.options,
        question_content: items.question_content,
        value: 0,
    }));
    let app = new Vue({
        el: '#app',
        data: {
            question,
        },
        methods: {
            submitForm: function() {
                this.sendData();
            },
            sendData: function() {
                let vm = this;
                vm.loading = true;

                const data = {
                    questionList: vm.question,
                }

                axios.post('/user/result/', data)
                    .then(function(response) {
                        vm.loading = false;
                        Swal.fire({
                            title: 'Success',
                            text: 'Data has been saved',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/user/question/';
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
        },
        computed: {
            questionGet: function() {
                return this.question.map(items => ({
                    id: items.id,
                    options: items.options,
                    question_content: items.question_content,
                    value: 0,
                }));


            },
        },
    })
</script>
<script src="{{ asset('admin-page/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>



<script>
    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function() {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })
</script>
@endsection