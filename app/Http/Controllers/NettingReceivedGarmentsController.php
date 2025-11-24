<?php

namespace App\Http\Controllers;

use App\Models\GarmentsFactroy;
use App\Models\NettingReceivedGarments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NettingReceivedGarmentsController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $nettings = NettingReceivedGarments::with('garmentsFactory')->orderBy('id', 'desc')->get();
        // return $nettings;
        return view('garments_quotation.index', compact('nettings'));

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(NettingReceivedGarments $nettingreceivedgarments) {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {
        $nettingreceivedgarments = NettingReceivedGarments::find($id);
        $garmentsFactroy         = GarmentsFactroy::where('status', 'active')->get();
        return view('garments_quotation.edit', compact('nettingreceivedgarments', 'garmentsFactroy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request) {
        $nettingreceivedgarments = NettingReceivedGarments::find($request->id);
        // return $nettingreceivedgarments;
        $request->validate([
            'po_number' => 'required',
        ]);

        if ($request->status === "recevied") {
            if (!$request->has('challan')) {
                toastr()->error('Challan info not found!');
                return back();
            }

            $challan = $request->input('challan');

            $requiredFields = ['challan_number', 'vehicle_number', 'challan_date', 'received_date'];

            foreach ($requiredFields as $field) {
                if (empty($challan[$field])) {
                    toastr()->error('Please fill in all challan information!');
                    return back();
                }
            }
        }

        $nettingreceivedgarments->update([
            'order_date'                => $request->order_date,
            'approximate_delivery_date' => $request->approximate_delivery_date,
            'quantity'                  => $request->quantity,
            // 'price'                     => $request->price,
            // 'total_price'               => $request->total_unit_price,
            'garments_factory_id'       => $request->garments_factory_id,
            'remarks'                   => $request->remarks,
            'status'                    => $request->status,
            'updated_by'                => Auth::id(),
        ]);
        if ($request->status === "approved") {
            $nettingreceivedgarments->approved_by = Auth::id();
            $nettingreceivedgarments->save();
        }

        if ($request->status === "received") {
            if ($request->hasFile('challan.challan_file')) {
                $path = $request->file('challan.challan_file')->store('garments_received_challan', 'public');
            } else {
                $path = $nettingreceivedgarments->challan_file ?? null;
            }

            // 'lot_number'     => $item['loat_no'],
            // 'bag_count'      => $item['bag_count'],
            $nettingreceivedgarments->challan_date   = $request->challan_date;
            $nettingreceivedgarments->challan_number = $request->challan_number;
            $nettingreceivedgarments->vehicle_number = $request->vehicle_number;
            $nettingreceivedgarments->received_date  = $request->received_date;
            $nettingreceivedgarments->received_by    = Auth::id();
            $nettingreceivedgarments->challan_file   = $path;
            $nettingreceivedgarments->save();

        }

        toastr('Dyeing Successfully Updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NettingReceivedGarments $nettingReceivedGarments) {
        //
    }
}
