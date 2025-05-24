<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Fun_Services\Fun_Film;
use App\Http\Requests\FilmRequest;
use Illuminate\Http\Request;

class FilmApiController extends Controller
{
    public function add_film(FilmRequest $request)
    {
        $result = (new Fun_Film())->add_film_services($request->validated());

        return response()->json($result, $result['status'] === 'success' ? 200 : 400);
    }

    public function delete_film(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:films,id',
        ]);

        $result = (new Fun_Film())->delete_film_services($validated);

        return response()->json($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function edit_film(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:films,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|string',
            'link' => 'nullable|url',
            'cast' => 'nullable|array',
            'rating' => 'nullable|numeric|min:1|max:10',
            'type_id' => 'nullable|array|exists:types,id',
            'image' => 'nullable|file|image|max:2048'
        ]);

        $result = (new Fun_Film())->edit_film_services($validated);

        return response()->json($result, $result['status'] === 'success' ? 200 : 400);
    }

    public function add_to_favorites(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|integer|exists:films,id',
        ]);

        $result = (new Fun_Film())->add_to_favorites_services($validated);

        return response()->json($result, $result['status'] === 'success' ? 200 : 400);
    }

    public function show_all_films()
    {
        $result = (new Fun_Film())->show_all_films_services();

        return response()->json($result, 200);
    }

    public function show_favorites_films()
    {
        $result = (new Fun_Film())->show_favorites_films_services();

        return response()->json($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'search' => 'required|string|max:255',
        ]);
        $result = (new Fun_Film())->search_services($validated);

        return response()->json($result, $result['status'] === 'success' ? 200 : 404);
    }
}
