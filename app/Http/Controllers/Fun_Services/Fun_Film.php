<?php

namespace App\Http\Controllers\Fun_Services;
use Illuminate\Support\Facades\DB; // تأكد من إضافة هذا السطر
use App\Models\Film;

class Fun_Film
{
    public function add_film_services($validated)
    {
        $existed_film = Film::where('name', $validated['name'])->first();
        if ($existed_film) {
            return false;
        }
    
        $add_film = Film::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'duration' => $validated['duration'],
            'image' => $validated['image'],
            'link' => $validated['link'],
            'cast' => implode(',', $validated['cast']),
            'rating' => $validated['rating']
        ]);
    
        if (!$add_film) {
            return false;
        }
    
        $all_attached = true;
        foreach ($validated['type_id'] as $type_id) {
            $add_film->types()->attach($type_id);
            if (!$add_film->types()->find($type_id)) {
                $all_attached = false;
                break;
            }
        }
    
        if (!$all_attached) {
            $add_film->delete();
            return false;
        }
    
        return true;
    }
    

    public function show_all_films_services()
    {
        $films = Film::all();
        return $films;
    }

    public function delete_film_services($request)
    {
        $film = Film::where('name', $request->name)->first();

        if ($film) {
            $film->delete();
            return true;
        }
        return false;
    }

    public function edit_film_services($validated)
    {
        $film = Film::find($validated['id']);

        if (!$film) {
            return false;
        }

        $status = $film->update(array_filter([
            'name' => $validated['name'] ?? $film->name,
            'description' => $validated['description'] ?? $film->description,
            'duration' => $validated['duration'] ?? $film->duration,
            'image' => $validated['image'] ?? $film->image,
            'link' => $validated['link'] ?? $film->link,
            'cast' => $validated['cast'] ?? $film->cast,
            'rating' => $validated['rating'] ?? $film->rating
        ]));

        if ($status) {
            return true;
        }
        return false;
    }
}
