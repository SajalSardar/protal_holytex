<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NettingStoreStock extends Model {
    //
    protected $guarded = ['id'];

    // public function nettingQty() {
    //     return $this->belongsTo(NettingQuotation::class, 'netting_quotation_id');
    // }
    // public function dyeingQty() {
    //     return $this->belongsTo(DyeingQuotation::class, 'dyeing_quotation_id');
    // }
    public function yarnQuotations() {
        return $this->hasMany(YarnQuotation::class, 'po_number', 'po_number')
            ->whereColumn('style', 'style');
    }
}
