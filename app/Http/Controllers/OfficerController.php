<?php

namespace App\Http\Controllers;

use App\Models\Officer;

class OfficerController extends Controller
{
    public function index()
    {
        $bphOfficers = Officer::where('department_level', 'bph')->orderBy('sort_order')->get();
        $pemrogramanOfficers = Officer::where('department_level', 'pemrograman')->orderBy('sort_order')->get();
        $multimediaOfficers = Officer::where('department_level', 'multimedia')->orderBy('sort_order')->get();
        $iotOfficers = Officer::where('department_level', 'iot')->orderBy('sort_order')->get();
        $cyberOfficers = Officer::where('department_level', 'cyber')->orderBy('sort_order')->get();

        return view('officers.index', compact(
            'bphOfficers',
            'pemrogramanOfficers',
            'multimediaOfficers',
            'iotOfficers',
            'cyberOfficers'
        ));
    }
}
