<?php

namespace App\Http\Controllers\Backend;

use App\Models\JenisCuti;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JenisCutiController extends Controller
{
    public function index()
    {
        return view('backend.jenisCuti.index');
    }

    public function create()
    {
        return view('backend.jenisCuti.create');
    }

    public function edit($id)
    {
        $jenis = JenisCuti::findOrFail($id);
        return view('backend.jenisCuti.edit', compact('id', 'jenis'));
    }
}
