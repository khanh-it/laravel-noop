<?php

namespace App\Models\Salary;

use Illuminate\Database\Eloquent\Builder;
use App\Models;

/**
 * @class BasicSummary
 */
class BasicSummary extends Models\Salary
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
            $builder->where(static::columnName('type'), static::TYPE_10);
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
        $this->typeBasicSummary();
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
        return $this->hasMany(BasicSummaryDetail::class, BasicSummaryDetail::columnName('salary_id'));
    }

    /**
     * Self populate details records
     * @param array $options An array of options
     * @return array
     */
    public function populateSalaryDetails(array $options = array())
    {
        // Fetch data
        $departments = Models\Department::findAllDescendant($departmentId = $this->colVal('department_id'));
        // +++

        // Thong tin chi tiet
        if (!empty($departments))
        {
            // Thong tin salary configs.
            $salaryConfig = $this->salaryConfig;
            // Detail model class
            $modelClass = get_class($this->salaryDetails()->getRelated());
            //
            foreach ($departments as $department)
            {
                // Thong tin bang luong theo don vi, +thoi gian
                $salaryRows = Basic::findByDepartmentAndTime(
                    $department->id(),
                    $this->colVal('month'),
                    $this->colVal('year'),
                    [ 'with_details' => true ]
                );
                //
                $model = app()->make($modelClass);
                $model->assignSalaryAndTimeSheetInfoSummary($department, $salaryRows);
                $model->save();
                $this->salaryDetails()->save($model);
            }
        }
        return count($departments);
    }
}