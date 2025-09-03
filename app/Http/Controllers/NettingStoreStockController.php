<?php

namespace App\Http\Controllers;

use App\Models\NettingStoreStock;
use Illuminate\Http\Request;

class NettingStoreStockController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $rowNettingstock    = NettingStoreStock::with('yarnQuotations:po_number,style,description')->where('delived_factory_type', 'netting')->orderBy('id', 'desc')->get();
        $dyeingNettingstock = NettingStoreStock::with('yarnQuotations:po_number,style,description')->where('delived_factory_type', 'dyeing')->orderBy('id', 'desc')->get();

        return view('netting_store.index', compact('rowNettingstock', 'dyeingNettingstock'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

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
