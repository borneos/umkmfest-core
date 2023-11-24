<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\CloudinaryImage;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class MerchantController extends Controller
{
    use CloudinaryImage;

    public function index(Request $request)
    {
        $merchantQuery = Merchant::query();
        $sortColumn = $request->query('sortColumn');
        $sortDirection = $request->query('sortDirection');
        $searchParam = $request->query('q');

        if ($sortColumn && $sortDirection) {
            $merchantQuery->orderBy($sortColumn, $sortDirection ?: 'asc');
        }

        if ($searchParam) {
            $merchantQuery = $merchantQuery->where(function ($query) use ($searchParam) {
                $query
                    ->orWhere('name', 'like', "%$searchParam%");
            });
        }

        $merchants = $merchantQuery->paginate(5);
        return view('admin.merchants', compact('merchants', 'sortColumn', 'sortDirection', 'searchParam'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:8192'
        ]);

        if ($request->file('image')) {
            $image = $this->UploadImageCloudinary(['image' => $request->file('image'), 'folder' => 'pktbeedufest/merchants']);
            $image_url = $image['url'];
            $additional_image = $image['additional_image'];
        } else {
            $image_url = '';
            $additional_image = '';
        };

        Merchant::create([
            'name' => $request->name,
            'slug'  => Str::slug($request->name) . '-' . Str::random(5),
            'description' => $request->description,
            'image' => $image_url,
            'image_additional' => $additional_image,
            'status'    => $request->status == "on" ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Merchant berhasil disimpan!');
    }

    public function edit($id)
    {
        $merchant = Merchant::find($id);
        return response()->json([
            'status' => 200,
            'merchant' => $merchant
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,svg|max:3000'
        ]);

        $merchant = Merchant::findOrFail($request->merchant_id);

        if ($request->file('image')) {
            $image = $this->UpdateImageCloudinary([
                'image'      => $request->file('image'),
                'folder'     => 'pktbeedufest/merchants',
                'collection' => $merchant
            ]);
            $image_url = $image['url'];
            $additional_image = $image['additional_image'];
        }

        $merchant->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image_url ?? $merchant->image,
            'image_additional' => $additional_image ?? $merchant->image_additional,
            'status'    => $request->status == "on" ? 1 : 0
        ]);

        return redirect()->back()->with('success', 'Merchant berhasil diubah!');
    }

    public function delete(Request $request)
    {
        $merchant = Merchant::findOrFail($request->id);
        if ($merchant->image && $merchant->image_additional) {
            $key = json_decode($merchant->image_additional);
            Cloudinary::destroy($key->public_id);
        }
        $merchant->delete();
        return response()->json(['status' => 200]);
    }
}
