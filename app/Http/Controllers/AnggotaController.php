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
        $idKlubPengguna = Auth::user()->id_club;

        $daftarAnggota = Anggota::with(['club', 'division'])
            ->where('id_club', $idKlubPengguna)
            ->get();

        $notifikasi = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();

        $jumlahNotifikasi = $notifikasi->count();

        return view('anggotas.index', compact('daftarAnggota', 'notifikasi', 'jumlahNotifikasi'));
    }

    public function create()
    {
        $pengguna = Auth::user();
        $daftarOrmawa = Clubs::where('id', $pengguna->id_club)->get();
        $daftarDivisi = Division::where('id_clubs', $pengguna->id_club)->get();

        return view('anggotas.create', compact('daftarOrmawa', 'daftarDivisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_club' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        Anggota::create($request->all());

        return redirect()->route('anggotas.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function show(Anggota $anggota)
    {
        return view('anggotas.show', compact('anggota'));
    }

    public function edit($id)
    {
        $dataAnggota = Anggota::findOrFail($id);
        $idOrmawaPengguna = Auth::user()->id_club;
        $daftarOrmawa = Clubs::where('id', $idOrmawaPengguna)->get();
        $daftarDivisi = Division::where('id_clubs', $idOrmawaPengguna)->get();

        return view('anggotas.edit', compact('dataAnggota', 'daftarOrmawa', 'daftarDivisi'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $request->validate([
            'id_club' => 'required|integer',
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
