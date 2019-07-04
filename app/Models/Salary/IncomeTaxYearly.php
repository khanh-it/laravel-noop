<?php

namespace App\Models\Salary;

use Illuminate\Database\Eloquent\Builder;
use App\Models;

class IncomeTaxYearly extends Models\Salary
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
            $builder->where(static::columnName('type'), static::TYPE_127);
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
        $this->typeIncomeTaxYearly();
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
        // +++
        return $this->hasMany(IncomeTaxYearlyDetail::class, IncomeTaxYearlyDetail::columnName('salary_id'));
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
        // +++
        
        // Thong tin chi tiet
        if (!empty($accounts))
        {
            // Thong tin salary configs.
            $salaryConfig = $this->salaryConfig;
            // Detail model class
            $modelClass = get_class($this->salaryDetails()->getRelated());

            //
            foreach ($accounts as $account)
            {
                // $params = [
                //     'year' => $this->colVal('year'),
                //     'departmentid' => ($departmentId != 1)?:null ,
                //     'account_id' => $account->id(),
                // ];
                // // các tháng làm việc của nhân viên
                // $arrTsMonth = Models\TimeSheet::getMonthInYearByUser( $params );

                // +++
                $slrTSInfoOpts = [
                    'account_id' => $account->id(),
                    // 'monthTax'  => $arrTsMonth,
                    'account_salary_reductions'=>[]
                ];

                $salaryTSInfoArr = static::fetchTaxAccountAllSalary( $this->colVal('year'), $slrTSInfoOpts );
                $model = app()->make($modelClass);
                $model->save();
                $model->setColVal('salary_id', $this->id());
                $model->assignSalaryAndTimeSheetInfo(
                    $salaryConfig, $account,
                    (array)$salaryTSInfoArr,
                    $slrTSInfoOpts
                );
                $this->salaryDetails()->save($model);
            }
            unset($accId);
        }
        return count($accounts);
    }
}