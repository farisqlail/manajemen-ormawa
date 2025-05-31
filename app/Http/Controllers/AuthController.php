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
        $prokers = Proker::with('club')->get()->groupBy('id_club');

        return view('auth.login', compact('prokers'));
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
        $prokers = Proker::with('club')->get()->groupBy('id_club');

        return view('auth.register', compact('clubs', 'divisions', 'prokers'));
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
        $notification = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $notificationCount = $notification->count();

        return view('users.index', compact('users', 'notification', 'notificationCount'));
    }

    public function create()
    {
        $clubs = Clubs::all();
        $divisions = Division::all();
        $notification = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $notificationCount = $notification->count();

        return view('users.create', compact('clubs', 'divisions', 'notification', 'notificationCount'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'id_club' => ['required', 'integer'],
            'role' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        $password = '12345678';

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            'id_club' => $data['id_club'],
            'id_division' => $request->get('id_division'),
            'role' => $data['role'],
            'status' => $data['status'],
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $clubs = Clubs::all();
        $divisions = Division::all();
        $notification = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $notificationCount = $notification->count();

        return view('users.edit', compact('user', 'clubs', 'divisions', 'notification', 'notificationCount'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'id_club' => ['required', 'integer'],
            'role' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->id_club = $data['id_club'];
        $user->id_division = $request->get('id_division');
        $user->role = $data['role'];
        $user->status = $data['status'];

        if ($request->filled('password')) {
            $user->password = Hash::make($data['name'] . '123'); 
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
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
