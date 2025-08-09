<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;

class OrderDetailController extends Controller {
    public function getStyleByPo($po_number) {
        $orderDetails = OrderDetail::where('po_number', $po_number)->select('order_id', 'style', 'order_number')->get();

        if ($orderDetails) {

            return $orderDetails;
        } else {
            return "No style found";
        }
    }
}
