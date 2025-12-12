<?php

use App\Http\Controllers\AccessoriesQuotationController;
use App\Http\Controllers\AccessoriesReceivedController;
use App\Http\Controllers\AccessoriesStockController;
use App\Http\Controllers\DyedFactoryController;
use App\Http\Controllers\DyedQuotationController;
use App\Http\Controllers\DyeingFactroyController;
use App\Http\Controllers\DyeingQuotationController;
use App\Http\Controllers\GarmentsFactroyController;
use App\Http\Controllers\NettingFactroyController;
use App\Http\Controllers\NettingQuotationController;
use App\Http\Controllers\NettingReceivedGarmentsController;
use App\Http\Controllers\NettingStoreStockController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\orderDetailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StyleController;
use App\Http\Controllers\YarnFactroyController;
use App\Http\Controllers\YarnQuotationController;
use App\Http\Controllers\YarnReceivedController;
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
    Route::name('order.details')->controller(OrderDetailController::class)->group(function () {

        Route::get('get-style-by-po-order-detail/{po_number}', 'getStyleByPo');
    });

    Route::resource('yarnquotation', YarnQuotationController::class);
    Route::resource('yarnreceived', YarnReceivedController::class);
    Route::get('details-view', [YarnReceivedController::class, 'detailsView'])->name('yarnreceived.detail.view');
    // Route::get('get-yarn-quotation-by-po/{po_number}', [YarnReceivedController::class, 'getYarnStyleByPo']);
    Route::get('distribute-yarn', [YarnReceivedController::class, 'yarnDistribute'])->name('yarnreceived.distribute');
    Route::post('distribute-yarn', [YarnReceivedController::class, 'yarnDistributeStore'])->name('yarnreceived.distribute.store');

    Route::resource('dyedquotation', DyedQuotationController::class);
    Route::get('distribute-yarn-dyed/{dyedquotation}', [DyedQuotationController::class, 'yarnDyedDistribute'])->name('yarn.dyed.distribute');
    Route::post('distribute-yarn-dyed', [DyedQuotationController::class, 'yarnDyedDistributeStore'])->name('yarn.dyed.distribute.store');

    //dyed yarn stock
    Route::resource('yarnstorestock', YarnStoreStockController::class);
    Route::get('use-yarn-stock/{id}', [YarnStoreStockController::class, 'useYarnStockCreate'])->name('use.yarn.stock.create');
    Route::post('use-yarn-stock', [YarnStoreStockController::class, 'useYarnStock'])->name('use.yarn.stock');

    Route::resource('nettingquotation', NettingQuotationController::class);
    Route::get('distribute-knit/{nettingquotation}', [NettingQuotationController::class, 'knitDistribute'])->name('knit.distribute');
    Route::post('distribute-knit', [NettingQuotationController::class, 'knitDistributeStore'])->name('knit.distribute.store');

    Route::resource('nettingstorestock', NettingStoreStockController::class);
    Route::get('knit-stock-distribute-create/{id}', [NettingStoreStockController::class, 'knitDistributeCreate'])->name('nettingstorestock.knit.distribute.create');
    Route::post('knit-stock-distribute', [NettingStoreStockController::class, 'knitDistributeStock'])->name('nettingstorestock.knit.distribute.stock.store');
    Route::controller(NettingStoreStockController::class)->name('dyeingknitstorestock.')->group(function () {
        Route::get('dyeing-knit-stock', 'dyeingKnitStock')->name('index');
        // Route::get('dyeing-knit-stock-create', 'create')->name('create');
        Route::get('dyeing-knit-stock-show/{nettingstorestock}', 'show')->name('show');
        Route::get('dyeing-knit-stock-edit/{nettingstorestock}', 'edit')->name('edit');
    });

    Route::resource('dyeingquotation', DyeingQuotationController::class);
    Route::get('distribute-dyeing/{dyeingquotation}', [DyeingQuotationController::class, 'dyeingDistribute'])->name('dyeing.distribute');
    Route::post('distribute-dyeing', [DyeingQuotationController::class, 'dyeingDistributeStore'])->name('dyeing.distribute.store');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('style', StyleController::class)->only(['index', 'edit', 'update', 'store', 'destroy']);
        Route::resource('yarnfactroy', YarnFactroyController::class)->only(['index', 'edit', 'update', 'store', 'destroy']);
        Route::resource('nettingfactroy', NettingFactroyController::class)->only(['index', 'edit', 'update', 'store', 'destroy']);
        Route::resource('dyeingfactroy', DyeingFactroyController::class)->only(['index', 'edit', 'update', 'store', 'destroy']);
        Route::resource('garmentsfactroy', GarmentsFactroyController::class)->only(['index', 'edit', 'update', 'store', 'destroy']);
        Route::resource('dyedfactory', DyedFactoryController::class)->only(['index', 'edit', 'update', 'store', 'destroy']);
        Route::resource('store', StoreController::class)->only(['index', 'edit', 'update', 'store', 'destroy']);
    });

    Route::resource('accessoriesquotation', AccessoriesQuotationController::class);
    Route::resource('accessoriesreceived', AccessoriesReceivedController::class);
    Route::resource('accessoriesstock', AccessoriesStockController::class);

    Route::get('nettingreceivedgarments', [NettingReceivedGarmentsController::class, 'index'])->name('nettingreceivedgarments.index');
    Route::get('nettingreceivedgarments/{id}', [NettingReceivedGarmentsController::class, 'edit'])->name('nettingreceivedgarments.edit');
    Route::put('nettingreceivedgarments', [NettingReceivedGarmentsController::class, 'update'])->name('nettingreceivedgarments.update');
    Route::get('nettingreceivedgarments/show/{id}', [NettingReceivedGarmentsController::class, 'show'])->name('nettingreceivedgarments.show');
    Route::delete('nettingreceivedgarments/delete/{id}', [NettingReceivedGarmentsController::class, 'destroy'])->name('nettingreceivedgarments.destroy');

    //
    //
    // Route::controller(YarnStoreStockController::class)->name('dyedyarnstock.')->group(function () {
    //     Route::get('dyed-yarn-stock', 'dyedYarnStock')->name('index');
    //     Route::get('dyed-yarn-stock-create', 'create')->name('create');
    //     Route::get('dyed-yarn-stock-show/{yarnstorestock}', 'show')->name('show');
    //     Route::get('dyed-yarn-stock-edit/{yarnstorestock}', 'edit')->name('edit');
    // });

    // Route::resource('yarnreceiveddyed', YarnReceivedDyedController::class);

    // Route::resource('nettingreceived', NettingReceivedController::class);

    // Route::resource('nettingstorestock', NettingStoreStockController::class);

    // Route::resource('dyeingreceived', DyeingReceivedController::class);

    // Route::get('get-all-dyeing-factory', [DyeingFactroyController::class, 'showAll']);
    // Route::get('get-all-dyed-factory', [DyedFactoryController::class, 'showAll']);
    // Route::get('get-all-garments-factory', [GarmentsFactroyController::class, 'showAll']);
    // Route::get('get-yarn-style-by-po-dyed/{po_number}', [DyedQuotationController::class, 'getYarnStyleByPo']);
    // Route::get('get-yarn-style-by-po/{po_number}', [NettingQuotationController::class, 'getYarnStyleByPo']);
    // Route::get('get-netting-order/{po_number}', [DyeingQuotationController::class, 'getNetting']);

    // Route::get('get-yarn-quotation-by-po-dyed/{po_number}', [YarnReceivedDyedController::class, 'getYarnStyleByPo']);
    // Route::get('get-netting-quotation-by-po/{po_number}', [NettingReceivedController::class, 'getNettingStyleByPo']);
    // Route::get('get-dyeing-quotation-by-po/{po_number}', [DyeingReceivedController::class, 'getDyeingStyleByPo']);
    // Route::get('get-accessories-quotation-by-po/{po_number}', [AccessoriesReceivedController::class, 'getAccessoriesStyleByPo']);

    // Route::get('get-recevied-total-yarn-by-style', [YarnReceivedController::class, 'getReceviedTotalYarnByStyle']);
    // Route::get('get-recevied-total-yarn-by-style-dyed', [YarnReceivedDyedController::class, 'getReceviedTotalYarnByStyle']);
    // Route::get('get-recevied-total-netting-by-style', [NettingReceivedController::class, 'getReceviedTotalNettingByStyle']);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
