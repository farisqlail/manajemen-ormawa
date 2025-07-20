<?php

namespace App\Http\Controllers;

use App\Models\Clubs;
use App\Models\Division;
use App\Models\Proker;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index($id_club)
    {
        $dataOrmawa = Clubs::findOrFail($id_club);
        $daftarDivisi = Division::where('id_clubs', $id_club)->get();
        $notifikasi = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $jumlahNotifikasi = $notifikasi->count();

        return view('divisi.index', compact('daftarDivisi', 'dataOrmawa', 'notifikasi', 'jumlahNotifikasi'));
    }

    public function create($id_club)
    {
        $dataOrmawa = Clubs::findOrFail($id_club);
        return view('divisi.buat', compact('dataOrmawa'));
    }

    public function store(Request $request, $id_club)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Division::create([
            'id_clubs' => $id_club,
            'name' => $request->name,
        ]);

        return redirect()->route('divisions.index', $id_club)->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $dataDivisi = Division::findOrFail($id);
        $dataOrmawa = Clubs::findOrFail($dataDivisi->id_clubs);
        return view('divisi.edit', compact('dataDivisi', 'dataOrmawa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $dataDivisi = Division::findOrFail($id);
        $dataDivisi->update([
            'name' => $request->name,
        ]);

        return redirect()->view('divisions.index', $dataDivisi->id_clubs)->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $division = Division::findOrFail($id);
        $division->delete();

        return redirect()->back()->with('success', 'Division deleted successfully.');
    }
}
