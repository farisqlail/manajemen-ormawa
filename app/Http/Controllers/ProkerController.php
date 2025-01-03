<?php

namespace App\Http\Controllers;

use App\Models\Clubs;
use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProkerController extends Controller
{
    public function index()
    {
        $prokers = Proker::all();
        return view('prokers.index', compact('prokers'));
    }

    public function create()
    {
        $clubs = Clubs::all();
        return view('prokers.create', compact('clubs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_club' => 'required|integer',
            'name' => 'required|string',
            'document_lpj' => 'required|file|mimes:pdf,doc,docx',
            'budget' => 'required|integer',
            'target_event' => 'required|date',
        ]);

        $filePath = $request->file('document_lpj')->store('lpj_documents', 'public');

        Proker::create([
            'id_club' => $request->id_club,
            'name' => $request->name,
            'document_lpj' => $filePath,
            'budget' => $request->budget,
            'target_event' => $request->target_event,
            'status' => "pending",
        ]);

        return redirect()->route('prokers.index')->with('success', 'Proker created successfully.');
    }

    public function show(Proker $proker)
    {
        return view('prokers.show', compact('proker'));
    }

    public function edit(Proker $proker)
    {
        $clubs = Clubs::all();
        return view('prokers.edit', compact('proker', 'clubs'));
    }

    public function update(Request $request, Proker $proker)
    {
        $request->validate([
            'id_club' => 'required|integer',
            'name' => 'required|string',
            'document_lpj' => 'nullable|file|mimes:pdf,doc,docx',
            'budget' => 'required|integer',
            'target_event' => 'required|date',
        ]);

        if ($request->hasFile('document_lpj')) {
            if ($proker->document_lpj) {
                Storage::disk('public')->delete($proker->document_lpj);
            }
            $filePath = $request->file('document_lpj')->store('lpj_documents', 'public');
            $proker->document_lpj = $filePath;
        }

        $proker->update([
            'id_club' => $request->id_club,
            'name' => $request->name,
            'budget' => $request->budget,
            'target_event' => $request->target_event,
            'status' => "pending",
        ]);

        return redirect()->route('prokers.index')->with('success', 'Proker updated successfully.');
    }

    public function destroy(Proker $proker)
    {
        $proker->delete();
        return redirect()->route('prokers.index')->with('success', 'Proker deleted successfully.');
    }
}
