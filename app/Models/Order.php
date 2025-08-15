<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $guarded = ['id'];

    public function orderDetails() {
        return $this->hasMany(OrderDetail::class);
    }

    public function yarnQuotations() {
        return $this->hasMany(YarnQuotation::class);
    }
    public function nettingQuotations() {
        return $this->hasMany(NettingQuotation::class);
    }
    public function dyeingQuotations() {
        return $this->hasMany(DyeingQuotation::class);
    }
    public function accessoriesQuotations() {
        return $this->hasMany(AccessoriesQuotation::class);
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
