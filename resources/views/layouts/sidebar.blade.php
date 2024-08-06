<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/home') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-chart-bar"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Inventory System</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <li class="nav-item active" @if(Request::is('home')) active @endif">
        <a class="nav-link" href="{{ url('/home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider">
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item @if(Request::is('asset_loans') || Request::is('problematic-items')) active @endif">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
            aria-controls="collapsePages">
            <i class="fas fa-exchange-alt"></i>
            <span>Transaksi</span>
        </a>
        <div id="collapsePages" class="collapse @if(Request::is('asset_loans') || Request::is('problematic-items')) show @endif" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Jenis Transaksi :</h6>
                <a class="collapse-item @if(Request::is('asset_loans')) active @endif" href="{{ url('/asset_loans') }}">Peminjaman Barang</a>                
                <a class="collapse-item @if(Request::is('problematic-items')) active @endif" href="{{ url('/problematic-items') }}">Barang Bermasalah</a>
            </div>
        </div>
    </li>

    <li class="nav-item @if(Request::is('employees') || Request::is('master-items')) active @endif">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages3" aria-expanded="true"
            aria-controls="collapsePages3">
            <i class="fas fa-list"></i>
            <span>Master</span>
        </a>
        <div id="collapsePages3" class="collapse @if(Request::is('employees') || Request::is('master-items')) show @endif" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Jenis Master :</h6>
                <a class="collapse-item @if(Request::is('employees')) active @endif" href="{{ url('/employees') }}">Master Karyawan</a>
                <a class="collapse-item @if(Request::is('users')) active @endif" href="{{ url('/users') }}">Master User</a>
                <a class="collapse-item @if(Request::is('master-items')) active @endif" href="{{ url('/master-items') }}">Master Barang</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Reporting -->
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages2" aria-expanded="true"
            aria-controls="collapsePages2">
            <i class="fas fa-chart-line"></i>
            <span>Laporan Berkala</span>
        </a>
        <div id="collapsePages2" class="collapse @if(Request::is('item-reporting') || Request::is('incoming-reporting') || Request::is('outgoing-reporting')) show @endif" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Jenis Laporan :</h6>
                <a class="collapse-item @if(Request::is('item-reporting')) active @endif" href="{{ url('/item-reporting') }}">Laporan Barang</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
