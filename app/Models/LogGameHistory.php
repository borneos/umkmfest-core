<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogGameHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_event',
        'id_game',
        'name',
        'telp',
        'play_date',
        'wins_at',
        'complete_at',
    ];
}
