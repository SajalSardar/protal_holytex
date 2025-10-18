<?php

namespace App\Http\Controllers;

use App\Models\NettingFactroy;
use Illuminate\Http\Request;

class NettingFactroyController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $nettingFactory = NettingFactroy::where('status', 'active')->orderBy('id', 'desc')->get();
        return view('netting_factory.index', compact('nettingFactory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'name'    => 'required|unique:netting_factroys,name',
            'address' => 'required',
        ]);

        NettingFactroy::create($request->all());
        toastr('Netting Factory Successfully Created!');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NettingFactroy $nettingfactroy) {
        return view('netting_factory.edit', compact('nettingfactroy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NettingFactroy $nettingfactroy) {
        $request->validate([
            'name'    => 'required|unique:netting_factroys,name,' . $nettingfactroy->id,
            'address' => 'required',
        ]);

        $nettingfactroy->update($request->all());
        toastr('Netting Factory Successfully Updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NettingFactroy $nettingfactroy) {
        $nettingfactroy->delete();
        toastr('Netting Factory Successfully Deleted!');
        return back();
    }
}
