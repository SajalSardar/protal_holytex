<?php

namespace App\Http\Controllers;

use App\Models\NettingLoss;
use App\Models\NettingQuotation;
use App\Models\NettingReceived;
use App\Models\NettingReceivedGarments;
use App\Models\YarnQuotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NettingReceivedController extends Controller {
    public function getNettingStyleByPo($po_number) {
        $receivedStyles = YarnQuotation::where('po_number', $po_number)
            ->get()
            ->groupBy('style')
            ->map(fn($items) => $items->every(fn($i) => $i->status === 'recevied'))
            ->filter()
            ->keys();

        $yearns = NettingQuotation::with('dyeingFactory', 'nettingFactory', 'garmentsFactory')
            ->withSum('nettingReceived', 'quantity')
            ->withSum('nettingLoss', 'quantity')
            ->withSum('storeStock', 'quantity')
            ->withSum('nettingReceiveGarments', 'quantity')
            ->where('po_number', $po_number)
            ->whereIn('style', $receivedStyles->toArray())
            ->where('status', 'approved')
            ->get()
            ->groupBy('style');

        if ($yearns) {
            return $yearns;
        } else {
            return 'Yarn not found!';
        }
    }

    public function getReceviedTotalNettingByStyle(Request $request) {
        $po_number = $request->query('po_number');
        $style     = $request->query('style');

        $totalReceived = NettingReceived::where('po_number', $po_number)
            ->where('style', $style)
            ->sum('quantity');

        // Always return JSON
        return response()->json([
            'po_number'      => $po_number,
            'style'          => $style,
            'total_received' => $totalReceived,
        ]);
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

        $yarnreceived = YarnQuotation::where('status', 'recevied')->groupby('po_number')
            ->pluck('po_number');

        $requestPo = request()->po_number;

        if ($requestPo) {
            if (!in_array($requestPo, $yarnreceived->toArray())) {
                return redirect()->route('nettingreceived.index');
            }
        }

        // $yearns = NettingQuotation::where('po_number', $requestPo)
        //     ->whereIn('style', $receivedStyles->toArray())
        //     ->where('status', 'approved')
        //     ->pluck('po_number');

        // return $yearns;

        // $receviedYarn = YarnQuotation::get()
        //     ->groupBy(['po_number', 'style'])
        //     ->map(fn($styles) => $styles->map(fn($items) => $items->every(fn($i) => $i->status === 'recevied')))
        //     ->filter(fn($styles) => $styles->contains(true))
        //     ->keys();

        $receivedStyles = YarnQuotation::get()
            ->groupBy(['po_number', 'style'])
            ->map(fn($styles) =>
                $styles->map(fn($items) =>
                    $items->every(fn($i) => $i->status === 'recevied')
                )->filter() // remove false values
            )
            ->filter(fn($styles) => $styles->isNotEmpty())
            ->map(fn($styles) => $styles->keys());

        $receviedYarn = NettingQuotation::whereIn('po_number', $receivedStyles->keys()->toArray())
            ->where('status', 'approved')
            ->groupBy('po_number')
            ->pluck('po_number');

        // return $receviedYarn;

        return view('netting_received.create', compact('receviedYarn'));
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

            $nettingReceivedTotal = $nettingQut->netting_received_sum_quantity + $nettingQut->netting_loss_sum_quantity + $nettingQut->store_stock_sum_quantity + $nettingQut->netting_receive_garments_sum_quantity;
            $nettingReceived      = array_key_exists('netting', $item) ? $item['netting'] : 0;
            $lossReceived         = array_key_exists('loss', $item) ? $item['loss'] : 0;
            $newReceived          = $nettingReceived + $lossReceived;
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

            if ((float) $item['yarn_recevied'] === (float) $total) {
                $nettingQut->update([
                    'status'        => 'recevied',
                    'delivery_date' => $request->received_date,
                    'updated_by'    => Auth::id(),
                ]);
            }
            if (!$nettingQut->yarn_recevied) {
                $nettingQut->update([
                    'yarn_recevied' => $item['yarn_recevied'],
                    'updated_by'    => Auth::id(),
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
