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
                                                <!-- Baris 1: Nomor Urut & Slogan -->
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputSequenceNumber"><i class="fas fa-sort-numeric-down text-primary mr-1"></i> NOMOR URUT</label>
                                                            <input type="number" class="form-control" id="inputSequenceNumber" v-model="sequence_number" placeholder="Contoh: 1" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label for="inputSlogan"><i class="fas fa-bullhorn text-warning mr-1"></i> JARGON / SLOGAN PASLON</label>
                                                            <input type="text" class="form-control" id="inputSlogan" v-model="slogan" placeholder="Contoh: Bergerak Bersama, Berkarya Nyata untuk Sekolah Berprestasi">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Baris 2: Calon Ketua & Calon Wakil Ketua -->
                                                <div class="row mt-2">
                                                    <div class="col-md-6">
                                                        <div class="card card-outline card-success shadow-sm">
                                                            <div class="card-header py-2">
                                                                <h5 class="card-title text-success font-weight-bold mb-0">
                                                                    <i class="fas fa-user-tie mr-1"></i> CALON KETUA OSIS
                                                                </h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="form-group">
                                                                    <label for="inputName">Nama Lengkap Ketua</label>
                                                                    <input type="text" class="form-control" id="inputName" v-model="name" placeholder="Masukkan nama calon ketua" required>
                                                                </div>
                                                                <div class="form-group mb-0">
                                                                    <label for="Inputclass">Kelas Calon Ketua</label>
                                                                    <input type="text" class="form-control" id="Inputclass" v-model="class_candidate" placeholder="Contoh: XI PPLG 1" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="card card-outline card-info shadow-sm">
                                                            <div class="card-header py-2">
                                                                <h5 class="card-title text-info font-weight-bold mb-0">
                                                                    <i class="fas fa-user-friends mr-1"></i> CALON WAKIL KETUA OSIS
                                                                </h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="form-group">
                                                                    <label for="inputViceName">Nama Lengkap Wakil Ketua</label>
                                                                    <input type="text" class="form-control" id="inputViceName" v-model="vice_name" placeholder="Masukkan nama calon wakil ketua" required>
                                                                </div>
                                                                <div class="form-group mb-0">
                                                                    <label for="inputViceClass">Kelas Calon Wakil Ketua</label>
                                                                    <input type="text" class="form-control" id="inputViceClass" v-model="vice_class" placeholder="Contoh: X DKV 2" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Baris 3: Visi & Misi -->
                                                <div class="row mt-2">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inputVisi"><i class="fas fa-eye text-primary mr-1"></i> VISI</label>
                                                            <textarea v-model="visi" class="form-control" id="inputVisi" rows="4" placeholder="Tuliskan visi pasangan calon..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inputMisi"><i class="fas fa-bullseye text-danger mr-1"></i> MISI</label>
                                                            <textarea v-model="misi" class="form-control" id="inputMisi" rows="4" placeholder="Tuliskan butir-butir misi pasangan calon..."></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Baris 4: Program Kerja Unggulan -->
                                                <div class="form-group">
                                                    <label for="inputWorkProgram"><i class="fas fa-tasks text-success mr-1"></i> PROGRAM KERJA UNGGULAN</label>
                                                    <textarea v-model="work_program" class="form-control" id="inputWorkProgram" rows="4" placeholder="Tuliskan uraian atau daftar program kerja unggulan..."></textarea>
                                                </div>

                                                <!-- Baris 5: File Foto -->
                                                <div class="form-group mb-0">
                                                    <label for="exampleInputFile"><i class="fas fa-camera text-secondary mr-1"></i> FOTO PASANGAN CALON</label>
                                                    @if($osis_candidate->image)
                                                        <div class="mb-2">
                                                            <img src="/file/image/{{ $osis_candidate->image }}" alt="Foto Paslon" class="img-thumbnail" style="max-height: 120px; border-radius: 10px;">
                                                            <small class="text-muted d-block mt-1">Foto saat ini (kosongkan jika tidak ingin mengubah foto)</small>
                                                        </div>
                                                    @endif
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" ref="image_candidate" class="custom-file-input" id="exampleInputFile" accept=".jpeg, .png, .jpg" v-on:change="handleFileUpload">
                                                            <label class="custom-file-label" for="exampleInputFile">@{{ image_candidate ? image_candidate.name : 'Pilih file baru untuk mengganti foto' }}</label>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-muted mt-1">Disarankan menggunakan foto resmi berdua (Ketua & Wakil) dengan rasio proporsional.</small>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <a href="/admin/osis-candidate" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary" :class="loading && 'spinner spinner-white spinner-right'" :disabled="loading">
                                    <i class="fas fa-save mr-1"></i> Perbarui Data Kandidat
                                </button>
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
            sequence_number: '{{ $osis_candidate->sequence_number }}',
            slogan: <?php echo Illuminate\Support\Js::from($osis_candidate->slogan) ?> || '',
            name: <?php echo Illuminate\Support\Js::from($osis_candidate->name) ?> || '',
            class_candidate: <?php echo Illuminate\Support\Js::from($osis_candidate->class) ?> || '',
            vice_name: <?php echo Illuminate\Support\Js::from($osis_candidate->vice_name) ?> || '',
            vice_class: <?php echo Illuminate\Support\Js::from($osis_candidate->vice_class) ?> || '',
            visi: <?php echo Illuminate\Support\Js::from($osis_candidate->visi) ?> || '',
            misi: <?php echo Illuminate\Support\Js::from($osis_candidate->misi) ?> || '',
            work_program: <?php echo Illuminate\Support\Js::from($osis_candidate->work_program) ?> || '',
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
                    Swal.fire({
                        icon: 'warning',
                        title: 'Format File Tidak Sesuai',
                        text: 'Silakan pilih gambar dengan format JPG, JPEG, atau PNG.',
                    });
                    this.image_candidate = '';
                    this.$refs.image_candidate.value = '';
                    return;
                }
            },
            isValidFileType(image_candidate) {
                const allowedExtensions = ['jpeg', 'png', 'jpg'];
                const ext = image_candidate.name.split('.').pop().toLowerCase();
                return allowedExtensions.includes(ext);
            },
            submitForm: function() {
                if (!this.name || !this.class_candidate) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        text: 'Harap isi nama dan kelas calon ketua!',
                    });
                    return;
                }
                this.sendData();
            },
            sendData: function() {
                let vm = this;
                vm.loading = true;
                let formData = new FormData();
                if (vm.image_candidate) {
                    formData.append('image', vm.image_candidate);
                }
                formData.append('sequence_number', vm.sequence_number || '');
                formData.append('slogan', vm.slogan || '');
                formData.append('name', vm.name || '');
                formData.append('class', vm.class_candidate || '');
                formData.append('vice_name', vm.vice_name || '');
                formData.append('vice_class', vm.vice_class || '');
                formData.append('visi', vm.visi || '');
                formData.append('misi', vm.misi || '');
                formData.append('work_program', vm.work_program || '');

                axios.post('/admin/osis-candidate/{{ $osis_candidate->id }}', formData)
                    .then(function(response) {
                        vm.loading = false;
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data Pasangan Kandidat OSIS berhasil diperbarui.',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/admin/osis-candidate';
                            }
                        });
                    })
                    .catch(function(error) {
                        vm.loading = false;
                        console.error(error);
                        let errMsg = error.response && error.response.data && error.response.data.message 
                            ? error.response.data.message 
                            : 'Terjadi kesalahan sistem saat memperbarui data.';
                        Swal.fire({
                            title: 'Gagal Memperbarui',
                            icon: 'error',
                            text: errMsg,
                        });
                    });
            },
        }
    })
</script>

@endsection