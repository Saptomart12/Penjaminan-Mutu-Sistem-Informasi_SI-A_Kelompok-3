{{-- Sidebar --}}
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    {{-- Brand Sidebar (Link ke Dashboard) --}}
    {{-- Menggunakan route name 'dashboard' --}}
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-graduation-cap"></i>
        </div>
        {{-- Nama Aplikasi --}}
        <div class="sidebar-brand-text mx-3">APIPANAS</div>
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

</ul>
{{-- End of Sidebar --}}