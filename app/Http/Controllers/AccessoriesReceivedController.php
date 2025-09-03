<?php

namespace App\Http\Controllers;

use App\Models\AccessoriesLoss;
use App\Models\AccessoriesQuotation;
use App\Models\AccessoriesReceived;
use App\Models\AccessoriesStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessoriesReceivedController extends Controller {
    public function getAccessoriesStyleByPo($po_number) {
        $dyeing = AccessoriesQuotation::where('po_number', $po_number)
            ->withSum('accessoriesReceived', 'quantity')
            ->withSum('accessoriesLoss', 'quantity')
            ->withSum('accessoriesStoreStock', 'quantity')
            ->get()
            ->groupBy('style');
        if ($dyeing) {
            return $dyeing;
        } else {
            return 'Datas not found!';
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return view('accessories_received.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        $accessoriesQut = AccessoriesQuotation::select('po_number')
            ->groupby('po_number')
            ->get();
        return view('accessories_received.create', compact('accessoriesQut'));
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
            $path = $request->file('challan_file')->store('accessories_received_challan', 'public');
        }
        $successMessageStatus = 0;
        foreach ($request->items as $item) {
            $accessoriesQut = AccessoriesQuotation::withSum('accessoriesReceived', 'quantity')
                ->withSum('accessoriesLoss', 'quantity')
                ->withSum('accessoriesStoreStock', 'quantity')
                ->where('id', $item['acc_qyt_id'])
                ->first();

            $receivedTotal = $accessoriesQut->accessories_received_sum_quantity + $accessoriesQut->accessories_loss_sum_quantity + $accessoriesQut->accessories_store_stock_sum_quantity;
            $newReceived   = (array_key_exists('netting', $item) ? $item['netting'] : 0) + (array_key_exists('loss', $item) ? $item['loss'] : 0) + (array_key_exists('stock', $item) ? $item['stock'] : 0);
            $total         = $newReceived + $receivedTotal;

            if (array_key_exists('accessories', $item) && $item['accessories'] > 0 && $accessoriesQut->quantity > $receivedTotal && $accessoriesQut->quantity >= $total) {
                $successMessageStatus = 1;
                AccessoriesReceived::create([
                    'accessories_quotation_id' => $item['acc_qyt_id'],
                    'po_number'                => $request->po_number,
                    'style'                    => $item['style'],
                    'quantity'                 => $item['accessories'],
                    'lot_number'               => $item['loat_no'],
                    'bag_count'                => $item['bag_count'],
                    'unit'                     => $item['unit'],
                    'challan_date'             => $request->challan_date,
                    'challan_number'           => $request->challan_number,
                    'vehicle_number'           => $request->vehicle_number,
                    'received_date'            => $request->received_date,
                    'created_by'               => Auth::id(),
                    'remarks'                  => $item['remarks'],
                    'challan_file'             => $path,
                ]);

            }

            if (array_key_exists('loss', $item) && $item['loss'] > 0 && $accessoriesQut->quantity > $receivedTotal && $accessoriesQut->quantity >= $total) {
                $successMessageStatus = 1;
                AccessoriesLoss::create([
                    'accessories_quotation_id' => $item['acc_qyt_id'],
                    'quantity'                 => $item['loss'],
                    'unit'                     => $item['unit'],
                    'created_by'               => Auth::id(),
                ]);
            }

            if (array_key_exists('stock', $item) && $item['stock'] > 0 && $accessoriesQut->quantity > $receivedTotal && $accessoriesQut->quantity >= $total) {
                $successMessageStatus = 1;
                AccessoriesStock::create([
                    'accessories_quotation_id' => $item['acc_qyt_id'],
                    'po_number'                => $request->po_number,
                    'style'                    => $item['style'],
                    'quantity'                 => $item['stock'],
                    'lot_number'               => $item['loat_no'],
                    'bag_count'                => $item['bag_count'],
                    'unit'                     => $item['unit'],
                    'challan_date'             => $request->challan_date,
                    'challan_number'           => $request->challan_number,
                    'vehicle_number'           => $request->vehicle_number,
                    'received_date'            => $request->received_date,
                    'created_by'               => Auth::id(),
                    'remarks'                  => $item['remarks'],
                    'store_address'            => $item['store_address'],
                    'challan_file'             => $path,
                ]);

            }

            if ((float) $accessoriesQut->quantity === (float) $total) {
                $accessoriesQut->update([
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
    public function show(AccessoriesReceived $accessoriesReceived) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccessoriesReceived $accessoriesReceived) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccessoriesReceived $accessoriesReceived) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccessoriesReceived $accessoriesReceived) {
        //
    }
}
