<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DokterController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data Poliklinik',
            'menu_slug' => 'poliklinik',
            'sub_menu_slug' => 'data-poliklinik',
            'data' => DB::table('data_poli')->get(),
        ];
        return view('poliklinik.data_poliklinik', $data);
    }

    public function detail($id)
    {
        $poli = DB::table('data_poli')->where('id', $id)->first();
        $data = [
            'title' => 'Detail Poliklinik - ' . $poli->nama_poli,
            'menu_slug' => 'poliklinik',
            'sub_menu_slug' => 'data-poliklinik',
            'data' => $poli,
            'data_line' => DB::table('data_dokter_poli')->where('poli_id', $id)->join('users', 'users.id', '=', 'data_dokter_poli.dokter_id')->select('data_dokter_poli.*', 'users.name')->get(),
        ];
        return view('poliklinik.detail_poliklinik', $data);
    }

    public function tambah(Request $request)
    {
        if ($request->isMethod('post')) {
            // return $request->all();
            $request->validate([
                'nama_poli' => 'required|unique:data_poli',
                'biaya_poli' => 'required|min:0',
            ], [
                'nama_poli.required' => 'Nama Poliklinik tidak boleh kosong',
                'nama_poli.unique' => 'Nama Poliklinik sudah ada',
                'biaya_poli.required' => 'Biaya Poliklinik tidak boleh kosong',
                'biaya_poli.min' => 'Biaya Poliklinik tidak boleh kurang dari 0',
            ]);
            $nama_poli = $request->nama_poli;
            $biaya_poli = $request->biaya_poli;
            $biaya_poli = str_replace('.', '', $biaya_poli);
            $biaya_poli = str_replace(',', '', $biaya_poli);
            $data = [
                'nama_poli' => $nama_poli,
                'biaya_poli' => $biaya_poli,
                'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
            ];
            try {
                DB::table('data_poli')->insert($data);
                // get id pasien last insert
                $id_poli = DB::getPdo()->lastInsertId();
                return redirect('data-poliklinik/detail/' . $id_poli)->with('success', 'Data Poliklinik ' . $nama_poli . ' berhasil ditambahkan');
            } catch (\Throwable $th) {
                return redirect('data-poliklinik')->with('error', 'Data gagal ditambahkan ' . $th);
            }
        }
    }

    public function edit(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'nama_poli' => 'required',
                'biaya_poli' => 'required|min:0',
            ], [
                'nama_poli.required' => 'Nama Poliklinik tidak boleh kosong',
                'biaya_poli.required' => 'Biaya Poliklinik tidak boleh kosong',
                'biaya_poli.min' => 'Biaya Poliklinik tidak boleh kurang dari 0',
            ]);
            $id = $request->id;
            $nama_poli = $request->nama_poli;
            $biaya_poli = $request->biaya_poli;
            $biaya_poli = str_replace('.', '', $biaya_poli);
            $biaya_poli = str_replace(',', '', $biaya_poli);
            $data = [
                'nama_poli' => $nama_poli,
                'biaya_poli' => $biaya_poli,
                'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
            ];
            try {
                DB::table('data_poli')->where('id', $id)->update($data);
                return redirect('data-poliklinik/detail/' . $id)->with('success', 'Data Poliklinik ' . $nama_poli . ' berhasil diubah');
            } catch (\Throwable $th) {
                return redirect('data-poliklinik/detail/' . $id)->with('error', 'Data gagal diubah ' . $th);
            }
        }
    }

    public function delete(Request $request)
    {
        $id = $request->id_poliklinik;
        $nama_poli = $request->nama_poli;
        $delete = DB::table('data_poli')->where('id', $id)->delete();
        if ($delete) {
            return redirect('data-poliklinik')->with('success', 'Data Poliklinik ' . $nama_poli . ' berhasil dihapus');
        } else {
            return redirect('data-poliklinik/detail/' . $id)->with('error', 'Data gagal dihapus');
        }
    }

    public function get_dokter_poliklinik(Request $request)
    {
        $tanggal = date('Y-m-d', strtotime($request->tanggal_periksa));
        $hari = date('w', strtotime($tanggal));
        $get_hari = "";
        switch ($hari) {
            case 0:
                $get_hari = "minggu";
                break;
            case 1:
                $get_hari = "senin";
                break;
            case 2:
                $get_hari = "selasa";
                break;
            case 3:
                $get_hari = "rabu";
                break;
            case 4:
                $get_hari = "kamis";
                break;
            case 5:
                $get_hari = "jumat";
                break;
            case 6:
                $get_hari = "sabtu";
                break;
        }
        $id_poliklinik = $request->id_poliklinik;
        $data = DB::table('data_dokter_poli')
            ->where('poli_id', $id_poliklinik)
            ->join('users', 'data_dokter_poli.dokter_id', '=', 'users.id')
            ->select('data_dokter_poli.*', 'users.name', 'users.id as id_dokter')
            ->get();
        if($tanggal != date('Y-m-d')){
            $jadwal_praktek = [];
            foreach ($data as $key => $value) {
                $data_jadwal_praktek = DB::table('data_jadwal_praktek')
                    ->where('dokter_poli_id', $value->id)
                    ->where('hari', $get_hari)
                    ->join('data_dokter_poli', 'data_dokter_poli.id', '=', 'data_jadwal_praktek.dokter_poli_id')
                    ->join('users', 'users.id', '=', 'data_dokter_poli.dokter_id')
                    ->select('data_jadwal_praktek.*', 'data_dokter_poli.dokter_id')
                    ->get();
                foreach ($data_jadwal_praktek as $key => $val) {
                    $jadwal_praktek[] = [
                        'id' => $val->id,
                        'dokter_poli_id' => $val->dokter_poli_id,
                        'dokter_id' => $val->dokter_id,
                        'nama_dokter' => $value->name,
                        'hari' => $val->hari,
                        'jam_mulai' => $val->jam_mulai,
                        'jam_selesai' => $val->jam_selesai,
                    ];
                }
            }
            $data = $jadwal_praktek;
        } else {
            $data = DB::table('data_dokter_poli')
                ->where('poli_id', $id_poliklinik)
                ->where('status', 'buka')
                ->join('users', 'data_dokter_poli.dokter_id', '=', 'users.id')
                ->select('data_dokter_poli.id as dokter_poli_id', 'data_dokter_poli.dokter_id', 'users.name as nama_dokter')
                ->get();
        }
        return response()->json($data);
    }

    // dokter poli
    public function dokter_poli()
    {

        $data = [
            'title' => 'Data Dokter Poliklinik',
            'menu_slug' => 'poliklinik',
            'sub_menu_slug' => 'data-dokter-poli',
            'data_poli' => DB::table('data_poli')->get(),
            'data_dokter' => DB::table('users')->where('role', 'dokter')->get(),
            'data' => DB::table('data_dokter_poli')->join('users', 'users.id', '=', 'data_dokter_poli.dokter_id')->join('data_poli', 'data_poli.id', '=', 'data_dokter_poli.poli_id')->select('data_dokter_poli.*', 'users.name as nama_dokter', 'data_poli.nama_poli')->get(),
        ];
        return view('poliklinik.data_dokter_poli', $data);
    }

    public function dokter_poli_detail($id)
    {
        $dokter_poli = DB::table('data_dokter_poli')->join('users', 'users.id', '=', 'data_dokter_poli.dokter_id')->join('data_poli', 'data_poli.id', '=', 'data_dokter_poli.poli_id')->select('data_dokter_poli.*', 'users.name as nama_dokter', 'data_poli.nama_poli')->where('data_dokter_poli.id', $id)->first();
        $data_jadwal_praktek = DB::table('data_jadwal_praktek')->where('dokter_poli_id', $id)->get();
        $is_buka = 0;
        foreach ($data_jadwal_praktek as $key => $value) {
            $jam_mulai = date('H:i', strtotime($value->jam_mulai));
            $jam_selesai = date('H:i', strtotime($value->jam_selesai));
            $hari_jadwal = $value->hari;
            $get_hari = date('w');
            $hari = "";
            switch ($get_hari) {
                case 0:
                    $hari = "minggu";
                    break;
                case 1:
                    $hari = "senin";
                    break;
                case 2:
                    $hari = "selasa";
                    break;
                case 3:
                    $hari = "rabu";
                    break;
                case 4:
                    $hari = "kamis";
                    break;
                case 5:
                    $hari = "jumat";
                    break;
                case 6:
                    $hari = "sabtu";
                    break;
            }
            $jam_sekarang = date('H:i');
            if ($jam_sekarang >= $jam_mulai && $jam_sekarang <= $jam_selesai && $hari == $hari_jadwal) {
                $is_buka = 1;
            }
        }
        $data = [
            'title' => 'Detail ' . $dokter_poli->nama_dokter . ' - ' . $dokter_poli->nama_poli,
            'menu_slug' => 'poliklinik',
            'sub_menu_slug' => 'data-dokter-poli',
            'data' => $dokter_poli,
            'data_line' => $data_jadwal_praktek,
            'data_poli' => DB::table('data_poli')->get(),
            'data_dokter' => DB::table('users')->where('role', 'dokter')->get(),
            'is_buka' => $is_buka,
        ];
        return view('poliklinik.detail_dokter_poliklinik', $data);
    }

    public function dokter_poli_tambah(Request $request)
    {
        if ($request->isMethod('post')) {
            // return $request->all();
            $request->validate([
                'poli_id' => 'required',
                'dokter_id' => 'required | unique:data_dokter_poli,dokter_id',
            ], [
                'poli_id.required' => 'Poliklinik harus dipilih',
                'dokter_id.required' => 'Dokter harus dipilih',
                'dokter_id.unique' => 'Dokter sudah terdaftar, silahkan pilih dokter lain',
            ]);
            $poli_id = $request->poli_id;
            $dokter_id = $request->dokter_id;
            $data = [
                'poli_id' => $poli_id,
                'dokter_id' => $dokter_id,
            ];
            try {
                $add = DB::table('data_dokter_poli')->insert($data);
                $id_dokter_poli = DB::getPdo()->lastInsertId();
                return redirect('data-dokter-poli/detail/' . $id_dokter_poli)->with('success', 'Data Dokter Poliklinik berhasil ditambahkan');
            } catch (\Throwable $th) {
                return redirect('data-dokter-poli')->with('error', 'Data gagal ditambahkan ' . $th);
            }
        }
    }

    public function dokter_poli_edit(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'poli_id' => 'required',
                'dokter_id' => 'required | unique:data_dokter_poli,dokter_id,' . $request->id,
                'biaya_tambahan' => 'required|min:0',
            ], [
                'poli_id.required' => 'Poliklinik harus dipilih',
                'dokter_id.required' => 'Dokter harus dipilih',
                'dokter_id.unique' => 'Dokter sudah terdaftar, silahkan pilih dokter lain',
                'biaya_tambahan.required' => 'Biaya tambahan harus diisi',
                'biaya_tambahan.min' => 'Biaya tambahan minimal 0',
            ]);
            $id = $request->id;
            $poli_id = $request->poli_id;
            $dokter_id = $request->dokter_id;
            $biaya_tambahan = $request->biaya_tambahan;
            $biaya_tambahan = str_replace(".", "", $biaya_tambahan);
            $biaya_tambahan = str_replace(",", "", $biaya_tambahan);
            $data = [
                'poli_id' => $poli_id,
                'dokter_id' => $dokter_id,
                'biaya_tambahan' => $biaya_tambahan,
            ];
            // dd($data);
            try {
                DB::table('data_dokter_poli')->where('id', $id)->update($data);
                return redirect('data-dokter-poli/detail/' . $id)->with('success', 'Data Dokter Poliklinik berhasil diubah');
            } catch (\Throwable $th) {
                return redirect('data-dokter-poli/detail/' . $id)->with('error', 'Data gagal diubah ' . $th);
            }
        }
    }

    public function dokter_poli_jadwal_tambah(Request $request)
    {
        // dd($request->all());
        if ($request->isMethod('post')) {
            $request->validate([
                'hari' => 'required',
                'jam_mulai' => 'required|before:jam_selesai',
                'jam_selesai' => 'required|after:jam_mulai',
            ], [
                'hari.required' => 'Hari harus dipilih',
                'jam_mulai.required' => 'Jadwal mulai harus diisi',
                'jam_selesai.required' => 'Jadwal selesai harus diisi',
                'jam_mulai.before' => 'Jadwal mulai harus lebih kecil dari jam selesai',
                'jam_selesai.after' => 'Jadwal selesai harus lebih besar dari jam mulai',
            ]);
            $hari = $request->hari;
            $jam_mulai = $request->jam_mulai;
            $jam_selesai = $request->jam_selesai;
            $dokter_poli_id = $request->dokter_poli_id;
            $data = [
                'hari' => $hari,
                'jam_mulai' => $jam_mulai,
                'jam_selesai' => $jam_selesai,
                'dokter_poli_id' => $dokter_poli_id,
            ];
            try {
                DB::table('data_jadwal_praktek')->insert($data);
                return redirect('data-dokter-poli/detail/' . $dokter_poli_id)->with('success', 'Data Jadwal Praktek berhasil ditambahkan');
            } catch (\Throwable $th) {
                return redirect('data-dokter-poli/detail/' . $dokter_poli_id)->with('error', 'Data gagal ditambahkan ' . $th);
            }
        }
    }

    public function update_status(Request $request)
    {
        $id = $request->id_dokter_poli;
        $status = $request->status;
        $data = [
            'status' => $status,
        ];
        $message = "";
        if ($status == 'buka') {
            $message = "Data Dokter Poliklinik telah dibuka";
        } else {
            $message = "Data Dokter Poliklinik telah ditutup";
        }
        try {
            DB::table('data_dokter_poli')->where('id', $id)->update($data);
            return redirect('data-dokter-poli/detail/' . $id)->with('success', $message);
        } catch (\Throwable $th) {
            return redirect('data-dokter-poli/detail/' . $id)->with('error', 'Data gagal diubah ' . $th);
        }
    }

    public function dokter_poli_hapus(Request $request)
    {
        $id = $request->id_dokter_poli;
        $type = $request->type;
        if ($type == "dokter_poli"){
            $jadwal = DB::table('data_jadwal_praktek')->where('dokter_poli_id', $id)->get();
            $antrean = DB::table('conf_antrean_rawat_jalan')->where('dokter_poli_id', $id)->get();
            try {
                DB::table('conf_antrean_rawat_jalan')->where('dokter_poli_id', $id)->delete();
            } catch (\Throwable $th) {
                return redirect('data-dokter-poli')->with('error', 'Data gagal dihapus');
            }
            if (isset($jadwal)) {
                foreach ($jadwal as $key => $value) {
                    $delete_jadwal = DB::table('data_jadwal_praktek')->where('id', $value->id)->delete();
                    if (!$delete_jadwal) {
                        return redirect('data-dokter-poli')->with('error', 'Data jadwal gagal dihapus');
                    }
                }
            }
            try {
                DB::table('data_dokter_poli')->where('id', $id)->delete();
                return redirect('data-dokter-poli')->with('success', 'Data Dokter Poliklinik berhasil dihapus');
            } catch (\Throwable $th) {
                return redirect('data-dokter-poli')->with('error', 'Data gagal dihapus');
            }
        }
        if ($type == "jadwal_praktek"){
            // dd($request->all());
            $id = $request->id_jadwal_praktek;
            $dokter_poli_id = $request->dokter_poli_id;
            try {
                DB::table('data_jadwal_praktek')->where('id', $id)->delete();
                return redirect('data-dokter-poli/detail/' . $dokter_poli_id)->with('success', 'Data Jadwal Praktek berhasil dihapus');
            } catch (\Throwable $th) {
                return redirect('data-dokter-poli/detail/' . $dokter_poli_id)->with('error', 'Data gagal dihapus');
            }
        }
    }
}
