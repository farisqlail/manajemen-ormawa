<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Clubs; // Pastikan model Clubs ada
use App\Models\Division; // Pastikan model Division ada
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggotaController extends Controller
{
    public function index()
    {
        $userIdClub = Auth::user()->id_club;
        $anggotas = Anggota::with(['club', 'division'])
            ->where('id_club', $userIdClub)
            ->get();

        return view('anggotas.index', compact('anggotas'));
    }

    public function create()
    {
        $user = Auth::user();
        $clubs = Clubs::where('id', $user->id_club)->get();
        $divisions = Division::where('id_clubs', $user->id_club)->get();

        return view('anggotas.create', compact('clubs', 'divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_club' => 'required|integer',
            'id_division' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        Anggota::create($request->all());
        return redirect()->route('anggotas.index')->with('success', 'Anggota created successfully.');
    }

    public function show(Anggota $anggota)
    {
        return view('anggotas.show', compact('anggota'));
    }

    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        $userIdClub = Auth::user()->id_club;
        $clubs = Clubs::where('id', $userIdClub)->get();
        $divisions = Division::where('id_clubs', $userIdClub)->get();

        return view('anggotas.edit', compact('anggota', 'clubs', 'divisions'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $request->validate([
            'id_club' => 'required|integer',
            'id_division' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        $anggota->update($request->all());
        return redirect()->route('anggotas.index')->with('success', 'Anggota updated successfully.');
    }

    public function destroy(Anggota $anggota)
    {
        $anggota->delete();
        return redirect()->route('anggotas.index')->with('success', 'Anggota deleted successfully.');
    }
}
