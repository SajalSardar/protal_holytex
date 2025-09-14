<?php

use App\Models\NettingReceivedGarments;

if (!function_exists('getKintByGermentsReceived')) {
    function getKintByGermentsReceived($po_number, $style) {
        $datas = NettingReceivedGarments::selectRaw('garments_factory_id, SUM(quantity) as total_quantity')
            ->where('po_number', $po_number)
            ->where('style', $style)
            ->groupBy('garments_factory_id')
            ->with('garmentsFactory')
            ->get();

        return $datas;
    }
}