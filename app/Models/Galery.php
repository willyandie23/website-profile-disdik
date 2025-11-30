<?php

namespace App\Models;

use App\Traits\ModelLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Galery extends Model
{
    use ModelLog, SoftDeletes, HasFactory;

    protected $table = 'galerys';

    protected $fillable = [
        'title',
        'description',
        'image'
    ];
}
