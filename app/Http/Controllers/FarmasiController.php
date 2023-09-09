<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmasiController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data Obat',
            'menu_slug' => 'farmasi',
            'sub_menu_slug' => 'data-obat',
            'data' => DB::table('data_obat')->get()
        ];
        return view('farmasi.data_obat', $data);
    }

    public function detail($id)
    {
        $obat = DB::table('data_obat')->where('id', $id)->first();
        $data = [
            'title' => 'Detail Obat (' . $obat->kode_obat . ')' . $obat->nama_obat,
            'menu_slug' => 'farmasi',
            'sub_menu_slug' => 'data-obat',
            'data' => DB::table('data_obat')->where('id', $id)->first()
        ];
        return view('farmasi.detail_obat', $data);
    }

    public function tambah(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'kode_obat' => 'required|unique:data_obat',
                'nama_obat' => 'required',
                'jenis_obat' => 'required',
                'harga_obat' => 'required|min:0',
                'stok_obat' => 'required|numeric|min:0',
            ], [
                'kode_obat.unique' => 'Kode Obat sudah terdaftar, pastikan Kode Obat yang anda masukkan benar dan belum terdaftar',
                'harga_obat.min' => 'Harga Obat tidak boleh kurang dari 0',
                'stok_obat.min' => 'Stok Obat tidak boleh kurang dari 0',
                'stok_obat.numeric' => 'Stok Obat harus berupa angka',
                'kode_obat.required' => 'Kode Obat tidak boleh kosong',
                'nama_obat.required' => 'Nama Obat tidak boleh kosong',
                'jenis_obat.required' => 'Jenis Obat tidak boleh kosong',
                'harga_obat.required' => 'Harga Obat tidak boleh kosong',
                'stok_obat.required' => 'Stok Obat tidak boleh kosong',
            ]);
            $kode_obat = $request->kode_obat;
            $nama_obat = $request->nama_obat;
            $jenis_obat = $request->jenis_obat;
            $harga_obat = $request->harga_obat;
            $harga_obat = str_replace('.', '', $harga_obat);
            $stok_obat = $request->stok_obat;

            $data = [
                'kode_obat' => $kode_obat,
                'nama_obat' => $nama_obat,
                'jenis_obat' => $jenis_obat,
                'harga_obat' => $harga_obat,
                'stok_obat' => $stok_obat,
                'keterangan' => '',
            ];

            try {
                DB::table('data_obat')->insert($data);
                $id = DB::getPdo()->lastInsertId();
                return redirect('data-obat/detail/' . $id)->with('success', 'Data Obat berhasil ditambahkan');
            } catch (\Throwable $th) {
                return redirect('data-obat/')->with('error', 'Data Obat gagal ditambahkan ' . $th->getMessage());
            }
        }
    }

    public function edit(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'nama_obat' => 'required',
                'jenis_obat' => 'required',
                'harga_obat' => 'required|min:0',
                'stok_obat' => 'required|numeric|min:0',
            ], [
                'harga_obat.min' => 'Harga Obat tidak boleh kurang dari 0',
                'stok_obat.min' => 'Stok Obat tidak boleh kurang dari 0',
                'stok_obat.numeric' => 'Stok Obat harus berupa angka',
                'nama_obat.required' => 'Nama Obat tidak boleh kosong',
                'jenis_obat.required' => 'Jenis Obat tidak boleh kosong',
                'harga_obat.required' => 'Harga Obat tidak boleh kosong',
                'stok_obat.required' => 'Stok Obat tidak boleh kosong',
            ]);
            $id = $request->id;
            $nama_obat = $request->nama_obat;
            $jenis_obat = $request->jenis_obat;
            $harga_obat = $request->harga_obat;
            $harga_obat = str_replace('.', '', $harga_obat);
            $harga_obat = str_replace(',', '', $harga_obat);
            $stok_obat = $request->stok_obat;

            $data = [
                'nama_obat' => $nama_obat,
                'jenis_obat' => $jenis_obat,
                'harga_obat' => $harga_obat,
                'stok_obat' => $stok_obat,
                'keterangan' => '',
            ];

            try {
                DB::table('data_obat')->where('id', $id)->update($data);
                return redirect('data-obat/detail/' . $id)->with('success', 'Data Obat berhasil diubah');
            } catch (\Throwable $th) {
                return redirect('data-obat/detail/' . $id)->with('error', 'Data Obat gagal diubah ' . $th->getMessage());
            }
        }
    }

    public function hapus(Request $request)
    {
        if ($request->isMethod('delete')) {
            $id = $request->id_obat;
            $resep_obat_line = DB::table('data_resep_obat_pasien_line')->where('obat_id', $id)->get();
            if (count($resep_obat_line) > 0) {
                $update = [
                    'obat_id' => null,
                ];
                try {
                    DB::table('data_resep_obat_pasien_line')->where('obat_id', $id)->update($update);
                } catch (\Throwable $th) {
                    return redirect('data-obat/')->with('error', 'Data Obat gagal dihapus ' . $th->getMessage());
                }
            }
            try {
                DB::table('data_obat')->where('id', $id)->delete();
                return redirect('data-obat/')->with('success', 'Data Obat berhasil dihapus');
            } catch (\Throwable $th) {
                return redirect('data-obat/')->with('error', 'Data Obat gagal dihapus ' . $th->getMessage());
            }
        }
    }

    // get obat

    public function get_obat(Request $request)
    {
        if($request->id){
            $obat = DB::table('data_obat')->where('id', $request->id)->first();
            return response()->json($obat);
        }
        $obat = DB::table('data_obat')->get();
        return response()->json($obat);
    }

    // resep obat

    public function resep_obat()
    {
        $resep_obat = DB::table('data_resep_obat_pasien')
            ->join('data_pendaftar_perawatan', 'data_resep_obat_pasien.pendaftaran_id', '=', 'data_pendaftar_perawatan.id')
            ->join('data_pasien', 'data_resep_obat_pasien.pasien_id', '=', 'data_pasien.id')
            ->join('data_rekam_medis', 'data_resep_obat_pasien.pasien_id', '=', 'data_rekam_medis.pasien_id')
            ->join('data_dokter_poli', 'data_resep_obat_pasien.dokter_poli_id', '=', 'data_dokter_poli.id')
            ->join('data_poli', 'data_dokter_poli.poli_id', '=', 'data_poli.id')
            ->join('users', 'data_dokter_poli.dokter_id', '=', 'users.id')
            ->select('data_resep_obat_pasien.*', 'data_pendaftar_perawatan.no_pendaftaran as no_registrasi', 'data_pasien.nama_pasien', 'data_rekam_medis.no_erm', 'data_poli.nama_poli', 'users.name as nama_dokter')
            ->orderBy('data_resep_obat_pasien.id', 'desc')
            ->get();
        $data = [
            'title' => 'Resep Obat',
            'menu_slug' => 'farmasi',
            'sub_menu_slug' => 'resep-obat',
            'data' => $resep_obat
        ];
        return view('farmasi.data_resep_obat', $data);
    }

    public function resep_obat_detail($no_resep){
        $resep_obat = DB::table('data_resep_obat_pasien')
            ->join('data_pendaftar_perawatan', 'data_resep_obat_pasien.pendaftaran_id', '=', 'data_pendaftar_perawatan.id')
            ->join('data_pasien', 'data_resep_obat_pasien.pasien_id', '=', 'data_pasien.id')
            ->join('data_rekam_medis', 'data_resep_obat_pasien.pasien_id', '=', 'data_rekam_medis.pasien_id')
            ->join('data_dokter_poli', 'data_resep_obat_pasien.dokter_poli_id', '=', 'data_dokter_poli.id')
            ->join('data_poli', 'data_dokter_poli.poli_id', '=', 'data_poli.id')
            ->join('users', 'data_dokter_poli.dokter_id', '=', 'users.id')
            ->select('data_resep_obat_pasien.*', 'data_pendaftar_perawatan.no_pendaftaran', 'data_pendaftar_perawatan.tgl_periksa', 'data_pendaftar_perawatan.diagnosa', 'data_pendaftar_perawatan.asuransi_pasien_id', 'data_pendaftar_perawatan.is_use_asuransi', 'data_pasien.nama_pasien', 'data_rekam_medis.no_erm', 'data_poli.nama_poli', 'users.name as nama_dokter')
            ->where('data_resep_obat_pasien.no_resep', $no_resep)
            ->first();
        $asuransi = null;
        if($resep_obat->is_use_asuransi == 1){
            $asuransi = DB::table('data_asuransi_pasien')
                ->join('data_asuransi', 'data_asuransi_pasien.asuransi_id', '=', 'data_asuransi.id')
                ->where('data_asuransi_pasien.id', $resep_obat->asuransi_pasien_id)
                ->select('data_asuransi_pasien.*', 'data_asuransi.nama_asuransi')
                ->first();
        }
        $line = DB::table('data_resep_obat_pasien_line')
            ->where('data_resep_obat_pasien_line.resep_obat_pasien_id', $resep_obat->id)
            ->join('data_obat', 'data_resep_obat_pasien_line.obat_id', '=', 'data_obat.id')
            ->select('data_resep_obat_pasien_line.*', 'data_obat.kode_obat', 'data_obat.nama_obat')
            ->get();
        $data = [
            'title' => 'Resep Obat',
            'menu_slug' => 'farmasi',
            'sub_menu_slug' => 'resep-obat',
            'data' => $resep_obat,
            'data_line' => $line,
            'asuransi' => $asuransi,
        ];
        return view('farmasi.detail_resep_obat', $data);
    }

    public function resep_obat_tambah_obat(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'obat_id' => 'required',
                'jumlah' => 'required|numeric|min:0',
                'satuan' => 'required',
                'aturan_pakai' => 'required',
                'keterangan' => 'required',
            ], [
                'resep_obat_pasien_id.required' => 'Resep Obat tidak boleh kosong',
                'obat_id.required' => 'Obat tidak boleh kosong',
                'jumlah.required' => 'Jumlah tidak boleh kosong',
                'jumlah.numeric' => 'Jumlah harus berupa angka',
                'jumlah.min' => 'Jumlah tidak boleh kurang dari 0',
                'satuan.required' => 'Satuan tidak boleh kosong',
                'aturan_pakai.required' => 'Aturan Pakai tidak boleh kosong',
                'keterangan.required' => 'Keterangan tidak boleh kosong',
            ]);
            $resep_obat_pasien_id = $request->resep_obat_pasien_id;
            $pendaftaran_id = $request->pendaftaran_id;
            $obat_id = $request->obat_id;
            $qty = $request->jumlah;
            $satuan = $request->satuan;
            $aturan_pakai = $request->aturan_pakai;
            $keterangan = $request->keterangan;
            $nama_obat = DB::table('data_obat')->where('id', $obat_id)->first()->nama_obat;
            $data = [
                'resep_obat_pasien_id' => $resep_obat_pasien_id,
                'obat_id' => $obat_id,
                'obat' => $nama_obat,
                'qty' => $qty,
                'satuan' => $satuan,
                'aturan_pakai' => $aturan_pakai,
                'keterangan' => $keterangan,
            ];
            $tagihan = DB::table('data_tagihan_pasien')
                ->where('perawatan_id', $pendaftaran_id)
                ->first();
            $obat = DB::table('data_obat')->where('id', $obat_id)->first();

            $add_id = null;
            try {
                $add_id = DB::table('data_resep_obat_pasien_line')->insertGetId($data);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Data Obat gagal ditambahkan '.$th->getMessage());
            }

            try {
                DB::table('data_obat')->where('id', $obat_id)->decrement('stok_obat', $qty);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Stok Obat gagal diperbaharui '.$th->getMessage());
            }

            $data_tagihan_line = [
                'tagihan_pasien_id' => $tagihan->id,
                'jenis_tagihan' => 'obat',
                'nama_tagihan' => '('.$obat->kode_obat.') '.$obat->nama_obat,
                'harga' => $obat->harga_obat,
                'qty' => $qty,
                'total' => $obat->harga_obat * $qty,
                'resep_obat_line_id' => $add_id,
            ];
            try {
                DB::table('data_tagihan_pasien_line')->insert($data_tagihan_line);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Data Tagihan gagal ditambahkan '.$th->getMessage());
            }
            return redirect()->back()->with('success', 'Data Obat berhasil ditambahkan');

        }
    }

    public function resep_obat_change_status(Request $request){
        // dd($request);
        if($request->isMethod('post')){
            $id = $request->id;
            $status = $request->status;
            $data = [
                'status' => $status,
            ];
            try {
                DB::table('data_resep_obat_pasien')->where('id', $id)->update($data);
                $json_res = [
                    'status' => 'success',
                    'message' => 'Status Resep Obat berhasil diubah',
                ];
                return response()->json($json_res);
            } catch (\Throwable $th) {
                return redirect('resep-obat/detail/'.$id)->with('error', 'Status Resep Obat gagal diubah '.$th->getMessage());
            }
        }
    }

    public function resep_obat_hapus(Request $request){
        if($request->isMethod('delete')){
            $id = $request->id_resep;
            $line = DB::table('data_resep_obat_pasien_line')->where('resep_obat_pasien_id', $id)->get();
            if(count($line) > 0){
                foreach($line as $l){
                    $delete = $this->delete_obat_line($l->id);
                    if($delete == TRUE){
                        continue;
                    } else {
                        return redirect()->back()->with('error', 'Data Obat gagal dihapus');
                    }
                }
            }
            try {
                DB::table('data_resep_obat_pasien')->where('id', $id)->delete();
                return redirect('resep-obat/')->with('success', 'Data Resep Obat berhasil dihapus');
            } catch (\Throwable $th) {
                return redirect('resep-obat/')->with('error', 'Data Resep Obat gagal dihapus '.$th->getMessage());
            }
        }
    }

    public function resep_obat_hapus_obat(Request $request){
        if($request->isMethod('delete')){
            $id = $request->id_obat_line;
            // call function delete_obat_line
            $delete = $this->delete_obat_line($id);
            if($delete == TRUE){
                return redirect()->back()->with('success', 'Data Obat berhasil dihapus');
            } else {
                return redirect()->back()->with('error', 'Data Obat gagal dihapus');
            }
        }
    }

    private function delete_obat_line($id){
        $resep_obat_line = DB::table('data_resep_obat_pasien_line')->where('id', $id)->first();
        try {
            DB::table('data_tagihan_pasien_line')->where('resep_obat_line_id', $id)->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data Tagihan gagal dihapus '.$th->getMessage());
        }
        try {
            DB::table('data_obat')->where('id', $resep_obat_line->obat_id)->increment('stok_obat', $resep_obat_line->qty);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Stok Obat gagal diperbaharui '.$th->getMessage());
        }
        try {
            DB::table('data_resep_obat_pasien_line')->where('id', $id)->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data Obat gagal dihapus '.$th->getMessage());
        }
        return True;
    }
}
