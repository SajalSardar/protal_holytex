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
     * Show the form for creating a new resource.
     */
    public function create() {
        //
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
     * Display the specified resource.
     */
    public function show(DyedFactory $dyedFactory) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DyedFactory $dyedFactory) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DyedFactory $dyedFactory) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DyedFactory $dyedFactory) {
        //
    }
}
