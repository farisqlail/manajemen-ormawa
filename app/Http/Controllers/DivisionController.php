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
        $club = Clubs::findOrFail($id_club);
        $divisions = Division::where('id_clubs', $id_club)->get();
        $notification = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $notificationCount = $notification->count();

        return view('divisions.index', compact('divisions', 'club', 'notification', 'notificationCount'));
    }

    public function create($id_club)
    {
        $club = Clubs::findOrFail($id_club);
        return view('divisions.create', compact('club'));
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

        return redirect()->route('divisions.index', $id_club)->with('success', 'Division created successfully.');
    }

    public function edit($id)
    {
        $division = Division::findOrFail($id);
        $club = Clubs::findOrFail($division->id_clubs);
        return view('divisions.edit', compact('division', 'club'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $division = Division::findOrFail($id);
        $division->update([
            'name' => $request->name,
        ]);

        return redirect()->route('divisions.index', $division->id_clubs)->with('success', 'Division updated successfully.');
    }

    public function destroy($id)
    {
        $division = Division::findOrFail($id);
        $division->delete();

        return redirect()->back()->with('success', 'Division deleted successfully.');
    }
}
