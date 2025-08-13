<?php

namespace App\Http\Controllers;

use App\Models\GarmentsFactroy;
use Illuminate\Http\Request;

class GarmentsFactroyController extends Controller {
    public function showAll() {
        $garmentsFactroy = GarmentsFactroy::where('status', 'active')->get();
        if ($garmentsFactroy) {
            return json_encode($garmentsFactroy);
        } else {
            return 'Yarn not found!';
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
        $garmentsFactory = GarmentsFactroy::where('status', 'active')->orderBy('id', 'desc')->get();
        return view('garments_factory.index', compact('garmentsFactory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'name'    => 'required|unique:garments_factroys,name',
            'address' => 'required',
        ]);

        GarmentsFactroy::create($request->all());
        toastr('Garments Factory Successfully Created!');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GarmentsFactroy $garmentsFactroy) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GarmentsFactroy $garmentsFactroy) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GarmentsFactroy $garmentsFactroy) {
        //
    }
}
