@extends('layout')
@section('style')
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
<form method="POST" action="/asuransi/edit" id="form_edit_data_asuransi">
@csrf
<input type="hidden" name="id" value="{{$data->id}}">
<button type="button" class="btn btn-danger" id="btn-hapus-data-asuransi" data-toggle="modal" data-target="#modal-hapus-data-asuransi" data-id="{{$data->id}}" data-nama="{{$data->nama_asuransi}}">
    <i class="fas fa-trash"></i>
</button>
<button type="button" class="btn btn-info" id="btn-edit-data-asuransi">
    Edit Asuransi
</button>
<button type="button" class="btn btn-danger" id="btn-cancel-edit-data-asuransi" style="display: none;">
    Cancel
</button>
<button type="button" class="btn btn-success" id="btn-save-data-asuransi" style="display: none;" onclick="simpanDataAsuransi()">
    Simpan Asuransi
</button>
@if($data->status == 'aktif')
    <button type="button" class="btn btn-warning" id="btn-nonaktifkan-data-asuransi" onclick="nonaktifkaAsuransi()">
        Nonaktifkan Asuransi
    </button>
@else
    <button type="button" class="btn btn-success" id="btn-aktifkan-data-asuransi" onclick="aktifkaAsuransi()">
        Aktifkan Asuransi
    </button>
@endif
<br><br>
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="kode_asuransi">Kode Asuransi</label>
                    <input type="text" id="kode_asuransi" class="form-control @error('kode_asuransi') is-invalid @enderror" value="{{$data->kode_asuransi}}" readonly name="kode_asuransi" maxlength="50" required>
                    @error('kode_asuransi')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="required" for="nama_asuransi">Nama Asuransi</label>
                    <input type="text" id="nama_asuransi" class="form-control @error('nama_asuransi') is-invalid @enderror" value="{{$data->nama_asuransi}}" readonly name="nama_asuransi" maxlength="50" required>
                    @error('nama_asuransi')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" readonly name="keterangan" maxlength="50" required>{{$data->keterangan}}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="emr-tabs-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="emr-tabs-tipe-tab" data-toggle="pill" href="#emr-tabs-tipe" role="tab" aria-controls="emr-tabs-tipe" aria-selected="true">Tipe Asuransi</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="emr-tabs-tabContent">
                    <div class="tab-pane fade show active" id="emr-tabs-tipe" role="tabpanel" aria-labelledby="emr-tabs-tipe-tab">
                        <button type="button" class="btn btn-primary" id="btn-tambah-tipe" data-toggle="modal" data-target="#modal-tambah-tipe" style="float: right;">
                            Tambah Tipe Asuransi
                        </button><br><br>
                        <div>
                            <table id="tabel_tipe" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Deskripsi</th>
                                        <th class="text-center">Tanggungan</th>
                                        <th class="text-center" style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_line as $line)
                                        <tr>
                                            <td class="text-center" style="text-transform: capitalize;">{{$line->nama}}</td>
                                            <td class="text-center">{{$line->deskripsi}}</td>
                                            <td>
                                                {{-- json --}}
                                                @foreach(json_decode($line->tanggungan) as $tanggungan)
                                                    <span class="badge badge-success" style="text-transform: capitalize;">{{$tanggungan->value == 'all' ? 'Semua' : $tanggungan->value}}</span>
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-data-asuransi-tipe" data-id="{{$line->id}}" data-nama="{{$line->nama}}" date-id-asuransi="{{$data->id}}">
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
<div class="modal fade" id="modal-tambah-tipe" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form  method="POST" action="/asuransi/tambah-tipe">
            @csrf
            <input type="hidden" name="asuransi_id" value="{{$data->id}}">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Tipe Asuransi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="required" for="nama_asuransi_tipe">Nama Tipe Asuransi</label>
                        <input type="text" id="nama_asuransi_tipe" class="form-control @error('nama_asuransi_tipe') is-invalid @enderror" value="{{old('nama_asuransi_tipe')}}" name="nama_asuransi_tipe" maxlength="50" required>
                        @error('nama_asuransi_tipe')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group" id="form-deskripsi">
                        <label class="required" for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" maxlength="50" required>{{old('deskripsi')}}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group" id="form-tanggungan">
                        <label class="required" for="tanggungan">Tanggungan</label>
                        <select class="form-control select2 @error('tanggungan') is-invalid @enderror" id="tanggungan" name="tanggungan[]" multiple="multiple" data-placeholder="Pilih Tanggungan" style="width: 100%;" required value="{{old('tanggungan')}}" onchange="tanggunganChange()">
                            <option value="all">Semua</option>
                            <option value="perawatan">Perawatan</option>
                            <option value="obat">Obat</option>
                            <option value="tindakan">Tindakan</option>
                            <option value="administrasi">Administrasi</option>
                        </select>
                        @error('tanggungan')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
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
    <div class="modal fade" id="modal-hapus-data-asuransi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="form_delete_data_asuransi" method="POST" action="/asuransi/delete">
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
<div>
    <div class="modal fade" id="modal-hapus-data-asuransi-tipe" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="form_delete_data_asuransi_tipe" method="POST" action="/asuransi/delete-tipe">
                        @csrf
                        <input type="hidden" name="id_asuransi" id="id_asuransi">
                        <input type="hidden" name="id_asuransi_tipe" id="id_asuransi_tipe">
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
        $("#tabel_tipe").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": false, "searching": false, "paging": false, "info": false,
            "language": {
                "emptyTable": "Tidak ada data tipe asuransi"
            }
        }).buttons().container().appendTo('#tabel_tipe_wrapper .col-md-6:eq(0)');
    });

    $('#btn-edit-data-asuransi').click(function(){
        $('#btn-edit-data-asuransi').hide();
        $('#btn-hapus-data-asuransi').hide();
        $('#btn-cancel-edit-data-asuransi').show();
        $('#btn-save-data-asuransi').show();
        $('#keterangan').attr('readonly', false);
        $('#nama_asuransi').attr('readonly', false);
        $('#kode_asuransi').attr('readonly', false);
    });
    $('#btn-cancel-edit-data-asuransi').click(function(){
        location.reload();
    });

    $('#modal-hapus-data-asuransi').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus Asuransi ' + nama + '?')
        $('#id_asuransi').val(id)
    });

    $('#modal-hapus-data-asuransi-tipe').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var id_asuransi = button.data('id-asuransi')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus Tipe Asuransi ' + nama + '?')
        $('#id_asuransi_tipe').val(id)
        $('#id_asuransi').val(id_asuransi)
    });

    function simpanDataAsuransi(){
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
                $('#btn-save-data-asuransi').html('<i class="fas fa-spinner fa-spin"></i>');
                $('#btn-save-data-asuransi').attr('disabled', true);
                $('#form_edit_data_asuransi').submit();
            }
        });
    }

    function aktifkaAsuransi(){
        Swal.fire({
            title: 'Aktifkan Asuransi?',
            text: "Asuransi akan diaktifkan",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya, aktifkan asuransi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btn-aktifkan-data-asuransi').html('<i class="fas fa-spinner fa-spin"></i>');
                $('#btn-aktifkan-data-asuransi').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "/asuransi/aktifkan",
                    data: {
                        id: '{{$data->id}}',
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: "Asuransi berhasil diaktifkan",
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
            title: 'Nonaktifkan Asuransi?',
            text: "Asuransi akan dinonaktifkan",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya, nonaktifkan asuransi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btn-nonaktifkan-data-asuransi').html('<i class="fas fa-spinner fa-spin"></i>');
                $('#btn-nonaktifkan-data-asuransi').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "/asuransi/nonaktifkan",
                    data: {
                        id: '{{$data->id}}',
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: "Asuransi berhasil dinonaktifkan",
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

    function tanggunganChange(){
        var tanggungan = $('#tanggungan').val();
        if(tanggungan.includes('all')){
            // delete all option except all
            $('#tanggungan option').each(function(){
                if($(this).val() != 'all'){
                    $(this).remove();
                }
            });
        }else{
            $('#tanggungan option').each(function(){
                $(this).remove();
            });
            $('#tanggungan').append('<option value="all">Semua</option>');
            $('#tanggungan').append('<option value="perawatan">Perawatan</option>');
            $('#tanggungan').append('<option value="obat">Obat</option>');
            $('#tanggungan').append('<option value="tindakan">Tindakan</option>');
            $('#tanggungan').append('<option value="administrasi">Administrasi</option>');
        }
    }
</script>
@endsection
