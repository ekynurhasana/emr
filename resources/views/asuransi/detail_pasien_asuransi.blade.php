@extends('layout')
@section('style')
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
<form method="POST" action="/pasien-asuransi/edit" id="form_edit_data_asuransi">
@csrf
<input type="hidden" name="id" value="{{$data->id}}">
<button type="button" class="btn btn-danger" id="btn-hapus-data-pasien-asuransi" data-toggle="modal" data-target="#modal-hapus-data-pasien-asuransi" data-id="{{$data->id}}" data-asuransi="{{$data->nama_asuransi}}" data-nama="{{$data->nama_pasien}}">
    <i class="fas fa-trash"></i>
</button>
<button type="button" class="btn btn-info" id="btn-edit-data-pasien-asuransi">
    Edit Pasien Asuransi
</button>
<button type="button" class="btn btn-danger" id="btn-cancel-edit-data-pasien-asuransi" style="display: none;">
    Cancel
</button>
<button type="button" class="btn btn-success" id="btn-save-data-pasien-asuransi" style="display: none;" onclick="simpanDataPasienAsuransi()">
    Simpan Data Pasien Asuransi
</button>
@if($data->status == 'aktif')
    <button type="button" class="btn btn-warning" id="btn-nonaktifkan-data-pasien-asuransi" onclick="nonaktifkaAsuransi()">
        Nonaktifkan Pasien Asuransi
    </button>
@else
    <button type="button" class="btn btn-success" id="btn-aktifkan-data-pasien-asuransi" onclick="aktifkaAsuransi()">
        Aktifkan Pasien Asuransi
    </button>
@endif
<br><br>
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="nomor_peserta">Nomor Peserta</label>
                    <input type="text" id="nomor_peserta" class="form-control @error('nomor_peserta') is-invalid @enderror" value="{{$data->nomor_peserta}}" readonly name="nomor_peserta" maxlength="50" required>
                    @error('nomor_peserta')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="required" for="pasien_id">Pasien</label>
                    <select class="form-control select2 @error('pasien_id') is-invalid @enderror" id="pasien_id" name="pasien_id" data-placeholder="Pilih Pasien" style="width: 100%;" required disabled>
                        <option value="{{$data->pasien_id}}">{{$data->slug_pasien . ' - ' . $data->nama_pasien}}</option>
                    </select>
                    @error('pasien_id')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="asuransi_id">Asuransi</label>
                            <select class="form-control select2 @error('asuransi_id') is-invalid @enderror" id="asuransi_id" name="asuransi_id" data-placeholder="Pilih Asuransi" style="width: 100%;" required onchange="getDataTipeAsuransi()" disabled>
                                <option value="{{$data->asuransi_id}}">{{$data->nama_asuransi}}</option>
                            </select>
                            @error('asuransi_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group" id="div_tipe_asuransi" {{$tipe_asuransi == null ? 'hidden' : ''}}>
                            <label class="required" for="tipe_asuransi_id">Tipe Asuransi</label>
                            <select class="form-control select2 @error('tipe_asuransi_id') is-invalid @enderror" id="tipe_asuransi_id" name="tipe_asuransi_id" data-placeholder="Pilih Tipe Asuransi" style="width: 100%;" disabled>
                                @if($tipe_asuransi == null)
                                    <option value="">Pilih Tipe Asuransi</option>
                                @else
                                    <option value="{{$tipe_asuransi->id}}">{{$tipe_asuransi->nama}}</option>
                                @endif
                            </select>
                            @error('tipe_asuransi_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="emr-tabs-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="emr-tabs-tanggungan-tab" data-toggle="pill" href="#emr-tabs-tanggungan" role="tab" aria-controls="emr-tabs-tanggungan" aria-selected="true">Tanggungan Asuransi Pasien</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="emr-tabs-tabContent">
                    <div class="tab-pane fade show active" id="emr-tabs-tanggungan" role="tabpanel" aria-labelledby="emr-tabs-tanggungan-tab">
                        <button type="button" class="btn btn-primary" id="btn-tambah-tipe" data-toggle="modal" data-target="#modal-tambah-tanggungan" style="float: right;">
                            Tambah Tanggungan Asuransi Pasien
                        </button><br><br>
                        <div>
                            <table id="tabel_tanggungan" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 15%">Jenis Tanggungan</th>
                                        <th class="text-center" style="width: 15%">Deskripsi</th>
                                        <th class="text-center">Limit</th>
                                        <th class="text-center">Sisa Limit</th>
                                        <th class="text-center" style="width: 15%">Terakhir Digunakan</th>
                                        <th class="text-center" style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_line as $line)
                                        <tr>
                                            <td class="text-center" style="text-transform: capitalize;">{{$line->jenis_tanggungan == 'all' ? 'Semua' : $line->jenis_tanggungan}}</td>
                                            <td class="text-center">{{$line->nama_tanggungan}}</td>
                                            <td class="text-center">{{$line->limit > 0 ? 'Rp'.number_format($line->limit, 2, ',', '.') : '-'}}</td>
                                            <td class="text-center">{{$line->sisa_limit > 0 ?'Rp'.number_format($line->sisa_limit, 2, ',', '.') : '-'}}</td>
                                            <td class="text-center">{{$line->tanggal_terakhir_penggunaan == null ? '-' : date('d/m/Y', strtotime($line->tanggal_terakhir_penggunaan))}}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-data-pasien-asuransi-tanggungan" data-id="{{$line->id}}" data-nama="{{$line->nama_tanggungan}}" date-id-asuransi="{{$data->id}}">
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
</form>
<div class="modal fade" id="modal-tambah-tanggungan" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form  method="POST" action="/pasien-asuransi/tambah-tanggungan">
            @csrf
            <input type="hidden" name="pasien_asuransi_id" value="{{$data->id}}">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Tanggungan Asuransi Pasien</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group" id="form-tipe-asuransi">
                        <label class="required" for="jenis_tanggungan">Jenis Tanggungan</label>
                        <select class="form-control select2 @error('jenis_tanggungan') is-invalid @enderror" id="jenis_tanggungan" name="jenis_tanggungan" data-placeholder="Pilih Jenis Tanggungan" style="width: 100%;" required>
                            <option value="all">Semua</option>
                            <option value="perawatan">Perawatan</option>
                            <option value="obat">Obat</option>
                            <option value="tindakan">Tindakan</option>
                            <option value="administrasi">Administrasi</option>
                        </select>
                        @error('jenis_tanggungan')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="required" for="nama_tanggungan">Deskripsi</label>
                        <input type="text" id="nama_tanggungan" class="form-control @error('nama_tanggungan') is-invalid @enderror" value="{{old('nama_tanggungan')}}" name="nama_tanggungan" maxlength="50" required>
                        @error('nama_tanggungan')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    {{-- checkbox is_limit --}}
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="is_limit" name="is_limit" value="1" onchange="isLimitChange()">
                            <label for="is_limit" class="custom-control-label">Limit</label>
                        </div>
                    </div>
                    <div class="form-group" id="div_limit" style="display: none;">
                        <label class="required" for="limit">Limit</label>
                        <input type="text" id="limit" class="form-control @error('limit') is-invalid @enderror" value="{{old('limit')}}" name="limit" min="0" onkeypress="return hanyaAngka(event)">
                        @error('limit')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div>
    <div class="modal fade" id="modal-hapus-data-pasien-asuransi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="form_delete_data_pasien_asuransi" method="POST" action="/pasien-asuransi/delete">
                        @csrf
                        <input type="hidden" name="id_pasien_asuransi" id="id_pasien_asuransi" value="{{$data->id}}">
                        <input type="method" name="_method" value="DELETE" id="_method" hidden>
                        <button type="submit" class="btn btn-outline-light">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <div class="modal fade" id="modal-hapus-data-pasien-asuransi-tanggungan" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="pasien__tanggungan" method="POST" action="/pasien-asuransi/delete-tanggungan">
                        @csrf
                        <input type="hidden" name="id_pasien_asuransi_tanggungan" id="id_pasien_asuransi_tanggungan">
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
        $("#limit").keyup(function(e){
            $(this).val(formatRupiah($(this).val()));
        });
    });

    $(function () {
        $("#tabel_tanggungan").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": false, "searching": false, "paging": false, "info": false,
            "language": {
                "emptyTable": "Tidak ada data tipe asuransi"
            }
        }).buttons().container().appendTo('#tabel_tanggungan_wrapper .col-md-6:eq(0)');
    });

    $('#btn-edit-data-pasien-asuransi').click(function(){
        var pasien_id = $('#pasien_id').val();
        var asuransi_id = $('#asuransi_id').val();
        $('#btn-edit-data-pasien-asuransi').hide();
        $('#btn-hapus-data-pasien-asuransi').hide();
        $('#btn-cancel-edit-data-pasien-asuransi').show();
        $('#btn-save-data-pasien-asuransi').show();
        $('#nomor_peserta').removeAttr('readonly');
        $('#pasien_id').removeAttr('disabled');
        $('#asuransi_id').removeAttr('disabled');
        $('#tipe_asuransi_id').removeAttr('disabled');
        getDataTipeAsuransi();
        $.ajax({
            type: "GET",
            url: "/pasien-asuransi/get-data-dropdown",
            data: {
                id: '{{$data->id}}',
                _token: '{{csrf_token()}}'
            },
            success: function (data) {
                $('#pasien_id').empty();
                $('#asuransi_id').empty();
                $.each(data.pasien, function (key, value) {
                    if(value.id == pasien_id){
                        $('#pasien_id').append('<option value="'+value.id+'" selected>'+value.slug_number+' - '+value.nama_pasien+'</option>');
                    }else{
                        $('#pasien_id').append('<option value="'+value.id+'">'+value.slug_number+' - '+value.nama_pasien+'</option>');
                    }
                });
                $.each(data.asuransi, function (key, value) {
                    if(value.id == asuransi_id){
                        $('#asuransi_id').append('<option value="'+value.id+'" selected>'+value.nama_asuransi+'</option>');
                    }else{
                        $('#asuransi_id').append('<option value="'+value.id+'">'+value.nama_asuransi+'</option>');
                    }
                });
            }
        });
    });
    $('#btn-cancel-edit-data-pasien-asuransi').click(function(){
        location.reload();
    });

    function getDataTipeAsuransi() {
        var id_asuransi = $('#asuransi_id').val();
        var tipe_asuransi_id = $('#tipe_asuransi_id').val();
        $.ajax({
            url: "{{url('/pasien-asuransi/get-data-dropdown')}}",
            type: "GET",
            data: {
                id_asuransi: id_asuransi
            },
            dataType: "json",
            success: function(data) {
                if (data.length != 0) {
                    $('#div_tipe_asuransi').removeAttr('hidden');
                    $('#tipe_asuransi_id').empty();
                    $('#tipe_asuransi_id').append('<option value="">-- Pilih Tipe Asuransi --</option>');
                    $.each(data, function(key, value) {
                        if(value.id == tipe_asuransi_id){
                            $('#tipe_asuransi_id').append('<option value="'+value.id+'" selected>'+value.nama+'</option>');
                        }else{
                            $('#tipe_asuransi_id').append('<option value="'+value.id+'">'+value.nama+'</option>');
                        }
                    });
                }
                else {
                    $('#div_tipe_asuransi').attr('hidden', true);
                    $('#tipe_asuransi_id').empty();
                    $('#tipe_asuransi_id').val('');
                }
            }
        });
    }

    $('#modal-hapus-data-pasien-asuransi').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var asuransi = button.data('asuransi')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus data pasien ' + nama + ' dari asuransi ' + asuransi + '?')
        $('#id_asuransi').val(id)
    });

    $('#modal-hapus-data-pasien-asuransi-tanggungan').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus tanggungan ' + nama + ' dari asuransi ini?')
        $('#id_pasien_asuransi_tanggungan').val(id)
    });

    function simpanDataPasienAsuransi(){
        Swal.fire({
            title: 'Simpan Data Asuransi?',
            text: "Data asuransi akan disimpan",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya, simpan data asuransi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btn-save-data-pasien-asuransi').html('<i class="fas fa-spinner fa-spin"></i>');
                $('#btn-save-data-pasien-asuransi').attr('disabled', true);
                $('#form_edit_data_asuransi').submit();
            }
        });
    }

    function aktifkaAsuransi(){
        Swal.fire({
            title: 'Aktifkan Asuransi Pasien?',
            text: "Asuransi akan diaktifkan dan dapat digunakan untuk pasien",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya, aktifkan asuransi pasien!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btn-aktifkan-data-pasien-asuransi').html('<i class="fas fa-spinner fa-spin"></i>');
                $('#btn-aktifkan-data-pasien-asuransi').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "/pasien-asuransi/aktifkan",
                    data: {
                        id: '{{$data->id}}',
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: "Asuransi berhasil diaktifkan dan dapat digunakan untuk pasien",
                            icon: 'success',
                            confirmButtonColor: '#007bff',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                });
            }
        });
    }

    function nonaktifkaAsuransi(){
        Swal.fire({
            title: 'Nonaktifkan Asuransi Pasien?',
            text: "Asuransi akan dinonaktifkan dan tidak dapat digunakan untuk pasien",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya, nonaktifkan asuransi pasien!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btn-nonaktifkan-data-pasien-asuransi').html('<i class="fas fa-spinner fa-spin"></i>');
                $('#btn-nonaktifkan-data-pasien-asuransi').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "/pasien-asuransi/nonaktifkan",
                    data: {
                        id: '{{$data->id}}',
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: "Asuransi berhasil dinonaktifkan dan tidak dapat digunakan untuk pasien",
                            icon: 'success',
                            confirmButtonColor: '#007bff',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                });
            }
        });
    }

    function isLimitChange(){
        if($('#is_limit').is(':checked')){
            $('#div_limit').show();
            $('#limit').attr('required', true);
        }else{
            $('#div_limit').hide();
            $('#limit').removeAttr('required');
            $('#limit').val('');
        }
    }
</script>
@if(session('errors'))
    <script>
        $('#modal-tambah-tanggungan').modal('show');
    </script>
@endif
@endsection
