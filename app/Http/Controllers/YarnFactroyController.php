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
    public function edit(YarnFactroy $yarnFactroy) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, YarnFactroy $yarnFactroy) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YarnFactroy $yarnFactroy) {
        //
    }
}
