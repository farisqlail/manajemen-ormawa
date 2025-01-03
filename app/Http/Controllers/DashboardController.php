<?php

namespace App\Http\Controllers;

use App\Models\Proker;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $pendingProkers = Proker::where('status', 'pending')->get();
        $nonPendingProkers = Proker::where('status', '!=', 'pending')->get();
        $members = Anggota::where('id_club', Auth::user()->id_club)->paginate(10);

        return view('dashboard.index', compact('pendingProkers', 'nonPendingProkers', 'members'));
    }
}
