<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\YarnQuotation;

class OrderDetailController extends Controller {

    public function getStyleByPo($po_number) {
        $quotations = YarnQuotation::where('po_number', $po_number)
            ->pluck('style')
            ->unique()
            ->values()
            ->toArray();
        $orderDetails = OrderDetail::where('po_number', $po_number)
            ->whereNotIn('style', $quotations)
            ->select('order_id', 'style', 'order_number')
            ->get();

        if ($orderDetails) {

            return $orderDetails;
        } else {
            return "No style found";
        }
    }
}
