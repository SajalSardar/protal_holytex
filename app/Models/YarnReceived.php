<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YarnReceived extends Model {
    use SoftDeletes;
    protected $guarded = ['id'];

    public function yarnStore() {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }
    public function yarnFactory() {
        return $this->hasOne(YarnFactroy::class, 'id', 'yarn_factory_id');
    }

    public function receivedBy() {
        return $this->hasOne(User::class, 'id', 'received_by');
    }

    public function lastUpdateBy() {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
