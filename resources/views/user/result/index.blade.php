@extends('layouts-admin.app')
@section('title')
Hasil
@endsection


@section('head')



@endsection

@section('content')

<div id="app" v-cloak>



    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Hasil Pakar</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/user/question">Home</a></li>
                        <li class="breadcrumb-item active">Detail Hasil Pakar</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">

            <div class="row">


                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <!-- <h5 class="m-0">Hasil</h5> -->

                        </div>


                        <div class="card-body">

                            @if($count_result_question > 0)

                            <div class="mb-4" style="text-align:right;">
                                <a href="/user/result/print/1" class="btn btn-info" target="_blank">Cetak Hasil <i class="nav-icon fa fa-print"></i></a>
                            </div>
                            <div class="alert alert-info" role="alert">
                                <h4 class="alert-heading">Terimakasih sudah menyelesaikan Diagnosis &nbsp;
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-patch-check-fill" viewBox="0 0 16 16">
                                        <path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708" />
                                    </svg>
                                </h4>
                                <p><b> HASIL PAKAR: </b></p>
                                @foreach($symptom_name as $symptomName)
                                <p>- {{ $symptomName }}</p>
                                @endforeach

                                <hr>
                                <p class="mb-0"><b> TINGKAT KECANDUAN : </b>&nbsp;
                                    @if($analysis_result->addictionLevel->code == 'P-001')
                                    <span class="badge badge-success">{{ $analysis_result->addictionLevel->level }}</span>
                                    @elseif( $analysis_result->addictionLevel->code == 'P-002' )
                                    <span class="badge badge-secondary">{{ $analysis_result->addictionLevel->level }}</span>
                                    @elseif( $analysis_result->addictionLevel->code == 'P-003' )
                                    <span class="badge badge-warning">{{ $analysis_result->addictionLevel->level }}</span>
                                    @else
                                    <span class="badge badge-danger">{{ $analysis_result->addictionLevel->level }}</span>
                                    @endif

                                </p>
                                <hr>
                                @if($analysis_result->addictionLevel->code != 'P-001')
                                <p class="mb-0"><b> SOLUSI : </b>&nbsp; {{ $analysis_result->addictionLevel->solution }}</p>
                                @endif
                            </div>

                            <table class="table table-bordered">
                                <thead>
                                    <th>Gejala</th>
                                    <th>Jawaban Anda</th>
                                </thead>
                                <tbody>
                                    <tr v-for="resultQuestions in resultQuestion">
                                        <td>@{{ resultQuestions.question?.question_content }}</td>
                                        <td>@{{ resultQuestions.options?.option }}</td>
                                    </tr>
                                </tbody>
                            </table>


                            @else
                            <div class="alert alert-warning" role="alert">
                                <h4 class="alert-heading">Belum Ada hasil &nbsp;
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-octagon" viewBox="0 0 16 16">
                                        <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z" />
                                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                                    </svg>
                                </h4>

                                <p class="mb-0"><b> Silahkan isi diagnosis terlebih dahulu &nbsp; <a href="/user/question" class="btn btn-sm btn-info">Diagnosis Sekarang</a> </b></p>
                            </div>

                            @endif
                        </div>

                    </div>



                </div>


            </div>

        </div>
    </section>

</div>

@endsection

@section('pagescript')

@section('pagescript')
<script>
    const resultQuestion = <?php echo Illuminate\Support\Js::from($result_question) ?>;
    let app = new Vue({
        el: '#app',
        data: {
            resultQuestion,

        },
        methods: {

        }
    })
</script>
@endsection