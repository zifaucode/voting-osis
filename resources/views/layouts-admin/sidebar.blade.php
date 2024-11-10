<!-- Main Sidebar Container -->

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/admin" class="brand-link">
        <span>VOTING-OSIS</span>
        <span class="brand-text font-weight-light" style="color: transparent;">Admin</span>

    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('gambar/logo-osis.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="/admin" class="d-block">{{auth()->user()->name ?? 'ADMIN'}}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Simple Link
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li> -->
                @auth
                @if(auth()->user()->role == 1)
                <li class="nav-item">
                    <a href="/admin" class="nav-link">
                        <i class="nav-icon 	fa fa-th-large"></i>
                        <p>
                            Dashboard

                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/admin-list" class="nav-link">
                        <i class="nav-icon fa fa-user-circle"></i>
                        <p>
                            Data Admin

                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/user" class="nav-link">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>
                            Data User

                        </p>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-user-md"></i>
                        <p>
                            Data Penyakit

                        </p>
                    </a>
                </li> -->
                <li class="nav-item">
                    <a href="/admin/osis-candidate" class="nav-link">
                        <i class="nav-icon fa fa-clipboard"></i>
                        <p>
                            Data Kandidat
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/admin/web-setting" class="nav-link">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            Setting
                        </p>
                    </a>
                </li>



                <li class="nav-item">
                    <a href="/admin/logout" class="nav-link">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>Keluar</p>
                    </a>
                </li>
                @endif

                @endauth




            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>