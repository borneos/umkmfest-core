<?php

namespace App\Http\Traits;

use App\Models\Event as ModelsEvent;

trait Event
{
    public function queryEventList($data)
    {
        $sort = $data['sort'];
        $perPage = $data['perPage'];
        $status = $data['status'];
        $category = $data['category'];

        if ($category == null) {
            return ModelsEvent::where('status', '=', $status)
                ->orderBy('id', $sort)
                ->paginate($perPage);
        } else {
            return ModelsEvent::where('status', '=', $status)
                ->where('category', '=', $category)
                ->orderBy('id', $sort)
                ->paginate($perPage);
        }
    }

    public function queryEvent($id)
    {
        $event = ModelsEvent::where('id', '=', $id)->first();

        return $event;
    }

    public function resultEventList($data)
    {
        foreach ($data as $result) {
            $results[] = [
                'name' => $result->name,
                'category' => $result->category,
                'slug' => $result->slug,
                'description' => $result->description,
                'presenterName' => $result->presenter_name,
                'presenterPosition' => $result->presenter_position,
                'presenterImage' => $result->presenter_image,
                'presenterImageAdditional' => $result->presenter_image_additional,
                'image' => $result->image,
                'imageAdditional' => $result->image_additional,
                'date' => $result->date,
                'start_time' => $result->start_time,
                'end_time' => $result->end_time,
                'location' => $result->location,
                'linkLocation' => $result->location_link,
                'status' => $result->status,
                'createdAt' => $result->created_at,
                'updateAt' => $result->update_at
            ];
        }
        return $results;
    }
}
