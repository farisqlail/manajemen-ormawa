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
        $pengguna = Auth::user();

        $penggunaPending = User::where('status', 'pending')->get();

        $daftarOrmawa = Clubs::all();

        $notifikasi = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();

        $jumlahNotifikasi = $notifikasi->count();

        $prokerPending = Proker::where('id_club', $pengguna->id_club)
            ->where(function ($query) {
                $query->where('status', 'pending')
                    ->where('target_event', '>=', now());
            })
            ->orWhere(function ($query) use ($pengguna) {
                $query->where('id_club', $pengguna->id_club)
                    ->whereNotNull('status_laporan')
                    ->where('status_laporan', 'pending');
            })
            ->get();

        $prokerPendingAdmin = Proker::where('status', 'approved')
            ->orWhere(function ($query) {
                $query->whereNotNull('status_laporan')
                    ->where('status_laporan', 'pending');
            })
            ->with('club')
            ->orderBy('id_club')
            ->get()
            ->groupBy('id_club');

        $prokerPendingPembina = Proker::where('status', 'pending')
            ->orWhere(function ($query) {
                $query->whereNotNull('status_laporan')
                    ->where('status_laporan', 'pending');
            })
            ->with('club')
            ->get()
            ->groupBy('id_club');

        $prokerNonPending = Proker::where('id_club', $pengguna->id_club)
            ->where(function ($query) {
                $query->where('status', '!=', 'pembina')
                    ->where('target_event', '>=', now());
            })
            ->orWhere(function ($query) use ($pengguna) {
                $query->where('id_club', $pengguna->id_club)
                    ->whereNotNull('status_laporan')
                    ->where('status_laporan', '!=', 'pending');
            })
            ->get();

        $prokerDisetujui = Proker::where('status', 'approved')
            ->orWhere(function ($query) {
                $query->whereNull('status_laporan')
                    ->where('status_laporan', null);
            })
            ->with('club')
            ->get()
            ->groupBy('id_club');

        $anggota = Anggota::where('id_club', $pengguna->id_club)->with('division')->paginate(10);

        return view('dashboard.index', compact(
            'penggunaPending',
            'prokerPending',
            'prokerPendingAdmin',
            'prokerPendingPembina',
            'prokerNonPending',
            'prokerDisetujui',
            'anggota',
            'daftarOrmawa',
            'notifikasi',
            'jumlahNotifikasi'
        ));
    }

    public function showClubProkers($idOrmawa)
    {
        $query = Proker::where('id_club', $idOrmawa);

        if (request()->has('search') && request('search') !== '') {
            $search = request('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        $anggota = Anggota::where('id_club', $idOrmawa)->with('division')->paginate(10);

        $daftarProker = $query->get();

        $notifikasi = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();

        $jumlahNotifikasi = $notifikasi->count();

        if (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin') {
            $prokerPending = $daftarProker->sortByDesc('created_at');
        } else {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        return view('prokers.ormawa', compact('prokerPending', 'notifikasi', 'jumlahNotifikasi', 'anggota'));
    }
}
