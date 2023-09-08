@extends('layout')
@section('style')
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
        @if ($data->status == 'draft')
            <button type="button" class="btn btn-warning" id="btn-proses" onclick="diproses()">
                <i class="fas fa-check"></i> Proses Resep Obat
            </button>
        @elseif ($data->status == 'diproses')
            <button type="button" class="btn btn-success" id="btn-selesai" onclick="selesai()">
                <i class="fas fa-check"></i> Selesaikan Resep Obat
            </button>
        @endif
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="status-bar">
            <span class="status-bar-item {{$data->status == 'draft' ? 'selected' : ''}}">Baru</span>
            <span class="status-bar-item {{$data->status == 'diproses' ? 'selected' : ''}}">Diproses</span>
            <span class="status-bar-item {{$data->status == 'selesai' ? 'selected' : ''}}">Selesai</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="required" for="no_resep">No. Resep</label>
                            <input type="text" id="no_resep" class="form-control" value="{{$data->no_resep}}" readonly name="no_resep">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="required" for="no_registrasi">No. Registrasi Perawatan</label>
                            <input type="text" id="no_registrasi" class="form-control" value="{{$data->no_pendaftaran}}" readonly name="no_registrasi">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="required" for="pasien_id">Pasien</label>
                            <input type="text" id="pasien_id" class="form-control" value="{{$data->nama_pasien}}" readonly name="pasien_id">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="required" for="no_erm">No. Rekam Medis</label>
                            <input type="text" id="no_erm" class="form-control" value="{{$data->no_erm}}" readonly name="no_erm">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="required" for="poli_id">Poliklinik Asal</label>
                            <input type="text" id="poli_id" class="form-control" value="{{$data->nama_poli}}" readonly name="poli_id">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="required" for="dokter_id">Dokter</label>
                            <input type="text" id="dokter_id" class="form-control" value="{{$data->nama_dokter}}" readonly name="dokter_id">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="required" for="tanggal_periksa">Tanggal Periksa</label>
                            <input type="text" id="tanggal_periksa" class="form-control" value="{{$data->tgl_periksa}}" readonly name="tanggal_periksa">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="required" for="jenis_pendaftaran">Jenis Pendaftaran</label>
                            <input type="text" id="tanggal_periksa" class="form-control" value="{{$data->is_use_asuransi == 1 ? 'Asuransi (' . $asuransi->nama_asuransi .')' : 'Umum'}}" readonly name="tanggal_periksa">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="required" for="diagnosa">Diagnosa Akhir</label>
                    <textarea id="diagnosa" class="form-control" readonly name="diagnosa">{{$data->diagnosa}}</textarea>
                </div>
                <div class="form-group">
                    <label class="required" for="resep_dokter">Resep Dokter</label>
                    <textarea id="resep_dokter" class="form-control" readonly name="resep_dokter">{{$data->resep_dokter}}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="emr-tabs-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="emr-tabs-obat-line-tab" data-toggle="pill" href="#emr-tabs-obat-line" role="tab" aria-controls="emr-tabs-obat-line" aria-selected="true">Obat</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="emr-tabs-tabContent">
                    <div class="tab-pane fade show active" id="emr-tabs-obat-line" role="tabpanel" aria-labelledby="emr-tabs-obat-line-tab">
                        <div>
                            @if ($data->status == 'diproses')
                                <div class="text-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah-obat-line" onclick="tambahObatLine()">
                                        <i class="fas fa-plus"></i> Tambah Obat
                                    </button>
                                </div><br>
                            @elseif ($data->status == 'draft')
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Resep obat belum diproses. Proses resep obat terlebih dahulu untuk menambahkan obat.
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <table id="tabel_obat_line" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 5%">No.</th>
                                        <th class="text-center">Obat</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Satuan</th>
                                        <th class="text-center">Aturan Pakai</th>
                                        <th class="text-center">Keterangan</th>
                                        <th class="text-center" style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_line as $line)
                                        <tr>
                                            <td class="text-center">{{$loop->iteration}}</td>
                                            <td class="text-center">{{$line->nama_obat}}</td>
                                            <td class="text-center">{{$line->qty}}</td>
                                            <td class="text-center">{{$line->satuan}}</td>
                                            <td class="text-center">{{$line->aturan_pakai}}</td>
                                            <td class="text-center">{{$line->keterangan}}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-obat-line" data-id="{{$line->id}}" data-nama="{{$line->nama_obat}}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <div class="modal fade" id="modal-tambah-obat-line" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form  method="POST" action="/resep-obat/tambah-obat">
                @csrf
                <input type="hidden" name="resep_obat_pasien_id" value="{{$data->id}}">
                <input type="hidden" name="pendaftaran_id" value="{{$data->pendaftaran_id}}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Obat</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="form-group" id="form-group-obat">
                                <label class="required" for="obat_id">Obat</label>
                                <select class="form-control select2" id="obat_id" name="obat_id" style="width: 100%;" required onchange="getObatDetail()">
                                    <option value="">-- Pilih Obat --</option>
                                </select>
                                @error('obat_id')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <input type="hidden" id="stok" class="form-control" name="stok">
                            <div class="form-group" id="form-group-jumlah">
                                <label class="required" for="jumlah">Jumlah</label>
                                <input type="text" id="jumlah" class="form-control" name="jumlah" value="{{old('jumlah')}}" placeholder="Masukkan Jumlah Obat" onkeypress="return hanyaAngka(event)" required>
                                @error('jumlah')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group" id="form-group-satuan">
                                <label class="required" for="satuan">Satuan</label>
                                <input type="text" id="satuan" class="form-control" name="satuan" value="{{old('satuan')}}" placeholder="Masukkan Satuan Obat" required readonly>
                                @error('satuan')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group" id="form-group-aturan_pakai">
                                <label class="required" for="aturan_pakai">Aturan Pakai</label>
                                <select class="form-control select2" id="aturan_pakai" name="aturan_pakai" style="width: 100%;" required>
                                    <option value="">-- Pilih Aturan Pakai --</option>
                                    <option value="1x1">1x1</option>
                                    <option value="2x1">2x1</option>
                                    <option value="3x1">3x1</option>
                                    <option value="4x1">4x1</option>
                                    <option value="1x2">1x2</option>
                                    <option value="2x2">2x2</option>
                                    <option value="3x2">3x2</option>
                                    <option value="4x2">4x2</option>
                                    <option value="1x3">1x3</option>
                                    <option value="2x3">2x3</option>
                                    <option value="3x3">3x3</option>
                                    <option value="4x3">4x3</option>
                                    <option value="1x4">1x4</option>
                                    <option value="2x4">2x4</option>
                                    <option value="3x4">3x4</option>
                                    <option value="4x4">4x4</option>
                                </select>
                                @error('aturan_pakai')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group" id="form-group-keterangan">
                                <label class="required" for="keterangan">Keterangan</label>
                                <textarea id="keterangan" class="form-control" name="keterangan" placeholder="Masukkan Keterangan Obat (Waktu minum atau lainnya)" required>{{old('keterangan')}}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btn-tambah-obat" class="btn btn-primary">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div>
    <div class="modal fade" id="modal-hapus-obat-line" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h4 class="modal-title">Danger!!!</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modal-body-delete-jadwal"></div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                    <form method="POST" action="/resep-obat/delete-obat">
                        @csrf
                        <input type="hidden" name="id_obat_line" id="id_obat_line" value="">
                        <input type="method" name="_method" value="DELETE" id="_method" hidden>
                        <button type="submit" class="btn btn-outline-light">Delete</button>
                    </form>
                </div>
            </div>
        </div>
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

    $(function () {
        $("#tabel_obat_line").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": false, "searching": false, "paging": false, "info": false,
            "language": {
                "emptyTable": "Belum ada obat yang ditambahkan"
            }
        }).buttons().container().appendTo('#tabel_obat_line_wrapper .col-md-6:eq(0)');
    });

    $('#modal-hapus-obat-line').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal-body-delete-jadwal').text('Apakah anda yakin ingin menghapus obat '+nama+' dari resep obat?')
        $('#id_obat_line').val(id)
    });
</script>
<script>
    function tambahObatLine() {
        $.ajax({
            url: "{{url('/data-obat/get-obat')}}",
            method: "GET",
            success: function (data) {
                if (data.length == 0) {
                    Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 6000,
                    }).fire({
                        icon: 'error',
                        title: 'Tidak ada obat yang tersedia',
                        width: 600,
                    });
                } else {
                    $('#obat_id').empty();
                    $('#obat_id').append('<option value="">-- Pilih Obat --</option>');
                    for (var i = 0; i < data.length; i++) {
                        $('#obat_id').append('<option value="' + data[i].id + '">(' + data[i].kode_obat + ') ' + data[i].nama_obat + '</option>');
                    }
                }
            }
        });
    }
    function getObatDetail() {
        var id = $('#obat_id').val();
        $.ajax({
            url: "{{url('/data-obat/get-obat')}}",
            method: "GET",
            data: {id: id},
            success: function (data) {
                $('#stok').val(data.stok_obat);
                $('#satuan').val(data.jenis_obat);
            }
        });
    }
    $('#jumlah').on('keyup', function () {
        var stok = $('#stok').val();
        var jumlah = $('#jumlah').val();
        if (parseInt(jumlah) > parseInt(stok)) {
            Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 6000,
            }).fire({
                icon: 'error',
                title: 'Stok obat tidak mencukupi',
                width: 600,
            });
            $('#jumlah').val('');
            $('#btn-tambah-obat').prop('disabled', true);
        } else {
            $('#btn-tambah-obat').prop('disabled', false);
        }
    });
</script>
<script>
    function diproses() {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Resep obat akan diproses",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Proses',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('/resep-obat/change-status')}}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: "{{$data->id}}",
                        status: "diproses"
                    },
                    success: function (data) {
                        if (data.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                text: 'Resep obat berhasil diproses',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function () {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: 'Resep obat gagal diproses',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
            }
        });
    }
    function selesai() {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Resep obat akan diselesaikan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Selesaikan',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('/resep-obat/change-status')}}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: "{{$data->id}}",
                        status: "selesai"
                    },
                    success: function (data) {
                        if (data.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                text: 'Resep obat berhasil diselesaikan',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function () {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: 'Resep obat gagal diselesaikan',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
            }
        });
    }
</script>
@endsection
