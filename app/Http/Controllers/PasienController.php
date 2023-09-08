<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataPasienModel;

class PasienController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Pasien',
            'menu_slug' => 'pasien',
            'sub_menu_slug' => 'data-pasien',
            'data' => DB::table('data_pasien')->get(),
        ];
        return view('pasien.data_pasien', $data);
    }

    public function detail($id)
    {
        $rm = DB::table('data_rekam_medis')->where('pasien_id', $id)->latest()->first();
        $no_rm = ($rm != null) ? $rm->no_erm : '';
        $data = [
            'title' => 'Pasien',
            'menu_slug' => 'pasien',
            'sub_menu_slug' => 'data-pasien',
            'pasien' => DB::table('data_pasien')->where('id', $id)->first(),
            'perawatan_id' => DB::table('data_pendaftar_perawatan')->where('pasien_id', $id)->where('status', '!=', 'selesai')->latest()->first(),
            'no_rm' => $no_rm,
        ];
        return view('pasien.detail_pasien', $data);
    }

    public function tambah(Request $request)
    {
        if ($request->isMethod('post')) {
            // return $request->all();
            $request->validate([
                'no_ktp' => 'required|unique:data_pasien',
            ],[
                'no_ktp.unique' => 'No KTP sudah terdaftar, pastikan No KTP yang anda masukkan benar dan belum terdaftar',
            ]);
            $nama_pasien = $request->nama_pasien;
            $no_ktp =($request->no_ktp != null) ? $request->no_ktp : '';
            $jenis_kelamin = $request->jenis_kelamin;
            $alamat = ($request->alamat != null) ? $request->alamat : '';

            $model = new DataPasienModel();

            $data = [
                'nama_pasien' => $nama_pasien,
                'no_ktp' => $no_ktp,
                'jenis_kelamin' => $jenis_kelamin,
                'alamat' => $alamat,
            ];
            try {
                $create = $model->create($data);
                // get id pasien last insert
                $id_pasien = $create->id;
                return redirect('data-pasien/detail/' . $id_pasien)->with('success', 'Data berhasil ditambahkan');
            } catch (\Throwable $th) {
                return redirect('data-pasien')->with('error', 'Data gagal ditambahkan ' . $th);
            }
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        // return redirect('data-pasien/detail/' . $id)->with('success', 'Data berhasil diubah');
        if ($request->isMethod('post')) {

            $request->validate([
                'no_ktp' => 'required|unique:data_pasien,no_ktp,' . $request->id,
                'nama_pasien' => 'required',
                'jenis_kelamin' => 'required',
                // 'agama' => 'required',
                // 'pekerjaan' => 'required',
                // 'status_perkawinan' => 'required',
                'alamat' => 'required',
                'no_telepon' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
            ], [
                'no_ktp.unique' => 'No KTP sudah terdaftar, pastikan No KTP yang anda masukkan benar dan belum terdaftar',
                'nama_pasien.required' => 'Nama pasien tidak boleh kosong',
                'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
                // 'agama.required' => 'Agama tidak boleh kosong',
                // 'pekerjaan.required' => 'Pekerjaan tidak boleh kosong',
                // 'status_perkawinan.required' => 'Status perkawinan tidak boleh kosong',
                'alamat.required' => 'Alamat tidak boleh kosong',
                'no_telepon.required' => 'No telepon tidak boleh kosong',
                'tempat_lahir.required' => 'Tempat lahir tidak boleh kosong',
                'tanggal_lahir.required' => 'Tanggal lahir tidak boleh kosong',
            ]);

            $nama_pasien = $request->nama_pasien;
            $no_ktp =($request->no_ktp != null) ? $request->no_ktp : '';
            $jenis_kelamin = $request->jenis_kelamin;
            $tempat_lahir = ($request->tempat_lahir != null) ? $request->tempat_lahir : '';
            $tanggal_lahir = ($request->tanggal_lahir != null) ? $request->tanggal_lahir : null;
            $agama = ($request->agama != 0) ? $request->agama : '';
            $pekerjaan = ($request->pekerjaan != 0) ? $request->pekerjaan : '';
            $status_perkawinan = ($request->status_perkawinan != 0) ? $request->status_perkawinan : '';
            $alamat = ($request->alamat != null) ? $request->alamat : '';
            $no_telepon = ($request->no_telepon != null) ? $request->no_telepon : '';
            $nama_wali = ($request->nama_wali != null) ? $request->nama_wali : '';
            $hubungan_dengan_wali = ($request->hubungan_dengan_wali != 0) ? $request->hubungan_dengan_wali : '';
            $jenis_kelamin_wali = ($request->jenis_kelamin_wali != 0) ? $request->jenis_kelamin_wali : '';
            $alamat_wali = ($request->alamat_wali != null) ? $request->alamat_wali : '';
            $no_telepon_wali = ($request->no_telepon_wali != null) ? $request->no_telepon_wali : '';

            $data = [
                'nama_pasien' => $nama_pasien,
                'no_ktp' => $no_ktp,
                'jenis_kelamin' => $jenis_kelamin,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $agama,
                'pekerjaan' => $pekerjaan,
                'status_perkawinan' => $status_perkawinan,
                'alamat' => $alamat,
                'no_telepon' => $no_telepon,
                'nama_wali' => $nama_wali,
                'hubungan_dengan_wali' => $hubungan_dengan_wali,
                'jenis_kelamin_wali' => $jenis_kelamin_wali,
                'alamat_wali' => $alamat_wali,
                'no_telepon_wali' => $no_telepon_wali,
            ];

            try {
                DB::table('data_pasien')->where('id', $request->id)->update($data);
                return redirect('data-pasien/detail/' . $request->id)->with('success', 'Data pasien berhasil diubah');
            } catch (\Throwable $th) {
                return redirect('data-pasien/detail/' . $request->id)->with('error', 'Data pasien gagal diubah ' . $th);
            }
        }

    }

    public function delete(Request $request)
    {
        if ($request->isMethod('delete')) {
            $id = $request->id_pasien;
            try {
                DB::table('data_pasien')->where('id', $id)->delete();
                return redirect('data-pasien')->with('success', 'Data berhasil dihapus');
            } catch (\Throwable $th) {
                return redirect('data-pasien')->with('error', 'Data gagal dihapus ' . $th);
            }
        }
    }

    // EMR Pasien
    public function emr_all()
    {
        $data = [
            'title' => 'Rekam Medik Pasien',
            'menu_slug' => 'pasien',
            'sub_menu_slug' => 'rekam-medis-pasien',
            'data' => DB::table('data_rekam_medis')->join('data_pasien', 'data_pasien.id', '=', 'data_rekam_medis.pasien_id')->get(),
        ];
        return view('pasien.data_emr_pasien', $data);
    }

    public function emr_detail($id)
    {
        $erm = DB::table('data_rekam_medis')->join('data_pasien', 'data_pasien.id', '=', 'data_rekam_medis.pasien_id')->where('data_rekam_medis.no_erm', $id)->first();
        $rm_line = DB::table('data_rekam_medis_pasien')
        ->where('data_rekam_medis_pasien.no_rekam_medis', $id)
        ->join('data_pendaftar_perawatan', 'data_rekam_medis_pasien.pendaftaran_id', '=', 'data_pendaftar_perawatan.id')
        ->join('data_poli', 'data_pendaftar_perawatan.poli_id', '=', 'data_poli.id')
        ->join('users', 'data_pendaftar_perawatan.dokter_id', '=', 'users.id')
        ->select(
            'data_rekam_medis_pasien.*',
            'data_poli.nama_poli as nama_poli',
            'users.name as nama_dokter')
        ->get();
        if ($erm == null) {
            return redirect('rm-pasien')->with('error', 'Data tidak ditemukan');
        }
        $data = [
            'title' => 'Rekam Medik Pasien (' . $erm->no_erm . ')',
            'menu_slug' => 'pasien',
            'sub_menu_slug' => 'rekam-medis-pasien',
            'data' => $erm,
            'data_line' => $rm_line,
        ];
        return view('pasien.detail_emr_pasien', $data);
    }
}
