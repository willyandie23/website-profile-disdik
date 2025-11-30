<?php

namespace App\Http\Controllers\API;

use App\Models\Download;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="download",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="file_name", type="string", example="file_example.pdf"),
 *     @OA\Property(property="total_download", type="integer", example=10),
 *     @OA\Property(property="file_path", type="string", example="http://example.com/storage/file_example.pdf")
 * )
 */
class DownloadController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/downloads",
     *     summary="Retrieve a list of Download files",
     *     tags={"Download"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Download files retrieved successfully",
     *         @OA\JsonContent(
     *            type="array",
     *             @OA\Items(ref="#/components/schemas/download")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $downloads = Download::orderBy('id', 'desc')->get();
            return ApiResponseClass::success($downloads, "Download retrieved successfully");
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException($e, "Failed to retrieve Download Data");
        }
    }

    /**
     * @OA\Post(
     *     path="/api/downloads",
     *     summary="Upload a PDF file",
     *     tags={"Download"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="file", type="string", format="binary", description="PDF file")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="File uploaded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="File uploaded successfully"),
     *             @OA\Property(property="file", ref="#/components/schemas/download")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid file type or size")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to upload file"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'file_name' => 'required|string|max:255',
            'file' => 'required|mimes:pdf|max:5120', //5MB = 5120KB
        ]);

        try {
            $path = $request->file('file')->store('downloads', 'public');
            
            $downloads = Download::create([
                'file_name' => $request->file_name,
                'total_download' => 0,
                'file_path' => Storage::url($path),
            ]);

            return ApiResponseClass::success($downloads, "File uploaded successfully");
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException($e, "Failed to upload file");
        }
    }

    /**
     * @OA\Get(
     *     path="/api/downloads/{id}",
     *     summary="Retrieve a single Download file by ID",
     *     tags={"Download"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Download file ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Download file retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/download")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Download file not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Download not found")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        try {
            $downloads = Download::find($id);

            if (!$downloads) {
                return response()->json(['message' => 'Download not found'], 404);
            }

            return ApiResponseClass::success($downloads, "Download retrieved successfully");
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException($e, "Failed to retrieve Download");
        }
    }

    /**
     * @OA\Put(
     *     path="/api/downloads/{id}",
     *     summary="Update a file by ID",
     *     tags={"Download"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="File ID to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="file_name", type="string", example="updated_file.pdf"),
     *             @OA\Property(property="file", type="string", format="binary", description="PDF file to replace")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="File updated successfully"),
     *             @OA\Property(property="file", ref="#/components/schemas/download")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="File not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="File not found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $downloads = Download::find($id);

        if (!$downloads) {
            return response()->json(['message' => 'File not found'], 404);
        }

        // Validasi input file jika ada perubahan file
        $request->validate([
            'file' => 'nullable|mimes:pdf|max:10240', // Validasi file PDF baru
            'file_name' => 'nullable|string|max:255', // Nama file opsional
        ]);

        try {
            // Jika ada file baru, lakukan proses upload dan update path
            if ($request->hasFile('file')) {
                // Hapus file yang lama dari storage
                Storage::delete('public/downloads/' . basename($downloads->file_path));

                // Upload file baru
                $path = $request->file('file')->store('downloads', 'public');
                $downloads->file_path = Storage::url($path);
            }

            // Update nama file jika ada perubahan
            if ($request->filled('file_name')) {
                $downloads->file_name = $request->file_name;
            }

            $downloads->save();

            return ApiResponseClass::success($downloads, "File updated successfully");
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException($e, "Failed to update file");
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/downloads/{id}",
     *     summary="Delete a file by ID",
     *     tags={"Download"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="File ID to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="File deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="File not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="File not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $downloads = Download::find($id);

        if (!$downloads) {
            return response()->json(['message' => 'File not found'], 404);
        }

        try {
            // Hapus file dari storage
            Storage::delete('public/downloads/' . basename($downloads->file_path));

            // Hapus entri file dari database
            $downloads->delete();

            return response()->json(['message' => 'File deleted successfully'], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to delete file', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/downloads/{id}/download",
     *     summary="Download a file by ID",
     *     tags={"Download"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="File ID to download",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File downloaded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="File downloaded successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="File not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="File not found")
     *         )
     *     )
     * )
     */
    public function download($id)
    {
        // Find the download record by ID
        $downloads = Download::find($id);

        if (!$downloads) {
            return response()->json(['message' => 'File not found'], 404);
        }

        $downloads->increment('total_download');
    }

    // Method untuk view (non-API)
    public function downloadShow()
    {
        return view('backend.download.index');
    }

    public function create()
    {
        $downloads = Download::all();
        return view('backend.download.create', compact('downloads'));
    }

    public function edit($id)
    {
        $downloads = Download::findOrFail($id);
        return view('backend.download.edit', compact('downloads'));
    }
}