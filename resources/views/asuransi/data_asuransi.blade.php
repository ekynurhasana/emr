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
        {{-- cek user role --}}
        @if (Auth::user()->role == 'super-admin')
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-tambah-asuransi" id="btn-tambah-asuransi">
                Tambah Asuransi
            </button>
        @endif
    </div>
</div><br>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Asuransi</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table_asuransi" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">No.</th>
                            <th class="text-center" style="width: 10%">Kode Asuransi</th>
                            <th class="text-center">Nama Asuransi</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($data as $asuransi)
                            <tr class="tr-emr">
                                <td class="text-center">{{$no}}</td>
                                <td class="text-center">{{$asuransi->kode_asuransi}}</td>
                                <td>{{$asuransi->nama_asuransi}}</td>
                                <td class="text-center">
                                    @if ($asuransi->status == 'aktif')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{url('/asuransi/detail/'.$asuransi->id)}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> </a>
                                    @if (Auth::user()->role == 'super-admin')
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-asuransi" data-id="{{$asuransi->id}}" data-nama="{{$asuransi->nama_asuransi}}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
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

<div class="modal fade" id="modal-tambah-asuransi" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form  method="POST" action="/asuransi/tambah">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Asuransi Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group" id="form-kode-asuransi">
                            <label class="required" for="kode_asuransi">Kode Asuransi</label>
                            <input type="text" class="form-control form-control-border @error('kode_asuransi') is-invalid @enderror" id="kode_asuransi" placeholder="Kode Asuransi" name="kode_asuransi" required value="{{old('kode_asuransi')}}">
                            @error('kode_asuransi')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="form-nama-asuransi">
                            <label class="required" for="nama_asuransi">Nama Asuransi</label>
                            <input type="text" class="form-control form-control-border @error('nama_asuransi') is-invalid @enderror" id="nama_asuransi" placeholder="Nama Asuransi" name="nama_asuransi" required value="{{old('nama_asuransi')}}">
                            @error('nama_asuransi')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-tambah-asuransi" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div>
    <div class="modal fade" id="modal-hapus-asuransi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="form-hapus-asuransi" method="POST" action="/asuransi/delete">
                        @csrf
                        <input type="hidden" name="id_asuransi" id="id_asuransi">
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
        $("#table_asuransi").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#table_asuransi_wrapper .col-md-6:eq(0)');
    });

    $('#modal-hapus-asuransi').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus asuransi ' + nama + '?')
        $('#id_asuransi').val(id)
    });
</script>
@if (session('errors'))
    <script>
        $(document).ready(function(){
            $('#modal-tambah-asuransi').modal('show');
        });
    </script>
@endif
@endsection
