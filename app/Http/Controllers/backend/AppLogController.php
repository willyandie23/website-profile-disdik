<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('backend.logs.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('backend.logs.show', [
            'id' => $id
        ]);
    }

}
