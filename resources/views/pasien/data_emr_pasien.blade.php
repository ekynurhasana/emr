@extends('layout')
@section('style')
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
@if (session('errors'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Rekam Medis Pasien</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table_rm" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 20%">Nomor Rekam Medis</th>
                            <th class="text-center">Nama Pasien</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $rm)
                            <tr class="tr-emr" onclick="window.location='{{url('/rm-pasien/detail/'.$rm->no_erm)}}'">
                                <td style="text-align: center">{{$rm->no_erm}}</td>
                                <td>{{$rm->nama_pasien}}</td>
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
    <div class="modal fade" id="modal-hapus-pasien" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="form_delete_pasien" method="POST" action="/data-pasien/delete">
                        @csrf
                        <input type="hidden" name="id_pasien" id="id_pasien">
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
        $("#table_rm").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#table_rm_wrapper .col-md-6:eq(0)');
    });

    $('#modal-hapus-pasien').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus pasien atas nama ' + nama + '?')
        $('#id_pasien').val(id)
    });

    function hanyaAngka(event) {
        var angka = (event.which) ? event.which : event.keyCode
        if ((angka < 48 || angka > 57))
            return false;
        return true;
    }

    $(document).ready(function() {
        $('#tr').click(function() {
            var id = $(this).data('id');
            // redirect to detail page
            console.log(id);
            // window.location.href = "/data-pasien/detail/" + id;
        });
    });
</script>
@endsection
