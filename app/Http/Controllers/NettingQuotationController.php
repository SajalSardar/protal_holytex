<?php

namespace App\Http\Controllers;

use App\Models\DyeingFactroy;
use App\Models\DyeingQuotation;
use App\Models\GarmentsFactroy;
use App\Models\NettingLoss;
use App\Models\NettingQuotation;
use App\Models\NettingReceived;
use App\Models\NettingReceivedGarments;
use App\Models\NettingStoreStock;
use App\Models\OrderDetail;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NettingQuotationController extends Controller {

    // public function getYarnStyleByPo($po_number) {
    //     $nettings = NettingQuotation::where('po_number', $po_number)->pluck('style');
    //     $yearns   = YarnQuotation::with('yarnFactory', 'nettingFactory')
    //         ->where('po_number', $po_number)
    //         ->where('receving_factory', 'knit')
    //         ->where('status', 'approved')
    //         ->whereNotIn('style', $nettings)
    //         ->get()->groupBy(['style', 'netting_factory_id']);

    //     $dyeds = DyedQuotation::with('dyedFactory', 'nettingFactory')
    //         ->where('po_number', $po_number)
    //         ->whereNotIn('style', $nettings)
    //         ->where('status', 'approved')
    //         ->get()->groupBy(['style', 'delivery_point_id']);

    //     return json_encode([
    //         'yearns' => $yearns ?? null,
    //         'dyeds'  => $dyeds ?? null,
    //     ]);

    // }

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
    // public function create() {

    //     $yearns = YarnQuotation::select('po_number')
    //         ->where('receving_factory', 'knit')
    //         ->where('status', 'approved')
    //         ->groupby('po_number')
    //         ->get();
    //     return view('netting_quotation.create', compact('yearns'));
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
    //             if (!empty($item['inner_items']) && is_array($item['inner_items'])) {
    //                 $totalInnerQty = collect($item['inner_items'])->sum('quantity');

    //                 if ($item['knit_quantity'] > 0 && $item['knit_quantity'] == $totalInnerQty) {
    //                     $successMessageStatus = 1;
    //                     foreach ($item['inner_items'] as $inner) {
    //                         NettingQuotation::create([
    //                             'order_id'                  => $request->order_id,
    //                             'order_number'              => $request->order_number,
    //                             'style'                     => $style,
    //                             'po_number'                 => $request->po_number,
    //                             'purchase_date'             => $request->order_date,
    //                             'approximate_delivery_date' => $request->approximate_delivery_date,
    //                             'remarks'                   => $request->remarks,
    //                             'delivery_factory_type'     => $item['delevary_poin_check'] ?? null,
    //                             'netting_factory_id'        => $item['netting_factory_id'] ?? null,
    //                             'from_stock_quantity'       => $inner['form_stock_quantity'] ?? null,
    //                             'quantity'                  => $inner['quantity'],
    //                             'price'                     => $item['rate'] ?? null,
    //                             'total_price'               => ($inner['quantity'] * $item['rate']) ?? null,
    //                             'created_by'                => Auth::id(),
    //                             'delivery_point_id'         => $inner['delivery_point'] ?? null,
    //                         ]);
    //                     }
    //                 } else {
    //                     toastr("Enter Total Quantity in {$style}!", 'error');
    //                 }

    //             } else {
    //                 toastr("Enter Quantity in {$style}!", 'error');
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
    public function show(NettingQuotation $nettingquotation) {
        return view('netting_quotation.show', compact('nettingquotation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NettingQuotation $nettingquotation) {

        $nettingquotation->load('knittReceived');

        $dyeingFactroy = DyeingFactroy::where('status', 'active')->get();

        $garmentsFactroy = GarmentsFactroy::where('status', 'active')->get();

        // return $nettingquotation;

        return view('netting_quotation.edit', compact('nettingquotation', 'dyeingFactroy', 'garmentsFactroy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NettingQuotation $nettingquotation) {
        // return $request;
        $request->validate([
            'po_number' => 'required',
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

        $nettingquotation->update([
            'purchase_date'             => $request->order_date,
            'approximate_delivery_date' => $request->approximate_delivery_date,
            'remarks'                   => $request->remarks,
            'quantity'                  => $request->quantity,
            'price'                     => $request->price,
            'total_price'               => $request->total_unit_price,
            'updated_by'                => Auth::id(),
            'delivery_point_id'         => $request->delivery_point_id,
            'status'                    => $request->status,
            'delivery_factory_type'     => $request->delivery_factory_type,
        ]);

        if ($request->status === "approved") {
            $nettingquotation->approved_by = Auth::id();
            $nettingquotation->save();
        }

        if ($request->status === "recevied") {
            if ($request->hasFile('challan.challan_file')) {
                $path = $request->file('challan.challan_file')->store('knitt_received_challan', 'public');
            } else {
                $path = $nettingquotation->knittReceived->challan_file ?? null;
            }
            $challanData = [
                'netting_quotation_id' => $nettingquotation->id,
                'challan_date'         => $request->challan['challan_date'],
                'challan_number'       => $request->challan['challan_number'],
                'vehicle_number'       => $request->challan['vehicle_number'],
                'received_date'        => $request->challan['received_date'],
                'received_by'          => Auth::id(),
                'challan_file'         => $path,
            ];

            if ($nettingquotation->knittReceived) {
                $nettingquotation->knittReceived->update($challanData);
            } else {
                NettingReceived::create($challanData);
            }

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

    public function knitDistribute(NettingQuotation $nettingquotation) {

        // return $dyedquotation;
        $dyeingFactroy = DyeingFactroy::where('status', 'active')->get();

        $garmentsFactroy = GarmentsFactroy::where('status', 'active')->get();

        $orderDetail  = OrderDetail::where('po_number', $nettingquotation->po_number)->where('style', $nettingquotation->style)->first();
        $storeAddress = Store::where('status', 'active')->get();
        return view('netting_quotation.distribute', compact('nettingquotation', 'orderDetail', 'storeAddress', 'dyeingFactroy', 'garmentsFactroy'));
    }

    public function yarnDyedDistributeStore(Request $request) {
        // $request->validate([
        //     'knit_factory_id' => 'required',
        //     'quantity'        => 'required',
        //     'price'           => 'required',
        // ]);

        $successMessageStatus = 0;

        if ($request->quantity && $request->delived_factory_type === 'dyeing') {
            $successMessageStatus = 1;
            DyeingQuotation::create([
                'netting_quotation_id'      => $request->netting_quotation_id,
                'description'               => $request->description,
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

        if ($request->quantity && $request->delived_factory_type === 'garments') {
            $successMessageStatus = 1;
            NettingReceivedGarments::create([
                'netting_quotation_id'      => $request->netting_quotation_id,
                'description'               => $request->description,
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
                'fabric_type'               => 'knitting',
            ]);
        }

        if ($request->loss > 0) {
            $successMessageStatus = 1;
            NettingLoss::create([
                'description'          => $request->description,
                'order_id'             => $request->order_id,
                'style'                => $request->style,
                'netting_quotation_id' => $request->netting_quotation_id,
                'quantity'             => $request->loss,
                'fabric_type'          => 'knitting',
                'created_by'           => Auth::id(),
            ]);
        }

        if ($request->stock > 0) {
            $successMessageStatus = 1;
            NettingStoreStock::create([
                'netting_quotation_id' => $request->netting_quotation_id,
                'order_id'             => $request->order_id,
                'description'          => $request->description,
                'po_number'            => $request->po_number,
                'style'                => $request->style,
                'quantity'             => $request->stock,
                'store_id'             => $request->store_id,
                'remarks'              => $request->remarks,
                'created_by'           => Auth::id(),
                'fabric_type'          => 'knitting',
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
