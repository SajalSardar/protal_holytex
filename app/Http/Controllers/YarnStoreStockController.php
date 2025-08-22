<?php

namespace App\Http\Controllers;

use App\Models\YarnQuotation;
use App\Models\YarnStoreStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YarnStoreStockController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $yarnStocks = YarnStoreStock::get();
        return view('yarn_store.index', compact('yarnStocks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
        $yearns = YarnQuotation::select('po_number')->groupby('po_number')->get();
        return view('yarn_store.create', compact('yearns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'challan_file' => "nullable|max:512|image",
        ]);

        $path = null;
        if ($request->hasFile('challan_file')) {
            $path = $request->file('challan_file')->store('yarn_received_challan', 'public');
        }
        $successMessageStatus = 0;
        foreach ($request->items as $item) {
            $yearnQut = YarnQuotation::withSum('yarnReceived', 'quantity')
                ->withSum('yarnLoss', 'quantity')
                ->withSum('storeStock', 'quantity')
                ->where('id', $item['yarn_id'])
                ->first();

            $yearnReceivedTotal = $yearnQut->yarn_received_sum_quantity + $yearnQut->yarn_loss_sum_quantity + $yearnQut->store_stock_sum_quantity;
            $newReceived        = (array_key_exists('stock', $item) ? $item['stock'] : 0);
            $total              = $newReceived + $yearnReceivedTotal;

            if (array_key_exists('stock', $item) && $item['stock'] > 0 && $yearnQut->quantity > $yearnReceivedTotal && $yearnQut->quantity >= $total) {
                $successMessageStatus = 1;
                YarnStoreStock::create([
                    'yarn_quotation_id' => $item['yarn_id'],
                    'po_number'         => $request->po_number,
                    'style'             => $item['style'],
                    'quantity'          => $item['stock'],
                    'lot_number'        => $item['loat_no'],
                    'bag_count'         => $item['bag_count'],
                    'store_address'     => $item['store_address'],
                    'challan_date'      => $request->challan_date,
                    'challan_number'    => $request->challan_number,
                    'vehicle_number'    => $request->vehicle_number,
                    'received_date'     => $request->received_date,
                    'remarks'           => $item['remarks'],
                    'yarn_factory_id'   => $item['yarn_factory_id'],
                    'challan_file'      => $path,
                    'created_by'        => Auth::id(),
                ]);
            }

        }
        if ($successMessageStatus === 1) {
            toastr('Data Successfully Created!');
            return back();
        } else {
            toastr('No Input data found!', 'error');
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(YarnStoreStock $yarnStoreStock) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(YarnStoreStock $yarnStoreStock) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, YarnStoreStock $yarnStoreStock) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YarnStoreStock $yarnStoreStock) {
        //
    }
}
