<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use App\Http\Controllers\Controller;


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
            ->latest('tanggal_pengajuan');

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
}
