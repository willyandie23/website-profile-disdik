<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        // Mengambil semua organisasi beserta bidang yang terkait
        $organizations = Organization::with('field')->get();

        $kepala = $organizations->where('level', 1);
        $sekretaris = $organizations->where('level', 2);
        $kabid = $organizations->where('level', 3);
        $staff = $organizations->where('level', 4);
        $kanwil = $organizations->where('level', 5);
        
        // Mengirimkan data yang sudah dikelompokkan ke frontend
        return view('frontend.organization.index', compact('kepala', 'sekretaris', 'kabid', 'staff', 'kanwil'))->with([
            'submenu' => false,
            'navbar'  => true,
            'footer'  => true,
        ]);
    }
}
