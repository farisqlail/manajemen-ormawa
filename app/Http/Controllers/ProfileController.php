<?php

namespace App\Http\Controllers;

use App\Models\Clubs;
use App\Models\Proker;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $id_club = Auth::user()->id_club;
        $club = Clubs::find($id_club);
        $notification = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $jumlahNotifikasi = $notification->count();

        if (!$club) {
            return redirect()->route('home')->with('error', 'Club not found.');
        }

        return view('profile.index', compact('club', 'notification', 'jumlahNotifikasi'));
    }
}
