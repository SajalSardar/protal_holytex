<?php

namespace App\Http\Controllers;

use App\Models\NettingStoreStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NettingStoreStockController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $rowNettingstock = NettingStoreStock::with('yarnQuotations:po_number,style,description')->where('delived_factory_type', 'netting')->orderBy('id', 'desc')->get();

        // return $rowNettingstock;
        return view('netting_store.index', compact('rowNettingstock'));
    }

    public function dyeingKnitStock() {
        $dyeingNettingstock = NettingStoreStock::with('yarnQuotations:po_number,style,description')->where('delived_factory_type', 'dyeing')->orderBy('id', 'desc')->get();

        return view('netting_store.dyeing', compact('dyeingNettingstock'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('netting_store.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'po_number'     => "required",
            'style'         => "required",
            'quantity'      => "required",
            'store_address' => "required",
        ]);

        NettingStoreStock::create([
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
        ]);

        toastr('Data Successfully Created!');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(NettingStoreStock $nettingStoreStock) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NettingStoreStock $nettingStoreStock) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NettingStoreStock $nettingStoreStock) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NettingStoreStock $nettingStoreStock) {
        //
    }
}
