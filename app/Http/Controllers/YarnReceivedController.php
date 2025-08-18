<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\YarnQuotation;
use App\Models\YarnReceived;
use Illuminate\Http\Request;

class YarnReceivedController extends Controller {
    public function getYarnStyleByPo($po_number) {
        $yearns = YarnQuotation::with('yarnFactory', 'nettingFactory')
            ->where('po_number', $po_number)
            ->get()
            ->groupBy('style');
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
    public function create(Request $request) {
        $order = Order::with([
            'orderDetails',
            'approvedBy',
            'creator',
            'lastUpdateBy',
            'orderDetails.creator:id,name',
            'orderDetails.lastUpdateBy:id,name',
            'yarnQuotations',
            'yarnQuotations.yarnFactory:id,name,address',
            'yarnQuotations.nettingFactory:id,name,address',
            'yarnQuotations.creator:id,name',
            'yarnQuotations.lastUpdateBy:id,name',
            'yarnQuotations.approvedBy:id,name',

        ])->where('id', $request->order_id)->first();

        $yearns = YarnQuotation::select('po_number')->groupby('po_number')->get();

        return view('yarn_received.receive', compact('yearns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        return $request;
    }

    /**
     * Display the specified resource.
     */
    public function show(YarnReceived $yarnReceived) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(YarnReceived $yarnReceived) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, YarnReceived $yarnReceived) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YarnReceived $yarnReceived) {
        //
    }
}
