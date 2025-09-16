<?php

namespace App\Http\Controllers;

use App\Models\DyedQuotation;
use App\Models\NettingQuotation;
use App\Models\NettingQuotationItem;
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
                $totalInnerQty = collect($item['inner_items'])->sum('quantity');

                if ($item['knit_quantity'] > 0 && $item['knit_quantity'] == $totalInnerQty) {
                    $successMessageStatus = 1;

                    $knitQty = NettingQuotation::create([
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
                        'quantity'                  => $item['knit_quantity'],
                        'price'                     => $item['rate'] ?? null,
                        'total_price'               => $item['total'] ?? null,
                        'created_by'                => Auth::id(),
                    ]);

                    if ($knitQty && !empty($item['inner_items']) && is_array($item['inner_items'])) {
                        foreach ($item['inner_items'] as $inner) {
                            NettingQuotationItem::create([
                                'netting_quotation_id' => $knitQty->id,
                                'quantity'             => $inner['quantity'] ?? null,
                                'delivery_point_id'    => $inner['delivery_point'] ?? null,
                                'created_by'           => Auth::id(),
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
    public function show(NettingQuotation $netting) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NettingQuotation $netting) {
        //
    }

    public function nettingQtyStatusUpdate(Request $request) {
        if (!$request->style && !$request->po_number) {
            toastr('PO number and style not found!', 'error');
            return back();
        }
        NettingQuotation::where('po_number', $request->po_number)->where('style', $request->style)->update([
            'status'      => $request->status,
            'updated_by'  => Auth::id(),
            'approved_by' => Auth::id(),
        ]);
        toastr('Netting quotation Status Updated!');
        return back();
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
