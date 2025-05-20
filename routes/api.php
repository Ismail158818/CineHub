<?php
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\TypeApiController;
use App\Http\Controllers\Api\FilmApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SeriesApiController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::controller(TypeApiController::class)->group(function () {
        Route::post('add-type', 'add_type');
    });

    Route::controller(FilmApiController::class)->group(function () {
        Route::post('add-film', 'add_film');
        Route::get('show-all-films', 'show_all_films');
        Route::post('delete-film', 'delete_film');
        Route::post('edit-film', 'edit_film');
        Route::post('add-to-favorites', 'add_to_favorites');
        Route::get('show-favorites-films', 'show_favorites_films');
        Route::post('search-film', 'search');
    });


    Route::controller(SeriesApiController::class)->group(function () {
        Route::post('add-series', 'add_series');
        Route::get('show-all-series', 'show_all_series');
        Route::post('delete-series', 'delete_series');
        Route::post('edit-series', 'edit_series');
        Route::post('add-to-favorites-series', 'add_to_favorites');
        Route::get('show-favorites-series', 'show_favorites_series');
        Route::post('search-series', 'search');
        Route::post('add-video', 'add_video');  // مسار لإضافة الفيديوهات
    });
    
    
});
Route::controller(AuthApiController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});
