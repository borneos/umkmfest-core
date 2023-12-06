<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\FormatMeta;
use App\Http\Traits\LogEventHistory;
use Illuminate\Http\Request;

class LogEventHistoryController extends Controller
{
    use FormatMeta, LogEventHistory;

    public function get_event_histories(Request $request)
    {
        $telp = $request->telp;
        $sort = $request->sort ?? 'desc';
        $category = $request->category ?? null;

        if ($telp || $category) {
            $eventHistories = $this->queryEventHistoryList(compact('telp', 'category', 'sort'));
            $eventHistoriesCount = $eventHistories->count() ?? 0;
            if ($eventHistoriesCount > 0) {
                $meta = $this->metaEventHistory([
                    'success' => true
                ]);
                return response()->json(['meta' => $meta, 'data' => $this->resultEventList($eventHistories)]);
                // return response()->json(['meta' => $meta, 'data' => $eventHistories]);
            } else {
                $meta = $this->metaEventHistory([
                    'success' => false
                ]);
                return response()->json(['meta' => $meta, 'data' => null]);
            }
        } else {
            $meta = $this->metaEventHistory([
                'success' => false
            ]);
            return response()->json(['meta' => $meta, 'data' => null]);
        }
    }
}
