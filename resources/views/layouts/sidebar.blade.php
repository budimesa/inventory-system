<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
            <i class="fas fa-chart-bar"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Inventory System</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item @if(Request::is('incoming_items') || Request::is('outgoing_items')) active @endif">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
            aria-controls="collapsePages">
            <i class="fas fa-exchange-alt"></i>
            <span>Transaksi</span>
        </a>
        <div id="collapsePages" class="collapse @if(Request::is('incoming_items') || Request::is('outgoing_items')) show @endif" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Jenis Transaksi :</h6>
                <a class="collapse-item @if(Request::is('incoming_items')) active @endif" href="{{ url('/incoming_items') }}">Barang Masuk</a>
                <a class="collapse-item @if(Request::is('outgoing_items')) active @endif" href="{{ url('/outgoing_items') }}">Barang Keluar</a>                
            </div>
        </div>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item @if(Request::is('suppliers')) active @endif">
        <a class="nav-link" href="{{ url('/suppliers') }}">
            <i class="fas fa-briefcase"></i>
            <span>Supplier</span></a>
    </li>

    <!-- Nav Item - Items -->
    <li class="nav-item @if(Request::is('items')) active @endif">
        <a class="nav-link" href="{{ url('/items') }}">
            <i class="fas fa-cube"></i>
            <span>Barang</span></a>
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
                <a class="collapse-item @if(Request::is('incoming-reporting')) active @endif" href="{{ url('/incoming-reporting') }}">Laporan Barang Masuk</a>
                <a class="collapse-item @if(Request::is('outgoing-reporting')) active @endif" href="{{ url('/outgoing-reporting') }}">Laporan Barang Keluar</a>                
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
