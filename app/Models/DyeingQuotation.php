<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DyeingQuotation extends Model {

    use SoftDeletes;

    protected $guarded = ['id'];

    public function dyeingFactory() {
        return $this->hasOne(DyeingFactroy::class, 'id', 'dyeing_factory_id');
    }

    public function garmentsFactory() {
        return $this->hasOne(GarmentsFactroy::class, 'id', 'delivery_point_id');
    }

    public function dyeingGarmentsQuot() {
        return $this->hasMany(NettingReceivedGarments::class, 'dyeing_quotation_id')->where('fabric_type', 'dyeing');
    }
    public function dyeingLoss() {
        return $this->hasMany(NettingLoss::class, 'dyeing_quotation_id')->where('fabric_type', 'dyeing');
    }
    public function dyeingStock() {
        return $this->hasMany(NettingStoreStock::class, 'dyeing_quotation_id')->where('fabric_type', 'dyeing');
    }

    public function dyeingReceived() {
        return $this->hasOne(DyeingReceived::class);
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
