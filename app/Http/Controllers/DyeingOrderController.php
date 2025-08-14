<?php

namespace App\Http\Controllers;

use App\Models\DyeingOrder;
use App\Models\GarmentsFactroy;
use App\Models\Netting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DyeingOrderController extends Controller {

    public function getNetting($po_number) {
        $dyeing = DyeingOrder::where('po_number', $po_number)->pluck('style');

        $nettings = Netting::with('dyeingFactory', 'nettingFactory')->where('po_number', $po_number)
            ->where('delivery_factory_type', 'dyeing')
            ->whereNotIn('style', $dyeing)
            ->get();

        if ($nettings) {
            return $nettings;
        } else {
            return 'Netting not found!';
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
        $nettings = Netting::where('delivery_factory_type', 'dyeing')->select('po_number')->groupby('po_number')->get();

        $delivery_point = GarmentsFactroy::where('status', 'active')->get();
        return view('dyeing_order.create', compact('nettings', 'delivery_point'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // return $request;
        $request->validate([
            'po_number' => 'required',
        ]);

        foreach ($request->items as $key => $item) {
            DyeingOrder::create([
                'order_id'                  => $request->order_id,
                'order_number'              => $request->order_number,
                'style'                     => $key,
                'po_number'                 => $request->po_number,
                'purchase_date'             => $request->order_date,
                'approximate_delivery_date' => $request->approximate_delivery_date,
                'quantity'                  => $item['quantity'],
                'price'                     => $item['rate'],
                'total_price'               => $item['total'],
                'delivery_point_id'         => $item['delivery_point'],
                'dyeing_factory_id'         => $item['dyeing_factory_id'],
                'remarks'                   => $request->remarks,
                'created_by'                => Auth::id(),
            ]);
        }

        toastr('Dyeing Successfully Created!');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(DyeingOrder $dyeingOrder) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DyeingOrder $dyeingOrder) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DyeingOrder $dyeingOrder) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DyeingOrder $dyeingOrder) {
        //
    }
}
