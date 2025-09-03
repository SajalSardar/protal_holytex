<?php

namespace App\Http\Controllers;

use App\Models\AccessoriesStock;
use Illuminate\Http\Request;

class AccessoriesStockController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $accStock = AccessoriesStock::with('accessoriesQty')->orderBy('id', 'desc')->get();
        // return $accStock;
        return view('accessories_stock.index', compact('accStock'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AccessoriesStock $accessoriesStock) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccessoriesStock $accessoriesStock) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccessoriesStock $accessoriesStock) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccessoriesStock $accessoriesStock) {
        //
    }
}
