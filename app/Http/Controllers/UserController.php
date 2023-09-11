<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = DB::table('users')->join('detail_user', 'users.id', '=', 'detail_user.user_id')->select('users.*', 'detail_user.nama_lengkap', 'detail_user.no_pegawai', 'detail_user.file_foto', 'detail_user.jenis_kelamin')->get();
        // dd($users);
        $data = [
            'title' => 'Users',
            'menu_slug' => 'users',
            'sub_menu_slug' => 'sub-user',
            'data' => $users
        ];
        return view('users.data_user', $data);
    }

    public function tambah(Request $request){
        if ($request->isMethod('post')){
            $request->validate([
                'name' => 'required',
                'email' => 'required|unique:users',
                'posisi' => 'required',
            ],
            [
                'name.required' => 'Nama tidak boleh kosong',
                'email.required' => 'Email tidak boleh kosong',
                'email.unique' => 'Email sudah terdaftar',
                'posisi.required' => 'Posisi tidak boleh kosong',
            ]);
            $data_user = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->posisi,
            ];
            $jabatan = '';
            if ($request->posisi == 'dokter') {
                $jabatan = "Dokter";
            } else if ($request->posisi == 'perawat') {
                $jabatan = "Perawat";
            } else if ($request->posisi == 'admin') {
                $jabatan = "Admin";
            } else if ($request->posisi == 'super-admin') {
                $jabatan = "Super Admin";
            } else if ($request->posisi == 'apoteker') {
                $jabatan = "Apoteker";
            } else if ($request->posisi == 'kasir') {
                $jabatan = "Kasir";
            } else if ($request->posisi == 'user') {
                $jabatan = "Pegawai";
            }
            try {
                $user = DB::table('users')->insertGetId($data_user);
                $data_detail = [
                    'user_id' => $user,
                    'nama_lengkap' => $request->name,
                    'jabatan' => $jabatan,
                ];
                DB::table('detail_user')->insert($data_detail);
                return redirect('users/detail/' . $user)->with('success', 'Data berhasil ditambahkan');
            } catch (\Illuminate\Database\QueryException $e) {
                return redirect('users')->with('error', 'Data gagal ditambahkan\n' . $e->getMessage());
            } catch (\Exception $e) {
                return redirect('users')->with('error', 'Data gagal ditambahkan\n' . $e->getMessage());
            }
        }
    }

    public function detail($id)
    {
        $user = DB::table('users')->
            join('detail_user', 'users.id', '=', 'detail_user.user_id')->
            select(
                'users.*',
                'detail_user.id as id_detail',
                'detail_user.nama_lengkap',
                'detail_user.no_pegawai',
                'detail_user.no_ktp',
                'detail_user.alamat',
                'detail_user.no_telepon',
                'detail_user.jenis_kelamin',
                'detail_user.tanggal_lahir',
                'detail_user.tempat_lahir',
                'detail_user.jabatan',
                'detail_user.agama',
                'detail_user.file_foto')->
            where('users.id', $id)->first();
        // dd($user);
        $data = [
            'title' => 'Detail Users ' . $user->name,
            'menu_slug' => 'users',
            'sub_menu_slug' => 'sub-user',
            'user' => $user
        ];
        return view('users.detail_user', $data);
    }

    public function edit(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_user' => 'required',
            'email' => 'required',
            'role' => 'required',
            'no_pegawai' => 'required',
            'alamat' => 'required',
            'no_ktp' => 'required',
            'no_telepon' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required',
            'tempat_lahir' => 'required',
            'agama' => 'required',
        ], [
            'nama_user.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'role.required' => 'Role tidak boleh kosong',
            'no_pegawai.required' => 'No Pegawai tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'no_ktp.required' => 'No KTP tidak boleh kosong',
            'no_telepon.required' => 'No Telepon tidak boleh kosong',
            'jenis_kelamin.required' => 'Jenis Kelamin tidak boleh kosong',
            'tanggal_lahir.required' => 'Tanggal Lahir tidak boleh kosong',
            'tempat_lahir.required' => 'Tempat Lahir tidak boleh kosong',
            'agama.required' => 'Agama tidak boleh kosong',
        ]);
        $id = $request->id;
        $id_detail = $request->id_detail;
        $data_user = [
            'name' => $request->nama_user,
            'email' => $request->email,
            'role' => $request->role
        ];
        $jabatan = '';
        if ($request->role == 'dokter') {
            $jabatan = "Dokter";
        } else if ($request->role == 'perawat') {
            $jabatan = "Perawat";
        } else if ($request->role == 'admin') {
            $jabatan = "Admin";
        } else if ($request->role == 'super-admin') {
            $jabatan = "Super Admin";
        } else if ($request->role == 'apoteker') {
            $jabatan = "Apoteker";
        } else if ($request->role == 'kasir') {
            $jabatan = "Kasir";
        } else if ($request->role == 'user') {
            $jabatan = "Pegawai";
        }
        // request and save file
        if ($request->foto_base64 != '' or $request->foto_base64 != null) {
            if ($request->foto_lama != '' or $request->foto_lama != null) {
                $file_path = asset('storage/public/asset/foto_profil/' . $request->foto_lama);
                if (file_exists($file_path)) {
                    try {
                        unlink($file_path);
                    } catch (\Exception $e) {
                        return redirect('users/detail/' . $id)->with('error', 'Data gagal diubah\n' . $e->getMessage());
                    }
                }
            }
            $file = $request->foto_base64;
            // convert to base64_decode
            $file = str_replace('data:image/png;base64,', '', $file);
            $file = str_replace(' ', '+', $file);
            $file = base64_decode($file);
            $file_name = time() . '-' . $request->nama_foto;
            // save to public path
            try {
                Storage::disk('public')->put('asset/foto_profil/' . $file_name, $file);
            } catch (\Exception $e) {
                return redirect('users/detail/' . $id)->with('error', 'Data gagal diubah\n' . $e->getMessage());
            }
            // update session detail_user->file_foto
            $data_detail = [
                'nama_lengkap' => $request->nama_user,
                'jabatan' => $jabatan,
                'no_pegawai' => $request->no_pegawai,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'no_ktp' => $request->no_ktp,
                'agama' => $request->agama,
                'file_foto' => $file_name
            ];
        } else {
            $data_detail = [
                'nama_lengkap' => $request->nama_user,
                'jabatan' => $jabatan,
                'no_pegawai' => $request->no_pegawai,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'no_ktp' => $request->no_ktp,
                'agama' => $request->agama,
            ];
        }
        // dd($data_detail);
        try {
            $update_user = DB::table('users')->where('id', $id)->update($data_user);
            try {
                $update_detail = DB::table('detail_user')->where('id', $id_detail)->update($data_detail);
                $detail_user = DB::table('detail_user')->where('user_id', $id)->first();
                session([
                    'detail_user' => $detail_user,
                ]);
                return redirect('users/detail/' . $id)->with('success', 'Data berhasil diubah');
            } catch (\Illuminate\Database\QueryException $e) {
                return redirect('users/detail/' . $id)->with('error', 'Data gagal diubah\n' . $e->getMessage());
            } catch (\Exception $e) {
                return redirect('users/detail/' . $id)->with('error', 'Data gagal diubah\n' . $e->getMessage());
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('users/detail/' . $id)->with('error', 'Data gagal diubah\n' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect('users/detail/' . $id)->with('error', 'Data gagal diubah\n' . $e->getMessage());
        }
    }

    public function ubah_password(Request $request){
        if ($request->isMethod('post')){
            // dd($request->all());
            $id = $request->id_pass_user;
            $pass = $request->password;
            $data = [
                'password' => bcrypt($pass)
            ];
            try {
                DB::table('users')->where('id', $id)->update($data);
                if(Auth::user()->role == 'super-admin'){
                    return redirect('logout')->with('success', 'Password berhasil diubah');
                } else {
                    return redirect('users/detail/' . $id)->with('success', 'Password berhasil diubah');
                }
            } catch (\Illuminate\Database\QueryException $e) {
                return redirect('users/detail/' . $id)->with('error', 'Password gagal diubah\n' . $e->getMessage());
            } catch (\Exception $e) {
                return redirect('users/detail/' . $id)->with('error', 'Password gagal diubah\n' . $e->getMessage());
            }
        }
    }

    public function activate(Request $request){
        if ($request->isMethod('post')){
            // dd($request->all());
            $id = $request->id;
            $data = [
                'is_active' => 1
            ];
            try {
                DB::table('users')->where('id', $id)->update($data);
                return response()->json([
                    'success' => true,
                    'message' => 'User berhasil diaktifkan'
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'error' => true,
                    'message' => 'User gagal diaktifkan\n' . $e->getMessage()
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => true,
                    'message' => 'User gagal diaktifkan\n' . $e->getMessage()
                ]);
            }
        }
    }

    public function deactivate(Request $request){
        if ($request->isMethod('post')){
            // dd($request->all());
            $id = $request->id;
            $data = [
                'is_active' => 0
            ];
            try {
                DB::table('users')->where('id', $id)->update($data);
                return response()->json([
                    'success' => true,
                    'message' => 'User berhasil dinonaktifkan'
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'error' => true,
                    'message' => 'User gagal dinonaktifkan\n' . $e->getMessage()
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => true,
                    'message' => 'User gagal dinonaktifkan\n' . $e->getMessage()
                ]);
            }
        }
    }

    public function delete(Request $request){
        if ($request->isMethod('delete')){
            // dd($request->all());
            $id = $request->id_user;
            $menu = $request->menu;
            $user_active = DB::table('users')->where('id', $id)->first();
            if ($user_active->is_active == 1) {
                if($menu == "data_user"){
                    return redirect('users')->with('error', 'User masih aktif. User tidak dapat dihapus');
                } else if($menu == "detail_user"){
                    return redirect('users/detail/' . $id)->with('error', 'User masih aktif. User tidak dapat dihapus');
                }
            }
            $dokter_poli = DB::table('data_dokter_poli')->where('dokter_id', $id)->join('data_poli', 'data_dokter_poli.poli_id', '=', 'data_poli.id')->first();
            if (isset($dokter_poli)) {
                if($menu == "data_user"){
                    return redirect('users')->with('error', 'User gagal dihapus. User masih terdaftar sebagai dokter di poliklinik ' . $dokter_poli->nama_poli);
                } else if($menu == "detail_user"){
                    return redirect('users/detail/' . $id)->with('error', 'User gagal dihapus. User masih terdaftar sebagai dokter di poliklinik ' . $dokter_poli->nama_poli);
                }
            }
            try {
                DB::table('detail_user')->where('user_id', $id)->delete();
                try {
                    DB::table('users')->where('id', $id)->delete();
                    return redirect('users')->with('success', 'User berhasil dihapus');
                } catch (\Illuminate\Database\QueryException $e) {
                    if($menu == "data_user"){
                        return redirect('users')->with('error', 'User gagal dihapus\n' . $e->getMessage());
                    } else if($menu == "detail_user"){
                        return redirect('users/detail/' . $id)->with('error', 'User gagal dihapus\n' . $e->getMessage());
                    }
                } catch (\Exception $e) {
                    if($menu == "data_user"){
                        return redirect('users')->with('error', 'User gagal dihapus\n' . $e->getMessage());
                    } else if($menu == "detail_user"){
                        return redirect('users/detail/' . $id)->with('error', 'User gagal dihapus\n' . $e->getMessage());
                    }
                }
            } catch (\Illuminate\Database\QueryException $e) {
                if($menu == "data_user"){
                    return redirect('users')->with('error', 'User gagal dihapus\n' . $e->getMessage());
                } else if($menu == "detail_user"){
                    return redirect('users/detail/' . $id)->with('error', 'User gagal dihapus\n' . $e->getMessage());
                }
            } catch (\Exception $e) {
                if($menu == "data_user"){
                    return redirect('users')->with('error', 'User gagal dihapus\n' . $e->getMessage());
                } else if($menu == "detail_user"){
                    return redirect('users/detail/' . $id)->with('error', 'User gagal dihapus\n' . $e->getMessage());
                }
            }
        }
    }
}
