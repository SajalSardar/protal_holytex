<?php

namespace App\Http\Controllers;

use App\Models\NettingLoss;
use App\Models\NettingQuotation;
use App\Models\NettingReceived;
use App\Models\NettingReceivedGarments;
use App\Models\YarnReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NettingReceivedController extends Controller {
    public function getNettingStyleByPo($po_number) {
        $yearns = NettingQuotation::with('dyeingFactory', 'nettingFactory', 'garmentsFactory')
            ->withSum('nettingReceived', 'quantity')
            ->withSum('nettingLoss', 'quantity')
            ->withSum('storeStock', 'quantity')
            ->withSum('nettingReceiveGarments', 'quantity')
        // ->where('delivery_factory_type', 'dyeing')
            ->where('po_number', $po_number)
            ->get()
            ->groupBy('style');
        if ($yearns) {
            return $yearns;
        } else {
            return 'Yarn not found!';
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $nettingReceived = NettingReceived::get();
        return view('netting_received.index', compact('nettingReceived'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $yarnreceived = YarnReceived::groupby('po_number')
            ->pluck('po_number');

        $requestPo = request()->po_number;

        if ($requestPo) {
            if (!in_array($requestPo, $yarnreceived->toArray())) {
                return redirect()->route('nettingreceived.index');
            }
        }

        $nettingQut = NettingQuotation::where('delivery_factory_type', 'dyeing')
            ->select('po_number')
            ->groupby('po_number')
            ->whereIn('po_number', $yarnreceived)
            ->get();
        return view('netting_received.create', compact('nettingQut'));
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
            $path = $request->file('challan_file')->store('netting_received_challan', 'public');
        }
        $successMessageStatus = 0;
        foreach ($request->items as $item) {
            $nettingQut = NettingQuotation::withSum('nettingReceived', 'quantity')
                ->withSum('nettingLoss', 'quantity')
                ->withSum('storeStock', 'quantity')
                ->withSum('nettingReceiveGarments', 'quantity')
                ->where('id', $item['netting_id'])
                ->first();

            $nettingReceivedTotal = $nettingQut->yarn_received_sum_quantity + $nettingQut->yarn_loss_sum_quantity + $nettingQut->store_stock_sum_quantity + $nettingQut->netting_receive_garments_sum_quantity;
            $newReceived          = (array_key_exists('netting', $item) ? $item['netting'] : 0) + array_key_exists('loss', $item) ? $item['loss'] : 0;
            $total                = $newReceived + $nettingReceivedTotal;

            if (array_key_exists('netting', $item) && $item['netting'] > 0 && $nettingQut->quantity > $nettingReceivedTotal && $nettingQut->quantity >= $total) {
                $successMessageStatus = 1;
                if ($item['receving_factory_type'] === "dyeing") {
                    NettingReceived::create([
                        'netting_quotation_id'  => $item['netting_id'],
                        'po_number'             => $request->po_number,
                        'style'                 => $item['style'],
                        'quantity'              => $item['netting'],
                        'lot_number'            => $item['loat_no'],
                        'bag_count'             => $item['bag_count'],
                        'challan_date'          => $request->challan_date,
                        'challan_number'        => $request->challan_number,
                        'vehicle_number'        => $request->vehicle_number,
                        'received_date'         => $request->received_date,
                        'received_by'           => Auth::id(),
                        'remarks'               => $item['remarks'],
                        'netting_factory_id'    => $item['netting_factory_id'],
                        'receving_factory_type' => 'dyeing',
                        'receving_point_id'     => $item['dyeing_factory_id'],
                        'challan_file'          => $path,
                    ]);
                }
                if ($item['receving_factory_type'] === "garments") {
                    NettingReceivedGarments::create([
                        'netting_quotation_id' => $item['netting_id'],
                        'delived_factory_type' => 'netting',
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
            }

            if (array_key_exists('loss', $item) && $item['loss'] > 0 && $nettingQut->quantity > $nettingReceivedTotal && $nettingQut->quantity >= $total) {
                $successMessageStatus = 1;
                NettingLoss::create([
                    'netting_quotation_id' => $item['netting_id'],
                    'quantity'             => $item['loss'],
                    'created_by'           => Auth::id(),
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
