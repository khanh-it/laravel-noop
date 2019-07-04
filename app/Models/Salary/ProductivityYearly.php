<?php

namespace App\Models\Salary;

use Illuminate\Database\Eloquent\Builder;
use App\Models;

/**
 * @class ProductivityYearly
 */
class ProductivityYearly extends Models\Salary
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where(static::columnName('type'), static::TYPE_70);
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
        $this->typeProductivityYearly();
        //
        return $return;
    }

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        $col = parent::jqxGridCol($col);
        // Bang luong nang suat nam, --> remove column 'thang'
        $dfd = static::jqxGridDatafieldByCol($col);
        if ('month' === $dfd) {
            $col['text'] = null;
        }
        return $col;
    }

    /**
     * Get salary details.
     * @return mixed
     */
    public function salaryDetails()
    {
        return $this->hasMany(ProductivityYearlyDetail::class, ProductivityYearlyDetail::columnName('salary_id'));
    }

    /**
     * Helper for populateSalaryDetails
     * @param array $options An array of options
     * @return mixed
     */
    public function psd_getSalaryInfoOfTime(array $options = array())
    {
        // Thong tin cham cong.
        $salaryTSInfo = [
            ($prop = 'month') => $this->colVal($prop),
            ($prop = 'year') => $this->colVal($prop),
            $prop = 'department_id' => $this->colVal($prop)
        ];
        //
        return $salaryTSInfo;
    }
}