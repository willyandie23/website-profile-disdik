<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;

class ApiResponseClass
{
    /**
     * Rollback transaksi database dan lemparkan exception dengan log
     */
    public static function rollback(Throwable $e, string $message = "Something went wrong! Process not completed."): void
    {
        DB::rollBack();
        self::errorException($e, $message);
    }

    /**
     * Menangani exception dengan logging dan response
     */
    public static function errorException(Throwable $e, string $message = "An unexpected error occurred."): void
    {
        Log::error("[ERROR] " . $e->getMessage(), [
            'exception' => $e,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        throw new HttpResponseException(response()->json([
            "success" => false,
            "message" => $message,
            "error" => $e->getMessage(),
        ], 500));
    }

    /**
     * Response untuk datatable
     */
    public static function sendResponseDatatable($result, string $message, int $draw = 1, int $count = 0, int $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'draw' => $draw,
            'data' => $result,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'message' => $message,
        ], $code);
    }

    /**
     * Response sukses
     */
    public static function success($data = [], string $message = "Request successfully processed.", int $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    /**
     * Response sukses untuk resource yang berhasil dibuat
     */
    public static function created($data = [], string $message = "Resource created successfully."): \Illuminate\Http\JsonResponse
    {
        return self::success($data, $message, 201);
    }

    /**
     * Response error umum
     */
    public static function error(string $message = "Something went wrong.", int $code = 400): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }

    /**
     * Response error 401 (Unauthorized)
     */
    public static function unauthorized(string $message = "Unauthorized access."): \Illuminate\Http\JsonResponse
    {
        return self::error($message, 401);
    }

    /**
     * Response error 403 (Forbidden)
     */
    public static function forbidden(string $message = "Forbidden access."): \Illuminate\Http\JsonResponse
    {
        return self::error($message, 403);
    }

    /**
     * Response error 404 (Not Found)
     */
    public static function notFound(string $message = "Resource not found."): \Illuminate\Http\JsonResponse
    {
        return self::error($message, 404);
    }

    /**
     * Response error 422 (Validation Error)
     */
    public static function validationError(array $errors = [], string $message = "Validation error."): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }

    /**
     * Response error internal server
     */
    public static function serverError(string $message = "Internal server error."): \Illuminate\Http\JsonResponse
    {
        return self::error($message, 500);
    }

    /**
     * Response for empty or non-existent data
     */
    public static function emptyData(string $message = "No data found."): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => $message
        ], 200);
    }
}
