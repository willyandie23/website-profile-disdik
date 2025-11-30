<?php

namespace App\Http\Controllers\API;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="field",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Bidang Olahraga"),
 *     @OA\Property(property="description", type="string", example="Bidang ini adalah..."),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class FieldController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/fields",
     *     summary="Retrieve a list of Fields",
     *     tags={"Fields"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Fields retrieved successfully",
     *         @OA\JsonContent(
     *            type="array",
     *             @OA\Items(ref="#/components/schemas/field")
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
            $fields = Field::orderBy('id', 'asc')->get();

            return ApiResponseClass::success(
                $fields,
                "Fields retrieved successfully"
            );
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException(
                $e,
                "Failed to retrieve Fields Data"
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/fields",
     *     tags={"Fields"},
     *     summary="Create a new Fields",
     *     description="Create a new Fields",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Fields created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Fields created successfully"),
     *             @OA\Property(
     *                 property="fields",
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="name_category_value"),
     *                 @OA\Property(property="description", type="string", example="description_value"),
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
     *             @OA\Property(property="message", type="string", example="Failed to create Fields"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        try {
            $fields = Field::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            return response()->json([
                'message' => 'Fields created successfully',
                'Field' => $fields
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create Fields', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/fields/{id}",
     *     summary="Retrieve a single Fields by ID",
     *     tags={"Fields"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Fields ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fields retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Kepala Dinas"),
     *             @OA\Property(property="description", type="string", example="Bidang ini adalah...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fields not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Fields not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to retrieve Fields"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        try {
            $fields = Field::find($id);

            if (!$fields) {
                return response()->json(['message' => 'Fields not found'], 404);
            }

            return ApiResponseClass::success($fields, "Fields retrieved successfully");
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException($e, "Failed to retrieve Fields");
        }
    }

    /**
     * @OA\Put(
     *     path="/api/fields/{id}",
     *     tags={"Fields"},
     *     summary="Update an existing Fields",
     *     description="Update an existing Fields",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Fields to update",
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
     *         description="Fields updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Organization updated successfully"),
     *             @OA\Property(
     *                 property="Fields",
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="name_organization_updated"),
     *                 @OA\Property(property="description", type="string", example="description_updated")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fields not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Fields not found")
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
     *                     @OA\Items(type="string", example="The Fields field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to update Fields"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        $fields = Field::find($id);
        if (!$fields) {
            return response()->json(['message' => 'Fields not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255' . $id,
            'description' => 'required|string',
        ]);

        try {
            $fields->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'message' => 'Fields updated successfully',
                'Field' => $fields
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Fields', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/fields/{id}",
     *     tags={"Fields"},
     *     summary="Delete a Fields",
     *     description="Delete a Fields by ID",
     *     security={{"bearerAuth": {}}}, 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Fields ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fields deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Fields deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fields not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Fields not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Field cannot be deleted because it is being used by one or more organizations",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Field cannot be deleted because it is being used by one or more organizations")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to delete Fields"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        // Cari kategori berdasarkan ID
        $fields = Field::find($id);

        if (!$fields) {
            return response()->json(['message' => 'Field not found'], 404);
        }

        try {
            // Cek apakah kategori ini digunakan oleh organisasi
            if ($fields->organizations()->count() > 0) {
                return response()->json([
                    'message' => 'Masih Ada Anggota Yang Menggunakan Kategori Ini'
                ], 400);
            }

            // Hapus kategori jika tidak digunakan
            $fields->delete();

            return response()->json([
                'message' => 'Fields deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Fields',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Method untuk view (non-API)
    public function fieldShow()
    {
        return view('backend.organizational-structure.field.index');
    }

    public function create()
    {
        $fields = Field::all();
        return view('backend.organizational-structure.field.create', compact('fields'));
    }

    public function edit($id)
    {
        $fields = Field::findOrFail($id);
        return view('backend.organizational-structure.field.edit', compact('fields'));
    }
}
