<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::all();
        $notification = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $notificationCount = $notification->count();

        return view('activities.index', compact('activities', 'notification', 'notificationCount'));
    }

    public function create()
    {
        return view('activities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_club' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi foto
        ]);

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('photos', 'public');
                $photos[] = $path;
            }
        }

        Activity::create([
            'id_club' => $request->id_club,
            'name' => $request->name,
            'description' => $request->description,
            'photos' => json_encode($photos),
        ]);

        return redirect()->route('activities.index')->with('success', 'Activity created successfully.');
    }

    public function show($id)
    {
        $activity = Activity::findOrFail($id);

        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        return view('activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'id_club' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi foto
        ]);

        $photos = json_decode($activity->photos, true);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('photos', 'public');
                $photos[] = $path;
            }
        }

        $activity->update([
            'id_club' => $request->id_club,
            'name' => $request->name,
            'description' => $request->description,
            'photos' => json_encode($photos),
        ]);

        return redirect()->route('activities.index')->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity)
    {
        $photos = json_decode($activity->photos, true);
        foreach ($photos as $photo) {
            Storage::disk('public')->delete($photo);
        }

        $activity->delete();
        return redirect()->route('activities.index')->with('success', 'Activity deleted successfully.');
    }
}
