<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\TypeApiController;
use App\Http\Controllers\Api\FilmApiController;
use App\Http\Controllers\Api\SeriesApiController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Returns the authenticated user's data
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Admin-only routes
    Route::middleware(CheckAdmin::class)->group(function () {

        // Type Management
        Route::controller(TypeApiController::class)->group(function () {
            Route::post('add-type', 'add_type');
        });

        // Film Management
        Route::controller(FilmApiController::class)->group(function () {
            Route::post('add-film', 'add_film');
            Route::post('delete-film', 'delete_film');
            Route::post('edit-film', 'edit_film');
        });

        // Series Management
        Route::controller(SeriesApiController::class)->group(function () {
            Route::post('add-series', 'add_series');
            Route::post('delete-series', 'delete_series');
            Route::post('edit-series', 'edit_series');
            Route::post('upload-video', 'upload_video');  
        });

    }); // End Admin Group

    // Authenticated user (non-admin) routes could be added here in future if needed


Route::controller(FilmApiController::class)->group(function () {
    Route::get('show-all-films', 'show_all_films');
    Route::post('add-to-favorites', 'add_to_favorites');
    Route::get('show-favorites-films', 'show_favorites_films');
    Route::post('search', 'search'); // Consider renaming this to search-films for clarity
});

// Series-related routes
Route::controller(SeriesApiController::class)->group(function () {
    Route::get('show-all-series', 'show_all_series');
    Route::post('remove-from-favorites', 'remove_from_favorites');
    Route::post('add-to-favorites-series', 'add_to_favorites');
    Route::post('show-favorites-series', 'show_favorites_series');
    // Note: 'show-all-series' is defined twice above, remove one for cleanup
});
});
// Auth routes (registration & login)
Route::controller(AuthApiController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});
