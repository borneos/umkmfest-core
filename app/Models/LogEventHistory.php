<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogEventHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'telp',
        'email',
        'checkin_at'
    ];
}
