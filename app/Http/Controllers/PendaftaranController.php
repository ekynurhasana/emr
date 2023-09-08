<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    public function index()
    {
        $pendaftaran_pasien = DB::table('data_pendaftar_perawatan')->join('data_pasien', 'data_pendaftar_perawatan.pasien_id', '=', 'data_pasien.id')->join('data_poli', 'data_pendaftar_perawatan.poli_id', '=', 'data_poli.id')->join('data_dokter_poli', 'data_pendaftar_perawatan.dokter_poli_id', '=', 'data_dokter_poli.id')->join('users', 'data_pendaftar_perawatan.dokter_id', '=', 'users.id')->select('data_pendaftar_perawatan.*', 'data_pasien.nama_pasien as nama_pasien', 'data_poli.nama_poli as nama_poli', 'users.name as nama_dokter')->get();
        $pasiens = DB::table('data_pasien')->get();
        $polis = DB::table('data_poli')->get();
        $data = [
            'title' => 'Pendaftaran Pasien',
            'menu_slug' => 'pendaftaran-pelayanan',
            'sub_menu_slug' => 'pendaftaran-pelayanan',
            'data' => $pendaftaran_pasien,
            'pasien' => $pasiens,
            'poli' => $polis,
        ];
        return view('pendaftaran', $data);
    }
}
