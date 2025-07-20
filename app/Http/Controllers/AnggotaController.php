<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Clubs; // Pastikan model Clubs ada
use App\Models\Division; // Pastikan model Division ada
use App\Models\Proker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggotaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifikasi = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();

        $jumlahNotifikasi = $notifikasi->count();

        if ($user->role === 'superadmin' && is_null($user->id_club)) {
            $daftarAnggota = Anggota::with(['club', 'division'])->get();
        } else {
            $daftarAnggota = Anggota::with(['club', 'division'])
                ->where('id_club', $user->id_club)
                ->get();
        }

        return view('anggota.index', compact('daftarAnggota', 'notifikasi', 'jumlahNotifikasi'));
    }


    public function getByClub($id)
    {
        $divisions = Division::where('club_id', $id)
            ->orWhere('id_club', $id)
            ->orWhere('id_clubs', $id)
            ->get();

        return response()->json($divisions);
    }

    public function create()
    {
        $pengguna = Auth::user();
        $daftarOrmawaNonUser = Clubs::all();
        $daftarOrmawa = Clubs::where('id', $pengguna->id_club)->get();
        $daftarDivisi = Division::where('id_clubs', $pengguna->id_club)->get();

        return view('anggota.buat', compact('daftarOrmawa', 'daftarOrmawaNonUser', 'daftarDivisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_club' => 'required|integer',
            'id_division' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        Anggota::create($request->all());

        return redirect()->route('anggotas.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function show(Anggota $anggota)
    {
        return view('anggota.show', compact('anggota'));
    }

    public function edit($id)
    {
        $dataAnggota = Anggota::findOrFail($id);
        $idOrmawaPengguna = Auth::user()->id_club;
        $daftarOrmawaNonUser = Clubs::all();
        $daftarOrmawa = Clubs::where('id', $idOrmawaPengguna)->get();
        $daftarDivisi = Division::where('id_clubs', $idOrmawaPengguna)->get();

        return view('anggota.edit', compact('dataAnggota', 'daftarOrmawa', 'daftarOrmawaNonUser', 'daftarDivisi'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $request->validate([
            'id_club' => 'required|integer',
            'id_division' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        $anggota->update($request->all());

        return redirect()->route('anggotas.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        $anggota->delete();
        return redirect()->route('anggotas.index')->with('success', 'Anggota deleted successfully.');
    }
}
