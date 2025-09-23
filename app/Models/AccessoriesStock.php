<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessoriesStock extends Model {
    //
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'challan_date'  => 'datetime',
        'received_date' => 'datetime',
    ];

    public function accessoriesQty() {
        return $this->belongsTo(AccessoriesQuotation::class, 'accessories_quotation_id');
    }
}
