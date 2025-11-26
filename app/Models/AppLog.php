<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * Class AppLog.
 *
 * @author  Willyandie S. <willyandie.sc@gmail.com>
 *
 * @OA\Schema(
 *     description="AppLog model",
 *     title="AppLog model",
 *     @OA\Xml(
 *         name="AppLog"
 *     )
 * )
 */

class AppLog extends Model
{
    /**
     * @var string
     */
    protected $table = 'app_logs';


    /**
     * @var string[]
     */
    protected $fillable = [
        'system_logable_id',
        'system_logable_type',
        'user_id',
        'guard_name',
        'module_name',
        'action',
        'old_value',
        'new_value',
        'ip_address'
    ];

    // Menambahkan relasi belongsTo ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
