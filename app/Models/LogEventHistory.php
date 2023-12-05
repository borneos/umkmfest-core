<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogEventHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'event_name',
        'event_category',
        'event_date',
        'name',
        'telp',
        'email',
        'checkin_at',
        'attendance'
    ];
}
