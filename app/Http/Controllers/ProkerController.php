<?php

namespace App\Http\Controllers;

use App\Models\Clubs;
use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ProkerController extends Controller
{
    public function index()
    {
        $idClub = Auth::user()->id_club;
        $prokers = Proker::where('id_club', $idClub)->get();

        return view('prokers.index', compact('prokers'));
    }

    public function create()
    {
        $clubs = Clubs::all();
        return view('prokers.create', compact('clubs'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_club' => 'required',
                'name' => 'required|string',
                'proposal' => 'required|string',
                'budget' => 'required|integer',
                'target_event' => 'required|date',
            ]);

            Proker::create([
                'id_club' => $request->id_club,
                'name' => $request->name,
                'proposal' => $request->proposal,
                'budget' => $request->budget,
                'target_event' => $request->target_event,
                'status' => "pending",
            ]);
            return redirect()->route('prokers.index')->with('success', 'Proker created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create Proker: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $proker = Proker::with('club')->findOrFail($id);

        return view('prokers.show', compact('proker'));
    }

    public function edit(Proker $proker)
    {
        $clubs = Clubs::all();
        return view('prokers.edit', compact('proker', 'clubs'));
    }

    public function update(Request $request, Proker $proker)
    {
        try {
            $request->validate([
                'id_club' => 'required|integer',
                'name' => 'required|string',
                'proposal' => 'required|string',
                'budget' => 'required|integer',
                'target_event' => 'required|date',
            ]);

            $proker->update([
                'id_club' => $request->id_club,
                'name' => $request->name,
                'budget' => $request->budget,
                'target_event' => $request->target_event,
                'laporan' => $request->laporan,
                'status_laporan' => $request->get('status_laporan'),
                'status' => $request->get('status'),
                'reason' => ''
            ]);

            return redirect()->route('prokers.index')->with('success', 'Proker updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update Proker: ' . $e->getMessage());
        }
    }

    public function destroy(Proker $proker)
    {
        $proker->delete();
        return redirect()->route('prokers.index')->with('success', 'Proker deleted successfully.');
    }

    public function downloadLPJ($id)
    {
        $proker = Proker::findOrFail($id);

        if (!$proker->document_lpj) {
            return redirect()->back()->with('error', 'Document not found.');
        }

        $clubName = $proker->club->name;
        $fileName = "{$proker->name}_{$clubName}." . pathinfo($proker->document_lpj, PATHINFO_EXTENSION);

        return response()->download(storage_path('app/public' . '/' . $proker->document_lpj), $fileName);
    }
    public function approveProker($id)
    {
        $proker = Proker::findOrFail($id);

        if (Auth::user()->role == 'pembina' && $proker->status == 'pending') {
            $proker->status = 'approved';
            $proker->reason = '';
        } elseif (Auth::user()->role == 'admin' && $proker->status == 'pembina') {
            $proker->status = 'approved';
            $proker->reason = '';
        }

        $proker->save();

        return redirect()->back()->with('success', 'Proker approved successfully.');
    }

    public function rejectProker(Request $request, $id)
    {
        $proker = Proker::findOrFail($id);
        if (Auth::user()->role == 'pembina' && $proker->status !== 'pembina' && $proker->status_laporan == '') {
            $proker->status = 'rejected';
            $proker->reason = $request->get('reason');
            $proker->save();

            return redirect()->back()->with('success', 'Proker rejected successfully.');
        } else {
            $proker->status_laporan = 'rejected';
            $proker->reason = $request->get('reason');
            $proker->save();

            return redirect()->back()->with('success', 'Proker rejected successfully.');
        }
    }
    public function approveProkerLaporan($id)
    {
        $proker = Proker::findOrFail($id);

        if (Auth::user()->role == 'pembina' && $proker->status_laporan == 'pending') {
            $proker->status_laporan = 'pembina';
            $proker->reason = '';
        } elseif (Auth::user()->role == 'admin' && $proker->status_laporan == 'pembina') {
            $proker->status_laporan = 'approved';
            $proker->reason = '';
        }

        $proker->save();

        return redirect()->back()->with('success', 'Proker approved successfully.');
    }

    public function rejectProkerLaporan(Request $request, $id)
    {
        $proker = Proker::findOrFail($id);
        $proker->status_laporan = 'rejected';
        $proker->reason = $request->get('reason');
        $proker->save();

        return redirect()->back()->with('success', 'Proker rejected successfully.');
    }

    public function exportProposalToWord($id)
    {
        $proker = Proker::findOrFail($id);

        $html = "<html>
        <head>
            <meta charset='utf-8'>
            <title>Proposal Proker</title>
        </head>
        <body>
            <h2 style='text-align: center;'>Proposal Proker</h2>
            <h3>Nama Proker: {$proker->name}</h3>
            <p><strong>Budget:</strong> Rp " . number_format($proker->budget, 0, ',', '.') . "</p>
            <p><strong>Target Event:</strong> {$proker->target_event}</p>
            <p><strong>Status:</strong> {$proker->status}</p>
            <hr>
            <h3>Deskripsi Proker</h3>
            {$proker->proposal}  <!-- Ambil langsung dari database -->
        </body>
    </html>";


        // Simpan sebagai file HTML dan ubah ekstensi ke .doc
        $fileName = 'Proposal_' . str_replace(' ', '_', $proker->name) . '.doc';
        Storage::disk('local')->put($fileName, $html);

        // Download file
        return response()->download(storage_path("app/$fileName"))->deleteFileAfterSend(true);
    }
    public function exportLaporanToWord($id)
    {
        $proker = Proker::findOrFail($id);

        $html = "<html>
        <head>
            <meta charset='utf-8'>
            <title>Laporan Proker</title>
        </head>
        <body>
            <h2 style='text-align: center;'>Laporan Proker</h2>
            <h3>Nama Proker: {$proker->name}</h3>
            <p><strong>Budget:</strong> Rp " . number_format($proker->budget, 0, ',', '.') . "</p>
            <p><strong>Target Event:</strong> {$proker->target_event}</p>
            <p><strong>Status:</strong> {$proker->status_laporan}</p>
            <hr>
            <h3>Deskripsi Proker</h3>
            {$proker->laporan}  <!-- Ambil langsung dari database -->
        </body>
    </html>";


        // Simpan sebagai file HTML dan ubah ekstensi ke .doc
        $fileName = 'Laporan_' . str_replace(' ', '_', $proker->name) . '.doc';
        Storage::disk('local')->put($fileName, $html);

        // Download file
        return response()->download(storage_path("app/$fileName"))->deleteFileAfterSend(true);
    }
}
