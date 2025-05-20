<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Fun_Services\Fun_Film;
use Illuminate\Http\Request;

class FilmApiController extends Controller
{
    public function add_film(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer',
            'link' => 'required|url',
            'cast' => 'required|array',
            'rating' => 'required|numeric|min:1|max:10',
            'type_id' => 'required|array',
            'image' => 'sometimes|file|image|max:2048'
        ]);

        $add = new Fun_Film();
        $data = $add->add_film_services($validated);

        if ($data) {
            return response()->json(['status' => 'success', 'message' => 'Film added successfully']);
        }

        return response()->json(['status' => 'fail', 'message' => 'Failed to add film']);
    }

    public function remove_film(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $remove = new Fun_Film();
        $result = $remove->delete_film_services($validated);

        return response()->json(['status' => $result ? 'success' : 'fail']);
    }

    public function search_film(Request $request)
    {
        // قم بتعريف دالة البحث حسب احتياجاتك
    }

    public function add_to_favorites(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|integer',
        ]);

        $add = new Fun_Film();
        $result = $add->add_to_favorites_services($validated);

        return response()->json($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function show_all_films()
    {
        $funFilm = new Fun_Film();
        $films = $funFilm->show_all_films_services();

        return response()->json(['status' => 'success', 'films' => $films]);
    }
}
