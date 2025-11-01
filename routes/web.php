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
use App\Http\Controllers\StoreController;
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
    Route::resource('yarnreceived', YarnReceivedController::class);
    Route::get('get-yarn-quotation-by-po/{po_number}', [YarnReceivedController::class, 'getYarnStyleByPo']);

    Route::resource('yarnstorestock', YarnStoreStockController::class);
    Route::post('use-yarn-stock', [YarnStoreStockController::class, 'useYarnStock'])->name('use.yarn.stock');
    Route::controller(YarnStoreStockController::class)->name('dyedyarnstock.')->group(function () {
        Route::get('dyed-yarn-stock', 'dyedYarnStock')->name('index');
        Route::get('dyed-yarn-stock-create', 'create')->name('create');
        Route::get('dyed-yarn-stock-show/{yarnstorestock}', 'show')->name('show');
        Route::get('dyed-yarn-stock-edit/{yarnstorestock}', 'edit')->name('edit');
    });

    Route::resource('dyedquotation', DyedQuotationController::class);
    Route::resource('yarnreceiveddyed', YarnReceivedDyedController::class);

    Route::resource('nettingquotation', NettingQuotationController::class);
    Route::resource('nettingreceived', NettingReceivedController::class);

    Route::resource('nettingstorestock', NettingStoreStockController::class);
    Route::controller(NettingStoreStockController::class)->name('dyeingknitstorestock.')->group(function () {
        Route::get('dyeing-knit-stock', 'dyeingKnitStock')->name('index');
        Route::get('dyeing-knit-stock-create', 'create')->name('create');
        Route::get('dyeing-knit-stock-show/{nettingstorestock}', 'show')->name('show');
        Route::get('dyeing-knit-stock-edit/{nettingstorestock}', 'edit')->name('edit');
    });

    Route::resource('dyeingquotation', DyeingQuotationController::class);
    Route::resource('dyeingreceived', DyeingReceivedController::class);

    Route::resource('accessoriesquotation', AccessoriesQuotationController::class);
    Route::resource('accessoriesreceived', AccessoriesReceivedController::class);
    Route::resource('accessoriesstock', AccessoriesStockController::class);

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('style', StyleController::class)->only(['index', 'edit', 'update', 'store', 'destroy']);
        Route::resource('yarnfactroy', YarnFactroyController::class)->only(['index', 'edit', 'update', 'store', 'destroy']);
        Route::resource('nettingfactroy', NettingFactroyController::class)->only(['index', 'edit', 'update', 'store', 'destroy']);
        Route::resource('dyeingfactroy', DyeingFactroyController::class)->only(['index', 'edit', 'update', 'store', 'destroy']);
        Route::resource('garmentsfactroy', GarmentsFactroyController::class)->only(['index', 'edit', 'update', 'store', 'destroy']);
        Route::resource('dyedfactory', DyedFactoryController::class)->only(['index', 'edit', 'update', 'store', 'destroy']);
        Route::resource('store', StoreController::class)->only(['index', 'edit', 'update', 'store', 'destroy']);
    });
    Route::get('get-all-dyeing-factory', [DyeingFactroyController::class, 'showAll']);
    Route::get('get-all-dyed-factory', [DyedFactoryController::class, 'showAll']);
    Route::get('get-all-garments-factory', [GarmentsFactroyController::class, 'showAll']);
    Route::get('get-yarn-style-by-po-dyed/{po_number}', [DyedQuotationController::class, 'getYarnStyleByPo']);
    Route::get('get-yarn-style-by-po/{po_number}', [NettingQuotationController::class, 'getYarnStyleByPo']);
    Route::get('get-netting-order/{po_number}', [DyeingQuotationController::class, 'getNetting']);

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
