<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index () {
        $contacts = Contact::orderBy('id', 'desc')->get();

        return view('backend.contact.index', compact('contacts'));
    }

    public function show($id)
    {
        // Ambil data kontak berdasarkan ID
        $contact = Contact::findOrFail($id);
        
        // Kirim data ke view
        return view('backend.contact.show', compact('contact'));
    }
}
