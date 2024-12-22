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
    public function get_event_histories_id($id)
    {
        $meta = $this->metaEventHistoryId(['success' => true]);
        $eventHistoriesId = $this->queryEventHistoryId($id);
        return response()->json(['meta' => $meta, 'data' => $this->resultEventListId($eventHistoriesId)]);
    }

    public function get_event_histories_uuid($uuid)
    {
        $meta = $this->metaEventHistoryId(['success' => true]);
        $data = $this->queryEventHistoryUUID($uuid);
        return response()->json(['meta' => $meta, 'data' => $data]);
    }
}
