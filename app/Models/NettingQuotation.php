<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NettingQuotation extends Model {
    //
    use SoftDeletes;
    protected $guarded = ['id'];

    // public function nettingQuotationItems() {
    //     return $this->hasMany(NettingQuotationItem::class, 'netting_quotation_id');
    // }

    public function garmentsFactory() {
        return $this->hasOne(GarmentsFactroy::class, 'id', 'delivery_point_id');
    }
    public function dyeingFactory() {
        return $this->hasOne(DyeingFactroy::class, 'id', 'delivery_point_id');
    }

    public function nettingFactory() {
        return $this->hasOne(NettingFactroy::class, 'id', 'netting_factory_id');
    }

    public function nettingReceived() {
        return $this->hasMany(NettingReceived::class, 'netting_quotation_id');
    }
    public function nettingReceiveGarments() {
        return $this->hasMany(NettingReceivedGarments::class, 'netting_quotation_id');
    }
    public function nettingLoss() {
        return $this->hasMany(NettingLoss::class, 'netting_quotation_id');
    }
    public function storeStock() {
        return $this->hasMany(NettingStoreStock::class, 'netting_quotation_id');
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
