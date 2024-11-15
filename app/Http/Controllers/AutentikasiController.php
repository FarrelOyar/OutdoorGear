<?php

namespace App\Http\Controllers;

use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Support\Facades\Auth;

class AutentikasiController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
        
    // }
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('index');
        }

        return view('auth.login');
    }
    public function loginprocess(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ], [
            'email.email' => 'Format Email tidak valid!!',
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('index')->with('loginSuccess','Login Berhasil');
        }
        return back()->with('loginError', 'Login gagal!!!');
    }
    public function regist()
    {
        return view('auth.regist');
    }
    public function registprocess(Request $request)
    {
        $request->validate([
            'username' => ['required', "regex:/^[a-zA-Z\s,'-]+$/"],
            'email' => ['required', 'email', 'unique:users'],
            'password1' => ['required', 'min:8'],
            'password2' => ['required', 'same:password1'],
            'nik' => ['required', 'numeric', 'digits:16', 'unique:users'],
            'foto_ktp' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,bmp,svg'],
        ], [
            'username.required' => 'The name field is required.',
            'username.unique' => 'Username telah terdaftar',
            'username.regex' => 'Hanya bisa memasukkan huruf dan tanda petik saja!!!',
            'email.email' => 'Format Email tidak valid!!',
            'email.unique' => 'Email telah terdaftar!!!',
            'password1.required' => 'Field password wajib diisi.',
            'password1.min' => 'Password minimal 8 karakter.',
            'password2.required' => 'Silakan konfirmasi password.',
            'password2.same' => 'Password dan konfirmasi password tidak cocok.',
            'nik.required' => 'The nik field is required.',
            'nik.numeric' => 'Masukkan angka pada NIK!!!',
            'nik.digits' => 'Masukkan 16 digit sesuai ktp!!!',
            'foto_ktp.required' => 'Foto KTP wajib diisi',
            'foto_ktp.mimes' => 'Format file tidak valid,Hanya diperbolehkan:jpeg,png,jpg,gif,bmp,svg',
            'foto_ktp.max' => 'Ukuran file tidak boleh melebihi 2048kb'
        ]);

        $extension = $request->file('foto_ktp')->getClientOriginalExtension();
        $photoname = $request->nik . '-' . now()->timestamp . '.' . $extension;
        $request->file('foto_ktp')->storeAs('foto_ktp', $photoname);

        DB::table('users')->insert([
            'role' => 'user',
            'email' => $request->email,
            'nik' => $request->nik,
            'username' => $request->username,
            'password' => bcrypt($request->password1),
            'foto_ktp' => $photoname
        ]);

        session()->flash('message', 'Pendaftaran Berhasil!');
        return redirect('login');
    }

    public function logout(Request $request){
        Auth::logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect()->route('login');
    }
}
