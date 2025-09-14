<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDelivered extends Model {
    //
    protected $guarded = ['id'];
    public function garmentsFactory() {
        return $this->hasOne(GarmentsFactroy::class, 'id', 'garments_factory_id');
    }
}
