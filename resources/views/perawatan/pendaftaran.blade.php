@extends('layout')
@section('style')
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
{{-- <div --}}
@if($sub_menu_slug == 'pendaftaran')
<div class="row">
    <div class="col-md-4 col-sm-12 col-xs-12">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-pendaftaran">
            Tambah Pendaftaran Baru
        </button>
    </div>
</div><br>
@endif
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data {{$title}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table_user" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">No.</th>
                            <th class="text-center" style="width: 10%">No. Pendaftaran</th>
                            @if($sub_menu_slug == 'antre-poli')
                            <th class="text-center">No. Antrean</th>
                            @endif
                            <th class="text-center">Pasien</th>
                            <th class="text-center">Poli</th>
                            <th class="text-center">Dokter</th>
                            <th class="text-center" style="width: 15%">Tanggal Pendaftaran</th>
                            @if($sub_menu_slug == 'riwayat-perawatan')
                            <th class="text-center">Tanggal Periksa</th>
                            @endif
                            @if($sub_menu_slug == 'antre-poli' || $sub_menu_slug == 'riwayat-perawatan')
                            <th class="text-center">Status</th>
                            @endif
                            <th class="text-center" style="width: 10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $daftar)
                            <tr class="tr-emr">
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td class="text-center">{{$daftar->no_pendaftaran}}</td>
                                @if($sub_menu_slug == 'antre-poli')
                                <td class="text-center">{{$daftar->no_antrian}}</td>
                                @endif
                                <td>{{$daftar->nama_pasien}}</td>
                                <td>{{$daftar->nama_poli}}</td>
                                <td>{{$daftar->nama_dokter}}</td>
                                <td class="text-center">{{date('d M Y', strtotime($daftar->tanggal_pendaftaran))}}</td>
                                @if($sub_menu_slug == 'riwayat-perawatan')
                                <td class="text-center">{{date('d M Y', strtotime($daftar->tgl_periksa))}}</td>
                                @endif
                                @if($sub_menu_slug == 'antre-poli' || $sub_menu_slug == 'riwayat-perawatan')
                                <td class="text-center">
                                    @if($daftar->status == 'antri')
                                    <span class="badge badge-warning">Antre</span>
                                    @elseif($daftar->status == 'diperiksa')
                                    <span class="badge badge-success">Diperiksa</span>
                                    @elseif($daftar->status == 'selesai')
                                    <span class="badge badge-info">Selesai</span>
                                    @elseif($daftar->status == 'batal')
                                    <span class="badge badge-danger">Batal</span>
                                    @endif
                                @endif
                                <td class="text-center">
                                    <a href="{{url('/rawat-jalan/detail/'.$daftar->id)}}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus" data-id="{{$daftar->id}}" data-nama="{{$daftar->nama_pasien}}">
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

<div class="modal fade" id="modal-pendaftaran">
    <div class="modal-dialog modal-lg">
        <form  method="POST" action="/rawat-jalan/pendaftaran/tambah" id="form-pendaftaran-rawat-jalan">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Pendaftaran Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="pasien-baru" name="pasien_baru" onclick="pasienBaru()">
                                <input type="hidden" name="is_pasien_baru" id="is_pasien_baru" value="0">
                                <label class="custom-control-label" for="pasien-baru">Pasien Baru</label>
                            </div>
                        </div>
                        <div class="form-group" hidden id="form-pasien-baru">
                            <label class="required" for="nama_pasien">Nama Pasien</label>
                            <input type="text" class="form-control form-control-border" id="nama_pasien" placeholder="Nama Pasien" name="nama_pasien">
                        </div>
                        <div class="form-group" hidden id="form-no-identitas">
                            <label class="required" for="no_identitas">No. Identitas</label>
                            <input type="text" class="form-control form-control-border" id="no_identitas" placeholder="No. Identitas (KTP)" name="no_identitas">
                        </div>
                        <div class="form-group" hidden id="form-jenis-kelamin">
                            <label class="required" for="jenis_kelamin">Jenis Kelamin</label>
                            <select class="custom-select form-control-border" id="jenis_kelamin" name="jenis_kelamin">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group" id="form-pasien-lama">
                            <label class="required" for="pasien">Pilih Pasien</label>
                            <select class="custom-select form-control-border select2" id="pasien" name="pasien" required style="width: 100%;">
                                <option value="" selected>Pilih Pasien</option>
                                @foreach($pasien as $pas)
                                <option value="{{$pas->id}}">{{$pas->nama_pasien}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="form-use-asuransi">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="use_asuransi" name="use_asuransi" onclick="useAsuransi()">
                                <input type="hidden" name="is_use_asuransi" id="is_use_asuransi" value="0">
                                <label class="custom-control-label" for="use_asuransi">Gunakan Asuransi</label>
                            </div>
                        </div>
                        <div class="form-group" hidden id="form-asuransi">
                            <label class="required" for="pasien_asuransi_id">Pilih Asuransi</label>
                            <select class="custom-select form-control-border select2" id="pasien_asuransi_id" name="pasien_asuransi_id" style="width: 100%;">
                                <option value="" selected>Pilih Asuransi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required" for="tanggal_periksa">Tanggal Periksa</label>
                            <input type="date" class="form-control form-control-border" id="tanggal_periksa" placeholder="Tanggal Periksa" name="tanggal_periksa" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                <label class="required" for="pilih-poli">Poli</label>
                                <select class="custom-select form-control-border select2" id="pilih-poli" name="pilih_poli" required style="width: 100%;">
                                    <option value="" selected>Pilih Poli</option>
                                    @foreach($poli as $pol)
                                        <option value="{{$pol->id}}">{{$pol->nama_poli}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                <label class="required" for="pilih-dokter">Jadwal Dokter</label>
                                <select class="custom-select form-control-border select2" id="pilih-dokter" name="pilih_dokter" required style="width: 100%;">
                                    <option value="" selected>Pilih Dokter</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-simpan" onclick="checkRequiredInputBeforeSubmit()">Simpan</button>
                    <button type="submit" class="btn btn-primary" hidden id="btn-simpan-pasien-baru">Simpan</button>
                </div>
            </div>
        </form>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-hapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                <form id="form_delete_pasien" method="POST" action="/rawat-jalan/pendaftaran/delete">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <input type="method" name="_method" value="DELETE" id="_method" hidden>
                    <button type="submit" class="btn btn-outline-light">Delete</button>
                </form>
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
        $("#table_user").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#table_user_wrapper .col-md-6:eq(0)');
    });
    function pasienBaru(){
        if($('#pasien-baru').prop('checked') == true){
            $('#is_pasien_baru').val(1);
            $('#form-pasien-baru').removeAttr('hidden');
            $('#nama_pasien').attr('required', true);
            $('#form-no-identitas').removeAttr('hidden');
            $('#no_identitas').attr('required', true);
            $('#form-jenis-kelamin').removeAttr('hidden');
            $('#jenis_kelamin').attr('required', true);
            $('#form-pasien-lama').attr('hidden', true);
            $('#pasien').removeAttr('required');
            $('#pasien').val('');
            $('#form-use-asuransi').attr('hidden', true);
        }else{
            $('#is_pasien_baru').val(0);
            $('#form-pasien-baru').attr('hidden', true);
            $('#nama_pasien').removeAttr('required');
            $('#nama_pasien').val('');
            $('#form-no-identitas').attr('hidden', true);
            $('#no_identitas').removeAttr('required');
            $('#no_identitas').val('');
            $('#form-jenis-kelamin').attr('hidden', true);
            $('#jenis_kelamin').removeAttr('required');
            $('#jenis_kelamin').val('');
            $('#form-pasien-lama').removeAttr('hidden');
            $('#pasien').attr('required', true);
            $('#form-use-asuransi').removeAttr('hidden');
        }
    }
    function useAsuransi(){
        if($('#use_asuransi').prop('checked') == true){
            $('#is_use_asuransi').val(1);
            $('#form-asuransi').removeAttr('hidden');
            $('#pasien_asuransi_id').attr('required', true);
            var id_pasien = $('#pasien').val();
            $.ajax({
                url: "{{url('/pasien-asuransi/list-asuransi-pasien')}}",
                method: "GET",
                data: {id_pasien:id_pasien},
                success:function(data){
                    console.log(data);
                    if (data.length == 0) {
                        Swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 5000
                        }).fire({
                            width: 600,
                            icon: 'error',
                            title: 'Pasien ini tidak memiliki asuransi.'
                        });
                        $('#use_asuransi').prop('checked', false);
                        $('#is_use_asuransi').val(0);
                        $('#form-asuransi').attr('hidden', true);
                        $('#pasien_asuransi_id').removeAttr('required');
                        $('#pasien_asuransi_id').val('');
                        $('#pasien_asuransi_id').empty();
                        return;
                    } else {
                        $('#pasien_asuransi_id').empty();
                        $('#pasien_asuransi_id').append('<option value="" selected>Pilih Asuransi</option>');
                        for(var i=0;i<data.length;i++){
                            $('#pasien_asuransi_id').append('<option value="'+data[i].id+'">'+data[i].nama_asuransi+'</option>');
                        }
                    }
                }
            });
        }else{
            $('#is_use_asuransi').val(0);
            $('#form-asuransi').attr('hidden', true);
            $('#pasien_asuransi_id').removeAttr('required');
            $('#pasien_asuransi_id').val('');
            $('#pasien_asuransi_id').empty();
        }
    }
    function checkRequiredInputBeforeSubmit(){
        // find required input
        var requiredInput = $('#form-pendaftaran-rawat-jalan').find('input[required]');
        var requiredSelect = $('#form-pendaftaran-rawat-jalan').find('select[required]');
        var requiredTextarea = $('#form-pendaftaran-rawat-jalan').find('textarea[required]');
        var required = requiredInput.add(requiredSelect).add(requiredTextarea);
        var error = false;
        // check if empty
        required.each(function(){
            if($(this).val() == ''){
                error = true;
            }
        });
        if(error == true){
            Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3000
            }).fire({
                icon: 'error',
                width: 450,
                title: 'Harap isi semua input yang bertanda bintang'
            });
        }else{
            simpanPendaftaran();
        }
    }
    function simpanPendaftaran(){
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data akan disimpan ke dalam database",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btn-simpan-pasien-baru').click();
            }
        })
    }
</script>
<script>
    $(document).ready(function(){
        $('#modal-hapus').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var nama = button.data('nama')
            var modal = $(this)
            modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus pendafataran atas nama '+nama+'?')
            $('#id').val(id)
        });
    });
</script>
<script>
    function changeSelect(){
        if($('#tanggal_periksa').val() == '' || $('#pilih-poli').val() == ''){
            $('#pilih-dokter').empty();
            $('#pilih-dokter').append('<option value="" selected>Pilih Dokter</option>');
            return;
        }
        $('#pilih-dokter').empty();
        var id = $('#pilih-poli').val();
        var tanggal = $('#tanggal_periksa').val();
        console.log('id poli: ' + id);
        console.log(tanggal);
        $.ajax({
            url: "{{url('/get_dokter_poliklinik')}}",
            method: "GET",
            data: {id_poliklinik:id, tanggal_periksa:tanggal},
            success:function(data){
                console.log(data);
                if (data.length == 0) {
                    Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 5000
                    }).fire({
                        width: 600,
                        icon: 'error',
                        title: 'Tidak ada jadwal dokter praktek di poliklinik '+$('#pilih-poli option:selected').text()+' saat ini.'
                    });
                } else {
                    $('#pilih-dokter').append('<option value="" selected>Pilih Dokter</option>');
                    for(var i=0;i<data.length;i++){
                        if (data[i].id != null) {
                            $('#pilih-dokter').append('<option value="'+data[i].dokter_id+','+data[i].dokter_poli_id+','+data[i].id+'">'+data[i].nama_dokter+' ('+data[i].jam_mulai+' - '+data[i].jam_selesai+')</option>');
                        } else {
                            $('#pilih-dokter').append('<option value="'+data[i].dokter_id+','+data[i].dokter_poli_id+'">'+data[i].nama_dokter+'</option>');
                        }
                        // if there is id jadwal from data
                    }
                }
            }
        });
    }
    $('#pilih-poli').change(function(){
        changeSelect();
    });
    $('#tanggal_periksa').change(function(){
        changeSelect();
    });
</script>
@endsection
