<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YarnReceived extends Model {
    //
    protected $guarded = ['id'];

    public function yarnStore() {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }
}
