<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller {
    public function index() {
        $storeList = Store::where('status', 'active')->orderBy('id', 'desc')->get();
        return view('store_address.index', compact('storeList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'name'    => 'required|unique:stores,name',
            'address' => 'required',
        ]);

        Store::create($request->all());
        toastr('Store Successfully Created!');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Store $store) {
        return view('store_address.edit', compact('store'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Store $store) {
        $request->validate([
            'name'    => 'required|unique:stores,name,' . $store->id,
            'address' => 'required',
        ]);

        $store->update($request->all());
        toastr('Store Successfully Updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store) {
        $store->delete();
        toastr('Store Successfully Deleted!');
        return back();
    }
}
