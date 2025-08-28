<?php

namespace App\Http\Controllers;

use App\Models\DyeingQuotation;
use App\Models\NettingReceived;
use App\Models\NettingReceivedGarments;
use App\Models\NettingStoreStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DyeingReceivedController extends Controller {
    public function getDyeingStyleByPo($po_number) {
        $dyeing = DyeingQuotation::with('dyeingFactory', 'garmentsFactory')
            ->withSum('dyeingReceiveGarments', 'quantity')
            ->withSum('dyeingStoreStock', 'quantity')
            ->where('po_number', $po_number)
            ->get()
            ->groupBy('style');
        if ($dyeing) {
            return $dyeing;
        } else {
            return 'Yarn not found!';
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $dyeingReceived = NettingReceivedGarments::where('delived_factory_type', 'dyeing')->get();
        return view('dyeing_received.index', compact('dyeingReceived'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $nettingReceived = NettingReceived::where('receving_factory_type', 'dyeing')->groupby('po_number')
            ->pluck('po_number');

        $requestPo = request()->po_number;

        if ($requestPo) {
            if (!in_array($requestPo, $nettingReceived->toArray())) {
                return redirect()->route('dyeingreceived.index');
            }
        }

        $nettingQut = DyeingQuotation::select('po_number')
            ->groupby('po_number')
            ->whereIn('po_number', $nettingReceived)
            ->get();
        return view('dyeing_received.create', compact('nettingQut'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        // return $request;
        $request->validate([
            'challan_file' => "nullable|max:512|image",
        ]);

        $path = null;
        if ($request->hasFile('challan_file')) {
            $path = $request->file('challan_file')->store('dyeing_received_challan', 'public');
        }
        $successMessageStatus = 0;
        foreach ($request->items as $item) {
            $nettingQut = DyeingQuotation::withSum('dyeingReceiveGarments', 'quantity')
                ->withSum('dyeingStoreStock', 'quantity')
                ->where('id', $item['dyeing_qty_id'])
                ->first();

            $nettingReceivedTotal = $nettingQut->dyeing_receive_garments_sum_quantity + $nettingQut->dyeing_store_stock_sum_quantity;
            $newReceived          = (array_key_exists('netting', $item) ? $item['netting'] : 0);
            $total                = $newReceived + $nettingReceivedTotal;

            if (array_key_exists('netting', $item) && $item['netting'] > 0 && $nettingQut->quantity > $nettingReceivedTotal && $nettingQut->quantity >= $total) {
                $successMessageStatus = 1;

                NettingReceivedGarments::create([
                    'dyeing_quotation_id'  => $item['dyeing_qty_id'],
                    'delived_factory_type' => 'dyeing',
                    'po_number'            => $request->po_number,
                    'style'                => $item['style'],
                    'quantity'             => $item['netting'],
                    'lot_number'           => $item['loat_no'],
                    'bag_count'            => $item['bag_count'],
                    'challan_date'         => $request->challan_date,
                    'challan_number'       => $request->challan_number,
                    'vehicle_number'       => $request->vehicle_number,
                    'received_date'        => $request->received_date,
                    'created_by'           => Auth::id(),
                    'remarks'              => $item['remarks'],
                    'challan_file'         => $path,
                ]);

            }
            if (array_key_exists('store_stock', $item) && $item['store_stock'] > 0 && $nettingQut->quantity > $nettingReceivedTotal && $nettingQut->quantity >= $total) {
                $successMessageStatus = 1;
                NettingStoreStock::create([
                    'dyeing_quotation_id'  => $item['dyeing_qty_id'],
                    'delived_factory_type' => 'dyeing',
                    'po_number'            => $request->po_number,
                    'style'                => $item['style'],
                    'quantity'             => $item['store_stock'],
                    'lot_number'           => $item['loat_no'],
                    'bag_count'            => $item['bag_count'],
                    'store_address'        => $item['store_address'],
                    'challan_date'         => $request->challan_date,
                    'challan_number'       => $request->challan_number,
                    'vehicle_number'       => $request->vehicle_number,
                    'received_date'        => $request->received_date,
                    'created_by'           => Auth::id(),
                    'remarks'              => $item['remarks'],
                    'challan_file'         => $path,
                ]);
            }

        }
        if ($successMessageStatus === 1) {
            toastr('Data Successfully Created!');
            return back();
        } else {
            toastr('No Input data found!', 'error');
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(NettingReceived $nettingReceived) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NettingReceived $nettingReceived) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NettingReceived $nettingReceived) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NettingReceived $nettingReceived) {
        //
    }
}
