<?php

namespace App\Http\Controllers;

use App\Models\DyeingQuotation;
use App\Models\DyeingReceived;
use App\Models\GarmentsFactroy;
use App\Models\NettingLoss;
use App\Models\NettingReceivedGarments;
use App\Models\NettingStoreStock;
use App\Models\OrderDetail;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DyeingQuotationController extends Controller {

    // public function getNetting($po_number) {
    //     $dyeing = DyeingQuotation::where('po_number', $po_number)->pluck('style');

    //     $nettings = NettingQuotation::with('dyeingFactory', 'nettingFactory')
    //         ->where('po_number', $po_number)
    //         ->where('status', 'approved')
    //         ->where('delivery_factory_type', 'dyeing')
    //         ->whereNotIn('style', $dyeing)
    //         ->get();
    //     // ->groupBy(['style']);

    //     if ($nettings->isNotEmpty()) {
    //         return $nettings;
    //     } else {
    //         return response()->json(['message' => 'New Netting Quotation not found!']);
    //     }
    // }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $dyeings = DyeingQuotation::with('dyeingFactory')
            ->withSum('dyeingGarmentsQuot', 'quantity')
            ->withSum('dyeingStock', 'quantity')
            ->withSum('dyeingLoss', 'quantity')
            ->orderBy('id', 'desc')
            ->get();
        return view('dyeing_quotation.index', compact('dyeings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create() {
    //     $nettings = NettingQuotation::where('delivery_factory_type', 'dyeing')
    //         ->where('status', 'approved')
    //         ->select('po_number')
    //         ->groupby('po_number')
    //         ->get();

    //     $delivery_point = GarmentsFactroy::where('status', 'active')->get();
    //     return view('dyeing_quotation.create', compact('nettings', 'delivery_point'));
    // }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request) {
    //     //return $request;
    //     $request->validate([
    //         'po_number' => 'required',
    //     ]);

    //     foreach ($request->items as $style => $items) {
    //         foreach ($items as $index => $item) {
    //             DyeingQuotation::create([
    //                 'order_id'                  => $request->order_id,
    //                 'order_number'              => $request->order_number,
    //                 'style'                     => $style,
    //                 'po_number'                 => $request->po_number,
    //                 'purchase_date'             => $request->order_date,
    //                 'approximate_delivery_date' => $request->approximate_delivery_date,
    //                 'from_stock_quantity'       => $item['from_stock'],
    //                 'quantity'                  => $item['quot_quantity'],
    //                 'price'                     => $item['rate'],
    //                 'total_price'               => $item['total'],
    //                 'delivery_point_id'         => $item['delivery_point'],
    //                 'dyeing_factory_id'         => $item['dyeing_factory_id'],
    //                 'remarks'                   => $request->remarks,
    //                 'created_by'                => Auth::id(),
    //             ]);
    //         }
    //     }

    //     toastr('Dyeing Successfully Created!');
    //     return back();
    // }

    /**
     * Display the specified resource.
     */
    public function show(DyeingQuotation $dyeingquotation) {
        $dyeingquotation->load('dyeingFactory', 'garmentsFactory');
        return view('dyeing_quotation.show', compact('dyeingquotation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DyeingQuotation $dyeingquotation) {
        $delivery_point = GarmentsFactroy::where('status', 'active')->get();
        return view('dyeing_quotation.edit', compact('dyeingquotation', 'delivery_point'));
    }

    public function dyeingQtyStatusUpdate(Request $request) {
        if (!$request->style && !$request->po_number) {
            toastr('PO number and style not found!', 'error');
            return back();
        }
        DyeingQuotation::where('po_number', $request->po_number)->where('style', $request->style)->update([
            'status'      => $request->status,
            'updated_by'  => Auth::id(),
            'approved_by' => Auth::id(),
        ]);
        toastr('Dyeing quotation Status Updated!');
        return back();
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DyeingQuotation $dyeingquotation) {
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

        $dyeingquotation->update([
            'order_date'                => $request->order_date,
            'approximate_delivery_date' => $request->approximate_delivery_date,
            'quantity'                  => $request->quantity,
            'price'                     => $request->price,
            'total_price'               => $request->total_unit_price,
            'delivery_point_id'         => $request->delivery_point_id,
            'remarks'                   => $request->remarks,
            'status'                    => $request->status,
            'updated_by'                => Auth::id(),
        ]);
        if ($request->status === "approved") {
            $dyeingquotation->approved_by = Auth::id();
            $dyeingquotation->save();
        }

        if ($request->status === "received") {
            if ($request->hasFile('challan.challan_file')) {
                $path = $request->file('challan.challan_file')->store('dyeing_received_challan', 'public');
            } else {
                $path = $dyeingquotation->dyedYarnReceived->challan_file ?? null;
            }
            $challanData = [
                'dyeing_quotation_id' => $dyeingquotation->id,
                // 'lot_number'     => $item['loat_no'],
                // 'bag_count'      => $item['bag_count'],
                'challan_date'        => $request->challan['challan_date'],
                'challan_number'      => $request->challan['challan_number'],
                'vehicle_number'      => $request->challan['vehicle_number'],
                'received_date'       => $request->challan['received_date'],
                'received_by'         => Auth::id(),
                'challan_file'        => $path,
            ];

            if ($dyeingquotation->dyeingReceived) {
                $dyeingquotation->dyeingReceived->update($challanData);
            } else {
                DyeingReceived::create($challanData);
            }

        }

        toastr('Dyeing Successfully Updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DyeingQuotation $dyeingquotation) {
        $dyeingquotation->delete();
        toastr('Dyeing Quotation Successfully Deleted!');
        return back();
    }

    public function dyeingDistribute(DyeingQuotation $dyeingquotation) {
        $dyeingquotation->loadSum([
            'dyeingGarmentsQuot as knit_sum' => function ($q) {},
            'dyeingLoss as loss_sum'         => function ($q) {},
            'dyeingStock as stock_sum'       => function ($q) {},
        ], 'quantity');

        $garmentsFactory = GarmentsFactroy::where('status', 'active')->get();
        $storeAddress    = Store::where('status', 'active')->get();

        $orderDetail = OrderDetail::where('po_number', $dyeingquotation->po_number)->where('style', $dyeingquotation->style)->first();
        // return $dyeingquotation;
        return view('dyeing_quotation.distribute', compact('dyeingquotation', 'garmentsFactory', 'orderDetail', 'storeAddress'));
    }

    public function dyeingDistributeStore(Request $request) {
        // $request->validate([
        //     'knit_factory_id' => 'required',
        //     'quantity'        => 'required',
        //     'price'           => 'required',
        // ]);

        $successMessageStatus = 0;

        if ($request->quantity) {
            $successMessageStatus = 1;
            NettingReceivedGarments::create([
                'dyeing_quotation_id'       => $request->dyeing_quotation_id,
                'description'               => $request->description,
                'order_id'                  => $request->order_id,
                'style'                     => $request->style,
                'po_number'                 => $request->po_number,
                'order_date'                => $request->order_date,
                'approximate_delivery_date' => $request->approximate_delivery_date,
                'remarks'                   => $request->remarks,
                'garments_factory_id'       => $request->garments_factory_id,
                'quantity'                  => $request->quantity,
                // 'price'                     => $request->price,
                // 'total_price'               => $request->total_amount,
                'created_by'                => Auth::id(),
                'fabric_type'               => 'dyeing',
                'status'                    => 'pending',
            ]);
        }

        if ($request->loss > 0) {
            $successMessageStatus = 1;
            NettingLoss::create([
                'description'         => $request->description,
                'order_id'            => $request->order_id,
                'style'               => $request->style,
                'po_number'           => $request->po_number,
                'dyeing_quotation_id' => $request->dyeing_quotation_id,
                'quantity'            => $request->loss,
                'fabric_type'         => 'dyeing',
                'created_by'          => Auth::id(),
            ]);
        }

        if ($request->stock > 0) {
            $successMessageStatus = 1;
            NettingStoreStock::create([
                'dyeing_quotation_id' => $request->dyeing_quotation_id,
                'order_id'            => $request->order_id,
                'description'         => $request->description,
                'po_number'           => $request->po_number,
                'style'               => $request->style,
                'quantity'            => $request->stock,
                'store_id'            => $request->store_id,
                'remarks'             => $request->remarks,
                'created_by'          => Auth::id(),
                'fabric_type'         => 'dyeing',
                'status'              => 'pending',
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
