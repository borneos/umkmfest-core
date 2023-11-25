<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\CloudinaryImage;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BlogController extends Controller
{
    use CloudinaryImage;

    public function index(Request $request)
    {
        $blogQuery = Blog::query();
        $sortColumn = $request->query('sortColumn');
        $sortDirection = $request->query('sortDirection');
        $searchParam = $request->query('q');

        if ($sortColumn && $sortDirection) {
            $blogQuery->orderBy($sortColumn, $sortDirection ?: 'asc');
        }

        if ($searchParam) {
            $blogQuery = $blogQuery->where(function ($query) use ($searchParam) {
                $query
                    ->orWhere('name', 'like', "%$searchParam%");
            });
        }

        $blogs = $blogQuery->paginate(5);
        return view('admin.blogs', compact('blogs', 'sortColumn', 'sortDirection', 'searchParam'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:8192'
        ]);

        if ($request->file('image')) {
            $image = $this->UploadImageCloudinary(['image' => $request->file('image'), 'folder' => 'pktbeedufest/blogs']);
            $image_url = $image['url'];
            $additional_image = $image['additional_image'];
        } else {
            $image_url = '';
            $additional_image = '';
        }

        Blog::create([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name) . '-' . Str::random(5),
            'description'  => $request->description,
            'image' => $image_url,
            'image_additional'  => $additional_image,
            'status'    => $request->status == "on" ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Blog berhasil disimpan!');
    }

    public function edit($id)
    {
        $blog = Blog::find($id);
        return response()->json([
            'status' => 200,
            'blog' => $blog
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,svg|max:3000'
        ]);

        $blog = Blog::findOrFail($request->blog_id);

        if ($request->file('image')) {
            $image = $this->UpdateImageCloudinary([
                'image'      => $request->file('image'),
                'folder'     => 'pktbeedufest/blogs',
                'collection' => $blog
            ]);
            $image_url = $image['url'];
            $additional_image = $image['additional_image'];
        }
        $blog->update([
            'name' => $request->name,
            'description' => $request->description,
            'status'    => $request->status == "on" ? 1 : 0,
            'image' => $image_url ?? $blog->image,
            'image_additional' => $additional_image ?? $blog->image_additional
        ]);

        return redirect()->back()->with('success', 'Blog berhasil diubah!');
    }

    public function delete(Request $request)
    {
        $blog = Blog::findOrFail($request->id);
        if ($blog->image && $blog->image_additional) {
            $key = json_decode($blog->image_additional);
            Cloudinary::destroy($key->public_id);
        }
        $blog->delete();
        return response()->json(['status' => 200]);
    }
}
