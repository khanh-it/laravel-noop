<?php

namespace App\Models\Salary;

use Illuminate\Database\Eloquent\Builder;
use App\Models;

class Fixed1st extends Models\Salary
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
            $builder->where(static::columnName('type'), static::TYPE_81);
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
        $this->typeFixed1st();
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
        return $col;
    }

    /**
     * Get salary details.
     * @return mixed
     */
    public function salaryDetails()
    {
        return $this->hasMany(Fixed1stDetail::class, Fixed1stDetail::columnName('salary_id'));
    }

    /**
     * Get last salary fixed details by time
     * @param int $month
     * @param int $year
     * @param array $options
     * @return array
     */
    public static function lastSalaryFixedDetailsByTime($month, $year, array $options = array())
    {
        // Create query builder
        $qB = static::with('salaryDetails')
            ->where(static::columnName('month'), $month)
            ->where(static::columnName('year'), $year)
            ->orderBy($colID = static::columnName('id'), 'DESC')
        ;
        // || Add filters
        if (isset($options['exclude'])) {
            $qB->whereNotIn($colID, (array)$options['exclude']);
        }
        // die($qB->toSql());

        // Fetch data
        $data = [];
        $slrFixed = $qB->first();
        if ($slrFixed) {
            $slrFixed->salaryDetails->map(function($slrDetailEnt) use (&$data) {
                $data[$slrDetailEnt->colVal('account_id')] = $slrDetailEnt;
            });
        }
        // dd($data);

        // Return
        return $data;
    }

    /**
     * Self populate details records
     * @param array $options An array of options
     * @return array
     */
    public function populateSalaryDetails(array $options = array())
    {
        // Get, format options
        $options['detail_options'] = $options['detail_options'] ?? [];
        // +++
        $options['detail_options']['last_salary_fixed_details'] = static::lastSalaryFixedDetailsByTime(
            $this->colVal('month'),
            $this->colVal('year'), 
            [
                'exclude' => $this->id()
            ]
        );
        //
        return parent::populateSalaryDetails($options);
    }
}
