{{-- Sidebar --}}
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    {{-- Brand Sidebar (Link ke Dashboard) --}}
    {{-- Menggunakan route name 'dashboard' --}}
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-graduation-cap"></i>
        </div>
        {{-- Nama Aplikasi --}}
        <div class="sidebar-brand-text mx-3">SIM_KAP</div>
    </a>
    <hr class="sidebar-divider my-0">

    {{-- Nav Item - Dashboard --}}
    {{-- Kondisi active diperbaiki untuk hanya '/' atau '/dashboard' --}}
    <li class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider">

    <div class="sidebar-heading"> Menu Utama </div>

    {{-- Nav Item - Mata Kuliah --}}
    {{-- Menggunakan route name 'mata-kuliah.index' dari Route::resource --}}
    {{-- Kondisi active diubah ke Request::routeIs('mata-kuliah.*') --}}
    <li class="nav-item {{ Request::routeIs('mata-kuliah.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('mata-kuliah.index') }}">
            <i class="fas fa-fw fa-book"></i>
            <span>Mata Kuliah</span>
        </a>
    </li>

    {{-- Nav Item - Semester --}}
            <li class="nav-item {{ Request::routeIs('semester.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('semester.index') }}">
                    <i class="fas fa-layer-group"></i> {{-- Ganti ikon jika perlu --}}
                    <span>Kelola Semester</span>
                </a>
            </li>

            {{-- Nav Item - Riwayat Semester --}}
            <li class="nav-item {{ Request::routeIs('riwayat') ? 'active' : '' }}">

    {{-- Nav Item - Riwayat Semester --}}
     {{-- Kondisi active diubah ke Request::routeIs('riwayat') --}}
    <li class="nav-item {{ Request::routeIs('riwayat') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('riwayat') }}">
            <i class="fas fa-fw fa-history"></i>
            <span>Riwayat Semester</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
{{-- End of Sidebar --}}