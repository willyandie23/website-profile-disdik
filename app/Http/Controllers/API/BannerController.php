<?php

namespace App\Http\Controllers\API;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="banner",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Banner Title"),
 *     @OA\Property(property="description", type="string", example="Banner Description"),
 *     @OA\Property(property="image", type="string", example="http://example.com/image.jpg")
 * )
 */
class BannerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/banner",
     *     summary="Retrieve a list of banner",
     *     tags={"Banner"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Banner retrieved successfully",
     *         @OA\JsonContent(
     *            type="array",
     *             @OA\Items(ref="#/components/schemas/banner")
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
            $banners = Banner::orderBy('id', 'asc')->get();

            return ApiResponseClass::success(
                $banners,
                "Banner retrieved successfully"
            );
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException(
                $e,
                "Failed to retrieve Banner"
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/banner/{id}",
     *     summary="Retrieve a single banner by ID",
     *     tags={"Banner"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Banner ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Banner retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/banner")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Banner not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Banner not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to retrieve Banner"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        try {
            $banners = Banner::find($id);

            if (!$banners) {
                return response()->json(['message' => 'Banner not found'], 404);
            }

            return ApiResponseClass::success($banners, "Banner retrieved successfully");
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException($e, "Failed to retrieve Banner");
        }
    }

    /**
     * @OA\Post(
     *     path="/api/banner",
     *     tags={"Banner"},
     *     summary="Create a new banner",
     *     description="Create a new banner",
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
     *         description="Banner created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Banner created successfully"),
     *             @OA\Property(
     *                 property="Banner",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="Banner Title"),
     *                 @OA\Property(property="description", type="string", example="Banner Description"),
     *                 @OA\Property(property="image", type="string", example="Banner Image URL")
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
     *             @OA\Property(property="message", type="string", example="Failed to create Banner"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:5120', // 5MB
        ]);

        // Check if the file is provided
        if ($request->hasFile('image')) {
            try {
                // Store the file
                $imagePath = $request->file('image')->store('banners', 'public');
                
                // Get the public URL
                $imageUrl = Storage::url($imagePath); // Use the public URL for the image

                // Create the banner
                $banner = Banner::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'image' => $imageUrl, // Save the public URL in the database
                ]);
                
                // Return the response as JSON
                return response()->json([
                    'message' => 'Banner created successfully',
                    'banner' => $banner
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to create Banner',
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'No image uploaded'
            ], 422);
        }
    }



    // {   
    //     $image = $request->input('image', 'test image'); 
    //     // Validate the input
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         // 'image' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
    //         'image' => 'nullable', // 5MB
    //     ]);

    //     // Check if the file is provided
    //         try {
    //             // Store the file
    //             // $imagePath = $request->file('image')->store('banners', 'public');
                
    //             // Save the banner
    //             $banner = Banner::create([
    //                 'title' => $request->title,
    //                 'description' => $request->description,
    //                 'image' => $image,
    //                 // 'image' => Storage::url($imagePath),
    //             ]);
                
    //             return response()->json([
    //                 'message' => 'Banner created successfully',
    //                 'banner' => $banner
    //             ], 201);
    //         } catch (\Exception $e) {
    //             return response()->json(['message' => 'Failed to create Banner', 'error' => $e->getMessage()], 500);
    //         }

    // }


    /**
     * @OA\Put(
     *     path="/api/banner/{id}",
     *     tags={"Banner"},
     *     summary="Update an existing Banner",
     *     description="Update an existing Banner",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Banner to update",
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
     *         description="Banner updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Banner updated successfully"),
     *             @OA\Property(
     *                 property="banner",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="Banner Title"),
     *                 @OA\Property(property="description", type="string", example="Banner Description"),
     *                 @OA\Property(property="image", type="string", example="Banner Image URL")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Banner not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Banner not found")
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
     *                     @OA\Items(type="string", example="The Banner Title field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to update Banner"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        $banners = Banner::find($id);

        if (!$banners) {
            return response()->json(['message' => 'Banner not found'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255' . $id,
            'description' => 'required|string'
        ]);

        try {
            if ($request->hasFile('image')) {
                if ($banners->image) {
                    $imagePath = str_replace('banners', 'public', $banners->image);
                    Storage::delete($imagePath);
                }

                $imagePath = $request->file('image')->store('banners', 'public');
                $banners->image = Storage::url($imagePath);
            }

            $banners->update([
                'title' => $request->title,
                'description' => $request->description
            ]);

            // $banners->save();

            return response()->json([
                'message' => 'Banner updated successfully',
                'banner' => $banners
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Banner', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/banner/{id}",
     *     tags={"Banner"},
     *     summary="Delete a Banner",
     *     description="Delete a Banner by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Banner ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Banner deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Banner deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Banner not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Banner not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to delete Banner"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        $banners = Banner::find($id);
        if (!$banners) {
            return response()->json(['message' => 'Banner not found'], 404);
        }

        try {
            Storage::delete('public/banners/' . basename($banners->image));
            $banners->delete();
            return response()->json([
                'message' => 'Banner deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete Banner', 'error' => $e->getMessage()], 500);
        }
    }

    // Method untuk view (non-API)
    public function bannerShow()
    {
        $banners = Banner::all();

        return view('backend.banner.index', compact('banners'));
    }

    public function create()
    {
        $banners = Banner::all();
        return view('backend.banner.create', compact('banners'));
    }

    public function edit($id)
    {
        $banners = Banner::findOrFail($id);
        return view('backend.banner.edit', compact('banners'));
    }
}
