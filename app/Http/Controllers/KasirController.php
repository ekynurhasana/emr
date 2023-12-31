<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        $tagihan = DB::table('data_tagihan_pasien')
            ->join('data_pasien', 'data_tagihan_pasien.pasien_id', '=', 'data_pasien.id')
            ->join('data_pendaftar_perawatan', 'data_tagihan_pasien.perawatan_id', '=', 'data_pendaftar_perawatan.id')
            ->select('data_tagihan_pasien.*', 'data_pasien.nama_pasien', 'data_pendaftar_perawatan.no_pendaftaran')
            ->where('data_tagihan_pasien.status', '!=', 'draft')
            ->orderBy('data_tagihan_pasien.id', 'desc')
            ->get();
        // dd($tagihan);
        $data = [
            'title' => 'Data Pembayaran Pasien',
            'menu_slug' => 'kasir',
            'sub_menu_slug' => 'pembayaran-pasien',
            'data' => $tagihan,
        ];
        return view('kasir.data_tagihan', $data);
    }

    public function get_data_draft()
    {
        $tagihan = DB::table('data_tagihan_pasien')
            ->join('data_pasien', 'data_tagihan_pasien.pasien_id', '=', 'data_pasien.id')
            ->join('data_pendaftar_perawatan', 'data_tagihan_pasien.perawatan_id', '=', 'data_pendaftar_perawatan.id')
            ->select('data_tagihan_pasien.*', 'data_pasien.nama_pasien', 'data_pendaftar_perawatan.no_pendaftaran')
            ->where('data_tagihan_pasien.status', 'draft')
            ->orderBy('data_tagihan_pasien.id', 'desc')
            ->get();
        $pasien = DB::table('data_pasien')->get();
            $data = [
            'title' => 'Data Tagihan Pasien (Draft)',
            'menu_slug' => 'kasir',
            'sub_menu_slug' => 'tagihan-pasien-draft',
            'data' => $tagihan,
            'pasien' => $pasien
        ];
        return view('kasir.data_tagihan', $data);
    }

    public function detail($id)
    {
        $tagihan = DB::table('data_tagihan_pasien')
            ->join('data_pasien', 'data_tagihan_pasien.pasien_id', '=', 'data_pasien.id')
            ->join('data_pendaftar_perawatan', 'data_tagihan_pasien.perawatan_id', '=', 'data_pendaftar_perawatan.id')
            ->select('data_tagihan_pasien.*', 'data_pasien.nama_pasien', 'data_pendaftar_perawatan.id as pendaftaran_id','data_pendaftar_perawatan.no_pendaftaran',  'data_pendaftar_perawatan.tgl_periksa')
            ->where('data_tagihan_pasien.id', $id)
            ->first();

        $tagihan_line = DB::table('data_tagihan_pasien_line')
            ->where('tagihan_pasien_id', $id)
            ->get();
        $erm = DB::table('data_rekam_medis')
            ->where('pasien_id', $tagihan->pasien_id)
            ->first();
        $no_erm = null;
        if ($erm) {
            $no_erm = $erm->no_erm;
        }
        $title = 'Detail Pembayaran Pasien (' . $tagihan->no_tagihan . ')';
        $sub_menu_slug = 'pembayaran-pasien';
        if ($tagihan->status == 'draft') {
            $title = 'Detail Tagihan Pasien (' . $tagihan->no_tagihan . ')';
            $sub_menu_slug = 'tagihan-pasien-draft';
        }
        $total_perawatan = 0;
        $total_obat = 0;
        $total_tidakan = 0;
        $total_administrasi = 0;
        $total = 0;
        $total_asuransi = 0;
        $total_tagihan = 0;
        foreach ($tagihan_line as $key => $value) {
            if ($value->jenis_tagihan == 'perawatan') {
                $total_perawatan += $value->total;
            } elseif ($value->jenis_tagihan == 'obat') {
                $total_obat += $value->total;
            } elseif ($value->jenis_tagihan == 'tindakan') {
                $total_tidakan += $value->total;
            } elseif ($value->jenis_tagihan == 'administrasi') {
                $total_administrasi += $value->total;
            }
            $total += $value->total;
        }
        $asuransi = null;
        if ($tagihan->is_use_asuransi == 1) {
            $asuransi_pasien = DB::table('data_asuransi_pasien')
                ->where('id', $tagihan->asuransi_pasien_id)
                ->first();
            $asuransi = DB::table('data_asuransi')
                ->where('id', $asuransi_pasien->asuransi_id)
                ->first();
            $tanggungan = DB::table('data_asuransi_pasien_tanggungan')
                ->where('asuransi_pasien_id', $tagihan->asuransi_pasien_id)
                ->get();
            foreach ($tanggungan as $key => $value) {
                $total_sementara = 0;
                if ($value->jenis_tanggungan == 'perawatan') {
                    $total_sementara += $total_perawatan;
                } elseif ($value->jenis_tanggungan == 'obat') {
                    $total_sementara += $total_obat;
                } elseif ($value->jenis_tanggungan == 'tindakan') {
                    $total_sementara += $total_tidakan;
                } elseif ($value->jenis_tanggungan == 'administrasi') {
                    $total_sementara += $total_administrasi;
                } elseif ($value->jenis_tanggungan == 'all') {
                    $total_sementara += $total;
                }
                if($value->is_limit == 1){
                    $sisa_limit = $value->sisa_limit;
                    if ($sisa_limit >= $total_sementara) {
                        $total_asuransi += $total_sementara;
                    } else {
                        $total_asuransi += $sisa_limit;
                    }
                }else{
                    $total_asuransi += $total_sementara;
                }
            }
        }
        $total_tagihan = $total - $total_asuransi;
        $data = [
            'title' => $title,
            'menu_slug' => 'kasir',
            'sub_menu_slug' => $sub_menu_slug,
            'data' => $tagihan,
            'data_line' => $tagihan_line,
            'total_perawatan' => $total_perawatan,
            'total_obat' => $total_obat,
            'total_tindakan' => $total_tidakan,
            'total_administrasi' => $total_administrasi,
            'total' => $total,
            'total_asuransi' => $total_asuransi,
            'total_tagihan' => $total_tagihan,
            'asuransi' => $asuransi,
            'no_erm' => $no_erm,
        ];
        return view('kasir.detail_tagihan', $data);
    }

    public function tambah_line(Request $request)
    {
        $request->validate([
            'jenis_tagihan' => 'required',
            'detail_tagihan' => 'required',
            'harga' => 'required|numeric|min:1',
            'jumlah' => 'required|numeric|min:1',
        ], [
            'jenis_tagihan.required' => 'Jenis tagihan harus diisi',
            'detail_tagihan.required' => 'Detail tagihan harus diisi',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga minimal 1',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah minimal 1',
        ]);
        $jenis_tagihan = $request->jenis_tagihan;
        $detail_tagihan = $request->detail_tagihan;
        $harga = str_replace('.', '', $request->harga);
        $jumlah = $request->jumlah;
        $total = $harga * $jumlah;
        $tagihan_id = $request->id_tagihan;
        $data = [
            'jenis_tagihan' => $jenis_tagihan,
            'nama_tagihan' => $detail_tagihan,
            'harga' => $harga,
            'qty' => $jumlah,
            'total' => $total,
            'tagihan_pasien_id' => $tagihan_id,
        ];
        try {
            $create = DB::table('data_tagihan_pasien_line')->insert($data);
            return redirect()->back()->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data gagal ditambahkan ' . $th->getMessage());
        }
    }

    public function delete(Request $request)
    {
        $id = $request->id_tagihan;
        $tagihan_line = DB::table('data_tagihan_pasien_line')
            ->where('tagihan_pasien_id', $id)
            ->get();
        for ($i = 0; $i < count($tagihan_line); $i++) {
            try {
                DB::table('data_tagihan_pasien_line')->where('id', $tagihan_line[$i]->id)->delete();
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Data gagal dihapus ' . $th->getMessage());
            }
        }
        try {
            DB::table('data_tagihan_pasien')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data gagal dihapus ' . $th->getMessage());
        }
    }

    public function delete_line(Request $request)
    {
        $id = $request->id_tagihan_line;
        try {
            DB::table('data_tagihan_pasien_line')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data gagal dihapus ' . $th->getMessage());
        }
    }

    public function change_state(Request $request){
        $id = $request->id_tagihan;
        $status = $request->status;
        try {
            DB::table('data_tagihan_pasien')
                ->where('id', $id)
                ->update([
                    'status' => $status
                ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah status tagihan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengubah status tagihan'
            ]);
        }
    }
}
