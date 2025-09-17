<?php

use App\Http\Controllers\AccessoriesQuotationController;
use App\Http\Controllers\AccessoriesReceivedController;
use App\Http\Controllers\AccessoriesStockController;
use App\Http\Controllers\DyedFactoryController;
use App\Http\Controllers\DyedQuotationController;
use App\Http\Controllers\DyeingFactroyController;
use App\Http\Controllers\DyeingQuotationController;
use App\Http\Controllers\DyeingReceivedController;
use App\Http\Controllers\GarmentsFactroyController;
use App\Http\Controllers\NettingFactroyController;
use App\Http\Controllers\NettingQuotationController;
use App\Http\Controllers\NettingReceivedController;
use App\Http\Controllers\NettingStoreStockController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\orderDetailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StyleController;
use App\Http\Controllers\YarnFactroyController;
use App\Http\Controllers\YarnQuotationController;
use App\Http\Controllers\YarnReceivedController;
use App\Http\Controllers\YarnReceivedDyedController;
use App\Http\Controllers\YarnStoreStockController;
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
    Route::get('order-distribute/{order}', [OrderController::class, 'distributeOrder'])->name('order.distribute');
    Route::post('order-distribute', [OrderController::class, 'deliveredOrder'])->name('order.delivered');
    Route::post('order-update-status', [OrderController::class, 'updateStatus'])->name('order.update.status');
    Route::name('order.details')->controller(OrderDetailController::class)->group(function () {

        Route::get('get-style-by-po-order-detail/{po_number}', 'getStyleByPo');
    });

    Route::resource('yarnquotation', YarnQuotationController::class);
    Route::post('yarn-qty-status-update', [YarnQuotationController::class, 'yarnQtyStatusUpdate'])->name('yarn.qty.update.status');
    Route::resource('yarnreceived', YarnReceivedController::class);
    Route::resource('yarnstorestock', YarnStoreStockController::class);

    Route::resource('dyedquotation', DyedQuotationController::class);
    Route::post('dyed-qty-status-update', [DyedQuotationController::class, 'dyedQtyStatusUpdate'])->name('dyed.qty.update.status');
    Route::resource('yarnreceiveddyed', YarnReceivedDyedController::class);

    Route::resource('nettingquotation', NettingQuotationController::class);
    Route::post('netting-qty-status-update', [NettingQuotationController::class, 'nettingQtyStatusUpdate'])->name('netting.qty.update.status');
    Route::resource('nettingreceived', NettingReceivedController::class);
    Route::resource('nettingstorestock', NettingStoreStockController::class);

    Route::resource('dyeingquotation', DyeingQuotationController::class);
    Route::post('dyeing-qty-status-update', [DyeingQuotationController::class, 'dyeingQtyStatusUpdate'])->name('dyeing.qty.update.status');
    Route::resource('dyeingreceived', DyeingReceivedController::class);

    Route::resource('accessoriesquotation', AccessoriesQuotationController::class);
    Route::post('acc-qty-status-update', [AccessoriesQuotationController::class, 'accQtyStatusUpdate'])->name('acc.qty.update.status');
    Route::resource('accessoriesreceived', AccessoriesReceivedController::class);
    Route::resource('accessoriesstock', AccessoriesStockController::class);

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('style', StyleController::class)->only(['index', 'edit', 'update', 'store']);
        Route::resource('yarnfactroy', YarnFactroyController::class)->only(['index', 'edit', 'update', 'store']);
        Route::resource('nettingfactroy', NettingFactroyController::class)->only(['index', 'edit', 'update', 'store']);
        Route::resource('dyeingfactroy', DyeingFactroyController::class)->only(['index', 'edit', 'update', 'store']);
        Route::resource('garmentsfactroy', GarmentsFactroyController::class)->only(['index', 'edit', 'update', 'store']);
        Route::resource('dyedfactory', DyedFactoryController::class)->only(['index', 'edit', 'update', 'store']);
    });
    Route::get('get-all-dyeing-factory', [DyeingFactroyController::class, 'showAll']);
    Route::get('get-all-dyed-factory', [DyedFactoryController::class, 'showAll']);
    Route::get('get-all-garments-factory', [GarmentsFactroyController::class, 'showAll']);
    Route::get('get-yarn-style-by-po-dyed/{po_number}', [DyedQuotationController::class, 'getYarnStyleByPo']);
    Route::get('get-yarn-style-by-po/{po_number}', [NettingQuotationController::class, 'getYarnStyleByPo']);
    Route::get('get-netting-order/{po_number}', [DyeingQuotationController::class, 'getNetting']);
    Route::get('get-yarn-quotation-by-po/{po_number}', [YarnReceivedController::class, 'getYarnStyleByPo']);
    Route::get('get-yarn-quotation-by-po-dyed/{po_number}', [YarnReceivedDyedController::class, 'getYarnStyleByPo']);
    Route::get('get-netting-quotation-by-po/{po_number}', [NettingReceivedController::class, 'getNettingStyleByPo']);
    Route::get('get-dyeing-quotation-by-po/{po_number}', [DyeingReceivedController::class, 'getDyeingStyleByPo']);
    Route::get('get-accessories-quotation-by-po/{po_number}', [AccessoriesReceivedController::class, 'getAccessoriesStyleByPo']);

    Route::get('get-recevied-total-yarn-by-style', [YarnReceivedController::class, 'getReceviedTotalYarnByStyle']);
    Route::get('get-recevied-total-yarn-by-style-dyed', [YarnReceivedDyedController::class, 'getReceviedTotalYarnByStyle']);
    Route::get('get-recevied-total-netting-by-style', [NettingReceivedController::class, 'getReceviedTotalNettingByStyle']);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
