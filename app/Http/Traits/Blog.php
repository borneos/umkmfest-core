<?php

namespace App\Http\Traits;

use App\Models\Blog as ModelsBlog;

trait Blog
{
    public function queryBlogList($data)
    {
        $sort = $data['sort'];
        $perPage = $data['perPage'];
        $status = $data['status'];

        return ModelsBlog::where('status', '=', $status)
            ->orderBy('id', $sort)
            ->paginate($perPage);
    }

    public function resultBlogList($data)
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
}
