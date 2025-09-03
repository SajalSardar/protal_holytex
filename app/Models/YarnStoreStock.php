<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YarnStoreStock extends Model {
    protected $guarded = ['id'];

    public function yarnQty() {
        return $this->belongsTo(YarnQuotation::class, 'yarn_quotation_id');
    }
}
