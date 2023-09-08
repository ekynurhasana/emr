@extends('layout')
@section('style')
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
<div class="row">
    {{-- <input type="hidden" name="id" value="{{$pasien->id}}"> --}}
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="nama_pasien">Nama Pasien</label>
                            <input type="text" id="nama_pasien" class="form-control" value="{{$data->nama_pasien}}" readonly name="nama_pasien">
                        </div>
                        <div class="form-group">
                            <label for="no_telepon">No. Telepon</label>
                            <input type="text" id="no_telepon" class="form-control" value="{{$data->no_telepon}}" readonly name="no_telepon">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="no_ktp">No. Identitas</label>
                            <input type="text" id="no_ktp" class="form-control" value="{{$data->no_ktp}}" readonly name="no_ktp">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="emr-tabs-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="emr-tabs-riwayat-tab" data-toggle="pill" href="#emr-tabs-riwayat" role="tab" aria-controls="emr-tabs-riwayat" aria-selected="true">Riwayat Rekam Medik</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="emr-tabs-tabContent">
                    <div class="tab-pane fade show active" id="emr-tabs-riwayat" role="tabpanel" aria-labelledby="emr-tabs-riwayat-tab">
                        <div>
                            <table id="tabel_riwayat" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 10%">Tanggal Pemeriksaan</th>
                                        <th class="text-center" style="width: 13%">Poli</th>
                                        <th class="text-center" style="width: 13%">Dokter</th>
                                        <th class="text-center" style="width: 18%">Diagnosa</th>
                                        <th class="text-center" style="width: 10%">ICD 10</th>
                                        <th class="text-center" style="width: 18%">Catatan Tindakan</th>
                                        <th class="text-center" style="width: 18%">Resep Obat</th>
                                        <th class="text-center" style="width: 10%">Riwayat Pemeriksaan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_line as $rm)
                                        <tr>
                                            <td class="text-center">{{date('d M Y', strtotime($rm->tanggal_pemeriksaan))}}</td>
                                            <td class="text-center">{{$rm->nama_poli}}</td>
                                            <td class="text-center">{{$rm->nama_dokter}}</td>
                                            <td class="text-center">{{$rm->diagnosa}}</td>
                                            <td class="text-center">{{$rm->diagnosa_icd}}</td>
                                            <td class="text-center">{{$rm->tindakan}}</td>
                                            <td class="text-center">{{$rm->resep_obat}}</td>
                                            <td class="text-center">
                                                <a href="{{url('/rawat-jalan/detail/'.$rm->pendaftaran_id)}}" class="btn btn-info">Detail</a>
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
    <div class="modal fade" id="modal-hapus-riwayat" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
        $("#tabel_riwayat").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tabel_riwayat_wrapper .col-md-6:eq(0)');
    });
    $('#modal-hapus-riwayat').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var tanggal = button.data('tanggal')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus riwayat medis pasien pada tanggal ' + tanggal + '?')
        $('#id_pasien').val(id)
    });
</script>
@endsection
