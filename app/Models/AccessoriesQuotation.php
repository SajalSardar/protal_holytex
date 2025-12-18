<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessoriesQuotation extends Model {
    //
    use SoftDeletes;
    protected $guarded = ['id'];

    public function approvedBy() {
        return $this->hasOne(User::class, 'id', 'approved_by');
    }
    public function creator() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function lastUpdateBy() {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function accessoriesReceived() {
        return $this->hasMany(AccessoriesReceived::class, 'accessories_quotation_id');
    }
    public function accessoriesLoss() {
        return $this->hasMany(AccessoriesLoss::class, 'accessories_quotation_id');
    }
    // public function accessoriesStoreStock() {
    //     return $this->hasMany(AccessoriesStock::class, 'accessories_quotation_id');
    // }

    public function storeAddress() {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }
}
