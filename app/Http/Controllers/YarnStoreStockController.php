<?php

namespace App\Http\Controllers;

use App\Models\YarnStoreStock;
use Illuminate\Http\Request;

class YarnStoreStockController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $yarnStocks = YarnStoreStock::with('yarnQty')->orderBy('id', 'desc')->get();
        // return $yarnStocks;
        return view('yarn_store.index', compact('yarnStocks'));
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
    public function show(YarnStoreStock $yarnStoreStock) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(YarnStoreStock $yarnStoreStock) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, YarnStoreStock $yarnStoreStock) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YarnStoreStock $yarnStoreStock) {
        //
    }
}
