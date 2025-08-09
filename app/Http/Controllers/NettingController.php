<?php

namespace App\Http\Controllers;

use App\Models\BuyYarn;
use App\Models\Netting;
use Illuminate\Http\Request;

class NettingController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $yearns = BuyYarn::select('po_number')->groupby('po_number')->get();
        return view('netting.create', compact('yearns'));
    }

    public function getYarnbyPo($po_number) {
        $yearns = BuyYarn::with('yarnFactory', 'nettingFactory')->where('po_number', $po_number)->get();
        if ($yearns) {
            return $yearns;
        } else {
            return 'Yarn not found!';
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //

        return $request;
    }

    /**
     * Display the specified resource.
     */
    public function show(Netting $netting) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Netting $netting) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Netting $netting) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Netting $netting) {
        //
    }
}
