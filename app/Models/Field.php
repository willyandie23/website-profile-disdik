<?php

namespace App\Models;

use App\Traits\ModelLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use ModelLog, SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'field_id');
    }
}
