<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogGameHistoryDetail extends Model
{
    use HasFactory;

    protected  $fillable = [
        'id_game_history',
        'id_mission',
        'name',
        'telp',
        'completed_at'
    ];
}
