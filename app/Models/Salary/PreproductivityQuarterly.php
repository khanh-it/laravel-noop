<?php

namespace App\Models\Salary;

use Illuminate\Database\Eloquent\Builder;
use App\Models;

/**
 * @class PreproductivityQuarterly
 */
class PreproductivityQuarterly extends Models\Salary
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
            $builder->where(static::columnName('type'), static::TYPE_50);
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
        $this->typePreproductivityQuarterly();
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
        //
        $dfd = static::jqxGridDatafieldByCol($col);
        if ('month_year' === $dfd) {
            $col['text'] = 'Quý / Năm';
        }
        return $col;
    }

    /**
     * Get salary details.
     * @return mixed
     */
    public function salaryDetails()
    {
        return $this->hasMany(PreproductivityQuarterlyDetail::class, PreproductivityQuarterlyDetail::columnName('salary_id'));
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

    /**
     * Calculate quarterly fees
     * @param array $options An array of options
     * @return array
     */
    public function calQuarterlyFees(array $options = array())
    {
        $quarterlyFees = [];
        $salaryConfig = $this->salaryConfig;
        if ($salaryConfig) {
            $quarterlyFees = [
                0 => 0,
                1 => $quarterlyFee1st = $salaryConfig->colVal('quarterly_fee_1st'),
                2 => $quarterlyFee2nd = $salaryConfig->colVal('quarterly_fee_2nd'),
                3 => $quarterlyFee3rd = $salaryConfig->colVal('quarterly_fee_3rd'),
                4 => $quarterlyFee4th = $salaryConfig->colVal('quarterly_fee_4th')
            ];
            $quarterlyFees[0] = $quarterlyFees[1 * $this->colVal('month')];
        }
        //
        return $quarterlyFees;
    }
}