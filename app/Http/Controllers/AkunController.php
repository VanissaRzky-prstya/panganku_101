<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AkunController extends Controller
{
    public function index(){
        return view('frontend.akun.index');
    }
    
    public function update(Request $request){
        $user = auth()->user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;

        if (!empty($data['password'])){
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        return redirect()->route('akun.index')->with('success', 'Perubahan berhasil disimpan');
    }

    public function updateFoto(Request $request){
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
        $user = auth()->user();
        $pathLama = $user->photo;
        $pathBaru = $request->file('photo')->store('foto-profil', 'public');
        if (!$pathBaru){
            return back()->withErrors([
                'photo' => 'Foto gagal disimpan. Coba lagi',
            ]);
        }
        $user->photo = $pathBaru;
        $user->save();
        if ($pathLama && $pathLama !== $pathBaru){
            Storage::disk('public')->delete($pathLama);
        }
        return redirect()->route('akun.index')->with('success', 'Foto berhasil disimpan');
    }
}