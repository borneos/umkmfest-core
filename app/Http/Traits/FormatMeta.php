<?php

namespace App\Http\Traits;

trait FormatMeta
{
    public function metaListBlog($data)
    {
        if ($data['countBlogs'] != 0) {
            $paginate = [
                'page' => $data['page'] == null ? 1 : (int)$data['page'],
                'perPage' => (int)$data['perPage'],
            ];
        }
        return [
            [
                'status' => $data['countBlogs'] == 0 ? 'error' : 'success',
                'statusCode' => $data['countBlogs'] == 0 ? 500 : 200,
                'statusMessage' => $data['countBlogs'] == 0 ? 'Gagal mendapatkan data, server mengalami gangguan' : 'Berhasil medapatkan data blog list',
                'pagination' => $paginate ?? null
            ],
        ];
    }

    public function metaListBanner($data)
    {
        return [
            [
                'status' => $data['countBanners'] == 0 ? 'error' : 'success',
                'statusCode' =>
                $data['countBanners'] == 0 ? '500' : '200',
                'statusMessage' => $data['countBanners'] == 0 ? 'Gagal mendapatkan data, server mengalami gangguan' : 'Berhasil medapatkan data banner list',
            ],
        ];
    }
}
