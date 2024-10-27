@extends('layouts-admin.app')
@section('title', 'Edit Data Kandidat')




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
                    <h1 class="m-0">Edit Data Kandidat</h1>
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

                                                <div class="form-group">
                                                    <label for="inputSequenceNumber">NOMOR URUT</label>
                                                    <input type="number" class="form-control" id="inputSequenceNumber" v-model="sequence_number" style="width: 5%;">
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputName">NAMA KANDIDAT</label>
                                                    <input type="text" class="form-control" id="inputName" v-model="name" placeholder="Input Nama" style="width: 50%;">
                                                </div>

                                                <div class="form-group">
                                                    <label for="Inputclass">KELAS</label>
                                                    <input type="text" class="form-control" id="Inputclass" v-model="class_candidate" placeholder="Input Kelas" style="width: 50%;">
                                                </div>


                                                <div class="form-group">
                                                    <label for="inputVisi">VISI</label>
                                                    <textarea v-model="visi" class="form-control" id="inputVisi"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputMisi">MISI</label>
                                                    <textarea v-model="misi" class="form-control" id="inputMisi"></textarea>
                                                </div>


                                                <div class="form-group">
                                                    <label for="exampleInputFile">File FOTO</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" ref="image_candidate" class="form-control" accept=".jpeg, .png, .jpg" v-on:change="handleFileUpload">
                                                        </div>

                                                    </div>
                                                </div>


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
            name: '{{ $osis_candidate->name }}',
            class_candidate: '{{ $osis_candidate->class }}',
            sequence_number: '{{ $osis_candidate->sequence_number }}',
            visi: <?php echo Illuminate\Support\Js::from($osis_candidate->visi) ?>,
            misi: <?php echo Illuminate\Support\Js::from($osis_candidate->misi) ?>,
            image_candidate: '',


            loading: false,

        },
        methods: {
            handleFileUpload() {
                this.image_candidate = this.$refs.image_candidate.files[0];
                if (!this.image_candidate) {
                    return;
                }
                if (!this.isValidFileType(this.image_candidate)) {
                    return;
                }
            },
            isValidFileType(image_candidate) {
                const allowedExtensions = ['.jpeg', '.png', '.jpg'];
                return allowedExtensions.includes(image_candidate.name.split('.').pop().toLowerCase());
            },
            submitForm: function() {
                this.sendData();
            },
            sendData: function() {
                let vm = this;
                let data = {
                    image: vm.image_candidate,
                    image_name: vm.image_candidate['name'],

                    name: vm.name,
                    class: vm.class_candidate,
                    sequence_number: vm.sequence_number,
                    visi: vm.visi,
                    misi: vm.misi,
                }
                let formData = new FormData();
                for (var key in data) {
                    formData.append(key, data[key]);
                }
                axios.post('/admin/osis-candidate/{{ $osis_candidate->id }}', formData)
                    .then(function(response) {
                        vm.loading = false;
                        Swal.fire({
                            title: 'Success',
                            text: 'Data Kandidat Osis berhasil diupdate.',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/admin/osis-candidate';
                            }
                        })
                    })
                    .catch(function(error) {
                        vm.loading = false;
                        console.log(error);
                        Swal.fire({
                            title: 'Internal Error',
                            error: true,
                            icon: 'error',
                            text: error.response.data.message,
                        })
                    });
            },
        }
    })
</script>

@endsection