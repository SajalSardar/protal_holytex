<?php

namespace App\Http\Controllers;

use App\Models\DyedFactory;
use Illuminate\Http\Request;

class DyedFactoryController extends Controller {
    public function showAll() {
        $dyenFactory = DyedFactory::where('status', 'active')->get();
        if ($dyenFactory) {
            return json_encode($dyenFactory);
        } else {
            return 'Yarn not found!';
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $dyenFactory = DyedFactory::where('status', 'active')->get();
        return view('dyed_factory.index', compact('dyenFactory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'name'    => 'required|unique:dyed_factories,name',
            'address' => 'required',
        ]);

        DyedFactory::create($request->all());
        toastr('Dyed Factory Successfully Created!');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DyedFactory $dyedfactory) {
        return view('dyed_factory.edit', compact('dyedfactory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DyedFactory $dyedfactory) {
        $request->validate([
            'name'    => 'required|unique:dyed_factories,name,' . $dyedfactory->id,
            'address' => 'required',
        ]);

        $dyedfactory->update($request->all());
        toastr('Dyed Factory Successfully updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DyedFactory $dyedfactory) {
        $dyedfactory->delete();
        toastr('Dyed Factory Successfully Deleted!');
        return back();
    }
}
