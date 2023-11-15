<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CoefficientController;
use App\Http\Controllers\ElementController;
use App\Http\Controllers\GroupElementController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialPieceController;
use App\Http\Controllers\OffertController;
use App\Http\Controllers\OrganigramController;
use App\Http\Controllers\PositionController;
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

Route::middleware('auth', 'role:admin')->group(function () {
    Route::resource('material_piece', MaterialPieceController::class)->except(['index']);
    Route::get('/material_piece', [MaterialPieceController::class, 'index'])->name('material_piece.index');

    Route::resource('material', MaterialController::class)->except(['index']);
    Route::get('/material', [MaterialController::class, 'index'])->name('material.index');

    Route::resource('element', ElementController::class)->except(['index']);
    Route::get('/element', [ElementController::class, 'index'])->name('element.index');

    Route::resource('group_element', GroupElementController::class)->except(['index']);
    Route::get('/group_element', [GroupElementController::class, 'index'])->name('group_element.index');

    Route::resource('organigram', OrganigramController::class)->except(['index']);
    Route::get('/organigram', [OrganigramController::class, 'index'])->name('organigram.index');

    Route::put('/coefficient/{id}', [CoefficientController::class, 'update'])->name('coefficient.update');
    Route::get('/coefficient', [CoefficientController::class, 'index'])->name('coefficient.index');

    Route::resource('client', ClientController::class)->except(['index']);
    Route::get('/client', [ClientController::class, 'index'])->name('client.index');
    
});

Route::middleware('auth')->group(function () {
    Route::resource('offert', OffertController::class)->except(['index', 'copy']);
    Route::get('/offert', [OffertController::class, 'index'])->name('offert.index');
    Route::get('/offert/{offert_id}/copy', [OffertController::class, 'copy'])->name('offert.copy');
    Route::get('/search_clients', [OffertController::class, 'searchClients']);
    Route::get('/pdf-export/{id}', [OffertController::class, 'exportPdf'])->name('offert.pdf');

    Route::resource('position', PositionController::class);
    Route::post('/positions/update-order', [PositionController::class, 'updateOrder'])->name('position.updateOrder');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
