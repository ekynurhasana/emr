@extends('layout')
@section('style')
@endsection
@section('content')
<form method="POST" action="/data-obat/edit" id="form_edit_data_obat">
@csrf
<input type="hidden" name="id" value="{{$data->id}}">
@if(in_array(Auth::user()->role, ['superadmin', 'admin', 'apoteker']))
    <button type="button" class="btn btn-danger" id="btn-hapus-data-obat" data-toggle="modal" data-target="#modal-hapus-data-obat" data-id="{{$data->id}}" data-nama="{{$data->nama_obat}}">
        <i class="fas fa-trash"></i>
    </button>
    <button type="button" class="btn btn-info" id="btn-edit-data-obat">
        Edit Data Obat
    </button>
    <button type="button" class="btn btn-danger" id="btn-cancel-edit-data-obat" style="display: none;">
        Cancel
    </button>
    <button type="button" class="btn btn-success" id="btn-save-data-obat" style="display: none;" onclick="simpanDataObat()">
        Simpan Data Obat
    </button>
    <br><br>
@endif
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="nama_obat">Nama Obat</label>
                    <input type="text" id="nama_obat" class="form-control @error('nama_obat') is-invalid @enderror" value="{{$data->nama_obat}}" readonly name="nama_obat" maxlength="50" required>
                    @error('nama_obat')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="required" for="jenis_obat">Jenis Obat</label>
                    <select id="jenis_obat" class="form-control @error('jenis_obat') is-invalid @enderror" disabled name="jenis_obat" required>
                        <option value="Kapsul" @if($data->jenis_obat == 'Kapsul') selected @endif>Kapsul</option>
                        <option value="Tablet" @if($data->jenis_obat == 'Tablet') selected @endif>Tablet</option>
                        <option value="Sirup" @if($data->jenis_obat == 'Sirup') selected @endif>Sirup</option>
                        <option value="Salep" @if($data->jenis_obat == 'Salep') selected @endif>Salep</option>
                        <option value="Puyer" @if($data->jenis_obat == 'Puyer') selected @endif>Puyer</option>
                        <option value="Lainnya" @if($data->jenis_obat == 'Lainnya') selected @endif>Lainnya</option>
                    </select>
                    @error('jenis_obat')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="required" for="harga_obat">Harga Obat</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp.</span>
                        </div>
                        <input type="text" id="harga_obat" class="form-control @error('harga_obat') is-invalid @enderror" value="{{$data->harga_obat}}" readonly name="harga_obat" maxlength="50" required onkeypress="return hanyaAngka(event)">
                        @error('harga_obat')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="required" for="stok_obat">Stok Obat</label>
                    <input type="text" id="stok_obat" class="form-control @error('stok_obat') is-invalid @enderror" value="{{$data->stok_obat}}" readonly name="stok_obat" maxlength="50" required onkeypress="return hanyaAngka(event)">
                    @error('stok_obat')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<div>
    <div class="modal fade" id="modal-hapus-data-obat" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="form_delete_data_obat" method="POST" action="/data-obat/delete">
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
<script>
    $('#btn-edit-data-obat').click(function(){
        $('#btn-edit-data-obat').hide();
        $('#btn-hapus-data-obat').hide();
        $('#btn-cancel-edit-data-obat').show();
        $('#btn-save-data-obat').show();
        $('#nama_obat').attr('readonly', false);
        $('#jenis_obat').attr('disabled', false);
        $('#harga_obat').attr('readonly', false);
        $('#stok_obat').attr('readonly', false);
    });
    $('#btn-cancel-edit-data-obat').click(function(){
        location.reload();
        $('#btn-edit-data-obat').show();
        $('#btn-hapus-data-obat').show();
        $('#btn-cancel-edit-data-obat').hide();
        $('#btn-save-data-obat').hide();
        $('#nama_obat').attr('readonly', true);
        $('#jenis_obat').attr('disabled', true);
        $('#harga_obat').attr('readonly', true);
        $('#stok_obat').attr('readonly', true);
    });

    $(function(){
        $("#harga_obat").keyup(function(e){
            $(this).val(formatRupiah($(this).val()));
        });
    });

    $(document).ready(function(){
        $('#harga_obat').val(formatRupiah($('#harga_obat').val()));
    });

    $('#modal-hapus-data-obat').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus Obat ' + nama + '?')
        $('#id_obat').val(id)
    });

    function simpanDataObat(){
        Swal.fire({
            title: 'Simpan Data Obat?',
            text: "Data obat akan disimpan",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya, simpan data obat!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btn-save-data-obat').html('<i class="fas fa-spinner fa-spin"></i>');
                $('#btn-save-data-obat').attr('disabled', true);
                $('#form_edit_data_obat').submit();
            }
        });
    }
</script>
@endsection
