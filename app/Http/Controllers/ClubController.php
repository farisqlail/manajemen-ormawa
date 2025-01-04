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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_structure' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        if ($request->hasFile('photo_structure')) {
            $data['photo_structure'] = $request->file('photo_structure')->store('photos', 'public');
        }

        Clubs::create($data);
        return redirect()->route('clubs.index')->with('success', 'Club created successfully.');
    }

    public function edit(Clubs $club)
    {
        return view('clubs.edit', compact('club'));
    }

    public function editOrmawa($id)
    {
        $club = Clubs::findOrFail($id);
        return view('clubs.editOrmawa', compact('club'));
    }

    public function update(Request $request, Clubs $club)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_structure' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        if ($request->hasFile('photo_structure')) {
            $data['photo_structure'] = $request->file('photo_structure')->store('photos', 'public');
        }

        $club->update($data);
        return redirect()->route('clubs.index')->with('success', 'Club updated successfully.');
    }

    public function updateOrmawa(Request $request, Clubs $club)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_structure' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        if ($request->hasFile('photo_structure')) {
            $data['photo_structure'] = $request->file('photo_structure')->store('photos', 'public');
        }

        $club->update($data);
        return redirect()->route('profile')->with('success', 'Club updated successfully.');
    }

    public function destroy(Clubs $club)
    {
        $club->delete();
        return redirect()->route('clubs.index')->with('success', 'Club deleted successfully.');
    }
}
