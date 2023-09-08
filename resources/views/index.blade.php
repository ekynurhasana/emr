@extends('layout')

@section('content')
    <h5 class="mb-2 mt-2">Pendaftaran Perawatan Hari Ini</h5>
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $perawatan_baru_today != 0 ? $perawatan_baru_today : '-' }}</h3>

                    <p>Pendaftaran Baru</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $perawatan_antre_today != 0 ? $perawatan_antre_today : '-' }}</h3>

                    <p>Perawatan Antre</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $perawatan_selesai_today != 0 ? $perawatan_selesai_today : '-' }}</h3>

                    <p>Perawatan Selesai</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-lightblue">
                <div class="inner">
                    <h3>{{ $perawatan_today != 0 ? $perawatan_today : '-' }}</h3>
                    <p>Total Perawatan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-md"></i>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <h5 class="mb-2 mt-2">Total Perawatan</h5>
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{ $perawatan_this_month != 0 ? $perawatan_this_month : '-' }}</h3>
                    <p>Bulan Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-md"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-teal">
                <div class="inner">
                    <h3>{{ $perawatan_this_year != 0 ? $perawatan_this_year : '-' }}</h3>
                    <p>Tahun Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-md"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-maroon">
                <div class="inner">
                    <h3>{{ $perawatan != 0 ? $perawatan : '-' }}</h3>
                    <p>Total Perawatan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-md"></i>
                </div>
            </div>
        </div>
    </div>
@endsection
