<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Fun_Services\Fun_Film;
use App\Http\Requests\FilmRequest;
use App\Models\Film;
use Illuminate\Http\Request;

class FilmApiController extends Controller
{
    public function add_film(FilmRequest $request)
    {
        $funFilm = new Fun_Film();
        $data = $funFilm->add_film_services($request);
        
        if ($data=='true') {
            return response()->json(['status' => 'success', 'data' => $data]);
        }
        return response()->json(['status' => 'fail']);
    }

    public function show_all_films()
    {
        $funFilm = new Fun_Film();
        $data = $funFilm->show_all_films_services();

        if ($data) {
            return response()->json(['status' => 'success', 'data' => $data]);
        }
        return response()->json(['status' => 'fail', 'message' => 'No films found']);
    }

    public function delete_film(Request $request)
    {
        $funFilm = new Fun_Film();
        $data = $funFilm->delete_film_services($request);

        if ($data) {
            return response()->json(['status' => 'success', 'message' => "Successfully deleted film {$request->name}"]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error deleting film']);
        }
    }

    public function edit_film(FilmRequest $request)
    {
        $funFilm = new Fun_Film();
        $data = $funFilm->edit_film_services($request);

        if ($data) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'fail']);
    }
}
