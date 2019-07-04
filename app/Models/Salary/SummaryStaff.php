<?php

namespace App\Models\Salary;

use Illuminate\Database\Eloquent\Builder;
use App\Models;

/**
 * @class SummaryStaff
 */
class SummaryStaff extends Models\Salary
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
            $builder->where(static::columnName('type'), static::TYPE_110);
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
        $this->typeSummaryStaff();
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
        return $this->hasMany(SummaryStaffDetail::class, SummaryStaffDetail::columnName('salary_id'));
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
        $accounts = Models\Account::findAllByOrganizationUnit($departmentId = $this->colVal('department_id'));
        
        // Thong tin chi tiet
        if (!empty($accounts))
        {
            //thông tin tháng năm bảng lương
            $date = $this->colVal('year').'-'.$this->colVal('month');
            // Thong tin luong tong hop cua nhan vien.
            // +++ Map --> lay thong tin account's id.
            $accountIds = $accounts->map(function($var){
                return $var->id();
            })->toArray();
            // +++
            $slrTSInfoOpts = [
                'account_id' => $accountIds,
                'account_salary_reductions' => []
            ];
            $salaryTSInfoArr = static::fetchAccountAllSalary(
                $this->colVal('month'), $this->colVal('year'), $slrTSInfoOpts
            );
            unset($slrTSInfoOpts['account_id']);
            // Thong tin salary configs.
            $salaryConfig = $this->salaryConfig;
            // Detail model class
            $modelClass = get_class($this->salaryDetails()->getRelated());

            //
            foreach ($accounts as $account)
            {
                if($account['account_end_time'] > strtotime($date) || $account['account_status'] == 1){
                    $model = app()->make($modelClass);
                    $model->save();
                    $model->setColVal('salary_id', $this->id());
                    $model->assignSalaryAndTimeSheetInfo(
                        $salaryConfig, $account,
                        (array)$salaryTSInfoArr[$accId = $account->id()],
                        $slrTSInfoOpts
                    );
                    $this->salaryDetails()->save($model);
                }
                
            }
            unset($accId);
        }
        return count($accounts);
    }
}