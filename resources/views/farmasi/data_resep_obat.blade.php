@extends('layout')
@section('style')
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Resep Obat Pasien</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table_resep_obat" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">No.</th>
                            <th class="text-center">No. Resep</th>
                            <th class="text-center">No. Registrasi</th>
                            <th class="text-center">No. Rekam Medis</th>
                            <th class="text-center">Nama Pasien</th>
                            <th class="text-center">Poliklinik Asal</th>
                            <th class="text-center">Dokter</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $ro)
                            <tr class="tr-emr">
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td class="text-center">{{$ro->no_resep}}</td>
                                <td class="text-center">{{$ro->no_registrasi}}</td>
                                <td class="text-center">{{$ro->no_erm}}</td>
                                <td>{{$ro->nama_pasien}}</td>
                                <td class="text-center">{{$ro->nama_poli}}</td>
                                <td>{{$ro->nama_dokter}}</td>
                                <td class="text-center">
                                    @if ($ro->status == 'draft')
                                        <span class="badge badge-warning">Baru</span>
                                    @elseif ($ro->status == 'diproses')
                                        <span class="badge badge-primary">Diproses</span>
                                    @elseif ($ro->status == 'selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @endif
                                <td class="text-center">
                                    <a href="{{url('/resep-obat/detail/'.$ro->no_resep)}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-resep-obat" data-id="{{$ro->id}}" data-no_resep="{{$ro->no_resep}}">
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

<div>
    <div class="modal fade" id="modal-hapus-resep-obat" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="form-hapus-resep" method="POST" action="/resep-obat/delete">
                        @csrf
                        <input type="hidden" name="id_resep" id="id_resep" value="">
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
        $("#table_resep_obat").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#table_resep_obat_wrapper .col-md-6:eq(0)');
    });

    $('#modal-hapus-resep-obat').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var resep = button.data('no_resep')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus resep obat dengan nomor resep '+resep+'?')
        $('#id_resep').val(id)
    });
</script>
@endsection
