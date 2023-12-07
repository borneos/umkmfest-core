<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\FormatMeta;
use App\Http\Traits\LogGameHistory;
use App\Models\Game;
use App\Models\LogGameHistory as ModelsLogGameHistory;
use App\Models\LogGameHistoryDetail;
use App\Models\Mission;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LogGameHistoryController extends Controller
{
    use FormatMeta, LogGameHistory;

    public function get_game_histories(Request $request)
    {
        $telp = $request->telp;
        $sort = $request->sort ?? 'desc';
        $perPage = $request->perPage ?? 10;
        if ($telp) {
            $gameHistories = $this->queryGameHistoryList(compact('telp', 'sort'));
            $countGameHistories = $gameHistories->count();
            $meta = $this->metaGameHistory([
                'page' => $request->page == null ? 1 : $request->page,
                'perPage' => $perPage,
                'countGameHistories' => $countGameHistories
            ]);
            if ($countGameHistories == 0) {
                return response()->json(['meta' => $meta, 'data' => null]);
            } else {
                return response()->json(['meta' => $meta, 'data' => $this->resultGameList($gameHistories)]);
            }
        } else {
            $meta = $this->metaGameHistory([
                'success' => false
            ]);
            return response()->json(['meta' => $meta, 'data' => null]);
        }
    }

    public function detail_game_histories($id)
    {
        $gameHistoriesDetail = $this->queryGameHistoryDetail($id);
        $countGameHistories = $gameHistoriesDetail->count();
        if ($countGameHistories > 0) {
            $meta = $this->metaGameHistoryDetail(['success' => true]);
            return response()->json(['meta' => $meta, 'data' => $this->resultGameDetail($gameHistoriesDetail)]);
        } else {
            $meta = $this->metaGameHistoryDetail(['success' => false]);
            return response()->json(['meta' => $meta, 'data' => null]);
        }
    }

    public function create_game_history(Request $request)
    {
        $telp = $request->telp;
        $name = $request->name;
        $id_event = $request->id_event;
        if ($telp || $id_event) {
            $createGameHistory = $this->createGameHistory(compact('telp', 'name', 'id_event'));
            if ($createGameHistory) {
                $meta = $this->metaGenerateGame(
                    ['success' => true]
                );
            } else {
                $meta = $this->metaGenerateGame(
                    ['success' => false]
                );
            }
            return response()->json(['meta' => $meta, 'data' => $createGameHistory]);
        }
    }

    public function redeem_game(Request $request)
    {
        $id_game = $request->idGame;
        $name = $request->name;
        $telp = $request->telp;
        $id_merchant = $request->idMerchant;

        $redeem = Mission::where('id_game', '=', $id_game)
            ->where('id_merchant', '=', $id_merchant)->first();
        $logGameDetail = LogGameHistoryDetail::where('id_mission', '=', $redeem->id)
            ->where('telp', '=', $telp)
            ->get();
        $countLogGameDetail = $logGameDetail->count() ?? 0;
        $logHistory = ModelsLogGameHistory::where('telp', '=', $telp)->whereDate('play_date', '=', Carbon::today()->toDateString())->first();
        if ($countLogGameDetail > 0) {
            $meta = [
                "status" => "error",
                "statusCode" => 500,
                "statusMessage" => "Gagal mendapatkan data, server mengalami gangguan"
            ];
        } else {
            $logCreate = LogGameHistoryDetail::create([
                'id_game_history' => $logHistory->id,
                'id_mission' => $redeem->id,
                'name' => $name,
                'telp' => $telp,
                'completed_at' => now()
            ]);
            $meta = [
                "status" => "success",
                "statusCode" => 200,
                "statusMessage" => "Berhasil redeem booth"
            ];
        }
        return response()->json(['meta' => $meta, 'data' => null]);
    }

    public function complete_game(Request $request, $id)
    {
        $pinToken = $request->pinToken;
        // $id = $request->id;
        $name = $request->name;
        $telp = $request->telp;

        $logGame = ModelsLogGameHistory::find($id);
        $game = Game::where('id', '=', $logGame->id_game)->where('pin', '=', $pinToken)->first();
        if ($game) {
            $logGame->complete_at = now();
            $logGame->save();

            $meta = [
                "status" => "success",
                "statusCode" => 200,
                "statusMessage" => "Berhasil dan selesai games",
            ];
        } else {

            $meta = [
                "status" => "error",
                "statusCode" => 500,
                "statusMessage" => "Gagal mendapatkan data, server mengalami gangguan"
            ];
        }
        return response()->json($meta);
    }
}
