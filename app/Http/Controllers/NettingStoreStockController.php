<?php

namespace App\Http\Controllers;

use App\Models\NettingQuotation;
use App\Models\NettingStoreStock;
use App\Models\YarnReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NettingStoreStockController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return view('netting_store.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $yarnreceived = YarnReceived::groupby('po_number')
            ->pluck('po_number');

        $requestPo = request()->po_number;

        if ($requestPo) {
            if (!in_array($requestPo, $yarnreceived->toArray())) {
                return redirect()->route('nettingreceived.index');
            }
        }

        $nettingQut = NettingQuotation::where('delivery_factory_type', 'dyeing')
            ->select('po_number')
            ->groupby('po_number')
            ->whereIn('po_number', $yarnreceived)
            ->get();
        return view('netting_store.create', compact('nettingQut'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // return $request;
        $request->validate([
            'challan_file' => "nullable|max:512|image",
        ]);

        $path = null;
        if ($request->hasFile('challan_file')) {
            $path = $request->file('challan_file')->store('netting_received_challan', 'public');
        }
        $successMessageStatus = 0;
        foreach ($request->items as $item) {
            $nettingQut = NettingQuotation::withSum('nettingReceived', 'quantity')
                ->withSum('nettingLoss', 'quantity')
                ->withSum('storeStock', 'quantity')
                ->withSum('nettingReceiveGarments', 'quantity')
                ->where('id', $item['netting_id'])
                ->first();

            $nettingReceivedTotal = $nettingQut->yarn_received_sum_quantity + $nettingQut->yarn_loss_sum_quantity + $nettingQut->store_stock_sum_quantity + $nettingQut->netting_receive_garments_sum_quantity;
            $newReceived          = (array_key_exists('netting', $item) ? $item['netting'] : 0);
            $total                = $newReceived + $nettingReceivedTotal;

            if (array_key_exists('netting', $item) && $item['netting'] > 0 && $nettingQut->quantity > $nettingReceivedTotal && $nettingQut->quantity >= $total) {
                $successMessageStatus = 1;
                NettingStoreStock::create([
                    'netting_quotation_id' => $item['netting_id'],
                    'delived_factory_type' => 'netting',
                    'po_number'            => $request->po_number,
                    'style'                => $item['style'],
                    'quantity'             => $item['netting'],
                    'lot_number'           => $item['loat_no'],
                    'bag_count'            => $item['bag_count'],
                    'store_address'        => $item['store_address'],
                    'challan_date'         => $request->challan_date,
                    'challan_number'       => $request->challan_number,
                    'vehicle_number'       => $request->vehicle_number,
                    'received_date'        => $request->received_date,
                    'created_by'           => Auth::id(),
                    'remarks'              => $item['remarks'],
                    'challan_file'         => $path,
                ]);

            }

            if ((float) $nettingQut->quantity === (float) $total) {
                $nettingQut->update([
                    'status'        => 'recevied',
                    'delivery_date' => $request->received_date,
                    'updated_by'    => Auth::id(),
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
    public function show(NettingStoreStock $nettingStoreStock) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NettingStoreStock $nettingStoreStock) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NettingStoreStock $nettingStoreStock) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NettingStoreStock $nettingStoreStock) {
        //
    }
}
