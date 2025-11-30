<?php

namespace App\Models;

use App\Traits\ModelLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Identity extends Model
{
    use ModelLog, SoftDeletes;

    protected $fillable = ['key', 'value'];
}
