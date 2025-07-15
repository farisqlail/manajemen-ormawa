<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Clubs;
use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'superadmin') {
            $kegiatan = Activity::with('club')->get(); 
        } else {
            $kegiatan = Activity::where('id_club', Auth::user()->id_club)->get();
        }

        $notifikasi = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();

        $jumlahNotifikasi = $notifikasi->count();

        return view('activities.index', compact('kegiatan', 'notifikasi', 'jumlahNotifikasi'));
    }

    public function create()
    {
        $clubs = [];

        if (Auth::user()->role === 'superadmin') {
            $clubs = Clubs::all();
        }

        return view('activities.create', compact('clubs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_club' => 'required',
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $daftarFoto = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $satuFoto) {
                $path = $satuFoto->store('photos', 'public');
                $daftarFoto[] = $path;
            }
        }

        Activity::create([
            'id_club' => $request->id_club,
            'name' => $request->nama_kegiatan,
            'description' => $request->deskripsi,
            'photos' => json_encode($daftarFoto),
        ]);

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $kegiatan = Activity::findOrFail($id);

        return view('activities.show', compact('kegiatan'));
    }

    public function edit(Activity $activity)
    {
        $clubs = [];

        if (Auth::user()->role === 'superadmin') {
            $clubs = Clubs::all();
        }

        return view('activities.edit', compact('activity', 'clubs'));
    }

    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'id_club' => 'required',
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $foto = json_decode($activity->photos, true);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('photos', 'public');
                $foto[] = $path;
            }
        }

        $activity->update([
            'id_club' => $request->id_club,
            'name' => $request->nama_kegiatan,
            'description' => $request->deskripsi,
            'photos' => json_encode($foto),
        ]);

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil diperbarui.');
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
