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
        $notification = Proker::where(function ($query) {
            $query->whereNull('status_laporan')
                ->orWhere('status_laporan', 'pending');
        })
            ->with('club')
            ->where('status', 'pending')
            ->get();
        $notificationCount = $notification->count();

        return view('prokers.index', compact('prokers', 'notification', 'notificationCount'));
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
                'proposal_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
                'budget' => 'required|integer',
                'target_event' => 'required|date',
            ]);

            $filePath = $request->file('proposal_file')->store('proposals', 'public');

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
        $club = Clubs::where('id', $proker->id_club)->first();
        $logoPath = storage_path('app/public/' . $club->logo);

        if (file_exists($logoPath)) {
            $imageData = base64_encode(file_get_contents($logoPath));
            $clubLogo = "data:image/png;base64," . $imageData;
        } else {
            $clubLogo = 'https://via.placeholder.com/80';
        }

        $html = "<html>
        <head>
            <meta charset='utf-8'>
            <title>Proposal Proker</title>
            <style>
                @page {
                    size: A4;
                    margin-top: 3cm;
                    margin-bottom: 2cm;
                    margin-left: 3cm;
                    margin-right: 2cm;
                }
                body {
                    font-family: Arial, sans-serif;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .header {
                    text-align: center;
                    font-size: 14px;
                    font-weight: bold;
                    border-bottom: 1px solid #000;
                    padding-bottom: 5px;
                    margin-bottom: 20px;
                }
                .page-break {
                    page-break-before: always;
                }
            </style>
        </head>
        <body>
            <table>
                <thead>
                    <tr>
                        <td class='header'><img src='https://upload.wikimedia.org/wikipedia/id/2/23/UNUSA.png' width='80' height='80'></td>
                        <td class='header' align='center'>
                            <span>KOPERASI MAHASISWA</span><br>
                            <span>UNIVERSITAS NAHDLATUL ULAMA SURABAYA</span><br>
                            <span>Badan Hukum No: 003412/BH/M.KUKM.12/02/2017</span>
                        </td>
                        <td class='header'><img src='{$clubLogo}' width='80' height='80'></td>
                    </tr>
                </thead>
            </table>

            <div>{$proker->proposal}</div>
        </body>
        </html>";

        $fileName = 'Proposal_' . str_replace(' ', '_', $proker->name) . '.doc';
        Storage::disk('local')->put($fileName, $html);

        return response()->download(storage_path("app/$fileName"))->deleteFileAfterSend(true);
    }

    public function exportLaporanToWord($id)
    {
        $proker = Proker::findOrFail($id);
        $club = Clubs::where('id', $proker->id_club)->first();
        $logoPath = storage_path('app/public/' . $club->logo);

        if (file_exists($logoPath)) {
            $imageData = base64_encode(file_get_contents($logoPath));
            $clubLogo = "data:image/png;base64," . $imageData;
        } else {
            $clubLogo = 'https://via.placeholder.com/80';
        }

        $html = "<html>
        <head>
            <meta charset='utf-8'>
            <title>Laporan Proker</title>
            <style>
                @page {
                    size: A4;
                    margin-top: 3cm;
                    margin-bottom: 2cm;
                    margin-left: 3cm;
                    margin-right: 2cm;
                }
                body {
                    font-family: Arial, sans-serif;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .header {
                    text-align: center;
                    font-size: 14px;
                    font-weight: bold;
                    border-bottom: 1px solid #000;
                    padding-bottom: 5px;
                    margin-bottom: 20px;
                }
                .page-break {
                    page-break-before: always;
                }
            </style>
        </head>
        <body>
            <table>
                <thead>
                    <tr>
                        <td class='header'><img src='https://upload.wikimedia.org/wikipedia/id/2/23/UNUSA.png' width='80' height='80'></td>
                        <td class='header' align='center'>
                            <span>KOPERASI MAHASISWA</span><br>
                            <span>UNIVERSITAS NAHDLATUL ULAMA SURABAYA</span><br>
                            <span>Badan Hukum No: 003412/BH/M.KUKM.12/02/2017</span>
                        </td>
                        <td class='header'><img src='{$clubLogo}' width='80' height='80'></td>
                    </tr>
                </thead>
            </table>

            <div>{$proker->laporan}</div>
        </body>
        </html>";

        $fileName = 'Laporan_' . str_replace(' ', '_', $proker->name) . '.doc';
        Storage::disk('local')->put($fileName, $html);

        // Download file
        return response()->download(storage_path("app/$fileName"))->deleteFileAfterSend(true);
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
