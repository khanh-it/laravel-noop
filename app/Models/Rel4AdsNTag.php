<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class Rel4AdsNTag
 */
class Rel4AdsNTag extends Rel
{
    /**
     * The "booting" method of the model.
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where(static::columnName('type'), static::TYPE_ADS_N_TAG);
        });
    }

    /**
     * Class's constructor
     */
    public function __construct()
    {
        // Call parent's constructor
        $return = parent::__construct();
        // Self init
        $this->setColVal('type', static::TYPE_ADS_N_TAG);
        //
        return $return;
    }
}
