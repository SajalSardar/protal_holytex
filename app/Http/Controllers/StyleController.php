<?php

namespace App\Http\Controllers;

use App\Models\Style;
use Illuminate\Http\Request;

class StyleController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $styles = Style::where('status', 'active')->orderBy('id', 'desc')->get();
        return view('style_view.index', compact('styles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'style_name' => 'required|unique:styles,style_name',
        ]);

        Style::create($request->all());
        toastr('Style Successfully Created!');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Style $style) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Style $style) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Style $style) {
        //
    }
}
