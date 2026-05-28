<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestTestController;
use App\Http\Controllers\DiggingDeeperController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::prefix('digging_deeper')->name('digging_deeper.')->group(function () {
    Route::get('collections', [DiggingDeeperController::class, 'collections'])->name('collections');
});

Route::get('rest', [RestTestController::class, 'index'])->name('restTest.index');
Route::post('rest', [RestTestController::class, 'store'])->name('restTest.store');
Route::get('rest/{rest}', [RestTestController::class, 'show'])->name('restTest.show');
Route::put('rest/{rest}', [RestTestController::class, 'update'])->name('restTest.update');
Route::delete('rest/{rest}', [RestTestController::class, 'destroy'])->name('restTest.destroy');
