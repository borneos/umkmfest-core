<?php

namespace App\Http\Traits;

use App\Models\Merchant as ModelsMerchant;
use App\Models\Mission;

trait Merchant
{
    public function queryMerchantList($data)
    {
        $sort = $data['sort'];
        $status = $data['status'];

        return ModelsMerchant::where('status', '=', $status)
            ->orderBy('id', $sort)->get();
    }

    public function queryMerchantDetail($data)
    {
        return ModelsMerchant::where('slug', '=', $data['slug'])->first();
    }

    public function queryMerchantGame($id)
    {
        $missions = Mission::where('id_game', '=', $id)->get();
        foreach ($missions as $mission) {
            $dataResult[] = [
                'id' => $mission->id,
                'merchants' => $mission->id_merchant,
                'name' => $mission->name,
                'description' => $mission->description,
                'image' => $mission->image,
                'imageAdditional' => $mission->image_additional,
            ];
        }
        return $dataResult;
    }

    public function resultMerchantList($data)
    {
        foreach ($data as $result) {
            $results[] = [
                'name' => $result->name,
                'slug' => $result->slug,
                'description' => $result->description,
                'image' => $result->image,
                'imageAdditional' => $result->image_additional,
                'status' => $result->status,
                'createdAt' => $result->created_at,
                'updateAt' => $result->update_at
            ];
        }
        return $results;
    }

    public function resultMerchantDetail($data)
    {
        $results[] = [
            'name' => $data->name,
            'slug' => $data->slug,
            'description' => $data->description,
            'image' => $data->image,
            'imageAdditional' => $data->image_additional,
            'status' => $data->status,
            'createdAt' => $data->created_at,
            'updateAt' => $data->update_at
        ];
        return $results;
    }
}
