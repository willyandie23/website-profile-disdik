<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GaleryController extends Controller
{
    public function index () {
        return view('frontend.galery.index', [
            'submenu'           => false,
            'navbar'            => true,
            'footer'            => true,
        ]);
    }
}
