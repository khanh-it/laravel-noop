<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class TimeSheet
 */
class TimeSheet extends AbstractModel
{
    // use SoftDeletes;

    /**
     * Helper: round worktime
     * @param float $val The value to round
     * @param float $precision The optional number of decimal digits to round to.
     * @return float
     */
    public static function round($val, $precision = 3)
    {
        return \round($val, $precision);
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hr_time_sheet';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'time_sheet_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'time_sheet_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const DELETED_AT = 'time_sheet_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    // const STATUS_COLUMN = 'time_sheet_status';

    /** @var string type 0 (tinh theo buoi) */
    const TYPE_0 = 0;
    /** @var string type 1 (tinh theo gio) */
    const TYPE_1 = 1;
    /**
     * Return type list
     * @return void
     */
    public static function typeList() {
        $list = [
            // static::TYPE_0 => 'Chấm công theo buổi',
            static::TYPE_1 => 'Chấm công theo giờ',
        ];
        return $list;
    }
    /**
     * set type 0
     * @return void
     */
    public function typeDay()
    {
        $this->time_sheet_type = static::TYPE_0;
        return $this;
    }
    /**
     * set type 1
     * @return void
     */
    public function typeHour()
    {
        $this->time_sheet_type = static::TYPE_1;
        return $this;
    }
    /**
     * is type 0
     * @return bool
     */
    public function isTypeDay()
    {
        return $this->time_sheet_type == static::TYPE_0;
    }
    /**
     * is type 1
     * @return bool
     */
    public function isTypeHour()
    {
        return $this->time_sheet_type == static::TYPE_1;
    }

     /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'time_sheet_id';

    /**
     * Get time_sheet_from data
     * @return string
     */
    public function timeSheetFrom()
    {
        $value = $this->time_sheet_from;
        if (!$value) {
            $value = "{$this->time_sheet_year}-{$this->time_sheet_month}-01 00:00:00";
        }
        return $value;
    }
    /**
     * Get time_sheet_to data
     * @return string
     */
    public function timeSheetTo()
    {
        $value = $this->time_sheet_to;
        if (!$value) {
            $value = strtotime("{$this->time_sheet_year}-{$this->time_sheet_month}-01");
            $value = date('Y-m-t 23:59:59', $value);
        }
        return $value;
    }
    /**
     * Get time_sheet from-to data
     * @param array $options An array of options
     * @return \DatePeriod
     */
    public function timeSheetPeriod(array $options = array())
    {
        $from = new \DateTime($this->timeSheetFrom());
        $to = new \DateTime($this->timeSheetTo());
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($from, $interval, $to);
        if ($options['as_array']) {
            $newPeriod = $period;
            $period = [];
            foreach ($newPeriod as $idx => $dt) {
                $period[$idx] = $dt;
            }
        }
        return $period;
    }

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'time_sheet_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [
            'text' => 'Tháng',
            'datafield' => 'month',
            'width' => 80,
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Năm',
            'datafield' => 'year',
            'width' => 80,
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Tên bảng chấm công',
            'datafield' => 'name',
            'minwidth' => 256,
        ],
        [ 'datafield' => 'type' ],
        [
            'text' => 'Quản lý thời gian theo',
            'datafield' => 'type_text',
            'width' => 164,
            'filtertype' => 'list',
        ],
        [ 'datafield' => 'department_id' ],
        [
            'text' => 'Đơn vị',
            'datafield' => [ 'department_id_text' ],
            'minwidth' => 256,
            'sortable' => false,
        ],
        [
            'text' => 'Ghi chú',
            'sortable' => false,
            'filterable' => false,
            'minwidth' => 256,
            'datafield' => 'note',
        ],
        // [ 'datafield' => 'created_by' ],
        // [ 'datafield' => 'created_at' ],
        // [ 'datafield' => 'updated_at' ],
        // [ 'datafield' => 'deleted_by' ],
        // [ 'datafield' => 'deleted_at' ],
    ];

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        $dfd = static::jqxGridDatafieldByCol($col);
        if ('type_text' === $dfd) {
            $col['filteritems'] =  array_values(static::typeList());
        }
        if ('department_id_text' === $dfd) {
            $col['filteritems'] = array_values(Department::makeList());
            $col['filtertype'] = 'list';
        }
        return $col;
    }

    /**
     * Get the create account.
     */
    public function createAccount()
    {
        return $this->belongsTo(Account::class, static::columnName('created_by'));
    }

    /**
     * Get the delete account.
     */
    public function deleteAccount()
    {
        return $this->belongsTo(Account::class, static::columnName('deleted_by'));
    }

    /**
     * Get time sheet details
     * @return mixed
     */
    public function tsDetails()
    {
        return $this->hasMany(TimeSheetDetail::class, TimeSheetDetail::columnName('time_sheet_id'));
    }

    /**
     * Get records simple as list
     * @param array $options An array of options
     * @return mixed
     */
    public static function makeListCompany(array $options = array())
    {
        return Company::makeList($options);
    }

    /**
     * Make a list of months in year
     * @param array $options An array of options
     * @return array
     */
    public static function makeListMonth(array $options = array())
    {
        $return = [];
        for ($i = 1; $i <= 12; $i++) {
            $return[$i] = "Tháng {$i}";
        }
        return $return;
    }

    /**
     * Make a list of years
     * @param array $options An array of options
     * @return array
     */
    public static function makeListYear(array $options = array())
    {
        $return = [];
        $curY = intval(date('Y'));
        for ($i = $curY; $i >= $curY - 5; $i--) {
            $return[$i] = "{$i}";
        }
        return $return;
    }

    /**
     * @TODO: 
     * @param array $options An array of options
     * @return array
     */
    public function populateTimeSheetDetails(array $options = array())
    {
        // @TODO: ghi nhan thong tin ca (buoi) lam viec!
        // ...
        // 
        $count = 0;
        $accounts = Account::findAllByOrganizationUnit($this->colVal('department_id'));
        if (!empty($accounts))
        {
            //thông tin tháng năm bảng lương
            $date = $this->colVal('year').'-'.$this->colVal('month');
            foreach ($accounts as $account)
            {
                // kiểm tra nhân viên nghỉ lamf
                if($account['account_end_time'] > strtotime($date) || $account['account_status'] == 1){
                    $tsdModel = app()->make(TimeSheetDetail::class);
                    $tsdModel->setAccount($account);
                    $this->tsDetails()->save($tsdModel);
                    $tsdModel->populateTimeSheetItems();
                    ++$count;
                }
            }
        }
        return $count;
    }

    /**
     * 
     * 
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(array $data, &$qB = null, &$totalRowsQB = null)
    {
        // Define vars
        // +++
        $departmentList = Department::makeList();
        $departmentListFlip = array_flip($departmentList);
        // +++
        $typeList = static::typeList();
        $typeListFlip = array_flip($typeList);
        //Roles
        $roles = Roles::checkRoles();
        $permissons = Permissions::checkPermissons();
        $userIDDep = \Auth::User()->account_department_id;
		// Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB) 
            use ($roles, $departmentList, $userIDDep, $permissons){
                $models = [
                    // 'com' => app()->make(Company::class),
                    'dep' => app()->make(Department::class),
                ];
                $modelTable = $this->getTable();
                /* // Join company
                $qB->leftJoin(
                    ($tableCom = $models['com']->getTable())
                    , ("{$tableCom}." . ($pK = $models['com']->getKeyName()))
                    , '=', "{$modelTable}." . $this->columnName($pK)
                );
                // Join department
                $qB->leftJoin(
                    ($tableDep = $models['dep']->getTable())
                    , ("{$tableDep}." . ($pK = $models['dep']->getKeyName()))
                    , '=', "{$modelTable}." . $this->columnName($pK)
                ); */
                if(!$permissons['permissonsAdmin']){
                    //kiểm tra quyền nhân viên
                    if($roles['rolesStaff']){
                        $qB->where("{$modelTable}.".$this->columnName('department_id'), $userIDDep);
                    }
                    //kiểm tra quyền trưởng phòng
                    if($roles['rolesHeadDepartment']){
                        //array id phong ban của trưởng phòng
                        $depArrID = array_keys($departmentList);
                        $qB->whereIn("{$modelTable}.".$this->columnName('department_id'), $depArrID);
                    }
                }

                // Select
                $qB->select([
                    "{$modelTable}.*",
                    // "{$tableCom}.company_name",
                    // "{$tableDep}.department_name",
                ]);
            },
            // Alter where conditions
            'where' => function(&$join, &$where, &$filter, &$condition, &$value)
                use ($departmentListFlip, $typeListFlip) {
                    // +++
                    if (($prop = $this->columnName('department_id')) . '_text' === $filter['field']) {
                        $value = $filter['value'] = $departmentListFlip[$value];
                    }
                    // +++
                    if (($prop = static::columnName('type')) . '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $typeListFlip[$value];
                    }
                }
        ]);
        // var_dump($data);die($qB->toSql());
		// Format data
		// +++
        $rows = $qB->get()->map(function($row, $idx)
            use ($departmentList, $typeList) {
                $prop;
                $row->setColVal(($prop = 'department_id') . '_text', $departmentList[$row->colVal($prop)]);
                $row->setColVal(($prop = 'type') . '_text', $typeList[$row->colVal($prop)]);
                //
                return $row;
            })
        ;
        //.end
        
        // Return
        return $rows;
    }
    
    /**
     * Get salary info by time 
     * @param int|string $month
     * @param int|string $year
     * @param array $options
     * @return array
     */
    public static function getSalaryInfoOfTime($month, $year, array $options = array())
    {
        // Get, format input()
        // +++ 
        $accountIds = [];
        $collect = Account::findAllByOrganizationUnit($options['department_id'], [
            /**
             * Alter query builder
             * @return void
             */
            'alter_query' => function($_qB) {
                $_qB->select(Account::columnName('id'));
            }
        ]);
        if ($collect) {
            $accountIds = $collect->pluck(Account::columnName('id'))->toArray();
        }
        unset($collect);
        //.end

        // Build query
        // +++
        $models = [
            'ts' => app()->make(TimeSheet::class),
            'tsDetails' => app()->make(TimeSheetDetail::class)
        ];
        $tblTs = $models['ts']->getTable();
        $tblTsDetails = $models['tsDetails']->getTable();
        // +++
        $qB = TimeSheetDetail::select([
                "{$tblTsDetails}.*"
            ])
            // ->with(['tsDetails', 'tsDetails.tsItems', 'tsDetails.tsItems.tsSign'])
            ->with('tsItems')->with('tsItems.tsSign')
            ->join(
                ($tblTs)
                , ("{$tblTs}." . ($pK = $models['ts']->getKeyName()))
                , '=', "{$tblTsDetails}." . $models['tsDetails']->columnName($pK)
            )
            ->where("{$tblTs}." . static::columnName('month'), $month)
            ->where("{$tblTs}." . static::columnName('year'), $year)
            // ->where(static::columnName('department_id'), $options['department_id'])
            ->whereIn(TimeSheetDetail::columnName('account_id'), $accountIds)
            ->orderBy("{$tblTs}." . static::columnName('created_at'), 'ASC')
        ;
        // die($qB->toSql());
        // Fetch data
        $tsDetails = $qB->get();

        // Xu ly: lay thong tin cham cong - options
        $wrkShiftOpts = [
            'department_id' => $options['department_id']
        ];
        $return = [
            'month' => $month, 'year' => $year,
            // @TODO: phai lay tu DB --> khong can tinh lai --> sai so lieu theo t.gian!
            'working_days_of_month' => (
                $workingDaysOfMonth = WorkingShift::workingDaysOfMonth($month, $year, $wrkShiftOpts)
            ),
            'working_hours_of_day' => (
                $workingHoursOfDay = WorkingShift::workingHoursOfDay($wrkShiftOpts)
            ),
            // Thong tin gio lam theo tung ca trong 1 ngay
            'working_shift_timeline' => (
                $workingShiftTimeline = WorkingShift::workingShiftTimeline($wrkShiftOpts)
            ),
            // Thong tin: cham cong
            'time_sheet' => [],
            // Thong tin: % ti le huong luong
            'tssign_avg_values' => [],
        ];
        // First timesheet
        $timeSheet = null;
        //
        if (count($tsDetails))
        {
            // First timesheet
            $timeSheet = $timeSheet ?: $tsDetails->first()->tSheet;
            // Xu ly: lay thong tin "% ti le huong luong" tu ky hieu cham cong?
            if ($options['fetch_tssign_avg_values']) {
                $return['tssign_avg_values'] = (array)TimeSheetSign::calAvgValues();
            }
            // Thong tin t.gian tren bang cham cong
            $dtPeriod = $timeSheet->timeSheetPeriod(['as_array' => true]);
            // Thong tin ngay le/tet
            $holidayInfo = Holiday::getHolidayInfoByTime(
                new \DateTime($timeSheet->timeSheetFrom()),
                new \DateTime($timeSheet->timeSheetTo())
            );
            //
            foreach ($tsDetails as $tsDetail) {
                $accId = $tsDetail->colVal('account_id');
                if ($return['time_sheet'][$accId]) {
                    continue;
                }
                $return['time_sheet'][$accId] = $tsDetail->getSalaryInfoOfTime([
                    'working_hours_of_day' => $workingHoursOfDay,
                    'working_shift_timeline' => $workingShiftTimeline,
                    'datetime_period' => $dtPeriod,
                    'holiday_info' => $holidayInfo,
                ]);
            }
            unset($timeSheet, $tsDetails, $tsDetail);
        }
        // dd($return);
        // Return
        return $return;
    }

    /**
     * Get detail time sheet
     * @param int|string $month
     * @param int|string $year
     * @param int|string $departmentid
     * @param array $options
     * @return array
     */
    public static function getTimeSheet(array $options = array())
    {
        // Get, format input()
        // +++ 
        $date = [];
        if($options['riceShift'] == true){
            
            $year_month = $options['year'].'-'.$options['month'];
            // First day of the month.
            $start_date = date('Y-m-01 00:00:00', strtotime($year_month));

            // Last day of the month.
            $last_date =  date('Y-m-t 23:59:59', strtotime($year_month));

            $from = new \DateTime($start_date);
            $to = new \DateTime($last_date);

            $interval = \DateInterval::createFromDateString('1 day');
            $period = new \DatePeriod($from, $interval, $to);
        }
        
        $query = static::whereRaw(1)
            ->where(static::columnName('month'), $options['month'])
            ->where(static::columnName('year'), $options['year'])
            ->where(static::columnName('department_id'), $options['departmentid'])
            ->max(static::columnName('id'));
  
        $params=[
            'query' => $query,
            'account_id' => $options['account_id'],
            'date' => $period,
            'riceShiftOT' => $options['riceShiftOT']
        ];
        $timeSheetDetail = TimeSheetDetail::getTimeSheetDetail( $params );
        // dd($return);
        // Return
        return $timeSheetDetail;
    }

    /**
     * Get month working in the year by user
     * @param array $options
     * @return array
     */
    public static function getMonthInYearByUser(array $options = array())
    {
        // Get, format input()
        // +++ 
        $models = [
            'ts' => app()->make(TimeSheet::class),
            'tsDetails' => app()->make(TimeSheetDetail::class)
        ];
        $tblTs = $models['ts']->getTable();
        $tblTsDetails = $models['tsDetails']->getTable();
        
        // +++
        $qB = static::whereRaw(1)
        ->select("{$tblTs}." . static::columnName('month'))
        ->join(
            ($tblTsDetails)
            , ("{$tblTs}." . ($pK = $models['ts']->getKeyName()))
            , '=', "{$tblTsDetails}." . $models['tsDetails']->columnName($pK)
        )->where("{$tblTs}." . static::columnName('year'), $options['year']);
        if($options['departmentid']){
            $qB = $qB->where("{$tblTs}." . static::columnName('department_id'), $options['departmentid']);
        }
        $qB = $qB->where(TimeSheetDetail::columnName('account_id'), $options['account_id'])
        ->orderBy("{$tblTs}." . static::columnName('month'), 'ASC')->get()->toArray();
        
        $arrMonth = [];
        if($qB){
            foreach($qB as $key){
                array_push($arrMonth,$key['time_sheet_month']);
            }
        }

        // dd($return);
        // Return
        return $arrMonth;
    }
}