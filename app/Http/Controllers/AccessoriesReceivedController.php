<?php

namespace App\Http\Controllers;

use App\Models\AccessoriesLoss;
use App\Models\AccessoriesQuotation;
use App\Models\AccessoriesReceived;
use App\Models\Store;
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
    public function create(Request $request) {
        $accessories_quotation = $request->accessories_quotation;
        $accessoriesQut        = AccessoriesQuotation::where('id', $accessories_quotation)
            ->withSum('accessoriesReceived', 'quantity')
            ->withSum('accessoriesLoss', 'quantity')
            ->first();
        $storeAddress = Store::where('status', 'active')->get();

        return view('accessories_received.create', compact('accessoriesQut', 'storeAddress'));
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

        $accessoriesQut = AccessoriesQuotation::withSum('accessoriesReceived', 'quantity')
            ->withSum('accessoriesLoss', 'quantity')
            ->where('id', $request->accessoriesQut_id)
            ->first();

        $receivedTotal = $accessoriesQut->accessories_received_sum_quantity + $accessoriesQut->accessories_loss_sum_quantity;
        $newReceived   = ($request->accessories ? $request->accessories : 0) + ($request->loss ? $request->accessories : 0);
        $total         = $newReceived + $receivedTotal;

        if ($request->accessories > 0 && $accessoriesQut->quantity > $receivedTotal && $accessoriesQut->quantity >= $total) {
            $successMessageStatus = 1;
            AccessoriesReceived::create([
                'accessories_quotation_id' => $request->accessoriesQut_id,
                'order_id'                 => $request->order_id,
                'po_number'                => $request->po_number,
                'style'                    => $request->style,
                'description'              => $request->description,
                'quantity'                 => $request->accessories,
                'lot_number'               => $request->loat_no,
                'bag_count'                => $request->bag_count,
                'unit'                     => $request->unit,
                'challan_date'             => $request->challan_date,
                'challan_number'           => $request->challan_number,
                'vehicle_number'           => $request->vehicle_number,
                'received_date'            => $request->received_date,
                'created_by'               => Auth::id(),
                'remarks'                  => $request->remarks,
                'challan_file'             => $path,
            ]);

        }

        if ($request->loss > 0 && $accessoriesQut->quantity > $receivedTotal && $accessoriesQut->quantity >= $total) {
            $successMessageStatus = 1;
            AccessoriesLoss::create([
                'accessories_quotation_id' => $request->accessoriesQut_id,
                'order_id'                 => $request->order_id,
                'po_number'                => $request->po_number,
                'style'                    => $request->style,
                'description'              => $request->description,
                'quantity'                 => $request->loss,
                'unit'                     => $request->unit,
                'created_by'               => Auth::id(),
                'remarks'                  => $request->remarks,
            ]);
        }

        if ($request->accessories > 0 || $request->loss > 0) {
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
