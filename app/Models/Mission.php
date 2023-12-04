<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_game',
        'id_merchant',
        'name',
        'description',
        'image',
        'image_additional'
    ];
}
