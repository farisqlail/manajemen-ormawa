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
        $user = Auth::user();

        $pendingUsers = User::where('status', 'pending')->get();

        $pendingProkers = Proker::where('status', 'pending')
            ->where('target_event', '>=', now())
            ->orWhereNotNull('status_laporan')
            ->get();
        $pendingProkers = Proker::where('status', 'pending')
            ->where('target_event', '>=', now())
            ->orWhere(function ($query) {
                $query->whereNotNull('status_laporan')
                    ->where('status_laporan', 'pending');
            })
            ->get();

        $pendingProkersAdmin = Proker::where('status', 'pending')
            ->orWhere(function ($query) {
                $query->whereNotNull('status_laporan')
                    ->where('status_laporan', 'pending');
            })
            ->with('club')
            ->get()
            ->groupBy('id_club');

        $pendingProkersPembinaAdmin = Proker::where('status', 'pembina')
            ->orWhere(function ($query) {
                $query->whereNotNull('status_laporan')
                    ->where('status_laporan', 'pembina');
            })
            ->with('club')
            ->get()
            ->groupBy('id_club');

        $nonPendingProkers = Proker::where('status', '!=', 'pembina')
            ->where('id_club', $user->id_club)
            ->where('target_event', '>=', now())
            ->orWhere(function ($query) {
                $query->whereNotNull('status_laporan')
                    ->where('status_laporan', '!=', 'pending');
            })
            ->get();

        $approvedProkers = Proker::where('status', 'approved')
            ->orWhere(function ($query) {
                $query->whereNotNull('status_laporan')
                    ->where('status_laporan', 'approved');
            })
            ->with('club')
            ->get()
            ->groupBy('id_club');

        $members = Anggota::where('id_club', $user->id_club)->paginate(10);

        return view('dashboard.index', compact(
            'pendingUsers',
            'pendingProkers',
            'pendingProkersAdmin',
            'pendingProkersPembinaAdmin',
            'nonPendingProkers',
            'approvedProkers',
            'members'
        ));
    }

    public function showClubProkers($clubId)
    {
        $prokers = Proker::where('id_club', $clubId)->get();

        // if ($prokers->isEmpty()) {
        //     return redirect()->back()->with('error', 'Tidak ada proker untuk club ini.');
        // }

        if (Auth::user()->role == 'pembina') {
            $pendingProkers = $prokers->where('status', 'pending')
                ->merge($prokers->where('status_laporan', 'pending'));
        } elseif (Auth::user()->role == 'admin') {
            $pendingProkers = $prokers->where('status', 'pembina')
                ->merge($prokers->where('status_laporan', 'pembina'));
        } else {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        return view('prokers.club', compact('pendingProkers'));
    }
}
