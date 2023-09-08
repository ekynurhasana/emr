<?php

namespace App\Http\Controllers;

use App\Models\DataAsuransiModel;
use App\Models\DataAsuransiPasienModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsuransiController extends Controller
{
    public function index()
    {
        $asuransi = DB::table('data_asuransi')
            ->get();
        $data = [
            'title' => 'Data Asuransi',
            'menu_slug' => 'asuransi',
            'sub_menu_slug' => 'asuransi',
            'data' => $asuransi,
        ];
        return view('asuransi.data_asuransi', $data);
    }

    public function detail($id)
    {
        $asuransi = DB::table('data_asuransi')
            ->where('id', $id)
            ->first();
        $tipe_asuransi = DB::table('data_asuransi_tipe')
            ->where('asuransi_id', $id)
            ->get();
        $data = [
            'title' => 'Detail Asuransi',
            'menu_slug' => 'asuransi',
            'sub_menu_slug' => 'asuransi',
            'data' => $asuransi,
            'data_line' => $tipe_asuransi,
        ];
        return view('asuransi.detail_asuransi', $data);
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'nama_asuransi' => 'required',
            'kode_asuransi' => 'required',
        ], [
            'nama_asuransi.required' => 'Nama asuransi tidak boleh kosong',
            'kode_asuransi.required' => 'Deskripsi asuransi tidak boleh kosong',
        ]);
        $nama = $request->nama_asuransi;
        $kode = $request->kode_asuransi;
        $data = [
            'nama_asuransi' => $nama,
            'kode_asuransi' => $kode,
        ];
        try {
            $asuransi_model = new DataAsuransiModel;
            $asuransi = $asuransi_model->tambah_asuransi($data);
            return redirect('asuransi/detail/'. $asuransi)->with('success', 'Berhasil menambahkan asuransi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menambahkan asuransi');
        }
    }

    public function tambah_tipe(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_asuransi_tipe' => 'required',
            'deskripsi' => 'required',
            'tanggungan' => 'required',
        ], [
            'nama_asuransi_tipe.required' => 'Nama tipe asuransi tidak boleh kosong',
            'deskripsi.required' => 'Deskripsi tipe asuransi tidak boleh kosong',
            'tanggungan.required' => 'Tanggungan harus diisi',
        ]);
        $asuransi_id = $request->asuransi_id;
        $nama = $request->nama_asuransi_tipe;
        $deskripsi = $request->deskripsi;
        $tanggungan = [];
        foreach ($request->tanggungan as $key => $value) {
            $tanggungan[] = [
                'value' => $value
            ];
        }
        // convert to json
        $tanggungan = json_encode($tanggungan);
        $data = [
            'asuransi_id' => $asuransi_id,
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'tanggungan' => $tanggungan,
        ];
        // dd($data);
        try {
            DB::table('data_asuransi_tipe')
                ->insert($data);
            return redirect()->back()->with('success', 'Berhasil menambahkan tipe asuransi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menambahkan tipe asuransi');
        }
    }

    public function aktifkan(Request $request)
    {
        $id = $request->id;
        try {
            DB::table('data_asuransi')
                ->where('id', $id)
                ->update([
                    'status' => 'aktif'
                ]);
            return redirect()->back()->with('success', 'Berhasil mengubah status asuransi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal mengubah status asuransi');
        }
    }
    public function nonaktifkan(Request $request)
    {
        $id = $request->id;
        try {
            DB::table('data_asuransi')
                ->where('id', $id)
                ->update([
                    'status' => 'tidak_aktif'
                ]);
            return redirect()->back()->with('success', 'Berhasil mengubah status asuransi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal mengubah status asuransi');
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'nama_asuransi' => 'required',
            'kode_asuransi' => 'required',
        ], [
            'nama_asuransi.required' => 'Nama asuransi tidak boleh kosong',
            'kode_asuransi.required' => 'Deskripsi asuransi tidak boleh kosong',
        ]);
        $id = $request->id;
        $nama = $request->nama_asuransi;
        $kode = $request->kode_asuransi;
        $data = [
            'nama_asuransi' => $nama,
            'kode_asuransi' => $kode,
            'keterangan' => $request->keterangan ?? '',
        ];
        try {
            DB::table('data_asuransi')
                ->where('id', $id)
                ->update($data);
            return redirect()->back()->with('success', 'Berhasil mengubah asuransi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal mengubah asuransi');
        }
    }

    public function delete(Request $request)
    {
        $id = $request->id_asuransi;
        $asuransi = DB::table('data_asuransi')
            ->where('id', $id)
            ->first();
        if ($asuransi->status == 'aktif') {
            return redirect()->back()->with('error', 'Gagal menghapus asuransi, nonaktifkan terlebih dahulu');
        }
        $tipe_asuransi = DB::table('data_asuransi_tipe')
            ->where('asuransi_id', $id)
            ->get();
        foreach ($tipe_asuransi as $key => $value) {
            try{
                DB::table('data_asuransi_tipe')
                    ->where('id', $value->id)
                    ->delete();
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Gagal menghapus asuransi');
            }
        }
        try {
            DB::table('data_asuransi')
                ->where('id', $id)
                ->delete();
            return redirect('asuransi')->with('success', 'Berhasil menghapus asuransi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menghapus asuransi');
        }
    }

    public function delete_tipe(Request $request)
    {
        $id = $request->id_asuransi_tipe;
        try {
            DB::table('data_asuransi_tipe')
                ->where('id', $id)
                ->delete();
            return redirect()->back()->with('success', 'Berhasil menghapus tipe asuransi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menghapus tipe asuransi');
        }
    }

    public function get_pasien_asuransi()
    {
        $pasien_asuransi = DB::table('data_asuransi_pasien')
            ->join('data_pasien', 'data_pasien.id', '=', 'data_asuransi_pasien.pasien_id')
            ->join('data_asuransi', 'data_asuransi.id', '=', 'data_asuransi_pasien.asuransi_id')
            ->select(
                'data_asuransi_pasien.*',
                'data_pasien.nama_pasien as nama_pasien',
                'data_asuransi.nama_asuransi as nama_asuransi',
            )
            ->get();
        $data = [
            'title' => 'Pasien Asuransi',
            'menu_slug' => 'pasien-asuransi',
            'sub_menu_slug' => 'pasien-asuransi',
            'data' => $pasien_asuransi,
        ];
        return view('asuransi.data_pasien_asuransi', $data);

    }

    public function tambah_pasien_asuransi(Request $request)
    {
        $request->validate([
            'id_pasien' => 'required',
            'id_asuransi' => 'required',
            'no_peserta' => 'required',
        ], [
            'id_pasien.required' => 'Pasien tidak boleh kosong',
            'id_asuransi.required' => 'Asuransi tidak boleh kosong',
            'no_peserta.required' => 'No peserta tidak boleh kosong',
        ]);
        $id_pasien = $request->id_pasien;
        $id_asuransi = $request->id_asuransi;
        $no_peserta = $request->no_peserta;

        $data = [
            'pasien_id' => $id_pasien,
            'asuransi_id' => $id_asuransi,
            'tipe_asuransi_id' => $request->tipe_asuransi ?? null, // 'aktif' or 'tidak_aktif
            'nomor_peserta' => $no_peserta,
            'status' => 'aktif',
        ];
        try {
            $pasien_asuransi_model = new DataAsuransiPasienModel;
            $pasien_asuransi = $pasien_asuransi_model->tambah_asuransi_pasien($data);
            // dd($pasien_asuransi);
            return redirect('pasien-asuransi/detail/'. $pasien_asuransi)->with('success', 'Berhasil menambahkan pasien asuransi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menambahkan pasien asuransi');
        }

    }

    public function detail_pasien_asuransi($id)
    {
        $pasien_asuransi = DB::table('data_asuransi_pasien')
            ->join('data_pasien', 'data_pasien.id', '=', 'data_asuransi_pasien.pasien_id')
            ->join('data_asuransi', 'data_asuransi.id', '=', 'data_asuransi_pasien.asuransi_id')
            ->select(
                'data_asuransi_pasien.*',
                'data_pasien.slug_number as slug_pasien',
                'data_pasien.nama_pasien as nama_pasien',
                'data_asuransi.nama_asuransi as nama_asuransi',
            )
            ->where('data_asuransi_pasien.id', $id)
            ->first();
        $tipe_asuransi = null;
        if ($pasien_asuransi->tipe_asuransi_id != null) {
            $tipe_asuransi = DB::table('data_asuransi_tipe')
                ->where('id', $pasien_asuransi->tipe_asuransi_id)
                ->first();
        }

        $tanggungan = DB::table('data_asuransi_pasien_tanggungan')
            ->where('asuransi_pasien_id', $id)
            ->get();

        $data = [
            'title' => 'Pasien Asuransi',
            'menu_slug' => 'pasien-asuransi',
            'sub_menu_slug' => 'pasien-asuransi',
            'data' => $pasien_asuransi,
            'data_line' => $tanggungan,
            'tipe_asuransi' => $tipe_asuransi,
        ];
        return view('asuransi.detail_pasien_asuransi', $data);
    }

    public function edit_pasien_asuransi(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'pasien_id' => 'required',
            'asuransi_id' => 'required',
            'nomor_peserta' => 'required',
        ], [
            'pasien_id.required' => 'Pasien tidak boleh kosong',
            'asuransi_id.required' => 'Asuransi tidak boleh kosong',
            'nomor_peserta.required' => 'No peserta tidak boleh kosong',
        ]);
        $id = $request->id;
        $id_pasien = $request->pasien_id;
        $id_asuransi = $request->asuransi_id;
        $no_peserta = $request->nomor_peserta;

        $data = [
            'pasien_id' => $id_pasien,
            'asuransi_id' => $id_asuransi,
            'tipe_asuransi_id' => $request->tipe_asuransi_id,
            'nomor_peserta' => $no_peserta,
        ];
        try {
            DB::table('data_asuransi_pasien')
                ->where('id', $id)
                ->update($data);
            return redirect()->back()->with('success', 'Berhasil mengubah pasien asuransi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal mengubah pasien asuransi');
        }
    }

    public function tambah_tangguungan_pasien_asuransi(Request $request){
        $request->validate([
            'jenis_tanggungan' => 'required',
            'nama_tanggungan' => 'required',
            'limit' => 'required_if:is_limit,==,1',
        ], [
            'jenis_tanggungan.required' => 'Jenis tanggungan tidak boleh kosong',
            'nama_tanggungan.required' => 'Nama tanggungan tidak boleh kosong',
            'limit.required_if' => 'Limit tidak boleh kosong',
        ]);
        $tanggungan_ready = DB::table('data_asuransi_pasien_tanggungan')
            ->where('asuransi_pasien_id', $request->pasien_asuransi_id)
            ->get();
        // check if isset jenis_taggungan is all
        foreach ($tanggungan_ready as $key => $value) {
            if ($value->jenis_tanggungan == 'all') {
                return redirect()->back()->with('error', 'Tanggungan untuk semua layanan sudah ada');
            }
            if ($value->jenis_tanggungan == $request->jenis_tanggungan) {
                return redirect()->back()->with('error', 'Tanggungan ' . $request->jenis_tanggungan . ' sudah ada');
            }
        }
        // dd($request->all());
        $id_asuransi_pasien = $request->pasien_asuransi_id;
        $jenis_tanggungan = $request->jenis_tanggungan;
        $nama_tanggungan = $request->nama_tanggungan;
        $is_limit = $request->is_limit ?? 0;
        $limit = floatval(str_replace(',', '', $request->limit)) ?? 0;
        $sisa_limit = $limit;
        $tanggal_terakhir_penggunaan = null;

        $data = [
            'asuransi_pasien_id' => $id_asuransi_pasien,
            'jenis_tanggungan' => $jenis_tanggungan,
            'nama_tanggungan' => $nama_tanggungan,
            'is_limit' => $is_limit,
            'limit' => $limit,
            'sisa_limit' => $sisa_limit,
            'tanggal_terakhir_penggunaan' => $tanggal_terakhir_penggunaan,
        ];


        try {
            DB::table('data_asuransi_pasien_tanggungan')
                ->insert($data);
            return redirect()->back()->with('success', 'Berhasil menambahkan tanggungan pasien asuransi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menambahkan tanggungan pasien asuransi');
        }
    }

    public function delete_tanggungan_pasien_asuransi(Request $request)
    {
        $id = $request->id_pasien_asuransi_tanggungan;
        try {
            DB::table('data_asuransi_pasien_tanggungan')
                ->where('id', $id)
                ->delete();
            return redirect()->back()->with('success', 'Berhasil menghapus tanggungan pasien asuransi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menghapus tanggungan pasien asuransi');
        }
    }

    public function delete_pasien_asuransi(Request $request)
    {
        $id = $request->id_pasien_asuransi;
        $data = DB::table('data_asuransi_pasien')
            ->where('id', $id)
            ->first();
        if ($data->status == 'aktif') {
            return redirect()->back()->with('error', 'Gagal menghapus pasien asuransi, nonaktifkan terlebih dahulu');
        }
        $tanggungan = DB::table('data_asuransi_pasien_tanggungan')
            ->where('asuransi_pasien_id', $id)
            ->get();
        if ($tanggungan->count() > 0) {
            foreach ($tanggungan as $key => $value) {
                try{
                    DB::table('data_asuransi_pasien_tanggungan')
                        ->where('id', $value->id)
                        ->delete();
                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', 'Gagal menghapus pasien asuransi, data tanggungan tidak terhapus');
                }
            }
        }
        try {
            DB::table('data_asuransi_pasien')
                ->where('id', $id)
                ->delete();
            return redirect('pasien-asuransi')->with('success', 'Berhasil menghapus pasien asuransi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menghapus pasien asuransi');
        }
    }

    public function get_data_dropdown(Request $request){
        if ($request->has('id_asuransi')) {
            $cari = $request->id_asuransi;
            $data = DB::table('data_asuransi_tipe')
                ->where('asuransi_id', $cari)
                ->get();
            return response()->json($data);
        }
        $asuransi = DB::table('data_asuransi')
            ->where('status', 'aktif')
            ->get();
        $pasien = DB::table('data_pasien')
            ->get();
        $data = [
            'asuransi' => $asuransi,
            'pasien' => $pasien,
        ];
        return response()->json($data);
    }

    public function get_list_asuransi_pasien(Request $request){
        $id_pasien = $request->id_pasien;
        $data = DB::table('data_asuransi_pasien')
            ->join('data_asuransi', 'data_asuransi.id', '=', 'data_asuransi_pasien.asuransi_id')
            ->select(
                'data_asuransi_pasien.*',
                'data_asuransi.nama_asuransi as nama_asuransi',
            )
            ->where('data_asuransi_pasien.pasien_id', $id_pasien)
            ->where('data_asuransi_pasien.status', 'aktif')
            ->get();
        return response()->json($data);
    }

    public function aktifkan_pasien_asuransi(Request $request)
    {
        $id = $request->id;
        try {
            DB::table('data_asuransi_pasien')
                ->where('id', $id)
                ->update([
                    'status' => 'aktif'
                ]);
            return redirect()->back()->with('success', 'Berhasil mengubah status asuransi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal mengubah status asuransi');
        }
    }
    public function nonaktifkan_pasien_asuransi(Request $request)
    {
        $id = $request->id;
        try {
            DB::table('data_asuransi_pasien')
                ->where('id', $id)
                ->update([
                    'status' => 'tidak_aktif'
                ]);
            return redirect()->back()->with('success', 'Berhasil mengubah status asuransi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal mengubah status asuransi');
        }
    }
}
