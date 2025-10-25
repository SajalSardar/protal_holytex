<?php

namespace App\Http\Controllers;

use App\Models\DyedQuotation;
use App\Models\DyeingFactroy;
use App\Models\GarmentsFactroy;
use App\Models\NettingQuotation;
use App\Models\YarnQuotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NettingQuotationController extends Controller {

    public function getYarnStyleByPo($po_number) {
        $nettings = NettingQuotation::where('po_number', $po_number)->pluck('style');
        $yearns   = YarnQuotation::with('yarnFactory', 'nettingFactory')
            ->where('po_number', $po_number)
            ->where('receving_factory', 'knit')
            ->where('status', 'approved')
            ->whereNotIn('style', $nettings)
            ->get()->groupBy(['style', 'netting_factory_id']);

        $dyeds = DyedQuotation::with('dyedFactory', 'nettingFactory')
            ->where('po_number', $po_number)
            ->whereNotIn('style', $nettings)
            ->where('status', 'approved')
            ->get()->groupBy(['style', 'delivery_point_id']);

        return json_encode([
            'yearns' => $yearns ?? null,
            'dyeds'  => $dyeds ?? null,
        ]);

    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
        $nettings = NettingQuotation::with('nettingFactory:id,name,address')->orderBy('id', 'desc')->get();
        return view('netting_quotation.index', compact('nettings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        $yearns = YarnQuotation::select('po_number')
            ->where('receving_factory', 'knit')
            ->where('status', 'approved')
            ->groupby('po_number')
            ->get();
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
        $successMessageStatus = 0;

        foreach ($request->items as $style => $items) {
            foreach ($items as $item) {
                // Main item
                if (!empty($item['inner_items']) && is_array($item['inner_items'])) {
                    $totalInnerQty = collect($item['inner_items'])->sum('quantity');

                    if ($item['knit_quantity'] > 0 && $item['knit_quantity'] == $totalInnerQty) {
                        $successMessageStatus = 1;
                        foreach ($item['inner_items'] as $inner) {
                            NettingQuotation::create([
                                'order_id'                  => $request->order_id,
                                'order_number'              => $request->order_number,
                                'style'                     => $style,
                                'po_number'                 => $request->po_number,
                                'purchase_date'             => $request->order_date,
                                'approximate_delivery_date' => $request->approximate_delivery_date,
                                'remarks'                   => $request->remarks,
                                'delivery_factory_type'     => $item['delevary_poin_check'] ?? null,
                                'netting_factory_id'        => $item['netting_factory_id'] ?? null,
                                'from_stock_quantity'       => $inner['form_stock_quantity'] ?? null,
                                'quantity'                  => $inner['quantity'],
                                'price'                     => $item['rate'] ?? null,
                                'total_price'               => ($inner['quantity'] * $item['rate']) ?? null,
                                'created_by'                => Auth::id(),
                                'delivery_point_id'         => $inner['delivery_point'] ?? null,
                            ]);
                        }
                    } else {
                        toastr("Enter Total Quantity in {$style}!", 'error');
                    }

                } else {
                    toastr("Enter Quantity in {$style}!", 'error');
                }
            }
        }

        if ($successMessageStatus === 1) {
            toastr("Style {$style} Netting Successfully Created!");
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(NettingQuotation $nettingquotation) {
        return view('netting_quotation.show', compact('nettingquotation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NettingQuotation $nettingquotation) {
        if ($nettingquotation->delivery_factory_type === "dyeing") {
            $delivery_factory = DyeingFactroy::where('status', 'active')->get();
        } else {
            $delivery_factory = GarmentsFactroy::where('status', 'active')->get();
        }
        return view('netting_quotation.edit', compact('nettingquotation', 'delivery_factory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NettingQuotation $nettingquotation) {
        $request->validate([
            'po_number' => 'required',
        ]);
        $nettingquotation->update([
            'purchase_date'             => $request->order_date,
            'approximate_delivery_date' => $request->approximate_delivery_date,
            'remarks'                   => $request->remarks,
            'from_stock_quantity'       => $request->from_stock_quantity,
            'quantity'                  => $request->quantity,
            'price'                     => $request->price,
            'total_price'               => $request->total_unit_price,
            'updated_by'                => Auth::id(),
            'delivery_point_id'         => $request->delivery_point_id,
            'status'                    => $request->status,
        ]);

        if ($request->status === "approved") {
            $nettingquotation->approved_by = Auth::id();
            $nettingquotation->save();
        }
        toastr('Netting quotation Updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NettingQuotation $nettingquotation) {
        $nettingquotation->delete();
        toastr('Netting Quotation Successfully Deleted!');
        return back();
    }
}
