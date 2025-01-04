<?php

namespace App\Http\Controllers;

use App\Models\Clubs;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $id_club = Auth::user()->id_club;
        $club = Clubs::find($id_club);

        if (!$club) {
            return redirect()->route('home')->with('error', 'Club not found.');
        }

        return view('profile.index', compact('club'));
    }
}
