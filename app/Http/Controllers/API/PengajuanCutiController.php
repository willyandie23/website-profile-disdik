<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use App\Models\RiwayatStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


/**
 * @OA\Tag(
 *     name="Pengajuan Cuti",
 *     description="API untuk pengelolaan pengajuan cuti pegawai"
 * )
 */
class PengajuanCutiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pengajuan-cuti",
     *     summary="Daftar semua pengajuan cuti",
     *     description="Mengembalikan seluruh data pengajuan cuti (tanpa paginasi). Bisa difilter berdasarkan status atau NIP pegawai.",
     *     operationId="pengajuanCutiIndex",
     *     tags={"Pengajuan Cuti"},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter status: draft, menunggu, disetujui, ditolak, dibatalkan",
     *         required=false,
     *         @OA\Schema(type="string", enum={"draft","menunggu","disetujui","ditolak","dibatalkan"})
     *     ),
     *     @OA\Parameter(
     *         name="nip",
     *         in="query",
     *         description="Filter berdasarkan NIP pegawai",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data pengajuan cuti",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data pengajuan cuti berhasil diambil"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PengajuanCutiResource")),
     *             @OA\Property(property="total", type="integer", example=42, description="Total data yang dikembalikan")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Parameter tidak valid"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index(Request $request)
    {
        $query = PengajuanCuti::with([
            'jenisCuti',
            'riwayatStatus' => fn($q) => $q->latest('tanggal')->limit(5)
        ])
            ->latest('updated_at');

        // Filter opsional
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('nip')) {
            $query->where('nip', $request->nip);
        }

        $data = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Data pengajuan cuti berhasil diambil',
            'data'    => $data,
            'total'   => $data->count(),
        ]);
    }

    public function teruskan($id)
    {
        try {
            $pengajuan = PengajuanCuti::findOrFail($id);
            $user = auth()->user();

            DB::beginTransaction();

            // ADMIN TU: Teruskan ke Kasubbag
            if ($user->hasRole('admin') && $pengajuan->level_approval === 'tu') {

                $pengajuan->update([
                    'status' => 'sedang_diproses',
                    'level_approval' => 'kasubbag',
                    'approved_by_tu' => $user->id,
                    'approved_at_tu' => now(),
                ]);

                $pengajuan->riwayatStatus()->create([
                    'status_lama' => $pengajuan->status,
                    'status_baru' => 'sedang_diproses',
                    'oleh' => $user->name,
                    'catatan' => 'Disetujui Admin TU, diteruskan ke Kasubbag',
                    'tanggal' => now()
                ]);

                $message = 'Pengajuan berhasil disetujui dan diteruskan ke Kasubbag';
            }

            // KASUBBAG: Setujui Final
            elseif ($user->hasRole('kassubag') && $pengajuan->level_approval === 'kasubbag') {


                $pengajuan->update([
                    'status' => 'disetujui',
                    'approved_by_kasubbag' => $user->id,
                    'approved_at_kasubbag' => now(),
                ]);

                $pengajuan->riwayatStatus()->create([
                    'status_lama' => 'sedang_diproses',
                    'status_baru' => 'disetujui',
                    'oleh' => $user->name,
                    'catatan' => 'Disetujui final oleh Kasubbag',
                    'tanggal' => now()
                ]);

                $message = 'Pengajuan berhasil disetujui.';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses atau pengajuan tidak dalam level yang tepat'
                ], 403);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================
    // 3. MINTA REVISI
    // ============================================
    public function mintaRevisi(Request $request, $id)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|min:10',
            'tipe_berkas_revisi' => 'required|array|min:1',
            'tipe_berkas_revisi.*' => 'in:lampiran_tambahan,surat_dokter,lampiran_cuti'
        ], [
            'tipe_berkas_revisi.required' => 'Pilih minimal satu jenis berkas yang harus direvisi.',
            'tipe_berkas_revisi.min' => 'Pilih minimal satu jenis berkas.',
        ]);

        try {
            $pengajuan = PengajuanCuti::findOrFail($id);
            $user = auth()->user();

            if (!$user->hasAnyRole(['admin', 'kassubag'])) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }

            DB::beginTransaction();

            $pengajuan->update([
                'status_revisi'       => 'perlu_revisi',
                'catatan_revisi'      => $request->catatan_revisi,
                'revisi_oleh'         => $user->name,
                'tipe_berkas_revisi'  => $request->tipe_berkas_revisi, // simpan array → jadi JSON otomatis
                'revisi_at'           => now(),
            ]);

            // Riwayat status
            $pengajuan->riwayatStatus()->create([
                'status_lama' => $pengajuan->getOriginal('status'),
                'status_baru' => $pengajuan->status,
                'oleh'        => $user->name,
                'catatan' => 'Diminta revisi berkas: ' . implode(', ', array_map(function ($v) {
                    return str_replace('_', ' ', $v);
                }, $request->tipe_berkas_revisi)),

                'tanggal'     => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permintaan revisi berhasil dikirim'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================
    // 4. CANCEL APPROVAL
    // ============================================
    public function cancelApproval($id)
    {
        try {
            $pengajuan = PengajuanCuti::findOrFail($id);
            $user = auth()->user();

            DB::beginTransaction();

            // ADMIN TU: Cancel persetujuan (dari kasubbag kembali ke TU)
            if ($user->hasRole('admin') && $pengajuan->level_approval === 'kasubbag') {

                $pengajuan->update([
                    'status' => 'sedang_diproses',
                    'level_approval' => 'tu',
                    'approved_by_tu' => null,
                    'approved_at_tu' => null,
                ]);

                $pengajuan->riwayatStatus()->create([
                    'status_lama' => 'sedang_diproses',
                    'status_baru' => 'sedang_diproses',
                    'oleh' => $user->name,
                    'catatan' => 'Admin TU membatalkan persetujuan, dikembalikan ke level TU',
                    'tanggal' => now()
                ]);

                $message = 'Persetujuan Admin TU berhasil dibatalkan.';
            }

            // KASUBBAG: Cancel keputusan (dari disetujui/ditolak kembali ke sedang_diproses)
            elseif ($user->hasRole('kassubag') && in_array($pengajuan->status, ['disetujui', 'ditolak'])) {

                $statusLama = $pengajuan->status;

                // Hapus PDF jika ada
                if ($pengajuan->final_pdf) {
                    Storage::delete($pengajuan->final_pdf);
                }

                $pengajuan->update([
                    'status' => 'sedang_diproses',
                    'level_approval' => 'kasubbag',
                    'approved_by_kasubbag' => null,
                    'approved_at_kasubbag' => null,
                    'rejected_by' => null,
                    'rejected_at' => null,
                    'final_pdf' => null,
                ]);

                $pengajuan->riwayatStatus()->create([
                    'status_lama' => $statusLama,
                    'status_baru' => 'sedang_diproses',
                    'oleh' => $user->name,
                    'catatan' => 'Kasubbag membatalkan keputusan, dikembalikan ke proses review',
                    'tanggal' => now()
                ]);

                $message = 'Keputusan Kasubbag berhasil dibatalkan.';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk membatalkan aksi ini'
                ], 403);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================
    // 5. BATALKAN (oleh User sendiri)
    // ============================================
    public function batalkan($id)
    {
        try {
            $pengajuan = PengajuanCuti::findOrFail($id);
            $user = auth()->user();

            // Validasi: hanya pembuat yang bisa membatalkan
            if ($pengajuan->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat membatalkan pengajuan orang lain'
                ], 403);
            }

            // Tidak bisa dibatalkan jika sudah disetujui
            if (in_array($pengajuan->status, ['disetujui', 'selesai'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan yang sudah disetujui tidak dapat dibatalkan'
                ], 400);
            }

            DB::beginTransaction();

            $statusLama = $pengajuan->status;

            $pengajuan->update([
                'status' => 'dibatalkan',
                'cancelled_at' => now(),
            ]);

            $pengajuan->riwayatStatus()->create([
                'status_lama' => $statusLama,
                'status_baru' => 'dibatalkan',
                'oleh' => $user->name,
                'catatan' => 'Pengajuan dibatalkan oleh pembuat',
                'tanggal' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dibatalkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function tolak($id)
    {
        try {
            $pengajuan = PengajuanCuti::findOrFail($id);
            $user = auth()->user();

            // Validasi akses
            if (!$user->hasAnyRole(['admin', 'kassubag'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses'
                ], 403);
            }

            DB::beginTransaction();

            $statusLama = $pengajuan->status;

            $pengajuan->update([
                'status' => 'ditolak',
                'rejected_by' => $user->id,
                'rejected_at' => now(),
            ]);

            $pengajuan->riwayatStatus()->create([
                'status_lama' => $statusLama,
                'status_baru' => 'ditolak',
                'oleh' => $user->name,
                'catatan' => 'Pengajuan ditolak oleh ' . ($user->hasRole('admin') ? 'Admin TU' : 'Kasubbag'),
                'tanggal' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadFinalPdf(Request $request, $id)
    {
        try {
            $pengajuan = PengajuanCuti::findOrFail($id);

            // Validasi role admin
            if (!auth()->user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Hanya admin yang dapat upload surat final.'
                ], 403);
            }

            // Validasi kondisi pengajuan
            if (
                !$pengajuan->approved_by_tu || !$pengajuan->approved_at_tu ||
                !$pengajuan->approved_by_kasubbag || !$pengajuan->approved_at_kasubbag
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan belum disetujui oleh semua pihak.'
                ], 400);
            }

            $request->validate([
                'nomor_surat' => 'required|string|max:100',
                'final_pdf' => 'nullable|file|mimes:pdf|max:5120' // 5MB
            ]);

            // Update nomor surat
            $pengajuan->nomor_surat = $request->nomor_surat;

            // Upload file jika ada
            if ($request->hasFile('final_pdf')) {
                // Hapus file lama jika ada
                if ($pengajuan->final_pdf && Storage::exists('public/' . $pengajuan->final_pdf)) {
                    Storage::delete('public/' . $pengajuan->final_pdf);
                }

                // Upload file baru
                $file = $request->file('final_pdf');
                $filename = 'surat_cuti_' . $pengajuan->kode_pengajuan . '_' . time() . '.pdf';
                $path = $file->storeAs('surat_cuti', $filename, 'public');

                $pengajuan->final_pdf = $path;
            }

            $pengajuan->save();

            // Tambah riwayat status
            RiwayatStatus::create([
                'pengajuan_cuti_id' => $pengajuan->id,
                'status_lama' => $pengajuan->status,
                'status_baru' => $pengajuan->status,
                'tanggal' => now(),
                'oleh' => auth()->user()->name,
                'catatan' => 'Surat cuti final telah diupload dengan nomor: ' . $request->nomor_surat
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Surat cuti final berhasil diupload!',
                'data' => [
                    'nomor_surat' => $pengajuan->nomor_surat,
                    'final_pdf' => $pengajuan->final_pdf
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
