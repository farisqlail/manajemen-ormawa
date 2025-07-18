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
        $daftarOrmawa = Clubs::all();
        $notifikasi = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $jumlahNotifikasi = $notifikasi->count();

        return view('ormawa.index', compact('daftarOrmawa', 'notifikasi', 'jumlahNotifikasi'));
    }


    public function create()
    {
        $notifikasi = Proker::where(function ($kueri) {
            $kueri->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();

        $jumlahNotifikasi = $notifikasi->count();

        return view('ormawa.buat', compact('notifikasi', 'jumlahNotifikasi'));
    }

    public function store(Request $permintaan)
    {
        try {
            $permintaan->validate([
                'name' => 'required|string|max:255',
                'description' => 'required',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'photo_structure' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $dataOrmawa = $permintaan->all();

            if ($permintaan->hasFile('logo')) {
                $dataOrmawa['logo'] = $permintaan->file('logo')->store('logos', 'public');
            }

            if ($permintaan->hasFile('photo_structure')) {
                $dataOrmawa['photo_structure'] = $permintaan->file('photo_structure')->store('photos', 'public');
            }

            Clubs::create($dataOrmawa);

            return redirect()->route('ormawa.index')->with('success', 'Ormawa berhasil ditambahkan.');
        } catch (\Exception $kesalahan) {
            return redirect()->back()->with('error', 'Gagal menambahkan Ormawa: ' . $kesalahan->getMessage());
        }
    }

    public function edit(Clubs $ormawa)
    {
        $notifikasi = Proker::where(function ($kueri) {
            $kueri->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();

        $jumlahNotifikasi = $notifikasi->count();

        return view('ormawa.edit', compact('ormawa', 'notifikasi', 'jumlahNotifikasi'));
    }

    public function update(Request $permintaan, Clubs $ormawa)
    {
        try {
            $permintaan->validate([
                'name' => 'required|string|max:255',
                'description' => 'required',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'photo_structure' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $permintaan->all();

            if ($permintaan->hasFile('logo')) {
                if ($ormawa->logo) {
                    Storage::disk('public')->delete($ormawa->logo);
                }
                $data['logo'] = $permintaan->file('logo')->store('logos', 'public');
            }

            if ($permintaan->hasFile('photo_structure')) {
                if ($ormawa->photo_structure) {
                    Storage::disk('public')->delete($ormawa->photo_structure);
                }
                $data['photo_structure'] = $permintaan->file('photo_structure')->store('photos', 'public');
            }

            $ormawa->update($data);

            return redirect()->route('ormawa.index')->with('success', 'Data ormawa berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui ormawa: ' . $e->getMessage());
        }
    }


    public function editOrmawa(Clubs $ormawa)
    {
        return view('ormawa.editOrmawa', compact('ormawa'));
    }

    public function updateOrmawa(Request $permintaan, Clubs $ormawa)
    {
        try {
            $permintaan->validate([
                'nama' => 'required|string|max:255',
                'deskripsi' => 'required',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'foto_struktur' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $permintaan->all();

            if ($permintaan->hasFile('logo')) {
                if ($ormawa->logo) {
                    Storage::disk('public')->delete($ormawa->logo);
                }
                $data['logo'] = $permintaan->file('logo')->store('logos', 'public');
            }

            if ($permintaan->hasFile('foto_struktur')) {
                if ($ormawa->foto_struktur) {
                    Storage::disk('public')->delete($ormawa->foto_struktur);
                }
                $data['foto_struktur'] = $permintaan->file('foto_struktur')->store('photos', 'public');
            }

            $ormawa->update($data);

            return redirect()->route('profile')->with('success', 'Data ormawa berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data ormawa: ' . $e->getMessage());
        }
    }

    public function destroy(Clubs $club)
    {
        $club->delete();
        return redirect()->route('ormawa.index')->with('success', 'Club deleted successfully.');
    }

    public function showProfile($id)
    {
        $ormawa = Clubs::findOrFail($id);
        $daftarProker = Proker::where('id_club', $id)->get();
        $daftarKegiatan = Activity::where('id_club', $id)->get();

        return view('ormawa.profile', compact('ormawa', 'daftarProker', 'daftarKegiatan'));
    }
}
