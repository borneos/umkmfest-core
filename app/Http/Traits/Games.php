<?php

namespace App\Http\Traits;

use App\Models\Game;

trait Games
{
    public function queryGameHistory($id)
    {
        $game = Game::where('id', '=', $id)->first();

        return [
            'id' => $game->id,
            'name' => $game->name,
            'slug' => $game->slug,
            'code' => $game->code,
            'image' => $game->image,
        ];
    }
}
