<?php

namespace App\Http\Controllers\Fun_Services;

use App\Models\Series;
use App\Models\User;
use App\Models\Video; // تأكد من إضافة هذا السطر
use Illuminate\Support\Facades\Storage;

class Fun_Series
{
    public function add_series_services($validated)
    {
        $imagePath = 'default_image_path.jpg';
        if (isset($validated['image'])) {
            $imagePath = $validated['image']->store('media', 'public');
        }

        $add_series = Series::create([
            'name' => $validated['name'],
            'date' => $validated['date'],
            'cast' => $validated['cast'],
            'description' => $validated['description'],
            'duration' => $validated['duration'],
            'rating' => $validated['rating'],
            'image' => $imagePath 
        ]);

        if (!$add_series) {
            return false;
        }

        $add_series->types()->attach($validated['type_id']);
        return true;
    }

    public function show_all_series_services()
    {
        return Series::all();
    }

    public function delete_series_services($request)
    {
        $series = Series::where('name', $request->name)->first();

        if ($series) {
            $series->delete();
            return true;
        }
        return false;
    }

    public function edit_series_services($validated)
    {
        $series = Series::find($validated['id']);

        if (!$series) {
            return false;
        }

        // حذف الصورة القديمة إذا كانت موجودة
        if (isset($validated['image'])) {
            Storage::disk('public')->delete($series->image); // حذف الصورة القديمة
            $validated['image'] = $validated['image']->store('media', 'public'); // تخزين الصورة الجديدة
        }

        $status = $series->update(array_filter([
            'name' => $validated['name'] ?? $series->name,
            'description' => $validated['description'] ?? $series->description,
            'image' => $validated['image'] ?? $series->image,
            'date' => $validated['date'] ?? $series->date,
            'cast' => $validated['cast'] ?? $series->cast,
            'duration' => $validated['duration'] ?? $series->duration,
            'rating' => $validated['rating'] ?? $series->rating
        ]));

        return $status;
    }

    public function add_to_favorites_services($validated)
    {
        $user = User::find(auth()->id());
        if (!$user) {
            return ['status' => 'fail', 'message' => 'User not found'];
        }

        $series = Series::find($validated['series_id']);
        if (!$series) {
            return ['status' => 'fail', 'message' => 'Series not found'];
        }

        if ($user->series()->where('series_id', $series->id)->exists()) {
            return ['status' => 'fail', 'message' => 'Series is already in favorites'];
        }

        $user->series()->attach($series->id);

        return ['status' => 'success', 'message' => 'Series added to favorites successfully'];
    }

    public function remove_from_favorites_services($validated)
    {
        $user = User::find(auth()->id());
        if (!$user) {
            return ['status' => 'fail', 'message' => 'User not found'];
        }

        $series = Series::find($validated['series_id']);
        if (!$series) {
            return ['status' => 'fail', 'message' => 'Series not found'];
        }

        if (!$user->series()->where('series_id', $series->id)->exists()) {
            return ['status' => 'fail', 'message' => 'Series is not in favorites'];
        }

        $user->series()->detach($series->id);

        return ['status' => 'success', 'message' => 'Series removed from favorites successfully'];
    }

    public function search_series_services($request)
    {
        if (!$request->name) {
            return ['status' => 'fail', 'message' => 'Please enter a name to search'];
        }

        $series = Series::where('name', 'like', '%' . $request->name . '%')
            ->orWhere('cast', 'like', '%' . $request->name . '%')
            ->orWhereHas('types', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            })
            ->get();

        if ($series->isNotEmpty()) {
            return ['status' => 'success', 'series' => $series];
        }

        return ['status' => 'fail', 'message' => 'No series found'];
    }

    public function add_video_services($validated)
    {
        $video = Video::create([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'duration' => $validated['duration'],
            'series_id' => $validated['series_id']
        ]);

        if (!$video) {
            return ['status' => 'fail', 'message' => 'Failed to add video'];
        }

        return ['status' => 'success', 'video' => $video];
    }
}
