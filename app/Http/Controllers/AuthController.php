<?php

namespace App\Http\Controllers;

use App\Models\Clubs;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function registerForm()
    {
        $clubs = Clubs::all();
        $divisions = Division::all();
        return view('auth.register', compact('clubs', 'divisions'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'id_club' => ['required', 'integer'],
            'id_division' => ['required', 'integer'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'id_club' => $data['id_club'],
            'id_division' => $data['id_division'],
            'role' => 'ormawa',
            'status' => 'pending'
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
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
        return view('users.index', compact('users'));
    }

    // Menampilkan form untuk membuat pengguna baru
    public function create()
    {
        $clubs = Clubs::all();
        $divisions = Division::all();
        return view('users.create', compact('clubs', 'divisions'));
    }

    // Menyimpan pengguna baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'id_club' => ['required', 'integer'],
            'id_division' => ['required', 'integer'],
            'role' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        // Set password to name + '123'
        $password = $data['name'] . '123';

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            'id_club' => $data['id_club'],
            'id_division' => $data['id_division'],
            'role' => $data['role'],
            'status' => $data['status'],
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Menampilkan form untuk mengedit pengguna
    public function edit(User $user)
    {
        $clubs = Clubs::all();
        $divisions = Division::all();
        return view('users.edit', compact('user', 'clubs', 'divisions'));
    }

    // Memperbarui pengguna
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'id_club' => ['required', 'integer'],
            'id_division' => ['required', 'integer'],
            'role' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);
    
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->id_club = $data['id_club'];
        $user->id_division = $data['id_division'];
        $user->role = $data['role'];
        $user->status = $data['status'];
    
        // Update password only if it's filled
        if ($request->filled('password')) {
            $user->password = Hash::make($data['name'] . '123'); // Set password to name + '123'
        }
    
        $user->save();
    
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }    

    // Menghapus pengguna
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
