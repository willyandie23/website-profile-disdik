<?php

namespace App\Models;

use App\Traits\ModelLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Download extends Model
{
    use ModelLog, HasFactory, SoftDeletes;

    protected $fillable = [
        'file_name',
        'total_download',
        'file_path'
    ];
}
