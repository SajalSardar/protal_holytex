<?php

namespace App\Http\Controllers;

use App\Models\DyedQuotation;
use App\Models\NettingFactroy;
use App\Models\YarnQuotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DyedQuotationController extends Controller {
    public function getYarnStyleByPo($po_number) {
        $nettings = DyedQuotation::where('po_number', $po_number)->pluck('style');
        $yearns   = YarnQuotation::with('yarnFactory', 'dyedFactory')
            ->where('po_number', $po_number)
            ->where('receving_factory', 'dyed')
            ->where('status', 'approved')
            ->whereNotIn('style', $nettings)
            ->get()->groupBy(['style', 'dyed_factory_id']);
        if ($yearns->isNotEmpty()) {
            return $yearns;
        } else {
            return response()->json(['message' => 'New Yarn Quotation not found!']);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $dydeQuty = DyedQuotation::with('dyedFactory:id,name,address')
            ->orderBy('id', 'desc')
            ->get();
        return view('dyed_quotation.index', compact('dydeQuty'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        $yearns = YarnQuotation::select('po_number')
            ->where('receving_factory', 'dyed')
            ->where('status', 'approved')
            ->groupby('po_number')
            ->get();
        $knitgFactory = NettingFactroy::where('status', 'active')
            ->get();
        return view('dyed_quotation.create', compact('yearns', 'knitgFactory'));
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
                $totalInnerQty = collect($item['inner_items'])->sum('quantity');

                if ($item['knit_quantity'] > 0 && $item['knit_quantity'] == $totalInnerQty) {
                    $successMessageStatus = 1;

                    if (!empty($item['inner_items']) && is_array($item['inner_items'])) {
                        foreach ($item['inner_items'] as $inner) {
                            DyedQuotation::create([
                                'dyed_factory_id'           => $item['dyed_factory_id'],
                                'description'               => $item['description'],
                                'order_id'                  => $request->order_id,
                                'order_number'              => $request->order_number,
                                'style'                     => $style,
                                'po_number'                 => $request->po_number,
                                'purchase_date'             => $request->order_date,
                                'approximate_delivery_date' => $request->approximate_delivery_date,
                                'remarks'                   => $request->remarks,
                                'delivery_factory_type'     => $item['delevary_poin_check'] ?? null,
                                'netting_factory_id'        => $item['netting_factory_id'] ?? null,
                                'from_stock_quantity'       => $item['from_stock_quantity'] ?? null,
                                'quantity'                  => $inner['quantity'] ?? null,
                                'price'                     => $item['rate'] ?? null,
                                'total_price'               => $item['total'] ?? null,
                                'created_by'                => Auth::id(),
                                'delivery_point_id'         => $inner['delivery_point'] ?? null,
                            ]);
                        }
                    }

                } else {
                    toastr("Enter Total Quantity in {$style}!", 'error');
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
    public function show(DyedQuotation $dyedQuotation) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DyedQuotation $dyedQuotation) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DyedQuotation $dyedQuotation) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DyedQuotation $dyedQuotation) {
        //
    }

    public function dyedQtyStatusUpdate(Request $request) {
        if (!$request->style && !$request->po_number) {
            toastr('PO number and style not found!', 'error');
            return back();
        }
        DyedQuotation::where('id', $request->id)->where('po_number', $request->po_number)->where('style', $request->style)->update([
            'status'      => $request->status,
            'updated_by'  => Auth::id(),
            'approved_by' => Auth::id(),
        ]);
        toastr('Dyed quotation status updated!');
        return back();
    }
}
