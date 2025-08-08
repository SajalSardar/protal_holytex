<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;

class orderDetailController extends Controller {
    public function getStyleByPo($po_number) {
        $getPo = OrderDetail::where('po_number', $po_number)->pluck('style');

        if ($getPo) {

            return $getPo;
        } else {
            return "No style found";
        }
    }
}
