<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    {{-- Brand Sidebar (Link ke Dashboard) --}}
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="sidebar-brand-text mx-3">APIPANAS</div>
    </a>
    
    <hr class="sidebar-divider my-0">

    {{-- Nav Item - Dashboard --}}
    <li class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    
    <hr class="sidebar-divider">

    {{-- Heading --}}
    <div class="sidebar-heading">
        Menu Utama
    </div>

    {{-- Nav Item - Kelola Semester (SUDAH AKTIF) --}}
    {{-- Ini akan menyala (active) jika kita sedang di halaman index, create, atau edit semester --}}
    <li class="nav-item {{ Request::routeIs('semester.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('semester.index') }}">
            <i class="fas fa-layer-group"></i>
            <span>Kelola Semester</span>
        </a>
    </li>

    {{-- 
        PENTING: 
        Menu Riwayat juga dikomentari dulu.
    --}}
    {{-- 
    <li class="nav-item {{ Request::routeIs('riwayat') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('riwayat') }}">
            <i class="fas fa-fw fa-history"></i>
            <span>Riwayat Semester</span>
        </a>
    </li>
    --}}

    <hr class="sidebar-divider d-none d-md-block">

    {{-- Sidebar Toggler (Sidebar) --}}
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>