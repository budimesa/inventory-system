@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Dashboard</h6>
    </div>
    <div class="card-body">
        <div class="row">
            
          <div class="col-sm-4">
            <div class="card mb-3 items-card">
                <div class="row no-gutters">
                    <div class="col-md-7">
                        <div class="card-body dashboard-card-body">
                            <h1 class="card-title">{{ $total_items }}</h1>
                            <p class="card-text">Jenis Barang</p>
                        </div>
                    </div>
                    <div class="col-md-5 d-flex align-items-center justify-content-center">
                        <i class="dashboard-icon fas fa-box"></i>
                    </div>
                </div>
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="card mb-3 problematic-items-card">
                <div class="row no-gutters">
                    <div class="col-md-7">
                        <div class="card-body dashboard-card-body">
                            <h1 class="card-title">{{ $total_problematic_items }}</h1>
                            <p class="card-text">Barang Bermasalah</p>
                        </div>
                    </div>
                    <div class="col-md-5 d-flex align-items-center justify-content-center">
                        <i class="dashboard-icon fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
          </div>

          <div class="col-sm-4">
            <div class="card mb-3 asset-loans-card">
                <div class="row no-gutters">
                    <div class="col-md-7">
                        <div class="card-body dashboard-card-body">
                            <h1 class="card-title">{{ $total_asset_loans }}</h1>
                            <p class="card-text">Barang Dipinjam</p>
                        </div>
                    </div>
                    <div class="col-md-5 d-flex align-items-center justify-content-center">
                        <i class="dashboard-icon fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>
          </div>

          <div class="col-sm-4">
            <div class="card mb-3 employees-card">
                <div class="row no-gutters">
                    <div class="col-md-7">
                        <div class="card-body dashboard-card-body">
                            <h1 class="card-title">{{ $total_employees }}</h1>
                            <p class="card-text">Total Karyawan </p>
                        </div>
                    </div>
                    <div class="col-md-5 d-flex align-items-center justify-content-center">
                        <i class="dashboard-icon fas fa-users"></i>
                    </div>
                </div>
            </div>
          </div>
    </div>
</div>
@endsection
