<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DyedQuotation extends Model {
    use SoftDeletes;

    protected $guarded = ['id'];

    public function dyedFactory() {
        return $this->hasOne(DyedFactory::class, 'id', 'dyed_factory_id');
    }

    public function nettingFactory() {
        return $this->hasOne(NettingFactroy::class, 'id', 'delivery_point_id');
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

    public function dyedYarnknitQuot() {
        return $this->hasOne(NettingQuotation::class, 'dyed_quotation_id');
    }
    public function dyedYarnLoss() {
        return $this->hasMany(YarnLoss::class, 'dyed_quotation_id');
    }
    public function dyedYarnStock() {
        return $this->hasMany(YarnStoreStock::class, 'dyed_quotation_id');
    }

}
