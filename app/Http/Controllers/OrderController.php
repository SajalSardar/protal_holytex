<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Style;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $orders = Order::all();
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
            'order_number'   => $orderNumber,
            'po_number'      => $request->po_number,
            'client_name'    => $request->client_name,
            'client_email'   => $request->client_email,
            'client_phone'   => $request->client_phone,
            'order_date'     => $request->order_date,
            'terms'          => $request->terms,
            'client_address' => $request->client_address,
            'ship_address'   => $request->ship_address,
            'total_quantity' => $request->total_quantity,
            'grand_total'    => $request->grand_total,
            'po_file'        => $path,
            'created_by'     => Auth::id(),
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order) {
        //
    }
}
