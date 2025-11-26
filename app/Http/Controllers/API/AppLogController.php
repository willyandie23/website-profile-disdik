<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppLog;
use App\Classes\ApiResponseClass;

class AppLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     *    @OA\Get(
     *       path="/api/app-logs",
     *       tags={"AppLogs"},
     *       operationId="getAppLog",
     *       summary="Get All AppLogs",
     *       description="Get All App Logs Data",
     *       @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *       ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     )
     *    )
     */
    public function index(Request $request)
    {
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = $request->input('search.value', '');

        $query = AppLog::with('user');

        if ($searchValue) {
            $query->where('system_logable_type', 'like', "%{$searchValue}%")
                ->orWhere('module_name', 'like', "%{$searchValue}%")
                ->orWhere('guard_name', 'like', "%{$searchValue}%")
                ->orWhere('action', 'like', "%{$searchValue}%")
                ->orWhere('created_at', 'like', "%{$searchValue}%");
        }

        $total = $query->count();

        $data = $query->orderBy('created_at', 'desc')
            ->skip($start)->take($length)->get();

        $message = 'App Logs retrieved successfully';
        return ApiResponseClass::sendResponseDatatable($data, $message, intval($request->input('draw')), $total);
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/app-logs/{id}",
     *     tags={"AppLogs"},
     *     summary="Display the specified item",
     *     operationId="appLogShow",
     *     @OA\Response(
     *         response=404,
     *         description="Item not found",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Identifier of item that needs to be displayed",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     * )
     */
    public function show(string $id)
    {

        try {
            $data = AppLog::with('user')->findOrFail($id);

            if (is_null($data)) {
                return ApiResponseClass::notFound();
            } else {
                return ApiResponseClass::success($data);
            }
        } catch (\Exception $e) {
            return ApiResponseClass::serverError($e->getMessage());
        }
    }
}
