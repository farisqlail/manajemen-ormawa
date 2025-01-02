<?php

namespace App\Http\Controllers;

use App\Models\Clubs;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Clubs::all();
        return view('clubs.index', compact('clubs'));
    }

    public function create()
    {
        return view('clubs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
        ]);

        Clubs::create($request->all());
        return redirect()->route('clubs.index')->with('success', 'Club created successfully.');
    }

    public function edit(Clubs $club)
    {
        return view('clubs.edit', compact('club'));
    }

    public function update(Request $request, Clubs $club)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
        ]);

        $club->update($request->all());
        return redirect()->route('clubs.index')->with('success', 'Club updated successfully.');
    }

    public function destroy(Clubs $club)
    {
        $club->delete();
        return redirect()->route('clubs.index')->with('success', 'Club deleted successfully.');
    }
}
