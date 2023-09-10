<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $data_pendaftar_perawatan_today = DB::table('data_pendaftar_perawatan')
            ->whereDate('tgl_periksa', date('Y-m-d'))
            ->count();
        $data_pendaftar_perawatan_this_month = DB::table('data_pendaftar_perawatan')
            ->whereMonth('tgl_periksa', date('m'))
            ->count();
        $data_pendaftar_perawatan_this_year = DB::table('data_pendaftar_perawatan')
            ->whereYear('tgl_periksa', date('Y'))
            ->count();
        $data_pendaftar_perawatan = DB::table('data_pendaftar_perawatan')
            ->count();
        $data_pendaftar_perawatan_baru_today = DB::table('data_pendaftar_perawatan')
            ->whereDate('tgl_periksa', date('Y-m-d'))
            ->where('status', 'baru')
            ->count();
        $data_pendaftar_perawatan_on_antre_today = DB::table('data_pendaftar_perawatan')
            ->whereDate('tgl_periksa', date('Y-m-d'))
            ->where('status', 'antre')
            ->count();
        $data_pendaftar_perawatan_selesai_today = DB::table('data_pendaftar_perawatan')
            ->whereDate('tgl_periksa', date('Y-m-d'))
            ->where('status', 'selesai')
            ->count();
        $data = [
            'title' => 'Dashboard',
            'menu_slug' => 'dashboard',
            'sub_menu_slug' => 'dashboard',
            'perawatan_today' => $data_pendaftar_perawatan_today,
            'perawatan_this_month' => $data_pendaftar_perawatan_this_month,
            'perawatan_this_year' => $data_pendaftar_perawatan_this_year,
            'perawatan' => $data_pendaftar_perawatan,
            'perawatan_baru_today' => $data_pendaftar_perawatan_baru_today,
            'perawatan_antre_today' => $data_pendaftar_perawatan_on_antre_today,
            'perawatan_selesai_today' => $data_pendaftar_perawatan_selesai_today,
        ];
        return view('index', $data);
    }
}
