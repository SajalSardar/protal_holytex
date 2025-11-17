<?php

namespace App\Http\Controllers;

use App\Models\DyedFactory;
use App\Models\DyedQuotation;
use App\Models\NettingFactroy;
use App\Models\NettingQuotation;
use App\Models\OrderDetail;
use App\Models\Store;
use App\Models\YarnLoss;
use App\Models\YarnQuotation;
use App\Models\YarnReceived;
use function Pest\Laravel\json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class YarnReceivedController extends Controller {
    public function getYarnStyleByPo($po_number) {
        $yearns = YarnQuotation::with('yarnStore', 'yarnFactory')
            ->withSum('yarnReceived', 'quantity')
            ->withSum('yarnLoss', 'quantity')
        // ->withSum('storeStock', 'quantity')
        // ->withSum('yarnReceivedFromStock', 'quantity')
        // ->withSum('yarnReceivedOnlyQot', 'quantity')
            ->where('po_number', $po_number)
            ->where('status', 'approved')
            ->get()
            ->groupBy('style');

        if (isset($yearns)) {
            return json_encode(isset($yearns) ? $yearns : null);
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
        $yarnReceived = YarnReceived::select(
            DB::raw('MAX(po_number) as po_number'),
            DB::raw('MAX(style) as style'),
            DB::raw('MAX(description) as description'),
            DB::raw('MAX(unit) as unit'),
            DB::raw('SUM(quantity) as total_quantity')
        )
            ->groupBy('po_number', 'style', 'description')
            ->get();

        foreach ($yarnReceived as $item) {
            $item->dyed_total = DyedQuotation::where('po_number', $item->po_number)
                ->where('style', $item->style)
                ->where('description', $item->description)->sum('quantity');
            $item->knit_total = NettingQuotation::where('po_number', $item->po_number)
                ->where('style', $item->style)
                ->where('description', $item->description)->sum('quantity');
        }

        // return $yarnReceived;
        return view('yarn_received.index', compact('yarnReceived'));
    }

    public function detailsView(Request $request) {
        $yarnReceived = YarnReceived::with('yarnStore')
            ->where('po_number', $request->po_number)
            ->where('style', $request->style)
            ->where('description', $request->description)
            ->get();

        // return $yarnReceived;
        return view('yarn_received.details', compact('yarnReceived'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) {

        $yearns       = YarnQuotation::select('po_number')->where('status', 'approved')->groupby('po_number')->get();
        $storeAddress = Store::where('status', 'active')->get();

        $yarnQuotation = YarnQuotation::with('yarnStore', 'yarnFactory')
            ->withSum('yarnReceived', 'quantity')
            ->withSum('yarnLoss', 'quantity')
            ->where('id', $request->yarn_quotation)
            // ->where('status', 'approved')
            ->first();

        // return $request->yarn_quotation;

        return view('yarn_received.create', compact('yearns', 'storeAddress', 'yarnQuotation'));
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

        $yearnQut = YarnQuotation::withSum('yarnReceived', 'quantity')
            ->withSum('yarnLoss', 'quantity')
            ->where('id', $request->yarn_id)
            ->first();

        $totalYearnQut = $yearnQut->quantity;

        $yearnReceivedTotal = $yearnQut->yarn_received_sum_quantity + $yearnQut->yarn_loss_sum_quantity;
        $yarnRec            = $request->yarn ?? 0;
        $yarnLossRec        = $request->loss ?? 0;
        $newReceived        = $yarnRec + $yarnLossRec;
        $total              = $newReceived + $yearnReceivedTotal;

        if ($request->yarn > 0 && $totalYearnQut > $yearnReceivedTotal && $totalYearnQut >= $total) {
            $successMessageStatus = true;
            YarnReceived::create([
                'yarn_quotation_id' => $request->yarn_id,
                'po_number'         => $request->po_number,
                'style'             => $request->style,
                'description'       => $yearnQut->description,
                'quantity'          => $request->yarn,
                'lot_number'        => $request->loat_no,
                'bag_count'         => $request->bag_count,
                'challan_date'      => $request->challan_date,
                'challan_number'    => $request->challan_number,
                'vehicle_number'    => $request->vehicle_number,
                'received_date'     => $request->received_date,
                'received_by'       => Auth::id(),
                'remarks'           => $request->remarks,
                'yarn_factory_id'   => $request->yarn_factory_id,
                'store_id'          => $request->store_id ?? $yearnQut->store_id,
                'challan_file'      => $path,
            ]);
        }

        if ($request->loss > 0 && $totalYearnQut > $yearnReceivedTotal && $totalYearnQut >= $total) {
            $successMessageStatus = true;
            YarnLoss::create([
                'yarn_quotation_id'    => $request->yarn_id,
                'quantity'             => $request->loss,
                'created_by'           => Auth::id(),
                'delived_factory_type' => 'yarn',
            ]);
        }

        if ($request->yarn > 0 || $request->loss > 0) {
            if ((float) $totalYearnQut === (float) $total) {
                $yearnQut->update([
                    'status'        => 'received',
                    'delivery_date' => $request->received_date,
                    'updated_by'    => Auth::id(),
                ]);
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
    public function show(YarnReceived $yarnreceived) {
        return view('yarn_received.show', compact('yarnreceived'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(YarnReceived $yarnreceived) {
        $storeAddress  = Store::where('status', 'active')->get();
        $yarnQuotation = YarnQuotation::with('yarnStore', 'yarnFactory')
            ->withSum('yarnReceived', 'quantity')
            ->withSum('yarnLoss', 'quantity')
            ->where('po_number', $yarnreceived->po_number)
            ->first();
        return view('yarn_received.edit', compact('yarnreceived', 'storeAddress', 'yarnQuotation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, YarnReceived $yarnreceived) {
        // return $request;

        $request->validate([
            'challan_file' => "nullable|max:512|image",
        ]);

        if ($request->hasFile('challan_file')) {
            if (!empty($yarnreceived->challan_file) && Storage::disk('public')->exists($yarnreceived->challan_file)) {
                Storage::disk('public')->delete($yarnreceived->challan_file);
            }
            $path = $request->file('challan_file')->store('yarn_received_challan', 'public');
        } else {
            $path = $yarnreceived->challan_file;
        }

        $yearnQut = YarnQuotation::withSum('yarnReceived', 'quantity')
            ->withSum('yarnLoss', 'quantity')
            ->where('id', $yarnreceived->yarn_quotation_id)
            ->first();

        // $totalYearnQut = $yearnQut->quantity;

        // $yearnReceivedTotal = $yearnQut->yarn_received_sum_quantity + $yearnQut->yarn_loss_sum_quantity;
        // $yarnRec            = array_key_exists('yarn', $item) ? $item['yarn'] : 0;
        // $yarnLossRec        = array_key_exists('loss', $item) ? $item['loss'] : 0;
        // $newReceived        = $yarnRec + $yarnLossRec;
        // $total              = $newReceived + $yearnReceivedTotal;

        $yarnreceived->update([
            'quantity'       => $request->quantity,
            'lot_number'     => $request->loat_no,
            'bag_count'      => $request->bag_count,
            'challan_date'   => $request->challan_date,
            'challan_number' => $request->challan_number,
            'vehicle_number' => $request->vehicle_number,
            'received_date'  => $request->received_date,
            'updated_by'     => Auth::id(),
            'remarks'        => $request->remarks,
            'store_id'       => $request->store_id ?? $yearnQut->store_id,
            'challan_file'   => $path,
        ]);

        // if ($item['yarn'] > 0 || $item['loss'] > 0) {
        //     if ((float) $totalYearnQut === (float) $total) {
        //         $yearnQut->update([
        //             'status'        => 'received',
        //             'delivery_date' => $request->received_date,
        //             'updated_by'    => Auth::id(),
        //         ]);
        //     }
        // }

        toastr('Data Successfully Updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YarnReceived $yarnreceived) {
        $yarnreceived->delete();
        toastr('Yarn Quotation Successfully Deleted!');
        return back();
    }

    public function yarnDistribute(Request $request) {

        $knitFactory = NettingFactroy::where('status', 'active')->get();
        $dyedFactory = DyedFactory::where('status', 'active')->get();

        $orderDetail = OrderDetail::where('po_number', $request->po_number)->where('style', $request->style)->first();

        $yarnreceived = YarnReceived::with('yarnStore')
            ->where('po_number', $request->po_number)
            ->where('style', $request->style)
            ->where('description', $request->description)
            ->get();

        $dyedQuotations = DyedQuotation::where('po_number', $request->po_number)
            ->where('style', $request->style)
            ->where('description', $request->description)->sum('quantity');
        $knitQuotations = NettingQuotation::where('po_number', $request->po_number)
            ->where('style', $request->style)
            ->where('description', $request->description)->sum('quantity');

        return view('yarn_received.distribute', compact('yarnreceived', 'knitFactory', 'dyedFactory', 'orderDetail', 'dyedQuotations', 'knitQuotations'));
    }

    public function yarnDistributeStore(Request $request) {

        // return $request;
        foreach ($request->items as $item) {
            if ($item['delivery_poin_check'] === 'yarn_dyed') {
                DyedQuotation::create([
                    'description'               => $request->description,
                    'order_id'                  => $request->order_id,
                    'style'                     => $request->style,
                    'po_number'                 => $request->po_number,
                    'purchase_date'             => $request->order_date,
                    'approximate_delivery_date' => $request->approximate_delivery_date,
                    'remarks'                   => $request->remarks,
                    'dyed_factory_id'           => $item['dyed_factory_id'],
                    'quantity'                  => $item['quantity'] ?? null,
                    'price'                     => $item['price'] ?? null,
                    'total_price'               => $item['total_amount'] ?? null,
                    'created_by'                => Auth::id(),
                ]);
            }
            if ($item['delivery_poin_check'] === 'knit') {
                NettingQuotation::create([
                    'order_id'                  => $request->order_id,
                    'description'               => $request->description,
                    'style'                     => $request->style,
                    'po_number'                 => $request->po_number,
                    'order_date'                => $request->order_date,
                    'approximate_delivery_date' => $request->approximate_delivery_date,
                    'remarks'                   => $request->remarks,
                    'netting_factory_id'        => $item['knit_factory_id'] ?? null,
                    'quantity'                  => $item['quantity'],
                    'price'                     => $item['price'] ?? null,
                    'total_price'               => $item['total_amount'],
                    'created_by'                => Auth::id(),
                ]);
            }
        }

        toastr('Data Successfully Created!');
        return back();

    }
}
