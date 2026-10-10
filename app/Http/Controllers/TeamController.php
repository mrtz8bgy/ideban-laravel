<?php

namespace App\Http\Controllers;

use App\Models\Resume;

class TeamController extends Controller
{
    public function index()
    {
        $people = Resume::published()->with('items')->orderBy('sort_order')->orderBy('id')->get();

        return view('team.index', compact('people'));
    }

    public function show(Resume $resume)
    {
        abort_unless($resume->is_published, 404);
        $resume->load('items');

        return view('team.show', compact('resume'));
    }
}
