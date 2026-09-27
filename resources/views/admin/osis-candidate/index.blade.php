@extends('layouts-admin.app')
@section('title', 'Data Kandidat')



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
                    <h1 class="m-0">Data Kandidat</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Manage</li>
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
                                <a href="/admin/osis-candidate/create" class="btn btn-primary">Tambah Data Kandidat</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 6%; text-align:center;">No. Urut</th>
                                        <th style="width: 10%; text-align:center;">Foto</th>
                                        <th style="width: 25%;">Pasangan Calon (Ketua & Wakil)</th>
                                        <th style="width: 18%;">Jargon / Slogan</th>
                                        <th style="width: 15%;">Visi & Misi</th>
                                        <th style="width: 16%;">Program Kerja</th>
                                        <th style="width: 10%; text-align:center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="candidate in osisCandidate" :key="candidate.id">
                                        <td class="text-center font-weight-bold align-middle">
                                            <span class="badge badge-primary px-2 py-1" style="font-size: 1rem;">
                                                #@{{ candidate.sequence_number ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <img :src="candidate.image ? '/file/image/' + candidate.image : '/gambar/logo-osis.png'" 
                                                 alt="Foto" 
                                                 style="width: 75px; height: 75px; object-fit: cover; border-radius: 10px; border: 2px solid #e2e8f0;">
                                        </td>
                                        <td class="align-middle">
                                            <div class="mb-2">
                                                <small class="text-success font-weight-bold d-block text-uppercase" style="letter-spacing: 0.5px;">
                                                    <i class="fas fa-user-tie mr-1"></i> Calon Ketua
                                                </small>
                                                <strong>@{{ candidate.name ?? '-' }}</strong>
                                                <span class="badge badge-light border ml-1">@{{ candidate.class ?? '-' }}</span>
                                            </div>
                                            <div>
                                                <small class="text-info font-weight-bold d-block text-uppercase" style="letter-spacing: 0.5px;">
                                                    <i class="fas fa-user-friends mr-1"></i> Calon Wakil
                                                </small>
                                                <strong>@{{ candidate.vice_name ?? '-' }}</strong>
                                                <span class="badge badge-light border ml-1">@{{ candidate.vice_class ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <span v-if="candidate.slogan" class="font-italic text-muted d-block small" style="border-left: 3px solid #f59e0b; padding-left: 8px;">
                                                “@{{ candidate.slogan }}”
                                            </span>
                                            <span v-else class="text-muted small">-</span>
                                        </td>
                                        <td class="align-middle small">
                                            <div class="mb-1">
                                                <strong class="text-primary">Visi:</strong>
                                                <p class="mb-1 text-truncate" style="max-width: 200px;" :title="candidate.visi">@{{ candidate.visi || '-' }}</p>
                                            </div>
                                            <div>
                                                <strong class="text-danger">Misi:</strong>
                                                <p class="mb-0 text-truncate" style="max-width: 200px;" :title="candidate.misi">@{{ candidate.misi || '-' }}</p>
                                            </div>
                                        </td>
                                        <td class="align-middle small">
                                            <p class="mb-0 text-truncate" style="max-width: 220px;" :title="candidate.work_program">
                                                @{{ candidate.work_program || '-' }}
                                            </p>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group">
                                                <a :href="'/admin/osis-candidate/edit/' + candidate.id" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <button type="button" @click.prevent="deleteRecord(candidate.id)" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>

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
<script>
    const osisCandidate = <?php echo Illuminate\Support\Js::from($osis_candidate) ?>;

    let app = new Vue({
        el: '#app',
        data: {
            osisCandidate,

        },
        methods: {
            deleteRecord: function(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "The data will be deleted",
                    icon: 'warning',
                    reverseButtons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios.delete('/admin/osis-candidate/' + id)
                            .then(function(response) {
                                console.log(response.data);
                            })
                            .catch(function(error) {
                                console.log(error.data);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops',
                                    text: 'Something wrong',
                                })
                            });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Data has been deleted',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                    }
                })
            }
        }
    })
</script>
@endsection