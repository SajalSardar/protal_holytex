<?php

namespace App\Http\Controllers;

use App\Models\NettingFactroy;
use App\Models\NettingQuotation;
use App\Models\OrderDetail;
use App\Models\Store;
use App\Models\YarnLoss;
use App\Models\YarnStoreStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YarnStoreStockController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $yarnStocks = YarnStoreStock::with('storeDetails')
        ->withSum('useStock', 'quantity')
        ->withSum('useStockLoss', 'quantity')
        ->where('delived_factory_type', 'dyed')
            ->orderBy('id', 'desc')
            ->get();

        // return $yarnStocks;

        return view('yarn_store.index', compact('yarnStocks'));
    }

    public function useYarnStockCreate($id) {
        $yarnStock = YarnStoreStock::with('storeDetails')
            ->where('delived_factory_type', 'dyed')
            ->orderBy('id', 'desc')
            ->where('id', $id)
            ->first();
        // return $yarnStock->quantity;
        $knitFactory  = NettingFactroy::where('status', 'active')->get();
        $orderDetails = OrderDetail::select('po_number', 'order_id', 'style')->where('status', 'processing')->orderBy('id', 'desc')->get();

        $useStockSum     = NettingQuotation::where('stock_id', $id)->sum('quantity');
        $useStockLossSum = YarnLoss::where('stock_id', $id)->where('delived_factory_type', 'dyed')->sum('quantity');

        return view('yarn_store.distribute', compact('yarnStock', 'knitFactory', 'orderDetails', 'useStockSum', 'useStockLossSum'));
    }

    //use stock
    public function useYarnStock(Request $request) {
        // return $request;
        $request->validate([
            'stock_id' => "required",
        ]);

        $successMessageStatus = false;

        if ($request->quantity) {
            $receiver_po = explode('-', $request->receiver_po_number);

            $orderDetail = OrderDetail::where('po_number', trim($receiver_po[0]))
                ->where('style', trim($receiver_po[1]))
                ->first();

            if (!$orderDetail) {
                toastr('Order Details Not Found!', 'error');
                return back();
            }

            NettingQuotation::create([
                'stock_id'                  => $request->stock_id,
                'description'               => $request->description,
                'order_id'                  => $orderDetail->order_id,
                'style'                     => trim($receiver_po[1]),
                'po_number'                 => trim($receiver_po[0]),
                'order_date'                => $request->order_date,
                'approximate_delivery_date' => $request->approximate_delivery_date,
                'remarks'                   => $request->remarks,
                'netting_factory_id'        => $request->knit_factory_id,
                'quantity'                  => $request->quantity,
                'price'                     => $request->price,
                'total_price'               => $request->total_amount,
                'created_by'                => Auth::id(),
            ]);

            $successMessageStatus = true;
        }

        // Save Yarn Loss
        if ($request->loss) {

            YarnLoss::create([
                'delived_factory_type' => 'dyed',
                'is_stock_received'    => 'Yes',
                'stock_id'             => $request->stock_id,
                'quantity'             => $request->loss,
                'created_by'           => Auth::id(),
                'style'                => $request->style,
                'po_number'            => $request->po_number,
                'order_id'             => $request->order_id,
                'description'          => $request->description,
            ]);

            $successMessageStatus = true;
        }

        toastr(
            $successMessageStatus ? 'Data Successfully Created!' : 'No Input data found!',
            $successMessageStatus ? 'success' : 'error'
        );

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(YarnStoreStock $yarnstorestock) {
        // $yarnstorestock->load('usesStock', 'yarnQty');
        // return $yarnstorestock;
        $yarnstorestock = YarnStoreStock::with('storeDetails')->find($yarnstorestock->id);
        // $usesStock      = $yarnstorestock->usesStock()
        //     ->orderBy('id', 'desc')
        //     ->paginate(20);

        return view('yarn_store.show', compact('yarnstorestock'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(YarnStoreStock $yarnstorestock) {
        //
        $storeAddress = Store::where('status', 'active')->get();
        return view('yarn_store.edit', compact('yarnstorestock', 'storeAddress'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, YarnStoreStock $yarnstorestock) {

        // return $request;
        $request->validate([
            'po_number'     => "required",
            'style'         => "required",
            'quantity'      => "required",
            'store_address' => "required",
            'description'   => "required",
        ]);

        if ($request->hasFile('challan_file')) {
            $path = $request->file('challan_file')->store('yarn_received_challan', 'public');
        } else {
            $path = $yarnstorestock->challan_file ?? null;
        }

        $yarnstorestock->update([
            "po_number"      => $request->po_number,
            "style"          => $request->style,
            "remarks"        => $request->remarks,
            "lot_number"     => $request->loat_no,
            "bag_count"      => $request->bag_count,
            "quantity"       => $request->quantity,
            "store_id"       => $request->store_address,
            "updated_by"     => Auth::id(),
            "received_date"  => $request->received_date,
            "description"    => $request->description,
            "status"         => $request->status,
            "challan_file"   => $path,
            "vehicle_number" => $request->vehicle_number,
            "challan_date"   => $request->challan_date,
            "challan_number" => $request->challan_number,
        ]);

        toastr('Data Successfully Updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YarnStoreStock $yarnstorestock) {
        //
        $yarnstorestock->delete();
        toastr('Data Successfully Deleted!');
        return back();
    }
}
