<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function index()
    {
        $kegiatan = Activity::where('id_club', Auth::user()->id_club)->get();

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
        return view('activities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_club' => 'required',
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi foto
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
            'name' => $request->nama_kegiatan, // Tetap pakai kolom `name` di database
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
        return view('activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'id_club' => 'required',
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi foto
        ]);

        $foto = json_decode($activity->photos, true); // Menyimpan nama lama jika tidak diganti

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
