<?php

namespace App\Models;

use App\Traits\ModelLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use ModelLog, SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image'
    ];
}
