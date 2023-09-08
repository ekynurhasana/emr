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
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-tambah-poli" id="btn-tambah-poli">
                Tambah Poliklinik Baru
            </button>
        @endif
    </div>
</div><br>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Poliklinik</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table_poli" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">No.</th>
                            <th class="text-center">ID Poliklinik</th>
                            <th class="text-center">Nama Poliklinik</th>
                            <th class="text-center" style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($data as $poli)
                            <tr class="tr-emr">
                                <td class="text-center">{{$no}}</td>
                                <td>{{$poli->id}}</td>
                                <td>{{$poli->nama_poli}}</td>
                                <td class="text-center">
                                    <a href="{{url('/data-poliklinik/detail/'.$poli->id)}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> </a>
                                    @if (Auth::user()->role == 'super-admin')
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-poli" data-id="{{$poli->id}}" data-nama="{{$poli->nama_poli}}">
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

<div class="modal fade" id="modal-tambah-poli" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form  method="POST" action="/data-poliklinik/tambah">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Poliklinik Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group" id="form-poli-baru">
                            <label class="required" for="nama_poli">Nama Poliklinik</label>
                            <input type="text" class="form-control form-control-border @error('nama_poli') is-invalid @enderror" id="nama_poli" placeholder="Nama Poliklinik" name="nama_poli" required value="{{old('nama_poli')}}">
                            @error('nama_poli')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="form-harga-poli">
                            <label class="required" for="biaya_poli">Biaya Poliklinik</label>
                            <input type="text" class="form-control form-control-border @error('biaya_poli') is-invalid @enderror" id="biaya_poli" placeholder="Biaya Poliklinik" name="biaya_poli" required value="{{old('biaya_poli')}}">
                            @error('biaya_poli')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-tambah-poli" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div>
    <div class="modal fade" id="modal-hapus-poli" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="form_delete_poliklinik" method="POST" action="/data-poliklinik/delete">
                        @csrf
                        <input type="hidden" name="id_poliklinik" id="id_poliklinik">
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
    $(function(){
        $("#biaya_poli").keyup(function(e){
            $(this).val(formatRupiah($(this).val()));
        });
    });

    $(document).ready(function(){
        $('#biaya_poli').val(formatRupiah($('#biaya_poli').val()));
    });

    $(function () {
        $("#table_poli").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#table_poli_wrapper .col-md-6:eq(0)');
    });

    $('#modal-hapus-poli').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus Poliklinik '+nama+'?')
        $('#id_poliklinik').val(id)
    });
</script>
@if (session('errors'))
    <script>
        $(document).ready(function(){
            $('#modal-tambah-poli').modal('show');
        });
    </script>
@endif
@endsection
