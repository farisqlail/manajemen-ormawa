<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Clubs;
use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Clubs::all();
        $notification = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $notificationCount = $notification->count();

        return view('clubs.index', compact('clubs', 'notification', 'notificationCount'));
    }

    public function create()
    {
        $notification = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $notificationCount = $notification->count();

        return view('clubs.create', compact('notification', 'notificationCount'));
    }

    public function store(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create Club: ' . $e->getMessage());
        }
    }

    public function edit(Clubs $club)
    {
        $notification = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $notificationCount = $notification->count();

        return view('clubs.edit', compact('club', 'notification', 'notificationCount'));
    }

    public function editOrmawa(Clubs $club)
    {
        return view('clubs.editOrmawa', compact('club'));
    }

    public function update(Request $request, Clubs $club)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'photo_structure' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->all();

            if ($request->hasFile('logo')) {
                if ($club->logo) {
                    Storage::disk('public')->delete($club->logo);
                }
                $data['logo'] = $request->file('logo')->store('logos', 'public');
            }

            if ($request->hasFile('photo_structure')) {
                if ($club->photo_structure) {
                    Storage::disk('public')->delete($club->photo_structure);
                }
                $data['photo_structure'] = $request->file('photo_structure')->store('photos', 'public');
            }

            $club->update($data);

            return redirect()->route('clubs.index')->with('success', 'Club updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update Club: ' . $e->getMessage());
        }
    }

    public function updateOrmawa(Request $request, Clubs $club)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'photo_structure' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->all();

            if ($request->hasFile('logo')) {
                if ($club->logo) {
                    Storage::disk('public')->delete($club->logo);
                }
                $data['logo'] = $request->file('logo')->store('logos', 'public');
            }

            if ($request->hasFile('photo_structure')) {
                if ($club->photo_structure) {
                    Storage::disk('public')->delete($club->photo_structure);
                }
                $data['photo_structure'] = $request->file('photo_structure')->store('photos', 'public');
            }

            $club->update($data);

            return redirect()->route('profile')->with('success', 'Club updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update Club: ' . $e->getMessage());
        }
    }

    public function destroy(Clubs $club)
    {
        $club->delete();
        return redirect()->route('clubs.index')->with('success', 'Club deleted successfully.');
    }

    public function showProfile($id)
    {
        $club = Clubs::findOrFail($id);
        $prokers = Proker::where('id_club', $id)->get();
        $activities = Activity::where('id_club', $id)->get();

        return view('ormawa.profile', compact('club', 'prokers', 'activities'));
    }
}
