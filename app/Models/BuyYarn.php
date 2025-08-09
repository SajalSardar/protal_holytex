<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyYarn extends Model {
    //
    protected $guarded = ['id'];

    public function yarnFactory() {
        return $this->hasOne(YarnFactroy::class, 'id', 'yarn_factory_id');
    }
    public function nettingFactory() {
        return $this->hasOne(NettingFactroy::class, 'id', 'netting_factory_id');
    }
}
