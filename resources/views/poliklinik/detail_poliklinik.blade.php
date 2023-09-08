@extends('layout')
@section('style')
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
<form method="POST" action="/data-poliklinik/edit" id="form_edit_poliklinik">
@csrf
<input type="hidden" name="id" value="{{$data->id}}">
<button type="button" class="btn btn-danger" id="btn-hapus-data-poli" data-toggle="modal" data-target="#modal-hapus-data-poli" data-id="{{$data->id}}" data-nama="{{$data->nama_poli}}">
    <i class="fas fa-trash"></i>
</button>
<button type="button" class="btn btn-info" id="btn-edit-data-poli">
    Edit Data Poliklinik
</button>
<button type="button" class="btn btn-danger" id="btn-cancel-edit-data-poli" style="display: none;">
    Cancel
</button>
<button type="button" class="btn btn-success" id="btn-save-data-poli" style="display: none;" onclick="simpanDataPoli()">
    Simpan Data Poliklinik
</button>
<br><br>
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="form-group">
                    <label for="nama_poli">Nama Poliklinik</label>
                    <input type="text" id="nama_poli" class="form-control @error('nama_poli') is-invalid @enderror" value="{{$data->nama_poli}}" readonly name="nama_poli" maxlength="50">
                    @error('nama_poli')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="biaya_poli">Biaya Poliklinik</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp.</span>
                        </div>
                        <input type="text" id="biaya_poli" class="form-control @error('biaya_poli') is-invalid @enderror" value="{{$data->biaya_poli}}" readonly name="biaya_poli" maxlength="13">
                        @error('biaya_poli')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" readonly name="keterangan">{{$data->keterangan}}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-8">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="emr-tabs-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="emr-tabs-dokter-poli-tab" data-toggle="pill" href="#emr-tabs-dokter-poli" role="tab" aria-controls="emr-tabs-dokter-poli" aria-selected="true">Dokter Poli</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="emr-tabs-tabContent">
                    <div class="tab-pane fade show active" id="emr-tabs-dokter-poli" role="tabpanel" aria-labelledby="emr-tabs-dokter-poli-tab">
                        <div>
                            <table id="tabel_dokter_poli" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 55%">Nama Dokter</th>
                                        <th class="text-center" style="width: 40%">Jadwal Praktek</th>
                                        <th class="text-center" style="width: 5%"><i class="fas fa-cog"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_line as $line)
                                        <tr>
                                            <td>{{$line->name}}</td>
                                            <td>{{$line->waktu_praktek}}</td>
                                            <td class="text-center">
                                                <a href="{{url('/data-dokter-poli/detail/'.$line->id)}}" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
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

<div>
    <div class="modal fade" id="modal-hapus-data-poli" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
    $('#btn-edit-data-poli').click(function(){
        $('#btn-edit-data-poli').hide();
        $('#btn-hapus-data-poli').hide();
        $('#btn-cancel-edit-data-poli').show();
        $('#btn-save-data-poli').show();
        $('#nama_poli').removeAttr('readonly');
        $('#biaya_poli').removeAttr('readonly');
        $('#keterangan').removeAttr('readonly');
        $('#nama_poli').focus();
    });
    $('#btn-cancel-edit-data-poli').click(function(){
        location.reload();
        $('#btn-edit-data-poli').show();
        $('#btn-hapus-data-poli').show();
        $('#btn-cancel-edit-data-poli').hide();
        $('#btn-save-data-poli').hide();
        $('#nama_poli').attr('readonly', true);
        $('#biaya_poli').attr('readonly', true);
        $('#keterangan').attr('readonly', true);
    });

    $(function(){
        $("#biaya_poli").keyup(function(e){
            $(this).val(formatRupiah($(this).val()));
        });
    });

    $(document).ready(function(){
        $('#biaya_poli').val(formatRupiah($('#biaya_poli').val()));
    });

    $(function () {
        $("#tabel_dokter_poli").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tabel_dokter_poli_wrapper .col-md-6:eq(0)');
    });
    $('#modal-hapus-data-poli').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus Poliklinik ' + nama + '?')
        $('#id_poliklinik').val(id)
    });

    function simpanDataPoli(){
        Swal.fire({
            title: 'Simpan Data Poliklinik?',
            text: "Data Poliklinik akan disimpan",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btn-save-data-poli').html('<i class="fas fa-spinner fa-spin"></i> Simpan');
                $('#btn-save-data-poli').attr('disabled', true);
                $('#form_edit_poliklinik').submit();
            }
        });
    }
</script>
@endsection
