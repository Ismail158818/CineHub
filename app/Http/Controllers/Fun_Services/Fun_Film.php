<?php

namespace App\Http\Controllers\Fun_Services;

use Illuminate\Support\Facades\DB;
use App\Models\Film;
use App\Models\Series;
use App\Models\User;

class Fun_Film
{
    public function add_film_services($validated)
    {
        $existed_film = Film::where('name', $validated['name'])->first();
        if ($existed_film) {
            return ['status' => 'fail', 'message' => 'Film already exists'];
        }

        $imagePath = 'default_image_path.jpg';
        if (isset($validated['image'])) {
            $imagePath = $validated['image']->store('media', 'public');
        }

        $film = Film::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'duration' => $validated['duration'],
            'link' => $validated['link'],
            'cast' => implode(',', $validated['cast']),
            'rating' => $validated['rating'],
            'image' => $imagePath,
        ]);

        if (!$film) {
            return ['status' => 'fail', 'message' => 'Film not added'];
        }

        foreach ($validated['type_id'] as $type_id) {
            $film->types()->attach($type_id);
        }

        return ['status' => 'success', 'message' => 'Film added successfully'];
    }

    public function show_all_films_services()
    {
        $films = Film::all();
        return ['status' => 'success', 'films' => $films];
    }

    public function delete_film_services($request)
    {
        $film = Film::find($request['id']);
        if (!$film) {
            return ['status' => 'fail', 'message' => 'Film not found'];
        }

        $film->delete();
        return ['status' => 'success', 'message' => 'Film deleted successfully'];
    }

    public function edit_film_services($validated)
    {
        $film = Film::find($validated['id']);
        if (!$film) {
            return ['status' => 'fail', 'message' => 'Film not found'];
        }

        $status = $film->update(array_filter([
            'name' => $validated['name'] ?? $film->name,
            'description' => $validated['description'] ?? $film->description,
            'duration' => $validated['duration'] ?? $film->duration,
            'image' => $validated['image'] ?? $film->image,
            'link' => $validated['link'] ?? $film->link,
            'cast' => isset($validated['cast']) ? implode(',', $validated['cast']) : $film->cast,
            'rating' => $validated['rating'] ?? $film->rating
        ]));

        return $status
            ? ['status' => 'success', 'message' => 'Film updated successfully']
            : ['status' => 'fail', 'message' => 'Failed to update film'];
    }

    public function add_to_favorites_services($validated)
    {
        $user = User::find(auth()->id());
        $film = Film::find($validated['film_id']);

        if ($user->films()->where('film_id', $film->id)->exists()) {
            return ['status' => 'fail', 'message' => 'Film already in favorites'];
        }

        $user->films()->attach($film->id);

        return ['status' => 'success', 'message' => 'Film added to favorites'];
    }

    public function show_favorites_films_services()
    {
        $user = User::find(auth()->id());
        if (!$user) {
            return ['status' => 'fail', 'message' => 'User not found'];
        }

        return ['status' => 'success', 'films' => $user->films];
    }
    public function search_services($request)
{
       $serch_films = Film::where('name', 'like', '%' . $request['search'] . '%')
        ->get()
        ->map(function ($film) {
            return [
                'id' => $film->id,
                'name' => $film->name,
                'image' => $film->image, 
                'rating' => $film->rating,      
                'type' => 'film',
            ];
        });

    // البحث في المسلسلات
    $serch_serios = Series::where('name', 'like', '%' . $request['search'] . '%')
        ->get()
        ->map(function ($series) {
            return [
                'id' => $series->id,
                'name' => $series->name,
                'image' => $series->image,
                'rating' => $series->rating,   
                'type' => 'series',
            ];
        });

    $all_search = $serch_films->concat($serch_serios)->values(); // ✅ concat يحل مشكلة getKey()
    if ($all_search->isEmpty()) {
        return ['status' => 'fail', 'message' => 'No results found'];
    }
    return ['status' => 'success', 'The results' => $all_search];
}

}
