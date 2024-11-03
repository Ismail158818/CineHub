<?php
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\TypeApiController;
use App\Http\Controllers\Api\FilmApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    });

    
});
Route::controller(AuthApiController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
})->middleware('auth:sactumyy');
