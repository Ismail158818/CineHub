<?php

namespace App\Http\Controllers\Fun_Services;

use App\Models\Series;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Fun_Series
{
    public function add_series_services($validated)
    {
        try {
            $imagePath = 'default_image_path.jpg';
            if (isset($validated['image'])) {
                $imagePath = $validated['image']->store('media', 'public');
            }

            $add_series = Series::create([
                'name' => $validated['name'],
                'date' => $validated['date'],
                'cast' => $validated['cast'],
                'description' => $validated['description'],
                'rating' => $validated['rating'],
                'image' => $imagePath,
                'type_id' => $validated['type_id']
            ]);

            if (!$add_series) {
                return false;
            }

            $add_series->types()->attach($validated['type_id']);
            return true;

        } catch (\Exception $e) {
            Log::error('Error in add_series_services: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $validated,
            ]);
            return false;
        }
    }

    public function show_all_series_services()
    {
        try {
            return Series::with('videos')->paginate(12);
        } catch (\Exception $e) {
            Log::error('Error in show_all_series_services: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return [];
        }
    }

    public function delete_series_services($validated)
    {
        try {
            $series = Series::find($validated['id']);

            if ($series) {
                $series->delete();
                return true;
            }
            return false;

        } catch (\Exception $e) {
            Log::error('Error in delete_series_services: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $validated,
            ]);
            return false;
        }
    }

    public function edit_series_services($validated)
    {
        try {
            $series = Series::find($validated['id']);

            if (!$series) {
                return false;
            }

            if (isset($validated['image'])) {
                if ($series->image && Storage::disk('public')->exists($series->image)) {
                    Storage::disk('public')->delete($series->image);
                }
                $validated['image'] = $validated['image']->store('media', 'public');
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

        } catch (\Exception $e) {
            Log::error('Error in edit_series_services: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $validated,
            ]);
            return false;
        }
    }

    public function add_to_favorites_services($validated)
    {
        try {
            $user = User::find(auth()->id());
            $series = Series::find($validated['series_id']);
        
            if ($user->series()->where('series_id', $series->id)->exists()) {
                return ['status' => 'fail', 'message' => 'Series is already in favorites'];
            }

            $user->series()->attach($series->id);

            return ['status' => 'success', 'message' => 'Series added to favorites successfully'];

        } catch (\Exception $e) {
            Log::error('Error in add_to_favorites_services: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $validated,
            ]);
            return ['status' => 'fail', 'message' => 'An error occurred while adding to favorites'];
        }
    }

    public function remove_from_favorites_services($validated)
    {
        try {
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

        } catch (\Exception $e) {
            Log::error('Error in remove_from_favorites_services: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $validated,
            ]);
            return ['status' => 'fail', 'message' => 'An error occurred while removing from favorites'];
        }
    }

    public function search_series_services($request)
    {
        try {
            if (!$request->name) {
                return ['status' => 'fail', 'message' => 'Please enter a name to search'];
            }

            $series = Series::where('name', 'like', '%' . $request->name . '%')
                ->orWhere('cast', 'like', '%' . $request->name . '%')
                ->orWhereHas('types', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->name . '%');
                })
                ->get();

            if ($series->isNotEmpty()) {
                return ['status' => 'success', 'series' => $series];
            }

            return ['status' => 'fail', 'message' => 'No series found'];

        } catch (\Exception $e) {
            Log::error('Error in search_series_services: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
            return ['status' => 'fail', 'message' => 'An error occurred while searching'];
        }
    }

    public function add_video_services($validated)
    {
        try {
            $video = Video::create($validated);
            if ($video) {
                return ['status' => 'success', 'message' => 'Video added successfully'];
            }
            return ['status' => 'fail', 'message' => 'Video not added'];
        } catch (\Exception $e) {
            Log::error('Error in add_video_services: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $validated,
            ]);
            return ['status' => 'fail', 'message' => 'An error occurred while adding video'];
        }
    }
}