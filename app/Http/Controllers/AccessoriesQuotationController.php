<?php

namespace App\Http\Controllers;

use App\Models\AccessoriesQuotation;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessoriesQuotationController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $accessoriesQuotation = AccessoriesQuotation::with('storeAddress')
            ->withSum('accessoriesReceived', 'quantity')
            ->withSum('accessoriesLoss', 'quantity')
            ->orderBy('id', 'desc')->get();
        // return $accessoriesQuotation;
        return view('accessories_quotation.index', compact('accessoriesQuotation'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $ordersPo     = Order::where('status', 'approved')->pluck('po_number', 'id');
        $storeAddress = Store::where('status', 'active')->get();
        return view('accessories_quotation.create', compact('ordersPo', 'storeAddress'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
        // return $request;

        $request->validate([
            'po_number' => 'required',
            'store_id'  => 'required',
        ]);

        foreach ($request->style as $key => $item) {
            AccessoriesQuotation::create([
                'order_id'                  => $request->order_id,
                'order_number'              => $request->order_number,
                'po_number'                 => $request->po_number,
                'supplier_name'             => $request->supplier_name,
                'supplier_phone'            => $request->supplier_phone,
                'supplier_address'          => $request->supplier_address,
                'order_date'                => $request->order_date,
                'store_id'                  => $request->store_id,
                'approximate_delivery_date' => $request->approximate_delivery_date,
                'remarks'                   => $request->remarks,
                'style'                     => $item,
                'description'               => $request->description[$key],
                'quantity'                  => $request->unit_quantity[$key],
                'price'                     => $request->unit_price[$key],
                'total_price'               => $request->total_unit_price[$key],
                'unit'                      => $request->unit[$key],
                'created_by'                => Auth::id(),
            ]);
        }
        toastr('Accessories Successfully Created!');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(AccessoriesQuotation $accessoriesquotation) {
        //
        return view('accessories_quotation.show', compact('accessoriesquotation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccessoriesQuotation $accessoriesquotation) {
        $ordersPo     = Order::where('status', 'approved')->pluck('po_number', 'id');
        $storeAddress = Store::where('status', 'active')->get();
        return view('accessories_quotation.edit', compact('accessoriesquotation', 'ordersPo', 'storeAddress'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccessoriesQuotation $accessoriesquotation) {
        $request->validate([
            'po_number' => 'required',
            'store_id'  => 'required',
        ]);
        $accessoriesquotation->update([
            'supplier_name'             => $request->supplier_name,
            'supplier_phone'            => $request->supplier_phone,
            'supplier_address'          => $request->supplier_address,
            'order_date'                => $request->order_date,
            'store_id'                  => $request->store_id,
            'approximate_delivery_date' => $request->approximate_delivery_date,
            'remarks'                   => $request->remarks,
            'description'               => $request->description,
            'quantity'                  => $request->quantity,
            'price'                     => $request->price,
            'total_price'               => $request->total_unit_price,
            'unit'                      => $request->unit,
            'status'                    => $request->status,
            'updated_by'                => Auth::id(),
        ]);
        if ($request->status === "approved") {
            $accessoriesquotation->approved_by = Auth::id();
            $accessoriesquotation->save();
        }

        toastr('Accessories Successfully Updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccessoriesQuotation $accessoriesquotation) {
        $accessoriesquotation->delete();
        toastr('Accessories Quotation Successfully Deleted!');
        return back();
    }
}
