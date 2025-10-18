<?php

namespace App\Http\Controllers;

use App\Models\YarnFactroy;
use Illuminate\Http\Request;

class YarnFactroyController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $yarnFactory = YarnFactroy::where('status', 'active')->orderBy('id', 'desc')->get();
        return view('yarn_factory.index', compact('yarnFactory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'name'    => 'required|unique:yarn_factroys,name',
            'address' => 'required',
        ]);

        YarnFactroy::create($request->all());
        toastr('Yarn Factory Successfully Created!');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(YarnFactroy $yarnfactroy) {
        return view('yarn_factory.edit', compact('yarnfactroy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, YarnFactroy $yarnfactroy) {
        $request->validate([
            'name'    => 'required|unique:yarn_factroys,name,' . $yarnfactroy->id,
            'address' => 'required',
        ]);

        $yarnfactroy->update($request->all());
        toastr('Yarn Factory Successfully Updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YarnFactroy $yarnfactroy) {
        $yarnfactroy->delete();
        toastr('Yarn Factory Successfully Deleted!');
        return back();
    }
}
