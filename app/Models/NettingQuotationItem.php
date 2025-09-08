<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NettingQuotationItem extends Model {
    //
    protected $guarded = ['id'];

    public function garmentsFactory() {
        return $this->hasOne(GarmentsFactroy::class, 'id', 'delivery_point_id');
    }
    public function dyeingFactory() {
        return $this->hasOne(DyeingFactroy::class, 'id', 'delivery_point_id');
    }
}
