<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DyedQuotation extends Model {

    protected $guarded = ['id'];
    public function dyedFactory() {
        return $this->hasOne(DyedFactory::class, 'id', 'dyed_factory_id');
    }

    public function nettingFactory() {
        return $this->hasOne(NettingFactroy::class, 'id', 'delivery_point_id');
    }


}
