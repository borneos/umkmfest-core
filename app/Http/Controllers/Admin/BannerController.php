<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\CloudinaryImage;
use Illuminate\Http\Request;
use App\Models\Banner;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BannerController extends Controller
{
    use CloudinaryImage;

    public function index(Request $request)
    {
        $bannerQuery = Banner::query();
        $sortColumn = $request->query('sortColumn');
        $sortDirection = $request->query('sortDirection');
        $searchParam = $request->query('q');

        if ($sortColumn && $sortDirection) {
            $bannerQuery->orderBy($sortColumn, $sortDirection ?: 'asc');
        }

        if ($searchParam) {
            $bannerQuery = $bannerQuery->where(function ($query) use ($searchParam) {
                $query
                    ->orWhere('name', 'like', "%$searchParam%")
                    ->orWhere('link', 'like', "%$searchParam%");
            });
        }

        $banners = $bannerQuery->paginate(10);
        return view('admin.banners', compact('banners', 'sortColumn', 'sortDirection', 'searchParam'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:8192'
        ]);

        if ($request->file('image')) {
            $image = $this->UploadImageCloudinary(['image' => $request->file('image'), 'folder' => 'pktbeedufest/banners']);
            $image_url = $image['url'];
            $additional_image = $image['additional_image'];
        } else {
            $image_url = '';
            $additional_image = '';
        }

        Banner::create([
            'name'  => $request->name,
            'image' => $image_url,
            'image_additional'  => $additional_image,
            'link'  => $request->link ?? '',
            'status'    => $request->status == "on" ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Banner berhasil disimpan!');
    }

    public function edit($id)
    {
        $banner = Banner::find($id);
        return response()->json([
            'status' => 200,
            'banner' => $banner
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,svg|max:3000'
        ]);

        $banner = Banner::findOrFail($request->banner_id);

        if ($request->file('image')) {
            $image = $this->UpdateImageCloudinary([
                'image'      => $request->file('image'),
                'folder'     => 'pktbeedufest/banners',
                'collection' => $banner
            ]);
            $image_url = $image['url'];
            $additional_image = $image['additional_image'];
        }
        $banner->update([
            'name' => $request->name,
            'image' => $image_url ?? $banner->image,
            'link' => $request->link,
            'status'    => $request->status == "on" ? 1 : 0,
            'image_additional' => $additional_image ?? $banner->image_additional
        ]);

        return redirect()->back()->with('success', 'Banner berhasil diubah!');
    }

    public function delete(Request $request)
    {
        $banner = Banner::findOrFail($request->id);
        if ($banner->image && $banner->image_additional) {
            $key = json_decode($banner->image_additional);
            Cloudinary::destroy($key->public_id);
        }
        $banner->delete();
        return response()->json(['status' => 200]);
    }
}
