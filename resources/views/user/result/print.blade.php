<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESULT</title>
    <style>
        @page {
            margin: 20px;
            /* padding: 0px 0px 0px 0px !important; */
        }

        body {
            font-size: 10px;
            font-family: sans-serif;
        }

        table {
            border-collapse: collapse;
        }

        .table-header th,
        .table-header td {
            padding: 3px;
        }

        .bordered-table th,
        .bordered-table td {
            border: 1px solid #000;
            padding: 3px;
        }

        .card {
            background-color: #048b8e;
            /* Green color */
            border-radius: 10px;
            /* Rounded corners */
            padding: 20px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
            /* Shadow effect */
            color: white;
            /* Text color */
        }
    </style>
</head>

<body>
    <div class="header" style="float: left; margin-bottom: 10;">
        <div style="width: 50%; float: left;">

            <span class="clearfix"> <img src="gambar/UNPAM-removebg.png" alt="logo" height="45"></span>

            <img src="gambar/logorsu (1).png" alt="logo" height="40">

            <!-- <div style="margin-top: 5px">
                <span style="display: block;">Nama Perusahaan</span>
                <span style="display: block;">Alamat Perusahaan </span>
                <span style="display: block;">Fax</span>
            </div> -->
            <div style="margin-top: 10px;">
                <table>

                    <tr>
                        <td style="padding-left: 10px; vertical-align: top;">
                            <span>Jl. Raya Pajajaran No.101 Pamulang Barat,<br> Kota Tangerang Selatan 15417 Banten</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="margin-top:100px">

            <table class="table-header" style="margin-top:100px;margin-left: auto;">
                <tr>
                    <td colspan="3">
                        <h1 style="text-align:right">Hasil Pakar</h1>
                    </td>
                </tr>

                <tr>
                    <td>Nama .</td>
                    <td>:</td>
                    <td>{{ $user->name ?? ''}}</td>
                </tr>
                <tr>
                    <td>Umur .</td>
                    <td>:</td>
                    <td>{{ $user->age ?? ''}} Tahun</td>
                </tr>

                <tr>
                    <td>No telp</td>
                    <td>:</td>
                    <td>{{ $user->phone ?? ''}}</td>
                </tr>

                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $user->address ?? ''}}</td>
                </tr>


                <tr>
                    <td>Gender</td>
                    <td>:</td>
                    <td>{{ $user->gender ?? ''}}</td>
                </tr>

                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td>{{ $user->email ?? ''}}</td>
                </tr>



            </table>
        </div>
    </div>

    <div>
        <table class="bordered-table" style="width: 100%">
            <thead>
                <tr>
                    <th>Kode Gejala</th>
                    <th>Gejala</th>
                    <th>Jawaban </th>

                </tr>
            </thead>
            <tbody>
                @foreach($result_ruestion as $question)
                <tr>
                    <td style="text-align: center;">{{ $question->question->number ?? '' }}</td>
                    <td>{{ $question->question->question_content ?? '' }}</td>
                    <td style="text-align: center;">{{ $question->options->option ?? '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        <br>
        <div class="card">
            <h3>Hasil Pakar :</h3>
            @foreach ( $symptom_name as $analisis)
            <h5 style="text-align: left;"> - {{ $analisis }}</h5>
            @endforeach
        </div>
        <br>
        <p> Hasil dari pakar adalah kamu dalam kategori <b style="color: red; text-transform:uppercase;">{{ $analysis_result->addictionLevel->level ?? 'KECANDUAN TIDAK TERDEFINISI' }}</b> Dengan persentase {{ ($analysis_result->cf_last ?? 0) * 100 }} % </p>
        <br>
        @if($analysis_result->addictionLevel->code != 'P-001')
        <p> SOLUSI: <b>{{ $analysis_result->addictionLevel->solution ?? 'TIDAK ADA SOLUSI' }}</b> </p>
        @endif
    </div>

</body>

</html>