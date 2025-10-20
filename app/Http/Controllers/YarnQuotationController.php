<?php

namespace App\Http\Controllers;

use App\Models\DyedFactory;
use App\Models\NettingFactroy;
use App\Models\Order;
use App\Models\OrderDetail;
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
        $yearnList = YarnQuotation::orderBy('id', 'desc')->get();
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
        $orders         = Order::where('status', 'approved')->pluck('po_number', 'id');
        $yarnFactory    = YarnFactroy::where('status', 'active')->get();
        $nettingFactory = NettingFactroy::where('status', 'active')->get();
        $dyenFactory    = DyedFactory::where('status', 'active')->get();
        return view('yarn_quotation.create', compact('orders', 'yarnFactory', 'nettingFactory', 'dyenFactory'));
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

            $yarnCreate = YarnQuotation::create([
                'order_number'              => $request->order_number,
                'po_number'                 => $request->po_number,
                'order_date'                => $request->order_date,
                'approximate_delivery_date' => $request->approximate_delivery_date,
                'order_id'                  => $request->order_id,
                'style'                     => $item,
                'description'               => $request->description[$key],
                'from_stock_quantity'       => $request->from_stock[$key],
                'quantity'                  => $request->unit_quantity[$key],
                'price'                     => $request->unit_price[$key],
                'total_price'               => $request->total_unit_price[$key],
                'yarn_factory_id'           => $request->yarn_factory[$key],
                'receving_factory'          => $request->delivery_fact_type[$key],
                'remarks'                   => $request->remarks,
                'created_by'                => Auth::id(),
            ]);

            if ($request->delivery_fact_type[$key] === "knit") {
                $yarnCreate->netting_factory_id = $request->delivery_point[$key];
            }
            if ($request->delivery_fact_type[$key] === "dyed") {
                $yarnCreate->dyed_factory_id = $request->delivery_point[$key];
            }
            $yarnCreate->save();

        }

        toastr('Order Successfully Created!');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(YarnQuotation $buyYarn) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(YarnQuotation $yarnquotation) {
        $yarnFactory    = YarnFactroy::where('status', 'active')->get();
        $nettingFactory = NettingFactroy::where('status', 'active')->get();
        $dyenFactory    = DyedFactory::where('status', 'active')->get();
        $getStyleByPo   = OrderDetail::where('po_number', $yarnquotation->po_number)
            ->pluck('style');
        // return $getStyleByPo;
        return view('yarn_quotation.edit', compact('yarnquotation', 'yarnFactory', 'nettingFactory', 'dyenFactory', 'getStyleByPo'));
    }

    public function yarnQtyStatusUpdate(Request $request) {
        // return $request;
        if (!$request->style && !$request->po_number) {
            toastr('PO number and style not found!', 'error');
            return back();
        }
        YarnQuotation::where('po_number', $request->po_number)->where('style', $request->style)->update([
            'status'      => $request->status,
            'updated_by'  => Auth::id(),
            'approved_by' => Auth::id(),
        ]);
        toastr('Yarn quotation Status Updated!');
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, YarnQuotation $yarnquotation) {
        //
        return $request;

        $request->validate([
            'po_number'    => 'required',
            'order_number' => 'required',
        ]);

        $yarnquotation->update([
            'order_number'              => $request->order_number,
            'po_number'                 => $request->po_number,
            'order_date'                => $request->order_date,
            'approximate_delivery_date' => $request->approximate_delivery_date,
            'order_id'                  => $request->order_id,
            'style'                     => $item,
            'description'               => $request->description[$key],
            'from_stock_quantity'       => $request->from_stock[$key],
            'quantity'                  => $request->unit_quantity[$key],
            'price'                     => $request->unit_price[$key],
            'total_price'               => $request->total_unit_price[$key],
            'yarn_factory_id'           => $request->yarn_factory[$key],
            'receving_factory'          => $request->delivery_fact_type[$key],
            'remarks'                   => $request->remarks,
            'created_by'                => Auth::id(),
        ]);

        if ($request->delivery_fact_type[$key] === "knit") {
            $yarnquotation->netting_factory_id = $request->delivery_point[$key];
        }
        if ($request->delivery_fact_type[$key] === "dyed") {
            $yarnquotation->dyed_factory_id = $request->delivery_point[$key];
        }
        $yarnquotation->save();

        toastr('Yarn Quotation Successfully Updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YarnQuotation $yarnquotation) {
        //
    }
}
