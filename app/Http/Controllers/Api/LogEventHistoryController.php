<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\FormatMeta;
use App\Http\Traits\LogEventHistory;
use App\Models\Event;
use App\Models\LogEventHistory as ModelsLogEventHistory;
use Carbon\Carbon;
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
    public function checkin_event_histories_uuid(Request $request, $uuid)
    {
        $logEvent = ModelsLogEventHistory::where('prefix', '=', $uuid)->firstOrFail();
        $event = Event::find($logEvent->event_id);

        $now = Carbon::now();
        $dateNow = $now->format('Y-m-d');
        $timeNow = $now->format('H:i');
        $eventStartTime = Carbon::parse($event->start_time);
        $eventScanTime = $eventStartTime->subHours(2)->format('H:i');
        // dd($eventScanTime);

        if (is_null($logEvent->checkin_at)) {
            if ($dateNow == $event->date) {
                if ($timeNow >= $eventScanTime) {
                    $logEvent->checkin_at = now();
                    $logEvent->checkin_by = $request->name;
                    $logEvent->checkin_telp_by = $request->telp;
                    $logEvent->save();
                    $meta = [
                        "status" => "success",
                        "statusCode" => 200,
                        "statusMessage" => "Berhasil Check-in",
                    ];
                    return response()->json(['meta' => $meta, 'data' => $logEvent]);
                } else {
                    $meta = [
                        "status" => "error",
                        "statusCode" => 500,
                        "statusMessage" => "Event Belum dimulai!!"
                    ];
                    return response()->json(['meta' => $meta, 'data' => null]);
                }
            }
        } else {
            $meta = [
                "status" => "error",
                "statusCode" => 500,
                "statusMessage" => "Sudah Pernah Scan!!"
            ];
            return response()->json(['meta' => $meta, 'data' => null]);
        }
    }
}
