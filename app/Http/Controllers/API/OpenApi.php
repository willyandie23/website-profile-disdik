<?php

namespace App\Http\Controllers\API;

/**
 * @OA\Info(
 *     title="Website Profile Dinas Pendidikan Kab. Katingan API",
 *     version="1.0.0",
 *     description="API untuk Website Profile Dinas Pendidikan Kabupaten Katingan. Digunakan untuk mengelola autentikasi pengguna, data pengguna, dan intregasi data.",
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Local Development Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your Bearer token in the format: Bearer <token>"
 * )
 *
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 * )
 */
class OpenApi
{
    // File ini hanya untuk anotasi, tidak perlu logika
}
