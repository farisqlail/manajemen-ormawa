<?php

namespace App\Http\Controllers;

use App\Models\Proker;
use App\Models\Anggota;
use App\Models\Clubs;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $pendingUsers = User::where('status', 'pending')->get();

        $clubs = Clubs::all();
        $notification = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $notificationCount = $notification->count();

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

        $pendingProkersAdmin = Proker::where('status', 'approved')
            ->orWhere(function ($query) {
                $query->whereNotNull('status_laporan')
                    ->where('status_laporan', 'pending');
            })
            ->with('club')
            ->orderBy('id_club')
            ->get()
            ->groupBy('id_club');


        $pendingProkersPembinaAdmin = Proker::where('status', 'pending')
            ->orWhere(function ($query) {
                $query->whereNotNull('status_laporan')
                    ->where('status_laporan', 'pending');
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

        $approvedProkers =  Proker::where('status', 'approved')
            ->orWhere(function ($query) {
                $query->whereNull('status_laporan')
                    ->where('status_laporan', null);
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
            'members',
            'clubs',
            'notification',
            'notificationCount'
        ));
    }

    public function showClubProkers($clubId)
    {
        $prokers = Proker::where('id_club', $clubId)->get();
        $notification = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $notificationCount = $notification->count();
        
        if (Auth::user()->role == 'admin') {
            $pendingProkers = $prokers->where('status', 'pending')
                ->merge($prokers->where('status_laporan', 'pending'));
        } else {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        return view('prokers.club', compact('pendingProkers', 'notification', 'notificationCount'));
    }
}
