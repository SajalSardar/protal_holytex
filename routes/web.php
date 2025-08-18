<?php

use App\Http\Controllers\AccessoriesQuotationController;
use App\Http\Controllers\DyeingFactroyController;
use App\Http\Controllers\DyeingQuotationController;
use App\Http\Controllers\GarmentsFactroyController;
use App\Http\Controllers\NettingFactroyController;
use App\Http\Controllers\NettingQuotationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\orderDetailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StyleController;
use App\Http\Controllers\YarnFactroyController;
use App\Http\Controllers\YarnQuotationController;
use App\Http\Controllers\YarnReceivedController;
use Illuminate\Support\Facades\Route;

// Route::get('/test', function () {
//     return view('dashboard-eliment.products-list');
// });

// Route::get('/', function () {
//     return view('login');
// });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('order', OrderController::class);
    Route::resource('yarnquotation', YarnQuotationController::class);
    Route::resource('nettingquotation', NettingQuotationController::class);
    Route::resource('dyeingquotation', DyeingQuotationController::class);
    Route::resource('accessoriesquotation', AccessoriesQuotationController::class);
    Route::resource('yarnreceived', YarnReceivedController::class);

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
    Route::get('get-yarn-style-by-po/{po_number}', [NettingQuotationController::class, 'getYarnStyleByPo']);
    Route::get('get-netting-order/{po_number}', [DyeingQuotationController::class, 'getNetting']);
    Route::get('get-yarn-quotation-by-po/{po_number}', [YarnReceivedController::class, 'getYarnStyleByPo']);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
