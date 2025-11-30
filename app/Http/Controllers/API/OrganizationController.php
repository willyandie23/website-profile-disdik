<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="organization",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="position", type="string", example="Kepala Dinas"),
 *     @OA\Property(property="NIP", type="string", example="11111111 123456 1 123"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="image", type="string", example="dummy.jpg"),
 *     @OA\Property(property="field_id", type="string", example="Bagian Sekretariat")
 * )
 */
class OrganizationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/organizations",
     *     summary="Retrieve a list of Organizations",
     *     tags={"Organization"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Organization retrieved successfully",
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
            $organizations = Organization::with('field')->orderBy('id', 'asc')->get();

            return ApiResponseClass::success(
                $organizations,
                "Organization retrieved successfully"
            );
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException(
                $e,
                "Failed to retrieve Organization Data"
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/organizations",
     *     tags={"Organization"},
     *     summary="Create a new Organization",
     *     description="Create a new Organization",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="position", type="string"),
     *             @OA\Property(property="NIP", type="string"),
     *             @OA\Property(property="field_id", type="integer"),
     *             @OA\Property(property="image", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Organizaion created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Organizaion created successfully"),
     *             @OA\Property(
     *                 property="organization",
     *                 type="object",
     *                @OA\Property(property="id", type="integer", example=1),
     *                @OA\Property(property="name", type="string", example="John Doe"),
     *                @OA\Property(property="position", type="string", example="Kepala Dinas"),
     *                @OA\Property(property="NIP", type="string", example="11111111 123456 1 123"),
     *                @OA\Property(property="image", type="string", example="dummy.jpg"),
     *                @OA\Property(property="field_id", type="string", example="Bagian Sekretariat")
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
     *             @OA\Property(property="message", type="string", example="Failed to create Organizaion"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'NIP' => 'required|string|max:255',
            'field_id' => 'required|exists:fields,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            $imagePath = $request->file('image')->store('organizations', 'public');

            $organization = Organization::create([
                'name' => $request->name,
                'position' => $request->position,
                'NIP' => $request->NIP,
                'field_id' => $request->field_id,
                'image' => Storage::url($imagePath)
            ]);

            return response()->json([
                'message' => 'Organization created successfully',
                'organization' => $organization
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create Organization',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/{id}",
     *     summary="Retrieve a single Organization by ID",
     *     tags={"Organization"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Organization ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Organization retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Example Organization"),
     *             @OA\Property(property="position", type="string", example="Example position"),
     *             @OA\Property(property="NIP", type="string", example="11111111 123456 1 123"),
     *             @OA\Property(
     *                 property="field", 
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Example Field")
     *             ),
     *             @OA\Property(property="image", type="string", example="Example Image")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Organization not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Organization not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to retrieve Organization"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        try {
            $organizations = Organization::with('field')->find($id);

            if (!$organizations) {
                return response()->json(['message' => 'Organization not found'], 404);
            }

            return ApiResponseClass::success($organizations, "Organization retrieved successfully");
        } catch (\Throwable $e) {
            return ApiResponseClass::errorException($e, "Failed to retrieve Organization");
        }
    }

    /**
     * @OA\Put(
     *     path="/api/organizations/{id}",
     *     tags={"Organization"},
     *     summary="Update an existing Organization",
     *     description="Update an existing Organization",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the organization to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="position", type="string"),
     *             @OA\Property(property="NIP", type="string"),
     *             @OA\Property(property="field_id", type="integer"),
     *             @OA\Property(property="image", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Organization updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Organization updated successfully"),
     *             @OA\Property(
     *                 property="organization",
     *                 type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Example Organization"),
     *             @OA\Property(property="position", type="string", example="Example position"),
     *             @OA\Property(property="NIP", type="string", example="11111111 123456 1 123"),
     *             @OA\Property(
     *                 property="field", 
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Example Field")
     *             ),
     *             @OA\Property(property="image", type="string", example="Example Image")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Organization not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Organization not found")
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
     *                     @OA\Items(type="string", example="The Organization Name field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to update Organization"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'NIP' => 'required|string|max:255',
            'field_id' => 'required|exists:fields,id'
        ]);

        $organizations = Organization::find($id);
        if (!$organizations) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        try {
            if ($request->hasFile('image')) {
                if ($organizations->image) {
                    $imagePath = str_replace('organizations', 'public', $organizations->image);
                    Storage::delete($imagePath);
                }

                $imagePath = $request->file('image')->store('organizations', 'public');
                $organizations->image = Storage::url($imagePath);
            }

            $organizations->update([
                'name' => $request->name,
                'position' => $request->position,
                'NIP' => $request->NIP,
                'field_id' => $request->field_id,
            ]);

            return response()->json([
                'message' => 'Organization updated successfully',
                'Organization' => $organizations
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Organization', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/organizations/{id}",
     *     tags={"Organization"},
     *     summary="Delete a Organization",
     *     description="Delete a Organization by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Organization ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Organization deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Organization deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Organization not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Organization not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to delete Organization"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        $organizations = Organization::find($id);
        if (!$organizations) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        try {
            $organizations->delete();
            return response()->json([
                'message' => 'Organization deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete Organization', 'error' => $e->getMessage()], 500);
        }
    }



    // Method untuk view (non-API)
    public function organizationShow()
    {
        $field = Field::all();
        return view('backend.organizational-structure.organizations.index', compact('field'));
    }

    public function create()
    {
        $organizations = Organization::all();
        $fields = Field::all();
        return view('backend.organizational-structure.organizations.create', compact('organizations', 'fields'));
    }
    
    public function edit($id)
    {
        $organizations = Organization::findOrFail($id);
        $fields = Field::all();
        return view('backend.organizational-structure.organizations.edit', compact('organizations', 'fields'));
    }
}
