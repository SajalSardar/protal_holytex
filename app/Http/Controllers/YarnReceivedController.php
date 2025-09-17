<?php

namespace App\Http\Controllers;

use App\Models\DyedQuotation;
use App\Models\YarnLoss;
use App\Models\YarnQuotation;
use App\Models\YarnReceived;
use App\Models\YarnStoreStock;
use function Pest\Laravel\json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YarnReceivedController extends Controller {
    public function getYarnStyleByPo($po_number) {
        $yearns = YarnQuotation::with('yarnFactory', 'nettingFactory')
            ->withSum('yarnReceived', 'quantity')
            ->withSum('yarnLoss', 'quantity')
            ->withSum('storeStock', 'quantity')
            ->withSum('yarnReceivedFromStock', 'quantity')
            ->withSum('yarnReceivedOnlyQot', 'quantity')
            ->where('po_number', $po_number)
            ->where('receving_factory', 'knit')
            ->where('status', 'approved')
            ->get()
            ->groupBy('style');

        $dyedYearns = DyedQuotation::with('dyedFactory', 'nettingFactory')
            ->withSum('yarnReceived', 'quantity')
            ->withSum('yarnLoss', 'quantity')
            ->withSum('storeStock', 'quantity')
            ->withSum('yarnReceivedFromStock', 'quantity')
            ->withSum('yarnReceivedOnlyQot', 'quantity')
            ->where('po_number', $po_number)
            ->where('status', 'approved')
            ->get()
            ->groupBy('style');

        if ($yearns) {
            return json_encode([
                'yearns'     => $yearns,
                'dyedYearns' => $dyedYearns,
            ]);
        } else {
            return 'Yarn not found!';
        }
    }
    public function getReceviedTotalYarnByStyle(Request $request) {
        $po_number = $request->query('po_number');
        $style     = $request->query('style');
        $knit_fact = $request->query('knit_fact');

        $totalReceived = YarnReceived::where('po_number', $po_number)
            ->where('style', $style)
            ->where('netting_factory_id', $knit_fact)
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
        $yarnReceived = YarnReceived::get();
        return view('yarn_received.index', compact('yarnReceived'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) {

        $yearns = YarnQuotation::select('po_number')->where('status', 'approved')->groupby('po_number')->get();

        return view('yarn_received.create', compact('yearns'));
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
            $path = $request->file('challan_file')->store('yarn_received_challan', 'public');
        }
        $successMessageStatus = false;
        if ($request->yarn) {
            foreach ($request->yarn as $item) {

                if ($item['netting'] > 0 || $item['loss'] > 0 || $item['stock'] > 0) {
                    $yearnQut = YarnQuotation::withSum('yarnReceived', 'quantity')
                        ->withSum('yarnLoss', 'quantity')
                        ->withSum('storeStock', 'quantity')
                        ->where('id', $item['yarn_id'])
                        ->first();

                    $totalYearnQut = $yearnQut->quantity + $yearnQut->from_stock_quantity;

                    $yearnReceivedTotal = $yearnQut->yarn_received_sum_quantity + $yearnQut->yarn_loss_sum_quantity + $yearnQut->store_stock_sum_quantity;
                    $yarnRec            = array_key_exists('netting', $item) ? $item['netting'] : 0;
                    $yarnLossRec        = array_key_exists('loss', $item) ? $item['loss'] : 0;
                    $stockLossRec       = array_key_exists('stock', $item) ? $item['stock'] : 0;
                    $newReceived        = $yarnRec + $yarnLossRec + $stockLossRec;
                    $total              = $newReceived + $yearnReceivedTotal;
                }

                if (array_key_exists('netting', $item) && $item['netting'] > 0 && $totalYearnQut > $yearnReceivedTotal && $totalYearnQut >= $total) {
                    $successMessageStatus = true;
                    YarnReceived::create([
                        'yarn_quotation_id'    => $item['yarn_id'],
                        'po_number'            => $request->po_number,
                        'style'                => $item['style'],
                        'quantity'             => $item['netting'],
                        'lot_number'           => $item['loat_no'],
                        'bag_count'            => $item['bag_count'],
                        'challan_date'         => $request->challan_date,
                        'challan_number'       => $request->challan_number,
                        'vehicle_number'       => $request->vehicle_number,
                        'received_date'        => $request->received_date,
                        'received_by'          => Auth::id(),
                        'remarks'              => $item['remarks'],
                        'yarn_factory_id'      => $item['yarn_factory_id'],
                        'netting_factory_id'   => $item['netting_factory_id'],
                        'challan_file'         => $path,
                        'delived_factory_type' => $item['delived_factory_type'],
                    ]);
                }

                if (array_key_exists('loss', $item) && $item['loss'] > 0 && $totalYearnQut > $yearnReceivedTotal && $totalYearnQut >= $total) {
                    $successMessageStatus = true;
                    YarnLoss::create([
                        'yarn_quotation_id'    => $item['yarn_id'],
                        'quantity'             => $item['loss'],
                        'created_by'           => Auth::id(),
                        'delived_factory_type' => $item['delived_factory_type'],
                    ]);
                }

                if (array_key_exists('stock', $item) && $item['stock'] > 0 && $totalYearnQut > $yearnReceivedTotal && $totalYearnQut >= $total) {
                    $successMessageStatus = true;
                    YarnStoreStock::create([
                        'yarn_quotation_id'    => $item['yarn_id'],
                        'po_number'            => $request->po_number,
                        'style'                => $item['style'],
                        'quantity'             => $item['stock'],
                        'lot_number'           => $item['loat_no'],
                        'bag_count'            => $item['bag_count'],
                        'store_address'        => $item['store_address'],
                        'challan_date'         => $request->challan_date,
                        'challan_number'       => $request->challan_number,
                        'vehicle_number'       => $request->vehicle_number,
                        'received_date'        => $request->received_date,
                        'remarks'              => $item['remarks'],
                        'challan_file'         => $path,
                        'created_by'           => Auth::id(),
                        'delived_factory_type' => $item['delived_factory_type'],
                    ]);
                }

                if ($item['netting'] > 0 || $item['loss'] > 0 || $item['stock'] > 0) {
                    if ((float) $totalYearnQut === (float) $total) {
                        $yearnQut->update([
                            'status'        => 'received',
                            'delivery_date' => $request->received_date,
                            'updated_by'    => Auth::id(),
                        ]);
                    }
                }

            }
        }
        if ($request->dyed) {
            foreach ($request->dyed as $item) {
                if ($item['netting'] > 0 || $item['loss'] > 0 || $item['stock'] > 0) {
                    $yearnQut = DyedQuotation::withSum('yarnReceived', 'quantity')
                        ->withSum('yarnLoss', 'quantity')
                        ->withSum('storeStock', 'quantity')
                        ->where('id', $item['dyed_id'])
                        ->first();

                    $totalYearnQut = $yearnQut->quantity + $yearnQut->from_stock_quantity;

                    $yearnReceivedTotal = $yearnQut->yarn_received_sum_quantity + $yearnQut->yarn_loss_sum_quantity + $yearnQut->store_stock_sum_quantity;
                    $yarnRec            = array_key_exists('netting', $item) ? $item['netting'] : 0;
                    $yarnLossRec        = array_key_exists('loss', $item) ? $item['loss'] : 0;
                    $stockLossRec       = array_key_exists('stock', $item) ? $item['stock'] : 0;
                    $newReceived        = $yarnRec + $yarnLossRec + $stockLossRec;
                    $total              = $newReceived + $yearnReceivedTotal;
                }

                if (array_key_exists('netting', $item) && $item['netting'] > 0 && $totalYearnQut > $yearnReceivedTotal && $totalYearnQut >= $total) {
                    $successMessageStatus = true;
                    YarnReceived::create([
                        'dyed_quotation_id'    => $item['dyed_id'],
                        'po_number'            => $request->po_number,
                        'style'                => $item['style'],
                        'quantity'             => $item['netting'],
                        'lot_number'           => $item['loat_no'],
                        'bag_count'            => $item['bag_count'],
                        'challan_date'         => $request->challan_date,
                        'challan_number'       => $request->challan_number,
                        'vehicle_number'       => $request->vehicle_number,
                        'received_date'        => $request->received_date,
                        'received_by'          => Auth::id(),
                        'remarks'              => $item['remarks'],
                        'dyed_factory_id'      => $item['dyed_factory_id'],
                        'netting_factory_id'   => $item['netting_factory_id'],
                        'challan_file'         => $path,
                        'delived_factory_type' => $item['delived_factory_type'],
                    ]);
                }

                if (array_key_exists('loss', $item) && $item['loss'] > 0 && $totalYearnQut > $yearnReceivedTotal && $totalYearnQut >= $total) {
                    $successMessageStatus = true;
                    YarnLoss::create([
                        'dyed_quotation_id'    => $item['dyed_id'],
                        'quantity'             => $item['loss'],
                        'created_by'           => Auth::id(),
                        'delived_factory_type' => $item['delived_factory_type'],
                    ]);
                }

                if (array_key_exists('stock', $item) && $item['stock'] > 0 && $totalYearnQut > $yearnReceivedTotal && $totalYearnQut >= $total) {
                    $successMessageStatus = true;
                    YarnStoreStock::create([
                        'dyed_quotation_id'    => $item['dyed_id'],
                        'po_number'            => $request->po_number,
                        'style'                => $item['style'],
                        'quantity'             => $item['stock'],
                        'lot_number'           => $item['loat_no'],
                        'bag_count'            => $item['bag_count'],
                        'store_address'        => $item['store_address'],
                        'challan_date'         => $request->challan_date,
                        'challan_number'       => $request->challan_number,
                        'vehicle_number'       => $request->vehicle_number,
                        'received_date'        => $request->received_date,
                        'remarks'              => $item['remarks'],
                        'challan_file'         => $path,
                        'created_by'           => Auth::id(),
                        'delived_factory_type' => $item['delived_factory_type'],
                    ]);
                }

                if ($item['netting'] > 0 || $item['loss'] > 0 || $item['stock'] > 0) {
                    if ((float) $totalYearnQut === (float) $total) {
                        $yearnQut->update([
                            'status'        => 'received',
                            'delivery_date' => $request->received_date,
                            'updated_by'    => Auth::id(),
                        ]);
                    }
                }

            }
        }

        if ($successMessageStatus) {
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
