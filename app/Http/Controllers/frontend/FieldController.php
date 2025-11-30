<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Field;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FieldController extends Controller
{
    public function show($id)
    {
        try {
            // Mencari bidang berdasarkan ID
            $field = Field::findOrFail($id);

            // Mengambil anggota yang terkait dengan bidang
            $organizations = Organization::where('field_id', $id)->get();

            // Mengembalikan view dengan data bidang dan anggota
            return view('frontend.field.show', compact('field', 'organizations'))->with([
                'submenu' => false,
                'navbar'  => true,
                'footer'  => true,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('/')->with('error', 'Bidang tidak ditemukan.');
        }
    }
}
