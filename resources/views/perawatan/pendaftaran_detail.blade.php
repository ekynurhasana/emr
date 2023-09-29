@extends('layout')
@section('style')
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
        @if(in_array(Session::get('role'), ['super-admin', 'admin']))
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus" data-id="{{$data->id}}" data-nama="{{$data->nama_pasien}}">
                <i class="fas fa-trash"></i>
            </button>
            {{-- cek data status not in ['selesai', 'batal'] --}}
            @if(in_array($data->status, ['baru', 'antri']))
                <button type="button" class="btn btn-danger" id="btn-batal" onclick="batalPasien()">
                    Batal Periksa
                </button>
            @endif
        @endif
        @if(in_array(Session::get('role'), ['super-admin', 'admin']))
            @if($data->status=="baru")
                <button type="button" class="btn btn-warning" id="btn-antri" onclick="antriPasien()">
                    Masukkan ke Antrian
                </button>
            @endif
        @endif
        @if(in_array(Session::get('role'), ['super-admin', 'perawat', 'dokter']))
            @if($data->status=="antri")
                <button type="button" class="btn btn-success" id="btn-periksa" onclick="antriPasien()">
                    Panggil Pasien
                </button>
                <button type="button" class="btn btn-info" id="btn-tambah-biaya" data-toggle="modal" data-target="#modal-tambah-biaya" onclick="tambahBiaya()">
                    Tambah Biaya
                </button>
            @endif
            @if($data->status=="diperiksa")
                <button type="button" class="btn btn-success" id="btn-selesai" onclick="antriPasien()">
                    Selesai Periksa
                </button>
                <button type="button" class="btn btn-info" id="btn-tambah-biaya" data-toggle="modal" data-target="#modal-tambah-biaya" onclick="tambahBiaya()">
                    Tambah Biaya
                </button>
            @endif
        @endif
        @if($data->status=="antri")
            <br/>
            <span style="margin-bottom:10px" class="badge badge-warning">Terakhir dipanggil: {{$last_antrean != null ? $last_antrean->no_antreaan : '-'}}</span>
        @endif
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="status-bar">
            <span class="status-bar-item {{$data->status == 'baru' ? 'selected' : ''}}">Baru</span>
            <span class="status-bar-item {{$data->status == 'antri' ? 'selected' : ''}}">Antre</span>
            <span class="status-bar-item {{$data->status == 'diperiksa' ? 'selected' : ''}}">Diperiksa</span>
            <span class="status-bar-item {{$data->status == 'selesai' ? 'selected' : ''}}">Selesai</span>
            <span class="status-bar-item {{$data->status == 'batal' ? 'selected' : ''}}">Batal</span>
        </div>
    </div>
</div>
<div class="row">
    @if($data->status=="antri")
    <div class="col-sm-12 col-md-6 col-lg-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning elevation-2"><i class="fas fa-list-ol"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Nomor Antrean</span>
                <span class="info-box-number">{{$no_antrian->no_antrian}}</span>
            </div>
        </div>
    </div>
    @endif
    <div class="col-sm-12 col-md-6 col-lg-3">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-2"><i class="fas fa-calendar-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tanggal Pendaftaran</span>
                <span class="info-box-number">{{date('d F Y', strtotime($data->tanggal_pendaftaran))}}</span>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-2"><i class="fas fa-calendar-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">{{in_array($data->status, ['baru', 'antri', 'batal']) ? 'Rencana Diperiksa' : 'Tanggal Diperiksa'}}</span>
                <span class="info-box-number">{{date('Y-m-d', strtotime($data->tgl_periksa)) == date('Y-m-d') ? 'Hari ini' : date('d F Y', strtotime($data->tgl_periksa))}}</span>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-3">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-2"><i class="fas fa-id-card"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pendaftaran</span>
                <span class="info-box-number">{{$data->is_use_asuransi == 1 ? 'Asuransi (' . $asuransi->nama_asuransi . ')' : 'Umum'}}</span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    {{-- <input type="hidden" name="id" value="{{$pasien->id}}"> --}}
    <div class="col-sm-12 col-md-6 col-lg-6">
        @if ($data->tempat_lahir_pasien == null || $data->tanggal_lahir_pasien == null || $data->no_telepon_pasien == null)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Perhatian!</strong> Data pasien belum lengkap, silahkan lengkapi data pasien terlebih dahulu.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="closeAlert()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="card card-warning card-outline">
            <div class="card-header">
                <div style="float: left">
                    <h3 class="card-title">Data Pasien</h3>
                </div>
                @if(in_array(Session::get('role'), ['super-admin', 'admin']))
                    <div style="float: right">
                        <a href="{{url('/data-pasien/detail/'.$data->id_pasien)}}" class="btn btn-warning" id="btn-rm-pasien">
                            Lengkapi Data Pasien
                        </a>
                    </div>
                @endif
            </div>
        @else
        <div class="card card-info card-outline">
            <div class="card-header">
                <div style="float: left">
                    <h3 class="card-title">Data Pasien</h3>
                </div>
                <div style="float: right">
                    <a href="{{url('/data-pasien/detail/'.$data->id_pasien)}}" class="btn btn-info" id="btn-rm-pasien">
                        Detail Pasien
                    </a>
                </div>
            </div>
        @endif
            <div class="card-body">
                <div class="form-group">
                    <label for="nama_pasien">Nama Pasien</label>
                    <input type="text" id="nama_pasien" class="form-control" value="{{$data->nama_pasien}}" readonly name="nama_pasien">
                </div>
                <div class="form-group">
                    <label for="no_ktp">No. Identitas</label>
                    <input type="text" id="no_ktp" class="form-control" value="{{$data->no_ktp_pasien}}" readonly name="no_ktp">
                </div>
                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <input type="text" id="jenis_kelamin" class="form-control" value="{{$data->jenis_kelamin_pasien == 'L' ? 'Laki-laki' : 'Perempuan'}}" readonly name="jenis_kelamin">
                </div>
                <div class="form-group">
                    <label for="tempat_lahir">Tempat Tanggal Lahir</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-building"></i></span>
                        </div>
                        <input type="text" id="tempat_lahir" class="form-control" value="{{$data->tempat_lahir_pasien}}" readonly name="tempat_lahir">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" id="tanggal_lahir" class="form-control" value="{{$data->tanggal_lahir_pasien}}" readonly name="tanggal_lahir">
                    </div>
                </div>
                <div class="form-group">
                    <label for="usia">Usia</label>
                    @php
                        $tanggal_lahir = new DateTime($data->tanggal_lahir_pasien);
                        $sekarang = new DateTime();
                        $diff = $sekarang->diff($tanggal_lahir);
                    @endphp
                    <input type="text" id="usia" class="form-control" value="{{$diff->y}} Tahun {{$diff->m}} Bulan {{$diff->d}} Hari" readonly name="usia">
                </div>
                <div class="form-group">
                    <label for="no_telepon">No. Telepon</label>
                    <input type="text" id="no_telepon" class="form-control" value="{{$data->no_telepon_pasien}}" readonly name="no_telepon">
                </div>
            </div>
        </div>
        @if ($data->status == 'selesai')
            <div class="card card-info card-outline">
                <div class="card-header">
                    <div style="float: left;">
                        <h3 class="card-title">Diagnosa Pemeriksaan</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <textarea id="diagnosa" class="form-control" readonly name="diagnosa">{{$data->diagnosa}}</textarea>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <form method="POST" action="/rawat-jalan/screening" id="form-screening">
            @csrf
            <input type="method" name="_method" value="PUT" id="_method" hidden>
            <input type="hidden" name="id_pendaftaran" value="{{$data->id}}">
            <input type="hidden" name="id_pasien" value="{{$data->id_pasien}}">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <div style="float: left;">
                        <h3 class="card-title">Screening Pasien</h3>
                    </div>
                    @if(in_array(Session::get('role'), ['super-admin', 'perawat', 'dokter']))
                        @if(in_array($data->status, ['antri']))
                            <div style="float: right;">
                                <button type="button" class="btn btn-info" id="btn-edit-screening" onclick="editScreening()">
                                    Edit Screening Pasien
                                </button>
                                <button type="button" class="btn btn-success" id="btn-save-screening" style="display: none" onclick="alertScreening()">
                                    Simpan Screening Pasien
                                </button>
                                <button type="button" class="btn btn-danger" id="btn-batal-screening" style="display: none" onclick="batalScreening()">
                                    Cancel
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="required" for="keluhan">Keluhan</label>
                        <textarea id="keluhan" class="form-control @error('keluhan') is-invalid @enderror" readonly name="keluhan" required>{{$data->keluhan}}</textarea>
                        @error('keluhan')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="riwayat_penyakit">Riwayat Penyakit</label>
                        <textarea id="riwayat_penyakit" class="form-control" readonly name="riwayat_penyakit">{{$data->riwayat_penyakit}}</textarea>
                        @error('riwayat_penyakit')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="riwayat_rawat_inap">Riwayat Rawat Inap</label>
                        <textarea id="riwayat_rawat_inap" class="form-control" readonly name="riwayat_rawat_inap">{{$data->riwayat_rawat_inap}}</textarea>
                        @error('riwayat_rawat_inap')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="riwayat_operasi">Riwayat Operasi</label>
                        <textarea id="riwayat_operasi" class="form-control" readonly name="riwayat_operasi">{{$data->riwayat_operasi}}</textarea>
                        @error('riwayat_operasi')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <label class="required" for="berat_badan">Berat Badan</label>
                            <div class="input-group">
                                <input type="text" id="berat_badan" class="form-control @error('berat_badan') is-invalid @enderror" value="{{$data->berat_badan}}" readonly name="berat_badan" required>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Kg</span>
                                </div>
                                @error('berat_badan')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <label class="required" for="tinggi_badan">Tinggi Badan</label>
                            <div class="input-group">
                                <input type="text" id="tinggi_badan" class="form-control @error('tinggi_badan') is-invalid @enderror" value="{{$data->tinggi_badan}}" readonly name="tinggi_badan" required>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Cm</span>
                                </div>
                                @error('tinggi_badan')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 20px; margin-bottom: 20px">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <label class="required" for="tekanan_darah">Tekanan Darah</label>
                            <div class="input-group">
                                <input type="text" id="tekanan_darah" class="form-control @error('tekanan_darah') is-invalid @enderror" value="{{$data->tekanan_darah}}" readonly name="tekanan_darah" required>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">mmHg</span>
                                </div>
                                @error('tekanan_darah')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <label for="nadi">Nadi</label>
                            <div class="input-group">
                                <input type="text" id="nadi" class="form-control @error('nadi') is-invalid @enderror" value="{{$data->nadi}}" readonly name="nadi">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">x/menit</span>
                                </div>
                                @error('nadi')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="suhu_badan">Suhu Badan</label>
                        <div class="input-group">
                            <input type="text" id="suhu_badan" class="form-control @error('suhu_badan') is-invalid @enderror" value="{{$data->suhu_badan}}" readonly name="suhu_badan">
                            <div class="input-group-prepend">
                                <span class="input-group-text">&#8451;</span>
                            </div>
                        </div>
                        @error('suhu_badan')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="alergi_obat">Alergi Obat / Makanan / lainnya</label>
                        <textarea id="alergi_obat" class="form-control @error('alergi_obat') is-invalid @enderror" readonly name="alergi_obat">{{$data->alergi_obat}}</textarea>
                        @error('alergi_obat')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    {{-- <div class="form-group">
                        <label for="pemeriksaan_fisik_lainnya">Pemeriksaan Fisik Lainnya</label>
                        <textarea id="pemeriksaan_fisik_lainnya" class="form-control @error('pemeriksaan_fisik_lainnya') is-invalid @enderror" readonly name="pemeriksaan_fisik_lainnya">{{$data->pemeriksaan_fisik_lainnya}}</textarea>
                        @error('pemeriksaan_fisik_lainnya')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div> --}}
                    <div class="form-group">
                        <label for="cara_berjalan_pasien">Cara Berjalan Pasien</label>
                        <select class="form-control select2 @error('cara_berjalan_pasien') is-invalid @enderror" id="cara_berjalan_pasien" name="cara_berjalan_pasien" style="width: 100%;" disabled onchange="hasilResikoJatuh()">
                            <option value="">-- Pilih Cara Berjalan Pasien --</option>
                            <option value="tidak_seimbang" {{$data->cara_berjalan_pasien == 'tidak_seimbang' ? 'selected' : ''}}>Tidak Seimbang /  sempoyongan / limbung</option>
                            <option value="menggunakan_alat_bantu" {{$data->cara_berjalan_pasien == 'menggunakan_alat_bantu' ? 'selected' : ''}}>Menggunakan Alat Bantu</option>
                            <option value="keduanya" {{$data->cara_berjalan_pasien == 'keduanya' ? 'selected' : ''}}>Keduanya</option>
                        </select>
                        @error('cara_berjalan_pasien')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="menopang_saat_akan_duduk">Menopang Saat Akan Duduk</label>
                        <select class="form-control select2 @error('menopang_saat_akan_duduk') is-invalid @enderror" id="menopang_saat_akan_duduk" name="menopang_saat_akan_duduk" style="width: 100%;" disabled onchange="hasilResikoJatuh()">
                            <option value="tidak">-- Pilih Menopang Saat Akan Duduk --</option>
                            <option value="ya" {{$data->menopang_saat_akan_duduk == 'ya' ? 'selected' : ''}}>Ya</option>
                            <option value="tidak" {{$data->menopang_saat_akan_duduk == 'tidak' ? 'selected' : ''}}>Tidak</option>
                        </select>
                        @error('menopang_saat_akan_duduk')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="resiko_jatuh">Hasil Resiko Jatuh</label>
                        <select class="form-control select2 @error('resiko_jatuh') is-invalid @enderror" id="resiko_jatuh" name="resiko_jatuh" style="width: 100%;" disabled onchange="hasilResikoJatuh()">
                            <option value="">-- Pilih Hasil Resiko Jatuh --</option>
                            <option value="tidak_beresiko" {{$data->resiko_jatuh == 'tidak_beresiko' ? 'selected' : ''}}>Tidak Beresiko</option>
                            <option value="resiko_rendah" {{$data->resiko_jatuh == 'resiko_rendah' ? 'selected' : ''}}>Resiko Rendah</option>
                            <option value="resiko_tinggi" {{$data->resiko_jatuh == 'resiko_tinggi' ? 'selected' : ''}}>Resiko Tinggi</option>
                        </select>
                        @error('resiko_jatuh')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tindakan_pengamanan_jatuh">Hasil Tindakan Pengamanan Pasien</label>
                        <textarea id="tindakan_pengamanan_jatuh" class="form-control @error('tindakan_pengamanan_jatuh') is-invalid @enderror" readonly name="tindakan_pengamanan_jatuh">{{$data->tindakan_pengamanan_jatuh}}</textarea>
                        @error('tindakan_pengamanan_jatuh')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="perawat_pemeriksa" class="required">Perawat Pemeriksa</label>
                        <input type="text" id="perawat_pemeriksa" class="form-control" value="{{$data->perawat_pemeriksa}}" readonly name="perawat_pemeriksa" required>
                        @error('perawat_pemeriksa')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                    @if(in_array($data->status, ['diperiksa']))
                    <li class="nav-item">
                        <a class="nav-link active" id="emr-tabs-add-rm-tab" data-toggle="pill" href="#emr-tabs-add-rm" role="tab" aria-controls="emr-tabs-add-rm" aria-selected="true">Tambah Pemeriksaan</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link {{$data->status != 'diperiksa' ? 'active' : ''}}" id="emr-tabs-riwayat-tab" data-toggle="pill" href="#emr-tabs-riwayat" role="tab" aria-controls="emr-tabs-riwayat">Riwayat Rekam Medik</a>
                    </li>
                    @if(!in_array($data->status, ['baru']))
                    <li class="nav-item">
                        <a class="nav-link" id="emr-tabs-resep-obat-tab" data-toggle="pill" href="#emr-tabs-resep-obat" role="tab" aria-controls="emr-tabs-resep-obat">Detail Resep Obat</a>
                    </li>
                    @endif
                    {{-- <li class="nav-item">
                        <a class="nav-link" id="emr-tabs-riwayat-tindakan-tab" data-toggle="pill" href="#emr-tabs-riwayat-tindakan" role="tab" aria-controls="emr-tabs-riwayat-tindakan">Riwayat Implementasi Tindakan</a>
                    </li> --}}
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-two-tabContent">
                    @if(in_array($data->status, ['diperiksa']))
                    <div class="tab-pane fade show active" id="emr-tabs-add-rm" role="tabpanel" aria-labelledby="emr-tabs-add-rm-tab">
                        <div>
                            @if(in_array(Session::get('role'), ['super-admin', 'dokter']))
                                <button type="button" class="btn btn-info" id="btn-periksa" onclick="periksa()">
                                    Periksa Pasien
                                </button>
                                <button type="button" class="btn btn-success" id="btn-add-pemeriksaan" style="display: none" onclick="addPemeriksaanCek()">
                                    Simpan Pemeriksaan
                                </button>
                                <button type="button" class="btn btn-danger" id="btn-batal-pemeriksaan" style="display: none" onclick="batalPemeriksaan()">
                                    Cancel
                                </button>
                                <br><br>
                            @endif
                            <form action="/rawat-jalan/periksa" method="post" id="add-pemeriksaan">
                                @csrf
                                <input type="method" name="_method" value="PUT" id="_method" hidden>
                                <input type="hidden" name="id_pendaftaran" value="{{$data->id}}">
                                <input type="hidden" name="id_pasien" value="{{$data->id_pasien}}">
                                <input type="hidden" name="id_dokter_poli" value="{{$data->dokter_poli_id}}">
                                <div class="form-group">
                                    <label class="required" for="tanggal_pemeriksaan">Tanggal Pemeriksaan</label>
                                    <input type="date" id="tanggal_pemeriksaan" class="form-control @error('tanggal_pemeriksaan') is-invalid @enderror" name="tanggal_pemeriksaan" required value="{{date('Y-m-d')}}" readonly>
                                    @error('tanggal_pemeriksaan')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="required" for="keluhan_periksa">Keluhan</label>
                                    <textarea id="keluhan_periksa" class="form-control @error('keluhan_periksa') is-invalid @enderror" name="keluhan_periksa" required readonly>{{$data->keluhan}}</textarea>
                                    @error('keluhan_periksa')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="diagnosa_icd" class="required">Diagnosa ICD</label>
                                    <select class="form-control select2 @error('diagnosa_icd') is-invalid @enderror" id="diagnosa_icd" name="diagnosa_icd" style="width: 100%;" disabled required>
                                        <option value="">-- Pilih Diagnosa ICD --</option>
                                    </select>
                                    @error('diagnosa_icd')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="diagnosa" class="required">Diagnosa</label>
                                    <textarea id="diagnosa" class="form-control @error('diagnosa') is-invalid @enderror" name="diagnosa" readonly required></textarea>
                                    @error('diagnosa')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="required" for="tindakan">Catatan Tindakan</label>
                                    <textarea id="tindakan" class="form-control @error('tindakan') is-invalid @enderror" name="tindakan" required readonly></textarea>
                                    @error('tindakan')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="required" for="resep_obat">Resep Obat</label>
                                    <textarea id="resep_obat" class="form-control @error('resep_obat') is-invalid @enderror" name="resep_obat" required readonly></textarea>
                                    @error('resep_obat')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea id="keterangan" class="form-control @error('resep_obat') is-invalid @enderror" name="keterangan" readonly></textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                    <div class="tab-pane fade {{$data->status != 'diperiksa' ? 'show active' : ''}}" id="emr-tabs-riwayat" role="tabpanel" aria-labelledby="emr-tabs-riwayat-tab">
                        <div>
                            <table id="tabel_riwayat" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 10%">Tanggal Pemeriksaan</th>
                                        <th class="text-center" style="width: 13%">Poli</th>
                                        <th class="text-center" style="width: 13%">Dokter</th>
                                        <th class="text-center" style="width: 18%">Diagnosa</th>
                                        <th class="text-center" style="width: 10%">ICD 10</th>
                                        <th class="text-center" style="width: 18%">Catatan Tindakan</th>
                                        <th class="text-center" style="width: 18%">Resep Obat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($erm as $rm)
                                        <tr>
                                            <td class="text-center">{{date('d M Y', strtotime($rm->tanggal_pemeriksaan))}}</td>
                                            <td class="text-center">{{$rm->nama_poli}}</td>
                                            <td class="text-center">{{$rm->nama_dokter}}</td>
                                            <td class="text-center">{{$rm->diagnosa}}</td>
                                            <td class="text-center">{{$rm->diagnosa_icd}}</td>
                                            <td class="text-center">{{$rm->tindakan}}</td>
                                            <td class="text-center">{{$rm->resep_obat}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if(!in_array($data->status, ['baru']))
                    <div class="tab-pane fade" id="emr-tabs-resep-obat" role="tabpanel" aria-labelledby="emr-tabs-resep-obat-tab">
                        <div>
                            <table id="tabel_riwayat_obat" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 10%">No. Resep</th>
                                        <th class="text-center">Obat</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Satuan</th>
                                        <th class="text-center">Aturan Pakai</th>
                                        <th class="text-center">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resep_line as $ro)
                                        <tr>
                                            <td class="text-center">{{$ro->no_resep}}</td>
                                            <td class="text-center">{{$ro->obat}}</td>
                                            <td class="text-center">{{$ro->qty}}</td>
                                            <td class="text-center">{{$ro->satuan}}</td>
                                            <td class="text-center">{{$ro->aturan_pakai}}</td>
                                            <td class="text-center">{{$ro->keterangan}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    {{-- <div class="tab-pane fade" id="emr-tabs-riwayat-tindakan" role="tabpanel" aria-labelledby="emr-tabs-riwayat-tindakan-tab">
                        <div>
                            <table id="tabel_riwayat_tindakan" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10%">Tanggal</th>
                                        <th style="width: 13%">Poli</th>
                                        <th style="width: 13%">Dokter</th>
                                        <th style="width: 18%">Diagnosa</th>
                                        <th style="width: 18%">ICD 10</th>
                                        <th style="width: 18%">Catatan Tindakan</th>
                                        <th style="width: 23%">Obat</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_line as $rm)
                                        <tr>
                                            <td>{{$rm->tanggal_pemeriksaan}}</td>
                                            <td>{{$rm->nama_poli}}</td>
                                            <td>{{$rm->nama_dokter}}</td>
                                            <td>{{$rm->diagnosa}}</td>
                                            <td>{{$rm->tindakan}}</td>
                                            <td></td>
                                            <td>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-riwayat" data-id="{{$rm->id}}" data-tanggal="{{$rm->tanggal_pemeriksaan}}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-hapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Danger!!!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal_body_delete"></div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                <form id="form_delete_pasien" method="POST" action="/rawat-jalan/pendaftaran/delete">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <input type="method" name="_method" value="DELETE" id="_method" hidden>
                    <button type="submit" class="btn btn-outline-light">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div>
    <div class="modal fade" id="modal-hapus-riwayat" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h4 class="modal-title">Danger!!!</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modal_body_delete"></div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                    <form id="form_delete_pasien" method="POST" action="/data-pasien/delete">
                        @csrf
                        <input type="hidden" name="id_pasien" id="id_pasien">
                        <input type="method" name="_method" value="DELETE" id="_method" hidden>
                        <button type="submit" class="btn btn-outline-light">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="form-antri" method="POST" action="/rawat-jalan/antri">
    @csrf
    <input type="hidden" name="id_pasien" id="id_pasien" value="{{$data->id_pasien}}">
    <input type="hidden" name="id_pendaftaran" id="id_pendaftaran" value="{{$data->id}}">
    <input type="hidden" name="id_poli" id="id_poli" value="{{$data->poli_id}}">
    <input type="hidden" name="id_dokter_poli" id="id_dokter_poli" value="{{$data->dokter_poli_id}}">
    <input type="hidden" name="status" id="status" value="{{$data->status}}">
    <input type="hidden" name="tgl_periksa" id="tgl_periksa" value="{{$data->tgl_periksa}}">
</form>
<form id="form-batal" method="POST" action="/rawat-jalan/batal">
    @csrf
    <input type="hidden" name="id_pendaftaran" id="id_pendaftaran" value="{{$data->id}}">
</form>
<div class="modal fade" id="modal-tambah-biaya" tabindex="-1" role="definition" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form  method="POST" action="/rawat-jalan/tambah-biaya" id="form-tambah-biaya">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Biaya</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group" id="form-biaya">
                            <label class="required" for="biaya">Biaya</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input type="text" id="biaya" class="form-control @error('biaya') is-invalid @enderror" name="biaya" maxlength="13" onkeypress="return hanyaAngka(event)">
                            </div>
                            @error('biaya')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="form-jenis-tagihan">
                            <label class="required" for="jenis_tagihan">Jenis Tagihan</label>
                            <select class="form-control select2 @error('jenis_tagihan') is-invalid @enderror" id="jenis_tagihan" name="jenis_tagihan" style="width: 100%;" required>
                                <option value="">-- Pilih Jenis Tagihan --</option>
                                <option value="perawatan">Perawatan</option>
                                <option value="tindakan">Tindakan</option>
                            </select>
                            @error('jenis_tagihan')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="form-keterangan">
                            <label class="required" for="keterangan">Keterangan Tagihan</label>
                            <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" required></textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <input type="hidden" name="id_pendaftaran" value="{{$data->id}}">
                        <input type="hidden" name="id_pasien" value="{{$data->id_pasien}}">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-success">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('/adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('/adminlte/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('/adminlte/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('/adminlte/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('/adminlte/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#modal-hapus').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var nama = button.data('nama')
            var modal = $(this)
            modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus pendafataran atas nama '+nama+'? Apabila anda menghapus data ini, maka detail data pendaftaran disetiap data lainnya akan hilang!')
            $('#id').val(id)
        });
    });
    function hasilResikoJatuh() {
        var cara_berjalan_pasien = $("#cara_berjalan_pasien").val();
        var menopang_saat_akan_duduk = $("#menopang_saat_akan_duduk").val();
        var resiko_jatuh_html = $("#resiko_jatuh")
        if ((cara_berjalan_pasien == "menggunakan_alat_bantu") && (menopang_saat_akan_duduk == "tidak")) {
            console.log("resiko tinggi");
            $("#resiko_jatuh").val("resiko_rendah");
        } else if ((cara_berjalan_pasien == "menggunakan_alat_bantu") && (menopang_saat_akan_duduk == "ya")) {
            $("#resiko_jatuh").val("resiko_tinggi");
        } else if ((cara_berjalan_pasien == "tidak_seimbang") && (menopang_saat_akan_duduk == "tidak")) {
            $("#resiko_jatuh").val("resiko_rendah");
        } else if ((cara_berjalan_pasien == "tidak_seimbang") && (menopang_saat_akan_duduk == "ya")) {
            $("#resiko_jatuh").val("resiko_tinggi");
        } else if ((cara_berjalan_pasien == "keduanya") && (menopang_saat_akan_duduk == "tidak")) {
            $("#resiko_jatuh").val("resiko_tinggi");
        } else if ((cara_berjalan_pasien == "keduanya") && (menopang_saat_akan_duduk == "ya")) {
            $("#resiko_jatuh").val("resiko_tinggi");
        } else {
            $("#resiko_jatuh").val("");
        }

        var resiko_jatuh = $("#resiko_jatuh").val();
        if (resiko_jatuh == "resiko_rendah") {
            $("#tindakan_pengamanan_jatuh").val("Edukasi pasien dan keluarga tentang resiko jatuh");
        } else if (resiko_jatuh == "resiko_tinggi") {
            $("#tindakan_pengamanan_jatuh").val("1. Edukasi pasien dan keluarga tentang resiko jatuh\n2. Pasang ID Card berwarna kuning (tanda peringatan jatuh)");
        } else {
            $("#tindakan_pengamanan_jatuh").val("");
        }
    }
</script>
<script>
    $(function () {
        $("#tabel_riwayat").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tabel_riwayat_wrapper .col-md-6:eq(0)');
    });
    $(function () {
        $("#tabel_riwayat_obat").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tabel_riwayat_obat_wrapper .col-md-6:eq(0)');
    });
    $('#modal-hapus-riwayat').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var tanggal = button.data('tanggal')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus riwayat medis pasien pada tanggal ' + tanggal + '?')
        $('#id_pasien').val(id)
    });
    $(function(){
        $("#biaya").keyup(function(e){
            $(this).val(formatRupiah($(this).val()));
        });
    });

    function editScreening() {
        $('#alergi_obat').removeAttr('readonly');
        $('#keluhan').removeAttr('readonly');
        $('#riwayat_penyakit').removeAttr('readonly');
        $('#riwayat_rawat_inap').removeAttr('readonly');
        $('#riwayat_operasi').removeAttr('readonly');
        $('#berat_badan').removeAttr('readonly');
        $('#tinggi_badan').removeAttr('readonly');
        $('#tekanan_darah').removeAttr('readonly');
        $('#nadi').removeAttr('readonly');
        $('#suhu_badan').removeAttr('readonly');
        // $('#pemeriksaan_fisik_lainnya').removeAttr('readonly');
        $('#cara_berjalan_pasien').removeAttr('disabled');
        $('#menopang_saat_akan_duduk').removeAttr('disabled');
        $('#resiko_jatuh').removeAttr('disabled');
        $('#tindakan_pengamanan_jatuh').removeAttr('readonly');
        $('#perawat_pemeriksa').removeAttr('readonly');
        $('#btn-edit-screening').hide();
        $('#btn-save-screening').show();
        $('#btn-batal-screening').show();
    }

    function alertScreening() {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data screening pasien akan disimpan!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-screening').submit();
            }
        })
    }

    function batalScreening() {
        location.reload();
    }

    function periksa() {
        $('#btn-periksa').hide();
        $('#btn-add-pemeriksaan').show();
        $('#btn-batal-pemeriksaan').show();
        $('#tanggal_pemeriksaan').removeAttr('readonly');
        $('#keluhan_periksa').removeAttr('readonly');
        $('#diagnosa').removeAttr('readonly');
        $('#diagnosa_icd').removeAttr('disabled');
        $('#tindakan').removeAttr('readonly');
        $('#resep_obat').removeAttr('readonly');
        $('#keterangan').removeAttr('readonly');
        $('#btn-tambah-biaya').show();
        getIcdData();
    }

    function addPemeriksaanCek(){
        // find required input
        var requiredInput = $('#add-pemeriksaan').find('input[required]');
        var requiredSelect = $('#add-pemeriksaan').find('select[required]');
        var requiredTextarea = $('#add-pemeriksaan').find('textarea[required]');
        var required = requiredInput.add(requiredSelect).add(requiredTextarea);
        var error = false;
        // check if empty
        required.each(function(){
            if($(this).val() == ''){
                error = true;
            }
        });
        if(error == true){
            Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3000
            }).fire({
                icon: 'error',
                width: 450,
                title: 'Harap isi semua input yang bertanda bintang'
            });
        }else{
            alertPemeriksaan();
        }
    }

    function alertPemeriksaan() {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data pemeriksaan pasien akan disimpan!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#add-pemeriksaan').submit();
            }
        })
    }

    function batalPemeriksaan() {
        location.reload();
    }
    function getIcdData() {
        $.ajax({
            url: "{{url('/rawat-jalan/icd')}}",
            type: "GET",
            dataType: "json",
            success: function(data) {
                // console.log(data);
                $('#diagnosa_icd').empty();
                $('#diagnosa_icd').append('<option value="">-- Pilih Diagnosa ICD --</option>');
                $.each(data, function(key, value) {
                    $('#diagnosa_icd').append('<option value="' + value.code + '">' + value.code + ' - ' + value.name_id + ' (' + value.name_en + ')' + '</option>');
                });
            }
        });
    }

    $('#diagnosa_icd').change(function() {
        var code = $(this).val();
        $.ajax({
            url: "{{url('/rawat-jalan/icd')}}/" + code,
            type: "GET",
            dataType: "json",
            success: function(data) {
                console.log(data);
                $('#diagnosa').val(data.name_id + '(' + data.name_en + ')');
            }
        });
    });

    function antriPasien() {
        var status = $('#status').val();
        var title = '';
        var text = '';
        if (status == 'baru') {
            title = 'Apakah anda yakin?';
            text = 'Pasien akan dipindahkan ke antrian!';
            confirmButtonText = 'Antri';
        } else if (status == 'antri') {
            title = 'Apakah anda yakin?';
            text = 'Pasien akan diperiksa!';
            confirmButtonText = 'Periksa';
        } else if (status == 'diperiksa') {
            title = 'Apakah anda yakin?';
            text = 'Pasien telah selesai diperiksa!';
            confirmButtonText = 'Selesai';
        }
        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-antri').submit();
            }
        })
    }

    function batalPasien() {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Pendaftaran pasien akan dibatalkan!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Batalkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-batal').submit();
            }
        })
    }
</script>
@endsection
