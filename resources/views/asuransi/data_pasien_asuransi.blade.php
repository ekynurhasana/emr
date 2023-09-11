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
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-tambah-pasien-asuransi" id="btn-tambah-pasien-asuransi" onclick="getDataDropdownPasienAsuransi()">
            Tambah Pasien Asuransi
        </button>
    </div>
</div><br>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Pasien Asuransi</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table_pasienasuransi" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">No.</th>
                            <th class="text-center" style="width: 10%">No Peserta</th>
                            <th class="text-center">Pasien</th>
                            <th class="text-center">Asuransi</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($data as $ps)
                            <tr class="tr-emr">
                                <td class="text-center">{{$no}}</td>
                                <td class="text-center">{{$ps->nomor_peserta}}</td>
                                <td>{{$ps->nama_pasien}}</td>
                                <td class="text-center">{{$ps->nama_asuransi}}</td>
                                <td class="text-center">
                                    @if ($ps->status == 'aktif')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{url('/pasien-asuransi/detail/'.$ps->id)}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-pasien-asuransi" data-id="{{$ps->id}}" data-nama="{{$ps->nama_pasien}}" data-asuransi="{{$ps->nama_asuransi}}">
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

<div class="modal fade" id="modal-tambah-pasien-asuransi" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form  method="POST" action="/pasien-asuransi/tambah">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Pasien Asuransi Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="required" for="no_peserta">No Peserta</label>
                        <input type="text" class="form-control @error('no_peserta') is-invalid @enderror" name="no_peserta" id="no_peserta" placeholder="Masukkan No Peserta" value="{{old('no_peserta')}}">
                        @error('no_peserta')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group" id="form-pasien">
                        <label class="required" for="id_pasien">Nama Pasien</label>
                        <select class="form-control select2 @error('id_pasien') is-invalid @enderror" name="id_pasien" id="id_pasien" style="width: 100%;">
                            <option value="">-- Pilih Pasien --</option>
                        </select>
                        @error('id_pasien')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group" id="form-asuransi">
                        <label class="required" for="id_asuransi">Asuransi</label>
                        <select class="form-control select2 @error('id_asuransi') is-invalid @enderror" name="id_asuransi" id="id_asuransi" style="width: 100%;" onchange="getDataTipeAsuransi()">
                            <option value="">-- Pilih Asuransi --</option>
                        </select>
                        @error('id_asuransi')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group" id="form-tipe-asuransi" hidden>
                        <label class="required" for="tipe_asuransi">Tipe Asuransi</label>
                        <select class="form-control select2 @error('tipe_asuransi') is-invalid @enderror" name="tipe_asuransi" id="tipe_asuransi" style="width: 100%;">
                            <option value="">-- Pilih Tipe Asuransi --</option>
                        </select>
                        @error('tipe_asuransi')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-tambah-pasien-asuransi" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div>
    <div class="modal fade" id="modal-hapus-pasien-asuransi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="form-hapus-pasien-asuransi" method="POST" action="/pasien-asuransi/delete">
                        @csrf
                        <input type="hidden" name="id_pasien_asuransi" id="id_pasien_asuransi" value="">
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
        $("#table_pasienasuransi").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#table_pasienasuransi_wrapper .col-md-6:eq(0)');
    });

    function getDataDropdownPasienAsuransi() {
        $.ajax({
            url: "{{url('/pasien-asuransi/get-data-dropdown')}}",
            type: "GET",
            dataType: "json",
            success: function(data) {
                if (data.pasien.length == 0) {
                    Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 10000
                    }).fire({
                        width: 600,
                        icon: 'error',
                        title: 'Oops...',
                        html: 'Data pasien belum ada, silahkan tambah data pasien terlebih dahulu! <br> <a href="{{url('/data-pasien')}}" class="btn btn-primary btn-sm mt-2">Tambah Pasien</a>',
                    });
                    $('#id_pasien').attr('disabled', true);
                } else if (data.asuransi.length == 0) {
                    Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 10000
                    }).fire({
                        width: 600,
                        icon: 'error',
                        title: 'Oops...',
                        html: 'Data asuransi belum ada, silahkan tambah atau aktifkan data asuransi terlebih dahulu! <br> <a href="{{url('/asuransi')}}" class="btn btn-primary btn-sm mt-2">Data Asuransi</a>',
                    });
                    $('#id_asuransi').attr('disabled', true);
                } else if (data.pasien.length == 0 && data.asuransi.length == 0) {
                    Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 10000
                    }).fire({
                        width: 600,
                        icon: 'error',
                        title: 'Oops...',
                        html: 'Data pasien dan asuransi belum ada, silahkan tambah data pasien dan atau aktifkan data asuransi terlebih dahulu! <br> <a href="{{url('/data-pasien')}}" class="btn btn-primary btn-sm mt-2">Tambah Pasien</a> <a href="{{url('/asuransi')}}" class="btn btn-primary btn-sm mt-2">Data Asuransi</a>',
                    });
                    $('#id_pasien').attr('disabled', true);
                    $('#id_asuransi').attr('disabled', true);
                }
                $('#id_pasien').empty();
                $('#id_asuransi').empty();
                $('#id_pasien').append('<option value="">-- Pilih Pasien --</option>');
                $('#id_asuransi').append('<option value="">-- Pilih Asuransi --</option>');
                $.each(data.pasien, function(key, value) {
                    $('#id_pasien').append('<option value="'+value.id+'">'+value.slug_number+' - '+value.nama_pasien+'</option>');
                });
                $.each(data.asuransi, function(key, value) {
                    $('#id_asuransi').append('<option value="'+value.id+'">'+value.nama_asuransi+'</option>');
                });
            }
        });
    }
    function getDataTipeAsuransi() {
        var id_asuransi = $('#id_asuransi').val();
        $.ajax({
            url: "{{url('/pasien-asuransi/get-data-dropdown')}}",
            type: "GET",
            data: {
                id_asuransi: id_asuransi
            },
            dataType: "json",
            success: function(data) {
                if (data.length != 0) {
                    $('#form-tipe-asuransi').removeAttr('hidden');
                    $('#tipe_asuransi').empty();
                    $('#tipe_asuransi').append('<option value="">-- Pilih Tipe Asuransi --</option>');
                    $.each(data, function(key, value) {
                        $('#tipe_asuransi').append('<option value="'+value.id+'">'+value.nama+'</option>');
                    });
                }
            }
        });
    }

    $('#modal-hapus-pasien-asuransi').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var asuransi = button.data('asuransi')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus data pasien  ' + nama + ' dari asuransi ' + asuransi + '?')
        $('#id_pasien_asuransi').val(id)
    });
</script>
@if (session('errors'))
    <script>
        $(document).ready(function(){
            $('#modal-tambah-pasien-asuransi').modal('show');
        });
    </script>
@endif
@endsection
