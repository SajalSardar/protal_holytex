<?php

namespace App\Http\Controllers;

use App\Models\YarnLoss;
use App\Models\YarnQuotation;
use App\Models\YarnReceived;
use App\Models\YarnReceivedDyed;
use App\Models\YarnStoreStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YarnStoreStockController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $yarnStocks = YarnStoreStock::with('yarnQty')
            ->where('delived_factory_type', 'yarn')
            ->orderBy('id', 'desc')
            ->get();
        $yearnsQot = YarnQuotation::with('nettingFactory', 'dyedFactory')
            ->withSum('yarnReceivedFromStock', 'quantity')
        // ->where('receving_factory', 'knit')
            ->where('status', 'approved')
            ->get();
        // $yearnsQotPo = $yearnsQot->pluck('po_number')->unique();
        // return $yearnsQot;
        return view('yarn_store.index', compact('yarnStocks', 'yearnsQot'));
    }
    public function dyedYarnStock() {
        $yarnStocks = YarnStoreStock::with('yarnQty')
            ->where('delived_factory_type', 'dyed')
            ->orderBy('id', 'desc')
            ->get();
        $yearnsQot = YarnQuotation::with('nettingFactory', 'dyedFactory')
            ->withSum('yarnReceivedFromStock', 'quantity')
            ->where('receving_factory', 'knit')
            ->where('status', 'approved')
            ->get();
        // $yearnsQotPo = $yearnsQot->pluck('po_number')->unique();
        // return $yearnsQot;
        return view('yarn_store.dyed', compact('yarnStocks', 'yearnsQot'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('yarn_store.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        // return $request;
        $request->validate([
            'po_number'     => "required",
            'style'         => "required",
            'quantity'      => "required",
            'store_address' => "required",
            'description'   => "required",
        ]);

        YarnStoreStock::create([
            "po_number"            => $request->po_number,
            "style"                => $request->style,
            "remarks"              => $request->remarks,
            "lot_number"           => $request->loat_no,
            "bag_count"            => $request->bag_count,
            "quantity"             => $request->quantity,
            "store_address"        => $request->store_address,
            "delived_factory_type" => $request->delived_factory_type,
            "created_by"           => Auth::id(),
            "received_date"        => $request->received_date,
            "description"          => $request->description,
        ]);

        toastr('Data Successfully Created!');
        return back();

    }

    //use stock
    public function useYarnStock(Request $request) {
        $request->validate([
            'stock_id'          => "required",
            'yarn_quotation_id' => "required",
            'challan_file'      => "nullable|max:512|image",
        ]);

        $yarnStock = YarnStoreStock::findOrFail($request->stock_id);

        if ($yarnStock->quantity <= 0) {
            toastr('Stock Quantity is 0', 'info');
            return back();
        }

        $yearnQut = YarnQuotation::findOrFail($request->yarn_quotation_id);

        // Load sums based on factory type
        if ($yearnQut->receving_factory === 'knit') {
            $yearnQut->loadSum('yarnReceivedFromStock', 'quantity')
                ->loadSum('yarnLossFromStock', 'quantity');
            $yearnReceivedTotal = $yearnQut->yarn_received_from_stock_sum_quantity
             + $yearnQut->yarn_loss_from_stock_sum_quantity;

        } elseif ($yearnQut->receving_factory === 'dyed') {

            $yearnQut->loadSum('yarnReceivedFromStockDyed', 'quantity')
                ->loadSum('yarnLossFromStockDyed', 'quantity');
            $yearnReceivedTotal = $yearnQut->yarn_received_from_stock_dyed_sum_quantity
             + $yearnQut->yarn_loss_from_stock_dyed_sum_quantity;

        } else {
            toastr('Invalid factory type!', 'error');
            return back();
        }

        // Store challan file if uploaded
        $path = $request->hasFile('challan_file')
        ? $request->file('challan_file')->store('yarn_received_challan', 'public')
        : null;

        $totalYearnQut = $yearnQut->quantity + $yearnQut->from_stock_quantity;

        $yarnRec     = (float) ($request->input_yarn ?? 0);
        $yarnLossRec = (float) ($request->input_loss ?? 0);
        $newReceived = $yarnRec + $yarnLossRec;
        $total       = $newReceived + $yearnReceivedTotal;

        $successMessageStatus = false;

        // Save Yarn Received
        if ($yarnRec > 0 && $totalYearnQut > $yearnReceivedTotal && $totalYearnQut >= $total) {
            $receivedData = [
                'yarn_quotation_id' => $request->yarn_quotation_id,
                'po_number'         => $request->po_number,
                'style'             => $request->style,
                'quantity'          => $yarnRec,
                'lot_number'        => $request->loat_no,
                'bag_count'         => $request->bag_count,
                'challan_date'      => $request->challan_date,
                'challan_number'    => $request->challan_number,
                'vehicle_number'    => $request->vehicle_number,
                'received_date'     => $request->received_date,
                'received_by'       => Auth::id(),
                'remarks'           => $request->remarks,
                'is_stock_received' => 'Yes',
                'stock_id'          => $request->stock_id,
                'challan_file'      => $path,
            ];

            if ($yearnQut->receving_factory === 'knit') {
                YarnReceived::create($receivedData);
            } else {
                YarnReceivedDyed::create($receivedData);
            }

            $successMessageStatus = true;
        }

        // Save Yarn Loss
        if ($yarnLossRec > 0 && $totalYearnQut > $yearnReceivedTotal && $totalYearnQut >= $total) {

            YarnLoss::create([
                'delived_factory_type' => 'yarn',
                'is_stock_received'    => 'Yes',
                'stock_id'             => $request->stock_id,
                'quantity'             => $yarnLossRec,
                'created_by'           => Auth::id(),
            ]);

            $successMessageStatus = true;
        }

        // Update quotation if fully received
        if ((float) $totalYearnQut === (float) $total) {
            $yearnQut->update([
                'status'        => 'recevied',
                'delivery_date' => $request->received_date,
                'updated_by'    => Auth::id(),
            ]);
        }

        // Update stock
        if ($newReceived > 0) {
            $yarnStock->decrement('quantity', $newReceived);
        }

        // Response
        toastr(
            $successMessageStatus ? 'Data Successfully Created!' : 'No Input data found!',
            $successMessageStatus ? 'success' : 'error'
        );

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(YarnStoreStock $yarnstorestock) {
        // $yarnstorestock->load('usesStock', 'yarnQty');
        // return $yarnstorestock;
        $yarnstorestock = YarnStoreStock::with('yarnQty')->find($yarnstorestock->id);
        $usesStock      = $yarnstorestock->usesStock()
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('yarn_store.show', compact('yarnstorestock', 'usesStock'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(YarnStoreStock $yarnstorestock) {
        //
        return view('yarn_store.edit', compact('yarnstorestock'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, YarnStoreStock $yarnstorestock) {
        $request->validate([
            'po_number'     => "required",
            'style'         => "required",
            'quantity'      => "required",
            'store_address' => "required",
            'description'   => "required",
        ]);

        $yarnstorestock->update([
            "po_number"            => $request->po_number,
            "style"                => $request->style,
            "remarks"              => $request->remarks,
            "lot_number"           => $request->loat_no,
            "bag_count"            => $request->bag_count,
            "quantity"             => $request->quantity,
            "store_address"        => $request->store_address,
            "delived_factory_type" => $request->delived_factory_type,
            "updated_by"           => Auth::id(),
            "received_date"        => $request->received_date,
            "description"          => $request->description,
        ]);

        toastr('Data Successfully Updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YarnStoreStock $yarnstorestock) {
        //
        $yarnstorestock->delete();
        toastr('Data Successfully Deleted!');
        return back();
    }
}
