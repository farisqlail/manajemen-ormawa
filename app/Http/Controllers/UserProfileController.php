<?php

namespace App\Http\Controllers;

use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserProfileController extends Controller
{
    public function show()
    {
        $pengguna = Auth::user();
        $notifikasi = Proker::where(function ($kueri) {
            $kueri->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();

        $jumlahNotifikasi = $notifikasi->count();

        return view('auth.show', compact('pengguna', 'notifikasi', 'jumlahNotifikasi'));
    }

    public function edit()
    {
        $user = Auth::user();
        $notifikasi = Proker::where(function ($kueri) {
            $kueri->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();

        $jumlahNotifikasi = $notifikasi->count();

        return view('auth.edit', compact('user', 'notifikasi', 'jumlahNotifikasi'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'nullable|string',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('profile.user.show', $user->id)->with('success', 'Profil berhasil diperbarui.');
    }
}
