@extends('layout')
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css"  />
@endsection
@section('style_custom')
<style type="text/css">
    .img-profile {
        display: block;
        max-width: 100%;
    }
    .preview {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin: 10px;
        border: 1px solid red;
    }
</style>
@endsection
@section('content')
<form method="POST" action="/users/edit" enctype="multipart/form-data">
@csrf
<input type="hidden" name="id" value="{{$user->id}}">
<input type="hidden" name="id_detail" value="{{$user->id_detail}}">
@if(in_array(Session::get('role'), ['super-admin']))
    <button type="button" class="btn btn-danger" id="btn-hapus-data-user" style="float: right;" data-toggle="modal" data-target="#modal-hapus-user" data-id="{{$user->id}}" data-nama="{{$user->name}}">
        <i class="fas fa-trash"></i>
    </button>
@endif
@if ($user->is_active == 1)
    @if(in_array(Session::get('role'), ['super-admin']))
        <button type="button" class="btn btn-danger" id="btn-deactivate-user" style="float: right; margin-right:5px" data-id="{{$user->id}}">
            <i class="fas fa-times"></i> Nonaktifkan User
        </button>
    @endif
    @if($user->password == null)
    <button type="button" class="btn btn-warning" id="btn-ubah-pass" style="float: right; margin-right:5px" data-toggle="modal" data-target="#modal-ubah-pass" data-id="{{$user->id}}">
        <i class="fas fa-key"></i> Set Password
    </button>
    @else
    <button type="button" class="btn btn-warning" id="btn-ubah-pass" style="float: right; margin-right:5px" data-toggle="modal" data-target="#modal-ubah-pass" data-id="{{$user->id}}">
        <i class="fas fa-key"></i> Ubah Password
    </button>
    @endif
@else
    {{-- activate user --}}
    <button type="button" class="btn btn-success" id="btn-activate-user" style="float: right; margin-right:5px" data-id="{{$user->id}}">
        <i class="fas fa-check"></i> Activate User
    </button>
@endif
<button type="button" class="btn btn-info" id="btn-edit-data-user">
    Edit Data User
</button>
<button type="button" class="btn btn-danger" id="btn-cancel-edit-data-user" style="display: none;">
    Cancel
</button>
<button type="submit" class="btn btn-success" id="btn-save-data-user" style="display: none;">
    Simpan Data User
</button>
<br><br>
<div class="row">
    <div class="col-sm-12 col-md-7 col-lg-7">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Informasi Umum</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-5">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                @if ($user->file_foto != null)
                                    <img class="profile-user-img img-fluid img-circle" src="{{asset('/storage/foto_profil/'.$user->file_foto)}}" alt="User profile picture" style="width: 150px; height: 150px;" id="foto_profil">
                                @else
                                    @if ($user->jenis_kelamin == 'L')
                                        <img class="profile-user-img img-fluid img-circle" src="{{asset('/asset/img/avatar-lk.png')}}" alt="User profile picture" style="width: 150px; height: 150px;" id="foto_profil">
                                    @else
                                        <img class="profile-user-img img-fluid img-circle" src="{{asset('/asset/img/avatar-pr.png')}}" alt="User profile picture" style="width: 150px; height: 150px;" id="foto_profil">
                                    @endif
                                @endif
                                {{-- <img class="profile-user-img img-fluid img-circle" src="{{asset('/adminlte/dist/img/user4-128x128.jpg')}}" alt="User profile picture" style="width: 150px; height: 150px;" id="foto_profil"> --}}
                            </div>
                            <h3 class="profile-username text-center">{{$user->name}}</h3>
                            <p class="text-muted text-center">{{$user->jabatan}}</p>
                            {{-- change profil photo --}}
                            <div class="form-group" id="form-foto" hidden>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input image @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*" onchange="loadProfilPhoto(event)">
                                        <input type="hidden" name="foto_base64" id="foto_base64">
                                        <input type="hidden" name="nama_foto" id="nama_foto">
                                        <label class="custom-file-label" for="foto">Pilih Foto Profil</label>
                                    </div>
                                    <input type="hidden" name="foto_lama" value="{{$user->file_foto}}">
                                </div>
                                @error('foto')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-7">
                        <div class="form-group">
                            <label class="required" for="no_pegawai">No. Pegawai</label>
                            <input type="text" id="no_pegawai" class="form-control emr-edit @error('no_pegawai') is-invalid @enderror" value="{{$user->no_pegawai}}" readonly name="no_pegawai" required>
                            @error('no_pegawai')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="required" for="nama_user">Nama</label>
                            <input type="text" id="nama_user" class="form-control emr-edit @error('nama_user') is-invalid @enderror" value="{{$user->name}}" readonly name="nama_user" required>
                            @error('nama_user')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="required" for="email">Email</label>
                            <input type="text" id="email" class="form-control emr-edit @error('email') is-invalid @enderror" value="{{$user->email}}" readonly name="email" required>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        @if(in_array(Session::get('role'), ['super-admin']))
                            <div class="form-group" id="form-role">
                                <label class="required" for="role">Role</label>
                                <select class="custom-select form-control-border emr-edit-select @error('role') is-invalid @enderror" id="role" disabled name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="super-admin" @if($user->role == 'super-admin') selected @endif>Super Admin</option>
                                    <option value="admin" @if($user->role == 'admin') selected @endif>Admin</option>
                                    <option value="dokter" @if($user->role == 'dokter') selected @endif>Dokter</option>
                                    <option value="perawat" @if($user->role == 'perawat') selected @endif>Perawat</option>
                                    <option value="apoteker" @if($user->role == 'apoteker') selected @endif>Apoteker</option>
                                    <option value="kasir" @if($user->role == 'kasir') selected @endif>Kasir</option>
                                    <option value="user" @if($user->role == 'user') selected @endif>User</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="required" for="no_ktp">No. KTP</label>
                    <input type="text" id="no_ktp" class="form-control emr-edit @error('no_ktp') is-invalid @enderror" value="{{$user->no_ktp}}" readonly name="no_ktp" required>
                    @error('no_ktp')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="required" for="jenis_kelamin">Jenis Kelamin</label>
                    <select class="custom-select form-control-border emr-edit-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" disabled name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" @if($user->jenis_kelamin == 'L') selected @endif>Laki-laki</option>
                        <option value="P" @if($user->jenis_kelamin == 'P') selected @endif>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                {{-- tempat tanggal lahir --}}
                <div class="form-group">
                    <label class="required" for="tempat_lahir">Tempat Tanggal Lahir</label>
                    <div class="input-group">
                        <input type="text" id="tempat_lahir" class="form-control emr-edit @error('tempat_lahir') is-invalid @enderror" value="{{$user->tempat_lahir}}" readonly name="tempat_lahir" required>
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" id="tanggal_lahir" class="form-control emr-edit @error('tanggal_lahir') is-invalid @enderror" value="{{$user->tanggal_lahir}}" readonly name="tanggal_lahir" required>
                        @error('tempat_lahir')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                        @error('tanggal_lahir')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                </div>
                {{-- agama --}}
                <div class="form-group">
                    <label for="agama">Agama</label>
                    <select class="custom-select form-control-border emr-edit-select @error('agama') is-invalid @enderror" id="agama" disabled name="agama">
                        <option value="">Pilih Agama</option>
                        <option value="Islam" @if($user->agama == 'Islam') selected @endif>Islam</option>
                        <option value="Kristen" @if($user->agama == 'Kristen') selected @endif>Kristen</option>
                        <option value="Katolik" @if($user->agama == 'Katolik') selected @endif>Katolik</option>
                        <option value="Hindu" @if($user->agama == 'Hindu') selected @endif>Hindu</option>
                        <option value="Budha" @if($user->agama == 'Budha') selected @endif>Budha</option>
                        <option value="Konghucu" @if($user->agama == 'Konghucu') selected @endif>Konghucu</option>
                    </select>
                    @error('agama')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-5 col-lg-5">
        {{-- <div class="card card-info card-outline">
            <div class="card-body">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if ($user->file_foto != null)
                            <img class="profile-user-img img-fluid img-circle" src="{{asset('/storage/foto_profil/'.$user->file_foto)}}" alt="User profile picture" style="width: 150px; height: 150px;" id="foto_profil">
                        @else
                            @if ($user->jenis_kelamin == 'L')
                                <img class="profile-user-img img-fluid img-circle" src="{{asset('/asset/img/avatar-lk.png')}}" alt="User profile picture" style="width: 150px; height: 150px;" id="foto_profil">
                            @else
                                <img class="profile-user-img img-fluid img-circle" src="{{asset('/asset/img/avatar-pr.png')}}" alt="User profile picture" style="width: 150px; height: 150px;" id="foto_profil">
                            @endif
                        @endif
                    </div>
                    <h3 class="profile-username text-center">{{$user->name}}</h3>
                    <p class="text-muted text-center">{{$user->jabatan}}</p>
                    <div class="form-group" id="form-foto" hidden>
                        <label for="foto">Ubah Foto</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*" onchange="loadProfilPhoto(event)">
                                <label class="custom-file-label" for="foto">Choose file</label>
                            </div>
                        </div>
                        @error('foto')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Informasi Kontak</h3>
                <div class="card-tools">
                    {{-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button> --}}
                </div>
            </div>
            <div class="card-body">
                {{-- alamat --}}
                <div class="form-group">
                    <label class="required" for="alamat">Alamat</label>
                    <textarea class="form-control emr-edit form-control-border" id="alamat @error('alamat') is-invalid @enderror" rows="3" placeholder="Alamat" name="alamat" readonly required>{{$user->alamat}}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                <label class="required" for="no_telepon">No. HP</label>
                    <input type="text" class="form-control emr-edit form-control-border @error('no_telepon') is-invalid @enderror" id="no_telepon" placeholder="No. HP" name="no_telepon" value="{{$user->no_telepon}}" readonly required>
                    @error('no_telepon')
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
    <div class="modal fade" id="modal-hapus-user" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-danger text-white">
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
                        <input type="hidden" name="menu" id="menu" value="detail_user">
                        <input type="method" name="_method" value="DELETE" id="_method" hidden>
                        <button type="submit" class="btn btn-outline-light">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-ubah-pass" tabindex="-1" role="definition" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form  method="POST" action="/users/ubah-password">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah Password</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group" id="form-password">
                            <label class="required" for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password" name="password" required minlength="8">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="btn-password"><i class="fas fa-eye" id="icon-password"></i></span>
                                </div>
                            </div>
                            @error('password')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <input type="hidden" name="id_pass_user" id="id_pass_user" value="{{$user->id}}">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-success">Ubah Password</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-profile" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Crop image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img id="image" class="img-profile"/>
                        </div>
                        <div class="col-md-4">
                            <div class="preview"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="crop-cancel">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop">Crop</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
    var bs_modal = $('#modal-profile');
    var image = document.getElementById('image');
    var cropper,reader,file;
    $("body").on("change", ".image", function(e) {
        var files = e.target.files;
        var done = function(url) {
            image.src = url;
            bs_modal.modal('show');
        };


        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function(e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    bs_modal.on('shown.bs.modal', function() {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 3,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function() {
        cropper.destroy();
        cropper = null;
    });

    $("#crop-cancel").click(function() {
        bs_modal.modal('hide');
    });

    $("#crop").click(function() {
        canvas = cropper.getCroppedCanvas({
            width: 160,
            height: 160,
        });

        canvas.toBlob(function(blob) {
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                var base64data = reader.result;
                $("#foto_profil").attr("src", base64data);
                $("#foto_base64").val(base64data);
                $("#nama_foto").val(file.name);
                bs_modal.modal('hide');
                $(".custom-file-label").text(file.name);
            }
        });
    });
</script>
<script>
    $('#btn-ubah-pass').click(function(){
        $('#modal-ubah-pass').modal('show');
    });

    $('#btn-activate-user').click(function(){
        var id = $(this).data('id');
        // alert(id);
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "User akan diaktifkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya, Aktifkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/users/activate',
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id
                    },
                    success: function(data){
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#28a745',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    },
                    error: function(data){
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                            confirmButtonColor: '#28a745',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    $('#btn-deactivate-user').click(function(){
        var id = $(this).data('id');
        // alert(id);
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "User akan dinonaktifkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya, Nonaktifkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/users/deactivate',
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id
                    },
                    success: function(data){
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#28a745',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    },
                    error: function(data){
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                            confirmButtonColor: '#28a745',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    $('#btn-password').click(function(){
        var x = document.getElementById("password");
        var y = document.getElementById("icon-password");
        if (x.type === "password") {
            x.type = "text";
            y.className = "fas fa-eye-slash";
        } else {
            x.type = "password";
            y.className = "fas fa-eye";
        }
    });
</script>
<script>
    $('#btn-edit-data-user').click(function(){
        $('#btn-edit-data-user').hide();
        $('#btn-hapus-data-user').hide();
        $('#btn-cancel-edit-data-user').show();
        $('#btn-save-data-user').show();
        $('#btn-cancel-save-data-user').show();
        $('.emr-edit').removeAttr('readonly');
        $('.emr-edit-select').removeAttr('disabled');
        // $('#nama_user').removeAttr('readonly');
        // $('#email').removeAttr('readonly');
        // $('#no_pegawai').removeAttr('readonly');
        // $('#tempat_lahir').removeAttr('readonly');
        // $('#tanggal_lahir').removeAttr('readonly');
        // $('#jenis_kelamin').removeAttr('disabled');
        // $('#agama').removeAttr('disabled');
        // $('#alamat').removeAttr('readonly');
        // $('#no_telepon').removeAttr('readonly');
        $('#form-foto').removeAttr('hidden');
    });
    $('#btn-cancel-edit-data-user').click(function(){
        location.reload();
    });

    // function loadProfilPhoto(event){
    //     var output = document.getElementById('foto_profil');
    //     var input = document.getElementById('foto');
    //     output.src = URL.createObjectURL(event.target.files[0]);
    // }

    $('#modal-hapus-user').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus user atas nama ' + nama + '?')
        $('#id_user').val(id)
    });
</script>
@endsection
