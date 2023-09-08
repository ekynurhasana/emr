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
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-tambah-pasien">
            Tambah Pasien Baru
        </button>
    </div>
</div><br>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Pasien</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table_pasien" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">Nama Pasien</th>
                            <th class="text-center" style="width: 15%">Jenis Kelamin</th>
                            <th class="text-center" style="width: 40%">Alamat</th>
                            <th class="text-center" style="width: 10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $pasien)
                            {{-- <tr onclick="window.location='{{url('/data-pasien/detail/'.$pasien->id)}}'" class="tr-emr"> --}}
                            <tr class="tr-emr">
                                <td>{{$pasien->nama_pasien}}</td>
                                <td class="text-center">{{$pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'}}</td>
                                <td>{{$pasien->alamat}}</td>
                                <td class="text-center">
                                    <a href="{{url('/data-pasien/detail/'.$pasien->id)}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-pasien" data-id="{{$pasien->id}}" data-nama="{{$pasien->nama_pasien}}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

<div class="modal fade" id="modal-tambah-pasien" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form  method="POST" action="/data-pasien/tambah-pasien">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Pasien Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group" id="form-pasien-baru">
                            <label class="required" for="nama_pasien">Nama Pasien</label>
                            <input type="text" class="form-control form-control-border @error('nama_pasien') is-invalid @enderror" id="nama_pasien" placeholder="Nama Pasien" name="nama_pasien" required value="{{old('nama_pasien')}}">
                            @error('nama_pasien')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="form-jenis-kelamin">
                            <label class="required" for="jenis_kelamin">Jenis Kelamin</label>
                            <select class="form-control form-control-border @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="L" {{old('jenis_kelamin') == 'L' ? 'selected' : ''}}>Laki-laki</option>
                                <option value="P" {{old('jenis_kelamin') == 'P' ? 'selected' : ''}}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="form-alamat">
                            <label class="required" for="alamat">Alamat</label>
                            <textarea class="form-control form-control-border @error('alamat') is-invalid @enderror" id="alamat" placeholder="Alamat" name="alamat" required>{{old('alamat')}}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="form-no-identitas">
                            <label class="required" for="no_ktp">No. Identitas</label>
                            <input type="text" class="form-control form-control-border @error('no_ktp') is-invalid @enderror" id="no_ktp" placeholder="No. Identitas" name="no_ktp" required value="{{old('no_ktp')}}">
                            @error('no_ktp')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-tambah-pasien" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div>
    <div class="modal fade" id="modal-hapus-pasien" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
        $("#table_pasien").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#table_pasien_wrapper .col-md-6:eq(0)');
    });

    $('#modal-hapus-pasien').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus pasien atas nama ' + nama + '?')
        $('#id_pasien').val(id)
    });

    function hanyaAngka(event) {
        var angka = (event.which) ? event.which : event.keyCode
        if ((angka < 48 || angka > 57))
            return false;
        return true;
    }

    $(document).ready(function() {
        $('#tr').click(function() {
            var id = $(this).data('id');
            // redirect to detail page
            console.log(id);
            // window.location.href = "/data-pasien/detail/" + id;
        });
    });
</script>
@if (session('errors'))
<script>
    $(document).ready(function() {
        $('#modal-tambah-pasien').modal('show');
    });
</script>
@endif
@endsection
