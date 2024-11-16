<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Fun_Services\Fun_Series;
use App\Http\Requests\SeriesRequest;
use Illuminate\Http\Request;
use App\Models\Series;
use App\Models\User;

class SeriesApiController extends Controller
{
    public function add_series(SeriesRequest $request)
    {
       $add = new Fun_Series();
       $data = $add->add_series_services($request->validated());
       if ($data) {
           return response()->json(['status' => 'success', 'series' => $data]);
       }
       return response()->json(['status' => 'fail']);
    }

    public function remove_from_favorites(Request $request)
    {
        $validated = $request->validate([
            'series_id' => 'sometimes|integer',
        ]);

        $remove = new Fun_Series();
        $result = $remove->remove_from_favorites_services($validated);

        return response()->json($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function search(Request $request)
    {
        $search = new Fun_Series();
        $result = $search->search_series_services($request);

        return response()->json($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function add_to_favorites(Request $request)
    {
        $validated = $request->validate([
            'series_id' => 'required|integer',
        ]);

        $add = new Fun_Series();
        $result = $add->add_to_favorites_services($validated);

        return response()->json($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function add_video(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'duration' => 'required|integer',
            'series_id' => 'required|integer|exists:series,id'
        ]);

        $add = new Fun_Series();
        $result = $add->add_video_services($validated);

        return response()->json($result, $result['status'] === 'success' ? 200 : 400);
    }
}
