<?php

namespace App\Http\Controllers;

use App\Models\DyedQuotation;
use App\Models\NettingFactroy;
use App\Models\NettingQuotation;
use App\Models\OrderDetail;
use App\Models\Store;
use App\Models\YarnLoss;
use App\Models\YarnQuotation;
use App\Models\YarnReceivedDyed;
use App\Models\YarnStoreStock;
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
        $dydeQuty = DyedQuotation::with('dyedFactory')->orderBy('id', 'desc')
            ->get();
        return view('dyed_quotation.index', compact('dydeQuty'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create() {

    //     $yearns = YarnQuotation::select('po_number')
    //         ->where('receving_factory', 'dyed')
    //         ->where('status', 'approved')
    //         ->groupby('po_number')
    //         ->get();
    //     $knitgFactory = NettingFactroy::where('status', 'active')
    //         ->get();
    //     return view('dyed_quotation.create', compact('yearns', 'knitgFactory'));
    // }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request) {
    //     //
    //     // return $request;
    //     $request->validate([
    //         'po_number' => 'required',
    //     ]);
    //     $successMessageStatus = 0;

    //     foreach ($request->items as $style => $items) {
    //         foreach ($items as $item) {
    //             // Main item
    //             $totalInnerQty = collect($item['inner_items'])->sum('quantity');

    //             if ($item['knit_quantity'] > 0 && $item['knit_quantity'] == $totalInnerQty) {
    //                 $successMessageStatus = 1;

    //                 if (!empty($item['inner_items']) && is_array($item['inner_items'])) {
    //                     foreach ($item['inner_items'] as $inner) {
    //                         DyedQuotation::create([
    //                             'dyed_factory_id'           => $item['dyed_factory_id'],
    //                             'description'               => $item['description'],
    //                             'order_id'                  => $request->order_id,
    //                             'order_number'              => $request->order_number,
    //                             'style'                     => $style,
    //                             'po_number'                 => $request->po_number,
    //                             'purchase_date'             => $request->order_date,
    //                             'approximate_delivery_date' => $request->approximate_delivery_date,
    //                             'remarks'                   => $request->remarks,
    //                             'netting_factory_id'        => $item['netting_factory_id'] ?? null,
    //                             'from_stock_quantity'       => $inner['form_stock_quantity'] ?? null,
    //                             'quantity'                  => $inner['quantity'] ?? null,
    //                             'price'                     => $item['rate'] ?? null,
    //                             'total_price'               => ($inner['quantity'] * $item['rate']) ?? null,
    //                             'created_by'                => Auth::id(),
    //                             'delivery_point_id'         => $inner['delivery_point'] ?? null,
    //                         ]);
    //                     }
    //                 }

    //             } else {
    //                 toastr("Enter Total Quantity in {$style}!", 'error');
    //             }
    //         }
    //     }

    //     if ($successMessageStatus === 1) {
    //         toastr("Style {$style} Netting Successfully Created!");
    //     }

    //     return back();
    // }

    /**
     * Display the specified resource.
     */
    public function show(DyedQuotation $dyedquotation) {
        $dyedquotation->load(
            "dyedFactory",
            "nettingFactory",
            "approvedBy",
            "creator",
            "lastUpdateBy"
        );
        return view('dyed_quotation.show', compact('dyedquotation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DyedQuotation $dyedquotation) {
        $knitgFactory = NettingFactroy::where('status', 'active')
            ->get();

        $yearnsQuotationSum = YarnQuotation::where('po_number', $dyedquotation->po_number)
            ->where('status', 'approved')
            ->where('style', $dyedquotation->style)
            ->sum('quantity');

        $dyedquotation->load('dyedYarnReceived');

        return view('dyed_quotation.edit', compact('dyedquotation', 'knitgFactory', 'yearnsQuotationSum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DyedQuotation $dyedquotation) {
        // return $request;
        $request->validate([
            'netting_factory_id' => 'required',
            'quantity'           => 'required',
            'price'              => 'required',
        ]);

        if ($request->status === "recevied") {
            if (!$request->has('challan')) {
                toastr()->error('Challan info not found!');
                return back();
            }

            $challan = $request->input('challan');

            $requiredFields = ['challan_number', 'vehicle_number', 'challan_date', 'received_date'];

            foreach ($requiredFields as $field) {
                if (empty($challan[$field])) {
                    toastr()->error('Please fill in all challan information!');
                    return back();
                }
            }
        }

        $dyedquotation->load('dyedYarnReceived');

        $dyedquotation->update([
            'purchase_date'             => $request->order_date,
            'approximate_delivery_date' => $request->approximate_delivery_date,
            'remarks'                   => $request->remarks,
            'netting_factory_id'        => $request->netting_factory_id ?? null,
            'quantity'                  => $request->quantity ?? null,
            'price'                     => $request->price ?? null,
            'total_price'               => $request->total_unit_price ?? null,
            'updated_by'                => Auth::id(),
            'delivery_point_id'         => $request->netting_factory_id ?? null,
            'status'                    => $request->status,
        ]);

        if ($request->status === "approved") {
            $dyedquotation->approved_by = Auth::id();
            $dyedquotation->save();
        }

        if ($request->status === "recevied") {
            if ($request->hasFile('challan.challan_file')) {
                $path = $request->file('challan.challan_file')->store('yarn_received_challan', 'public');
            } else {
                $path = $dyedquotation->dyedYarnReceived->challan_file ?? null;
            }
            $challanData = [
                'dyed_quotation_id' => $dyedquotation->id,
                // 'lot_number'     => $item['loat_no'],
                // 'bag_count'      => $item['bag_count'],
                'challan_date'      => $request->challan['challan_date'],
                'challan_number'    => $request->challan['challan_number'],
                'vehicle_number'    => $request->challan['vehicle_number'],
                'received_date'     => $request->challan['received_date'],
                'received_by'       => Auth::id(),
                'challan_file'      => $path,
            ];

            if ($dyedquotation->dyedYarnReceived) {
                $dyedquotation->dyedYarnReceived->update($challanData);
            } else {
                YarnReceivedDyed::create($challanData);
            }

        }

        toastr("Dyed Quotation Successfully Updated!");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DyedQuotation $dyedquotation) {
        $dyedquotation->delete();
        toastr('Dyed Quotation Successfully Deleted!');
        return back();
    }

    public function yarnDyedDistribute(DyedQuotation $dyedquotation) {
        $dyedquotation->load('dyedYarnknitQuot', 'dyedYarnLoss', 'dyedYarnStock');

        $knitFactory  = NettingFactroy::where('status', 'active')->get();
        $storeAddress = Store::where('status', 'active')->get();

        $orderDetail = OrderDetail::where('po_number', $dyedquotation->po_number)->where('style', $dyedquotation->style)->first();
        // return $dyedquotation;
        return view('dyed_quotation.distribute', compact('dyedquotation', 'knitFactory', 'orderDetail', 'storeAddress'));
    }

    public function yarnDyedDistributeStore(Request $request) {
        // $request->validate([
        //     'knit_factory_id' => 'required',
        //     'quantity'        => 'required',
        //     'price'           => 'required',
        // ]);

        $successMessageStatus = 0;

        if ($request->quantity) {
            $successMessageStatus = 1;
            NettingQuotation::create([
                'dyed_quotation_id'         => $request->dyed_quotation_id,
                'order_id'                  => $request->order_id,
                'style'                     => $request->style,
                'po_number'                 => $request->po_number,
                'order_date'                => $request->order_date,
                'approximate_delivery_date' => $request->approximate_delivery_date,
                'remarks'                   => $request->remarks,
                'netting_factory_id'        => $request->knit_factory_id,
                'quantity'                  => $request->quantity,
                'price'                     => $request->price,
                'total_price'               => $request->total_amount,
                'created_by'                => Auth::id(),
            ]);
        }

        if ($request->loss > 0) {
            $successMessageStatus = 1;
            YarnLoss::create([
                'dyed_quotation_id'    => $request->dyed_quotation_id,
                'quantity'             => $request->loss,
                'delived_factory_type' => 'dyed',
                'created_by'           => Auth::id(),
            ]);
        }

        if ($request->stock > 0) {
            $successMessageStatus = 1;
            YarnStoreStock::create([
                'dyed_quotation_id'    => $request->dyed_quotation_id,
                'po_number'            => $request->po_number,
                'style'                => $request->style,
                'quantity'             => $request->stock,
                'store_id'             => $request->store_id,
                'remarks'              => $request->remarks,
                'created_by'           => Auth::id(),
                'delived_factory_type' => 'dyed',
            ]);
        }
        if ($successMessageStatus === 1) {
            toastr("Successfully Created!");
        } else {
            toastr()->error("No data found!");
        }
        return back();
    }

}
