<?php

namespace App\Http\Controllers\API;

use App\Models\Galery;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="galery",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Gallery Title"),
 *     @OA\Property(property="description", type="string", example="Gallery Description"),
 *     @OA\Property(property="image", type="string", example="http://example.com/image.jpg")
 * )
 */
class GaleryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/galery",
     *     summary="Retrieve a list of Gallery",
     *     tags={"Gallery"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Gallery retrieved successfully",
     *         @OA\JsonContent(
     *            type="array",
     *             @OA\Items(ref="#/components/schemas/galery")
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
            $gallerys = Galery::orderBy('id', 'desc')->get();

            return ApiResponseClass::success(
                $gallerys,
                "Gallery retrieved successfully"
            );
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException(
                $e,
                "Failed to retrieve Gallery"
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/galery/{id}",
     *     summary="Retrieve a single gallery galery ID",
     *     tags={"Gallery"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Gallery ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Gallery retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/galery")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Gallery not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Gallery not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to retrieve Gallery"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        try {
            $gallerys = Galery::find($id);

            if (!$gallerys) {
                return response()->json(['message' => 'Gallery not found'], 404);
            }

            return ApiResponseClass::success($gallerys, "Gallery retrieved successfully");
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException($e, "Failed to retrieve Gallery");
        }
    }

    /**
     * @OA\Post(
     *     path="/api/galery",
     *     tags={"Gallery"},
     *     summary="galery a new gallery",
     *     description = "Create a new gallery",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="image", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Gallery created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Gallery created successfully"),
     *             @OA\Property(
     *                 property="Gallery",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="Gallery Title"),
     *                 @OA\Property(property="description", type="string", example="Gallery Description"),
     *                 @OA\Property(property="image", type="string", example="Gallery Image URL")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Token invalid or missing",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="array",
     *                     @OA\Items(type="string", example="The title field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to create Gallery"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        // Validasi inputan yang diperlukan
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', //
        ]);

        try {
            // Menyimpan gambar
            $imagePath = $request->file('image')->store('gallerys', 'public');

            $gallerys = Galery::create([
                'title' => $request->title,
                'description' => $request->description,
                'image' => Storage::url($imagePath)
            ]);

            // Mengirim respon sukses
            return response()->json([
                'message' => 'Gallery created successfully',
                'Gallery' => $gallerys
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create Gallery', 'error' => $e->getMessage()], 500);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/galery/{id}",
     *     tags={"Gallery"},
     *     summary= "Update an existing Gallery",
     *     description = "Update an existing Gallery",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Gallery to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="image", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Gallery updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Gallery updated successfully"),
     *             @OA\Property(
     *                 property="gallery",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="Gallery Title"),
     *                 @OA\Property(property="description", type="string", example="Gallery Description"),
     *                 @OA\Property(property="image", type="string", example="Gallery Image URL")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Gallery not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Gallery not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="array",
     *                     @OA\Items(type="string", example="The Gallery Title field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to update Gallery"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        $gallerys = Galery::find($id);

        if (!$gallerys) {
            return response()->json(['message' => 'Gallery not found'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255' . $id,
            'description' => 'required|string'
        ]);

        try {
            if ($request->hasFile('image')) {
                if ($gallerys->image) {
                    $imagePath = str_replace('gallerys', 'public', $gallerys->image);
                    Storage::delete($imagePath);
                }

                $imagePath = $request->file('image')->store('gallerys', 'public');
                $gallerys->image = Storage::url($imagePath);
            }

            $gallerys->update([
                'title' => $request->title,
                'description' => $request->description
            ]);

            // $gallerys->save();

            return response()->json([
                'message' => 'Gallery updated successfully',
                'Gallery' => $gallerys
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Gallery', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/galery/{id}",
     *     tags={"Gallery"},
     *     summary="galery a Gallery",
     *     description = "Delete a Gallery by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Gallery ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Gallery deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Gallery deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Gallery not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Gallery not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to delete Gallery"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        $gallerys = Galery::find($id);
        if (!$gallerys) {
            return response()->json(['message' => 'Gallery not found'], 404);
        }

        try {
            Storage::delete('public/gallerys/' . basename($gallerys->image));
            $gallerys->delete();
            return response()->json([
                'message' => 'Gallery deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete Gallery', 'error' => $e->getMessage()], 500);
        }
    }

    // Method untuk view (non-API)
    public function galleryShow()
    {
        $gallerys = Galery::all();

        return view('backend.gallery.index', compact('gallerys'));
    }

    public function create()
    {
        $gallerys = Galery::all();
        return view('backend.gallery.create', compact('gallerys'));
    }

    public function edit($id)
    {
        $gallerys = Galery::findOrFail($id);
        return view('backend.gallery.edit', compact('gallerys'));
    }
}
