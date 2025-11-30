<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact.index', [
            'submenu'           => false,
            'navbar'            => true,
            'footer'            => true,
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Simpan data kontak ke database
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // Redirect dengan session untuk menampilkan notifikasi
        return redirect()->route('hubungi.index')->with('success', 'Pesan Anda telah dikirim!');
    }
}
