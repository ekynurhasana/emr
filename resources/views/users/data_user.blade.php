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
        {{-- cek user role --}}
        @if (Auth::user()->role == 'super-admin')
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-tambah-user" id="btn-tambah-user">
                Tambah User
            </button>
        @endif
    </div>
</div><br>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data User</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table_poli" class="table table-bordered table-hover projects">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">No.</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">No. Pegawai</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Posisi</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($data as $user)
                            <tr class="tr-emr">
                                <td class="text-center">{{$no}}</td>
                                <td>
                                    <ul class="list-inline">
                                        <li class="list-inline-item">
                                            @if ($user->file_foto != null)
                                                <img alt="Avatar" class="table-avatar img-fluid img-circle" src="{{asset('/asset/img/profile_users/'.$user->file_foto)}}">
                                            @else
                                                @if ($user->jenis_kelamin == 'L')
                                                    <img alt="Avatar" class="table-avatar" src="{{asset('/asset/img/avatar-lk.png')}}">
                                                @else
                                                    <img alt="Avatar" class="table-avatar" src="{{asset('/asset/img/avatar-pr.png')}}">
                                                @endif
                                            @endif
                                        </li>
                                        <li class="list-inline-item">
                                            {{$user->name}}
                                        </li>
                                    </ul>
                                </td>
                                <td class="text-center">{{$user->no_pegawai}}</td>
                                <td class="text-center">{{$user->email}}</td>
                                <td class="text-center">
                                    @if($user->role == 'super-admin')
                                        <span>Super Admin</span>
                                    @elseif($user->role == 'admin')
                                        <span>Admin</span>
                                    @elseif($user->role == 'dokter')
                                        <span>Dokter</span>
                                    @elseif($user->role == 'perawat')
                                        <span>Perawat</span>
                                    @elseif($user->role == 'apoteker')
                                        <span>Apoteker</span>
                                    @elseif($user->role == 'kasir')
                                        <span>Kasir</span>
                                    @elseif($user->role == 'user')
                                        <span>User</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($user->is_active == 1)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{url('/users/detail/'.$user->id)}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> </a>
                                    @if (Auth::user()->role == 'super-admin')
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-user" data-id="{{$user->id}}" data-nama="{{$user->name}}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
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

<div class="modal fade" id="modal-tambah-user" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form  method="POST" action="/users/tambah">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah User Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group" id="form-nama">
                            <label class="required" for="name">Nama Lengkap</label>
                            <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" maxlength="50" value="{{old('name')}}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="form-email">
                            <label class="required" for="email">Email</label>
                            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" maxlength="50" value="{{old('email')}}" required>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="form-posisi">
                            <label class="required" for="posisi">Posisi</label>
                            <select id="posisi" class="form-control @error('posisi') is-invalid @enderror" name="posisi" required>
                                <option value="">-- Pilih Posisi --</option>
                                <option value="super-admin" @if(old('posisi') == 'super-admin') selected @endif>Super Admin</option>
                                <option value="admin" @if(old('posisi') == 'admin') selected @endif>Admin</option>
                                <option value="dokter" @if(old('posisi') == 'dokter') selected @endif>Dokter</option>
                                <option value="perawat" @if(old('posisi') == 'perawat') selected @endif>Perawat</option>
                                <option value="apoteker" @if(old('posisi') == 'apoteker') selected @endif>Apoteker</option>
                                <option value="kasir" @if(old('posisi') == 'kasir') selected @endif>Kasir</option>
                                <option value="user" @if(old('posisi') == 'user') selected @endif>User</option>
                            </select>
                            @error('posisi')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
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

<div>
    <div class="modal fade" id="modal-hapus-user" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="form_delete_user" method="POST" action="/users/delete">
                        @csrf
                        <input type="hidden" name="id_user" id="id_user">
                        <input type="hidden" name="menu" value="data_user">
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
        $("#table_poli").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#table_poli_wrapper .col-md-6:eq(0)');
    });

    $('#modal-hapus-user').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus User ' + nama + '?')
        $('#id_user').val(id)
    });
</script>
@if(session('errors'))
<script>
    $(document).ready(function(){
        $('#modal-tambah-user').modal('show');
    });
</script>
@endif
@endsection
