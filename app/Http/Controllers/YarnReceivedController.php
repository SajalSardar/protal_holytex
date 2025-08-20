<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\YarnLoss;
use App\Models\YarnQuotation;
use App\Models\YarnReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YarnReceivedController extends Controller {
    public function getYarnStyleByPo($po_number) {
        $yearns = YarnQuotation::with('yarnFactory', 'nettingFactory')
            ->withSum('yarnReceived', 'quantity')
            ->withSum('yarnLoss', 'quantity')
            ->withSum('storeStock', 'quantity')
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
        return view('yarn_received.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) {
        $order = Order::with([
            'orderDetails',
            'approvedBy',
            'creator',
            'lastUpdateBy',
            'orderDetails.creator:id,name',
            'orderDetails.lastUpdateBy:id,name',
            'yarnQuotations',
            'yarnQuotations.yarnFactory:id,name,address',
            'yarnQuotations.nettingFactory:id,name,address',
            'yarnQuotations.creator:id,name',
            'yarnQuotations.lastUpdateBy:id,name',
            'yarnQuotations.approvedBy:id,name',

        ])->where('id', $request->order_id)->first();

        $yearns = YarnQuotation::select('po_number')->groupby('po_number')->get();

        return view('yarn_received.create', compact('yearns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        $path = null;
        if ($request->hasFile('challan_file')) {
            $path = $request->file('challan_file')->store('yarn_received_challan', 'public');
        }
        $successMessageStatus = 0;
        foreach ($request->items as $item) {
            $yearnQut = YarnQuotation::withSum('yarnReceived', 'quantity')
                ->withSum('yarnLoss', 'quantity')
                ->withSum('storeStock', 'quantity')
                ->where('id', $item['yarn_id'])
                ->first();

            $yearnReceivedTotal = $yearnQut->yarn_received_sum_quantity + $yearnQut->yarn_loss_sum_quantity + $yearnQut->store_stock_sum_quantity;
            $newReceived        = (array_key_exists('netting', $item) ? $item['netting'] : 0) + array_key_exists('loss', $item) ? $item['loss'] : 0;
            $total              = $newReceived + $yearnReceivedTotal;

            if (array_key_exists('netting', $item) && $item['netting'] > 0 && $yearnQut->quantity > $yearnReceivedTotal && $yearnQut->quantity >= $total) {
                $successMessageStatus = 1;
                YarnReceived::create([
                    'yarn_quotation_id'  => $item['yarn_id'],
                    'po_number'          => $request->po_number,
                    'style'              => $item['style'],
                    'quantity'           => $item['netting'],
                    'lot_number'         => $item['loat_no'],
                    'bag_count'          => $item['bag_count'],
                    'challan_date'       => $request->challan_date,
                    'challan_number'     => $request->challan_number,
                    'vehicle_number'     => $request->vehicle_number,
                    'received_date'      => $request->received_date,
                    'received_by'        => Auth::id(),
                    'remarks'            => $item['remarks'],
                    'yarn_factory_id'    => $item['yarn_factory_id'],
                    'netting_factory_id' => $item['netting_factory_id'],
                    'challan_file'       => $path,
                ]);
            }

            if (array_key_exists('loss', $item) && $item['loss'] > 0 && $yearnQut->quantity > $yearnReceivedTotal && $yearnQut->quantity >= $total) {
                $successMessageStatus = 1;
                YarnLoss::create([
                    'yarn_quotation_id' => $item['yarn_id'],
                    'quantity'          => $item['loss'],
                    'created_by'        => Auth::id(),
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
    public function show(YarnReceived $yarnReceived) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(YarnReceived $yarnReceived) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, YarnReceived $yarnReceived) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YarnReceived $yarnReceived) {
        //
    }
}
