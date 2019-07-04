<?php

namespace App\Models\Salary;

use Illuminate\Database\Eloquent\Builder;
use App\Models;

/**
 * @class SummaryOrganize
 */
class SummaryOrganize extends Models\Salary
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
            $builder->where(static::columnName('type'), static::TYPE_120);
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
        $this->typeSummaryOrganize();
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
        return $this->hasMany(SummaryOrganizeDetail::class, SummaryOrganizeDetail::columnName('salary_id'));
    }

    /**
     * Self assign data
     * @return this
     */
    public function selfAssignData()
    {
        //
        // ...
        return $this;
    }

    /**
     * Self populate details records
     * @param array $options An array of options
     * @return array
     */
    public function populateSalaryDetails(array $options = array())
    {
        // Fetch data
        // +++ Thong tin luong tong hop cua don vi (cong ty / phong ban).
        $salaryTSInfoArr = static::fetchDepartmentAllSalary(
            $this->colVal('month'), $this->colVal('year'), [
                'department_id' => $departmentId = $this->colVal('department_id')
            ]
        );
        // +++ Thong tin don vi
        $departmentIds = array_keys($salaryTSInfoArr);
        $departments = Models\Department::find($departmentIds);

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
                $model = app()->make($modelClass);
                $model->save();
                $model->setColVal('salary_id', $this->id());
                $model->assignSalaryAndTimeSheetInfoSummary($salaryConfig, $department, (array)$salaryTSInfoArr[$department->id()]);
                $this->salaryDetails()->save($model);
            }
        }
        return count($departments);
    }
}