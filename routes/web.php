<?php

use App\Http\Controllers\AsuransiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\FarmasiController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PerawatanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KasirController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return redirect('login');
    });
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'index']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    // Route::get('/', function () {
    //     return redirect('emr');
    // });

    // Route::get('/home', function () {
    //     return redirect('emr');
    // });

    Route::get('', [HomeController::class, 'index']);

    // users
    Route::get('/users',[UserController::class, 'index'])->middleware('role:super-admin');
    Route::get('/users/detail/{id}', [UserController::class, 'detail']);
    Route::post('/users/tambah', [UserController::class, 'tambah'])->middleware('role:super-admin');
    Route::post('/users/edit', [UserController::class, 'edit']);
    Route::post('/users/ubah-password', [UserController::class, 'ubah_password']);
    Route::post('/users/activate', [UserController::class, 'activate'])->middleware('role:super-admin');
    Route::post('/users/deactivate', [UserController::class, 'deactivate'])->middleware('role:super-admin');
    Route::delete('/users/delete', [UserController::class, 'delete'])->middleware('role:super-admin');

    // rawat jalan
    Route::get('/rawat-jalan/icd', [PerawatanController::class, 'get_icd']);
    Route::get('/rawat-jalan/icd/{code}', [PerawatanController::class, 'get_icd_detail']);
    Route::get('/rawat-jalan/pendaftaran', [PerawatanController::class, 'index'])->middleware('role:super-admin|admin|dokter|perawat');
    Route::post('/rawat-jalan/pendaftaran/tambah', [PerawatanController::class, 'tambah'])->middleware('role:super-admin|admin|dokter|perawat');
    Route::post('/rawat-jalan/antri', [PerawatanController::class, 'antri'])->middleware('role:super-admin|admin|dokter|perawat');
    Route::post('/rawat-jalan/batal', [PerawatanController::class, 'batal'])->middleware('role:super-admin|admin');
    Route::put('/rawat-jalan/screening', [PerawatanController::class, 'screening'])->middleware('role:super-admin|dokter|perawat');
    Route::put('/rawat-jalan/periksa', [PerawatanController::class, 'periksa'])->middleware('role:super-admin|dokter');
    Route::post('/rawat-jalan/tambah-biaya', [PerawatanController::class, 'tambah_biaya'])->middleware('role:super-admin|dokter|perawat|admin');
    Route::delete('/rawat-jalan/pendaftaran/delete', [PerawatanController::class, 'delete'])->middleware('role:super-admin|admin');

    Route::get('/rawat-jalan/detail/{id}', [PerawatanController::class, 'detail']);
    Route::get('/antre-poli', [PerawatanController::class, 'get_antre_poli'])->middleware('role:super-admin|admin|dokter|perawat');
    Route::get('/riwayat-perawatan', [PerawatanController::class, 'get_riwayat_perawatan'])->middleware('role:super-admin|admin|dokter|perawat');

    Route::get('/get_dokter_poliklinik', [DokterController::class, 'get_dokter_poliklinik']);
    // pasien
    Route::get('/data-pasien', [PasienController::class, 'index'])->middleware('role:super-admin|admin|dokter|perawat');
    Route::get('/data-pasien/detail/{id}', [PasienController::class, 'detail']);
    Route::post('/data-pasien/edit', [PasienController::class, 'edit'])->middleware('role:super-admin|admin|dokter|perawat');
    Route::post('/data-pasien/tambah-pasien', [PasienController::class, 'tambah'])->middleware('role:super-admin|admin');
    Route::delete('/data-pasien/delete', [PasienController::class, 'delete'])->middleware('role:super-admin|admin');

    // rm pasien
    Route::get('/rm-pasien', [PasienController::class, 'emr_all'])->middleware('role:super-admin|admin|dokter|perawat');
    Route::get('/rm-pasien/detail/{id}', [PasienController::class, 'emr_detail']);

    // poliklinik
    Route::get('/data-poliklinik', [DokterController::class, 'index'])->middleware('role:super-admin|admin');
    Route::get('/data-poliklinik/detail/{id}', [DokterController::class, 'detail'])->middleware('role:super-admin|admin');
    Route::post('/data-poliklinik/edit', [DokterController::class, 'edit'])->middleware('role:super-admin|admin');
    Route::post('/data-poliklinik/tambah', [DokterController::class, 'tambah'])->middleware('role:super-admin|admin');
    Route::delete('/data-poliklinik/delete', [DokterController::class, 'delete'])->middleware('role:super-admin|admin');

    Route::get('/data-dokter-poli', [DokterController::class, 'dokter_poli'])->middleware('role:super-admin|admin|dokter');
    Route::get('/data-dokter-poli/detail/{id}', [DokterController::class, 'dokter_poli_detail'])->middleware('role:super-admin|admin|dokter');
    Route::post('/data-dokter-poli/tambah', [DokterController::class, 'dokter_poli_tambah'])->middleware('role:super-admin|admin');
    Route::post('/data-dokter-poli/update', [DokterController::class, 'dokter_poli_edit'])->middleware('role:super-admin|admin|dokter');
    Route::post('/data-dokter-poli/tambah-jadwal', [DokterController::class, 'dokter_poli_jadwal_tambah'])->middleware('role:super-admin|admin|dokter');
    Route::put('/data-dokter-poli/update-status', [DokterController::class, 'update_status'])->middleware('role:super-admin|admin|dokter');
    Route::delete('/data-dokter-poli/delete', [DokterController::class, 'dokter_poli_hapus'])->middleware('role:super-admin|admin');

    // farmasi
    Route::get('/data-obat', [FarmasiController::class, 'index'])->middleware('role:super-admin|admin|apoteker');
    Route::get('/data-obat/get-obat', [FarmasiController::class, 'get_obat']);
    Route::get('/data-obat/detail/{id}', [FarmasiController::class, 'detail'])->middleware('role:super-admin|admin|apoteker');
    Route::post('/data-obat/edit', [FarmasiController::class, 'edit'])->middleware('role:super-admin|admin|apoteker');
    Route::post('/data-obat/tambah', [FarmasiController::class, 'tambah'])->middleware('role:super-admin|admin|apoteker');
    Route::delete('/data-obat/delete', [FarmasiController::class, 'hapus'])->middleware('role:super-admin|admin|apoteker');

    Route::get('/resep-obat', [FarmasiController::class, 'resep_obat'])->middleware('role:super-admin|apoteker');
    Route::get('/resep-obat/detail/{id}', [FarmasiController::class, 'resep_obat_detail'])->middleware('role:super-admin|apoteker');
    Route::post('/resep-obat/tambah-obat', [FarmasiController::class, 'resep_obat_tambah_obat'])->middleware('role:super-admin|apoteker');
    Route::post('/resep-obat/change-status', [FarmasiController::class, 'resep_obat_change_status'])->middleware('role:super-admin|apoteker');
    Route::delete('/resep-obat/delete', [FarmasiController::class, 'resep_obat_hapus'])->middleware('role:super-admin|apoteker');
    Route::delete('/resep-obat/delete-obat', [FarmasiController::class, 'resep_obat_hapus_obat'])->middleware('role:super-admin|apoteker');

    // kasir
    Route::get('/pembayaran-pasien', [KasirController::class, 'index'])->middleware('role:super-admin|kasir');
    Route::get('/tagihan/detail/{id}', [KasirController::class, 'detail'])->middleware('role:super-admin|kasir');
    Route::post('/tagihan/tambah-line', [KasirController::class, 'tambah_line'])->middleware('role:super-admin|kasir');
    Route::post('/tagihan/bayar', [KasirController::class, 'change_state'])->middleware('role:super-admin|kasir');
    Route::get('/tagihan-pasien/draft', [KasirController::class, 'get_data_draft'])->middleware('role:super-admin|kasir');
    Route::delete('/tagihan/delete', [KasirController::class, 'delete'])->middleware('role:super-admin|kasir');
    Route::delete('/tagihan/line/delete', [KasirController::class, 'delete_line'])->middleware('role:super-admin|kasir');

    // asuransi
    Route::get('/asuransi', [AsuransiController::class, 'index'])->middleware('role:super-admin|admin');
    Route::get('/asuransi/detail/{id}', [AsuransiController::class, 'detail'])->middleware('role:super-admin|admin');
    Route::post('/asuransi/tambah', [AsuransiController::class, 'tambah'])->middleware('role:super-admin|admin');
    Route::post('/asuransi/tambah-tipe', [AsuransiController::class, 'tambah_tipe'])->middleware('role:super-admin|admin');
    Route::post('/asuransi/edit', [AsuransiController::class, 'edit'])->middleware('role:super-admin|admin');
    Route::post('/asuransi/aktifkan', [AsuransiController::class, 'aktifkan'])->middleware('role:super-admin|admin');
    Route::post('/asuransi/nonaktifkan', [AsuransiController::class, 'nonaktifkan'])->middleware('role:super-admin|admin');
    Route::delete('/asuransi/delete', [AsuransiController::class, 'delete'])->middleware('role:super-admin|admin');
    Route::delete('/asuransi/delete-tipe', [AsuransiController::class, 'delete_tipe'])->middleware('role:super-admin|admin');

    Route::get('/pasien-asuransi', [AsuransiController::class, 'get_pasien_asuransi'])->middleware('role:super-admin|admin');
    Route::get('/pasien-asuransi/get-data-dropdown', [AsuransiController::class, 'get_data_dropdown']);
    Route::get('/pasien-asuransi/list-asuransi-pasien', [AsuransiController::class, 'get_list_asuransi_pasien']);
    Route::get('/pasien-asuransi/detail/{id}', [AsuransiController::class, 'detail_pasien_asuransi'])->middleware('role:super-admin|admin');
    Route::post('/pasien-asuransi/tambah', [AsuransiController::class, 'tambah_pasien_asuransi'])->middleware('role:super-admin|admin');
    Route::post('/pasien-asuransi/tambah-tanggungan', [AsuransiController::class, 'tambah_tangguungan_pasien_asuransi'])->middleware('role:super-admin|admin');
    Route::post('/pasien-asuransi/edit', [AsuransiController::class, 'edit_pasien_asuransi'])->middleware('role:super-admin|admin');
    Route::delete('/pasien-asuransi/delete', [AsuransiController::class, 'delete_pasien_asuransi'])->middleware('role:super-admin|admin');
    Route::delete('/pasien-asuransi/delete-tanggungan', [AsuransiController::class, 'delete_tanggungan_pasien_asuransi'])->middleware('role:super-admin|admin');
    Route::post('/pasien-asuransi/aktifkan', [AsuransiController::class, 'aktifkan_pasien_asuransi'])->middleware('role:super-admin|admin');
    Route::post('/pasien-asuransi/nonaktifkan', [AsuransiController::class, 'nonaktifkan_pasien_asuransi'])->middleware('role:super-admin|admin');

    Route::get('/logout', [AuthController::class, 'logout']);
});
