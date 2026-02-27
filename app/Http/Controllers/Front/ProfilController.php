<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ProfileContent;

class ProfilController extends Controller
{
    public function index()
    {
        $sejarah = ProfileContent::byType('sejarah')->active()->get();
        $milestones = ProfileContent::byType('milestone')->active()->get();
        $visions = \App\Models\ProfileVisionMission::byType('visi')->active()->get();
        $missions = \App\Models\ProfileVisionMission::byType('misi')->active()->get();

        return view('asesi.profil', compact('sejarah', 'milestones', 'visions', 'missions'));
    }
}
