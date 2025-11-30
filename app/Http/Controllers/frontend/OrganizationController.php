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

        // Mengelompokkan organisasi berdasarkan posisi mereka
        $head_of_department = $organizations->whereIn('position',
            [
                'Kepala Dinas', 
                'KEPALA DINAS', 
                'Plt. Kepala Dinas', 
                'Plt. KEPALA DINAS'
            ]
        );
        $secretariat = $organizations->whereIn('position', 
            [
                'Plt. SEKRETARIS',
                'SEKRETARIS',
                'Plt. Sekretaris',
                'Sekretaris'
            ]
        );

        // Mengelompokkan berdasarkan bidang
        $cultural_department = $organizations->where('field_id', 3);
        $tourism_department = $organizations->where('field_id', 4);
        $youth_department = $organizations->where('field_id', 5);
        $sports_department = $organizations->where('field_id', 6);
        
        // Sekretariat tambahan posisi
        $secretariat_sub = $organizations->where('field_id', 2);

        // Mengirimkan data yang sudah dikelompokkan ke frontend
        return view('frontend.organization.index', [
            'head_of_department' => $head_of_department,
            'secretariat' => $secretariat,
            'secretariat_sub' => $secretariat_sub,
            'cultural_department' => $cultural_department,
            'tourism_department' => $tourism_department,
            'youth_department' => $youth_department,
            'sports_department' => $sports_department,
        ])->with([
            'submenu' => false,
            'navbar'  => true,
            'footer'  => true,
        ]);
    }
}
