<?php

namespace App\Http\Controllers;

use App\Models\BuyYarn;
use App\Models\NettingFactroy;
use App\Models\Order;
use App\Models\YarnFactroy;
use Illuminate\Http\Request;

class BuyYarnController extends Controller {
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
        $orders         = Order::where('status', 'processing')->pluck('po_number', 'id');
        $yarnFactory    = YarnFactroy::where('status', 'active')->get();
        $nettingFactory = NettingFactroy::where('status', 'active')->get();
        return view('buy_yarn.create', compact('orders', 'yarnFactory', 'nettingFactory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
        return $request;
    }

    /**
     * Display the specified resource.
     */
    public function show(BuyYarn $buyYarn) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BuyYarn $buyYarn) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BuyYarn $buyYarn) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BuyYarn $buyYarn) {
        //
    }
}
