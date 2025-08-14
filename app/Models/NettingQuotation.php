<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NettingQuotation extends Model {
    //
    protected $guarded = ['id'];

    public function dyeingFactory() {
        return $this->hasOne(DyeingFactroy::class, 'id', 'delivery_point_id');
    }
    public function nettingFactory() {
        return $this->hasOne(NettingFactroy::class, 'id', 'netting_factory_id');
    }

}
