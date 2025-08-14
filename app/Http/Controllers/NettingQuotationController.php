<?php

namespace App\Http\Controllers;

use App\Models\NettingQuotation;
use App\Models\YarnQuotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NettingQuotationController extends Controller {

    public function getYarnStyleByPo($po_number) {
        $nettings = NettingQuotation::where('po_number', $po_number)->pluck('style');
        $yearns   = YarnQuotation::with('yarnFactory', 'nettingFactory')
            ->where('po_number', $po_number)
            ->whereNotIn('style', $nettings)
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
        return view('netting_quotation.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        $yearns = YarnQuotation::select('po_number')->groupby('po_number')->get();
        return view('netting_quotation.create', compact('yearns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
        // return $request;

        $request->validate([
            'po_number' => 'required',
        ]);

        foreach ($request->items as $key => $item) {
            NettingQuotation::create([
                'order_id'                  => $request->order_id,
                'order_number'              => $request->order_number,
                'style'                     => $key,
                'po_number'                 => $request->po_number,
                'purchase_date'             => $request->order_date,
                'approximate_delivery_date' => $request->approximate_delivery_date,
                'quantity'                  => $item['quantity'],
                'price'                     => $item['rate'],
                'total_price'               => $item['total'],
                'delivery_factory_type'     => $item['delevary_poin_check'],
                'delivery_point_id'         => $item['delivery_point'],
                'netting_factory_id'        => $item['netting_factory_id'],
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
    public function show(NettingQuotation $netting) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NettingQuotation $netting) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NettingQuotation $netting) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NettingQuotation $netting) {
        //
    }
}
