<?php

namespace App\Http\Controllers;

use App\Models\BuyYarn;
use App\Models\Netting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NettingController extends Controller {

    public function getYarnStyleByPo($po_number) {
        $yearns = BuyYarn::with('yarnFactory', 'nettingFactory')->where('po_number', $po_number)->get()->groupBy('style');
        if ($yearns) {
            return $yearns;
        } else {
            return 'Yarn not found!';
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        $yearns = BuyYarn::select('po_number')->groupby('po_number')->get();
        return view('netting.create', compact('yearns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
        //return $request;

        $request->validate([
            'po_number' => 'required',
        ]);

        foreach ($request->items as $key => $item) {
            Netting::create([
                'order_id'                  => $request->order_id,
                'style'                     => $key,
                'po_number'                 => $request->po_number,
                'purchase_date'             => $request->order_date,
                'approximate_delivery_date' => $request->approximate_delivery_date,
                'quantity'                  => $item['quantity'],
                'price'                     => $item['rate'],
                'total_price'               => $item['total'],
                'delivery_factory_type'     => $item['delevary_poin_check'],
                'delivery_point_id'         => $item['delivery_point'],
                'remarks'                   => $request->remarks,
                'created_by'                => Auth::id(),
            ]);
        }

        toastr('Netting Successfully Created!');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Netting $netting) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Netting $netting) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Netting $netting) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Netting $netting) {
        //
    }
}
