<?php

namespace App\Http\Traits;

use App\Http\Traits\Event as TraitsEvent;
use App\Models\Event;
use App\Models\LogEventHistory as ModelsLogEventHistory;

trait LogEventHistory
{
    use TraitsEvent;

    public function queryEventHistoryList($data)
    {
        $telp = $data['telp'];
        $email = $data['email'];

        return ModelsLogEventHistory::where('telp', '=', $telp)->where('email', '=', $email)->get();
    }

    public function resultEventList($data)
    {
        foreach ($data as $result) {
            $results[] = [
                'name' => $result->name,
                'telp' => $result->telp,
                'email' => $result->email,
                'events' => [
                    $this->queryEvent($result['event_id'])
                ],
                // 'events' => [
                //     'id' => $event->id,
                //     'name' => $event->name,
                //     'category' => $event->category,
                //     'slug' => $event->slug,
                //     'description' => $event->description,
                //     'presenterName' => $event->presenter_name,
                //     'presenterPosition' => $event->presenter_position,
                //     'presenterImage' => $event->presenter_image,
                //     'presenterImageAdditional' => $event->presenter_image_additional,
                //     'image' => $event->image,
                //     'imageAdditional' => $event->image_additional,
                //     'date' => $event->date,
                //     'start_time' => $event->start_time,
                //     'end_time' => $event->end_time,
                //     'location' => $event->location,
                //     'linkLocation' => $event->location_link,
                //     'status' => $event->status,
                //     'createdAt' => $event->created_at,
                //     'updateAt' => $event->update_at
                // ],
                'checkinAt' => $result->checkin_at,
                'createdAt' => $result->created_at,
                'updateAt' => $result->update_at
            ];
        }
        return $results;
    }
}
