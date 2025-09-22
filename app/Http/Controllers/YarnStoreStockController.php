<?php

namespace App\Http\Controllers;

use App\Models\YarnQuotation;
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
            "created_by"           => Auth::id(),
            "received_date"        => $request->received_date,
            "description"          => $request->description,
        ]);

        toastr('Data Successfully Updated!');
        return redirect()->route('yarnstorestock.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YarnStoreStock $yarnstorestock) {
        //
        $yarnstorestock->delete();
        toastr('Data Successfully Deleted!');
        return redirect()->route('yarnstorestock.index');
    }
}
