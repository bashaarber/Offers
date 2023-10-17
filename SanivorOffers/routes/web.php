<?php

use App\Http\Controllers\ElementController;
use App\Http\Controllers\GroupElementController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\Organigram;
use App\Http\Controllers\OrganigramController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth','role:admin')->group(function () {
    Route::resource('material', MaterialController::class)->except(['index']);
    Route::get('/material', [MaterialController::class, 'index'])->name('material.index');
});

Route::middleware('auth','role:admin')->group(function () {
    Route::resource('element', ElementController::class)->except(['index']);
    Route::get('/element', [ElementController::class, 'index'])->name('element.index');
});

Route::middleware('auth','role:admin')->group(function () {
    Route::resource('group_element', GroupElementController::class)->except(['index']);
    Route::get('/group_element', [GroupElementController::class, 'index'])->name('group_element.index');
});

Route::middleware('auth','role:admin')->group(function () {
    Route::resource('organigram', OrganigramController::class)->except(['index']);
    Route::get('/organigram', [OrganigramController::class, 'index'])->name('organigram.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


