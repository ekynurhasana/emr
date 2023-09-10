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
        @if (in_array(Auth::user()->role, ['super-admin', 'admin']))
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-tambah-dokter-poli" id="btn-tambah-dokter-poli">
                Tambah Jadwal Dokter
            </button>
        @endif
    </div>
</div><br>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Jadwal Dokter</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table_poli" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">No.</th>
                            <th class="text-center">Poliklinik</th>
                            <th class="text-center">Dokter</th>
                            <th class="text-center">Jadwal Praktek</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($data as $dok_poli)
                            <tr class="tr-emr">
                                <td class="text-center">{{$no}}</td>
                                <td>{{$dok_poli->nama_poli}}</td>
                                <td>{{$dok_poli->nama_dokter}}</td>
                                <td>{{$dok_poli->waktu_praktek}}</td>
                                <td class="text-center">
                                    @if ($dok_poli->status == 'buka')
                                        <span class="badge badge-success">Buka</span>
                                    @else
                                        <span class="badge badge-danger">Tutup</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{url('/data-dokter-poli/detail/'.$dok_poli->id)}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> </a>
                                    @if (in_array(Auth::user()->role, ['super-admin', 'admin']))
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-dokter-poli" data-id="{{$dok_poli->id}}" data-poli="{{$dok_poli->nama_poli}}" data-dokter="{{$dok_poli->nama_dokter}}">
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

<div class="modal fade" id="modal-tambah-dokter-poli" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form  method="POST" action="/data-dokter-poli/tambah">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Dokter Poliklinik Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group" id="form-poliklinik">
                            <label class="required" for="poli_id">Pilih Poliklinik</label>
                            <select class="form-control form-control-border select2 @error('poli_id') is-invalid @enderror" id="poli_id" name="poli_id" required>
                                <option value="">-- Pilih Poliklinik --</option>
                                @foreach ($data_poli as $p)
                                    <option value="{{$p->id}}" {{old('poli_id') == $p->id ? 'selected' : ''}}>{{$p->nama_poli}}</option>
                                @endforeach
                            </select>
                            @error('poli_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="form-dokter">
                            <label class="required" for="dokter_id">Pilih Dokter</label>
                            <select class="form-control form-control-border select2 @error('dokter_id') is-invalid @enderror" id="dokter_id" name="dokter_id" required>
                                <option value="">-- Pilih Dokter --</option>
                                @foreach ($data_dokter as $d)
                                    <option value="{{$d->id}}" {{old('dokter_id') == $d->id ? 'selected' : ''}}>{{$d->name}}</option>
                                @endforeach
                            </select>
                            @error('dokter_id')
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
    <div class="modal fade" id="modal-hapus-dokter-poli" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="form_delete_pasien" method="POST" action="/data-dokter-poli/delete">
                        @csrf
                        <input type="hidden" name="id_dokter_poli" id="id_dokter_poli">
                        <input type="hidden" name="type" id="type" value="dokter_poli">
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
        $("#table_poli").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#table_poli_wrapper .col-md-6:eq(0)');
    });

    $('#modal-hapus-dokter-poli').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('dokter')
        var poli = button.data('poli')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus Jadwal ' + nama + ' pada Poliklinik ' + poli + '?')
        $('#id_dokter_poli').val(id)
    });
</script>
@if(session('errors'))
<script>
    $(document).ready(function(){
        $('#modal-tambah-dokter-poli').modal('show');
    });
</script>
@endif
@endsection
