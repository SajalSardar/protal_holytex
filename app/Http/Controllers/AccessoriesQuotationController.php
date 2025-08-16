<?php

namespace App\Http\Controllers;

use App\Models\AccessoriesQuotation;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessoriesQuotationController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $accessoriesQuotation = AccessoriesQuotation::get();
        // return $accessoriesQuotation;
        return view('accessories_quotation.index', compact('accessoriesQuotation'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $ordersPo = Order::pluck('po_number', 'id');
        return view('accessories_quotation.create', compact('ordersPo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
        // return $request;
        foreach ($request->style as $key => $item) {
            AccessoriesQuotation::create([
                'order_id'                  => $request->order_id,
                'order_number'              => $request->order_number,
                'po_number'                 => $request->po_number,
                'supplier_name'             => $request->supplier_name,
                'supplier_phone'            => $request->supplier_phone,
                'supplier_address'          => $request->supplier_address,
                'purchase_date'             => $request->order_date,
                'shiphing_address'          => $request->shiphing_address,
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
    public function show(AccessoriesQuotation $accessoriesQuotation) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccessoriesQuotation $accessoriesQuotation) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccessoriesQuotation $accessoriesQuotation) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccessoriesQuotation $accessoriesQuotation) {
        //
    }
}
