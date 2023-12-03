<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\Blog as TraitsBlog;
use App\Http\Traits\FormatMeta;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use TraitsBlog, FormatMeta;

    public function get_blogs(Request $request)
    {
        $status = $request->status ?? 1;
        $sort = $request->sort ?? 'desc';
        $perPage = $request->perPage ?? 10;
        $blogs = $this->queryBlogList(compact('status', 'sort', 'perPage'));
        $countBlogs = $blogs->count();
        $meta = $this->metaListBlog([
            'page' => $request->page == null ? 1 : $request->page,
            'perPage' => $perPage,
            'countBlogs' => $countBlogs
        ]);

        if (!$blogs) {
            return response()->json(['meta' => $meta, 'data' => null]);
        } else {
            return response()->json(['meta' => $meta, 'data' => $this->resultBlogList($blogs)]);
        }
    }

    public function detail_blogs($slug)
    {
        $blogs = $this->queryDetailBlog(compact('slug'));
        $countBlogs = $blogs == null ? 0 : $blogs->count();
        $meta = $this->metaDetailBlog([
            'countBlogs' => $countBlogs
        ]);

        if (!$blogs) {
            return response()->json(['meta' => $meta, 'data' => null]);
        } else {
            return response()->json(['meta' => $meta, 'data' => $this->resultBlogDetail($blogs)]);
        }
    }
}
