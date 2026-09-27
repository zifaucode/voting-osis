@extends('layouts-admin.app')
@section('title')
Data Pemilih (Siswa & Guru)
@endsection

@section('head')
<style>
    .stat-card {
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    }
    .nav-pills-custom .nav-link {
        font-weight: 600;
        border-radius: 8px;
        padding: 0.55rem 1.25rem;
        color: #4b5563;
        background: #f3f4f6;
        margin-right: 0.5rem;
        transition: all 0.2s ease;
    }
    .nav-pills-custom .nav-link.active {
        background: #059669;
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(5, 150, 105, 0.25);
    }
    .table thead th {
        background-color: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        color: #334155;
        font-weight: 700;
        font-size: 0.875rem;
        vertical-align: middle;
    }
    .table td {
        vertical-align: middle;
        font-size: 0.875rem;
    }
    .badge-code {
        font-family: monospace;
        letter-spacing: 0.5px;
        padding: 0.35rem 0.6rem;
        border-radius: 6px;
        background: #f1f5f9;
        color: #0f172a;
        border: 1px solid #cbd5e1;
        font-weight: 700;
    }
</style>
@endsection

@section('content')
<div id="app" v-cloak>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold" style="color: #1e293b;">Data User (Pemilih)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Pemilih</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <!-- Summary KPI Cards -->
            <div class="row mb-3">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box stat-card bg-white">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-muted">Total Seluruh Pemilih</span>
                            <span class="info-box-number font-weight-bold" style="font-size: 1.4rem;">@{{ users.length }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box stat-card bg-white">
                        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user-graduate"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-muted">Pemilih Siswa</span>
                            <span class="info-box-number font-weight-bold" style="font-size: 1.4rem;">@{{ students.length }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box stat-card bg-white">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard-teacher"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-muted">Pemilih Guru & Karyawan</span>
                            <span class="info-box-number font-weight-bold" style="font-size: 1.4rem;">@{{ teachers.length }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box stat-card bg-white">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-vote-yea"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-muted">Sudah / Belum Memilih</span>
                            <span class="info-box-number" style="font-size: 1.1rem;">
                                <span class="text-success font-weight-bold">@{{ votedCount }}</span> / <span class="text-muted">@{{ unvotedCount }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload File Callout Card -->
            <div class="card card-outline card-success shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold" style="color: #065f46;">
                        <i class="fas fa-file-excel text-success mr-1"></i> Impor Data Pemilih (Excel)
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center mb-3" style="gap: 0.6rem;">
                        <a href="/file/format/EXEL-IMPORT.xlsx" download class="btn btn-sm btn-success font-weight-bold shadow-sm">
                            <i class="fas fa-file-download mr-1"></i> Download Format Template Excel Kosong
                        </a>
                        <span class="text-muted small ml-1">(Format kolom: <code>NO</code>, <code>USERNAME</code>, <code>NAMA</code>, <code>ROLE</code>, <code>KODE AKSES</code>)</span>
                    </div>

                    <p class="text-muted small mb-2">Upload file Excel (.xlsx / .xls / .csv) berisi daftar pemilih (siswa maupun guru/karyawan). Sistem akan membaca Username, Nama, Role/Kelas, dan Kode Akses secara otomatis.</p>
                    
                    <div class="row align-items-center">
                        <div class="col-md-6 col-12 mb-2 mb-md-0">
                            <div class="custom-file">
                                <input type="file" ref="file" class="custom-file-input" id="customFile" accept=".xls, .xlsx, .csv" v-on:change="handleFileUpload">
                                <label class="custom-file-label" for="customFile">@{{ file ? file.name : 'Pilih file Excel...' }}</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <button type="button" class="btn btn-primary btn-block font-weight-bold" @click.prevent="submitForm" :disabled="loading || !file">
                                <span v-if="loading" class="spinner-border spinner-border-sm mr-1"></span>
                                <i v-else class="fas fa-cloud-upload-alt mr-1"></i> Upload & Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation & Table Card -->
            <div class="card card-tabs shadow-sm">
                <div class="card-header p-0 pt-1 border-bottom-0 bg-white">
                    <ul class="nav nav-tabs nav-pills-custom p-2" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" :class="{ active: currentTab === 'guru' }" href="javascript:void(0)" @click="setTab('guru')">
                                <i class="fas fa-chalkboard-teacher mr-1"></i> Pemilih Guru & Karyawan
                                <span class="badge badge-light ml-1">@{{ teachers.length }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :class="{ active: currentTab === 'siswa' }" href="javascript:void(0)" @click="setTab('siswa')">
                                <i class="fas fa-user-graduate mr-1"></i> Pemilih Siswa
                                <span class="badge badge-light ml-1">@{{ students.length }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :class="{ active: currentTab === 'semua' }" href="javascript:void(0)" @click="setTab('semua')">
                                <i class="fas fa-users mr-1"></i> Semua Pemilih
                                <span class="badge badge-light ml-1">@{{ users.length }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <!-- Controls: Search & Delete Selected -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3" style="gap: 0.75rem;">
                        <div class="d-flex align-items-center" style="gap: 0.5rem; max-width: 400px; width: 100%;">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                </div>
                                <input type="text" 
                                       class="form-control" 
                                       v-model="searchQuery" 
                                       placeholder="Cari nama, username, atau kelas/unit..."
                                       @input="currentPage = 1">
                                <div class="input-group-append" v-if="searchQuery">
                                    <button class="btn btn-outline-secondary" type="button" @click="searchQuery = ''; currentPage = 1">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center flex-wrap" style="gap: 0.6rem;">
                            <button class="btn btn-danger btn-sm font-weight-bold shadow-sm" 
                                    @click.prevent="deleteSelectedRecord" 
                                    v-if="selectedInTab.length > 0">
                                <i class="fas fa-trash-alt mr-1"></i> Hapus @{{ selectedInTab.length }} yang diceklis
                            </button>

                            <button class="btn btn-outline-danger btn-sm font-weight-bold" 
                                    @click.prevent="deleteAllVoters" 
                                    v-if="users.length > 0"
                                    title="Hapus seluruh data pemilih (Siswa & Guru)">
                                <i class="fas fa-trash mr-1"></i> Kosongkan Semua Pemilih
                            </button>

                            <div class="d-flex align-items-center">
                                <span class="text-muted small mr-2 d-none d-sm-inline">Tampilkan:</span>
                                <select class="custom-select custom-select-sm" style="width: 85px;" v-model.number="perPage" @change="currentPage = 1">
                                    <option :value="25">25</option>
                                    <option :value="50">50</option>
                                    <option :value="100">100</option>
                                    <option :value="999999">Semua</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Main Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 40px;">
                                        <input type="checkbox" @change="toggleSelectAllInTab" :checked="isAllSelectedInTab">
                                    </th>
                                    <th class="text-center" style="width: 50px;">No</th>
                                    <th style="width: 140px;">Username</th>
                                    <th>Nama Lengkap</th>
                                    <th style="width: 160px;">Role / Kelas</th>
                                    <th style="width: 140px;" class="text-center">Kode Akses</th>
                                    <th style="width: 130px;" class="text-center">Status Suara</th>
                                    <th class="text-center" style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, idx) in paginatedList" :key="item.id">
                                    <td class="text-center">
                                        <input type="checkbox" v-model="userIds" :value="item.id">
                                    </td>
                                    <td class="text-center text-muted">@{{ (currentPage - 1) * (perPage === 999999 ? filteredList.length : perPage) + idx + 1 }}</td>
                                    <td>
                                        <span class="badge" :class="item.is_teacher ? 'badge-success' : 'badge-primary'" style="font-size: 0.85rem; padding: 0.35rem 0.6rem;">
                                            <i :class="item.is_teacher ? 'fas fa-chalkboard-teacher' : 'fas fa-id-badge'" class="mr-1"></i>
                                            @{{ item.nisn || '-' }}
                                        </span>
                                    </td>
                                    <td class="font-weight-bold" style="color: #1e293b;">@{{ item.name }}</td>
                                    <td>
                                        <span class="badge badge-light border" style="font-size: 0.8rem; padding: 0.3rem 0.5rem;">
                                            @{{ item.class || '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-code">@{{ item.code_access || '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success px-2 py-1" v-if="item.vote_status == 1">
                                            <i class="fas fa-check-circle mr-1"></i> Sudah Memilih
                                        </span>
                                        <span class="badge badge-warning px-2 py-1 text-dark" v-else>
                                            <i class="fas fa-clock mr-1"></i> Belum Memilih
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus Pemilih" @click.prevent="deleteRecord(item.id)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="paginatedList.length === 0">
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-info-circle fa-2x mb-2 d-block text-secondary"></i>
                                        <span v-if="searchQuery">Tidak ada pemilih yang cocok dengan pencarian "<strong>@{{ searchQuery }}</strong>"</span>
                                        <span v-else-if="currentTab === 'guru'">Belum ada data pemilih Guru & Karyawan. Silakan upload file Excel di atas.</span>
                                        <span v-else-if="currentTab === 'siswa'">Belum ada data pemilih Siswa. Silakan upload file Excel di atas.</span>
                                        <span v-else>Belum ada data pemilih yang terdaftar di sistem.</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 pt-2 border-top" v-if="filteredList.length > 0">
                        <div class="text-muted small mb-2 mb-sm-0">
                            Menampilkan <strong>@{{ startIndex + 1 }}</strong> sampai <strong>@{{ endIndex }}</strong> dari <strong>@{{ filteredList.length }}</strong> pemilih
                            <span v-if="filteredList.length !== currentTabList.length" class="text-primary">(difilter dari @{{ currentTabList.length }})</span>
                        </div>

                        <ul class="pagination pagination-sm m-0" v-if="totalPages > 1">
                            <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                <a class="page-link" href="javascript:void(0)" @click="currentPage > 1 && (currentPage--)">&laquo; Prev</a>
                            </li>
                            <li class="page-item" 
                                v-for="page in visiblePages" 
                                :key="page" 
                                :class="{ active: currentPage === page }">
                                <a class="page-link" href="javascript:void(0)" @click="currentPage = page">@{{ page }}</a>
                            </li>
                            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                <a class="page-link" href="javascript:void(0)" @click="currentPage < totalPages && (currentPage++)">Next &raquo;</a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('pagescript')
<script>
    const users = <?php echo Illuminate\Support\Js::from($user) ?>;

    let app = new Vue({
        el: '#app',
        data: {
            users: users || [],
            file: null,
            userIds: [],
            loading: false,
            currentTab: 'guru', // default ke guru agar langsung terlihat
            searchQuery: '',
            perPage: 25,
            currentPage: 1,
        },
        computed: {
            teachers() {
                return this.users.filter(u => Boolean(u.is_teacher));
            },
            students() {
                return this.users.filter(u => !Boolean(u.is_teacher));
            },
            votedCount() {
                return this.users.filter(u => u.vote_status == 1).length;
            },
            unvotedCount() {
                return this.users.filter(u => u.vote_status != 1).length;
            },
            currentTabList() {
                if (this.currentTab === 'guru') return this.teachers;
                if (this.currentTab === 'siswa') return this.students;
                return this.users;
            },
            filteredList() {
                const query = (this.searchQuery || '').trim().toLowerCase();
                if (!query) return this.currentTabList;
                return this.currentTabList.filter(item => {
                    const name = (item.name || '').toLowerCase();
                    const nisn = (item.nisn || '').toLowerCase();
                    const cls = (item.class || '').toLowerCase();
                    return name.includes(query) || nisn.includes(query) || cls.includes(query);
                });
            },
            totalPages() {
                if (this.perPage === 999999) return 1;
                return Math.max(1, Math.ceil(this.filteredList.length / this.perPage));
            },
            startIndex() {
                return (this.currentPage - 1) * this.perPage;
            },
            endIndex() {
                if (this.perPage === 999999) return this.filteredList.length;
                return Math.min(this.startIndex + this.perPage, this.filteredList.length);
            },
            paginatedList() {
                if (this.perPage === 999999) return this.filteredList;
                return this.filteredList.slice(this.startIndex, this.endIndex);
            },
            visiblePages() {
                const total = this.totalPages;
                const current = this.currentPage;
                const delta = 2;
                let range = [];
                for (let i = Math.max(2, current - delta); i <= Math.min(total - 1, current + delta); i++) {
                    range.push(i);
                }
                if (current - delta > 2) range.unshift('...');
                if (current + delta < total - 1) range.push('...');
                range.unshift(1);
                if (total > 1) range.push(total);
                return range.filter(p => p !== '...' || true);
            },
            selectedInTab() {
                const currentIds = new Set(this.currentTabList.map(u => u.id));
                return this.userIds.filter(id => currentIds.has(id));
            },
            isAllSelectedInTab() {
                if (this.paginatedList.length === 0) return false;
                const pageIds = this.paginatedList.map(u => u.id);
                return pageIds.every(id => this.userIds.includes(id));
            }
        },
        methods: {
            setTab(tab) {
                this.currentTab = tab;
                this.searchQuery = '';
                this.currentPage = 1;
            },
            toggleSelectAllInTab() {
                const pageIds = this.paginatedList.map(u => u.id);
                const allSelected = pageIds.every(id => this.userIds.includes(id));
                if (allSelected) {
                    this.userIds = this.userIds.filter(id => !pageIds.includes(id));
                } else {
                    const newIds = new Set([...this.userIds, ...pageIds]);
                    this.userIds = Array.from(newIds);
                }
            },
            handleFileUpload() {
                this.file = this.$refs.file.files[0];
            },
            submitForm() {
                if (!this.file) {
                    Swal.fire('Perhatian', 'Silakan pilih file Excel terlebih dahulu.', 'warning');
                    return;
                }
                this.loading = true;
                let formData = new FormData();
                formData.append('file', this.file);

                let vm = this;
                axios.post('/admin/user/import-excel', formData)
                    .then(function(response) {
                        vm.loading = false;
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data Pemilih berhasil diimpor ke sistem.',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/admin/user';
                            }
                        });
                    })
                    .catch(function(error) {
                        vm.loading = false;
                        console.error(error);
                        Swal.fire({
                            title: 'Gagal Mengimpor',
                            icon: 'error',
                            text: (error.response && error.response.data && error.response.data.message) ? error.response.data.message : 'Terjadi kesalahan sistem saat membaca file Excel.',
                        });
                    });
            },
            deleteRecord(id) {
                Swal.fire({
                    title: 'Hapus Pemilih Ini?',
                    text: 'Data pemilih dan riwayat suara terkait akan dihapus.',
                    icon: 'warning',
                    reverseButtons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios.delete('/admin/user/' + id)
                            .then(response => response.data)
                            .catch(error => {
                                Swal.showValidationMessage('Gagal menghapus: ' + (error.response?.data?.message || 'Error'));
                            });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Dihapus',
                            text: 'Data pemilih telah berhasil dihapus.',
                            timer: 1500,
                            showConfirmButton: false,
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                });
            },
            deleteSelectedRecord() {
                const targetIds = this.selectedInTab;
                if (targetIds.length === 0) return;

                Swal.fire({
                    title: 'Hapus ' + targetIds.length + ' Pemilih?',
                    text: 'Semua pemilih yang diceklis pada tab ini akan dihapus dari sistem.',
                    icon: 'warning',
                    reverseButtons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus Semua',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios.post('/admin/user/selected-delete', { userIds: targetIds })
                            .then(response => response.data)
                            .catch(error => {
                                Swal.showValidationMessage('Gagal menghapus: ' + (error.response?.data?.message || 'Error'));
                            });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Dihapus',
                            text: targetIds.length + ' data pemilih telah dihapus.',
                            timer: 1500,
                            showConfirmButton: false,
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                });
            },
            deleteAllVoters() {
                Swal.fire({
                    title: 'Kosongkan SEMUA Pemilih?',
                    text: 'PERINGATAN: Semua akun pemilih (siswa dan guru/karyawan) beserta data suara terkait akan dihapus secara permanen dari sistem!',
                    icon: 'warning',
                    reverseButtons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Kosongkan Semua!',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios.post('/admin/user/delete-all')
                            .then(response => response.data)
                            .catch(error => {
                                Swal.showValidationMessage('Gagal mengosongkan: ' + (error.response?.data?.message || 'Error'));
                            });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Dikosongkan',
                            text: 'Seluruh data pemilih telah berhasil dihapus.',
                            timer: 1500,
                            showConfirmButton: false,
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                });
            }
        }
    });
</script>
@endsection