<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessoriesQuotation extends Model {
    //
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
}
