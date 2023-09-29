<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataPasienModel;
use App\Models\DataPendaftarPerawatanModel;
use App\Models\DataTagihanModel;
use App\Models\DataRekamMedisModel;
use App\Models\DataResepObatPasienModel;

class PerawatanController extends Controller
{

    public function index()
    {

        if(session('role') == 'dokter'){
            $pendaftaran_pasien = DB::table('data_pendaftar_perawatan')
                ->join('data_pasien', 'data_pendaftar_perawatan.pasien_id', '=', 'data_pasien.id')
                ->join('data_poli', 'data_pendaftar_perawatan.poli_id', '=', 'data_poli.id')
                ->join('data_dokter_poli', 'data_pendaftar_perawatan.dokter_poli_id', '=', 'data_dokter_poli.id')
                ->join('users', 'data_pendaftar_perawatan.dokter_id', '=', 'users.id')
                ->where('data_pendaftar_perawatan.status', '=', 'baru')
                ->where('data_pendaftar_perawatan.dokter_id', '=', Auth()->user()->id)
                ->select('data_pendaftar_perawatan.*', 'data_pasien.nama_pasien as nama_pasien', 'data_poli.nama_poli as nama_poli', 'users.name as nama_dokter')
                ->orderBy('data_pendaftar_perawatan.tanggal_pendaftaran', 'desc')
                ->get();
        } else {
            $pendaftaran_pasien = DB::table('data_pendaftar_perawatan')
            ->where('data_pendaftar_perawatan.status', '=', 'baru')
            ->join('data_pasien', 'data_pendaftar_perawatan.pasien_id', '=', 'data_pasien.id')
            ->join('data_poli', 'data_pendaftar_perawatan.poli_id', '=', 'data_poli.id')
            ->join('data_dokter_poli', 'data_pendaftar_perawatan.dokter_poli_id', '=', 'data_dokter_poli.id')
            ->join('users', 'data_pendaftar_perawatan.dokter_id', '=', 'users.id')
            ->select('data_pendaftar_perawatan.*', 'data_pasien.nama_pasien as nama_pasien', 'data_poli.nama_poli as nama_poli', 'users.name as nama_dokter')
            ->orderBy('data_pendaftar_perawatan.tanggal_pendaftaran', 'desc')
            ->get();
        }
        $pasiens = DB::table('data_pasien')->get();
        $polis = DB::table('data_poli')->get();
        $data = [
            'title' => 'Pendaftaran Pasien',
            'menu_slug' => 'rawat-jalan',
            'sub_menu_slug' => 'pendaftaran',
            'data' => $pendaftaran_pasien,
            'pasien' => $pasiens,
            'poli' => $polis,
        ];
        return view('perawatan.pendaftaran', $data);
    }

    public function get_icd()
    {
        $icd = DB::table('icds')->get();
        return response()->json($icd);
    }

    public function get_icd_detail($code)
    {
        $icd = DB::table('icds')->where('code', $code)->first();
        return response()->json($icd);
    }

    public function detail($id)
    {
        $pendaftaran_pasien = DB::table('data_pendaftar_perawatan')
            ->join('data_pasien', 'data_pendaftar_perawatan.pasien_id', '=', 'data_pasien.id')
            ->join('data_poli', 'data_pendaftar_perawatan.poli_id', '=', 'data_poli.id')
            ->join('data_dokter_poli', 'data_pendaftar_perawatan.dokter_poli_id', '=', 'data_dokter_poli.id')
            ->join('users', 'data_pendaftar_perawatan.dokter_id', '=', 'users.id')
            ->select(
                'data_pendaftar_perawatan.*',
                'data_pasien.id as id_pasien',
                'data_pasien.nama_pasien as nama_pasien',
                'data_pasien.jenis_kelamin as jenis_kelamin_pasien',
                'data_pasien.tempat_lahir as tempat_lahir_pasien',
                'data_pasien.tanggal_lahir as tanggal_lahir_pasien',
                'data_pasien.no_telepon as no_telepon_pasien',
                'data_pasien.no_ktp as no_ktp_pasien',
                'data_poli.nama_poli as nama_poli',
                'users.name as nama_dokter')
            ->where('data_pendaftar_perawatan.id', $id)->first();
        $pasiens = DB::table('data_pasien')->get();
        $polis = DB::table('data_poli')->get();
        $title = 'Detail Pendaftaran (' . $pendaftaran_pasien->no_pendaftaran . ')';
        $sub_menu_slug = 'pendaftaran';
        $no_antrian = null;
        $last_antrean = null;
        if (in_array($pendaftaran_pasien->status, ['antri', 'diperiksa'])) {
            $title = 'Detail Antrean (' . $pendaftaran_pasien->no_pendaftaran . ')';
            $sub_menu_slug = 'antre-poli';
            $no_antrian = DB::table('data_pendaftar_perawatan')
                ->join('conf_antrean_rawat_jalan', 'data_pendaftar_perawatan.id', '=', 'conf_antrean_rawat_jalan.pendaftaran_perawatan_id')
                ->select(
                    'data_pendaftar_perawatan.*',
                    'conf_antrean_rawat_jalan.no_antreaan as no_antrian')
                ->where('data_pendaftar_perawatan.id', $id)->first();
            $last_antrean = DB::table('conf_antrean_rawat_jalan')
                ->where('status', 'selesai')
                ->where('dokter_poli_id', $pendaftaran_pasien->dokter_poli_id)
                ->where('tanggal', date('Y-m-d'))
                ->orderBy('waktu_panggilan', 'desc')
                ->latest()
                ->first();
        }
        if (in_array($pendaftaran_pasien->status, ['batal', 'selesai'])) {
            $title = 'Detail Riwayat (' . $pendaftaran_pasien->no_pendaftaran . ')';
            $sub_menu_slug = 'riwayat-perawatan';
        }
        $rm = DB::table('data_rekam_medis_pasien')
            ->where('data_rekam_medis_pasien.pasien_id', $pendaftaran_pasien->pasien_id)
            ->join('data_pendaftar_perawatan', 'data_rekam_medis_pasien.pendaftaran_id', '=', 'data_pendaftar_perawatan.id')
            ->join('data_pasien', 'data_rekam_medis_pasien.pasien_id', '=', 'data_pasien.id')
            ->join('data_poli', 'data_pendaftar_perawatan.poli_id', '=', 'data_poli.id')
            ->join('users', 'data_rekam_medis_pasien.dokter_id', '=', 'users.id')
            ->select(
                'data_rekam_medis_pasien.*',
                'data_poli.nama_poli as nama_poli',
                'users.name as nama_dokter')
            ->get();
        $asuransi = null;
        if ($pendaftaran_pasien->is_use_asuransi) {
            $asuransi = DB::table('data_asuransi_pasien')
                ->join('data_asuransi', 'data_asuransi_pasien.asuransi_id', '=', 'data_asuransi.id')
                ->where('data_asuransi_pasien.id', $pendaftaran_pasien->asuransi_pasien_id)
                ->select('data_asuransi_pasien.*', 'data_asuransi.nama_asuransi as nama_asuransi')
                ->first();
        }
        $resep_line = null;
        if ($pendaftaran_pasien->status != 'baru') {
            $resep_line = DB::table('data_resep_obat_pasien_line')
                ->join('data_resep_obat_pasien', 'data_resep_obat_pasien_line.resep_obat_pasien_id', '=', 'data_resep_obat_pasien.id')
                ->where('data_resep_obat_pasien.pendaftaran_id', $pendaftaran_pasien->id)
                ->select(
                    'data_resep_obat_pasien_line.*',
                    'data_resep_obat_pasien.no_resep as no_resep'
                )
                ->get();
        }
        $data = [
            'title' => $title,
            'menu_slug' => 'rawat-jalan',
            'sub_menu_slug' => $sub_menu_slug,
            'data' => $pendaftaran_pasien,
            'pasien' => $pasiens,
            'poli' => $polis,
            'no_antrian' => $no_antrian,
            'last_antrean' => $last_antrean,
            'erm' => $rm,
            'asuransi' => $asuransi,
            'resep_line' => $resep_line,
        ];
        return view('perawatan.pendaftaran_detail', $data);
    }

    public function tambah(Request $request)
    {
        // dd($request->all());
        if(isset($request->pasien_baru)){
            $request->validate([
                'nama_pasien' => 'required',
                'no_identitas' => 'required|unique:data_pasien,no_ktp',
                'pilih_poli' => 'required',
                'pilih_dokter' => 'required',
            ],[
                'no_identitas.unique' => 'No KTP sudah terdaftar, pastikan No KTP yang anda masukkan benar dan belum terdaftar',
                'nama_pasien.required' => 'Nama Pasien tidak boleh kosong',
                'no_identitas.required' => 'No KTP tidak boleh kosong',
                'pilih_poli.required' => 'Poliklinik harus dipilih',
                'pilih_dokter.required' => 'Dokter harus dipilih',
            ]);
        }else{
            $request->validate([
                'pasien' => 'required',
                'pilih_poli' => 'required',
                'pilih_dokter' => 'required',
            ],[
                'pasien.required' => 'Pasien harus dipilih',
                'pilih_poli.required' => 'Poliklinik harus dipilih',
                'pilih_dokter.required' => 'Dokter harus dipilih',
            ]);
        }
        $pasien = new DataPasienModel();
        $data_pasien = [
            'nama_pasien' => $request->nama_pasien,
            'no_ktp' => $request->no_identitas
        ];
        $pasien_id = ($request->pasien_baru == 'on') ? $pasien->tambah_pasien_pendaftaran($data_pasien) : $request->pasien;
        $model = new DataPendaftarPerawatanModel();
        $pilih_dokter = explode(',', $request->pilih_dokter);
        $jadwal_dokter = null;
        if(count($pilih_dokter) == 3){
            $jadwal_dokter = $pilih_dokter[2];
        }
        $dokter_poli_id = explode(',', $request->pilih_dokter)[1];
        $dokter_id = explode(',', $request->pilih_dokter)[0];
        $pendaftaran_pasie_today = DB::table('data_pendaftar_perawatan')
            ->where('tanggal_pendaftaran', date('Y-m-d'))
            ->where('pasien_id', $pasien_id)
            ->where('dokter_poli_id', $dokter_poli_id)
            ->count();
        if($pendaftaran_pasie_today > 0){
            return redirect('rawat-jalan/pendaftaran')->with('error', 'Pasien sudah terdaftar di poli yang sama hari ini!');
        }
        $data = [
            'pasien_id' => $pasien_id,
            'poli_id' => $request->pilih_poli,
            'dokter_poli_id' => $dokter_poli_id,
            'dokter_id' => $dokter_id,
            'tanggal_pendaftaran' => date('Y-m-d H:i:s'),
            'tgl_periksa' => $request->tanggal_periksa,
            'jadwal_dokter_id' => $jadwal_dokter,
            'is_use_asuransi' => $request->is_use_asuransi ?? false,
            'asuransi_pasien_id' => $request->pasien_asuransi_id ?? null,
        ];
        // dd($data);
        $poli = DB::table('data_poli')->where('id', $request->pilih_poli)->first();
        $biaya_poliklinik = $poli->biaya_poli != null ? $poli->biaya_poli : 0;
        $dokter_poliklinik = DB::table('data_dokter_poli')->where('data_dokter_poli.id', $dokter_poli_id)->join('users', 'data_dokter_poli.dokter_id', '=', 'users.id')->select('data_dokter_poli.*', 'users.name as nama_dokter')->first();
        $biaya_dokter = $dokter_poliklinik->biaya_tambahan ?? 0;
        try {
            $create = $model->simpanDataAwal($data);
            $id_pendaftaran = $create;
            $data_tagihan = [
                'pasien_id' => $pasien_id,
                'perawatan_id' => $id_pendaftaran,
                'is_use_asuransi' => $request->is_use_asuransi ?? false,
                'asuransi_pasien_id' => $request->pasien_asuransi_id ?? null,
            ];
            try {
                $tagihan = new DataTagihanModel();
                $create_tagihan = $tagihan->simpanDataAwal($data_tagihan);
                $data_tagihan_line = (array) [];
                if($biaya_poliklinik > 0){
                    // array_push($data_tagihan_line, [
                    array_push($data_tagihan_line, [
                        'tagihan_pasien_id' => $create_tagihan,
                        'jenis_tagihan' => 'perawatan',
                        'nama_tagihan' => 'Biaya Poliklinik ' . $poli->nama_poli,
                        'harga' => $biaya_poliklinik,
                        'qty' => 1,
                        'total' => $biaya_poliklinik * 1,
                    ]);
                }
                if($biaya_dokter > 0){
                    array_push($data_tagihan_line, [
                        'tagihan_pasien_id' => $create_tagihan,
                        'jenis_tagihan' => 'perawatan',
                        'nama_tagihan' => 'Biaya Tambahan Dokter ' . $dokter_poliklinik->nama_dokter,
                        'harga' => $biaya_dokter,
                        'qty' => 1,
                        'total' => $biaya_dokter * 1,
                    ]);
                }
                // cek if any data tagihan line
                if(count($data_tagihan_line) > 0){
                    foreach ($data_tagihan_line as $key => $value) {
                        try {
                            $tagihan_line = DB::table('data_tagihan_pasien_line')->insert($value);
                        } catch (\Throwable $th) {
                            return redirect('rawat-jalan/detail/' . $id_pendaftaran)->with('error', 'Data detail tagihan ' . $value['nama_tagihan'] . ' gagal ditambahkan');
                        }
                    }
                }
            } catch (\Throwable $th) {
                return redirect('rawat-jalan/pendaftaran')->with('error', 'Data tagihan gagal ditambahkan');
            }
            return redirect('rawat-jalan/detail/' . $id_pendaftaran)->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect('rawat-jalan/pendaftaran')->with('error', 'Data gagal ditambahkan');
        }

    }

    public function screening(Request $request)
    {
        $request->validate([
            'keluhan' => 'required',
            'tekanan_darah' => 'required',
            'berat_badan' => 'required',
            'tinggi_badan' => 'required',
        ],[
            'keluhan.required' => 'Keluhan tidak boleh kosong',
            'tekanan_darah.required' => 'Tekanan darah tidak boleh kosong',
            'berat_badan.required' => 'Berat badan tidak boleh kosong',
            'tinggi_badan.required' => 'Tinggi badan tidak boleh kosong',
        ]);
        $model = new DataPendaftarPerawatanModel();
        $data = [
            'alergi_obat' => $request->alergi_obat ?? '',
            'keluhan' => $request->keluhan,
            'riwayat_penyakit' => $request->riwayat_penyakit ?? '',
            'riwayat_rawat_inap' => $request->riwayat_rawat_inap ?? '',
            'riwayat_operasi' => $request->riwayat_operasi ?? '',
            'tekanan_darah' => $request->tekanan_darah,
            'nadi' => $request->nadi ?? '',
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'suhu_badan' => $request->suhu_badan ?? '',
            // 'pemeriksaan_fisik_lainnya' => $request->pemeriksaan_fisik_lainnya ?? '',
            'cara_berjalan_pasien' => $request->cara_berjalan_pasien ?? '',
            'menopang_saat_akan_duduk' => $request->menopang_saat_akan_duduk ?? '',
            'resiko_jatuh' => $request->resiko_jatuh ?? '',
            'tindakan_pengamanan_jatuh' => $request->tindakan_pengamanan_jatuh ?? '',
            'perawat_pemeriksa' => $request->perawat_pemeriksa ?? '',
        ];
        if($request->alergi_obat != null || $request->alergi_obat != ''){
            try {
                $update = DB::table('data_pasien')->where('id', $request->id_pasien)->update(['alergi_obat' => $data['alergi_obat']]);
            } catch (\Throwable $th) {
                return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data gagal diupdate');
            }
        }
        try {
            $update = $model->where('id', $request->id_pendaftaran)->update($data);
            return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data gagal ditambahkan');
        } catch (\Exception $e) {
            return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data gagal ditambahkan');
        }
    }

    public function periksa(Request $request)
    {
        $request->validate([
            'keluhan_periksa' => 'required',
            'diagnosa' => 'required',
            'diagnosa_icd' => 'required',
            'tindakan' => 'required',
            'resep_obat' => 'required',
            'tanggal_pemeriksaan' => 'required',
        ],[
            'keluhan_periksa.required' => 'Diagnosa tidak harus diisi',
            'diagnosa.required' => 'Diagnosa tidak harus diisi',
            'diagnosa_icd.required' => 'Diagnosa ICD tidak harus diisi',
            'tindakan.required' => 'Tindakan tidak harus diisi',
            'resep_obat.required' => 'Resep obat tidak harus diisi',
            'tanggal_pemeriksaan.required' => 'Tanggal pemeriksaan tidak harus diisi',
        ]);
        $rm_model = new DataRekamMedisModel();
        // find rm by pasien_id
        $rm = $rm_model->where('pasien_id', $request->id_pasien)->first();
        if($rm == null){
            $data_rm = [
                'pasien_id' => $request->id_pasien,
                'keterangan' => '',
            ];
            try {
                $create_rm = $rm_model->create($data_rm);
                $rm = $rm_model->where('pasien_id', $request->id_pasien)->first();
            } catch (\Throwable $th) {
                return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data gagal ditambahkan');
            }
        }

        $data_rm_line = [
            'no_rekam_medis' => $rm->no_erm,
            'erm_id' => $rm->id,
            'pasien_id' => $request->id_pasien,
            'dokter_id' => Auth()->user()->id,
            'pendaftaran_id' => $request->id_pendaftaran,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'keluhan' => $request->keluhan_periksa,
            'diagnosa' => $request->diagnosa,
            'diagnosa_icd' => $request->diagnosa_icd,
            'tindakan' => $request->tindakan,
            'keterangan' => $request->keterangan ?? '',
            'resep_obat' => $request->resep_obat,
        ];


        $data_resep = [
            'pendaftaran_id' => $request->id_pendaftaran,
            'pasien_id' => $request->id_pasien,
            'dokter_poli_id' => $request->id_dokter_poli,
            'resep_dokter' => $request->resep_obat,
            'status' => 'draft',
            'keterangan' => '',
        ];

        try {
            $resep_model = new DataResepObatPasienModel();
            $resep_id = $resep_model->simpanDataAwal($data_resep);
        } catch (\Throwable $th) {
            return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data resep gagal ditambahkan');
        }

        try {
            DB::table('data_pendaftar_perawatan')->where('id', $request->id_pendaftaran)->update(['diagnosa' => $request->diagnosa]);
        } catch (\Throwable $th) {
            return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data gagal diupdate');
        }

        try {
            DB::table('data_rekam_medis_pasien')->insert($data_rm_line);
            return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data gagal ditambahkan');
        }
    }

    public function antri(Request $request)
    {
        $dokter_poli_id = $request->id_dokter_poli;
        $tgl_periksa = $request->tgl_periksa;
        $status = $request->status;
        if($status == 'baru'){
            $antrian = DB::table('conf_antrean_rawat_jalan')
                ->where('dokter_poli_id', $dokter_poli_id)
                ->where('tanggal', $tgl_periksa)
                ->count();
            if ($antrian == 0) {
                $antrian = 1;
            } else {
                $last_antrian = DB::table('conf_antrean_rawat_jalan')
                    ->where('dokter_poli_id', $dokter_poli_id)
                    ->where('tanggal', $tgl_periksa)
                    ->latest()
                    ->first();
                // remove leading zero and convert to integer
                $antrian = (int) ltrim($last_antrian->no_antreaan, '0');
                $antrian = $antrian + 1;
            }
            $no_antrian = str_pad($antrian, 3, '0', STR_PAD_LEFT);
            $data = [
                'tanggal' => $tgl_periksa,
                'no_antreaan' => $no_antrian,
                'pendaftaran_perawatan_id' => $request->id_pendaftaran,
                'pasien_id' => $request->id_pasien,
                'poli_id' => $request->id_poli,
                'dokter_poli_id' => $request->id_dokter_poli,
            ];
            try {
                try {
                    $create = DB::table('conf_antrean_rawat_jalan')->insert($data);
                } catch (\Throwable $th) {
                    return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data gagal ditambahkan');
                }
                $update = DB::table('data_pendaftar_perawatan')->where('id', $request->id_pendaftaran)->update(['status' => 'antri']);
                return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('success', 'Data berhasil diupdate');
            } catch (\Throwable $th) {
                return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data gagal diupdate');
            }
        }
        if($status == 'antri'){
            $data_perawatan = [
                'status' => 'diperiksa',
            ];
            $data_antrean = [
                'status' => 'selesai',
                'waktu_panggilan' => date('Y-m-d H:i:s'),
            ];
            try {
                try {
                    $update_antrean = DB::table('conf_antrean_rawat_jalan')->where('pendaftaran_perawatan_id', $request->id_pendaftaran)->update($data_antrean);
                } catch (\Throwable $th) {
                    return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data gagal diupdate');
                }
                $update = DB::table('data_pendaftar_perawatan')->where('id', $request->id_pendaftaran)->update($data_perawatan);
                return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('success', 'Data berhasil diupdate');
            } catch (\Throwable $th) {
                return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data gagal diupdate');
            }
        }
        if($status == 'diperiksa'){
            $rm_line = DB::table('data_rekam_medis_pasien')
                ->where('pendaftaran_id', $request->id_pendaftaran)
                ->first();
            if($rm_line == null){
                return redirect('rawat-jalan/detail/' . $request->id_pendaftaran . '/#btn-periksa')->with('error', 'Pemeriksaan belum dilakukan, silahkan lakukan pemeriksaan terlebih dahulu');
            }
            $data_perawatan = [
                'status' => 'selesai',
            ];
            $data_tagihan = [
                'status' => 'pending',
            ];
            try {
                try {
                    $update_tagihan = DB::table('data_tagihan_pasien')->where('perawatan_id', $request->id_pendaftaran)->update($data_tagihan);
                } catch (\Throwable $th) {
                    return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data tagihan gagal diupdate');
                }
                $update = DB::table('data_pendaftar_perawatan')->where('id', $request->id_pendaftaran)->update($data_perawatan);
                return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('success', 'Data berhasil diupdate');
            } catch (\Throwable $th) {
                return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data gagal diupdate');
            }
        }
    }

    public function batal(Request $request)
    {
        $data = [
            'status' => 'batal',
        ];
        $id = $request->id_pendaftaran;
        $antrean = DB::table('conf_antrean_rawat_jalan')->where('pendaftaran_perawatan_id', $id)->first();
        if ($antrean != null) {
            try {
                $batal_antrean = DB::table('conf_antrean_rawat_jalan')->where('pendaftaran_perawatan_id', $id)->update(['status' => 'batal']);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Data antrean gagal dihapus');
            }
        }
        $resep = DB::table('data_resep_obat_pasien')->where('pendaftaran_id', $id)->first();
        if ($resep != null) {
            try {
                $batal_resep = DB::table('data_resep_obat_pasien')->where('pendaftaran_id', $id)->update(['status' => 'batal']);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Data resep gagal dihapus');
            }
        }
        $tagihan = DB::table('data_tagihan_pasien')->where('perawatan_id', $id)->first();
        if ($tagihan != null) {
            try {
                $batal_tagihan = DB::table('data_tagihan_pasien')->where('perawatan_id', $id)->update(['status' => 'batal']);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Data tagihan gagal dihapus');
            }
        }
        try {
            $update = DB::table('data_pendaftar_perawatan')->where('id', $request->id_pendaftaran)->update($data);
            return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('success', 'Data berhasil diupdate');
        } catch (\Throwable $th) {
            return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data gagal diupdate');
        }
    }

    public function tambah_biaya(Request $request){
        if ($request->isMethod('post')) {
            $request->validate([
                'biaya' => 'required|numeric|min:0',
                'jenis_tagihan' => 'required',
                'keterangan' => 'required',
            ],[
                'biaya.required' => 'Biaya tidak boleh kosong',
                'biaya.numeric' => 'Biaya harus berupa angka',
                'biaya.min' => 'Biaya tidak boleh kurang dari 0',
                'jenis_tagihan.required' => 'Jenis tagihan tidak boleh kosong',
                'keterangan.required' => 'Keterangan tidak boleh kosong',
            ]);
            $id_pendaftaran = $request->id_pendaftaran;
            $id_pasien = $request->id_pasien;
            $biaya = (float) str_replace('.', '', $request->biaya);
            $jenis_tagihan = $request->jenis_tagihan;
            $keterangan = $request->keterangan;
            $tagihan = DB::table('data_tagihan_pasien')
                ->where('perawatan_id', $id_pendaftaran)
                ->first();
            if ($tagihan == null){
                $data_tagihan = [
                    'pasien_id' => $id_pasien,
                    'perawatan_id' => $id_pendaftaran,
                ];
                try {
                    $tagihan_model = new DataTagihanModel();
                    $tagihan_id = $tagihan_model->simpanDataAwal($data_tagihan);
                    $tagihan = DB::table('data_tagihan_pasien')
                        ->where('id', $tagihan_id)
                        ->first();
                } catch (\Throwable $th) {
                    return redirect('rawat-jalan/detail/' . $id_pendaftaran)->with('error', 'Data tagihan gagal ditambahkan');
                }
            }

            $data_tagihan_line = [
                'tagihan_pasien_id' => $tagihan->id,
                'jenis_tagihan' => $jenis_tagihan,
                'nama_tagihan' => $keterangan,
                'harga' => $biaya,
                'qty' => 1,
                'total' => $biaya * 1,
            ];

            try {
                $tagihan_line = DB::table('data_tagihan_pasien_line')->insert($data_tagihan_line);
                return redirect('rawat-jalan/detail/' . $id_pendaftaran)->with('success', 'Data tagihan berhasil ditambahkan');
            } catch (\Throwable $th) {
                return redirect('rawat-jalan/detail/' . $request->id_pendaftaran)->with('error', 'Data detail tagihan ' . $data_tagihan_line['nama_tagihan'] . ' gagal ditambahkan');
            }
        }
    }

    // -----------------------------------------------------------------------------

    public function get_antre_poli()
    {
        if(session('role') == 'dokter'){
            $pendaftaran_pasien = DB::table('data_pendaftar_perawatan')
                ->join('data_pasien', 'data_pendaftar_perawatan.pasien_id', '=', 'data_pasien.id')
                ->join('data_poli', 'data_pendaftar_perawatan.poli_id', '=', 'data_poli.id')
                ->join('data_dokter_poli', 'data_pendaftar_perawatan.dokter_poli_id', '=', 'data_dokter_poli.id')
                ->join('users', 'data_pendaftar_perawatan.dokter_id', '=', 'users.id')
                ->join('conf_antrean_rawat_jalan', 'data_pendaftar_perawatan.id', '=', 'conf_antrean_rawat_jalan.pendaftaran_perawatan_id')
                ->whereIn('data_pendaftar_perawatan.status', ['antri', 'diperiksa'])
                ->where('data_pendaftar_perawatan.dokter_id', auth()->user()->id)
                ->select('data_pendaftar_perawatan.*', 'data_pasien.nama_pasien as nama_pasien', 'data_poli.nama_poli as nama_poli', 'users.name as nama_dokter', 'conf_antrean_rawat_jalan.no_antreaan as no_antrian')
                ->orderBy('data_pendaftar_perawatan.tanggal_pendaftaran', 'desc')
                ->get();
        } else {
            $pendaftaran_pasien = DB::table('data_pendaftar_perawatan')
                ->where('data_pendaftar_perawatan.status', '=', 'antri')
                ->orWhere('data_pendaftar_perawatan.status', '=', 'diperiksa')
                ->join('data_pasien', 'data_pendaftar_perawatan.pasien_id', '=', 'data_pasien.id')
                ->join('data_poli', 'data_pendaftar_perawatan.poli_id', '=', 'data_poli.id')
                ->join('data_dokter_poli', 'data_pendaftar_perawatan.dokter_poli_id', '=', 'data_dokter_poli.id')
                ->join('users', 'data_pendaftar_perawatan.dokter_id', '=', 'users.id')
                ->join('conf_antrean_rawat_jalan', 'data_pendaftar_perawatan.id', '=', 'conf_antrean_rawat_jalan.pendaftaran_perawatan_id')
                ->select('data_pendaftar_perawatan.*', 'data_pasien.nama_pasien as nama_pasien', 'data_poli.nama_poli as nama_poli', 'users.name as nama_dokter', 'conf_antrean_rawat_jalan.no_antreaan as no_antrian')
                ->orderBy('data_pendaftar_perawatan.tanggal_pendaftaran', 'desc')
                ->get();
        }
        $pasiens = DB::table('data_pasien')->get();
        $polis = DB::table('data_poli')->get();
        $data = [
            'title' => 'Antrean Poliklinik',
            'menu_slug' => 'rawat-jalan',
            'sub_menu_slug' => 'antre-poli',
            'data' => $pendaftaran_pasien,
            'pasien' => $pasiens,
            'poli' => $polis,
        ];
        return view('perawatan.pendaftaran', $data);
    }

    public function get_riwayat_perawatan()
    {
        if(session('role') == 'dokter'){
            $pendaftaran_pasien = DB::table('data_pendaftar_perawatan')
                ->join('data_pasien', 'data_pendaftar_perawatan.pasien_id', '=', 'data_pasien.id')
                ->join('data_poli', 'data_pendaftar_perawatan.poli_id', '=', 'data_poli.id')
                ->join('data_dokter_poli', 'data_pendaftar_perawatan.dokter_poli_id', '=', 'data_dokter_poli.id')
                ->join('users', 'data_pendaftar_perawatan.dokter_id', '=', 'users.id')
                ->whereIn('data_pendaftar_perawatan.status', ['selesai', 'batal'])
                ->where('data_pendaftar_perawatan.dokter_id', auth()->user()->id)
                ->orderBy('data_pendaftar_perawatan.tgl_periksa', 'desc')
                ->select('data_pendaftar_perawatan.*', 'data_pasien.nama_pasien as nama_pasien', 'data_poli.nama_poli as nama_poli', 'users.name as nama_dokter')
                ->get();
        } else {
            $pendaftaran_pasien = DB::table('data_pendaftar_perawatan')
                ->whereIn('data_pendaftar_perawatan.status', ['selesai', 'batal'])
                ->join('data_pasien', 'data_pendaftar_perawatan.pasien_id', '=', 'data_pasien.id')
                ->join('data_poli', 'data_pendaftar_perawatan.poli_id', '=', 'data_poli.id')
                ->join('data_dokter_poli', 'data_pendaftar_perawatan.dokter_poli_id', '=', 'data_dokter_poli.id')
                ->join('users', 'data_pendaftar_perawatan.dokter_id', '=', 'users.id')
                ->orderBy('data_pendaftar_perawatan.tgl_periksa', 'desc')
                ->select('data_pendaftar_perawatan.*', 'data_pasien.nama_pasien as nama_pasien', 'data_poli.nama_poli as nama_poli', 'users.name as nama_dokter')
                ->get();
        }
        $pasiens = DB::table('data_pasien')->get();
        $polis = DB::table('data_poli')->get();
        $data = [
            'title' => 'Riwayat Perawatan',
            'menu_slug' => 'rawat-jalan',
            'sub_menu_slug' => 'riwayat-perawatan',
            'data' => $pendaftaran_pasien,
            'pasien' => $pasiens,
            'poli' => $polis,
        ];
        return view('perawatan.pendaftaran', $data);
    }

    public function delete(Request $request)
    {
        // related table
        // data_rekam_medis_pasien -> pendaftaran_id
        // conf_antrean_rawat_jalan -> pendaftaran_perawatan_id
        // data_tagihan_pasien -> perawatan_id
        // data_tagihan_pasien_line -> tagihan_pasien_id
        $id = $request->id;
        if($request->isMethod('delete')){
            $pendaftaran = DB::table('data_pendaftar_perawatan')->where('id', $id)->first();
            $antrean = DB::table('conf_antrean_rawat_jalan')->where('pendaftaran_perawatan_id', $id)->first();
            if ($antrean != null) {
                try {
                    $update_antrean = DB::table('conf_antrean_rawat_jalan')->where('pendaftaran_perawatan_id', $id)->update(['status' => 'batal', 'pendaftaran_perawatan_id' => null]);
                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', 'Data antrean gagal diupdate' . $th->getMessage());
                }
            }
            $rm = DB::table('data_rekam_medis_pasien')->where('pendaftaran_id', $id)->first();
            if ($rm != null) {
                try {
                    $update_rm = DB::table('data_rekam_medis_pasien')->where('pendaftaran_id', $id)->update(['pendaftaran_id' => null]);
                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', 'Data rekam medis gagal diupdate' . $th->getMessage());
                }
            }
            $resep = DB::table('data_resep_obat_pasien')->where('pendaftaran_id', $id)->first();
            if ($resep != null) {
                try {
                    $update_resep = DB::table('data_resep_obat_pasien')->where('pendaftaran_id', $id)->update(['pendaftaran_id' => null, 'status' => 'batal']);
                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', 'Data resep gagal diupdate' . $th->getMessage());
                }
            }
            $tagihan = DB::table('data_tagihan_pasien')->where('perawatan_id', $id)->first();
            if ($tagihan != null) {
                try {
                    $update_tagihan = DB::table('data_tagihan_pasien')->where('perawatan_id', $id)->update(['perawatan_id' => null, 'status' => 'batal']);
                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', 'Data tagihan gagal diupdate' . $th->getMessage());
                }
            }
            try {
                $delete = DB::table('data_pendaftar_perawatan')->where('id', $id)->delete();
                return redirect('rawat-jalan/pendaftaran')->with('success', 'Data berhasil dihapus');
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Data gagal dihapus');
            }
        } else {
            return redirect()->back()->with('error', 'Data gagal dihapus');
        }
    }
}
