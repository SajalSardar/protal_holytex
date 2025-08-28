<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DyeingQuotation extends Model {

    protected $guarded = ['id'];

    public function dyeingFactory() {
        return $this->hasOne(DyeingFactroy::class, 'id', 'dyeing_factory_id');
    }

    public function garmentsFactory() {
        return $this->hasOne(GarmentsFactroy::class, 'id', 'delivery_point_id');
    }


    public function dyeingReceiveGarments() {
        return $this->hasMany(NettingReceivedGarments::class, 'dyeing_quotation_id');
    }
    public function dyeingStoreStock() {
        return $this->hasMany(NettingStoreStock::class, 'dyeing_quotation_id');
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
