@extends('layout')
@section('style')
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
{{-- <div --}}
<div class="row">
    <div class="col-md-4 col-sm-12 col-xs-12">
        @if($sub_menu_slug == 'tagihan-pasien-draft')
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-tambah-tagihan" id="btn-tambah-tagihan">
            Tambah Tagihan Baru
        </button>
        @endif
    </div>
</div><br>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Tagihan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table_tagihan" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">No.</th>
                            <th class="text-center">No. Tagihan</th>
                            <th class="text-center">No. Pendfataran</th>
                            <th class="text-center">Nama Pasien</th>
                            <th class="text-center">Tanggal Daftar</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($data as $tg)
                            <tr class="tr-emr">
                                <td class="text-center">{{$no}}</td>
                                <td class="text-center">{{$tg->no_tagihan}}</td>
                                <td class="text-center">{{$tg->no_pendaftaran}}</td>
                                <td class="text-center">{{$tg->nama_pasien}}</td>
                                <td class="text-center">{{date('d M Y', strtotime($tg->created_at))}}</td>
                                <td class="text-center">
                                    @if($tg->status == 'draft')
                                        <span class="badge badge-info">Draft</span>
                                    @elseif($tg->status == 'pending')
                                        <span class="badge badge-warning">Menunggu Pembayaran</span>
                                    @elseif($tg->status == 'terbayar')
                                        <span class="badge badge-success">Terbayar</span>
                                    @endif
                                <td class="text-center">
                                    <a href="{{url('/tagihan/detail/'.$tg->id)}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-tagihan" data-id="{{$tg->id}}" data-nama="{{$tg->no_tagihan}}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @php $no++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
@if($sub_menu_slug == 'tagihan-pasien-draft')
<div class="modal fade" id="modal-tambah-tagihan" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form  method="POST" action="/tagihan/tambah">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Tagihan Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group" id="form-kode-obat">
                            <label class="required" for="pasien_id">Tagihan untuk Pasien</label>
                            <select class="form-control select2" id="pasien_id" name="pasien_id" style="width: 100%;" required>
                                <option value="" selected disabled>Pilih Pasien</option>
                                @foreach($pasien as $ps)
                                    <option value="{{$ps->id}}">{{$ps->slug_number}} - {{$ps->nama_pasien}}</option>
                                @endforeach
                            </select>
                            @error('pasien_id')
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
@endif

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
        $('.harga_class').each(function(){
            $(this).html(formatRupiah($(this).html()));
        });
    });

    $(function () {
        $("#table_tagihan").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#table_tagihan_wrapper .col-md-6:eq(0)');
    });

    $('#modal-hapus-tagihan').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus tagihan ' + nama + '?')
        $('#id_obat').val(id)
    });
</script>
@if (session('errors'))
    <script>
        $(document).ready(function(){
            $('#modal-tambah-tagihan').modal('show');
        });
    </script>
@endif
@endsection
