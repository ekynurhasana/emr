@extends('layout')
@section('style')
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12 col-md-7 col-lg-7">
        <form method="POST" action="/data-dokter-poli/update" id="form_edit_data_dokter_poli">
        @csrf
        <input type="hidden" name="id" value="{{$data->id}}">
        @if(in_array(Auth::user()->role, ['super-admin', 'admin']))
            <button type="button" class="btn btn-danger" id="btn-hapus-data" data-toggle="modal" data-target="#modal-hapus-data" data-id="{{$data->id}}" data-poli="{{$data->nama_poli}}" data-dokter="{{$data->nama_dokter}}">
                <i class="fas fa-trash"></i>
            </button>
        @endif
        @if(Auth::user()->id == $data->dokter_id or in_array(Auth::user()->role, ['super-admin']))
            @if($data->status == 'tutup' && $is_buka)
                <button type="button" class="btn btn-success" id="btn-buka-jadwal-praktek" onclick="updateStatusPraktek()">
                    Buka Praktek
                </button>
            @endif
            @if ($data->status == 'buka')
                <button type="button" class="btn btn-danger" id="btn-tutup-jadwal-praktek" onclick="updateStatusPraktek()">
                    Tutup Praktek
                </button>
            @endif
        @endif
        @if(in_array(Auth::user()->role, ['super-admin', 'admin']))
            <button type="button" class="btn btn-info" id="btn-edit-data">
                Edit Data Dokter Poliklinik
            </button>
            <button type="button" class="btn btn-danger" id="btn-cancel-edit-data" style="display: none;">
                Cancel
            </button>
            <button type="button" class="btn btn-success" id="btn-save-data" style="display: none;" onclick="simpanDataDokterPoli()">
                Simpan Data Dokter Poliklinik
            </button>
        @endif
        <br><br>
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="dokter_id">Dokter</label>
                    <select id="dokter_id" class="form-control select2 @error('dokter_id') is-invalid @enderror" name="dokter_id" disabled required>
                        <option value="">-- Pilih Dokter --</option>
                        @foreach($data_dokter as $d)
                            <option value="{{$d->id}}" {{$d->id == $data->dokter_id ? 'selected' : ''}}>{{$d->name}}</option>
                        @endforeach
                    </select>
                    @error('dokter_id')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="required" for="poli_id">Poliklinik</label>
                    <select id="poli_id" class="form-control select2 @error('poli_id') is-invalid @enderror" name="poli_id" disabled required>
                        <option value="">-- Pilih Poliklinik --</option>
                        @foreach($data_poli as $p)
                            <option value="{{$p->id}}" {{$p->id == $data->poli_id ? 'selected' : ''}}>{{$p->nama_poli}}</option>
                        @endforeach
                    </select>
                    @error('poli_id')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="required" for="biaya_tambahan">Biaya Tambahan</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp.</span>
                        </div>
                        <input type="text" id="biaya_tambahan" class="form-control @error('biaya_tambahan') is-invalid @enderror" value="{{$data->biaya_tambahan}}" readonly name="biaya_tambahan" onkeypress="return hanyaAngka(event)" maxlength="13" required>
                        @error('biaya_tambahan')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
    <div class="col-sm-12 col-md-5 col-lg-5">
        <div class="card card-info card-outline card-outline-tabs" style="margin-top: 61px;">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="emr-tabs-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="emr-tabs-jadwal-praktek-tab" data-toggle="pill" href="#emr-tabs-jadwal-praktek" role="tab" aria-controls="emr-tabs-jadwal-praktek" aria-selected="true">Jadwal Praktek</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="emr-tabs-tabContent">
                    <div class="tab-pane fade show active" id="emr-tabs-jadwal-praktek" role="tabpanel" aria-labelledby="emr-tabs-jadwal-praktek-tab">
                        @if(in_array(Auth::user()->role, ['super-admin', 'admin']))
                            <button type="button" class="btn btn-primary" id="btn-tambah-jadwal-praktek" data-toggle="modal" data-target="#modal-tambah-jadwal-praktek" style="float: right;">
                                Tambah Jadwal Praktek
                            </button><br><br>
                        @endif
                        <div>
                            <table id="tabel_dokter_poli" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 25%">Hari</th>
                                        <th class="text-center" style="width: 35%">Jam Mulai Praktek</th>
                                        <th class="text-center" style="width: 35%">Jam Selesai Praktek</th>
                                        <th class="text-center" style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_line as $line)
                                        <tr>
                                            <td class="text-center" style="text-transform: capitalize;">{{$line->hari}}</td>
                                            <td class="text-center">{{$line->jam_mulai}}</td>
                                            <td class="text-center">{{$line->jam_selesai}}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm btn-hapus-jadwal-praktek" data-toggle="modal" data-target="#modal-hapus-jadwal-praktek" data-id="{{$line->id}}" data-hari="{{$line->hari}}" data-mulai="{{$line->jam_mulai}}" data-selesai="{{$line->jam_selesai}}" data-dokpoli="{{$data->id}}">
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

<div>
    <div class="modal fade" id="modal-tambah-jadwal-praktek" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form  method="POST" action="/data-dokter-poli/tambah-jadwal">
                @csrf
                <input type="hidden" name="dokter_poli_id" value="{{$data->id}}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Jadwal Praktek</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="form-group" id="form-hari">
                                <label class="required" for="hari">Pilih Hari Praktek</label>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="hari" value="senin" id="senin" {{old('hari') == 'senin' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="senin">
                                                Senin
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="hari" value="selasa" id="selasa" {{old('hari') == 'selasa' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="selasa">
                                                Selasa
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="hari" value="rabu" id="rabu" {{old('hari') == 'rabu' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="rabu">
                                                Rabu
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="hari" value="kamis" id="kamis" {{old('hari') == 'kamis' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="kamis">
                                                Kamis
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="hari" value="jumat" id="jumat" {{old('hari') == 'jumat' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="jumat">
                                                Jumat
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="hari" value="sabtu" id="sabtu" {{old('hari') == 'sabtu' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="sabtu">
                                                Sabtu
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @if($errors->has('hari'))
                                    <div class="text-danger">
                                        {{ $errors->first('hari')}}
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group" id="form-jadwal-mulai">
                                        <label class="required" for="jam_mulai">Jadwal Mulai</label>
                                        <input type="time" id="jam_mulai" name="jam_mulai" required value="{{old('jam_mulai')}}"  class="form-control form-control-border @error('jam_mulai') is-invalid @enderror">
                                        @if($errors->has('jam_mulai'))
                                            <div class="text-danger">
                                                {{ $errors->first('jam_mulai')}}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group" id="form-jadwal-selesai">
                                        <label class="required" for="jam_selesai">Jadwal Selesai</label>
                                        <input type="time" id="jam_selesai" name="jam_selesai" required value="{{old('jam_selesai')}}" class="form-control form-control-border @error('jam_selesai') is-invalid @enderror">
                                        @if($errors->has('jam_selesai'))
                                            <div class="text-danger">
                                                {{ $errors->first('jam_selesai')}}
                                            </div>
                                        @endif
                                    </div>
                                </div>
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
</div>

<div>
    <div class="modal fade" id="modal-hapus-data" role="dialog" aria-hidden="true">
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
                    <form id="form_delete_poliklinik" method="POST" action="/data-dokter-poli/delete">
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

<div>
    <div class="modal fade" id="modal-hapus-jadwal-praktek" role="dialog" aria-hidden="true">
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
                    <form id="form_delete_poliklinik" method="POST" action="/data-dokter-poli/delete">
                        @csrf
                        <input type="hidden" name="id_jadwal_praktek" id="id_jadwal_praktek">
                        <input type="hidden" name="dokter_poli_id" id="dokter_poli_id">
                        <input type="hidden" name="type" id="type" value="jadwal_praktek">
                        <input type="method" name="_method" value="DELETE" id="_method" hidden>
                        <button type="submit" class="btn btn-outline-light">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="form_status_praktek" method="POST" action="/data-dokter-poli/update-status">
    @csrf
    <input type="hidden" name="id_dokter_poli" value="{{$data->id}}">
    <input type="hidden" name="status" id="status" value="{{$data->status}}">
    <input type="method" name="_method" value="PUT" id="_method" hidden>
</form>
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
        $('#biaya_tambahan').val(formatRupiah($('#biaya_tambahan').val()));
    });
</script>
@if(Auth::user()->id == $data->dokter_id or in_array(Auth::user()->role, ['super-admin']))
<script>
    $(document).ready(function(){
        $('#biaya_tambahan').val(formatRupiah($('#biaya_tambahan').val()));
        @if ($is_buka && $data->status == 'tutup')
            Swal.fire({
                title: 'Waktunya Buka Praktek',
                text: "Sekarang merupakan waktu praktek dokter, apakah anda ingin membuka praktek?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Buka Praktek',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#status').val('buka');
                    $('#btn-buka-jadwal-praktek').html('<i class="fas fa-spinner fa-spin"></i> Buka Praktek');
                    $('#btn-buka-jadwal-praktek').attr('disabled', true);
                    $('#form_status_praktek').submit();
                }
            })
        @endif
    });
</script>
@endif
<script>
    $('#btn-edit-data').click(function(){
        $('#btn-edit-data').hide();
        $('#btn-hapus-data').hide();
        $('#btn-cancel-edit-data').show();
        $('#btn-save-data').show();
        $('#poli_id').removeAttr('disabled');
        $('#dokter_id').removeAttr('disabled');
        $('#biaya_tambahan').removeAttr('readonly');
        $('#keterangan').removeAttr('readonly');
        $('#poli_id').focus();
    });
    $('#btn-cancel-edit-data').click(function(){
        location.reload();
        $('#btn-edit-data').show();
        $('#btn-hapus-data').show();
        $('#btn-cancel-edit-data').hide();
        $('#btn-save-data').hide();
        $('#poli_id').attr('disabled', true);
        $('#dokter_id').attr('disabled', true);
        $('#biaya_tambahan').attr('readonly', true);
        $('#keterangan').attr('readonly', true);
    });

    // #biaya_tambahan on keypress change to rupiah format
    $(function(){
        $("#biaya_tambahan").keyup(function(e){
            $(this).val(formatRupiah($(this).val()));
        });
    });
    function simpanDataDokterPoli(){
        Swal.fire({
            title: 'Simpan Data Dokter Poliklinik?',
            text: "Data dokter poliklinik akan disimpan",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btn-save-data').html('<i class="fas fa-spinner fa-spin"></i> Simpan Data Dokter Poliklinik');
                $('#btn-save-data').attr('disabled', true);
                $('#form_edit_data_dokter_poli').submit();
            }
        })
    }

    function updateStatusPraktek(){
        var message = '';
        if($('#status').val() == 'tutup'){
            message = 'Apakah anda yakin ingin membuka praktek?';
        }else if($('#status').val() == 'buka'){
            message = 'Apakah anda yakin ingin menutup praktek?';
        }
        Swal.fire({
            title: message,
            text: "Status praktek akan diupdate",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Update',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                if($('#status').val() == 'tutup'){
                    $('#status').val('buka');
                    $('#btn-buka-jadwal-praktek').html('<i class="fas fa-spinner fa-spin"></i> Buka Praktek');
                    $('#btn-buka-jadwal-praktek').attr('disabled', true);
                }else if($('#status').val() == 'buka'){
                    $('#status').val('tutup');
                    $('#btn-tutup-jadwal-praktek').html('<i class="fas fa-spinner fa-spin"></i> Tutup Praktek');
                    $('#btn-tutup-jadwal-praktek').attr('disabled', true);
                }
                $('#form_status_praktek').submit();
            }
        })
    }

    $(function () {
        $("#tabel_dokter_poli").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": false, "searching": false, "paging": false, "info": false,
            "language": {
                "emptyTable": "Jadwal Praktek belum diatur"
            }
        }).buttons().container().appendTo('#tabel_dokter_poli_wrapper .col-md-6:eq(0)');
    });
    $('#modal-hapus-data').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var poli = button.data('poli')
        var dokter = button.data('dokter')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus Jadwal ' + dokter + ' pada Poliklinik ' + poli + '?')
        $('#id_dokter_poli').val(id)
    });
    $('#modal-hapus-jadwal-praktek').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var dokter_poli_id = button.data('dokpoli')
        var hari = button.data('hari')
        var jam_mulai = button.data('mulai')
        var jam_selesai = button.data('selesai')
        var modal = $(this)
        modal.find('.modal-body #modal-body-delete-jadwal').text('Apakah anda yakin ingin menghapus jadwal praktek pada hari ' + hari + ' dari jam ' + jam_mulai + ' sampai ' + jam_selesai + '?')
        $('#id_jadwal_praktek').val(id)
        $('#dokter_poli_id').val(dokter_poli_id)
    });
</script>
@if ($errors->has('hari') || $errors->has('jam_mulai') || $errors->has('jam_selesai'))
<script>
    $(document).ready(function() {
        $('#modal-tambah-jadwal-praktek').modal('show');
    });
</script>
@endif
@endsection
