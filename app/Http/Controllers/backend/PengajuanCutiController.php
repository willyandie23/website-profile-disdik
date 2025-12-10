<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use App\Http\Controllers\Controller;

class PengajuanCutiController extends Controller
{
    public function index()
    {
        return view('backend.pengajuan_cuti.index');
    }

    public function track($id)
    {
        // Ambil data lengkap + relasi
        $pengajuan = PengajuanCuti::with([
            'jenisCuti',
            'riwayatStatus' => fn($q) => $q->orderBy('tanggal', 'asc'),
            'berkas'
        ])
            ->withTrashed() // kalau pakai softdelete & mau lihat yang sudah dihapus
            ->findOrFail($id);
        // dd($pengajuan);

        return view('backend.pengajuan_cuti.track', compact('pengajuan'));
    }
}
