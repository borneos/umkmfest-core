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
        $email = $data['email'];
        $sort = $data['sort'];

        return ModelsLogGameHistory::orwhere('telp', '=', $telp)
            ->orwhere('email', '=', $email)
            ->orderBy('id', $sort)
            ->get();
    }

    public function createGameHistory($data)
    {
        $telp = $data['telp'];
        $email = $data['email'];
        $id_event = $data['id_event'];
        $name = $data['name'];

        $logGameHistory = ModelsLogGameHistory::orwhere('telp', '=', $telp)
            ->orwhere('email', '=', $email)
            ->whereDate('play_date', '=', Carbon::today()->toDateString())->first();
        if ($logGameHistory) {
            return null;
        } else {
            $cekCodeGame = ModelsLogGameHistory::orwhere('telp', '=', $telp)
                ->orwhere('email', '=', $email)->get();

            foreach ($cekCodeGame as $datacek) {
                $exCodeGame[] = $datacek['id_game'];
            }
            $codeGame = Game::inRandomOrder()->whereNotIn('id', $exCodeGame)->first();
            $missions = Mission::where('id_game', '=', $codeGame->id)->get();
            // $merchant = Merchant::where('id','=',);
            foreach ($missions as $mission) {
                [
                    'id' => $mission->id,
                    'merchants' => $this->QueryMer
                ];
            }

            $createGame = ModelsLogGameHistory::create([
                'id_event' => $id_event,
                'id_game' => $codeGame,
                'name' => $name,
                'telp' => $telp,
                'email' => $email ?? null,
                'play_date' => now()
            ]);
            if ($createGame) {
                return response()->json([
                    'id' => $codeGame['id'],
                    'name' => $codeGame['name'],
                    'slug' => $codeGame['slug'],
                    'code' => $codeGame['code'],
                    'pin' => $codeGame['pin'],
                    'description' => $codeGame['description'],
                    'mission' => $mission,
                ]);
            }
        }
        return $mission;
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
                'email' => $result->email,
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
