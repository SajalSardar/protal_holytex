<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StyleController;
use App\Http\Controllers\BuyYarnController;
use App\Http\Controllers\NettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DyeingOrderController;
use App\Http\Controllers\orderDetailController;
use App\Http\Controllers\YarnFactroyController;
use App\Http\Controllers\DyeingFactroyController;
use App\Http\Controllers\NettingFactroyController;
use App\Http\Controllers\GarmentsFactroyController;

Route::get('/test', function () {
    return view('dashboard-eliment.products-list');
});

// Route::get('/', function () {
//     return view('login');
// });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('order', OrderController::class);
    Route::resource('buyyarn', BuyYarnController::class);
    Route::resource('netting', NettingController::class);
    Route::resource('dyeingorder', DyeingOrderController::class);

    Route::name('order.details')->controller(OrderDetailController::class)->group(function () {

        Route::get('get-style-by-po-order-detail/{po_number}', 'getStyleByPo');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('style', StyleController::class)->only(['index', 'edit', 'update', 'store']);
        Route::resource('yarnfactroy', YarnFactroyController::class)->only(['index', 'edit', 'update', 'store']);
        Route::resource('nettingfactroy', NettingFactroyController::class)->only(['index', 'edit', 'update', 'store']);
        Route::resource('dyeingfactroy', DyeingFactroyController::class)->only(['index', 'edit', 'update', 'store']);
        Route::resource('garmentsfactroy', GarmentsFactroyController::class)->only(['index', 'edit', 'update', 'store']);
    });
    Route::get('get-all-dyeing-factory', [DyeingFactroyController::class, 'showAll']);
    Route::get('get-all-garments-factory', [GarmentsFactroyController::class, 'showAll']);
    Route::get('get-yarn-style-by-po/{po_number}', [NettingController::class, 'getYarnStyleByPo']);
    Route::get('get-netting-order/{po_number}', [DyeingOrderController::class, 'getNetting']);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
