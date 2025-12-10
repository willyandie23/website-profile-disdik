<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PengajuanCuti;
use App\Models\BerkasPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CutiController extends Controller
{
    public function track(Request $request)
    {
        $kode = $request->query('kode');
        $nip = $request->query('nip');

        if ($kode) {
            $pengajuan = PengajuanCuti::with(['jenisCuti', 'berkas', 'riwayatStatus'])
                ->where('kode_pengajuan', $kode)
                ->first();

            if (!$pengajuan) {
                return '<div class="text-center py-5 text-danger"><h5>Kode tidak ditemukan</h5></div>';
            }

            return view('frontend.cuti.partials.result', compact('pengajuan'));
        }

        if ($nip) {
            $list = PengajuanCuti::with('jenisCuti')
                ->where('nip', $nip)
                ->orderBy('tanggal_pengajuan', 'desc')
                ->get();

            if ($list->isEmpty()) {
                return '<div class="text-center py-5 text-danger"><h5>Tidak ada pengajuan dengan NIP ini</h5></div>';
            }

            return view('frontend.cuti.partials.list-by-nip', compact('list'));
        }

        return '<div class="text-center py-5 text-muted"><h5>Masukkan kode atau NIP</h5></div>';
    }

    public function getJenisCuti($id)
    {
        $jc = \App\Models\JenisCuti::select('butuh_surat_dokter', 'butuh_lampiran_tambahan')->findOrFail($id);
        return response()->json($jc);
    }

    public function store(Request $request)
    {
        try {
            // Validasi data pribadi & cuti (selalu wajib)
            $request->validate([
                'nip' => 'nullable|string|max:20',
                'nama_lengkap' => 'required|string|max:255',
                'tempat_lahir' => 'nullable|string|max:100',
                'tanggal_lahir' => 'nullable|date',
                'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
                'pangkat_golongan' => 'nullable|string|max:50',
                'jabatan' => 'required|string|max:255',
                'unit_kerja' => 'required|string|max:255',
                'nomor_hp' => 'required|string|max:20',
                'alamat' => 'nullable|string',

                'jenis_cuti_id' => 'required|exists:jenis_cuti,id',
                'tanggal_mulai' => 'required|date|after_or_equal:today',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'alasan_cuti' => 'required|string',
                'alamat_selama_cuti' => 'required|string|max:255',
                'kontak_selama_cuti' => 'required|string|max:20',

                // Berkas umum (selalu boleh, tapi tidak wajib)
                'berkas' => 'nullable|array|max:10',
                'berkas.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            ]);

            // Ambil info jenis cuti untuk validasi dinamis
            $jenisCuti = \App\Models\JenisCuti::findOrFail($request->jenis_cuti_id);

            // Validasi dinamis untuk surat dokter & lampiran tambahan
            $dynamicRules = [];

            if ($jenisCuti->butuh_surat_dokter) {
                $dynamicRules['surat_dokter'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:10240';
            }
            if ($jenisCuti->butuh_lampiran_tambahan) {
                $dynamicRules['lampiran_tambahan'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:10240';
            }

            $request->validate($dynamicRules);

            // Hitung jumlah hari
            $mulai = \Carbon\Carbon::parse($request->tanggal_mulai);
            $selesai = \Carbon\Carbon::parse($request->tanggal_selesai);
            $jumlahHari = $mulai->diffInDays($selesai) + 1;

            // Generate kode random unik
            do {
                $randomNumber = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $kode = 'CT-' . date('Y') . '-' . $randomNumber;
            } while (PengajuanCuti::withTrashed()->where('kode_pengajuan', $kode)->exists());

            // Simpan pengajuan
            $pengajuan = PengajuanCuti::create([
                'kode_pengajuan' => $kode,
                'nip' => $request->nip ?? null,
                'nama_lengkap' => $request->nama_lengkap,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'pangkat_golongan' => $request->pangkat_golongan,
                'jabatan' => $request->jabatan,
                'unit_kerja' => $request->unit_kerja,
                'nomor_hp' => $request->nomor_hp,
                'alamat' => $request->alamat,

                'jenis_cuti_id' => $request->jenis_cuti_id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'jumlah_hari' => $jumlahHari,
                'alasan_cuti' => $request->alasan_cuti,
                'alamat_selama_cuti' => $request->alamat_selama_cuti,
                'kontak_selama_cuti' => $request->kontak_selama_cuti,

                'status' => 'diajukan',
                'level_approval' => 'tu',
                'tanggal_pengajuan' => now(),
            ]);

            // Riwayat status otomatis (draft → diajukan)
            DB::table('riwayat_status')->insert([
                'pengajuan_cuti_id' => $pengajuan->id,
                'status_lama' => 'draft',
                'status_baru' => 'diajukan',
                'catatan' => 'Pengajuan cuti diajukan oleh pemohon melalui form publik',
                'oleh' => $request->nama_lengkap . ($request->nip ? ' (NIP: ' . $request->nip . ')' : ''),
                'tanggal' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 1. Upload berkas umum (opsional)
            if ($request->hasFile('berkas')) {
                foreach ($request->file('berkas') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('cuti/berkas', $filename, 'public');

                    BerkasPengajuan::create([
                        'pengajuan_cuti_id' => $pengajuan->id,
                        'tipe_berkas' => 'lampiran_cuti',
                        'nama_asli' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime_type' => $file->getMimeType(),
                        'ukuran' => $file->getSize(),
                    ]);
                }
            }

            // 2. Upload Surat Dokter (jika wajib)
            if ($request->hasFile('surat_dokter')) {
                $file = $request->file('surat_dokter');
                $filename = 'surat_dokter_' . time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('cuti/berkas', $filename, 'public');

                BerkasPengajuan::create([
                    'pengajuan_cuti_id' => $pengajuan->id,
                    'tipe_berkas' => 'surat_dokter',
                    'nama_asli' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'ukuran' => $file->getSize(),
                ]);
            }

            // 3. Upload Lampiran Tambahan (jika wajib)
            if ($request->hasFile('lampiran_tambahan')) {
                $file = $request->file('lampiran_tambahan');
                $filename = 'tambahan_' . time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('cuti/berkas', $filename, 'public');

                BerkasPengajuan::create([
                    'pengajuan_cuti_id' => $pengajuan->id,
                    'tipe_berkas' => 'lampiran_tambahan',
                    'nama_asli' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'ukuran' => $file->getSize(),
                ]);
            }

            return response()->json([
                'success' => true,
                'kode' => $kode,
                'message' => 'Pengajuan cuti berhasil dikirim!'
            ]);

        } catch (\Exception $e) {
            Log::error('Cuti Error: ' . $e->getMessage() . ' | Line: ' . $e->getLine());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function submitRevisi(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required|exists:pengajuan_cuti,id',
        ]);

        $pengajuan = PengajuanCuti::findOrFail($request->pengajuan_id);

        if ($pengajuan->status_revisi !== 'perlu_revisi') {
            return response()->json(['success' => false, 'message' => 'Tidak ada revisi yang diminta']);
        }

        $revisiTypes = json_decode($pengajuan->tipe_berkas_revisi, true) ?: [];

        foreach ($revisiTypes as $type) {
            $inputName = "revisi_$type";
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);

                // Hapus file lama
                $old = $pengajuan->berkas()->where('tipe_berkas', $type)->first();
                if ($old && Storage::disk('public')->exists($old->path)) {
                    Storage::disk('public')->delete($old->path);
                    $old->delete();
                }

                // Simpan file baru
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('cuti/berkas', $filename, 'public');

                BerkasPengajuan::create([
                    'pengajuan_cuti_id' => $pengajuan->id,
                    'tipe_berkas' => $type,
                    'nama_asli' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'ukuran' => $file->getSize(),
                ]);
            }
        }

        // Update status revisi
        $pengajuan->update([
            'status_revisi' => 'sudah_direvisi',
            'tipe_berkas_revisi' => null,
        ]);

        // Catat riwayat
        DB::table('riwayat_status')->insert([
            'pengajuan_cuti_id' => $pengajuan->id,
            'status_lama' => $pengajuan->status,
            'status_baru' => $pengajuan->status,
            'catatan' => 'Pemohon telah mengunggah revisi berkas',
            'oleh' => $pengajuan->nama_lengkap . ' (Revisi)',
            'tanggal' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Revisi berhasil dikirim! Menunggu verifikasi ulang.'
        ]);
    }
}
