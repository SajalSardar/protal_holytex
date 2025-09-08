<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Style;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $orders = Order::orderBy('id', 'desc')->get();
        return view('order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $styles = Style::where('status', 'active')->get();
        return view('order.create', compact('styles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // return $request;

        $request->validate([
            'po_number' => 'required|unique:orders,po_number',
            'po_file'   => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('po_file')) {
            $path = $request->file('po_file')->store('orders', 'public');
        }

        $orderNumber = 'ORD-' . time() . rand(1000, 9999);
        $order       = Order::create([
            'order_number'              => $orderNumber,
            'po_number'                 => $request->po_number,
            'client_name'               => $request->client_name,
            'client_email'              => $request->client_email,
            'client_phone'              => $request->client_phone,
            'order_date'                => $request->order_date,
            'client_address'            => $request->client_address,
            'ship_address'              => $request->ship_address,
            'total_quantity'            => $request->total_quantity,
            'grand_total'               => $request->grand_total,
            'approximate_delivery_date' => $request->approximate_delivery_date,
            'remarks'                   => $request->remarks,
            'po_file'                   => $path,
            'created_by'                => Auth::id(),
        ]);
        if ($order) {
            foreach ($request->style as $key => $item) {
                OrderDetail::create([
                    'order_id'         => $order->id,
                    'order_number'     => $orderNumber,
                    'po_number'        => $request->po_number,
                    'style'            => $item,
                    'description'      => $request->description[$key],
                    'unit_quantity'    => $request->unit_quantity[$key],
                    'unit_price'       => $request->unit_price[$key],
                    'total_unit_price' => $request->total_unit_price[$key],
                    'created_by'       => Auth::id(),
                ]);
            }
        }
        toastr('Order Successfully Created!');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order) {

        $order = $order->load([
            'orderDetails',
            'approvedBy',
            'creator',
            'lastUpdateBy',
            'orderDetails.creator:id,name',
            'orderDetails.lastUpdateBy:id,name',
            'yarnQuotations'        => function ($q) {
                $q->withSum('yarnReceived', 'quantity')
                    ->withSum('yarnLoss', 'quantity')
                    ->withSum('storeStock', 'quantity');
            },
            'yarnQuotations.yarnFactory:id,name,address',
            'yarnQuotations.nettingFactory:id,name,address',
            'yarnQuotations.creator:id,name',
            'yarnQuotations.lastUpdateBy:id,name',
            'yarnQuotations.approvedBy:id,name',
            'nettingQuotations',
            'nettingQuotations.nettingQuotationItems',
            'nettingQuotations.creator:id,name',
            'nettingQuotations.lastUpdateBy:id,name',
            'nettingQuotations.approvedBy:id,name',
            'nettingQuotations.nettingFactory:id,name,address',
            'nettingQuotations.nettingQuotationItems.dyeingFactory:id,name,address',
            'nettingQuotations.nettingQuotationItems.garmentsFactory:id,name,address',
            'nettingQuotations'     => function ($q) {
                $q->withSum('nettingReceived', 'quantity')
                    ->withSum('nettingReceiveGarments', 'quantity')
                    ->withSum('nettingLoss', 'quantity')
                    ->withSum('storeStock', 'quantity');
            },
            'dyeingQuotations',
            'dyeingQuotations.creator:id,name',
            'dyeingQuotations.lastUpdateBy:id,name',
            'dyeingQuotations.approvedBy:id,name',
            'dyeingQuotations.garmentsFactory:id,name,address',
            'dyeingQuotations.dyeingFactory:id,name,address',
            'dyeingQuotations'      => function ($q) {
                $q->withSum('dyeingReceiveGarments', 'quantity')
                    ->withSum('dyeingStoreStock', 'quantity');
            },
            'accessoriesQuotations',
            'accessoriesQuotations.creator:id,name',
            'accessoriesQuotations.lastUpdateBy:id,name',
            'accessoriesQuotations.approvedBy:id,name',
            'accessoriesQuotations' => function ($q) {
                $q->withSum('accessoriesReceived', 'quantity')
                    ->withSum('accessoriesLoss', 'quantity')
                    ->withSum('accessoriesStoreStock', 'quantity');
            },
        ]);
        // return $order;
        return view('order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order) {
        $styles = Style::where('status', 'active')->get();
        $order->load('orderDetails');
        // return $order;
        return view('order.edit', compact('order', 'styles'));
    }

    public function updateStatus(Request $request) {
        // return $request;
        if (!$request->order_id) {
            toastr('Order Id not found!', 'error');
            return back();
        }
        Order::where('id', $request->order_id)->update([
            'status' => $request->status,
        ]);
        toastr('Order Status Updated!');
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order) {
        // return $request;
        if ($order->status === 'approved') {
            toastr('The order is approved but not yet updated in the system.', 'info');
            return back();
        }
        $request->validate([
            'po_number' => 'required|unique:orders,po_number,' . $order->id,
            'po_file'   => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('po_file')) {
            if ($order->po_file && Storage::disk('public')->exists($order->po_file)) {
                Storage::disk('public')->delete($order->po_file);
            }
            $path = $request->file('po_file')->store('orders', 'public');
        } else {
            $path = $order->po_file;
        }

        $order->update([
            'client_name'               => $request->client_name,
            'client_email'              => $request->client_email,
            'client_phone'              => $request->client_phone,
            'order_date'                => $request->order_date,
            'client_address'            => $request->client_address,
            'ship_address'              => $request->ship_address,
            'total_quantity'            => $request->total_quantity,
            'grand_total'               => $request->grand_total,
            'approximate_delivery_date' => $request->approximate_delivery_date,
            'remarks'                   => $request->remarks,
            'po_file'                   => $path,
            'updated_by'                => Auth::id(),
        ]);
        if ($order) {
            foreach ($request->style as $key => $item) {
                $detail = $order->orderDetails()
                    ->where('po_number', $request->po_number)
                    ->where('style', $item)
                    ->first();
                $detail->update([
                    'description'      => $request->description[$key],
                    'unit_quantity'    => $request->unit_quantity[$key],
                    'unit_price'       => $request->unit_price[$key],
                    'total_unit_price' => $request->total_unit_price[$key],
                    'updated_by'       => Auth::id(),
                ]);

            }
        }
        toastr('Order Successfully Updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order) {
        //
    }
}
