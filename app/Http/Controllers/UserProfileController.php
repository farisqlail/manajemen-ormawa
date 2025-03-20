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
        $user = Auth::user();
        $notification = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $notificationCount = $notification->count();

        return view('auth.show', compact('user', 'notification', 'notificationCount'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('auth.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('profile.user.show', $user->id)->with('success', 'Profile updated successfully.');
    }
}
