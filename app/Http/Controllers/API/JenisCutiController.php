<?php

namespace App\Http\Controllers\API;

use App\Models\JenisCuti;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;

class JenisCutiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/jenis-cuti",
     *     tags={"Jenis Cuti"},
     *     summary="List seluruh jenis cuti",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mendapatkan data"
     *     )
     * )
     */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => JenisCuti::all()
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/jenis-cuti",
     *     tags={"Jenis Cuti"},
     *     summary="Tambah jenis cuti",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="nama", type="string"),
     *              @OA\Property(property="maks_hari", type="integer"),
     *              @OA\Property(property="butuh_surat_dokter", type="boolean"),
     *              @OA\Property(property="butuh_lampiran_tambahan", type="boolean"),
     *              @OA\Property(property="keterangan", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Data berhasil dibuat"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'maks_hari' => 'nullable|integer',
            'butuh_surat_dokter' => 'boolean',
            'butuh_lampiran_tambahan' => 'boolean',
            'keterangan' => 'nullable|string'
        ]);

        $jenis = JenisCuti::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $jenis
        ], 201);
    }


    /**
     * @OA\Get(
     *     path="/api/jenis-cuti/{id}",
     *     tags={"Jenis Cuti"},
     *     summary="Detail jenis cuti",
     *     @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          description="ID jenis cuti"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mendapatkan data"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data tidak ditemukan"
     *     )
     * )
     */
    public function show($id)
    {
        $jenis = JenisCuti::find($id);
        if (!$jenis) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $jenis
        ], 200);
    }


    /**
     * @OA\Put(
     *     path="/api/jenis-cuti/{id}",
     *     tags={"Jenis Cuti"},
     *     summary="Update jenis cuti",
     *     @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          description="ID jenis cuti"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="nama", type="string"),
     *              @OA\Property(property="maks_hari", type="integer"),
     *              @OA\Property(property="butuh_surat_dokter", type="boolean"),
     *              @OA\Property(property="butuh_lampiran_tambahan", type="boolean"),
     *              @OA\Property(property="keterangan", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengupdate data"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $jenis = JenisCuti::find($id);
        if (!$jenis) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ditemukan'], 404);
        }

        $request->validate([
            'nama' => 'nullable|string|max:255',
            'maks_hari' => 'nullable|integer',
            'butuh_surat_dokter' => 'boolean',
            'butuh_lampiran_tambahan' => 'boolean',
            'keterangan' => 'nullable|string'
        ]);

        $jenis->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $jenis
        ], 200);
    }


    /**
     * @OA\Delete(
     *     path="/api/jenis-cuti/{id}",
     *     tags={"Jenis Cuti"},
     *     summary="Hapus jenis cuti",
     *     @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          description="ID jenis cuti"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil menghapus data"
     *     )
     * )
     */
    public function destroy($id)
    {
        $jenis = JenisCuti::find($id);
        if (!$jenis) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ditemukan'], 404);
        }

        $jenis->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus'
        ], 200);
    }
}
