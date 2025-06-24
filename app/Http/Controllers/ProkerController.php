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
    public function index(Request $request)
    {
        $idClub = Auth::user()->id_club;
        $search = $request->input('search');

        $query = Proker::where('id_club', $idClub);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $prokers = $query->paginate(10)->appends(['search' => $search]);

        $notification = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();

        $notificationCount = $notification->count();

        return view('prokers.index', compact('prokers', 'notification', 'notificationCount', 'search'));
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
                'proposal_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'budget' => 'required|integer',
                'target_event' => 'required|date',
            ]);

            $filePath = null;
            if ($request->hasFile('proposal_file')) {
                $filePath = $request->file('proposal_file')->store('proposals', 'public');
            }

            Proker::create([
                'id_club' => $request->id_club,
                'name' => $request->name,
                'proposal' => $filePath,
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
                'budget' => 'required|integer',
                'target_event' => 'required|date',
                'proposal_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'laporan_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ]);

            $dataToUpdate = [
                'id_club' => $request->id_club,
                'name' => $request->name,
                'budget' => $request->budget,
                'target_event' => $request->target_event,
                'status_laporan' => $request->get('status_laporan'),
                'status' => $request->get('status'),
                'reason' => '',
            ];

            if ($request->hasFile('proposal_file')) {
                if ($proker->proposal && Storage::disk('public')->exists($proker->proposal)) {
                    Storage::disk('public')->delete($proker->proposal);
                }
                $proposalPath = $request->file('proposal_file')->store('proposals', 'public');
                $dataToUpdate['proposal'] = $proposalPath;
            }

            if ($request->hasFile('laporan_file')) {
                if ($proker->laporan && Storage::disk('public')->exists($proker->laporan)) {
                    Storage::disk('public')->delete($proker->laporan);
                }
                $laporanPath = $request->file('laporan_file')->store('laporan', 'public');
                $dataToUpdate['laporan'] = $laporanPath;
            }

            $proker->update($dataToUpdate);

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

        // if (Auth::user()->role == 'pembina' && $proker->status == 'pending') {
        //     $proker->status = 'pembina';
        //     $proker->reason = '';
        // } else
        if (Auth::user()->role == 'admin') {
            $proker->status = 'approved';
            $proker->reason = '';
        }

        $proker->save();

        return redirect()->back()->with('success', 'Proker approved successfully.');
    }

    public function rejectProker(Request $request, $id)
    {
        $proker = Proker::findOrFail($id);

        $proker->status_laporan = 'rejected';
        $proker->reason = $request->get('reason');
        $proker->save();

        return redirect()->back()->with('success', 'Proker rejected successfully.');
    }
    public function approveProkerLaporan($id)
    {
        $proker = Proker::findOrFail($id);

        if (Auth::user()->role == 'admin' && $proker->status_laporan == 'pending') {
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

        if (!$proker->proposal || !Storage::disk('public')->exists($proker->proposal)) {
            return redirect()->back()->with('error', 'File proposal tidak ditemukan.');
        }

        $fileContent = Storage::disk('public')->get($proker->proposal);
        $extension = pathinfo($proker->proposal, PATHINFO_EXTENSION);
        $filename = 'Proposal_' . str_replace(' ', '_', $proker->name) . '.' . $extension;

        return response()->streamDownload(function () use ($fileContent) {
            echo $fileContent;
        }, $filename);
    }

    public function exportLaporanToWord($id)
    {
        $proker = Proker::findOrFail($id);

        if (!$proker->laporan || !Storage::disk('public')->exists($proker->laporan)) {
            return redirect()->back()->with('error', 'File laporan tidak ditemukan.');
        }

        $fileContent = Storage::disk('public')->get($proker->laporan);
        $extension = pathinfo($proker->laporan, PATHINFO_EXTENSION);
        $filename = 'Laporan_' . str_replace(' ', '_', $proker->name) . '.' . $extension;

        return response()->streamDownload(function () use ($fileContent) {
            echo $fileContent;
        }, $filename);
    }

    public function uploadPdf(Request $request, $id)
    {
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:2048'
        ]);

        $proker = Proker::findOrFail($id);

        if ($request->hasFile('pdf_file')) {
            $path = $request->file('pdf_file')->store('pdf_files', 'public');
            $proker->pdf_file = $path;
            $proker->save();
        }

        return redirect()->back()->with('success', 'File PDF berhasil diunggah.');
    }
}
