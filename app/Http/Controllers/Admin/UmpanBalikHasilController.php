<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class UmpanBalikHasilController extends Controller
{
    public function index()
    {
        return view('admin.umpan-balik-hasil.index');
    }
}
