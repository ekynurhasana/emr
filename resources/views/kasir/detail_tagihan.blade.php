@extends('layout')
@section('style')
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
        @if(in_array(Session::get('role'), ['super-admin']))
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus-tagihan" data-id="{{$data->id}}" data-nama="{{$data->no_tagihan}}">
                <i class="fas fa-trash"></i> Hapus Tagihan
            </button>
        @endif
        <a href="{{url('/rawat-jalan/detail/'.$data->pendaftaran_id)}}" class="btn btn-info">
            Detail Perawatan
        </a>
        @if($no_erm != null)
            <a href="{{url('/rm-pasien/detail/'.$no_erm)}}" class="btn btn-info">
                Detail Rekam Medik
            </a>
        @endif
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="status-bar">
            <span class="status-bar-item {{$data->status == 'draft' ? 'selected' : ''}}">Baru</span>
            <span class="status-bar-item {{$data->status == 'pending' ? 'selected' : ''}}">Menunggu Pembayaran</span>
            <span class="status-bar-item {{$data->status == 'terbayar' ? 'selected' : ''}}">Terbayar</span>
            <span class="status-bar-item {{$data->status == 'batal' ? 'selected' : ''}}">Batal</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-8">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="no_tagihan">No. Tagihan</label>
                    <input type="text" id="no_tagihan" class="form-control" value="{{$data->no_tagihan}}" readonly name="no_tagihan">
                </div>
                <div class="form-group">
                    <label class="required" for="no_registrasi">No. Registrasi Perawatan</label>
                    <input type="text" id="no_registrasi" class="form-control" value="{{$data->no_pendaftaran}}" readonly name="no_registrasi">
                </div>
                <div class="form-group">
                    <label class="required" for="pasien_id">Pasien</label>
                    <input type="text" id="pasien_id" class="form-control" value="{{$data->nama_pasien}}" readonly name="pasien_id">
                </div>
                <div class="form-group">
                    <label class="required" for="tanggal_periksa">Tanggal Periksa</label>
                    <input type="text" id="tanggal_periksa" class="form-control" value="{{$data->tgl_periksa}}" readonly name="tanggal_periksa">
                </div>
                <div class="form-group">
                    <label class="required" for="jenis_pendaftaran">Jenis Pendaftaran</label>
                    <input type="text" id="tanggal_periksa" class="form-control" value="{{$data->is_use_asuransi == 1 ? 'Asuransi (' . $asuransi->nama_asuransi .')' : 'Umum'}}" readonly name="tanggal_periksa">
                </div>
            </div>
        </div>
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="emr-tabs-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="emr-tabs-tagihan-line-tab" data-toggle="pill" href="#emr-tabs-tagihan-line" role="tab" aria-controls="emr-tabs-tagihan-line" aria-selected="true">Detail Tagihan</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="emr-tabs-tabContent">
                    <div class="tab-pane fade show active" id="emr-tabs-tagihan-line" role="tabpanel" aria-labelledby="emr-tabs-tagihan-line-tab">
                        <div>
                            <div class="text-right">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah-tagihan-line">
                                    <i class="fas fa-plus"></i> Tambah Tagihan
                                </button>
                            </div><br>
                            <table id="tabel_tagihan_line" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 5%">No.</th>
                                        <th class="text-center">Jenis Tagihan</th>
                                        <th class="text-center">Detail Tagihan</th>
                                        <th class="text-center" style="width: 10%">Harga</th>
                                        <th class="text-center" style="width: 10%">Jumlah</th>
                                        <th class="text-center" style="width: 10%">Subtotal</th>
                                        <th class="text-center" style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_line as $line)
                                        <tr>
                                            <td class="text-center">{{$loop->iteration}}</td>
                                            <td class="text-center" style="text-transform: capitalize">{{$line->jenis_tagihan}}</td>
                                            <td>{{$line->nama_tagihan}}</td>
                                            <td class="text-center harga_class">{{$line->harga}}</td>
                                            <td class="text-center">{{$line->qty}}</td>
                                            <td class="text-center harga_class">{{$line->total}}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-tagihan-line" data-id="{{$line->id}}" data-nama="{{$line->nama_tagihan}}">
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
    <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Detail Pembayaran</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th style="width:50%">Perawatan:</th>
                                <td class="text-right"><span>Rp</span><span id="total-perawatan">{{$total_perawatan}}</span></td>
                            </tr>
                            <tr>
                                <th style="width:50%">Tindakan:</th>
                                <td class="text-right"><span>Rp</span><span id="total-tindakan">{{$total_tindakan}}</span></td>
                            </tr>
                            <tr>
                                <th style="width:50%">Obat:</th>
                                <td class="text-right"><span>Rp</span><span id="total-obat">{{$total_obat}}</span></td>
                            </tr>
                            <tr>
                                <th style="width:50%">Administrasi:</th>
                                <td class="text-right"><span>Rp</span><span id="total-administrasi">{{$total_administrasi}}</span></td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <th style="width:50%">Total:</th>
                                <td class="text-right"><span>Rp</span><span id="total-all">{{$total}}</span></td>
                            </tr>
                            <tr>
                                <th style="width:50%">Potongan Asuransi:</th>
                                <td class="text-right"><span>(-)Rp</span><span id="total-asuransi">{{$total_asuransi}}</span></td>
                            </tr>
                            @if($data->status == 'pending')
                            <tr id="tr_diskon" style="display: none">
                                <th style="width:50%">Diskon:</th>
                                <td class="text-right">
                                    <input type="text" class="form-control form-control-sm text-right" value="0" name="diskon" id="diskon" onkeypress="return hanyaAngka(event)" onkeyup="hitungTotalTagihan()">
                                </td>
                            </tr>
                            @endif
                        </tbody>
                        <tbody>
                            <tr>
                                <th style="width:50%">Total Tagihan:</th>
                                <td class="text-right"><span>Rp</span><span id="total-tagihan">{{$total_tagihan}}</span></td>
                            </tr>
                            @if($total_tagihan != 0 && $data->status == 'pending')
                                <tr id="input-pembayaran">
                                    <th style="width:50%">Pembayaran:</th>
                                    <td class="text-right">
                                        <input type="text" class="form-control form-control-sm text-right" value="0" name="pembayaran" id="pembayaran" onkeypress="return hanyaAngka(event)" onkeyup="hitungKembalian()" placeholder="Masukkan Pembayaran">
                                    </td>
                                </tr>
                                <tr id="kembalian">
                                    <th style="width:50%">Kembalian:</th>
                                    <td class="text-right"><span>Rp</span><span id="kembalian-value">0</span></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    @if($total_tagihan != 0 && $data->status == 'pending')
                        <button type="button" class="btn btn-success btn-block" onclick="bayarTagihan()">
                            <i class="fas fa-money-bill-wave"></i> Bayar
                        </button>
                    @elseif($data->status == 'pending')
                        <div class="text-right">
                            <button type="button" class="btn btn-primary" onclick="selesaiTagihan()">
                                <i class="fas fa-check"></i> Selesaikan Tagihan
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <div class="modal fade" id="modal-tambah-tagihan-line" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form  method="POST" action="/tagihan/tambah-line">
                @csrf
                <input type="hidden" name="id_tagihan" value="{{$data->id}}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Tagihan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="form-group" id="form-jenis-tagihan">
                                <label class="required" for="jenis_tagihan">Jenis Tagihan</label>
                                <select class="form-control select2 @error('jenis_tagihan') is-invalid @enderror" id="jenis_tagihan" name="jenis_tagihan" style="width: 100%;">
                                    <option value="" disabled selected>Pilih Jenis Tagihan</option>
                                    <option value="perawatan">Perawatan</option>
                                    <option value="tindakan">Tindakan</option>
                                    <option value="obat">Obat</option>
                                    <option value="administrasi">Administrasi</option>
                                </select>
                                @error('jenis_tagihan')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group" id="form-tagihan">
                                <label class="required" for="detail_tagihan">Detail Tagihan</label>
                                <input type="text" id="detail_tagihan" class="form-control" name="detail_tagihan" placeholder="Masukkan Detail Tagihan">
                                @error('detail_tagihan')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group" id="form-harga">
                                <label class="required" for="harga">Harga</label>
                                <input type="text" id="harga" class="form-control" name="harga" placeholder="Masukkan Harga" onkeypress="return hanyaAngka(event)">
                                @error('harga')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group" id="form-jumlah">
                                <label class="required" for="jumlah">Jumlah</label>
                                <input type="text" id="jumlah" class="form-control" name="jumlah" placeholder="Masukkan Jumlah" onkeypress="return hanyaAngka(event)">
                                @error('jumlah')
                                    <div class="invalid-feedback">
                                        {{$message}}
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
    <div class="modal fade" id="modal-hapus-tagihan" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="form-hapus-tagihan" method="POST" action="/tagihan/delete">
                        @csrf
                        <input type="hidden" name="id_tagihan" id="id_tagihan">
                        <input type="method" name="_method" value="DELETE" id="_method" hidden>
                        <button type="submit" class="btn btn-outline-light">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <div class="modal fade" id="modal-hapus-tagihan-line" role="dialog" aria-hidden="true">
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
                    <form method="POST" action="/tagihan/line/delete">
                        @csrf
                        <input type="hidden" name="id_tagihan_line" id="id_tagihan_line">
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
        $("#tabel_tagihan_line").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": false, "searching": false, "paging": false, "info": false,
            "language": {
                "emptyTable": "Tidak ada tagihan"
            }
        }).buttons().container().appendTo('#tabel_tagihan_line_wrapper .col-md-6:eq(0)');
    });
    $(document).ready(function(){
        $('#total-perawatan').each(function(){
            $(this).html(formatRupiah($(this).html()));
        });
        $('#total-tindakan').each(function(){
            $(this).html(formatRupiah($(this).html()));
        });
        $('#total-obat').each(function(){
            $(this).html(formatRupiah($(this).html()));
        });
        $('#total-administrasi').each(function(){
            $(this).html(formatRupiah($(this).html()));
        });
        $('#total-all').each(function(){
            $(this).html(formatRupiah($(this).html()));
        });
        $('#total-asuransi').each(function(){
            $(this).html(formatRupiah($(this).html()));
        });
        $('#total-tagihan').each(function(){
            $(this).html(formatRupiah($(this).html()));
        });
        $('.harga_class').each(function(){
            $(this).html('Rp'+formatRupiah($(this).html()));
        });
    });
    $('#harga').on('keyup', function(){
        $(this).val(formatRupiah($(this).val()));
    });
    $(document).ready(function(){
        var total_tagihan = $('#total-tagihan').text();
        if(total_tagihan == 0){
            $('#tr_diskon').hide();
        } else {
            $('#tr_diskon').show();
        }
    });
    $('#modal-hapus-tagihan').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus tagihan ' + nama + '?')
        $('#id_tagihan').val(id)
    });
    $('#modal-hapus-tagihan-line').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal-body-delete-jadwal').text('Apakah anda yakin ingin menghapus tagihan ' + nama + '?')
        $('#id_tagihan_line').val(id)
    });
    function hitungTotalTagihan(){
        var total_all = parseInt($('#total-all').text().replace(/,.*|[^0-9]/g, ''));
        var total_asuransi = parseInt($('#total-asuransi').text().replace(/,.*|[^0-9]/g, ''));
        var diskon = parseInt($('#diskon').val());
        if(isNaN(diskon)){
            diskon = 0;
        }
        if (diskon > total_all - total_asuransi) {
            Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3000
            }).fire({
                icon: 'error',
                title: 'Diskon tidak boleh melebihi total tagihan',
                width: 600,
            });
            $('#diskon').val(0);
            diskon = 0;
        }
        var total_tagihan = total_all - total_asuransi - diskon;
        $('#total-tagihan').text(total_tagihan);
        $('#total-tagihan').html(formatRupiah($('#total-tagihan').text()));
    }
    function selesaiTagihan(){
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Anda akan menyelesaikan tagihan ini",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Selesaikan',
            cancelButtonText: 'Batal',
            width: 600,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('/tagihan/bayar')}}",
                    type: 'POST',
                    data: {
                        '_token': "{{csrf_token()}}",
                        'id_tagihan': '{{$data->id}}',
                        'status': 'terbayar'
                    },
                    success: function(data){
                        if (data.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message,
                                width: 600,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function(){
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message,
                                width: 600,
                            });
                        }
                    }
                });
            }
        });
    }
</script>
@if ($total_tagihan != 0)
<script>
    function hitungKembalian(){
        var pembayaran = parseInt($('#pembayaran').val());
        var total_tagihan = parseInt($('#total-tagihan').text().replace(/,.*|[^0-9]/g, ''));
        var kembalian = pembayaran - total_tagihan;
        $('#kembalian-value').text(kembalian);
        $('#kembalian-value').html(formatRupiah($('#kembalian-value').text()));
    }
    function bayarTagihan(){
        var pembayaran = parseInt($('#pembayaran').val());
        var total_tagihan = parseInt($('#total-tagihan').text().replace(/,.*|[^0-9]/g, ''));
        if(isNaN(pembayaran)){
            pembayaran = 0;
        }
        if (pembayaran < total_tagihan) {
            Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3000
            }).fire({
                icon: 'error',
                title: 'Pembayaran tidak boleh kurang dari total tagihan',
                width: 600,
            });
        } else {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan melakukan pembayaran sebesar Rp" + formatRupiah($('#total-tagihan').text()),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Bayar',
                cancelButtonText: 'Batal',
                width: 600,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{url('/tagihan/bayar')}}",
                        type: 'POST',
                        data: {
                            '_token': "{{csrf_token()}}",
                            'id_tagihan': '{{$data->id}}',
                            'pembayaran': pembayaran,
                            'status': 'terbayar'
                        },
                        success: function(data){
                            if (data.status == 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message,
                                    width: 600,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function(){
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: data.message,
                                    width: 600,
                                });
                            }
                        }
                    });
                }
            });
        }
    }
</script>
@endif
@if (session('errors'))
    <script>
        $(document).ready(function(){
            $('#modal-tambah-tagihan-line').modal('show');
        });
    </script>
@endif
@endsection
