<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\CloudinaryImage;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class EventController extends Controller
{
    use CloudinaryImage;

    public function index(Request $request)
    {
        $eventQuery = Event::query();
        $sortColumn = $request->query('sortColumn');
        $sortDirection = $request->query('sortDirection');
        $searchParam = $request->query('q');

        if ($sortColumn && $sortDirection) {
            $eventQuery->orderBy($sortColumn, $sortDirection ?: 'asc');
        }

        if ($searchParam) {
            $eventQuery = $eventQuery->where(function ($query) use ($searchParam) {
                $query
                    ->orWhere('name', 'like', "%$searchParam%")
                    ->orWhere('presenter_name', 'like', "%$searchParam%")
                    ->orWhere('location', 'like', "%$searchParam%")
                    ->orWhere('category', 'like', "%$searchParam%");
            });
        }

        $events = $eventQuery->paginate(5);
        return view('admin.events', compact('events', 'sortColumn', 'sortDirection', 'searchParam'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'description' => 'required',
            'presenter_name' => 'required',
            'presenter_position' => 'required',
            'presenter_image' => 'required|image|mimes:jpeg,png,jpg,svg|max:8192',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required',
            'location_link' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:8192'
        ]);

        if ($request->file('image')) {
            $image = $this->UploadImageCloudinary(['image' => $request->file('image'), 'folder' => 'pktbeedufest/events']);
            $image_url = $image['url'];
            $additional_image = $image['additional_image'];
        } else {
            $image_url = '';
            $additional_image = '';
        };

        if ($request->file('presenter_image')) {
            $image_presenter = $this->UploadImageCloudinary(['image' => $request->file('presenter_image'), 'folder' => 'pktbeedufest/events']);
            $image_url_presenter = $image_presenter['url'];
            $additional_image_presenter = $image_presenter['additional_image'];
        } else {
            $image_url_presenter = '';
            $additional_image_presenter = '';
        };

        Event::create([
            'name'  => $request->name,
            'category'  => $request->category,
            'slug'  => Str::slug($request->name) . '-' . Str::random(5),
            'description'  => $request->description,
            'presenter_name'  => $request->presenter_name,
            'presenter_position'  => $request->presenter_position,
            'presenter_image'  => $image_url_presenter,
            'presenter_image_additional'  => $additional_image_presenter,
            'date'  => $request->date,
            'start_time'  => $request->start_time,
            'end_time'  => $request->end_time,
            'location'  => $request->location,
            'location_link'  => $request->location_link,
            'image' => $image_url,
            'image_additional'  => $additional_image,
            'status'    => $request->status == "on" ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Event berhasil disimpan!');
    }

    public function edit($id)
    {
        $event = Event::find($id);
        return response()->json([
            'status' => 200,
            'event' => $event
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,svg|max:3000'
        ]);

        $event = Event::findOrFail($request->event_id);

        if ($request->file('image')) {
            $image = $this->UpdateImageCloudinary([
                'image'      => $request->file('image'),
                'folder'     => 'pktbeedufest/events',
                'collection' => $event
            ]);
            $image_url = $image['url'];
            $additional_image = $image['additional_image'];
        }

        if ($request->file('presenter_image')) {
            $image_presenter = $this->UpdateImageCloudinary([
                'image'      => $request->file('presenter_image'),
                'folder'     => 'pktbeedufest/events',
                'collection' => $event
            ]);
            $image_url_presenter = $image_presenter['url'];
            $additional_image_presenter = $image_presenter['additional_image'];
        }
        $event->update([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'presenter_name' => $request->presenter_name,
            'presenter_position' => $request->presenter_position,
            'presenter_image' => $image_url_presenter ?? $event->presenter_image,
            'presenter_image_additional' => $additional_image_presenter ?? $event->presenter_image_additional,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'location_link' => $request->location_link,
            'image' => $image_url ?? $event->image,
            'image_additional' => $additional_image ?? $event->image_additional,
            'status'    => $request->status == "on" ? 1 : 0
        ]);

        return redirect()->back()->with('success', 'Event berhasil diubah!');
    }

    public function delete(Request $request)
    {
        $event = Event::findOrFail($request->id);
        if ($event->image && $event->image_additional) {
            $key = json_decode($event->image_additional);
            Cloudinary::destroy($key->public_id);
        }
        if ($event->presenter_image && $event->presenter_image_additional) {
            $key = json_decode($event->presenter_image_additional);
            Cloudinary::destroy($key->public_id);
        }
        $event->delete();
        return response()->json(['status' => 200]);
    }
}
