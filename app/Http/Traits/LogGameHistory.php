<?php

namespace App\Http\Traits;

use App\Http\Traits\Merchant as TraitsMerchant;
use App\Models\Game;
use App\Models\LogGameHistory as ModelsLogGameHistory;
use App\Models\Merchant;
use App\Models\Mission;
use Illuminate\Support\Carbon;

trait LogGameHistory
{
    use Event, Games, TraitsMerchant;

    public function queryGameHistoryList($data)
    {
        $telp = $data['telp'];
        $sort = $data['sort'];

        return ModelsLogGameHistory::where('telp', '=', $telp)
            ->orderBy('id', $sort)
            ->get();
    }

    public function queryGameHistoryDetail($id)
    {
        return ModelsLogGameHistory::where('id', '=', $id)
            ->get();
    }

    public function createGameHistory($data)
    {
        $telp = $data['telp'];
        $id_event = $data['id_event'];
        $name = $data['name'];

        $logGameHistory = ModelsLogGameHistory::where('telp', '=', $telp)
            ->whereDate('play_date', '=', Carbon::today()->toDateString())->first();
        if ($logGameHistory) {
            return null;
        } else {
            $cekCodeGame = ModelsLogGameHistory::where('telp', '=', $telp)->get();
            $countCekCode = $cekCodeGame->count() ?? 1;
            if ($countCekCode > 0) {
                foreach ($cekCodeGame as $datacek) {
                    $exCodeGame[] = $datacek['id_game'];
                }
                $codeGame = Game::inRandomOrder()->whereNotIn('id', $exCodeGame)->first();
            } else {
                $codeGame = Game::inRandomOrder()->first();
            }

            $missions = Mission::where('id_game', '=', $codeGame->id)->get();
            foreach ($missions as $mission) {
                [
                    'id' => $mission->id,
                    'merchants' => [
                        $this->queryMerchantGame($mission['id_game'])
                    ],
                    'name' => $mission->name,
                    'description' => $mission->description,
                    'image' => $mission->image,
                    'imageAdditional' => $mission->imageAdditional
                ];
            }

            $createGame = ModelsLogGameHistory::create([
                'id_event' => $id_event,
                'id_game' => $codeGame->id,
                'name' => $name,
                'telp' => $telp,
                'email' => $email ?? null,
                'play_date' => now()
            ]);
            if ($createGame) {
                return [
                    'id' => $codeGame['id'],
                    'name' => $codeGame['name'],
                    'slug' => $codeGame['slug'],
                    'code' => $codeGame['code'],
                    'pin' => $codeGame['pin'],
                    'description' => $codeGame['description'],
                    'mission' => $this->queryMerchantGame($mission['id_game']),
                    'image' => $codeGame['image'],
                    'imageAdditional' => $codeGame['imageAdditional'],
                    'status' => $codeGame['status'],
                    'createdAt' => $codeGame['create_at'],
                    'updatedAt' => $codeGame['update_at'],
                ];
            }
        }
    }

    public function resultGameList($data)
    {
        foreach ($data as $result) {
            $results[] = [
                'events' => [
                    $this->queryEventNameSlug($result['id_event'])
                ],
                'games' => [
                    $this->queryGameHistory($result['id_game'])
                ],
                'name' => $result->name,
                'telp' => $result->telp,
                'playDate' => $result->play_date,
                'winsAt' => $result->wins_at,
                'completeAt' => $result->complete_at,
                'createdAt' => $result->created_at,
                'updateAt' => $result->update_at
            ];
        }
        return $results;
    }

    public function resultGameDetail($data)
    {
        foreach ($data as $result) {
            $results[] = [
                'events' => [
                    $this->queryEventNameSlug($result['id_event'])
                ],
                'games' => [
                    $this->queryGameHistory($result['id_game'])
                ],
                'name' => $result->name,
                'telp' => $result->telp,
                'playDate' => $result->play_date,
                'winsAt' => $result->wins_at,
                'completeAt' => $result->complete_at,
                'createdAt' => $result->created_at,
                'updateAt' => $result->update_at
            ];
        }
        return $results;
    }
}
