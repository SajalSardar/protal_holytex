<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessoriesStock extends Model {
    //
    protected $guarded = ['id'];

    public function accessoriesQty() {
        return $this->belongsTo(AccessoriesQuotation::class, 'accessories_quotation_id');
    }
}
