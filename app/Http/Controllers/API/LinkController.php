<?php

namespace App\Http\Controllers\API;

use App\Models\Link;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;


/**
 * @OA\Schema(
 *     schema="link",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Portal Katingan"),
 *     @OA\Property(property="link", type="string", example="https://portal.katingankab.go.id/"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class LinkController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/links",
     *     summary="Retrieve a list of link",
     *     tags={"Links"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Links retrieved successfully",
     *         @OA\JsonContent(
     *            type="array",
     *             @OA\Items(ref="#/components/schemas/link")
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
            $links = Link::orderBy('id', 'asc')->get();

            return ApiResponseClass::success(
                $links,
                "Links retrieved successfully"
            );
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException(
                $e,
                "Failed to retrieve Links Data"
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/links",
     *     tags={"Links"},
     *     summary="Create a new Links",
     *     description="Create a new Links",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="link", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Links created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Links created successfully"),
     *             @OA\Property(
     *                 property="Links",
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="name_value"),
     *                 @OA\Property(property="link", type="string", example="link_value"),
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
     *                     property="key",
     *                     type="array",
     *                     @OA\Items(type="string", example="The key field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to create Links"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|string|max:255',
        ]);

        $rowCount = Link::count();
        if ($rowCount >= 5) {
            return response()->json([
                'status' => 'error',
                'message' => 'Row limit exceeded',
            ], 422);
        }

        try {
            $links = Link::create([
                'name' => $request->name,
                'link' => $request->link,
            ]);
            return response()->json([
                'message' => 'Links created successfully',
                'Link' => $links
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create Links', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/links/{id}",
     *     summary="Retrieve a single Links by ID",
     *     tags={"Links"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Links ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Links retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Portal Katingan"),
     *             @OA\Property(property="link", type="string", example="https://portal.katingankab.go.id/")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Links not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Links not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to retrieve Links"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        try {
            $links = Link::find($id);

            if (!$links) {
                return response()->json(['message' => 'Links not found'], 404);
            }

            return ApiResponseClass::success($links, "Links retrieved successfully");
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException($e, "Failed to retrieve Links");
        }
    }

    /**
     * @OA\Put(
     *     path="/api/links/{id}",
     *     tags={"Links"},
     *     summary="Update an existing Links",
     *     description="Update an existing Links",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Links to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Links updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Organization updated successfully"),
     *             @OA\Property(
     *                 property="Links",
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="name_updated"),
     *                 @OA\Property(property="link", type="string", example="link_updated")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Links not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Links not found")
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
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string", example="The Links field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to update Links"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        $links = Link::find($id);
        if (!$links) {
            return response()->json(['message' => 'Links not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255' . $id,
            'link' => 'required|string|max:255',
        ]);

        try {
            $links->update([
                'name' => $request->name,
                'link' => $request->link,
            ]);

            return response()->json([
                'message' => 'Links updated successfully',
                'Link' => $links
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Links', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/links/{id}",
     *     tags={"Links"},
     *     summary="Delete a Links",
     *     description="Delete a Links by ID",
     *     security={{"bearerAuth": {}}}, 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Links ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Links deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Links deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Links not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Links not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to delete Links"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        $links = Link::find($id);

        if (!$links) {
            return response()->json(['message' => 'Link not found'], 404);
        }

        try {
            $links->delete();

            return response()->json([
                'message' => 'Links deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Links',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Method untuk view (non-API)
    public function linkShow()
    {
        return view('backend.link.index');
    }

    public function create()
    {
        $links = Link::all();
        return view('backend.link.create', compact('links'));
    }

    public function edit($id)
    {
        $links = Link::findOrFail($id);
        return view('backend.link.edit', compact('links'));
    }
}
