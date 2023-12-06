<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\CloudinaryImage;
use App\Models\Game;
use App\Models\Merchant;
use App\Models\Mission;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class MissionController extends Controller
{
    use CloudinaryImage;

    public function index($id)
    {
        $arrayMerchants[] = '';
        $game = Game::where('id', '=', $id)->first();
        $missions = Mission::where('id_game', '=', $id)->get();
        $missionsAll = Mission::all();
        $merchantsEdit = Merchant::all();
        foreach ($missionsAll as $mission) {
            $arrayMerchants[] = $mission->id_merchant;
        }
        if ($arrayMerchants == '') {
            $merchants = Merchant::all();
        } else {
            $merchants = Merchant::whereNotIn('id', $arrayMerchants)->get();
        }

        return view('admin.missions', compact('missions', 'game', 'merchants', 'merchantsEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_merchant' => 'required',
            'name' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,svg|max:8192'
        ]);

        if ($request->file('image')) {
            $image = $this->UploadImageCloudinary(['image' => $request->file('image'), 'folder' => 'pktbeedufest/missions']);
            $image_url = $image['url'];
            $additional_image = $image['additional_image'];
        } else {
            $image_url = '';
            $additional_image = '';
        }

        Mission::create([
            'id_game' => $request->id_game,
            'id_merchant' => $request->id_merchant,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image_url,
            'image_additional'  => $additional_image
        ]);

        return redirect()->back()->with('success', 'Mission berhasil disimpan');
    }

    public function edit($id)
    {
        $mission = Mission::find($id);
        return response()->json([
            'status' => 200,
            'mission' => $mission
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_merchant' => 'required',
            'name' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,svg|max:8192'
        ]);

        $mission = Mission::findOrFail($request->mission_id);

        if ($request->file('image')) {
            $image = $this->UpdateImageCloudinary([
                'image'      => $request->file('image'),
                'folder'     => 'pktbeedufest/missions',
                'collection' => $mission
            ]);
            $image_url = $image['url'];
            $additional_image = $image['additional_image'];
        }
        $mission->update([
            'id_merchant' => $request->id_merchant,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image_url ?? $mission->image,
            'image_additional' => $additional_image ?? $mission->image_additional
        ]);

        return redirect()->back()->with('success', 'Mission berhasil diubah!');
    }

    public function delete(Request $request)
    {
        $mission = Mission::findOrFail($request->id);
        if ($mission->image && $mission->image_additional) {
            $key = json_decode($mission->image_additional);
            Cloudinary::destroy($key->public_id);
        }
        $mission->delete();
        return response()->json(['status' => 200]);
    }
}
