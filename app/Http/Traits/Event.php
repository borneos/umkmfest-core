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

    public function queryEventNameSlug($id)
    {
        $eventGame = ModelsEvent::where('id', '=', $id)->first();

        return [
            'id' => $eventGame->id,
            'name' => $eventGame->name,
            'slug' => $eventGame->slug,
        ];
    }

    public function queryDetailEvent($data)
    {
        return ModelsEvent::where('slug', '=', $data['slug'])->first();
    }

    public function resultEventList($data)
    {
        foreach ($data as $result) {
            $results[] = [
                'id' => $result->id,
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

    public function resultEventDetail($data)
    {
        $results[] = [
            'id' => $data->id,
            'name' => $data->name,
            'category' => $data->category,
            'slug' => $data->slug,
            'description' => $data->description,
            'presenterName' => $data->presenter_name,
            'presenterPosition' => $data->presenter_position,
            'presenterImage' => $data->presenter_image,
            'presenterImageAdditional' => $data->presenter_image_additional,
            'image' => $data->image,
            'imageAdditional' => $data->image_additional,
            'date' => $data->date,
            'start_time' => $data->start_time,
            'end_time' => $data->end_time,
            'location' => $data->location,
            'linkLocation' => $data->location_link,
            'status' => $data->status,
            'createdAt' => $data->created_at,
            'updateAt' => $data->update_at
        ];
        return $results;
    }
}
