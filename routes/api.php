<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Blog\Admin\CategoryController;
use App\Http\Controllers\Api\Blog\Admin\PostController;

Route::prefix('admin/blog')->name('admin.blog.')->group(function () {
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::prefix('admin/blog')->group(function () {
    Route::apiResource('posts', PostController::class)
        ->except(['show'])
        ->names('blog.admin.posts');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\DiggingDeeperController;

//BlogPost
Route::apiResource('posts', PostController::class)
->except(['show'])                          //не робити маршрут для метода show
->names('blog.admin.posts');

Route::get('process-video', [DiggingDeeperController::class, 'processVideo'])
    ->name('digging_deeper.processVideo');
    
Route::get('prepare-catalog', [DiggingDeeperController::class, 'prepareCatalog'])
    ->name('digging_deeper.prepareCatalog'); 

