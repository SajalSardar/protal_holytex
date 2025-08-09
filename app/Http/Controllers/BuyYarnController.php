<?php

namespace App\Http\Controllers;

use App\Models\BuyYarn;
use App\Models\NettingFactroy;
use App\Models\Order;
use App\Models\YarnFactroy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        //return $request;

        $request->validate([
            'po_number'    => 'required',
            'order_number' => 'required',
        ]);

        foreach ($request->style as $key => $item) {
            BuyYarn::create([
                'order_number'       => $request->order_number,
                'po_number'          => $request->po_number,
                'order_date'         => $request->order_date,
                'order_id'           => $request->order_id,
                'style'              => $item,
                'description'        => $request->description[$key],
                'quantity'           => $request->unit_quantity[$key],
                'price'              => $request->unit_price[$key],
                'total_price'        => $request->total_unit_price[$key],
                'yarn_factory_id'    => $request->yarn_factory[$key],
                'netting_factory_id' => $request->delivery_point[$key],
                'created_by'         => Auth::id(),
            ]);
        }

        toastr('Order Successfully Created!');
        return back();
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
