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
    public function garmentsFactory() {
        return $this->hasOne(GarmentsFactroy::class, 'id', 'delivery_point_id');
    }

    public function approvedBy() {
        return $this->hasOne(User::class, 'id', 'approved_by');
    }
    public function creator() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function lastUpdateBy() {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

}
