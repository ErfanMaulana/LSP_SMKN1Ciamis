<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Kontak;

class KontakController extends Controller
{
    /**
     * Display kontak page
     */
    public function index()
    {
        $kontak = Kontak::getKontak();
        return view('front.kontak.index', compact('kontak'));
    }
}
