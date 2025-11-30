<?php

namespace App\Models;

use App\Traits\ModelLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use ModelLog, SoftDeletes, HasFactory;

    protected $fillable = ['name', 'position', 'NIP', 'image', 'field_id'];

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
}
