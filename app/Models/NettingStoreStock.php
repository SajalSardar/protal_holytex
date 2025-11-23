<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NettingStoreStock extends Model {

    use SoftDeletes;
    //
    protected $guarded = ['id'];

    protected $casts = [
        'challan_date'  => 'datetime',
        'received_date' => 'datetime',
    ];

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
    public function storeAddress() {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
