@extends('layout')
@section('style')
@endsection
@section('content')
<form method="POST" action="/data-pasien/edit" id="form_edit_data_pasien">
<button type="button" class="btn btn-danger" id="btn-hapus-data-pasien" style="float: right;" data-toggle="modal" data-target="#modal-hapus-pasien" data-id="{{$pasien->id}}" data-nama="{{$pasien->nama_pasien}}">
    <i class="fas fa-trash"></i>
</button>
<button type="button" class="btn btn-info" id="btn-edit-data-pasien">
    Edit Data Pasien
</button>
<button type="button" class="btn btn-danger" id="btn-cancel-edit-data-pasien" style="display: none;">
    Cancel
</button>
<button type="button" class="btn btn-success" id="btn-save-data-pasien" style="display: none;" onclick="simpanDataPasien()">
    Simpan Data Pasien
</button>
<a href="{{url('/rm-pasien/detail/'.$no_rm)}}" class="btn btn-primary" id="btn-rm-pasien">
    Rekam Medis Pasien
</a>
@if(isset($perawatan_id))
<a href="{{url('/rawat-jalan/detail/'.$perawatan_id->id)}}" class="btn btn-primary" id="btn-rawat-jalan">
    Pendaftaran Rawat Jalan Pasien
</a>
@endif

<br><br>
<div class="row">
    @csrf
    <input type="hidden" name="id" value="{{$pasien->id}}">
    <div class="col-sm-12 col-md-7 col-lg-7">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Informasi Umum</h3>
                <div class="card-tools">
                    {{-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                    </button> --}}
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="nama_pasien">Nama Pasien</label>
                    <input type="text" id="nama_pasien" class="form-control @error('nama_pasien') is-invalid @enderror" value="{{$pasien->nama_pasien}}" readonly name="nama_pasien" required>
                    @error('nama_pasien')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="required" for="no_ktp">No. Identitas</label>
                    <input type="text" id="no_ktp" class="form-control" value="{{$pasien->no_ktp}}" readonly name="no_ktp">
                    @error('no_ktp')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="required" for="jenis_kelamin">Jenis Kelamin</label>
                    <select class="custom-select form-control-border @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" disabled name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" @if($pasien->jenis_kelamin == 'L') selected @endif>Laki-laki</option>
                        <option value="P" @if($pasien->jenis_kelamin == 'P') selected @endif>Perempuan</option>
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
                        <input type="text" id="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{$pasien->tempat_lahir}}" readonly name="tempat_lahir" required>
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{$pasien->tanggal_lahir}}" readonly name="tanggal_lahir" required>
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
                    <select class="custom-select form-control-border select2 @error('agama') is-invalid @enderror" id="agama" disabled name="agama">
                        <option value="">Pilih Agama</option>
                        <option value="Islam" @if($pasien->agama == 'Islam') selected @endif>Islam</option>
                        <option value="Kristen" @if($pasien->agama == 'Kristen') selected @endif>Kristen</option>
                        <option value="Katolik" @if($pasien->agama == 'Katolik') selected @endif>Katolik</option>
                        <option value="Hindu" @if($pasien->agama == 'Hindu') selected @endif>Hindu</option>
                        <option value="Budha" @if($pasien->agama == 'Budha') selected @endif>Budha</option>
                        <option value="Konghucu" @if($pasien->agama == 'Konghucu') selected @endif>Konghucu</option>
                    </select>
                    @error('agama')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                {{-- pekerjaan, tambah inputan lainnya apabila lainnya dipilih --}}
                <div class="form-group">
                    <label for="pekerjaan">Pekerjaan</label>
                    <select class="custom-select form-control-border select2 @error('pekerjaan') is-invalid @enderror" id="pekerjaan" disabled name="pekerjaan">
                        <option value="">Pilih Pekerjaan</option>
                        <option value="PNS" @if($pasien->pekerjaan == 'PNS') selected @endif>PNS</option>
                        <option value="TNI" @if($pasien->pekerjaan == 'TNI') selected @endif>TNI</option>
                        <option value="POLRI" @if($pasien->pekerjaan == 'POLRI') selected @endif>POLRI</option>
                        <option value="BUMN" @if($pasien->pekerjaan == 'BUMN') selected @endif>BUMN</option>
                        <option value="Wirausaha" @if($pasien->pekerjaan == 'Wirausaha') selected @endif>Wirausaha</option>
                        <option value="Swasta" @if($pasien->pekerjaan == 'Swasta') selected @endif>Swasta</option>
                        <option value="Pensiunan" @if($pasien->pekerjaan == 'Pensiunan') selected @endif>Pensiunan</option>
                        <option value="Petani" @if($pasien->pekerjaan == 'Petani') selected @endif>Petani</option>
                        <option value="Nelayan" @if($pasien->pekerjaan == 'Nelayan') selected @endif>Nelayan</option>
                        <option value="Ibu Rumah Tangga" @if($pasien->pekerjaan == 'Ibu Rumah Tangga') selected @endif>Ibu Rumah Tangga</option>
                        <option value="Pelajar/Mahasiswa" @if($pasien->pekerjaan == 'Pelajar/Mahasiswa') selected @endif>Pelajar/Mahasiswa</option>
                        <option value="Tidak Bekerja" @if($pasien->pekerjaan == 'Tidak Bekerja') selected @endif>Tidak Bekerja</option>
                        <option value="Lainnya" @if($pasien->pekerjaan == 'Lainnya') selected @endif>Lainnya</option>
                    </select>
                    @error('pekerjaan')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                {{-- status perkawinan --}}
                <div class="form-group">
                    <label for="status_perkawinan">Status Perkawinan</label>
                    <select class="custom-select form-control-border select2 @error('status_perkawinan') is-invalid @enderror" id="status_perkawinan" disabled name="status_perkawinan">
                        <option value="">Pilih Status Perkawinan</option>
                        <option value="Belum Menikah" @if($pasien->status_perkawinan == 'Belum Menikah') selected @endif>Belum Menikah</option>
                        <option value="Menikah" @if($pasien->status_perkawinan == 'Menikah') selected @endif>Menikah</option>
                        <option value="Cerai Hidup" @if($pasien->status_perkawinan == 'Cerai Hidup') selected @endif>Cerai Hidup</option>
                        <option value="Cerai Mati" @if($pasien->status_perkawinan == 'Cerai Mati') selected @endif>Cerai Mati</option>
                    </select>
                    @error('status_perkawinan')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-5 col-lg-5">
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
                    <textarea class="form-control form-control-border @error('alamat') is-invalid @enderror" id="alamat" rows="3" placeholder="Alamat" name="alamat" readonly required>{{$pasien->alamat}}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                <label class="required" for="no_telepon">No. HP</label>
                    <input type="text" class="form-control form-control-border @error('no_telepon') is-invalid @enderror" id="no_telepon" placeholder="No. HP" name="no_telepon" value="{{$pasien->no_telepon}}" readonly required>
                    @error('no_telepon')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Informasi Wali Pasien</h3>
                <div class="card-tools">
                    {{-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button> --}}
                </div>
            </div>
            <div class="card-body">
                {{-- nama wali --}}
                <div class="form-group">
                    <label for="nama_wali">Nama Wali</label>
                    <input type="text" class="form-control form-control-border" id="nama_wali" placeholder="Nama Wali" name="nama_wali" value="{{$pasien->nama_wali}}" readonly>
                </div>
                {{-- hubungan dengan wali --}}
                <div class="form-group">
                    <label for="hubungan_dengan_wali">Hubungan dengan Wali</label>
                    <select class="custom-select form-control-border select2-addtag" id="hubungan_dengan_wali" disabled name="hubungan_dengan_wali">
                        <option value="">Pilih Hubungan dengan Wali</option>
                        <option value="Orang Tua" @if($pasien->hubungan_dengan_wali == 'Orang Tua') selected @endif>Orang Tua</option>
                        <option value="Suami/Istri" @if($pasien->hubungan_dengan_wali == 'Suami/Istri') selected @endif>Suami/Istri</option>
                        <option value="Anak" @if($pasien->hubungan_dengan_wali == 'Anak') selected @endif>Anak</option>
                        <option value="Saudara" @if($pasien->hubungan_dengan_wali == 'Saudara') selected @endif>Saudara</option>
                        <option value="Lainnya" @if($pasien->hubungan_dengan_wali == 'Lainnya') selected @endif>Lainnya</option>
                        @if ($pasien->hubungan_dengan_wali != 'Orang Tua' || $pasien->hubungan_dengan_wali != 'Suami/Istri' || $pasien->hubungan_dengan_wali != 'Anak' || $pasien->hubungan_dengan_wali != 'Saudara' || $pasien->hubungan_dengan_wali != 'Lainnya')
                            <option value="{{$pasien->hubungan_dengan_wali}}" selected>{{$pasien->hubungan_dengan_wali}}</option>
                        @endif
                    </select>
                </div>
                {{-- jenis kelamin wali --}}
                <div class="form-group">
                    <label for="jenis_kelamin_wali">Jenis Kelamin Wali</label>
                    <select class="custom-select form-control-border" id="jenis_kelamin_wali" disabled name="jenis_kelamin_wali">
                        <option value="">Pilih Jenis Kelamin Wali</option>
                        <option value="Laki-laki" @if($pasien->jenis_kelamin_wali == 'Laki-laki') selected @endif>Laki-laki</option>
                        <option value="Perempuan" @if($pasien->jenis_kelamin_wali == 'Perempuan') selected @endif>Perempuan</option>
                    </select>
                </div>
                {{-- alamat wali --}}
                <div class="form-group">
                    <label for="alamat_wali">Alamat Wali</label>
                    <textarea class="form-control form-control-border" id="alamat_wali" placeholder="Alamat Wali" name="alamat_wali" readonly>{{$pasien->alamat_wali}}</textarea>
                </div>
                {{-- no hp wali --}}
                <div class="form-group">
                    <label for="no_hp_wali">No. HP Wali</label>
                    <input type="text" class="form-control form-control-border" id="no_telepon_wali" placeholder="No. HP Wali" name="no_telepon_wali" value="{{$pasien->no_telepon_wali}}" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<div>
    <div class="modal fade" id="modal-hapus-pasien" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
<script>
    $('#btn-tes').click(function(){
        alert('tes');
        Swal.fire('Any fool can use a computer')
    })
</script>
<script>
    $('#btn-edit-data-pasien').click(function(){
        $('#btn-edit-data-pasien').hide();
        $('#btn-hapus-data-pasien').hide();
        $('#btn-rm-pasien').hide();
        $('#btn-cancel-edit-data-pasien').show();
        $('#btn-save-data-pasien').show();
        $('#btn-cancel-save-data-pasien').show();
        $('#nama_pasien').removeAttr('readonly');
        $('#tempat_lahir').removeAttr('readonly');
        $('#tanggal_lahir').removeAttr('readonly');
        $('#jenis_kelamin').removeAttr('disabled');
        $('#agama').removeAttr('disabled');
        $('#pekerjaan').removeAttr('disabled');
        $('#status_perkawinan').removeAttr('disabled');
        $('#alamat').removeAttr('readonly');
        $('#no_telepon').removeAttr('readonly');
        $('#nama_wali').removeAttr('readonly');
        $('#hubungan_dengan_wali').removeAttr('disabled');
        $('#jenis_kelamin_wali').removeAttr('disabled');
        $('#alamat_wali').removeAttr('readonly');
        $('#no_telepon_wali').removeAttr('readonly');
    });
    $('#btn-cancel-edit-data-pasien').click(function(){
        location.reload();
        $('#btn-edit-data-pasien').show();
        $('#btn-hapus-data-pasien').show();
        $('#btn-rm-pasien').show();
        $('#btn-cancel-edit-data-pasien').hide();
        $('#btn-save-data-pasien').hide();
        $('#btn-cancel-save-data-pasien').hide();
        $('#nama_pasien').attr('readonly', true);
        $('#tempat_lahir').attr('readonly', true);
        $('#tanggal_lahir').attr('readonly', true);
        $('#jenis_kelamin').attr('disabled', true);
        $('#agama').attr('disabled', true);
        $('#pekerjaan').attr('disabled', true);
        $('#status_perkawinan').attr('disabled', true);
        $('#alamat').attr('readonly', true);
        $('#no_telepon').attr('readonly', true);
        $('#nama_wali').attr('readonly', true);
        $('#hubungan_dengan_wali').attr('disabled', true);
        $('#jenis_kelamin_wali').attr('disabled', true);
        $('#alamat_wali').attr('readonly', true);
        $('#no_telepon_wali').attr('readonly', true);
    });

    $(function () {
        $(".select2-addtag").select2({
            tags: true
        });
    })

    function simpanDataPasien(){
        Swal.fire({
            title: 'Simpan Data Pasien?',
            text: "Data pasien akan disimpan",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya, simpan data pasien!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                $('#btn-save-data-pasien').html('<i class="fas fa-spinner fa-pulse"></i> Menyimpan...');
                $('#btn-save-data-pasien').attr('disabled', true);
                $('#form_edit_data_pasien').submit();
            }
        })
    }

    $('#modal-hapus-pasien').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var modal = $(this)
        modal.find('.modal-body #modal_body_delete').text('Apakah anda yakin ingin menghapus pasien atas nama ' + nama + '?')
        $('#id_pasien').val(id)
    });
</script>
@endsection
