<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    //
    public function index(Request $request)
    {
        // return login view
        if ($request->isMethod('get')) {
            return view('auth.login');
        } else if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required',
            ]);
            $user_active = User::where('email', $request->email)->first();
            if ($user_active->is_active == 0) {
                return redirect('/login')->with('error', 'User belum diaktifkan. Silahkan hubungi administator!');
            }
            $login = [
                'email' => $request->email,
                'password' => $request->password
            ];
            if (Auth::attempt($login)) {
                // add variable session
                $user = User::where('email', $request->email)->first();
                User::where('id', $user->id)->update([
                    'last_login' => date('Y-m-d H:i:s')
                ]);
                $detail_user = DB::table('detail_user')->where('user_id', $user->id)->first();
                session([
                    'role' => $user->role,
                    'detail_user' => $detail_user,
                ]);
                return redirect('')->with('success', 'Login success');
            } else {
                return redirect('/login')->with('error', 'Invalid email or password');
            }

        }
    }

    public function register(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('auth.register');
        }
        else if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'name' => 'required|max:55',
                'email' => 'email|required|unique:users',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required|same:password',
            ]);
            // dd($validatedData);
            $data_user = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password_confirmation),
                'role' => 'user'
            ];
            DB::table('users')->insert($data_user);

            return redirect('login')->with('success', 'User created successfully!');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }
}
