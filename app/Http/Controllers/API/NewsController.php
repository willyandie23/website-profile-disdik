<?php

namespace App\Http\Controllers\API;

use App\Models\News;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="news",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Gallery Title"),
 *     @OA\Property(property="author", type="string", example="Gallery Author"),
 *     @OA\Property(property="image", type="string", example="http://example.com/image.jpg"),
 *     @OA\Property(property="description", type="string", example="Gallery Description"),
 * )
 */
class NewsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/news",
     *     summary="Retrieve a list of News",
     *     tags={"News"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="News retrieved successfully",
     *         @OA\JsonContent(
     *            type="array",
     *             @OA\Items(ref="#/components/schemas/news")
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
            $news = News::orderBy('id', 'desc')->get();

            return ApiResponseClass::success(
                $news,
                "News retrieved successfully"
            );
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException(
                $e,
                "Failed to retrieve News"
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/news/{id}",
     *     summary="Retrieve a single News by ID",
     *     tags={"News"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="News ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="News retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/news")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="News not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="News not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to retrieve News"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        try {
            $news = News::find($id);

            if (!$news) {
                return response()->json(['message' => 'News not found'], 404);
            }

            return ApiResponseClass::success($news, "News retrieved successfully");
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException($e, "Failed to retrieve News");
        }
    }

    /**
     * @OA\Post(
     *     path="/api/news",
     *     tags={"News"},
     *     summary="Create a new News",
     *     description="Create a new News",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="author", type="string"),
     *             @OA\Property(property="image", type="string"),
     *             @OA\Property(property="description", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="News created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="News created successfully"),
     *             @OA\Property(
     *                 property="News",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="News Title"),
     *                 @OA\Property(property="author", type="string", example="News Author"),
     *                 @OA\Property(property="image", type="string", example="News Image URL"),
     *                 @OA\Property(property="description", type="string", example="News Description"),
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
     *             @OA\Property(property="message", type="string", example="Failed to create News"),
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
            'author' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'description' => 'nullable|string',
        ]);

        try {
            // Menyimpan gambar
            $imagePath = $request->file('image')->store('news', 'public');

            // Membuat News baru
            $news = News::create([
                'title' => $request->title,
                'author' => $request->author,
                'image' => Storage::url($imagePath),
                'description' => $request->description,
            ]);

            // Mengirim respon sukses
            return response()->json([
                'message' => 'News created successfully',
                'News' => $news
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create News', 'error' => $e->getMessage()], 500);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/news/{id}",
     *     tags={"News"},
     *     summary="Update an existing News",
     *     description="Update an existing News",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the News to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="author", type="string"),
     *             @OA\Property(property="image", type="string"),
     *             @OA\Property(property="description", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="News updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="News updated successfully"),
     *             @OA\Property(
     *                 property="news",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="News Title"),
     *                 @OA\Property(property="author", type="string", example="News Author"),
     *                 @OA\Property(property="description", type="string", example="News Description"),
     *                 @OA\Property(property="image", type="string", example="News Image URL")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="News not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="News not found")
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
     *                     @OA\Items(type="string", example="The News Title field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to update News"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255' . $id,
            'author' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        try {
            if ($request->hasFile('image')) {
                if ($news->image) {
                    $imagePath = str_replace('news', 'public', $news->image);
                    Storage::delete($imagePath);
                }

                $imagePath = $request->file('image')->store('news', 'public');
                $news->image = Storage::url($imagePath);
            }

            $news->update([
                'title' => $request->title,
                'author' => $request->author,
                'description' => $request->description
            ]);

            // $news->save();

            return response()->json([
                'message' => 'News updated successfully',
                'News' => $news
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update News', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/news/{id}",
     *     tags={"News"},
     *     summary="Delete a News",
     *     description="Delete a News by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="News ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="News deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="News deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="News not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="News not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to delete News"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        $news = News::find($id);
        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        try {
            Storage::delete('public/news/' . basename($news->image));
            $news->delete();
            return response()->json([
                'message' => 'News deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete News', 'error' => $e->getMessage()], 500);
        }
    }

    // Method untuk view (non-API)
    public function newsShow()
    {
        $news = News::all();

        return view('backend.news.index', compact('news'));
    }

    public function create()
    {
        $news = News::all();
        return view('backend.news.create', compact('news'));
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('backend.news.edit', compact('news'));
    }
}
