@extends('layouts.app')

@section('content')
<style>
    .dashboard-icon {
        font-size: xxx-large;
        color:white;
    }
    .dashboard-card-body {
        color: white;
    }
    .items-card {
        background:crimson;
    }
    .problematic-items-card {
        background:yellowgreen;
    }
    .asset-loans-card {
        background:royalblue;
    }
</style>
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
                            <h1 class="card-title">100</h1>
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
                            <h1 class="card-title">5</h1>
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
                            <h1 class="card-title">12</h1>
                            <p class="card-text">Barang Dipinjam</p>
                        </div>
                    </div>
                    <div class="col-md-5 d-flex align-items-center justify-content-center">
                        <i class="dashboard-icon fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
