<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function daftar(){
        return view('frontend.auth.daftar');
    }
    
    public function prosesDaftar(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required','string','min:6','confirmed'],
        ],[
            'name.required' => 'Nama wajib diisi.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.unique' => 'Nomor HP sudah terdaftar. Silakan masuk.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);
        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => $request->password,
            'email' => null,
        ]);
        return redirect()->route('masuk')->with('success', 'Pendaftaran berhasil. Silakan masuk menggunakan nomor HP dan password.');
    }

    public function masuk(){
        return view('frontend.auth.masuk');
    }

    public function prosesMasuk(Request $request){
        $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'],
        ],[
            'phone.required' => 'Nomor HP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);
        $credentials = [
            'phone' => $request->phone,
            'password' => $request->password,
        ];
        if (Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->intended(route('beranda'))->with('success', 'Berhasil masuk');
        }
        return back()->withErrors(['phone' => 'Nomor HP atau password salah'])->withInput($request->only('phone'));
    }

    public function lupaPassword(){
        return view('frontend.auth.lupa-password');
    }

    public function prosesLupaPassword(Request $request){
        $request->validate([
            'phone' => 'required',
            'password' => 'required|min:6|confirmed',
        ],[
            'phone.required' => 'Nomor HP wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);
        $user = User::where('phone', $request->phone)->first();
        if (!$user){
            return back()->withInput()->withErrors(['phone' => 'Nomor HP belum terdaftar']);
        }
        $user->password = $request->password;
        $user->save();
        return redirect()->route('masuk')->with('success', 'Password berhasil diperbarui. Silakan masuk kembali');
    }

    public function keluar(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('beranda')->with('success', 'Berhasil keluar');
    }
}