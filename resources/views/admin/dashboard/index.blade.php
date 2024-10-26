@extends('layouts-admin.app')
@section('title')
Dashboard
@endsection


@section('head')



@endsection

@section('content')

<div id="app" v-cloak>

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $user_total }}</h3>
                            <p>Total User</p>
                        </div>
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-person-rolodex" viewBox="0 0 16 16">
                                <path d="M8 9.05a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                                <path d="M1 1a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h.5a.5.5 0 0 0 .5-.5.5.5 0 0 1 1 0 .5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5.5.5 0 0 1 1 0 .5.5 0 0 0 .5.5h.5a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H6.707L6 1.293A1 1 0 0 0 5.293 1zm0 1h4.293L6 2.707A1 1 0 0 0 6.707 3H15v10h-.085a1.5 1.5 0 0 0-2.4-.63C11.885 11.223 10.554 10 8 10c-2.555 0-3.886 1.224-4.514 2.37a1.5 1.5 0 0 0-2.4.63H1z" />
                            </svg>
                        </div>
                        <a href="/admin/user" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $user_not_addiction_total }}</h3>
                            <p>Total Tidak Kecanduan</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer"> <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $user_addiction_low_total }}</h3>
                            <p>Total Kecanduan Ringan</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer"> <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $user_addiction_medium_total }}</h3>
                            <p>Total Kecanduan Sedang</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer"> <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $user_addiction_high_total }}</h3>
                            <p>Total Kecanduan Berat</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer"> <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>



            <div class="row col-lg-12">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="myChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="myChartPie" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>

</div>

@endsection



@section('pagescript')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



<script>
    const chart = <?php echo Illuminate\Support\Js::from($chart) ?>;

    let app = new Vue({
        el: '#app',
        data: {
            chart,
        },
        methods: {

        }
    })
</script>

<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Tidak Kecanduan', 'Kecanduan Ringan', 'Kecanduan Sedang', 'Kecanduan Berat'],
            datasets: [{
                label: '# Tingkat Kecanduan',
                // data: [12, 19, 3, 5],
                data: chart,
                backgroundColor: [
                    'rgb(125, 125, 125)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(245, 66, 66)',
                ],
                hoverOffset: 4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script>
    const pieCh = document.getElementById('myChartPie');

    new Chart(pieCh, {
        type: 'doughnut',
        data: {
            labels: ['Tidak Kecanduan', 'Kecanduan Ringan', 'Kecanduan Sedang', 'Kecanduan Berat'],
            datasets: [{
                label: '# Tingkat Kecanduan',
                // data: [12, 19, 3, 5],
                data: chart,
                backgroundColor: [
                    'rgb(125, 125, 125)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(245, 66, 66)',
                ],
                hoverOffset: 4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>


@endsection