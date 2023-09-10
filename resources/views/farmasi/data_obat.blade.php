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
        @if (in_array(Auth::user()->role, ['super-admin', 'admin', 'apoteker']))
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-tambah-obat" id="btn-tambah-obat">
                Tambah Obat Baru
            </button>
        @endif
    </div>
</div><br>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Obat</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table_obat" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">No.</th>
                            <th class="text-center">Kode Obat</th>
                            <th class="text-center">Nama Obat</th>
                            <th class="text-center">Jenis Obat</th>
                            <th class="text-center">Stok Obat</th>
                            <th class="text-center">Harga Obat</th>
                            <th class="text-center" style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($data as $obat)
                            <tr class="tr-emr">
                                <td class="text-center">{{$no}}</td>
                                <td class="text-center">{{$obat->kode_obat}}</td>
                                <td>{{$obat->nama_obat}}</td>
                                <td class="text-center">{{$obat->jenis_obat}}</td>
                                <td class="text-center">{{$obat->stok_obat}}</td>
                                <td class="text-center" class="harga_obat_class">{{$obat->harga_obat}}</td>
                                <td class="text-center">
                                    <a href="{{url('/data-obat/detail/'.$obat->id)}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> </a>
                                    @if (in_array(Auth::user()->role, ['super-admin', 'admin', 'apoteker']))
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-obat" data-id="{{$obat->id}}" data-nama="{{$obat->nama_obat}}">
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

<div class="modal fade" id="modal-tambah-obat" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form  method="POST" action="/data-obat/tambah">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Obat Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group" id="form-kode-obat">
                            <label class="required" for="kode_obat">Kode Obat</label>
                            <input type="text" class="form-control form-control-border @error('kode_obat') is-invalid @enderror" id="kode_obat" placeholder="Kode Obat" name="kode_obat" required value="{{old('kode_obat')}}">
                            @error('kode_obat')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="form-nama-obat">
                            <label class="required" for="nama_obat">Nama Obat</label>
                            <input type="text" class="form-control form-control-border @error('nama_obat') is-invalid @enderror" id="nama_obat" placeholder="Nama Obat" name="nama_obat" required value="{{old('nama_obat')}}">
                            @error('nama_obat')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="form-jenis-obat">
                            <label class="required" for="jenis_obat">Jenis Obat</label>
                            <select class="form-control form-control-border @error('jenis_obat') is-invalid @enderror" id="jenis_obat" name="jenis_obat" required>
                                <option value="">-- Pilih Jenis Obat --</option>
                                <option value="Kapsul" {{old('jenis_obat') == 'Kapsul' ? 'selected' : ''}}>Kapsul</option>
                                <option value="Tablet" {{old('jenis_obat') == 'Tablet' ? 'selected' : ''}}>Tablet</option>
                                <option value="Sirup" {{old('jenis_obat') == 'Sirup' ? 'selected' : ''}}>Sirup</option>
                                <option value="Salep" {{old('jenis_obat') == 'Salep' ? 'selected' : ''}}>Salep</option>
                                <option value="Koyo" {{old('jenis_obat') == 'Koyo' ? 'selected' : ''}}>Koyo</option>
                                <option value="Lainnya" {{old('jenis_obat') == 'Lainnya' ? 'selected' : ''}}>Lainnya</option>
                            </select>
                            @error('jenis_obat')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="form-stok-obat">
                            <label class="required" for="stok_obat">Stok Obat</label>
                            <input type="text" class="form-control form-control-border @error('stok_obat') is-invalid @enderror" id="stok_obat" placeholder="Stok Obat" name="stok_obat" required value="{{old('stok_obat')}}" onkeypress="return hanyaAngka(event)">
                            @error('stok_obat')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="form-harga-obat">
                            <label class="required" for="harga_obat">Harga Obat</label>
                            <input type="text" class="form-control form-control-border @error('harga_obat') is-invalid @enderror" id="harga_obat" placeholder="Harga Obat" name="harga_obat" required value="{{old('harga_obat')}}" onkeypress="return hanyaAngka(event)">
                            @error('harga_obat')
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

<div>
    <div class="modal fade" id="modal-hapus-obat" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="form-hapus-obat" method="POST" action="/data-obat/delete">
                        @csrf
                        <input type="hidden" name="id_obat" id="id_obat">
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
        $("#harga_obat").keyup(function(e){
            $(this).val(formatRupiah($(this).val()));
        });
    });

    $(document).ready(function(){
        $('.harga_obat_classs').each(function(){
            $(this).html(formatRupiah($(this).html()));
        });
    });

    $(function () {
        $("#table_obat").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#table_obat_wrapper .col-md-6:eq(0)');
    });

    $('#modal-hapus-obat').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus obat ' + nama + '?')
        $('#id_obat').val(id)
    });
</script>
@if (session('errors'))
    <script>
        $(document).ready(function(){
            $('#modal-tambah-obat').modal('show');
        });
    </script>
@endif
@endsection
