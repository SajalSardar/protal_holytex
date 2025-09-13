<?php

namespace App\Http\Controllers;

use App\Models\YarnLoss;
use App\Models\YarnQuotation;
use App\Models\YarnReceived;
use App\Models\YarnStoreStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YarnStoreStockController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $yarnStocks = YarnStoreStock::with('yarnQty')->orderBy('id', 'desc')->get();
        $yearnsQot  = YarnQuotation::with('nettingFactory', )
            ->withSum('yarnReceivedFromStock', 'quantity')
            ->where('status', 'approved')->get();
        // $yearnsQotPo = $yearnsQot->pluck('po_number')->unique();
        // return $yearnsQot;
        return view('yarn_store.index', compact('yarnStocks', 'yearnsQot'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        // return $request;
        $request->validate([
            'stock_id'          => "required",
            'yarn_quotation_id' => "required",
            'challan_file'      => "nullable|max:512|image",
        ]);

        $yarnStock = YarnStoreStock::findOrFail($request->stock_id);

        if ($yarnStock->quantity <= 0) {
            toastr('Stock Quantity is 0', 'info');
            return back();
        }

        $path = null;
        if ($request->hasFile('challan_file')) {
            $path = $request->file('challan_file')->store('yarn_received_challan', 'public');
        }
        $successMessageStatus = false;

        $yearnQut = YarnQuotation::withSum('yarnReceived', 'quantity')
            ->withSum('yarnLoss', 'quantity')
            ->withSum('storeStock', 'quantity')
            ->where('id', $request->yarn_quotation_id)
            ->first();

        $totalYearnQut = $yearnQut->quantity + $yearnQut->from_stock_quantity;

        $yearnReceivedTotal = $yearnQut->yarn_received_sum_quantity + $yearnQut->yarn_loss_sum_quantity + $yearnQut->store_stock_sum_quantity;
        $yarnRec            = $request->input_yarn ?? 0;
        $yarnLossRec        = $request->input_loss ?? 0;
        $newReceived        = $yarnRec + $yarnLossRec;
        $total              = $newReceived + $yearnReceivedTotal;

        // Save Yarn Received
        if ($request->input_yarn > 0 && $totalYearnQut > $yearnReceivedTotal && $totalYearnQut >= $total) {

            YarnReceived::create([
                'yarn_quotation_id' => $request->yarn_quotation_id,
                'po_number'         => $request->po_number,
                'style'             => $request->style,
                'quantity'          => $request->input_yarn,
                'lot_number'        => $request->loat_no,
                'bag_count'         => $request->bag_count,
                'challan_date'      => $request->challan_date,
                'challan_number'    => $request->challan_number,
                'vehicle_number'    => $request->vehicle_number,
                'received_date'     => $request->received_date,
                'received_by'       => Auth::id(),
                'remarks'           => $request->remarks,
                'is_stock_received' => 'Yes',
                'stock_id'          => $request->stock_id,
                'challan_file'      => $path,
            ]);
            $successMessageStatus = true;
        }

        // Save Yarn Loss
        if ($request->input_loss > 0 && $totalYearnQut > $yearnReceivedTotal && $totalYearnQut >= $total) {

            YarnLoss::create([
                'yarn_quotation_id' => $request->yarn_quotation_id,
                'is_stock_received' => 'Yes',
                'stock_id'          => $request->stock_id,
                'quantity'          => $request->input_loss,
                'created_by'        => Auth::id(),
            ]);
            $successMessageStatus = true;
        }

        // Update quotation if fully received
        if ((float) $totalYearnQut === (float) $total) {
            $yearnQut->update([
                'status'        => 'recevied',
                'delivery_date' => $request->received_date,
                'updated_by'    => Auth::id(),
            ]);
        }

        // Update stock
        $yarnStock->decrement('quantity', $newReceived);

        // Response
        if ($successMessageStatus) {
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
