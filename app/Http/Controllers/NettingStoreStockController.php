<?php

namespace App\Http\Controllers;

use App\Models\DyeingFactroy;
use App\Models\DyeingQuotation;
use App\Models\GarmentsFactroy;
use App\Models\NettingLoss;
use App\Models\NettingQuotation;
use App\Models\NettingReceivedGarments;
use App\Models\NettingStoreStock;
use App\Models\OrderDetail;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NettingStoreStockController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $rowNettingstock = NettingStoreStock::with('yarnQuotations:po_number,style,description')
            ->where('fabric_type', 'knitting')
            ->orderBy('id', 'desc')
            ->get();

        $nettingQot = NettingQuotation::with('nettingFactory')
        // ->withSum('yarnReceivedFromStock', 'quantity')
        // ->where('receving_factory', 'knit')
            ->where('status', 'approved')
            ->get();

        // return $rowNettingstock;
        return view('netting_store.index', compact('rowNettingstock', 'nettingQot'));
    }

    public function dyeingKnitStock() {
        $dyeingNettingstock = NettingStoreStock::with('yarnQuotations:po_number,style,description', 'storeAddress')
            ->where('fabric_type', 'dyeing')
            ->orderBy('id', 'desc')
            ->get();

        return view('netting_store.dyeing', compact('dyeingNettingstock'));
    }

    public function knitDistributeCreate($id) {
        $nettingStock = NettingStoreStock::with('storeAddress')
            ->where('fabric_type', 'knitting')
            ->orderBy('id', 'desc')
            ->where('id', $id)
            ->first();
        // return $yarnStock->quantity;
        $dyeingFactory   = DyeingFactroy::where('status', 'active')->get();
        $garmentsFactory = GarmentsFactroy::where('status', 'active')->get();
        $orderDetails    = OrderDetail::select('po_number', 'order_id', 'style')->where('status', 'processing')->orderBy('id', 'desc')->get();

        $dyeingQuotation         = DyeingQuotation::where('stock_id', $id)->sum('quantity');
        $nettingReceivedGarments = NettingReceivedGarments::where('stock_id', $id)->sum('quantity');
        $useStockLossSum         = NettingLoss::where('stock_id', $id)->where('fabric_type', 'knitting')->sum('quantity');
        $useStockSum             = $dyeingQuotation + $nettingReceivedGarments;

        return view('netting_store.distribute', compact('nettingStock', 'dyeingFactory', 'garmentsFactory', 'orderDetails', 'useStockSum', 'useStockLossSum'));
    }

    //use stock
    public function knitDistributeStock(Request $request) {
        // return $request;

        $request->validate([
            'stock_id'           => "required",
            'receiver_po_number' => 'required_without:loss|required_with:quantity|nullable',
            'quantity'           => 'required_without:loss|nullable',
        ]);

        $successMessageStatus = false;

        if ($request->quantity) {
            $receiver_po = explode('-', $request->receiver_po_number);

            $orderDetail = OrderDetail::where('po_number', trim($receiver_po[0]))
                ->where('style', trim($receiver_po[1]))
                ->first();

            if (!$orderDetail) {
                toastr('Order Details Not Found!', 'error');
                return back();
            }

            if ($request->quantity && $request->delived_factory_type === 'dyeing') {
                $successMessageStatus = true;
                DyeingQuotation::create([
                    'stock_id'                  => $request->stock_id,
                    'description'               => $request->description,
                    'order_id'                  => $request->order_id,
                    'style'                     => trim($receiver_po[1]),
                    'po_number'                 => trim($receiver_po[0]),
                    'order_date'                => $request->order_date,
                    'approximate_delivery_date' => $request->approximate_delivery_date,
                    'remarks'                   => $request->remarks,
                    'dyeing_factory_id'         => $request->dyeing_factory_id,
                    'quantity'                  => $request->quantity,
                    'price'                     => $request->price,
                    'total_price'               => $request->total_amount,
                    'created_by'                => Auth::id(),
                ]);
            }

            if ($request->quantity && $request->delived_factory_type === 'garments') {
                $successMessageStatus = true;
                NettingReceivedGarments::create([
                    'stock_id'                  => $request->stock_id,
                    'description'               => $request->description,
                    'order_id'                  => $request->order_id,
                    'style'                     => trim($receiver_po[1]),
                    'po_number'                 => trim($receiver_po[0]),
                    'order_date'                => $request->order_date,
                    'approximate_delivery_date' => $request->approximate_delivery_date,
                    'remarks'                   => $request->remarks,
                    'garments_factory_id'       => $request->garments_factory_id,
                    'quantity'                  => $request->quantity,
                    // 'price'                     => $request->price,
                    // 'total_price'               => $request->total_amount,
                    'created_by'                => Auth::id(),
                    'fabric_type'               => 'knitting',
                    'status'                    => 'pending',
                ]);
            }

            $successMessageStatus = true;
        }

        // Save Yarn Loss
        if ($request->loss) {

            NettingLoss::create([
                'fabric_type' => 'knitting',
                'stock_id'    => $request->stock_id,
                'quantity'    => $request->loss,
                'created_by'  => Auth::id(),
                'style'       => $request->style,
                'po_number'   => $request->po_number,
                'order_id'    => $request->order_id,
                'description' => $request->description,
            ]);

            $successMessageStatus = true;
        }

        toastr(
            $successMessageStatus ? 'Data Successfully Created!' : 'No Input data found!',
            $successMessageStatus ? 'success' : 'error'
        );

        return back();
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create() {
    //     return view('netting_store.create');
    // }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request) {
    //     $request->validate([
    //         'po_number'     => "required",
    //         'style'         => "required",
    //         'quantity'      => "required",
    //         'store_address' => "required",
    //     ]);

    //     NettingStoreStock::create([
    //         "po_number"            => $request->po_number,
    //         "style"                => $request->style,
    //         "remarks"              => $request->remarks,
    //         "lot_number"           => $request->loat_no,
    //         "bag_count"            => $request->bag_count,
    //         "quantity"             => $request->quantity,
    //         "store_address"        => $request->store_address,
    //         "delived_factory_type" => $request->delived_factory_type,
    //         "created_by"           => Auth::id(),
    //         "received_date"        => $request->received_date,
    //     ]);

    //     toastr('Data Successfully Created!');
    //     return back();
    // }

    /**
     * Display the specified resource.
     */
    public function show(NettingStoreStock $nettingstorestock) {
        $nettingstorestock->load('yarnQuotations:po_number,style,description');
        return view('netting_store.show', compact('nettingstorestock'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NettingStoreStock $nettingstorestock) {
        $nettingstorestock->load('yarnQuotations:po_number,style,description');
        $storeAddress = Store::where('status', 'active')->get();
        return view('netting_store.edit', compact('nettingstorestock', 'storeAddress'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NettingStoreStock $nettingstorestock) {

        $request->validate([
            'po_number'     => "required",
            'style'         => "required",
            'quantity'      => "required",
            'store_address' => "required",
            'description'   => "required",
        ]);

        if ($request->hasFile('challan_file')) {
            $path = $request->file('challan_file')->store('knitting_received_challan', 'public');
        } else {
            $path = $nettingstorestock->challan_file ?? null;
        }

        $nettingstorestock->update([
            "remarks"        => $request->remarks,
            "lot_number"     => $request->loat_no,
            "bag_count"      => $request->bag_count,
            "quantity"       => $request->quantity,
            "store_id"       => $request->store_address,
            "updated_by"     => Auth::id(),
            "received_date"  => $request->received_date,
            "description"    => $request->description,
            "status"         => $request->status,
            "challan_file"   => $path,
            "vehicle_number" => $request->vehicle_number,
            "challan_date"   => $request->challan_date,
            "challan_number" => $request->challan_number,
        ]);

        toastr('Data Successfully Created!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NettingStoreStock $nettingstorestock) {
        $nettingstorestock->delete();
        toastr('Data Successfully Deleted!');
        return back();
    }
}
