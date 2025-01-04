<?php

namespace App\Http\Controllers;

use App\Models\Proker;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $pendingUsers = User::where('status', 'pending')->get();
        $pendingProkers = Proker::where('status', 'pending')->get();
        $pendingProkersAdmin = Proker::where('status', 'pending')->with('club')->get()->groupBy('id_club');
        $nonPendingProkers = Proker::where('status', '!=', 'pending')->get();
        $approvedProkers = Proker::where('status', 'approved')->with('club')->get()->groupBy('id_club');
        $members = Anggota::where('id_club', Auth::user()->id_club)->paginate(10);

        return view('dashboard.index', compact('pendingUsers', 'pendingProkers', 'nonPendingProkers', 'members', 'pendingProkersAdmin', 'approvedProkers'));
    }

    public function showClubProkers($clubId)
    {
        $pendingProkers = Proker::where('id_club', $clubId)->where('status', 'pending')->get();

        return view('prokers.club', compact('pendingProkers'));
    }
}
