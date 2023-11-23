<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'slug',
        'description',
        'presenter_name',
        'presenter_position',
        'presenter_image',
        'presenter_image_additional',
        'image',
        'image_additional',
        'date',
        'start_time',
        'end_time',
        'location',
        'location_link',
        'status'
    ];
}
