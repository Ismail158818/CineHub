<?php

namespace App\Http\Controllers\Api;

use Cloudinary\Cloudinary;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Fun_Services\Fun_Series;
use App\Http\Requests\SeriesRequest;
use Illuminate\Http\Request;
use App\Models\User;

class SeriesApiController extends Controller
{
    public function add_series(SeriesRequest $request)
    {
       $funSeries = new Fun_Series();
       $result = $funSeries->add_series_services($request->validated());

       if ($result) {
           return response()->json(['status' => 'success', 'message' => 'Series added successfully']);
       }

       return response()->json(['status' => 'fail', 'message' => 'Series not added']);
    }

    public function show_all_series()
    {
        $funSeries = new Fun_Series();
        $series = $funSeries->show_all_series_services();

        return response()->json(['status' => 'success', 'series' => $series]);
    }
    
    public function delete_series(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:series,id',
        ]);
        
        $funSeries = new Fun_Series();
        $result = $funSeries->delete_series_services($validated);

        return response()->json([
            'status' => $result ? 'success' : 'fail',
            'message' => $result ? 'Series deleted successfully' : 'Series not deleted'
        ]);
    }
   
    public function remove_from_favorites(Request $request)
    {
        $validated = $request->validate([
            'series_id' => 'required|integer|exists:series,id',
        ]);

        $funSeries = new Fun_Series();
        $result = $funSeries->remove_from_favorites_services($validated);

        return response()->json($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function search(Request $request)
    {
        $funSeries = new Fun_Series();
        $result = $funSeries->search_series_services($request);

        return response()->json($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function add_to_favorites(Request $request)
    {
        $validated = $request->validate([
            'series_id' => 'required|integer|exists:series,id',
        ]);

        $funSeries = new Fun_Series();
        $result = $funSeries->add_to_favorites_services($validated);

        return response()->json($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function show_favorites_series()
    {
        $user = User::find(auth()->id());
        if (!$user) {
            return response()->json(['status' => 'fail', 'message' => 'User not found'], 404);
        }

        return response()->json(['status' => 'success', 'series' => $user->series]);
    }

    public function add_video(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'duration' => 'required|integer',
            'series_id' => 'required|integer|exists:series,id'
        ]);

        $funSeries = new Fun_Series();
        $result = $funSeries->add_video_services($validated);

        return response()->json($result, $result['status'] === 'success' ? 200 : 400);
    }

    public function upload_video(Request $request)
    {
        $validated = $request->validate([
            'series_id' => 'required|integer|exists:series,id',
            'title' => 'required|string|max:255',
            'duration' => 'required|integer',
            'media' => 'required|file'
        ]);

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => 'dlxk5oeta',
                'api_key'    => '461546227219161',
                'api_secret' => 'OkeemKM-Tp1Th_iqukekgY-fIyo',
            ],
        ]);
    
        $uploadedFileUrl = $cloudinary->uploadApi()->upload(
            $request->file('media')->getRealPath(),
            ['folder' => 'series_videos', 'resource_type' => 'auto']
        );

        $video_url = $uploadedFileUrl['secure_url'];

        $videoData = [
            'series_id' => $validated['series_id'],
            'title' => $validated['title'],
            'url' => $video_url,
            'duration' => $validated['duration'],
        ];

        $funSeries = new Fun_Series();
        $result = $funSeries->add_video_services($videoData);

        return response()->json($result, $result['status'] === 'success' ? 200 : 400);
    }
}
