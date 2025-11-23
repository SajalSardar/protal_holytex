<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YarnStoreStock extends Model {
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'challan_date'  => 'datetime',
        'received_date' => 'datetime',
    ];

    // public function yarnQty() {
    //     return $this->belongsTo(YarnQuotation::class, 'yarn_quotation_id');
    // }
    // public function usesStock() {
    //     return $this->hasMany(YarnReceived::class, 'stock_id', 'id');
    // }
    public function storeDetails() {
        return $this->belongsTo(Store::class,'store_id');
    }
}
