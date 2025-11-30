<?php

namespace App\Http\Controllers\Backend;

use App\Models\News;
use App\Models\Field;
use App\Models\Contact;
use App\Models\Download;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil jumlah Organization
        $organizationCount = Organization::count();

        // Mengambil jumlah News
        $newsCount = News::count();

        // Mengambil jumlah Field
        $fieldCount = Field::count();

        // Mengambil jumlah Downloads
        $downloadCount = Download::sum('total_download');

        // Mengambil dua pesan terbaru dari Contact
        $latestContacts = Contact::latest()->take(2)->get();

        return view('backend.dashboard.index', compact(
            'organizationCount', 
            'newsCount', 
            'fieldCount', 
            'downloadCount', 
            'latestContacts'
        ));
    }
}
