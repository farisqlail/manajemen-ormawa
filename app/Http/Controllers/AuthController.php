<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Clubs;
use App\Models\Division;
use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginForm()
    {
        $daftarProker = Proker::with('club')->get()->groupBy('id_club');

        return view('auth.login', compact('daftarProker'));
    }

    public function login(Request $permintaan)
    {
        $kredensial = $permintaan->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $pengguna = \App\Models\User::where('email', $kredensial['email'])->first();

        if (!$pengguna) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan di sistem.',
            ]);
        }

        if ($pengguna->status === 'nonactive') {
            return back()->withErrors([
                'email' => 'Akun Anda belum aktif. Silakan lakukan registrasi ulang atau aktivasi.',
            ]);
        }

        // DEBUG: Cek password hash
        \Log::info('=== LOGIN DEBUG ===');
        \Log::info('Email: ' . $kredensial['email']);
        \Log::info('Password input: ' . $kredensial['password']);
        \Log::info('Password hash in DB: ' . $pengguna->password);
        \Log::info('Hash check result: ' . (Hash::check($kredensial['password'], $pengguna->password) ? 'MATCH' : 'NO MATCH'));

        // Cek manual password
        if (!Hash::check($kredensial['password'], $pengguna->password)) {
            \Log::info('Password check failed manually');
            return back()->withErrors([
                'password' => 'Password salah.',
            ]);
        }

        if (Auth::attempt($kredensial)) {
            $permintaan->session()->regenerate();
            \Log::info('Login successful for: ' . $kredensial['email']);
            return redirect()->intended('dashboard');
        }

        \Log::error('Auth::attempt failed even though Hash::check passed');
        return back()->withErrors([
            'email' => 'Gagal login. Silakan coba lagi.',
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if ($user->status === 'active') {
                return redirect()->back()
                    ->with('email_active', true)
                    ->withInput();
            } elseif ($user->status === 'nonactive') {
                $user->password = Hash::make('12345678');
                $user->save();

                Auth::login($user);

                return redirect()->route('dashboard')->with('success', 'Registrasi berhasil, Anda telah login otomatis.');
            }
        } else {
            return redirect()->back()
                ->with('email_not_found', true)
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function index()
    {
        $users = User::all();
        $notifikasi = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $jumlahNotifikasi = $notifikasi->count();

        return view('pengguna.index', compact('users', 'notifikasi', 'jumlahNotifikasi'));
    }

    public function create()
    {
        $daftarOrmawa = Clubs::all();
        $daftarDivisi = Division::all();

        $notifikasi = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();

        $jumlahNotifikasi = $notifikasi->count();

        return view('pengguna.buat', compact('daftarOrmawa', 'daftarDivisi', 'notifikasi', 'jumlahNotifikasi'));
    }

    public function store(Request $request)
    {
        $dataValidasi = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'id_club' => ['required', 'integer'],
            'role' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        $passwordDefault = '12345678';

        User::create([
            'name' => $dataValidasi['name'],
            'email' => $dataValidasi['email'],
            'password' => Hash::make($passwordDefault),
            'id_club' => $dataValidasi['id_club'],
            'id_division' => $request->get('id_division'),
            'role' => $dataValidasi['role'],
            'status' => $dataValidasi['status'],
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $daftarOrmawa = Clubs::all();
        $daftarDivisi = Division::all();
        $notifikasi = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $jumlahNotifikasi = $notifikasi->count();

        return view('pengguna.edit', compact('user', 'daftarOrmawa', 'daftarDivisi', 'notifikasi', 'jumlahNotifikasi'));
    }

    public function update(Request $request, User $user)
    {
        $dataPengguna = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'id_club' => ['required', 'integer'],
            'role' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);
    
        $user->name = $dataPengguna['name'];
        $user->email = $dataPengguna['email'];
        $user->id_club = $dataPengguna['id_club'];
        $user->id_division = $request->get('id_division');
        $user->role = $dataPengguna['role'];
        $user->status = $dataPengguna['status'];
    
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        } 
        elseif ($request->has('reset_password')) {
            $user->password = Hash::make($dataPengguna['name'] . '123');
        }
    
        $user->save();
    
        $message = 'Pengguna berhasil diperbarui.';
        if ($request->has('reset_password')) {
            $message .= ' Password direset menjadi: ' . $dataPengguna['name'] . '123';
        }
    
        return redirect()->route('users.index')->with('success', $message);
    }
    

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();

        Anggota::create([
            'name' => $user->name,
            'email' => $user->email,
            'id_club' => $user->id_club,
            'id_division' => $user->id_division,
        ]);

        return redirect()->route('dashboard')->with('success', 'User approved successfully.');
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User rejected successfully.');
    }
}
