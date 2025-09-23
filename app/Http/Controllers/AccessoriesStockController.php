<?php

namespace App\Http\Controllers;

use App\Models\AccessoriesStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('accessories_stock.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'po_number'     => "required",
            'style'         => "required",
            'quantity'      => "required",
            'description'   => "required",
            'store_address' => "required",
        ]);

        AccessoriesStock::create([
            "unit"          => $request->unit,
            "description"   => $request->description,
            "po_number"     => $request->po_number,
            "style"         => $request->style,
            "remarks"       => $request->remarks,
            "lot_number"    => $request->loat_no,
            "bag_count"     => $request->bag_count,
            "quantity"      => $request->quantity,
            "store_address" => $request->store_address,
            "created_by"    => Auth::id(),
            "received_date" => $request->received_date,
        ]);

        toastr('Data Successfully Created!');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(AccessoriesStock $accessoriesstock) {
        //

        return view('accessories_stock.show', compact('accessoriesstock'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccessoriesStock $accessoriesstock) {
        return view('accessories_stock.edit', compact('accessoriesstock'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccessoriesStock $accessoriesstock) {

        // return $request;
        $request->validate([
            'po_number'     => "required",
            'style'         => "required",
            'quantity'      => "required",
            'description'   => "required",
            'store_address' => "required",
        ]);

        $accessoriesstock->update([
            "unit"          => $request->unit,
            "description"   => $request->description,
            "po_number"     => $request->po_number,
            "style"         => $request->style,
            "remarks"       => $request->remarks,
            "lot_number"    => $request->loat_no,
            "bag_count"     => $request->bag_count,
            "quantity"      => $request->quantity,
            "store_address" => $request->store_address,
            "updated_by"    => Auth::id(),
            "received_date" => $request->received_date,
        ]);

        toastr('Data Successfully Created!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccessoriesStock $accessoriesstock) {
        $accessoriesstock->delete();
        toastr('Data Successfully Deleted!');
        return back();
    }
}
