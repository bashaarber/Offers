<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CoefficientController;
use App\Http\Controllers\ElementController;
use App\Http\Controllers\GroupElementController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialPieceController;
use App\Http\Controllers\OffertController;
use App\Http\Controllers\OrganigramController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
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

// Temporary emergency endpoint for production repair without shell access.
// Keep disabled by default and protect with a strong shared token.
Route::get('/_ops/repair-element-material', function (Request $request) {
    $enabled = filter_var(env('REPAIR_ENDPOINT_ENABLED', false), FILTER_VALIDATE_BOOLEAN);
    if (! $enabled) {
        abort(404);
    }

    $expectedToken = (string) env('REPAIR_ENDPOINT_TOKEN', '');
    $providedToken = (string) $request->query('token', '');
    if ($expectedToken === '' || $providedToken === '' || ! hash_equals($expectedToken, $providedToken)) {
        abort(403);
    }

    $before = DB::table('element_material')->count();
    Artisan::call('elements:check-materials', ['--repair' => true]);
    $after = DB::table('element_material')->count();

    return response()->json([
        'ok' => $after > 0,
        'before_count' => $before,
        'after_count' => $after,
        'output' => Artisan::output(),
    ]);
});

Route::post('/_ops/repair-element-material', function () {
    $enabled = filter_var(env('REPAIR_ENDPOINT_ENABLED', false), FILTER_VALIDATE_BOOLEAN);
    if (! $enabled) {
        abort(404);
    }

    $before = DB::table('element_material')->count();
    Artisan::call('elements:check-materials', ['--repair' => true]);
    $after = DB::table('element_material')->count();

    return redirect()
        ->route('dashboard')
        ->with('repair_status', [
            'before' => $before,
            'after' => $after,
            'ok' => $after > 0,
        ]);
})->middleware(['auth', 'role:admin'])->name('ops.repair-element-material');

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

    Route::resource('client', ClientController::class)->except(['index']);
    Route::get('/client', [ClientController::class, 'index'])->name('client.index');
    Route::post('/client/{id}/archive', [ClientController::class, 'archive'])->name('client.archive');
    Route::post('/client/{id}/unarchive', [ClientController::class, 'unarchive'])->name('client.unarchive');
});

Route::middleware('auth')->group(function () {
    Route::put('/coefficient/{id}', [CoefficientController::class, 'update'])->name('coefficient.update');
    Route::get('/coefficient', [CoefficientController::class, 'index'])->name('coefficient.index');
    Route::resource('offert', OffertController::class)->except(['index', 'copy']);
    Route::get('/offert', [OffertController::class, 'index'])->name('offert.index');
    Route::get('/offert/{offert_id}/copy', [OffertController::class, 'copy'])->name('offert.copy');
    Route::get('/pdf-export/{id}', [OffertController::class, 'exportPdf'])->name('offert.pdf');
    Route::post('/offert/{id}/auto-save', [OffertController::class, 'autoSave'])->name('offert.auto-save');
    Route::post('/offert/{id}/lock', [OffertController::class, 'lock'])->name('offert.lock');
    Route::post('/offert/{id}/unlock', [OffertController::class, 'unlock'])->name('offert.unlock');

    Route::resource('position', PositionController::class)->except('create');
    Route::get('/position/create/{index}', [PositionController::class, 'create'])->name('position.create');
    Route::post('/positions/update-order', [PositionController::class, 'updateOrder'])->name('position.updateOrder');
    Route::post('position/{id}/copy', [PositionController::class, 'copy'])->name('position.copy');
    Route::post('/position/auto-save', [PositionController::class, 'autoSave'])->name('position.auto-save');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Localization Route
Route::get("locale/{lange}", [LocalizationController::class, 'setLang']);

require __DIR__ . '/auth.php';
