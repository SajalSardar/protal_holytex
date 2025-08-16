<?php

namespace App\Http\Controllers;

use App\Models\DyeingQuotation;
use App\Models\NettingFactroy;
use App\Models\NettingQuotation;
use App\Models\Order;
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
        $yearnList = YarnQuotation::orderBy('id', 'desc')->get()
            ->groupBy('po_number')
            ->map(function ($items) {
                return $items->groupBy('style');
            });
        // return $yearnList;
        return view('yarn_quotation.index', compact('yearnList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $orders         = Order::pluck('po_number', 'id');
        $yarnFactory    = YarnFactroy::where('status', 'active')->get();
        $nettingFactory = NettingFactroy::where('status', 'active')->get();
        return view('yarn_quotation.create', compact('orders', 'yarnFactory', 'nettingFactory'));
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

            $netting = NettingQuotation::where('po_number', $request->po_number)->where('style', $item)->first();
            $dyeing  = DyeingQuotation::where('po_number', $request->po_number)->where('style', $item)->first();

            $yarnCreate = YarnQuotation::create([
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
                'netting_factory_id'        => $request->delivery_point[$key],
                'remarks'                   => $request->remarks,
                'created_by'                => Auth::id(),
            ]);

            if ($netting && $yarnCreate) {
                $netting->update([
                    'quantity'    => $netting->quantity + $request->unit_quantity[$key],
                    'total_price' => ($netting->quantity + $request->unit_quantity[$key]) * $netting->price,
                ]);
            }
            if ($dyeing && $yarnCreate) {
                $dyeing->update([
                    'quantity'    => $dyeing->quantity + $request->unit_quantity[$key],
                    'total_price' => ($dyeing->quantity + $request->unit_quantity[$key]) * $dyeing->price,
                ]);
            }
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
    public function edit(YarnQuotation $buyYarn) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, YarnQuotation $buyYarn) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YarnQuotation $buyYarn) {
        //
    }
}
