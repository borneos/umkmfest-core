<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\CloudinaryImage;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class GameController extends Controller
{
    use CloudinaryImage;

    public function index(Request $request)
    {
        $gameQuery = Game::query();
        $sortColumn = $request->query('sortColumn');
        $sortDirection = $request->query('sortDirection');
        $searchParam = $request->query('q');

        if ($sortColumn && $sortDirection) {
            $gameQuery->orderBy($sortColumn, $sortDirection ?: 'asc');
        }

        if ($searchParam) {
            $gameQuery = $gameQuery->where(function ($query) use ($searchParam) {
                $query
                    ->orWhere('name', 'like', "%$searchParam%");
            });
        }

        $games = $gameQuery->paginate(10);
        return view('admin.games', compact('games', 'sortColumn', 'sortDirection', 'searchParam'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'pin' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,svg|max:8192'
        ]);

        if ($request->file('image')) {
            $image = $this->UploadImageCloudinary(['image' => $request->file('image'), 'folder' => 'pktbeedufest/games']);
            $image_url = $image['url'];
            $additional_image = $image['additional_image'];
        } else {
            $image_url = '';
            $additional_image = '';
        }

        Game::create([
            'name'  => $request->name,
            'code'  => $request->code,
            'slug'  => Str::slug($request->name) . '-' . Str::random(5),
            'pin'  => $request->pin,
            'description'  => $request->description,
            'image' => $image_url,
            'image_additional'  => $additional_image,
            'status'    => $request->status == "on" ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Game berhasil disimpan!');
    }

    public function edit($id)
    {
        $game = Game::find($id);
        return response()->json([
            'status' => 200,
            'game' => $game
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'pin' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,svg|max:8192'
        ]);

        $game = Game::findOrFail($request->game_id);

        if ($request->file('image')) {
            $image = $this->UpdateImageCloudinary([
                'image'      => $request->file('image'),
                'folder'     => 'pktbeedufest/games',
                'collection' => $game
            ]);
            $image_url = $image['url'];
            $additional_image = $image['additional_image'];
        }

        $game->update([
            'name' => $request->name,
            'code' => $request->code,
            'pin' => $request->pin,
            'description' => $request->description,
            'status'    => $request->status == "on" ? 1 : 0,
            'image' => $image_url ?? $game->image,
            'image_additional' => $additional_image ?? $game->image_additional
        ]);

        return redirect()->back()->with('success', 'Banner berhasil diubah!');
    }

    public function delete(Request $request)
    {
        $game = Game::findOrFail($request->id);
        if ($game->image && $game->image_additional) {
            $key = json_decode($game->image_additional);
            Cloudinary::destroy($key->public_id);
        }
        $game->delete();
        return response()->json(['status' => 200]);
    }
}
