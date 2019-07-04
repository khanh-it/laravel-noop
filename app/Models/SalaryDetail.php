<?php

namespace App\Models;

/**
 * @class SalaryDetail
 */
class SalaryDetail extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_salary_detail';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'salary_detail_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'salary_detail_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'salary_detail_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    // const STATUS_COLUMN = 'salary_detail_status';

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'salary_detail_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'salary_detail_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [];

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    // public static function jqxGridCol($col) { return $col; }

    /**
     * Get salary.
     * @return Salary|null
     */
    public function salary()
    {
        return $this->belongsTo(Salary::class, $this->columnName('salary_id'));
    }

    /**
     * Get the department model.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, $this->columnName('department_id'));
    }

    /**
     * Get/set salary sums.
     * @return mixed
     */
    public function salarySums()
    {
        return $this->hasMany(SalarySum::class, SalarySum::columnName('salary_detail_id'));
    }

    /**
     * Get/set salary pays.
     * @return mixed
     */
    public function salaryPays()
    {
        return $this->hasMany(SalaryPay::class, SalaryPay::columnName('salary_detail_id'));
    }

    /**
     * Get the account model.
     */
    public function account()
    {
        return $this->belongsTo(Account::class, $this->columnName('account_id'));
    }

    /**
     * 
     * @param SalaryConfig $salaryConfig
     * @param Account $account
     * @param array $salaryTSInfo
     * @param array $options
     * @return this
     */
    public function assignSalaryAndTimeSheetInfo(SalaryConfig $salaryConfig, Account $account, array $salaryTSInfo, array $options = [])
    {
        // Get, format input(s)
        // +++ T.Tin cham cong theo tung nhan vien
        $timeSheetInfo = (array)$salaryTSInfo['time_sheet'][$accId = $account->id()];
        // +++ || chi tiet
        $tsInfoDetailsDOW = (array)$timeSheetInfo['_detailsDOW'];

        // Set relationshiops
        $this->account()->associate($account);

        // Thong tin salary configs
        // +++ Muc luong toi da dong BHYT, BHXH, KPCĐ
        $maximumInsurrance = (1 * $salaryConfig->colVal('maximum_insurrance'));

        // Self update salary detail info
        $colPrefix = static::$columnPrefix;
        $slrdInfo = [
            // salary info
            ($colPrefix . ($col = 'salary_scale_level')) => ($salaryScaleLevel = $account->getSlrScaleLevelRate()),
            ($colPrefix . ($col = 'salary_department_scale_level')) => $account->getDepSlrScaleLevelRate(),
            ($colPrefix . ($col = 'salary_basic')) => (1 * $account->colVal($col)),
            ($colPrefix . ($col = 'salary_fixed')) => (1 * $account->colVal($col)),
            ($colPrefix . ($col = 'salary_insurance')) => (1 * $account->colVal($col)),
            ($colPrefix . ($col = 'salary_minimum_time')) => (1 * $account->colVal($col)),
            ($colPrefix . ($col = 'salary_quarterly_productivity')) => (1 * $account->colVal($col)),
            ($colPrefix . ($col = 'salary_year_productivity')) => (1 * $account->colVal($col)),
            ($colPrefix . ($col = 'salary_products')) => (1 * $account->colVal($col)),
            ($colPrefix . ($col = 'salary_monthly_coefficient')) => (1 * $account->colVal($col)),
            ($colPrefix . ($col = 'salary_responsibility_allowance_coefficient')) => (1 * $account->colVal($col)),
            ($colPrefix . ($col = 'salary_responsibility_allowance')) => (1 * $account->colVal($col)),
            ($colPrefix . ($col = 'salary_dependents_number')) => (1 * $account->colVal($col)),
            ($colPrefix . ($col = 'salary_tax')) => (1 * $account->colVal($col)),
            ($colPrefix . ($col = 'salary_monthly_productivity_rate')) => (1 * $account->colVal($col)),
            ($colPrefix . ($col = 'salary_additional_corporate')) => (1 * $account->colVal($col)),
            // timesheet info
            ($colPrefix . ($col = 'time_worktime')) => (1 * $timeSheetInfo['worktime']),
            ($colPrefix . ($col = 'time_worktime_wkd')) => (1 * $tsInfoDetailsDOW['worktime']['Wkd']),
            ($colPrefix . ($col = 'time_worktime_sat')) => (1 * $tsInfoDetailsDOW['worktime']['Sat']),
            ($colPrefix . ($col = 'time_worktime_sun')) => (1 * $tsInfoDetailsDOW['worktime']['Sun']),
            ($colPrefix . ($col = 'time_worktime_hol')) => (1 * $tsInfoDetailsDOW['worktime']['Hol']),
            ($colPrefix . ($col = 'time_ot')) => (1 * $timeSheetInfo['ot']),
            ($colPrefix . ($col = 'time_ot_wkd')) => (1 * $tsInfoDetailsDOW['ot']['Wkd']),
            ($colPrefix . ($col = 'time_ot_sat')) => (1 * $tsInfoDetailsDOW['ot']['Sat']),
            ($colPrefix . ($col = 'time_ot_sun')) => (1 * $tsInfoDetailsDOW['ot']['Sun']),
            ($colPrefix . ($col = 'time_ot_hol')) => (1 * $tsInfoDetailsDOW['ot']['Hol']),
            ($colPrefix . ($col = 'time_ot_no_salary')) => (1 * $timeSheetInfo['ot_no_salary']),
            ($colPrefix . ($col = 'time_lv_worktime')) => (1 * $timeSheetInfo['leave_worktime']),
            ($colPrefix . ($col = 'time_lv_sick')) => (1 * $timeSheetInfo['leave_sick']),
            ($colPrefix . ($col = 'time_lv_maternity')) => (1 * $timeSheetInfo['leave_maternity']),
        ];
        $this->updateSalaryDetailInfo($slrdInfo);
        //.end
        //
        return $this;
    }

    /**
     * Get the create account.
     */
    public function createAccount()
    {
        return $this->belongsTo(Account::class, $this->columnName('created_by'));
    }
    
    /**
     * Find records match
     * @param int|string|array $id Record ids
     * @param int|string $slrId Salary id
     * @return mixed
     */
    public static function findMatchSalary($id, $slrId)
    {
        $model = app()->make(static::class);
        $isArr = \is_array($id);
        $id = (array)$id;
        $query = static
            ::whereIn($model->getKeyName(), $id)
            ->where(static::columnName('salary_id'), $slrId)
        ;
        $return = $isArr ? $query->get() : $query->first();
        return $return;
    }

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        // Salary info columns
        "salary_detail_salary_scale_level",
        "salary_detail_salary_department_scale_level",
        "salary_detail_salary_basic",
        "salary_detail_salary_fixed",
        "salary_detail_salary_insurance",
        "salary_detail_salary_minimum_time",
        "salary_detail_salary_quarterly_productivity",
        "salary_detail_salary_year_productivity",
        "salary_detail_salary_products",
        "salary_detail_salary_monthly_coefficient",
        "salary_detail_salary_responsibility_allowance_coefficient",
        "salary_detail_salary_responsibility_allowance",
        "salary_detail_salary_dependents_number",
        "salary_detail_salary_tax",
        "salary_detail_salary_monthly_productivity_rate",
        "salary_detail_salary_worktime_wkd",
        "salary_detail_salary_worktime_sat",
        "salary_detail_salary_worktime_sun",
        "salary_detail_salary_worktime_hol",
        "salary_detail_salary_ot_wkd",
        "salary_detail_salary_ot_sat",
        "salary_detail_salary_ot_sun",
        "salary_detail_salary_ot_hol",
        "salary_detail_salary_basic_final",
        "salary_detail_salary_fixed_final",
        "salary_detail_salary_insurance_final",
        "salary_detail_salary_additional_corporate",
        "salary_detail_salary_additional",
        "salary_detail_salary_additional_subtotal",
        "salary_detail_salary_deduction_social",
        "salary_detail_salary_deduction_advance",
        "salary_detail_salary_deduction_pit",
        "salary_detail_salary_deduction_insurance",
        "salary_detail_salary_deduction_others",
        "salary_detail_salary_subtotal",
        "salary_detail_salary_pit",
        "salary_detail_salary_account_reduction_pit",
        "salary_detail_salary_account_reduction_dependents_number",
        "salary_detail_salary_account_reduction_dependents_amount",
        "salary_detail_salary_account_reductions",
        "salary_detail_pit",
        "salary_detail_salary_assessment_quarterly",
        "salary_detail_salary_titles_id",//id danh hiệu xếp loại
        // Time info columns
        "salary_detail_time_worktime",
        "salary_detail_time_worktime_wkd",
        "salary_detail_time_worktime_sat",
        "salary_detail_time_worktime_sun",
        "salary_detail_time_worktime_hol",
        "salary_detail_time_ot",
        "salary_detail_time_ot_wkd",
        "salary_detail_time_ot_sat",
        "salary_detail_time_ot_sun",
        "salary_detail_time_ot_hol",
        "salary_detail_time_ot_no_salary",
        "salary_detail_time_lv_worktime",
        "salary_detail_time_lv_sick",
        "salary_detail_time_lv_maternity",
        // 
        "salary_detail_note"
    ];

    /**
     * Self update record's salary info
     * @param array $slrdItem Salary info item
     * @return bool
     */
    public function updateSalaryDetailInfo(array $slrdInfo)
    {
        $result = !!$this->fill($slrdInfo);
        return $result;
    }

    /**
     * 
     * @param string $colName
     * @param float $slrpc Ti le % huong luong
     * @return double
     */
    public function timeColWithSlrPc($colName, $slrpc)
    {
        return (1 * $this->colVal($colName)) * ((is_null($slrpc) ? 100 : (1 * $slrpc)) / 100);
    }

    /**
     * save update time_worktime time sheet
     * @param int|string $month
     * @param int|string $year
     * @param int|string $departmentid
     * @param array $options
     * @return array
     */
    // public static function updateWorkTime($salary_id,$department_id,$account_id,$timeWork)
    // {
    //     // Get, format input()
    //     // +++ 
    //     $query = static::whereRaw(1)
    //         ->where(static::columnName('salary_id'), $salary_id)
    //         ->where(static::columnName('department_id'),$department_id)
    //         ->where(static::columnName('account_id'),$account_id)
    //         ->update([
    //             'salary_detail_time_worktime' => $timeWork
    //         ]);
    //     // dd($return);
    //     // Return
    //     return $query;
    // }

    /**
     * Report details by time
     * hiệ thì bảng lương từ tháng năm tới tháng năm
     * @param array $options An array of options
     * @return Collection
     */
    public function fetchReportDataByTime(array $options, &$qB = null, &$totalRowsQB = null)
    {
        // Define vars
        $pdo = \DB::connection()->getPdo();
        //Roles
        $roles = Roles::checkRoles();
        $permissons = Permissions::checkPermissons();
        $userID = \Auth::id();
        //danh sách phòng ban
        if($roles['rolesHeadDepartment']){
            $departmentList = Department::makeList(['rolesHead' => true]);
        }

        // +++ relatives
        $models = [
            'dept' => app()->make(Department::class),
            'acc' => app()->make(Account::class),
            'slr' => app()->make(Salary::class),
        ];
        $tables = [
            'dept' => $models['dept']->getTable(),
            'acc' => $models['acc']->getTable(),
            'slr' => $models['slr']->getTable(),
            'slrD' => $this->getTable()
        ];
        $cols = [
            ($prop = 'month') => $models['slr']->columnName($prop),
            ($prop = 'year') => $models['slr']->columnName($prop),
        ];

        if ($options['summary']) {
            $tableSelect = [
                "{$tables['dept']}.department_id",
                "{$tables['dept']}.department_code",
                "{$tables['dept']}.department_name",
            ];
        }else{
            $tableSelect = [
                "{$tables['acc']}.account_id",
                "{$tables['acc']}.account_code",
                "{$tables['acc']}.account_fullname",
            ];
        }

        $select = array_merge($tableSelect,[
            // salary
            "{$tables['slr']}.salary_id",
            "{$tables['slr']}.salary_department_id",
            "{$tables['slr']}.salary_month",
            "{$tables['slr']}.salary_year",
            // salary details
            "{$tables['slrD']}.*",
        ]);

        $qB = static::whereRaw(1)
            ->select($select)
            // Join salary
            ->join(
                $tables['slr']
                , ("{$tables['slr']}." . ($pK = $models['slr']->getKeyName()))
                , '=', "{$tables['slrD']}." . $this->columnName($pK)
            );

        if ($options['summary']) {
            // Join department
            $qB->join(
                $tables['dept']
                , ("{$tables['dept']}." . ($pK = $models['dept']->getKeyName()))
                , '=', "{$tables['slrD']}." . $this->columnName($pK)
            );

            if(!$permissons['permissonsAdmin']){
                //phân quyền trưởng phòng 
                if($roles['rolesHeadDepartment']){
                    //array id phong ban của trưởng phòng
                    $depArrID = array_keys($departmentList);
                    $qB->whereIn(("{$tables['dept']}." . $pK),$depArrID);
                }
            }

        }else{
            // Join account
            $qB->join(
                $tables['acc']
                , ("{$tables['acc']}." . ($pK = $models['acc']->getKeyName()))
                , '=', "{$tables['slrD']}." . $this->columnName($pK)
            );
            if(!$permissons['permissonsAdmin']){
                //phân quyền nhân viên
                if($roles['rolesStaff']){
                    $qB->where(("{$tables['acc']}." . ($pK = $models['acc']->getKeyName())),$userID);
                }

                //phân quyền trưởng phòng 
                if($roles['rolesHeadDepartment']){
                    //array id phong ban của trưởng phòng
                    $depArrID = array_keys($departmentList);
                    $qB->whereIn(("{$tables['acc']}." . $models['acc']->columnName('department_id')),$depArrID);
                }
            }
            //SẮP XẾP THEO CHỨC DANH
            $qB->orderBy("{$tables['acc']}.account_position_id", 'ASC');
        }
        // Filters
        // +++ 
        if (!is_null($options['salary_type'])) {
            $qB->whereIn($models['slr']->columnName('type'), (array)$options['salary_type']);
        }
        // +++ 
        if ($options['salary_department_id']) {
            $depEntPKs = array_keys(Department::findAllDescendant($slrDepId = $options['salary_department_id']));
            $depEntPKs[] = $slrDepId;
            $qB->whereIn("{$tables['slr']}." . $models['slr']->columnName('department_id'), $depEntPKs);
            // if($options['summary']){
            //     $qB->where("{$tables['dept']}.department_parent_id",$slrDepId)
            //     ->whereNull("{$tables['dept']}.department_deleted_at");//lấy phòng ban không khoán,và ban ko bị xóa
            // }
            unset($slrDepId, $depEntPKs);
        }
        // +++ time (month/year) from
        if ($options['salary_month_fr'] && $options['salary_year_fr']) {
            $month = 1 * $options['salary_month_fr'];
            $year = 1 * $options['salary_year_fr'];
            $qB->whereRaw("(`{$cols['year']}` * 100 + `{$cols['month']}`) >= " . $pdo->quote($year * 100 + $month, \PDO::PARAM_INT));
            unset($year, $month);
        }
        // +++ time (month/year) to
        if ($options['salary_month_to'] && $options['salary_year_to']) {
            $month = 1 * $options['salary_month_to'];
            $year = 1 * $options['salary_year_to'];
            $qB->whereRaw("(`{$cols['year']}` * 100 + `{$cols['month']}`) <= " . $pdo->quote($year * 100 + $month, \PDO::PARAM_INT));
            unset($year, $month);
        }
        // die($qB->toSql());
        
        // Get, format data
        $data = [];
        // +++ list of columns (datafields used in report)
        $dfds = [];
        foreach (static::$jqxGridColumns as $col) {
            $col = static::jqxGridDatafieldByCol($col);
            if (strpos($col, 'id') !== false) {
                continue;
            }
            $dfds[] = $col;
        } // dd($dfds);

        $id_tbale = $options['summary'] ? 'department_id' : 'account_id';
        $slrSums = $options['salarySums'];

        if($slrSums){
            $qB->with('salarySums');
        }
        
        $rows = $qB->get()->filter(function($row, $idx) use (&$data, $dfds, $id_tbale, $slrSums) {

            if($slrSums){
                foreach ($row->salarySums as $slrSum) {
                    if (is_null($slrSum->colVal('for_salary_id'))) {
                        $row->{'TYPE_' . $slrSum->colVal('salary_type')} = $slrSum->colVal('salary_subtotal');
                    }
                }
                unset($row->salarySums);
            }

            $ent = $data[$accId = $row->colVal($id_tbale)];
            if (!$ent) {
                $data[$accId] = ($ent = $row);
                return true;
            }
            
            foreach ($dfds as $col) {
                $valRow = $row->colVal($col)?:$row[$col];
                $valEnt = $ent->colVal($col)?:$ent[$col];

                if (is_numeric($valRow)/*  && is_numeric($valEnt) || (is_null($valRow) || is_null($valEnt)) */) {
                    if($slrSums){
                        switch ($col) {

                            case "TYPE_0":
                            case "TYPE_82":
                            case "TYPE_1":
                            case "TYPE_50":
                            case "TYPE_60":
                            case "TYPE_20":
                            case "TYPE_30":
                            case "TYPE_40":
                            case "TYPE_70":
                            case "TYPE_100":
                                $ent[$col] = $valEnt + $valRow;
                                break;
                            default:
                                $ent->setColVal($col, $valEnt + $valRow);
                        }
                    }else {
                        $ent->setColVal($col, $valEnt + $valRow);
                    }
                    
                }
            }
            unset($valEnt, $valRow);
            return false;
        });
        //.end
        unset($data);
        // dd($rows->toArray());
        
        // Return
        return $rows;
    }
}