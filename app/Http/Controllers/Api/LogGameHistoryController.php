<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\FormatMeta;
use App\Http\Traits\LogGameHistory;
use App\Models\LogGameHistory as ModelsLogGameHistory;
use Illuminate\Http\Request;

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
}
