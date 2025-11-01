<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Store;
use App\Models\YarnFactroy;
use App\Models\YarnQuotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YarnQuotationController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
        $yearnList = YarnQuotation::with('yarnStore')->orderBy('id', 'desc')->get();
        // ->groupBy('po_number')
        // ->map(function ($items) {
        //     return $items->groupBy('style');
        // });
        // return $yearnList;
        return view('yarn_quotation.index', compact('yearnList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $orders       = Order::where('status', 'approved')->pluck('po_number', 'id');
        $yarnFactory  = YarnFactroy::where('status', 'active')->get();
        $storeAddress = Store::where('status', 'active')->get();
        return view('yarn_quotation.create', compact('orders', 'yarnFactory', 'storeAddress'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // return $request;

        $request->validate([
            'po_number'    => 'required',
            'order_number' => 'required',
        ]);

        foreach ($request->style as $key => $item) {

            YarnQuotation::create([
                'order_number'              => $request->order_number,
                'po_number'                 => $request->po_number,
                'order_date'                => $request->order_date,
                'approximate_delivery_date' => $request->approximate_delivery_date,
                'order_id'                  => $request->order_id,
                'style'                     => $item,
                'description'               => $request->description[$key],
                'quantity'                  => $request->unit_quantity[$key],
                'price'                     => $request->unit_price[$key],
                'total_price'               => $request->total_unit_price[$key],
                'yarn_factory_id'           => $request->yarn_factory[$key],
                'remarks'                   => $request->remarks,
                'created_by'                => Auth::id(),
                'store_id'                  => $request->delivery_point[$key],
            ]);

        }

        toastr('Order Successfully Created!');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(YarnQuotation $yarnquotation) {

        return view('yarn_quotation.show', compact('yarnquotation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(YarnQuotation $yarnquotation) {
        $yarnFactory  = YarnFactroy::where('status', 'active')->get();
        $storeAddress = Store::where('status', 'active')->get();
        $getStyleByPo = OrderDetail::where('po_number', $yarnquotation->po_number)
            ->pluck('style');
        // return $getStyleByPo;
        return view('yarn_quotation.edit', compact('yarnquotation', 'yarnFactory', 'getStyleByPo', 'storeAddress'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, YarnQuotation $yarnquotation) {
        //return $request;

        $request->validate([
            'po_number'    => 'required',
            'order_number' => 'required',
        ]);

        $yarnquotation->update([
            'order_date'                => $request->order_date,
            'approximate_delivery_date' => $request->approximate_delivery_date,
            'style'                     => $request->style,
            'description'               => $request->description,
            'quantity'                  => $request->quantity,
            'price'                     => $request->price,
            'total_price'               => $request->total_unit_price,
            'yarn_factory_id'           => $request->yarn_factory,
            'remarks'                   => $request->remarks,
            'updated_by'                => Auth::id(),
            'status'                    => $request->status,
            'store_id'                  => $request->delivery_point,
        ]);

        if ($request->status === "approved") {
            $yarnquotation->approved_by = Auth::id();
            $yarnquotation->save();
        }

        toastr('Yarn Quotation Successfully Updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YarnQuotation $yarnquotation) {
        $yarnquotation->delete();
        toastr('Yarn Quotation Successfully Deleted!');
        return back();
    }
}
